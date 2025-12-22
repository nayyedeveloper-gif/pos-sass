<?php

namespace App\Livewire\Cashier;

use App\Models\Category;
use App\Models\Item;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Customer;
use App\Models\Setting;
use App\Models\Shift;
use App\Events\OrderCreated;
use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\DB;

class PharmacyPOS extends Component
{
    // Categories & Items
    public $categories = [];
    public $selectedCategory = null;
    public $items = [];
    public $searchTerm = '';
    public $barcodeInput = '';
    
    // Cart
    public $cart = [];
    
    // Shift
    public $hasOpenShift = false;
    public $showShiftModal = false;
    
    // Patient Info
    public $patientName = '';
    public $patientPhone = '';
    public $patientAge = '';
    public $prescriptionNumber = '';
    public $doctorName = '';
    public $showPatientModal = false;
    
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
    public $showDrugInfoModal = false;
    public $completedOrder = null;
    public $selectedDrug = null;
    
    // Alerts
    public $expiringItems = [];
    public $lowStockItems = [];

    public function mount()
    {
        $this->checkShiftStatus();
        $this->loadCategories();
        $this->loadItems();
        $this->loadAlerts();
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
                  ->orWhere('barcode', 'like', '%' . $this->searchTerm . '%')
                  ->orWhere('sku', 'like', '%' . $this->searchTerm . '%');
            });
        }

        $this->items = $query->orderBy('name')->get();
    }

    public function loadAlerts()
    {
        try {
            // Expiring items (within 90 days)
            $this->expiringItems = Item::where('tenant_id', auth()->user()->tenant_id)
                ->where('is_active', true)
                ->whereNotNull('expiry_date')
                ->whereDate('expiry_date', '<=', now()->addDays(90))
                ->whereDate('expiry_date', '>', now())
                ->orderBy('expiry_date')
                ->limit(5)
                ->get();
        } catch (\Exception $e) {
            $this->expiringItems = collect([]);
        }

        try {
            // Low stock items
            $this->lowStockItems = Item::where('tenant_id', auth()->user()->tenant_id)
                ->where('is_active', true)
                ->whereNotNull('stock_quantity')
                ->whereNotNull('reorder_level')
                ->whereColumn('stock_quantity', '<=', 'reorder_level')
                ->limit(5)
                ->get();
        } catch (\Exception $e) {
            $this->lowStockItems = collect([]);
        }
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

    public function scanBarcode()
    {
        if (empty($this->barcodeInput)) return;

        $item = Item::where('tenant_id', auth()->user()->tenant_id)
            ->where(function($q) {
                $q->where('barcode', $this->barcodeInput)
                  ->orWhere('sku', $this->barcodeInput);
            })
            ->where('is_active', true)
            ->first();

        if ($item) {
            $this->addToCart($item->id);
        } else {
            session()->flash('error', 'ဆေးဝါး မတွေ့ပါ: ' . $this->barcodeInput);
        }

        $this->barcodeInput = '';
    }

    public function showDrugInfo($itemId)
    {
        $this->selectedDrug = Item::find($itemId);
        $this->showDrugInfoModal = true;
    }

    public function addToCart($itemId)
    {
        $item = Item::find($itemId);
        if (!$item) return;

        // Check if expired
        if ($item->expiry_date && $item->expiry_date->isPast()) {
            session()->flash('error', 'ဤဆေးဝါး သက်တမ်းကုန်သွားပါပြီ!');
            return;
        }

        // Check stock
        if ($item->stock_quantity !== null && $item->stock_quantity <= 0) {
            session()->flash('error', 'ဆေးဝါး ကုန်သွားပါပြီ!');
            return;
        }

        $existingIndex = collect($this->cart)->search(fn($cartItem) => $cartItem['id'] === $itemId);

        if ($existingIndex !== false) {
            if ($item->stock_quantity !== null && $this->cart[$existingIndex]['quantity'] >= $item->stock_quantity) {
                session()->flash('error', 'Stock မလုံလောက်ပါ!');
                return;
            }
            
            $this->cart[$existingIndex]['quantity']++;
            $this->cart[$existingIndex]['total'] = $this->cart[$existingIndex]['quantity'] * $this->cart[$existingIndex]['price'];
        } else {
            $this->cart[] = [
                'id' => $item->id,
                'name' => $item->name,
                'name_mm' => $item->name_mm,
                'generic_name' => $item->generic_name ?? '',
                'barcode' => $item->barcode,
                'price' => $item->price,
                'quantity' => 1,
                'total' => $item->price,
                'stock_quantity' => $item->stock_quantity,
                'requires_prescription' => $item->requires_prescription ?? false,
                'expiry_date' => $item->expiry_date?->format('Y-m-d'),
                'dosage' => $item->dosage ?? '',
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

        $stockQty = $this->cart[$index]['stock_quantity'];
        if ($stockQty !== null && $quantity > $stockQty) {
            session()->flash('error', 'Stock မလုံလောက်ပါ!');
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
        $this->patientAge = '';
        $this->prescriptionNumber = '';
        $this->doctorName = '';
        $this->discountPercentage = 0;
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

        // Check if prescription items exist
        if ($this->hasPrescriptionItems() && empty($this->prescriptionNumber)) {
            $this->showPrescriptionModal = true;
            return;
        }

        $this->amountReceived = ceil($this->total / 100) * 100;
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
        $this->amountReceived = ceil($this->total / 100) * 100;
        $this->showPaymentModal = true;
    }

    public function closePaymentModal()
    {
        $this->showPaymentModal = false;
    }

    public function setQuickAmount($amount)
    {
        $this->amountReceived = $amount;
        $this->change = max(0, $this->amountReceived - $this->total);
    }

    public function processPayment()
    {
        if ($this->paymentMethod === 'cash' && $this->amountReceived < $this->total) {
            session()->flash('error', 'ငွေမလုံလောက်ပါ!');
            return;
        }

        DB::beginTransaction();
        try {
            $notes = [];
            if ($this->prescriptionNumber) $notes[] = 'Rx: ' . $this->prescriptionNumber;
            if ($this->doctorName) $notes[] = 'Doctor: ' . $this->doctorName;
            if ($this->patientAge) $notes[] = 'Age: ' . $this->patientAge;

            $order = Order::create([
                'tenant_id' => auth()->user()->tenant_id,
                'user_id' => auth()->id(),
                'order_number' => 'RX-' . date('Ymd') . '-' . str_pad(Order::where('tenant_id', auth()->user()->tenant_id)->whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT),
                'order_type' => 'pharmacy',
                'customer_name' => $this->patientName,
                'customer_phone' => $this->patientPhone,
                'subtotal' => $this->subtotal,
                'discount_percentage' => $this->discountPercentage,
                'discount_amount' => $this->discountAmount,
                'total' => $this->total,
                'payment_method' => $this->paymentMethod,
                'amount_received' => $this->amountReceived,
                'change_amount' => $this->change,
                'notes' => implode(' | ', $notes),
                'status' => 'completed',
                'paid_at' => now(),
            ]);

            foreach ($this->cart as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'item_id' => $item['id'],
                    'item_name' => $item['name'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['price'],
                    'total_price' => $item['total'],
                    'notes' => $item['dosage'] ?? null,
                ]);

                // Update stock
                if ($item['stock_quantity'] !== null) {
                    Item::where('id', $item['id'])->decrement('stock_quantity', $item['quantity']);
                }
            }

            event(new OrderCreated($order));

            DB::commit();

            $this->completedOrder = $order;
            $this->showPaymentModal = false;
            $this->showSuccessModal = true;
            $this->clearCart();
            $this->loadAlerts();

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'အော်ဒါ မအောင်မြင်ပါ: ' . $e->getMessage());
        }
    }

    public function closeSuccessModal()
    {
        $this->showSuccessModal = false;
        $this->completedOrder = null;
    }

    public function printReceipt()
    {
        if ($this->completedOrder) {
            $this->dispatch('print-receipt', orderId: $this->completedOrder->id);
        }
    }

    public function render()
    {
        return view('livewire.cashier.pharmacy-pos')
            ->layout('layouts.app');
    }
}
