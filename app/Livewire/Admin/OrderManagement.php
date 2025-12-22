<?php

namespace App\Livewire\Admin;

use App\Models\Order;
use App\Models\Table;
use Livewire\Component;
use Livewire\WithPagination;

class OrderManagement extends Component
{
    use WithPagination;

    public $statusFilter = 'all';
    public $orderTypeFilter = 'all';
    public $searchTerm = '';
    public $selectedOrder = null;
    public $showOrderModal = false;

    protected $queryString = ['statusFilter', 'orderTypeFilter'];

    public function updatingSearchTerm()
    {
        $this->resetPage();
    }

    public function filterByStatus($status)
    {
        $this->statusFilter = $status;
        $this->resetPage();
    }

    public function filterByType($type)
    {
        $this->orderTypeFilter = $type;
        $this->resetPage();
    }

    public function viewOrder($orderId)
    {
        $this->selectedOrder = Order::with(['items', 'table', 'customer', 'user'])->find($orderId);
        $this->showOrderModal = true;
    }

    public function updateOrderStatus($orderId, $status)
    {
        $order = Order::find($orderId);
        if ($order) {
            $order->update(['status' => $status]);
            
            if ($status === 'completed' && $order->table_id) {
                Table::find($order->table_id)?->update([
                    'status' => 'available',
                    'current_order_id' => null,
                    'occupied_at' => null,
                    'guest_count' => null,
                ]);
            }
        }
        $this->showOrderModal = false;
    }

    public function getOrdersProperty()
    {
        $query = Order::where('tenant_id', auth()->user()->tenant_id)
            ->with(['table', 'customer', 'user'])
            ->latest();

        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        if ($this->orderTypeFilter !== 'all') {
            $query->where('order_type', $this->orderTypeFilter);
        }

        if ($this->searchTerm) {
            $query->where(function ($q) {
                $q->where('order_number', 'like', '%' . $this->searchTerm . '%')
                  ->orWhereHas('customer', function ($q) {
                      $q->where('name', 'like', '%' . $this->searchTerm . '%');
                  });
            });
        }

        return $query->paginate(20);
    }

    public function getStatsProperty()
    {
        $tenantId = auth()->user()->tenant_id;
        
        return [
            'total' => Order::where('tenant_id', $tenantId)->whereDate('created_at', today())->count(),
            'pending' => Order::where('tenant_id', $tenantId)->where('status', 'pending')->count(),
            'preparing' => Order::where('tenant_id', $tenantId)->where('status', 'preparing')->count(),
            'completed' => Order::where('tenant_id', $tenantId)->whereDate('created_at', today())->where('status', 'completed')->count(),
            'revenue' => Order::where('tenant_id', $tenantId)->whereDate('created_at', today())->where('status', 'completed')->sum('total'),
        ];
    }

    public function render()
    {
        return view('livewire.admin.order-management', [
            'orders' => $this->orders,
            'stats' => $this->stats,
        ]);
    }
}
