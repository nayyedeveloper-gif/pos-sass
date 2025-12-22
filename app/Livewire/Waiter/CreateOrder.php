<?php

namespace App\Livewire\Waiter;

use App\Models\Category;
use App\Models\Item;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Table;
use App\Models\Customer;
use App\Models\Setting;
use App\Services\PrinterService;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class CreateOrder extends Component
{
    public $table;
    public $orderType = 'dine_in';
    public $categories;
    public $selectedCategory = null;
    public $items = [];
    public $cart = [];
    public $notes = '';
    public $searchTerm = '';
    public $existingOrder = null;
    public $isEditMode = false;
    
    // Customer & Loyalty
    public $customer_id = null;
    public $customer = null;
    public $customer_phone = '';
    public $loyalty_points_to_redeem = 0;
    public $showCustomerLookup = false;

    public function mount($table = null, $type = null, $order = null)
    {
        // Check if editing existing order
        if ($order) {
            $this->existingOrder = Order::with(['items.item', 'table'])->findOrFail($order);
            $this->isEditMode = true;
            $this->table = $this->existingOrder->table;
            $this->orderType = $this->existingOrder->order_type;
            $this->notes = $this->existingOrder->notes;
            
            // Load existing items into cart
            /** @var OrderItem $orderItem */
            foreach ($this->existingOrder->items as $orderItem) {
                if (!$orderItem->item) {
                    continue;
                }
                $cartKey = 'item_' . $orderItem->item_id;
                $this->cart[$cartKey] = [
                    'item_id' => $orderItem->item_id,
                    'name' => $orderItem->item->name,
                    'name_mm' => $orderItem->item->name_mm ?? $orderItem->item->name,
                    'price' => $orderItem->price,
                    'quantity' => $orderItem->quantity,
                    'subtotal' => $orderItem->subtotal,
                    'is_foc' => $orderItem->is_foc,
                    'notes' => $orderItem->notes ?? '',
                ];
            }
        } elseif ($type === 'takeaway') {
            $this->orderType = 'takeaway';
        } elseif ($table) {
            $this->table = Table::findOrFail($table);
            $this->orderType = 'dine_in';
            
            // Check if table has existing pending order
            $pendingOrder = Order::where('table_id', $this->table->id)
                ->where('status', 'pending')
                ->with(['orderItems.item'])
                ->first();
            
            if ($pendingOrder) {
                // Load existing order into edit mode
                $this->existingOrder = $pendingOrder;
                $this->isEditMode = true;
                $this->notes = $pendingOrder->notes;
                
                // Load existing items into cart
                /** @var OrderItem $orderItem */
                foreach ($pendingOrder->orderItems as $orderItem) {
                    if (!$orderItem->item) {
                        continue;
                    }
                    $cartKey = 'item_' . $orderItem->item_id;
                    $this->cart[$cartKey] = [
                        'item_id' => $orderItem->item_id,
                        'name' => $orderItem->item->name,
                        'name_mm' => $orderItem->item->name_mm ?? $orderItem->item->name,
                        'price' => $orderItem->price,
                        'quantity' => $orderItem->quantity,
                        'foc_quantity' => $orderItem->foc_quantity ?? 0,
                        'subtotal' => $orderItem->subtotal,
                        'notes' => $orderItem->notes ?? '',
                    ];
                }
            }
        }

        $this->loadCategories();
        $this->loadItems();
    }

    public function loadCategories()
    {
        $this->categories = Category::where('tenant_id', auth()->user()->tenant_id)
            ->where('is_active', true)
            ->withCount(['items as active_items_count' => function ($query) {
                $query->where('is_active', true);
            }])
            ->orderBy('sort_order')
            ->get();

        if ($this->categories->isNotEmpty() && !$this->selectedCategory) {
            $this->selectedCategory = $this->categories->first()->id;
        }
    }

    public function loadItems()
    {
        $query = Item::where('tenant_id', auth()->user()->tenant_id)
            ->where('is_active', true)
            ->with('category');

        if ($this->selectedCategory) {
            $query->where('category_id', $this->selectedCategory);
        }

        if ($this->searchTerm) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->searchTerm . '%')
                  ->orWhere('name_mm', 'like', '%' . $this->searchTerm . '%');
            });
        }

        $this->items = $query->orderBy('name')->get();
    }

    public function selectCategory($categoryId)
    {
        $this->selectedCategory = $categoryId;
        $this->loadItems();
    }

    public function updatedSearchTerm()
    {
        $this->loadItems();
    }

    public function addToCart($itemId)
    {
        $item = Item::find($itemId);

        if (!$item || !$item->is_available) {
            session()->flash('error', 'Item not available');
            return;
        }

        $cartKey = 'item_' . $itemId;

        if (isset($this->cart[$cartKey])) {
            $this->cart[$cartKey]['quantity']++;
            $this->cart[$cartKey]['subtotal'] = $this->cart[$cartKey]['quantity'] * $this->cart[$cartKey]['price'];
        } else {
            $this->cart[$cartKey] = [
                'item_id' => $item->id,
                'name' => $item->name,
                'name_mm' => $item->name_mm,
                'price' => $item->price,
                'quantity' => 1,
                'subtotal' => $item->price,
                'notes' => '',
                'foc_quantity' => 0,
            ];
        }
    }

    public function updateQuantity($cartKey, $quantity)
    {
        if ($quantity <= 0) {
            unset($this->cart[$cartKey]);
        } else {
            $this->cart[$cartKey]['quantity'] = $quantity;
            
            // Reset FOC if it exceeds new quantity
            if (($this->cart[$cartKey]['foc_quantity'] ?? 0) > $quantity) {
                $this->cart[$cartKey]['foc_quantity'] = 0;
            }
            
            $this->calculateItemSubtotal($cartKey);
        }
    }

    public function removeFromCart($cartKey)
    {
        unset($this->cart[$cartKey]);
    }

    public function clearCart()
    {
        $this->cart = [];
        $this->notes = '';
    }

    public function sendToKitchen()
    {
        $this->submitOrder();
    }
    
    public function getSubtotal()
    {
        return collect($this->cart)->sum('subtotal');
    }

    public function updateFocQuantity($cartKey, $focQuantity)
    {
        $focQuantity = max(0, min((int)$focQuantity, $this->cart[$cartKey]['quantity']));
        $this->cart[$cartKey]['foc_quantity'] = $focQuantity;
        $this->calculateItemSubtotal($cartKey);
    }
    
    private function calculateItemSubtotal($cartKey)
    {
        $item = $this->cart[$cartKey];
        $quantity = $item['quantity'];
        $focQuantity = $item['foc_quantity'] ?? 0;
        $chargeableQuantity = $quantity - $focQuantity;
        
        $this->cart[$cartKey]['subtotal'] = $chargeableQuantity * $item['price'];
    }

    public function updateItemNotes($cartKey, $notes)
    {
        $this->cart[$cartKey]['notes'] = $notes;
    }
    
    // Customer Lookup Methods
    public function searchCustomer()
    {
        if (empty($this->customer_phone)) {
            $this->customer = null;
            $this->customer_id = null;
            return;
        }
        
        $this->customer = Customer::where('phone', $this->customer_phone)->first();
        
        if ($this->customer) {
            $this->customer_id = $this->customer->id;
            $this->loyalty_points_to_redeem = 0; // Reset
        } else {
            $this->customer_id = null;
            session()->flash('customer_error', 'ဖောက်သည် မတွေ့ပါ။ ဖုန်းနံပါတ် စစ်ဆေးပါ။');
        }
    }
    
    public function clearCustomer()
    {
        $this->customer = null;
        $this->customer_id = null;
        $this->customer_phone = '';
        $this->loyalty_points_to_redeem = 0;
    }
    
    public function updatedLoyaltyPointsToRedeem($value)
    {
        if ($this->customer && $value > $this->customer->loyalty_points) {
            $this->loyalty_points_to_redeem = $this->customer->loyalty_points;
        }
    }

    public function getSubtotalProperty()
    {
        return collect($this->cart)->sum('subtotal');
    }
    
    public function getTotalProperty()
    {
        $subtotal = $this->subtotal;
        
        // Apply loyalty discount if redeeming points
        if ($this->customer && $this->loyalty_points_to_redeem > 0) {
            $discountAmount = ($this->loyalty_points_to_redeem / 100) * 1000;
            $subtotal -= $discountAmount;
        }
        
        return max(0, $subtotal);
    }

    public function submitOrder()
    {
        if (empty($this->cart)) {
            session()->flash('error', 'Cart is empty');
            return;
        }

        try {
            DB::beginTransaction();

            if ($this->isEditMode && $this->existingOrder) {
                // Update existing order
                $order = $this->existingOrder;
                $order->update([
                    'subtotal' => $this->subtotal,
                    'total' => $this->subtotal,
                    'notes' => $this->notes,
                ]);

                // Get existing items and their current quantities
                $existingItems = $order->items->keyBy('item_id');
                $cartItemIds = collect($this->cart)->pluck('item_id')->toArray();

                // Update or create items
                // For existing items, only print quantity increases; for new items, print them
                $itemsToPrint = [];
                
                // First, update all existing items in cart
                foreach ($this->cart as $cartItem) {
                    $focQuantity = $cartItem['foc_quantity'] ?? 0;

                    $existingOrderItem = $existingItems->get($cartItem['item_id']);
                    $previousQty = $existingOrderItem ? (int) $existingOrderItem->quantity : 0;
                    $newQty = (int) $cartItem['quantity'];

                    // How many more pieces were added in this update?
                    $deltaQty = max(0, $newQty - $previousQty);

                    $orderItem = $order->items()->updateOrCreate(
                        ['item_id' => $cartItem['item_id']],
                        [
                            'quantity' => $newQty,
                            'foc_quantity' => $focQuantity,
                            'price' => $cartItem['price'],
                            'subtotal' => $cartItem['subtotal'],
                            'is_foc' => $focQuantity >= $newQty,
                            'notes' => $cartItem['notes'],
                            'status' => 'pending',
                            // is_printed is handled by PrinterService after successful printing
                        ]
                    );

                    // If quantity increased or item is new, send to printer
                    if ($deltaQty > 0 || !$existingOrderItem) {
                        $printItem = clone $orderItem;
                        if ($deltaQty > 0 && $existingOrderItem) {
                            // For existing items, print only the additional quantity
                            $printItem->quantity = $deltaQty;
                        }
                        $itemsToPrint[] = $printItem;
                    }
                    
                    // Remove from existing items to track which ones are processed
                    if ($existingOrderItem) {
                        $existingItems->forget($cartItem['item_id']);
                    }
                }
                
                // Any remaining items in $existingItems are removed from cart
                // They are already deleted by the whereNotIn query above

                // Load item relationships for printing
                if (!empty($itemsToPrint)) {
                    $itemsToPrint = collect($itemsToPrint)->map(function (OrderItem $orderItem) {
                        return $orderItem->load('item.category');
                    })->all();
                }

                // Print changes to kitchen and Bar (always print for updates)
                // This ensures kitchen gets notified of any order changes
                if (!empty($itemsToPrint)) {
                    try {
                        $printerService = new PrinterService();
                        
                        // Always print kitchen for updates (ignore auto-print setting)
                        // Log what would be printed (for testing without printer)
                        $kitchenPreview = $printerService->previewKitchenOrder($order, $itemsToPrint);
                        logger()->info('Update Print Kitchen - Order #' . $order->order_number, [
                            'preview' => $kitchenPreview
                        ]);
                        
                        $printerService->printKitchenOrder($order, $itemsToPrint);
                        
                        // Always print bar for updates (ignore auto-print setting)
                        $BarPreview = $printerService->previewBarOrder($order, $itemsToPrint);
                        logger()->info('Update Print Bar - Order #' . $order->order_number, [
                            'preview' => $BarPreview
                        ]);
                        
                        $printerService->printBarOrder($order, $itemsToPrint);
                    } catch (\Exception $e) {
                        logger()->error('Print failed: ' . $e->getMessage());
                    }
                }

                // Dispatch events for client-side printing (Print Agent)
                if (!empty($itemsToPrint)) {
                    $kitchenData = $this->getOrderDataForPrinting($order, $itemsToPrint, 'kitchen');
                    if ($kitchenData) {
                        $this->dispatch('print-kitchen-order', orderData: $kitchenData);
                    }

                    $barData = $this->getOrderDataForPrinting($order, $itemsToPrint, 'bar');
                    if ($barData) {
                        $this->dispatch('print-bar-order', orderData: $barData);
                    }
                }

                // Reload items to ensure totals include previous (already printed) items too
                $order->load('items');
                $order->update([
                    'subtotal' => $order->items->sum('subtotal'),
                    'total' => $order->items->sum('subtotal'),
                ]);

                $message = 'Order updated successfully!';
            } else {
                // Calculate discount from loyalty points
                $loyaltyDiscount = 0;
                if ($this->customer && $this->loyalty_points_to_redeem > 0) {
                    $loyaltyDiscount = ($this->loyalty_points_to_redeem / 100) * 1000;
                }
                
                // Create new order
                $order = Order::create([
                    'table_id' => $this->table?->id,
                    'waiter_id' => auth()->id(),
                    'customer_id' => $this->customer_id,
                    'order_type' => $this->orderType,
                    'status' => 'pending',
                    'subtotal' => $this->subtotal,
                    'discount_amount' => $loyaltyDiscount,
                    'total' => $this->total,
                    'notes' => $this->notes,
                ]);

                // Create order items
                $newItems = [];
                foreach ($this->cart as $cartItem) {
                    $focQuantity = $cartItem['foc_quantity'] ?? 0;
                    $orderItem = OrderItem::create([
                        'order_id' => $order->id,
                        'item_id' => $cartItem['item_id'],
                        'quantity' => $cartItem['quantity'],
                        'foc_quantity' => $focQuantity,
                        'price' => $cartItem['price'],
                        'subtotal' => $cartItem['subtotal'],
                        'is_foc' => $focQuantity >= $cartItem['quantity'], // All FOC if foc_quantity equals quantity
                        'notes' => $cartItem['notes'],
                        'status' => 'pending',
                    ]);
                    
                    $newItems[] = $orderItem;
                }

                // Load item relationships for printing
                $newItems = collect($newItems)->map(function (OrderItem $orderItem) {
                    return $orderItem->load('item.category');
                })->all();

                // Update table status if dine-in
                if ($this->table) {
                    $this->table->update(['status' => 'occupied']);
                }

                // Print to kitchen and Bar (if auto-print enabled)
                try {
                    $printerService = new PrinterService();
                    
                    // Check auto-print settings
                    $autoPrintKitchen = Setting::where('key', 'auto_print_kitchen')->first();
                    if (!$autoPrintKitchen || $autoPrintKitchen->value) {
                        // Log what would be printed (for testing without printer)
                        $kitchenPreview = $printerService->previewKitchenOrder($order, $newItems);
                        logger()->info('Auto Print Kitchen - Order #' . $order->order_number, [
                            'preview' => $kitchenPreview
                        ]);
                        
                        $printerService->printKitchenOrder($order, $newItems);
                    }
                    
                    $autoPrintBar = Setting::where('key', 'auto_print_Bar')->first();
                    if (!$autoPrintBar || $autoPrintBar->value) {
                        // Log what would be printed (for testing without printer)
                        $BarPreview = $printerService->previewBarOrder($order, $newItems);
                        logger()->info('Auto Print Bar - Order #' . $order->order_number, [
                            'preview' => $BarPreview
                        ]);
                        
                        $printerService->printBarOrder($order, $newItems);
                    }

                    // Nan Pyar Printer Auto Print
                    $printerService->printNanPyarOrder($order, $newItems);

                } catch (\Exception $e) {
                    logger()->error('Print failed: ' . $e->getMessage());
                }
                
                // Dispatch events for client-side printing (Print Agent)
                if (!empty($newItems)) {
                    $kitchenData = $this->getOrderDataForPrinting($order, $newItems, 'kitchen');
                    if ($kitchenData) {
                        $this->dispatch('print-kitchen-order', orderData: $kitchenData);
                    }

                    $nanPyarData = $this->getOrderDataForPrinting($order, $newItems, 'nan_pyar');
                    if ($nanPyarData) {
                        $this->dispatch('print-nan-pyar-order', orderData: $nanPyarData);
                    }

                    $barData = $this->getOrderDataForPrinting($order, $newItems, 'bar');
                    if ($barData) {
                        $this->dispatch('print-bar-order', orderData: $barData);
                    }
                }

                // Process customer loyalty points
                if ($this->customer) {
                    // Redeem points if requested
                    if ($this->loyalty_points_to_redeem > 0) {
                        $this->customer->redeemPoints(
                            $this->loyalty_points_to_redeem,
                            $order,
                            'Redeemed for Order #' . $order->order_number
                        );
                    }
                    
                    // Earn points from purchase (1 point per 1000 Ks)
                    $pointsEarned = floor($order->total / 1000);
                    if ($pointsEarned > 0) {
                        $this->customer->earnPoints(
                            $pointsEarned,
                            $order,
                            'Earned from Order #' . $order->order_number
                        );
                    }
                    
                    // Update customer stats
                    $this->customer->increment('total_spent', $order->total);
                    $this->customer->increment('visit_count');
                    $this->customer->update(['last_visit_at' => now()]);
                }

                $message = 'Order created successfully!';
            }

            DB::commit();

            session()->flash('success', $message);
            
            return redirect()->route('waiter.tables.index');

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Failed to save order: ' . $e->getMessage());
        }
    }

    private function getOrderDataForPrinting($order, $items, $type)
    {
        $filteredItems = collect($items)->filter(function ($item) use ($type) {
            return $item->item->category->printer_type === $type;
        })->values()->map(function ($item) {
            return [
                'name' => $item->item->name,
                'name_mm' => $item->item->name_mm,
                'quantity' => $item->quantity,
                'notes' => $item->notes,
                'is_foc' => $item->is_foc,
            ];
        })->toArray();

        if (empty($filteredItems)) {
            return null;
        }

        return [
            'orderNumber' => $order->order_number,
            'table' => $order->table ? ($order->table->name_mm ?? $order->table->name) : 'Takeaway',
            'time' => now()->format('g:i A'),
            'items' => $filteredItems,
            'type' => $type
        ];
    }

    public function render()
    {
        return view('livewire.waiter.create-order');
    }
}
