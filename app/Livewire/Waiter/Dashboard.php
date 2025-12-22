<?php

namespace App\Livewire\Waiter;

use App\Models\Order;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class Dashboard extends Component
{
    public $todayStats;
    public $recentOrders;
    public $activeOrders;

    public function mount()
    {
        $this->loadStats();
        $this->loadOrders();
    }

    public function loadStats()
    {
        $waiterId = auth()->id();
        $tenantId = auth()->user()->tenant_id;
        $today = now()->startOfDay();

        $this->todayStats = [
            'active' => Order::where('tenant_id', $tenantId)
                ->where('waiter_id', $waiterId)
                ->whereIn('status', ['pending', 'preparing'])
                ->count(),
            'completed' => Order::where('tenant_id', $tenantId)
                ->where('waiter_id', $waiterId)
                ->where('status', 'completed')
                ->whereDate('created_at', $today)
                ->count(),
            'total_sales' => Order::where('tenant_id', $tenantId)
                ->where('waiter_id', $waiterId)
                ->where('status', 'completed')
                ->whereDate('created_at', $today)
                ->sum('total'),
            'total_orders' => Order::where('tenant_id', $tenantId)
                ->where('waiter_id', $waiterId)
                ->whereDate('created_at', $today)
                ->count(),
        ];
    }

    public function loadOrders()
    {
        $waiterId = auth()->id();
        $tenantId = auth()->user()->tenant_id;

        // Active orders (pending/preparing)
        $this->activeOrders = Order::where('tenant_id', $tenantId)
            ->where('waiter_id', $waiterId)
            ->whereIn('status', ['pending', 'preparing'])
            ->with(['table', 'orderItems.item'])
            ->latest()
            ->take(5)
            ->get();

        // Recent completed orders
        $this->recentOrders = Order::where('tenant_id', $tenantId)
            ->where('waiter_id', $waiterId)
            ->where('status', 'completed')
            ->with(['table', 'orderItems.item'])
            ->latest()
            ->take(5)
            ->get();
    }

    public function render()
    {
        return view('livewire.waiter.dashboard');
    }
}
