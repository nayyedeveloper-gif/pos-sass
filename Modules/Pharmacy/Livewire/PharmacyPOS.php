<?php

namespace Modules\Pharmacy\Livewire;

use App\Models\Category;
use App\Models\Item;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Customer;
use App\Models\Shift;
use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\DB;

class PharmacyPOS extends Component
{
    public $categories = [];
    public $selectedCategory = null;
    public $items = [];
    public $cart = [];
    public $searchTerm = '';
    public $barcodeInput = '';
    
    // Shift Status
    public $hasOpenShift = false;
    public $showShiftModal = false;

    // Customer/Patient
    public $customer_id = null;
    public $customer = null;
    public $patientName = '';
    public $patientPhone = '';
    public $prescriptionNumber = '';
    
    // Payment
    public $subtotal = 0;
    public $discountPercentage = 0;
    public $discountAmount = 0;
    public $total = 0;
    public $paymentMethod = 'cash';
    public $amountReceived = 0;
    public $change = 0;
    
    // UI State
    public $showPaymentModal = false;
    public $showSuccessModal = false;
    public $showPrescriptionModal = false;
    public $completedOrderId = null;

    public function mount()
    {
        $this->checkShiftStatus();
        $this->loadCategories();
        $this->loadItems();
    }
    
    public function checkShiftStatus()
    {
        $this->hasOpenShift = Shift::where('user_id', auth()->id())
            ->where('status', 'open')
            ->exists();
            
        if (!$this->hasOpenShift) {
            $this->showShiftModal = true;
        }
    }

    #[On('shift-opened')]
    public function onShiftOpened()
    {
        $this->hasOpenShift = true;
        $this->showShiftModal = false;
    }

    public function loadCategories()
    {
        $this->categories = Category::where('tenant_id', auth()->user()->tenant_id)
            ->where('is_active', true)
            ->withCount(['items as active_items_count' => function ($query) {
                $query->where('is_active', true);
            }])
            ->orderBy('sort_order')
            ->get();
    }

