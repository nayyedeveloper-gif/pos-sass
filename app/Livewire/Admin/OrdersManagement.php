<?php

namespace App\Livewire\Admin;

use App\Models\Order;
use App\Models\Table;
use Livewire\Component;
use Livewire\WithPagination;

class OrdersManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $tableFilter = '';
    public $dateFilter = '';
    
    public $selectedOrder = null;
    public $showOrderDetails = false;

    protected $queryString = ['search', 'statusFilter', 'tableFilter', 'dateFilter'];

    public function mount()
    {
        // Check if table parameter is passed from URL
        if (request()->has('table')) {
            $this->tableFilter = request()->get('table');
        }
        
        if (!$this->dateFilter) {
            $this->dateFilter = 'today';
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

    public function viewOrder($orderId)
    {
        $this->selectedOrder = Order::with(['table', 'orderItems.item', 'waiter', 'cashier'])
            ->findOrFail($orderId);
        $this->showOrderDetails = true;
    }

    public function closeOrderDetails()
    {
        $this->showOrderDetails = false;
        $this->selectedOrder = null;
    }

    public function printReceipt($orderId)
    {
        $this->selectedOrder = Order::with(['table', 'orderItems.item', 'waiter', 'cashier'])
            ->findOrFail($orderId);
        
        $this->dispatch('print-receipt', orderId: $orderId);
    }

    public function updateOrderStatus($orderId, $status)
    {
        $order = Order::findOrFail($orderId);
        $order->update(['status' => $status]);
        
        if ($status === 'completed') {
            $order->update(['completed_at' => now()]);
            
            // Update table status to available
            if ($order->table) {
                $order->table->update(['status' => 'available']);
            }
        }
        
        session()->flash('message', 'အော်ဒါ အခြေအနေကို ပြောင်းလဲပြီးပါပြီ။');
        
        if ($this->selectedOrder && $this->selectedOrder->id === $orderId) {
            $this->selectedOrder->refresh();
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

    public function render()
    {
        $query = Order::with(['table', 'orderItems', 'waiter'])
            ->latest();

        if ($this->search) {
            $query->where(function($q) {
                $q->where('order_number', 'like', '%' . $this->search . '%')
                  ->orWhereHas('table', function($q) {
                      $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('name_mm', 'like', '%' . $this->search . '%');
                  });
            });
        }

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        if ($this->tableFilter) {
            $query->where('table_id', $this->tableFilter);
        }

        if ($this->dateFilter) {
            switch ($this->dateFilter) {
                case 'today':
                    $query->whereDate('created_at', today());
                    break;
                case 'yesterday':
                    $query->whereDate('created_at', today()->subDay());
                    break;
                case 'week':
                    $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'month':
                    $query->whereMonth('created_at', now()->month)
                          ->whereYear('created_at', now()->year);
                    break;
            }
        }

        $orders = $query->paginate(20);
        $tables = Table::active()->ordered()->get();

        return view('livewire.admin.orders-management', [
            'orders' => $orders,
            'tables' => $tables,
        ]);
    }
}
