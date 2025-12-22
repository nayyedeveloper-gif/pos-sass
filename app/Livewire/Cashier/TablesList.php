<?php

namespace App\Livewire\Cashier;

use App\Models\Table;
use App\Models\Order;
use Livewire\Component;

class TablesList extends Component
{
    public $tables;
    public $search = '';

    public function mount()
    {
        $this->loadTables();
    }

    public function loadTables()
    {
        $this->tables = Table::active()
            ->withCount(['orders as active_orders_count' => function ($query) {
                $query->where('status', 'pending');
            }])
            ->with(['orders' => function ($query) {
                $query->where('status', 'pending')
                      ->with(['orderItems.item', 'waiter'])
                      ->latest();
            }])
            ->ordered()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('name_mm', 'like', '%' . $this->search . '%');
            })
            ->get();
    }

    public function updatedSearch()
    {
        $this->loadTables();
    }

    public function selectTable($tableId)
    {
        $table = Table::with(['orders' => function ($query) {
            $query->where('status', 'pending')
                  ->with(['orderItems.item', 'waiter'])
                  ->latest();
        }])->find($tableId);

        if (!$table) {
            return;
        }

        $pendingOrders = $table->orders;

        if ($pendingOrders->isEmpty()) {
            // Table is available - no action needed for cashier
            session()->flash('info', 'ဤစားပွဲတွင် ငွေရှင်းရန် အော်ဒါမရှိပါ။');
            return;
        }

        // Redirect to orders list with table filter and auto-open first order
        $firstOrderId = $pendingOrders->first()->id;
        return redirect()->route('cashier.orders.index', [
            'table' => $tableId,
            'order' => $firstOrderId
        ]);
    }

    public function render()
    {
        return view('livewire.cashier.tables-list');
    }
}