    public function loadItems()
    {
        $query = Item::where('tenant_id', auth()->user()->tenant_id)
            ->where('is_active', true)
            ->with('category');

        if ($this->selectedCategory) {
            $query->where('category_id', $this->selectedCategory);
        }

        if ($this->searchTerm) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->searchTerm . '%')
                  ->orWhere('name_mm', 'like', '%' . $this->searchTerm . '%')
                  ->orWhere('barcode', 'like', '%' . $this->searchTerm . '%');
            });
        }

        $this->items = $query->orderBy('name')->get();
    }

    public function scanBarcode()
    {
        if (empty($this->barcodeInput)) return;

        $item = Item::where('tenant_id', auth()->user()->tenant_id)
            ->where('barcode', $this->barcodeInput)
            ->where('is_active', true)
            ->first();

        if ($item) {
            $this->addToCart($item->id);
        } else {
            session()->flash('error', 'ဆေးဝါး မတွေ့ပါ: ' . $this->barcodeInput);
        }

        $this->barcodeInput = '';
    }

    public function selectCategory($categoryId)
    {
        $this->selectedCategory = $categoryId;
        $this->loadItems();
    }

    public function updatedSearchTerm()
    {
        $this->loadItems();
    }

    public function addToCart($itemId)
    {
        $item = Item::find($itemId);
        if (!$item) return;

        $existingIndex = collect($this->cart)->search(fn($cartItem) => $cartItem['id'] === $itemId);

        if ($existingIndex !== false) {
            $this->cart[$existingIndex]['quantity']++;
            $this->cart[$existingIndex]['total'] = $this->cart[$existingIndex]['quantity'] * $this->cart[$existingIndex]['price'];
        } else {
            $this->cart[] = [
                'id' => $item->id,
                'name' => $item->name,
                'name_mm' => $item->name_mm,
                'price' => $item->price,
                'quantity' => 1,
                'total' => $item->price,
                'requires_prescription' => $item->requires_prescription ?? false,
            ];
        }

        $this->calculateTotals();
    }

    public function updateQuantity($index, $quantity)
    {
        if ($quantity <= 0) {
            $this->removeFromCart($index);
            return;
        }

        $this->cart[$index]['quantity'] = $quantity;
        $this->cart[$index]['total'] = $this->cart[$index]['quantity'] * $this->cart[$index]['price'];
        $this->calculateTotals();
    }

    public function removeFromCart($index)
    {
        unset($this->cart[$index]);
        $this->cart = array_values($this->cart);
        $this->calculateTotals();
    }

    public function clearCart()
    {
        $this->cart = [];
        $this->patientName = '';
        $this->patientPhone = '';
        $this->prescriptionNumber = '';
        $this->calculateTotals();
    }

    public function calculateTotals()
    {
        $this->subtotal = collect($this->cart)->sum('total');
        $this->discountAmount = $this->subtotal * ($this->discountPercentage / 100);
        $this->total = $this->subtotal - $this->discountAmount;
        $this->change = max(0, $this->amountReceived - $this->total);
    }

    public function updatedDiscountPercentage()
    {
        $this->calculateTotals();
    }

    public function updatedAmountReceived()
    {
        $this->change = max(0, $this->amountReceived - $this->total);
    }

    public function hasPrescriptionItems(): bool
    {
        return collect($this->cart)->contains('requires_prescription', true);
    }

    public function openPaymentModal()
    {
        if (empty($this->cart)) {
            session()->flash('error', 'ဈေးခြင်းထဲ ဆေးဝါးမရှိပါ!');
            return;
        }

        // Check if prescription items exist and prescription number is required
        if ($this->hasPrescriptionItems() && empty($this->prescriptionNumber)) {
            $this->showPrescriptionModal = true;
            return;
        }

        $this->amountReceived = $this->total;
        $this->showPaymentModal = true;
    }

    public function closePrescriptionModal()
    {
        $this->showPrescriptionModal = false;
    }

    public function confirmPrescription()
    {
        if (empty($this->prescriptionNumber)) {
            session()->flash('error', 'ဆရာဝန်ညွှန်ကြားချက် နံပါတ် ထည့်ပါ!');
            return;
        }
        $this->showPrescriptionModal = false;
        $this->amountReceived = $this->total;
        $this->showPaymentModal = true;
    }

    public function closePaymentModal()
    {
        $this->showPaymentModal = false;
    }

    public function processPayment()
    {
        if ($this->paymentMethod === 'cash' && $this->amountReceived < $this->total) {
            session()->flash('error', 'ငွေမလုံလောက်ပါ!');
            return;
        }

        DB::beginTransaction();
        try {
            $order = Order::create([
                'tenant_id' => auth()->user()->tenant_id,
                'user_id' => auth()->id(),
                'customer_id' => $this->customer_id,
                'order_number' => 'RX-' . date('Ymd') . '-' . str_pad(Order::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT),
                'order_type' => 'pharmacy',
                'subtotal' => $this->subtotal,
                'discount_percentage' => $this->discountPercentage,
                'discount_amount' => $this->discountAmount,
                'total' => $this->total,
                'payment_method' => $this->paymentMethod,
                'amount_received' => $this->amountReceived,
                'change_amount' => $this->change,
                'status' => 'completed',
                'paid_at' => now(),
                'notes' => $this->prescriptionNumber ? 'Prescription: ' . $this->prescriptionNumber : null,
            ]);

            foreach ($this->cart as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'item_id' => $item['id'],
                    'item_name' => $item['name'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['price'],
                    'total_price' => $item['total'],
                ]);
            }

            DB::commit();

            $this->completedOrderId = $order->id;
            $this->showPaymentModal = false;
            $this->showSuccessModal = true;
            $this->clearCart();
            $this->customer_id = null;
            $this->customer = null;

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'ငွေပေးချေမှု မအောင်မြင်ပါ: ' . $e->getMessage());
        }
    }

    public function closeSuccessModal()
    {
        $this->showSuccessModal = false;
        $this->completedOrderId = null;
    }

    public function render()
    {
        return view('pharmacy::livewire.pharmacy-pos')
            ->layout('layouts.app');
    }
}
