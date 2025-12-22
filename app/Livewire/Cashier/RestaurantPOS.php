<?php

namespace App\Livewire\Cashier;

use App\Models\Category;
use App\Models\Item;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Table;
use App\Models\Customer;
use App\Models\Setting;
use App\Models\Shift;
use App\Events\OrderCreated;
use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\DB;

class RestaurantPOS extends Component
{
    // Categories & Items
    public $categories = [];
    public $selectedCategory = null;
    public $items = [];
    public $searchTerm = '';
    
    // Cart
    public $cart = [];
    public $cartNotes = [];
    
    // Shift
    public $hasOpenShift = false;
    public $showShiftModal = false;
    
    // Order Details
    public $orderType = 'dine_in'; // dine_in, takeaway, delivery
    public $selectedTable = null;
    public $selectedTableData = null;
    public $customerName = '';
    public $customerPhone = '';
    public $deliveryAddress = '';
    public $orderNotes = '';
    public $guestCount = 1;
    
    // Customer & Loyalty
    public $customer_id = null;
    public $customer = null;
    public $loyaltyPointsToRedeem = 0;
    public $showCustomerModal = false;
    
    // Tables
    public $tables = [];
    public $sections = [];
    public $selectedSection = null;
    public $layoutElements = [];
    public $showTableModal = false;
    public $tableFilter = 'all'; // all, available, occupied
    public $tableViewMode = 'grid'; // grid or layout
    
    // Payment
    public $subtotal = 0;
    public $taxPercentage = 5;
    public $taxAmount = 0;
    public $serviceChargePercentage = 10;
    public $serviceChargeAmount = 0;
    public $discountPercentage = 0;
    public $discountAmount = 0;
    public $total = 0;
    public $paymentMethod = 'cash';
    public $amountReceived = 0;
    public $change = 0;
    public $splitPayment = false;
    public $payments = [];
    
    // UI State
    public $showPaymentModal = false;
    public $showSuccessModal = false;
    public $showKitchenNoteModal = false;
    public $completedOrder = null;
    public $activeItemForNote = null;
    
    // Kitchen
    public $sendToKitchen = true;
    public $kitchenNote = '';

