<div class="min-h-screen bg-gray-50 pb-20">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <h1 class="text-2xl font-bold text-gray-900 font-mono">#{{ $order->order_number }}</h1>
                    @if(isset($order->order_count) && $order->order_count > 1)
                        <span class="px-2 py-0.5 rounded-full text-xs font-bold bg-purple-100 text-purple-700">
                            {{ $order->order_count }} orders combined
                        </span>
                    @endif
                </div>
                <p class="text-sm text-gray-500 mt-1 flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    {{ $order->created_at->format('d M Y, h:i A') }}
                </p>
            </div>
            <a href="{{ route('waiter.orders.index') }}" class="p-2 rounded-xl hover:bg-white hover:shadow-sm text-gray-500 hover:text-gray-700 transition-all border border-transparent hover:border-gray-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </a>
        </div>

        <!-- Info Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <!-- Table -->
            <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1 myanmar-text">စားပွဲ</p>
                <div class="flex items-center gap-2">
                    <div class="p-1.5 bg-gray-100 rounded-lg text-gray-500">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                    </div>
                    @if($order->table)
                        <p class="font-bold text-gray-900 myanmar-text">{{ $order->table->name_mm ?? $order->table->name }}</p>
                    @else
                        <p class="font-bold text-gray-900 myanmar-text">ပါဆယ်ယူမည်</p>
                    @endif
                </div>
            </div>

            <!-- Waiter -->
            <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1 myanmar-text">ဝန်ထမ်း</p>
                <div class="flex items-center gap-2">
                    <div class="p-1.5 bg-gray-100 rounded-lg text-gray-500">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <p class="font-bold text-gray-900">{{ $order->waiter->name }}</p>
                </div>
            </div>

            <!-- Status -->
            <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1 myanmar-text">အခြေအနေ</p>
                @if($order->status === 'pending')
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-sm font-medium bg-amber-100 text-amber-800">
                        <span class="w-2 h-2 rounded-full bg-amber-500 mr-2 animate-pulse"></span>
                        <span class="myanmar-text">စောင့်ဆိုင်းဆဲ</span>
                    </span>
                @elseif($order->status === 'completed')
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-sm font-medium bg-emerald-100 text-emerald-800">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 mr-2"></span>
                        <span class="myanmar-text">ပြီးစီး</span>
                    </span>
                @else
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-sm font-medium bg-rose-100 text-rose-800">
                        <span class="myanmar-text">ပယ်ဖျက်</span>
                    </span>
                @endif
            </div>
        </div>

        <!-- Items List -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                <h2 class="font-bold text-gray-900 myanmar-text">မှာယူထားသော ပစ္စည်းများ</h2>
            </div>
            <div class="divide-y divide-gray-100">
                @foreach($order->items as $orderItem)
                    <div class="p-4 flex justify-between items-start hover:bg-gray-50 transition-colors">
                        <div class="flex-1 pr-4">
                            <div class="flex items-baseline gap-2">
                                <span class="font-mono text-gray-500 text-sm">{{ $orderItem->quantity }}x</span>
                                <div>
                                    <p class="font-bold text-gray-900 text-sm">{{ $orderItem->item->name }}</p>
                                    <p class="text-xs text-gray-500 myanmar-text">{{ $orderItem->item->name_mm }}</p>
                                    @if($orderItem->notes)
                                        <p class="text-xs text-gray-400 italic mt-0.5">"{{ $orderItem->notes }}"</p>
                                    @endif
                                    @if($orderItem->is_foc)
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-medium bg-green-100 text-green-700 mt-1">FOC</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            @if($orderItem->is_foc)
                                <span class="font-bold text-green-600 text-sm">0 Ks</span>
                                <span class="block text-[10px] text-gray-400 line-through">{{ number_format($orderItem->price * $orderItem->quantity, 0) }}</span>
                            @else
                                <span class="font-bold text-gray-900 text-sm">{{ number_format($orderItem->subtotal, 0) }} Ks</span>
                                <span class="block text-[10px] text-gray-400">{{ number_format($orderItem->price, 0) }} ea</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Order Summary -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
            <div class="space-y-3">
                <div class="flex justify-between text-sm text-gray-600">
                    <span class="myanmar-text">စုစုပေါင်း</span>
                    <span class="font-medium">{{ number_format($order->subtotal, 0) }} Ks</span>
                </div>

                @if($order->tax_amount > 0)
                    <div class="flex justify-between text-sm text-gray-600">
                        <span class="myanmar-text">အခွန် ({{ $order->tax_percentage }}%)</span>
                        <span class="font-medium">{{ number_format($order->tax_amount, 0) }} Ks</span>
                    </div>
                @endif

                @if($order->discount_amount > 0)
                    <div class="flex justify-between text-sm text-red-600">
                        <span class="myanmar-text">လျှော့ဈေး</span>
                        <span class="font-medium">-{{ number_format($order->discount_amount, 0) }} Ks</span>
                    </div>
                @endif

                @if($order->service_charge > 0)
                    <div class="flex justify-between text-sm text-gray-600">
                        <span class="myanmar-text">ဝန်ဆောင်ခ</span>
                        <span class="font-medium">{{ number_format($order->service_charge, 0) }} Ks</span>
                    </div>
                @endif

                <div class="pt-4 border-t border-gray-100 mt-4">
                    <div class="flex justify-between items-center">
                        <span class="text-lg font-bold text-gray-900 myanmar-text">စုစုပေါင်း ပေးရန်</span>
                        <span class="text-2xl font-bold text-primary-600">{{ number_format($order->total, 0) }} <span class="text-sm font-normal text-gray-500">Ks</span></span>
                    </div>
                </div>
            </div>
        </div>

        @if($order->notes)
            <div class="bg-yellow-50 border border-yellow-100 rounded-xl p-4 text-sm text-yellow-800 mb-6">
                <p class="font-bold mb-1 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    <span class="myanmar-text">မှတ်ချက်</span>
                </p>
                <p>{{ $order->notes }}</p>
            </div>
        @endif
    </div>

    <!-- Flash Messages -->
    @if (session()->has('success'))
        <div class="fixed bottom-4 right-4 z-50 bg-emerald-600 text-white px-6 py-3 rounded-xl shadow-lg flex items-center gap-3 animate-fade-in-up">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <div>
                <p class="font-bold myanmar-text">အောင်မြင်သည်</p>
                <p class="text-sm opacity-90 myanmar-text">{{ session('success') }}</p>
            </div>
        </div>
    @endif
</div>
