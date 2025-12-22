<?php

namespace App\Livewire\Cashier;

use Livewire\Component;
use App\Models\Order;
use App\Models\Table;
use Livewire\WithPagination;

class OrdersList extends Component
{
    use WithPagination;

    public $search = '';
    public $status = 'all';
    public $statusFilter = '';
    public $tableFilter = '';
    public $dateFilter = 'today';
    public $autoOpenOrderId = null;
    public $selectedOrder = null;
    public $showOrderDetails = false;
    public $showPaymentModal = false;
    public $showSuccessModal = false;
    public $paymentOrder = null;
    public $completedOrder = null;
    public $paymentMethod = 'cash';
    public $discountType = 'none';
    public $discountValue = 0;
    public $amountReceived = 0;
    public $applyTax = false;
    public $applyService = false;
    public $calculatedSubtotal = 0;
    public $calculatedTax = 0;
    public $calculatedService = 0;
    public $calculatedDiscount = 0;
    public $calculatedTotal = 0;
    public $calculatedChange = 0;

    protected $paginationTheme = 'tailwind';
    
    protected $queryString = [
        'tableFilter' => ['except' => ''],
        'autoOpenOrderId' => ['except' => '', 'as' => 'order'],
    ];

    public function mount()
    {
        // Check for table filter from URL
        if (request()->has('table')) {
            $this->tableFilter = request()->get('table');
            $this->statusFilter = 'pending'; // Show pending orders when coming from tables view
        }
        
        // Check for auto-open order from URL
        if (request()->has('order')) {
            $this->autoOpenOrderId = request()->get('order');
            // Auto-open the order details
            $this->viewOrder($this->autoOpenOrderId);
        }
    }
    
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingTableFilter()
    {
        $this->resetPage();
    }

    public function updatingDateFilter()
    {
        $this->resetPage();
    }

    public function render()
    {
        $baseQuery = Order::with(['table', 'items.item'])
            ->when($this->search, function($query) {
                $query->where('order_number', 'like', '%' . $this->search . '%');
            });

        // For all status, group pending orders by table
        $pendingOrders = (clone $baseQuery)->where('status', 'pending')
            ->whereHas('table') // Only group orders that have tables
            ->get()
            ->groupBy('table_id')
            ->map(function ($tableOrders) {
                // Take the latest order for this table as the representative order
                $latestOrder = $tableOrders->sortByDesc('created_at')->first();
                
                // Combine all items from all orders for this table
                $combinedItems = collect();
                foreach ($tableOrders as $order) {
                    $combinedItems = $combinedItems->merge($order->items);
                }
                
                // Group items by item_id and sum quantities
                $groupedItems = $combinedItems->groupBy('item_id')->map(function ($itemGroup) {
                    $firstItem = $itemGroup->first();
                    $totalQty = $itemGroup->sum('quantity');
                    $totalFocQty = $itemGroup->sum('foc_quantity');
                    
                    // Create a combined item with total quantities
                    $combinedItem = clone $firstItem;
                    $combinedItem->quantity = $totalQty;
                    $combinedItem->foc_quantity = $totalFocQty;
                    $combinedItem->subtotal = ($totalQty - $totalFocQty) * $firstItem->price;
                    
                    return $combinedItem;
                })->values();
                
                // Set combined items on the latest order
                $latestOrder->setRelation('items', $groupedItems);
                
                // Update totals
                $latestOrder->subtotal = $groupedItems->sum('subtotal');
                $latestOrder->total = $latestOrder->subtotal - ($latestOrder->discount_amount ?? 0);
                
                // Add order count for display
                $latestOrder->order_count = $tableOrders->count();
                $latestOrder->all_table_orders = $tableOrders;
                
                return $latestOrder;
            })->values();

        // Get completed/cancelled orders individually
        $completedOrders = (clone $baseQuery)->where('status', '!=', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Combine pending (grouped) and completed orders
        $allOrders = $pendingOrders->concat($completedOrders);
        
        // Apply additional filters
        $filteredOrders = $allOrders->filter(function($order) {
            // Status filter
            if ($this->statusFilter && $this->statusFilter !== 'all') {
                if ($order->status !== $this->statusFilter) {
                    return false;
                }
            }
            
            // Table filter
            if ($this->tableFilter) {
                if (!$order->table || $order->table->id != $this->tableFilter) {
                    return false;
                }
            }
            
            // Date filter
            if ($this->dateFilter === 'today') {
                if (!$order->created_at->isToday()) {
                    return false;
                }
            } elseif ($this->dateFilter === 'yesterday') {
                if (!$order->created_at->isYesterday()) {
                    return false;
                }
            }
            
            return true;
        });

        // Manual pagination since we have a collection
        $perPage = 10;
        $currentPage = $this->getPage();
        $orders = new \Illuminate\Pagination\LengthAwarePaginator(
            $filteredOrders->forPage($currentPage, $perPage),
            $filteredOrders->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'pageName' => 'page']
        );

        return view('livewire.cashier.orders-list', [
            'orders' => $orders,
            'tables' => Table::all()
        ]);
    }

