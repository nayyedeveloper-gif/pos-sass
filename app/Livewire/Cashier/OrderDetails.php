<?php

namespace App\Livewire\Cashier;

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

    public function printReceipt()
    {
        // Prepare order data for printing
        $orderData = $this->prepareOrderDataForPrinting();
        
        // Dispatch event with order data for frontend printing
        $this->dispatch('print-receipt', orderData: $orderData);
    }

    private function prepareOrderDataForPrinting()
    {
        $items = [];
        foreach ($this->order->items as $item) {
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
        $tableName = $this->order->table ? 
            ($this->order->table->name_mm ?: $this->order->table->name) : 
            'Takeaway';

        return [
            'businessName' => \App\Models\Setting::get('business_name', config('app.name')),
            'businessAddress' => \App\Models\Setting::get('business_address', ''),
            'businessPhone' => \App\Models\Setting::get('business_phone', ''),
            'orderNumber' => $this->order->order_number,
            'date' => $this->order->created_at->format('d/m/Y g:i A'),
            'table' => $tableName,
            'waiter' => $this->order->waiter ? $this->order->waiter->name : null,
            'items' => $items,
            'subtotal' => $this->order->subtotal,
            'tax' => $this->order->tax_amount,
            'taxPercentage' => $this->order->tax_percentage,
            'discount' => $this->order->discount_amount,
            'discountPercentage' => $this->order->discount_percentage,
            'serviceCharge' => $this->order->service_charge,
            'total' => $this->order->total
        ];
    }

    public function render()
    {
        return view('livewire.cashier.order-details');
    }
}