    public function mount()
    {
        $this->checkShiftStatus();
        $this->loadCategories();
        $this->loadItems();
        $this->loadTables();
        $this->loadSettings();
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
        $this->taxPercentage = (float) Setting::get('tax_percentage', 5);
        $this->serviceChargePercentage = (float) Setting::get('service_charge_percentage', 10);
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
                  ->orWhere('sku', 'like', '%' . $this->searchTerm . '%');
            });
        }

        $this->items = $query->orderBy('name')->get();
    }

    public function loadTables()
    {
        // Load sections
        $this->sections = \App\Models\TableSection::where('tenant_id', auth()->user()->tenant_id)
            ->orderBy('floor')
            ->orderBy('sort_order')
            ->get();
            
        if ($this->sections->isNotEmpty() && !$this->selectedSection) {
            $this->selectedSection = $this->sections->first()->id;
        }

        $query = Table::where('tenant_id', auth()->user()->tenant_id)
            ->where('is_active', true);
            
        if ($this->selectedSection) {
            $query->where('section_id', $this->selectedSection);
        }
            
        if ($this->tableFilter === 'available') {
            $query->where('status', 'available');
        } elseif ($this->tableFilter === 'occupied') {
            $query->where('status', 'occupied');
        }
        
        $this->tables = $query->orderBy('sort_order')->orderBy('name')->get();
        
        // Load layout elements for current section
        if ($this->selectedSection) {
            $this->layoutElements = \App\Models\TableLayoutElement::where('section_id', $this->selectedSection)->get();
        }
    }
    
    public function selectSection($sectionId)
    {
        $this->selectedSection = $sectionId;
        $this->loadTables();
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

    public function updatedTableFilter()
    {
        $this->loadTables();
    }

    public function selectTable($tableId)
    {
        $table = Table::find($tableId);
        if ($table && $table->status === 'available') {
            $this->selectedTable = $tableId;
            $this->selectedTableData = $table;
            $this->orderType = 'dine_in';
            $this->showTableModal = false;
        } elseif ($table && $table->status === 'occupied') {
            // Load existing order for this table
            $this->loadTableOrder($table);
        }
    }

    public function loadTableOrder($table)
    {
        $existingOrder = Order::where('table_id', $table->id)
            ->where('status', 'pending')
            ->with('items.item')
            ->first();
            
        if ($existingOrder) {
            $this->cart = [];
            foreach ($existingOrder->items as $orderItem) {
                $this->cart[] = [
                    'id' => $orderItem->item_id,
                    'name' => $orderItem->item_name,
                    'name_mm' => $orderItem->item->name_mm ?? $orderItem->item_name,
                    'price' => $orderItem->unit_price,
                    'quantity' => $orderItem->quantity,
                    'total' => $orderItem->total_price,
                    'notes' => $orderItem->notes ?? '',
                    'sent_to_kitchen' => $orderItem->sent_to_kitchen ?? false,
                ];
            }
            $this->selectedTable = $table->id;
            $this->selectedTableData = $table;
            $this->calculateTotals();
        }
        $this->showTableModal = false;
    }

    public function clearTable()
    {
        $this->selectedTable = null;
        $this->selectedTableData = null;
    }

    public function setOrderType($type)
    {
        $this->orderType = $type;
        if ($type !== 'dine_in') {
            $this->clearTable();
        }
    }

    public function addToCart($itemId)
    {
        $item = Item::find($itemId);
        if (!$item) return;

        $existingIndex = collect($this->cart)->search(fn($cartItem) => 
            $cartItem['id'] === $itemId && empty($cartItem['notes'])
        );

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
                'notes' => '',
                'sent_to_kitchen' => false,
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

    public function openKitchenNoteModal($index)
    {
        $this->activeItemForNote = $index;
        $this->kitchenNote = $this->cart[$index]['notes'] ?? '';
        $this->showKitchenNoteModal = true;
    }

    public function saveKitchenNote()
    {
        if ($this->activeItemForNote !== null) {
            $this->cart[$this->activeItemForNote]['notes'] = $this->kitchenNote;
        }
        $this->showKitchenNoteModal = false;
        $this->kitchenNote = '';
        $this->activeItemForNote = null;
    }

    public function clearCart()
    {
        $this->cart = [];
        $this->clearTable();
        $this->customerName = '';
        $this->customerPhone = '';
        $this->deliveryAddress = '';
        $this->orderNotes = '';
        $this->discountPercentage = 0;
        $this->customer_id = null;
        $this->customer = null;
        $this->calculateTotals();
    }

    public function calculateTotals()
    {
        $this->subtotal = collect($this->cart)->sum('total');
        
        // Tax
        $this->taxAmount = $this->subtotal * ($this->taxPercentage / 100);
        
        // Service Charge (only for dine-in)
        if ($this->orderType === 'dine_in') {
            $this->serviceChargeAmount = $this->subtotal * ($this->serviceChargePercentage / 100);
        } else {
            $this->serviceChargeAmount = 0;
        }
        
        // Discount
        $this->discountAmount = $this->subtotal * ($this->discountPercentage / 100);
        
        // Total
        $this->total = $this->subtotal + $this->taxAmount + $this->serviceChargeAmount - $this->discountAmount;
        
        // Change
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

        if ($this->orderType === 'dine_in' && !$this->selectedTable) {
            $this->showTableModal = true;
            return;
        }

        $this->amountReceived = ceil($this->total / 100) * 100; // Round up to nearest 100
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

    public function sendToKitchen()
    {
        if (empty($this->cart)) {
            session()->flash('error', 'ဈေးခြင်းထဲ ပစ္စည်းမရှိပါ!');
            return;
        }

        // Mark items as sent to kitchen
        foreach ($this->cart as $index => $item) {
            if (!$item['sent_to_kitchen']) {
                $this->cart[$index]['sent_to_kitchen'] = true;
            }
        }

        // TODO: Dispatch event to Kitchen Display System
        // event(new OrderSentToKitchen($this->cart, $this->selectedTableData));

        session()->flash('success', 'မီးဖိုချောင်သို့ ပို့ပြီးပါပြီ!');
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
                'table_id' => $this->selectedTable,
                'order_number' => $this->generateOrderNumber(),
                'order_type' => $this->orderType,
                'customer_name' => $this->customerName,
                'customer_phone' => $this->customerPhone,
                'delivery_address' => $this->deliveryAddress,
                'guest_count' => $this->guestCount,
                'subtotal' => $this->subtotal,
                'tax_percentage' => $this->taxPercentage,
                'tax_amount' => $this->taxAmount,
                'service_charge_percentage' => $this->serviceChargePercentage,
                'service_charge_amount' => $this->serviceChargeAmount,
                'discount_percentage' => $this->discountPercentage,
                'discount_amount' => $this->discountAmount,
                'total' => $this->total,
                'payment_method' => $this->paymentMethod,
                'amount_received' => $this->amountReceived,
                'change_amount' => $this->change,
                'notes' => $this->orderNotes,
                'status' => 'completed',
                'paid_at' => now(),
            ]);

            // Create Order Items
            foreach ($this->cart as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'item_id' => $item['id'],
                    'item_name' => $item['name'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['price'],
                    'total_price' => $item['total'],
                    'notes' => $item['notes'] ?? null,
                ]);
            }

            // Update table status
            if ($this->selectedTable) {
                Table::where('id', $this->selectedTable)->update(['status' => 'available']);
            }

            // Fire event
            event(new OrderCreated($order));

            DB::commit();

            $this->completedOrder = $order;
            $this->showPaymentModal = false;
            $this->showSuccessModal = true;
            $this->clearCart();
            $this->loadTables();

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'အော်ဒါ မအောင်မြင်ပါ: ' . $e->getMessage());
        }
    }

    protected function generateOrderNumber(): string
    {
        $prefix = match($this->orderType) {
            'dine_in' => 'DI',
            'takeaway' => 'TA',
            'delivery' => 'DL',
            default => 'ORD'
        };
        
        $date = date('Ymd');
        $count = Order::where('tenant_id', auth()->user()->tenant_id)
            ->whereDate('created_at', today())
            ->count() + 1;
            
        return $prefix . '-' . $date . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
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
        return view('livewire.cashier.restaurant-pos')
            ->layout('layouts.app');
    }
}