    public function viewOrder($orderId)
    {
        $order = Order::with(['table', 'items.item'])->findOrFail($orderId);
        
        // If this order has a table and is pending, check for other pending orders on the same table
        if ($order->table && $order->status === 'pending') {
            $tableOrders = Order::with(['items.item'])
                ->where('table_id', $order->table_id)
                ->where('status', 'pending')
                ->get();
            
            if ($tableOrders->count() > 1) {
                // Combine all items from all orders for this table
                $combinedItems = collect();
                foreach ($tableOrders as $tableOrder) {
                    $combinedItems = $combinedItems->merge($tableOrder->items);
                }
                
                // Group items by item_id and sum quantities
                $groupedItems = $combinedItems->groupBy('item_id')->map(function ($itemGroup) {
                    $firstItem = $itemGroup->first();
                    $totalQty = $itemGroup->sum('quantity');
                    $totalFocQty = $itemGroup->sum('foc_quantity');
                    
                    // Create a combined item with total quantities
                    $combinedItem = clone $firstItem;
                    $combinedItem->quantity = $totalQty;
                    $combinedItem->foc_quantity = $totalFocQty;
                    $combinedItem->subtotal = ($totalQty - $totalFocQty) * $firstItem->price;
                    
                    return $combinedItem;
                })->values();
                
                // Set combined items on the order
                $order->setRelation('items', $groupedItems);
                
                // Update totals
                $order->subtotal = $groupedItems->sum('subtotal');
                $order->total = $order->subtotal - ($order->discount_amount ?? 0);
                
                // Add order count for display
                $order->order_count = $tableOrders->count();
                $order->all_table_orders = $tableOrders;
            }
        }
        
        $this->selectedOrder = $order;
        $this->showOrderDetails = true;
    }

    public function closeOrderDetails()
    {
        $this->showOrderDetails = false;
        $this->selectedOrder = null;
    }

    public function printReceipt($orderId)
    {
        try {
            // Ensure the order is selected so the receipt DOM element is rendered
            $this->selectedOrder = Order::with(['table', 'items.item', 'waiter', 'cashier'])->findOrFail($orderId);
            
            // Dispatch Livewire event with orderId for frontend printing (DOM-based)
            $this->dispatch('print-browser-receipt', orderId: $orderId);
        } catch (\Exception $e) {
            session()->flash('error', 'အမှားအယွင်းရှိပါသည်: ' . $e->getMessage());
        }
    }

