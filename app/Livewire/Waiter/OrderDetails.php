<?php

namespace App\Livewire\Waiter;

use App\Models\Order;
use Livewire\Component;

class OrderDetails extends Component
{
    public $orderId;
    public $order;

    public function mount($orderId)
    {
        $this->orderId = $orderId;
        $this->loadOrder();
    }

    public function loadOrder()
    {
        $order = Order::with(['table', 'waiter', 'cashier', 'items.item'])
            ->findOrFail($this->orderId);
        
        // If this order has a table and is pending, check for other pending orders on the same table
        if ($order->table && $order->status === 'pending') {
            $tableOrders = Order::with(['items.item'])
                ->where('table_id', $order->table_id)
                ->where('status', 'pending')
                ->where('waiter_id', auth()->id())
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
        
        $this->order = $order;
    }

    public function updateStatus($status)
    {
        // If this order has combined orders, update all of them
        if (isset($this->order->all_table_orders) && $this->order->all_table_orders->count() > 1) {
            foreach ($this->order->all_table_orders as $tableOrder) {
                $tableOrder->update(['status' => $status]);
            }
        } else {
            $this->order->update(['status' => $status]);
        }
        
        $this->loadOrder();
        session()->flash('success', 'Order status updated successfully!');
    }

    public function render()
    {
        return view('livewire.waiter.order-details');
    }
}
