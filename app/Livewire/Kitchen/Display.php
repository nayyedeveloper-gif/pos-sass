<?php

namespace App\Livewire\Kitchen;

use App\Models\OrderItem;
use Livewire\Component;
use Livewire\Attributes\On;

class Display extends Component
{
    public $orders = [];
    public $filter = 'all'; // all, pending, preparing, ready

    public function mount()
    {
        $this->loadOrders();
    }

    #[On('echo:orders,OrderCreated')]
    public function loadOrders()
    {
        // Fetch order items that are relevant to the kitchen
        // Grouped by Order for better display
        $query = OrderItem::with(['order.table', 'item'])
            ->whereIn('status', ['pending', 'preparing', 'ready'])
            ->orderBy('created_at', 'asc');

        if ($this->filter !== 'all') {
            $query->where('status', $this->filter);
        }

        $orderItems = $query->get();

        // Group items by order
        $this->orders = $orderItems->groupBy('order_id')->map(function ($items) {
            $firstItem = $items->first();
            return [
                'order_id' => $firstItem->order_id,
                'order_number' => $firstItem->order->order_number,
                'table_name' => $firstItem->order->table ? $firstItem->order->table->name : 'Takeaway',
                'waiter_name' => $firstItem->order->waiter ? $firstItem->order->waiter->name : 'N/A',
                'created_at' => $firstItem->created_at,
                'items' => $items,
                'status' => $this->getOrderStatus($items),
            ];
        })->values();
    }

    private function getOrderStatus($items)
    {
        if ($items->where('status', 'pending')->count() > 0) return 'pending';
        if ($items->where('status', 'preparing')->count() > 0) return 'preparing';
        return 'ready';
    }

    public function updateStatus($itemId, $status)
    {
        $orderItem = OrderItem::find($itemId);
        if ($orderItem) {
            $orderItem->update(['status' => $status]);
            $this->loadOrders();
        }
    }

    public function updateOrderStatus($orderId, $status)
    {
        OrderItem::where('order_id', $orderId)
            ->whereIn('status', ['pending', 'preparing', 'ready'])
            ->update(['status' => $status]);
        
        $this->loadOrders();
    }

    public function setFilter($filter)
    {
        $this->filter = $filter;
        $this->loadOrders();
    }

    public function render()
    {
        return view('livewire.kitchen.display');
    }
}
