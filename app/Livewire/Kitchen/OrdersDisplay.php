<?php

namespace App\Livewire\Kitchen;

use App\Models\Order;
use Livewire\Component;
use Livewire\Attributes\On;

class OrdersDisplay extends Component
{
    public $orders = [];
    
    public function mount()
    {
        $this->loadOrders();
    }

    #[On('order-created')]
    #[On('order-updated')]
    public function loadOrders()
    {
        $this->orders = Order::with(['table', 'orderItems.item', 'waiter'])
            ->whereIn('status', ['pending', 'preparing'])
            ->orderBy('created_at', 'asc')
            ->get();
    }

    public function startPreparing($orderId)
    {
        $order = Order::findOrFail($orderId);
        $order->update(['status' => 'preparing']);
        
        $this->dispatch('order-updated');
        $this->loadOrders();
    }

    public function markReady($orderId)
    {
        $order = Order::findOrFail($orderId);
        $order->update(['status' => 'completed']);
        
        $this->dispatch('order-updated');
        $this->loadOrders();
    }

    public function render()
    {
        return view('livewire.kitchen.orders-display')->layout('layouts.kitchen');
    }
}