    public function openPaymentModal($orderId)
    {
        $order = Order::with(['table', 'items.item'])->findOrFail($orderId);
        
        // If this order has a table and is pending, check for other pending orders on the same table
        if ($order->table && $order->status === 'pending') {
            $tableOrders = Order::with(['items.item'])
                ->where('table_id', $order->table_id)
                ->where('status', 'pending')
                ->get();
            
            if ($tableOrders->count() > 1) {
                // Combine all items from all orders for this table
                $combinedItems = collect();
                foreach ($tableOrders as $tableOrder) {
                    $combinedItems = $combinedItems->merge($tableOrder->items);
                }
                
                // Group items by item_id and sum quantities
                $groupedItems = $combinedItems->groupBy('item_id')->map(function ($itemGroup) {
                    $firstItem = $itemGroup->first();
                    $totalQty = $itemGroup->sum('quantity');
                    $totalFocQty = $itemGroup->sum('foc_quantity');
                    
                    // Create a combined item with total quantities
                    $combinedItem = clone $firstItem;
                    $combinedItem->quantity = $totalQty;
                    $combinedItem->foc_quantity = $totalFocQty;
                    $combinedItem->subtotal = ($totalQty - $totalFocQty) * $firstItem->price;
                    
                    return $combinedItem;
                })->values();
                
                // Set combined items on the order
                $order->setRelation('items', $groupedItems);
                
                // Update totals
                $order->subtotal = $groupedItems->sum('subtotal');
                $order->total = $order->subtotal - ($order->discount_amount ?? 0);
                
                // Store all table orders for payment processing
                $order->all_table_orders = $tableOrders;
            }
        }
        
        $this->paymentOrder = $order;
        $this->selectedOrder = $this->paymentOrder;
        
        // Reset payment fields
        $this->paymentMethod = 'cash';
        $this->amountReceived = 0;
        $this->applyTax = true; // Default to true for 5% Tax
        $this->applyService = false; // Default to false (Removed)
        $this->discountType = 'none';
        $this->discountValue = 0;
        
        $this->showPaymentModal = true;
        $this->calculatePayment();
    }
    
    // Add reactive updates
    public function updatedAmountReceived()
    {
        $this->calculatePayment();
    }
    
    public function updatedApplyTax()
    {
        $this->calculatePayment();
    }
    
    public function updatedApplyService()
    {
        $this->calculatePayment();
    }
    
    public function updatedDiscountType()
    {
        $this->discountValue = 0;
        $this->calculatePayment();
    }
    
    public function updatedDiscountValue()
    {
        $this->calculatePayment();
    }

    public function closePaymentModal()
    {
        $this->showPaymentModal = false;
        $this->paymentOrder = null;
        $this->reset(['paymentMethod', 'discountType', 'discountValue', 'amountReceived']);
    }

    public function calculatePayment()
    {
        if (!$this->paymentOrder) return;
        
        $this->calculatedSubtotal = $this->paymentOrder->items->sum(function($item) {
            return $item->quantity * $item->price;
        });
        
        // Tax is fixed at 5%
        $this->calculatedTax = $this->applyTax ? ($this->calculatedSubtotal * 0.05) : 0;
        
        // Service Charge is removed (0%)
        $this->calculatedService = 0;
        
        if ($this->discountType === 'percentage') {
            $this->calculatedDiscount = $this->calculatedSubtotal * ($this->discountValue / 100);
        } elseif ($this->discountType === 'fixed') {
            $this->calculatedDiscount = $this->discountValue;
        } else {
            $this->calculatedDiscount = 0;
        }
        
        $this->calculatedTotal = $this->calculatedSubtotal + $this->calculatedTax + $this->calculatedService - $this->calculatedDiscount;
        $this->calculatedChange = $this->amountReceived > 0 ? ($this->amountReceived - $this->calculatedTotal) : 0;
    }

    public function processPayment()
    {
        if (!$this->paymentOrder) return;
        
        // If this is a combined order, update all orders for the table
        if (isset($this->paymentOrder->all_table_orders) && $this->paymentOrder->all_table_orders->count() > 1) {
            foreach ($this->paymentOrder->all_table_orders as $tableOrder) {
                $tableOrder->update([
                    'status' => 'completed',
                    'payment_method' => $this->paymentMethod,
                    'amount_received' => $this->paymentMethod === 'cash' ? $this->amountReceived : $this->calculatedTotal,
                    'subtotal' => $this->calculatedSubtotal,
                    'tax_amount' => $this->calculatedTax,
                    'tax_percentage' => 5, // Fixed at 5%
                    'service_charge' => 0, // Removed
                    'service_charge_percentage' => 0, // Removed
                    'discount_amount' => $this->calculatedDiscount,
                    'total' => $this->calculatedTotal,
                    'completed_at' => now()
                ]);
            }
        } else {
            // Single order payment
            $this->paymentOrder->update([
                'status' => 'completed',
                'payment_method' => $this->paymentMethod,
                'amount_received' => $this->paymentMethod === 'cash' ? $this->amountReceived : $this->calculatedTotal,
                'subtotal' => $this->calculatedSubtotal,
                'tax_amount' => $this->calculatedTax,
                'tax_percentage' => 5, // Fixed at 5%
                'service_charge' => 0, // Removed
                'service_charge_percentage' => 0, // Removed
                'discount_amount' => $this->calculatedDiscount,
                'total' => $this->calculatedTotal,
                'completed_at' => now()
            ]);
        }
        
        // Free up table if exists
        if ($this->paymentOrder->table) {
            $this->paymentOrder->table->update(['status' => 'available']);
        }
        
        // Store completed order for success modal (use the main order)
        $this->completedOrder = $this->paymentOrder;
        
        // Close payment modal and show success
        $this->showPaymentModal = false;
        $this->showSuccessModal = true;
        $this->paymentOrder = null;
        
        // Flash success message
        session()->flash('message', 'ငွေရှင်းခြင်း အောင်မြင်ပါသည်။');
    }

