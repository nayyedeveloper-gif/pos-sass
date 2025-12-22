<div class="min-h-screen bg-gray-50 pb-20" wire:poll.5s>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 myanmar-text">အော်ဒါများ</h1>
                <p class="mt-1 text-sm text-gray-500 myanmar-text">
                    သင်ယူထားသော အော်ဒါများကို ကြည့်ရှုနိုင်ပါသည်။
                </p>
            </div>
            <a href="{{ route('waiter.tables.index') }}" class="inline-flex items-center justify-center px-4 py-2.5 border border-transparent rounded-xl shadow-sm text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all">
                <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                <span class="myanmar-text">အော်ဒါသစ်</span>
            </a>
        </div>

        <!-- Search & Filter Bar -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-6">
            <div class="flex flex-col sm:flex-row gap-4 items-center">
                <!-- Search -->
                <div class="relative flex-1 w-full">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input 
                        type="text" 
                        wire:model.live.debounce.300ms="search" 
                        placeholder="အော်ဒါနံပါတ် (သို့) စားပွဲ..."
                        class="w-full pl-10 pr-4 py-2.5 border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 myanmar-text text-sm"
                    >
                </div>

                <!-- Status Pills -->
                <div class="flex overflow-x-auto pb-1 sm:pb-0 gap-2 w-full sm:w-auto hide-scrollbar">
                    <button wire:click="$set('statusFilter', 'all')" 
                            class="px-4 py-2 rounded-lg text-xs font-medium border transition-colors whitespace-nowrap {{ $statusFilter === 'all' ? 'bg-gray-900 text-white border-gray-900' : 'bg-gray-50 text-gray-600 border-gray-200 hover:bg-gray-100' }}">
                        <span class="myanmar-text">အားလုံး</span>
                    </button>
                    <button wire:click="$set('statusFilter', 'pending')" 
                            class="px-4 py-2 rounded-lg text-xs font-medium border transition-colors whitespace-nowrap flex items-center gap-1.5 {{ $statusFilter === 'pending' ? 'bg-amber-100 text-amber-800 border-amber-200' : 'bg-gray-50 text-gray-600 border-gray-200 hover:bg-gray-100' }}">
                        <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                        <span class="myanmar-text">စောင့်ဆိုင်းဆဲ</span>
                    </button>
                    <button wire:click="$set('statusFilter', 'completed')" 
                            class="px-4 py-2 rounded-lg text-xs font-medium border transition-colors whitespace-nowrap flex items-center gap-1.5 {{ $statusFilter === 'completed' ? 'bg-emerald-100 text-emerald-800 border-emerald-200' : 'bg-gray-50 text-gray-600 border-gray-200 hover:bg-gray-100' }}">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        <span class="myanmar-text">ပြီးစီး</span>
                    </button>
                    <button wire:click="$set('statusFilter', 'cancelled')" 
                            class="px-4 py-2 rounded-lg text-xs font-medium border transition-colors whitespace-nowrap flex items-center gap-1.5 {{ $statusFilter === 'cancelled' ? 'bg-rose-100 text-rose-800 border-rose-200' : 'bg-gray-50 text-gray-600 border-gray-200 hover:bg-gray-100' }}">
                        <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                        <span class="myanmar-text">ပယ်ဖျက်</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Orders List -->
        <div class="space-y-4">
            @forelse($orders as $order)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-shadow cursor-pointer group" wire:click="viewOrder({{ $order->id }})">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <span class="font-mono font-bold text-lg text-gray-900">#{{ $order->order_number }}</span>
                                @if(isset($order->order_count) && $order->order_count > 1)
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-purple-100 text-purple-700">
                                        {{ $order->order_count }} orders
                                    </span>
                                @endif
                                @if($order->order_type === 'takeaway')
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-orange-100 text-orange-700 uppercase tracking-wide">TK</span>
                                @else
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-100 text-blue-700 uppercase tracking-wide">IN</span>
                                @endif
                            </div>
                            <div class="flex items-center text-gray-600 text-sm">
                                <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                @if($order->table)
                                    <span class="myanmar-text font-medium">{{ $order->table->name_mm ?? $order->table->name }}</span>
                                @else
                                    <span class="myanmar-text font-medium">ပါဆယ်ယူမည်</span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="text-right">
                            <span class="block text-lg font-bold text-emerald-600">{{ number_format($order->total, 0) }} <span class="text-xs font-normal text-gray-500">Ks</span></span>
                            <span class="text-xs text-gray-400 mt-1 block">{{ $order->created_at->format('h:i A') }}</span>
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-4 border-t border-gray-50">
                        <div class="flex items-center text-sm text-gray-500">
                            @php
                                // Sum total quantities across all order items (e.g. x2 Coffee + x1 Cola = 3 items)
                                $totalItemQty = $order->items->sum('quantity');
                            @endphp
                            <span class="bg-gray-100 text-gray-600 py-1 px-2 rounded-lg text-xs font-medium mr-2">
                                {{ $totalItemQty }} items
                            </span>
                            @if($order->notes)
                                <span class="text-gray-400 text-xs italic flex items-center max-w-[150px] truncate">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path></svg>
                                    {{ $order->notes }}
                                </span>
                            @endif
                        </div>

                        <div>
                            @if($order->status === 'pending')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-amber-50 text-amber-700 border border-amber-100">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1.5 animate-pulse"></span>
                                    <span class="myanmar-text">စောင့်ဆိုင်းဆဲ</span>
                                </span>
                            @elseif($order->status === 'completed')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-100">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>
                                    <span class="myanmar-text">ပြီးစီး</span>
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-rose-50 text-rose-700 border border-rose-100">
                                    <span class="myanmar-text">ပယ်ဖျက်</span>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-2xl shadow-sm border border-dashed border-gray-200 p-12 text-center">
                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 myanmar-text mb-1">အော်ဒါများ မရှိပါ</h3>
                    <p class="text-sm text-gray-500 myanmar-text mb-6">ယခုအချိန်အထိ အော်ဒါတင်ထားခြင်း မရှိသေးပါ။</p>
                    <a href="{{ route('waiter.tables.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-xl shadow-sm text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all">
                        <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        <span class="myanmar-text">အော်ဒါအသစ်ယူမည်</span>
                    </a>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($orders->hasPages())
            <div class="mt-8">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</div>
