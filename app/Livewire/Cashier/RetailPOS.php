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

class RetailPOS extends Component
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
    
    // Customer
    public $customer_id = null;
    public $customer = null;
    public $customerPhone = '';
    public $showCustomerModal = false;
    
    // Payment
    public $subtotal = 0;
    public $taxPercentage = 0;
    public $taxAmount = 0;
    public $discountPercentage = 0;
    public $discountAmount = 0;
    public $total = 0;
    public $paymentMethod = 'cash';
    public $amountReceived = 0;
    public $change = 0;
    
    // UI State
    public $showPaymentModal = false;
    public $showSuccessModal = false;
    public $showCalculatorModal = false;
    public $completedOrder = null;
    public $showItemModal = false;
    public $selectedItem = null;
    
    // Stock Alerts
    public $lowStockItems = [];

    public function mount()
    {
        $this->checkShiftStatus();
        $this->loadCategories();
        $this->loadItems();
        $this->loadSettings();
        $this->loadLowStockItems();
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

    public function loadSettings()
    {
        $this->taxPercentage = (float) Setting::get('tax_percentage', 0);
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

    public function loadLowStockItems()
    {
        try {
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
            $this->dispatch('barcode-scanned', name: $item->name);
        } else {
            session()->flash('error', 'ပစ္စည်း မတွေ့ပါ: ' . $this->barcodeInput);
        }

        $this->barcodeInput = '';
    }

    public function addToCart($itemId)
    {
        $item = Item::find($itemId);
        if (!$item) return;

        // Check stock
        if ($item->stock_quantity !== null && $item->stock_quantity <= 0) {
            session()->flash('error', 'ပစ္စည်း ကုန်သွားပါပြီ!');
            return;
        }

        $existingIndex = collect($this->cart)->search(fn($cartItem) => $cartItem['id'] === $itemId);

        if ($existingIndex !== false) {
            // Check if we have enough stock
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
                'barcode' => $item->barcode,
                'sku' => $item->sku,
                'price' => $item->price,
                'cost_price' => $item->cost_price,
                'quantity' => 1,
                'total' => $item->price,
                'stock_quantity' => $item->stock_quantity,
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

        // Check stock
        $stockQty = $this->cart[$index]['stock_quantity'];
        if ($stockQty !== null && $quantity > $stockQty) {
            session()->flash('error', 'Stock မလုံလောက်ပါ! ကျန် ' . $stockQty . ' ခုသာရှိပါသည်။');
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
        $this->customer_id = null;
        $this->customer = null;
        $this->discountPercentage = 0;
        $this->calculateTotals();
    }

    public function calculateTotals()
    {
        $this->subtotal = collect($this->cart)->sum('total');
        $this->taxAmount = $this->subtotal * ($this->taxPercentage / 100);
        $this->discountAmount = $this->subtotal * ($this->discountPercentage / 100);
        $this->total = $this->subtotal + $this->taxAmount - $this->discountAmount;
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

    public function openPaymentModal()
    {
        if (empty($this->cart)) {
            session()->flash('error', 'ဈေးခြင်းထဲ ပစ္စည်းမရှိပါ!');
            return;
        }

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
            // Create Order
            $order = Order::create([
                'tenant_id' => auth()->user()->tenant_id,
                'user_id' => auth()->id(),
                'customer_id' => $this->customer_id,
                'order_number' => 'RT-' . date('Ymd') . '-' . str_pad(Order::where('tenant_id', auth()->user()->tenant_id)->whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT),
                'order_type' => 'retail',
                'subtotal' => $this->subtotal,
                'tax_percentage' => $this->taxPercentage,
                'tax_amount' => $this->taxAmount,
                'discount_percentage' => $this->discountPercentage,
                'discount_amount' => $this->discountAmount,
                'total' => $this->total,
                'payment_method' => $this->paymentMethod,
                'amount_received' => $this->amountReceived,
                'change_amount' => $this->change,
                'status' => 'completed',
                'paid_at' => now(),
            ]);

            // Create Order Items & Update Stock
            foreach ($this->cart as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'item_id' => $item['id'],
                    'item_name' => $item['name'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['price'],
                    'cost_price' => $item['cost_price'] ?? 0,
                    'total_price' => $item['total'],
                ]);

                // Update stock
                if ($item['stock_quantity'] !== null) {
                    Item::where('id', $item['id'])->decrement('stock_quantity', $item['quantity']);
                }
            }

            // Fire event
            event(new OrderCreated($order));

            DB::commit();

            $this->completedOrder = $order;
            $this->showPaymentModal = false;
            $this->showSuccessModal = true;
            $this->clearCart();
            $this->loadLowStockItems();

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
        return view('livewire.cashier.retail-pos')
            ->layout('layouts.app');
    }
}
