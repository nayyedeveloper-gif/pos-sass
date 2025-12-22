<?php

namespace App\Livewire\Waiter;

use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;

class OrdersList extends Component
{
    use WithPagination;

    public $statusFilter = 'all';
    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function viewOrder($orderId)
    {
        return redirect()->route('waiter.orders.show', $orderId);
    }

    public function render()
    {
        $tenantId = auth()->user()->tenant_id;
        
        // Get orders with table relationships
        $ordersQuery = Order::with(['table', 'waiter', 'items.item'])
            ->where('tenant_id', $tenantId)
            ->where('waiter_id', auth()->id())
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('order_number', 'like', '%' . $this->search . '%')
                      ->orWhereHas('table', function ($tq) {
                          $tq->where('name', 'like', '%' . $this->search . '%')
                             ->orWhere('name_mm', 'like', '%' . $this->search . '%');
                      });
                });
            });

        // If filtering by status, get individual orders
        if ($this->statusFilter !== 'all') {
            $orders = (clone $ordersQuery)
                ->where('status', $this->statusFilter)
                ->latest()
                ->paginate(20);
        } else {
            // For 'all' status, group pending orders by table
            $pendingOrders = (clone $ordersQuery)
                ->where('status', 'pending')
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
                    
                    return $latestOrder;
                })->values();

            // Get completed orders individually
            $completedOrders = (clone $ordersQuery)
                ->where('status', '!=', 'pending')
                ->latest()
                ->get();
            
            // Combine pending (grouped) and completed orders
            $allOrders = $pendingOrders->concat($completedOrders);
            
            // Manual pagination since we have a collection
            $perPage = 20;
            $currentPage = $this->getPage();
            $orders = new \Illuminate\Pagination\LengthAwarePaginator(
                $allOrders->forPage($currentPage, $perPage),
                $allOrders->count(),
                $perPage,
                $currentPage,
                ['path' => request()->url(), 'pageName' => 'page']
            );
        }

        return view('livewire.waiter.orders-list', [
            'orders' => $orders,
        ]);
    }
}