    public function printCompletedReceipt()
    {
        if ($this->completedOrder) {
            try {
                // Load order with all necessary relationships
                $this->selectedOrder = Order::with(['table', 'items.item', 'waiter', 'cashier'])->findOrFail($this->completedOrder->id);
                
                // Dispatch Livewire event to trigger browser print dialog
                $this->dispatch('print-browser-receipt', orderId: $this->completedOrder->id);
            } catch (\Exception $e) {
                session()->flash('error', 'အမှားအယွင်းရှိပါသည်: ' . $e->getMessage());
            }
        }
    }

    public function cancelOrder($orderId)
    {
        $order = Order::findOrFail($orderId);
        
        // Only allow canceling pending orders
        if ($order->status !== 'pending') {
            session()->flash('error', 'ပြီးဆုံးပြီး သို့မဟုတ် ပယ်ဖျက်ပြီး အော်ဒါကို ပယ်ဖျက်၍ မရပါ။');
            return;
        }
        
        $order->update(['status' => 'cancelled']);
        
        // Free up table if exists
        if ($order->table) {
            $order->table->update(['status' => 'available']);
        }
        
        session()->flash('message', 'အော်ဒါကို ပယ်ဖျက်ပြီးပါပြီ။');
        
        if ($this->selectedOrder && $this->selectedOrder->id == $orderId) {
            $this->closeOrderDetails();
        }
    }

    public function deleteOrder($orderId)
    {
        $order = Order::findOrFail($orderId);
        
        // Only allow deleting cancelled orders
        if ($order->status !== 'cancelled') {
            session()->flash('error', 'ပယ်ဖျက်ထားသော အော်ဒါများကိုသာ ဖျက်နိုင်ပါသည်။');
            return;
        }
        
        $order->delete();
        
        session()->flash('message', 'အော်ဒါကို ဖျက်ပြီးပါပြီ။');
        
        if ($this->selectedOrder && $this->selectedOrder->id == $orderId) {
            $this->closeOrderDetails();
        }
    }

    public function closeSuccessModal()
    {
        $this->showSuccessModal = false;
        $this->completedOrder = null;
        $this->closeOrderDetails();
    }

    private function prepareOrderDataForPrinting($order)
    {
        $items = [];
        foreach ($order->items as $item) {
            $items[] = [
                'name' => $item->item->name_mm ?: $item->item->name,
                'nameEn' => $item->item->name,
                'quantity' => $item->quantity,
                'amount' => $item->subtotal,
                'isFoc' => $item->is_foc,
                'notes' => $item->notes
            ];
        }

        // Get table name
        $tableName = $order->table ? 
            ($order->table->name_mm ?: $order->table->name) : 
            'Takeaway';

        return [
            'businessName' => \App\Models\Setting::get('business_name', config('app.name')),
            'businessAddress' => \App\Models\Setting::get('business_address', ''),
            'businessPhone' => \App\Models\Setting::get('business_phone', ''),
            'orderNumber' => $order->order_number,
            'date' => $order->created_at->format('d/m/Y g:i A'),
            'table' => $tableName,
            'waiter' => $order->waiter ? $order->waiter->name : null,
            'items' => $items,
            'subtotal' => $order->subtotal,
            'tax' => $order->tax_amount,
            'taxPercentage' => $order->tax_percentage,
            'discount' => $order->discount_amount,
            'discountPercentage' => $order->discount_percentage,
            'serviceCharge' => $order->service_charge,
            'total' => $order->total
        ];
    }
}
