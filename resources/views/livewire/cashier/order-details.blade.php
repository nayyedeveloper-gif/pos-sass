<style>
    @media print {
        /* Show Myanmar text in print */
        .myanmar-text {
            display: inline !important;
            visibility: visible !important;
        }
        
        /* Ensure all text sizes are visible in print */
        .text-sm, .text-xs {
            display: block !important;
            visibility: visible !important;
            font-size: 10pt !important;
        }
        
        /* Show item names with Myanmar */
        td p, td div {
            display: block !important;
            visibility: visible !important;
        }
        
        /* Hide buttons in print */
        .btn, button, a.btn {
            display: none !important;
        }
        
        /* Optimize print layout */
        body {
            font-size: 12pt;
        }
        
        .card {
            page-break-inside: avoid;
        }
        
        /* Ensure table content is visible */
        table td, table th {
            padding: 8px !important;
        }
    }
</style>

<div class="py-6">
    {{-- Care about people's approval and you will be their prisoner. --}}
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-2">
                    <span>
                        <span class="myanmar-text">အော်ဒါအသေးစိတ်</span> / Order #{{ $order->order_number }}
                    </span>
                    @if(isset($order->order_count) && $order->order_count > 1)
                        <span class="px-2 py-0.5 rounded-full text-xs font-bold bg-purple-100 text-purple-700">
                            {{ $order->order_count }} orders combined
                        </span>
                    @endif
                </h1>
                <p class="mt-2 text-sm text-gray-600">
                    {{ $order->created_at->format('d M Y, h:i A') }}
                </p>
            </div>
            <div class="flex space-x-2">
                <button wire:click="printReceipt" class="btn btn-primary">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    <span class="myanmar-text">ပရင့်ထုတ်မည်</span>
                </button>
                <a href="{{ route('cashier.orders.index') }}" class="btn btn-secondary">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    <span class="myanmar-text">နောက်သို့</span>
                </a>
            </div>
        </div>

        <!-- Order Info Card -->
        <div class="card mb-6">
            <div class="card-body">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <!-- Table/Type -->
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-2 myanmar-text">စားပွဲ / Type</h3>
                        @if($order->table)
                            <p class="text-lg font-semibold text-gray-900">
                                {{ $order->table->name }} / <span class="myanmar-text">{{ $order->table->name_mm }}</span>
                            </p>
                        @else
                            <p class="text-lg font-semibold text-gray-900 myanmar-text">ပါဆယ်ယူမည် / Takeaway</p>
                        @endif
                    </div>

                    <!-- Waiter -->
                    @if($order->waiter)
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-2 myanmar-text">ဝန်ထမ်း / Waiter</h3>
                            <p class="text-lg font-semibold text-gray-900">{{ $order->waiter->name }}</p>
                        </div>
                    @endif

                    <!-- Cashier -->
                    @if($order->cashier)
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-2 myanmar-text">ငွေကောက်သူ / Cashier</h3>
                            <p class="text-lg font-semibold text-gray-900">{{ $order->cashier->name }}</p>
                        </div>
                    @endif

                    <!-- Status -->
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-2 myanmar-text">အခြေအနေ / Status</h3>
                        @if($order->status === 'completed')
                            <span class="badge badge-success text-lg">
                                <span class="myanmar-text">ပြီးစီး</span> / Completed
                            </span>
                        @elseif($order->status === 'cancelled')
                            <span class="badge badge-danger text-lg">
                                <span class="myanmar-text">ပယ်ဖျက်</span> / Cancelled
                            </span>
                        @else
                            <span class="badge badge-warning text-lg">
                                <span class="myanmar-text">စောင့်ဆိုင်းဆဲ</span> / {{ ucfirst($order->status) }}
                            </span>
                        @endif
                    </div>
                </div>

                @if($order->notes)
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <h3 class="text-sm font-medium text-gray-500 mb-2 myanmar-text">မှတ်ချက် / Notes</h3>
                        <p class="text-gray-900">{{ $order->notes }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Order Items -->
        <div class="card mb-6">
            <div class="card-header">
                <h2 class="text-lg font-bold text-gray-900 myanmar-text">အော်ဒါပစ္စည်းများ / Order Items</h2>
            </div>
            <div class="card-body p-0">
                <div class="overflow-x-auto">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="myanmar-text">ပစ္စည်း / Item</th>
                                <th class="text-center myanmar-text">အရေအတွက် / Qty</th>
                                <th class="text-right myanmar-text">ဈေးနှုန်း / Price</th>
                                <th class="text-right myanmar-text">စုစုပေါင်း / Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $orderItem)
                                <tr>
                                    <td>
                                        <div>
                                            <p class="font-semibold text-gray-900">{{ $orderItem->item->name }}</p>
                                            <p class="text-sm text-gray-600 myanmar-text" style="display: block !important;">{{ $orderItem->item->name_mm }}</p>
                                            @if($orderItem->notes)
                                                <p class="text-xs text-gray-500 italic mt-1">{{ $orderItem->notes }}</p>
                                            @endif
                                            @if($orderItem->is_foc)
                                                <span class="badge badge-success mt-1">FOC</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="font-semibold">{{ $orderItem->quantity }}</span>
                                    </td>
                                    <td class="text-right">
                                        {{ number_format($orderItem->price, 0) }} Ks
                                    </td>
                                    <td class="text-right font-semibold">
                                        @if($orderItem->is_foc)
                                            <span class="text-green-600">FOC</span>
                                        @else
                                            {{ number_format($orderItem->subtotal, 0) }} Ks
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="card">
            <div class="card-body">
                <div class="space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600 myanmar-text">စုစုပေါင်း / Subtotal</span>
                        <span class="font-semibold">{{ number_format($order->subtotal, 0) }} Ks</span>
                    </div>

                    @if($order->tax_amount > 0)
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600 myanmar-text">အခွန် / Tax ({{ $order->tax_percentage }}%)</span>
                            <span class="font-semibold">{{ number_format($order->tax_amount, 0) }} Ks</span>
                        </div>
                    @endif

                    @if($order->discount_amount > 0)
                        <div class="flex justify-between text-sm text-red-600">
                            <span class="myanmar-text">လျှော့ဈေး / Discount ({{ $order->discount_percentage }}%)</span>
                            <span class="font-semibold">-{{ number_format($order->discount_amount, 0) }} Ks</span>
                        </div>
                    @endif

                    @if($order->service_charge > 0)
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600 myanmar-text">ဝန်ဆောင်ခ / Service Charge</span>
                            <span class="font-semibold">{{ number_format($order->service_charge, 0) }} Ks</span>
                        </div>
                    @endif

                    <div class="pt-3 border-t border-gray-200">
                        <div class="flex justify-between items-center">
                            <span class="text-xl font-bold text-gray-900 myanmar-text">စုစုပေါင်း / Total</span>
                            <span class="text-2xl font-bold text-primary-600">{{ number_format($order->total, 0) }} Ks</span>
                        </div>
                    </div>

                    @if($order->completed_at)
                        <div class="pt-3 border-t border-gray-200">
                            <div class="flex justify-between text-sm text-gray-600">
                                <span class="myanmar-text">ပြီးစီးသည့်အချိန် / Completed At</span>
                                <span>{{ $order->completed_at->format('d M Y, h:i A') }}</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    @if (session()->has('success'))
        <div class="fixed top-4 right-4 z-50 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg">
            {{ session('success') }}
        </div>
    @endif
</div>

<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('print-order-details', () => {
            // Trigger browser print dialog
            window.print();
        });
    });
</script>
