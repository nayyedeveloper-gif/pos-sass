<?php

namespace App\Livewire\Cashier;

use App\Models\Order;
use Livewire\Component;

class Dashboard extends Component
{
    public $todayStats;
    public $pendingOrders;
    public $recentSales;

    public function mount()
    {
        $this->loadStats();
        $this->loadOrders();
    }

    public function loadStats()
    {
        $today = now()->startOfDay();

        $this->todayStats = [
            'pending' => Order::where('status', 'pending')->count(),
            'completed_today' => Order::where('status', 'completed')
                ->whereDate('completed_at', $today)
                ->count(),
            'total_sales' => Order::where('status', 'completed')
                ->whereDate('completed_at', $today)
                ->sum('total'),
            'average_sale' => Order::where('status', 'completed')
                ->whereDate('completed_at', $today)
                ->avg('total'),
        ];
    }

    public function loadOrders()
    {
        // Pending orders waiting for payment
        $this->pendingOrders = Order::where('status', 'pending')
            ->with(['table', 'waiter', 'orderItems.item'])
            ->latest()
            ->take(10)
            ->get();

        // Recent completed sales
        $this->recentSales = Order::where('status', 'completed')
            ->with(['table', 'cashier'])
            ->latest('completed_at')
            ->take(5)
            ->get();
    }

    public function render()
    {
        return view('livewire.cashier.dashboard');
    }
}
