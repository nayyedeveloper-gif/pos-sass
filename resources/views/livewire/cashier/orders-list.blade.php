<div class="py-6 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header & Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 myanmar-text">အော်ဒါမှတ်တမ်း</h1>
                <p class="text-sm text-gray-500 mt-1">
                    စုစုပေါင်း <span class="font-semibold text-gray-900">{{ $orders->total() }}</span> ခု
                </p>
            </div>
            <a href="{{ route('cashier.pos') }}" class="btn btn-primary shadow-md hover:shadow-lg transition-all flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                <span class="myanmar-text">အော်ဒါအသစ်</span>
            </a>
        </div>

        <!-- Compact Filter Bar -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-3 mb-6">
            <div class="flex flex-col md:flex-row gap-3 items-center">
                <!-- Search -->
                <div class="relative w-full md:w-64">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="ID သို့မဟုတ် စားပွဲ..." class="form-input pl-9 py-2 text-sm border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-lg">
                </div>

                <!-- Quick Filters (Pills) -->
                <div class="flex overflow-x-auto pb-1 md:pb-0 gap-2 flex-1 w-full md:w-auto hide-scrollbar">
                    <button wire:click="$set('statusFilter', '')" class="px-3 py-1.5 rounded-lg text-xs font-medium border transition-colors whitespace-nowrap {{ $statusFilter === '' ? 'bg-gray-900 text-white border-gray-900' : 'bg-white text-gray-600 border-gray-200 hover:bg-gray-50' }}">
                        အားလုံး
                    </button>
                    <button wire:click="$set('statusFilter', 'pending')" class="px-3 py-1.5 rounded-lg text-xs font-medium border transition-colors whitespace-nowrap flex items-center gap-1 {{ $statusFilter === 'pending' ? 'bg-yellow-100 text-yellow-800 border-yellow-200 ring-1 ring-yellow-300' : 'bg-white text-gray-600 border-gray-200 hover:bg-gray-50' }}">
                        <span class="w-2 h-2 rounded-full bg-yellow-500"></span>
                        စောင့်ဆိုင်းဆဲ
                    </button>
                    <button wire:click="$set('statusFilter', 'completed')" class="px-3 py-1.5 rounded-lg text-xs font-medium border transition-colors whitespace-nowrap flex items-center gap-1 {{ $statusFilter === 'completed' ? 'bg-green-100 text-green-800 border-green-200 ring-1 ring-green-300' : 'bg-white text-gray-600 border-gray-200 hover:bg-gray-50' }}">
                        <span class="w-2 h-2 rounded-full bg-green-500"></span>
                        ပြီးစီး
                    </button>
                    <button wire:click="$set('statusFilter', 'cancelled')" class="px-3 py-1.5 rounded-lg text-xs font-medium border transition-colors whitespace-nowrap flex items-center gap-1 {{ $statusFilter === 'cancelled' ? 'bg-red-100 text-red-800 border-red-200 ring-1 ring-red-300' : 'bg-white text-gray-600 border-gray-200 hover:bg-gray-50' }}">
                        <span class="w-2 h-2 rounded-full bg-red-500"></span>
                        ပယ်ဖျက်
                    </button>
                </div>

                <div class="h-6 w-px bg-gray-200 hidden md:block"></div>

                <!-- Date & Table Selects -->
                <div class="flex gap-2 w-full md:w-auto">
                    <select wire:model.live="dateFilter" class="form-select py-2 text-sm border-gray-300 rounded-lg w-full md:w-32 cursor-pointer">
                        <option value="today">ယနေ့</option>
                        <option value="yesterday">မနေ့က</option>
                        <option value="week">ဤအပတ်</option>
                        <option value="month">ဤလ</option>
                        <option value="">အားလုံး</option>
                    </select>
                    <select wire:model.live="tableFilter" class="form-select py-2 text-sm border-gray-300 rounded-lg w-full md:w-32 cursor-pointer">
                        <option value="">စားပွဲအားလုံး</option>
                        @foreach($tables as $table)
                        <option value="{{ $table->id }}">{{ $table->name_mm }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Orders List -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <!-- Desktop Table -->
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-50/80 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 font-semibold text-gray-600 uppercase tracking-wider text-xs">အော်ဒါ ID</th>
                            <th class="px-6 py-3 font-semibold text-gray-600 uppercase tracking-wider text-xs">စားပွဲ/အမျိုးအစား</th>
                            <th class="px-6 py-3 font-semibold text-gray-600 uppercase tracking-wider text-xs text-center">ပစ္စည်း</th>
                            <th class="px-6 py-3 font-semibold text-gray-600 uppercase tracking-wider text-xs text-right">ပမာဏ</th>
                            <th class="px-6 py-3 font-semibold text-gray-600 uppercase tracking-wider text-xs text-center">အခြေအနေ</th>
                            <th class="px-6 py-3 font-semibold text-gray-600 uppercase tracking-wider text-xs text-right">အချိန်</th>
                            <th class="px-6 py-3 font-semibold text-gray-600 uppercase tracking-wider text-xs text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($orders as $order)
                        <tr class="hover:bg-gray-50 transition-colors cursor-pointer group" wire:click="viewOrder({{ $order->id }})">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <span class="font-mono font-bold text-gray-900">#{{ $order->order_number }}</span>
                                    @if(isset($order->order_count) && $order->order_count > 1)
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-purple-100 text-purple-700">
                                            {{ $order->order_count }} orders
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg flex items-center justify-center text-xs font-bold {{ $order->order_type === 'dine_in' ? 'bg-blue-50 text-blue-700' : 'bg-orange-50 text-orange-700' }}">
                                        {{ $order->order_type === 'dine_in' ? 'IN' : 'TK' }}
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900 myanmar-text">{{ $order->table ? ($order->table->name_mm ?: $order->table->name) : 'Takeaway' }}</p>
                                        @if($order->waiter)
                                        <p class="text-xs text-gray-500">{{ $order->waiter->name }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                    {{ $order->items->sum('quantity') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <span class="font-bold text-primary-700 text-base">{{ number_format($order->total, 0) }}</span>
                                <span class="text-xs text-gray-400">Ks</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @if($order->status === 'pending')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 border border-yellow-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-yellow-500 mr-1.5"></span>
                                        Pending
                                    </span>
                                @elseif($order->status === 'completed')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-1.5"></span>
                                        Paid
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-200">
                                        Cancelled
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-gray-500 text-sm font-mono">
                                {{ $order->created_at->format('h:i A') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <svg class="w-5 h-5 text-gray-300 group-hover:text-primary-500 mx-auto transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-400">
                                    <svg class="w-12 h-12 mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                    <p class="myanmar-text font-medium">အော်ဒါမှတ်တမ်း မရှိပါ</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Mobile Cards View -->
            <div class="md:hidden divide-y divide-gray-100">
                @forelse($orders as $order)
                <div class="p-4 hover:bg-gray-50 transition-colors cursor-pointer" wire:click="viewOrder({{ $order->id }})">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="font-mono font-bold text-gray-900">#{{ $order->order_number }}</span>
                                @if(isset($order->order_count) && $order->order_count > 1)
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-purple-100 text-purple-700">
                                        {{ $order->order_count }} orders
                                    </span>
                                @endif
                            </div>
                            <p class="text-sm text-gray-600 mt-0.5 myanmar-text">{{ $order->table ? ($order->table->name_mm ?: $order->table->name) : 'Takeaway' }}</p>
                        </div>
                        <div class="text-right">
                            <span class="block font-bold text-gray-900">{{ number_format($order->total, 0) }} Ks</span>
                            <span class="text-xs text-gray-500">{{ $order->created_at->format('h:i A') }}</span>
                        </div>
                    </div>
                    <div class="flex justify-between items-center mt-3">
                        <span class="text-xs text-gray-500">{{ $order->orderItems->count() }} items</span>
                        @if($order->status === 'pending')
                            <span class="badge badge-warning myanmar-text">စောင့်ဆိုင်းဆဲ</span>
                        @elseif($order->status === 'completed')
                            <span class="badge badge-success myanmar-text">ပြီးစီး</span>
                        @else
                            <span class="badge badge-danger myanmar-text">ပယ်ဖျက်</span>
                        @endif
                    </div>
                </div>
                @empty
                <div class="p-8 text-center text-gray-500 myanmar-text">အော်ဒါမှတ်တမ်း မရှိပါ</div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $orders->links() }}
            </div>
        </div>
    </div>

    <!-- Order Details Modal -->
    @if($showOrderDetails && $selectedOrder)
    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-medium text-gray-900 myanmar-text">အော်ဒါ အသေးစိတ်</h3>
                    <p class="text-sm text-gray-500">{{ $selectedOrder->order_number }}</p>
                </div>
                <button wire:click="closeOrderDetails" class="text-gray-400 hover:text-gray-500">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="px-6 py-4">
                <!-- Order Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <p class="text-sm text-gray-500 myanmar-text">စားပွဲ</p>
                        <p class="text-base font-medium myanmar-text">
                            {{ $selectedOrder->table ? ($selectedOrder->table->name_mm ?: $selectedOrder->table->name) : '-' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 myanmar-text">အမျိုးအစား</p>
                        <p class="text-base font-medium myanmar-text">
                            {{ $selectedOrder->order_type === 'dine_in' ? 'ဆိုင်တွင်းစားမည်' : 'ပါဆယ်ယူမည်' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 myanmar-text">စားပွဲထိုး</p>
                        <p class="text-base font-medium">
                            {{ $selectedOrder->waiter ? $selectedOrder->waiter->name : '-' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 myanmar-text">ငွေကိုင်</p>
                        <p class="text-base font-medium">
                            {{ $selectedOrder->cashier ? $selectedOrder->cashier->name : '-' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 myanmar-text">အချိန်</p>
                        <p class="text-base font-medium">
                            {{ $selectedOrder->created_at->format('Y-m-d g:i A') }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 myanmar-text">အခြေအနေ</p>
                        <p class="text-base font-medium myanmar-text">
                            @if($selectedOrder->status === 'pending') စောင့်ဆိုင်းဆဲ
                            @elseif($selectedOrder->status === 'completed') ငွေရှင်းပြီး
                            @else ပယ်ဖျက်
                            @endif
                        </p>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="mb-6">
                    <h4 class="text-sm font-medium text-gray-900 mb-3 myanmar-text">မှာယူထားသော ပစ္စည်းများ</h4>
                    <div class="space-y-2">
                        @foreach($selectedOrder->items as $item)
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <div class="flex-1">
                                @if($item->item)
                                    @if($item->item->name_mm)
                                    <p class="text-sm font-medium text-gray-900 myanmar-text">{{ $item->item->name_mm }}</p>
                                    <p class="text-xs text-gray-500">{{ $item->item->name }}</p>
                                    @else
                                    <p class="text-sm font-medium text-gray-900">{{ $item->item->name }}</p>
                                    @endif
                                @else
                                    <p class="text-sm font-medium text-red-500 myanmar-text">ပစ္စည်းပျက်နေပါသည် (Deleted Item)</p>
                                @endif
                                @if($item->is_foc)
                                <span class="text-xs text-red-600 myanmar-text">(အခမဲ့)</span>
                                @endif
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-900">{{ $item->quantity }} x {{ number_format($item->price, 0) }} Ks</p>
                                <p class="text-sm font-medium text-gray-900">{{ number_format($item->subtotal, 0) }} Ks</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Order Total -->
                <div class="border-t border-gray-200 pt-4 space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600 myanmar-text">စုစုပေါင်း</span>
                        <span class="font-medium">{{ number_format($selectedOrder->subtotal, 0) }} Ks</span>
                    </div>
                    @if($selectedOrder->tax_amount > 0)
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600 myanmar-text">အခွန် ({{ $selectedOrder->tax_percentage }}%)</span>
                        <span class="font-medium">{{ number_format($selectedOrder->tax_amount, 0) }} Ks</span>
                    </div>
                    @endif
                    @if($selectedOrder->service_charge > 0)
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600 myanmar-text">ဝန်ဆောင်မှု ကြေး</span>
                        <span class="font-medium">{{ number_format($selectedOrder->service_charge, 0) }} Ks</span>
                    </div>
                    @endif
                    @if($selectedOrder->discount_amount > 0)
                    <div class="flex justify-between text-sm text-red-600">
                        <span class="myanmar-text">လျှော့ဈေး</span>
                        <span class="font-medium">-{{ number_format($selectedOrder->discount_amount, 0) }} Ks</span>
                    </div>
                    @endif
                    <div class="flex justify-between text-lg font-bold border-t border-gray-300 pt-2">
                        <span class="myanmar-text">စုစုပေါင်း ပေးရန်</span>
                        <span>{{ number_format($selectedOrder->total, 0) }} Ks</span>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-between">
                <div class="flex space-x-2">
                    <!-- Print Receipt Button (only for completed orders) -->
                    @if($selectedOrder->status === 'completed')
                    <button wire:click="printReceipt({{ $selectedOrder->id }})" 
                            class="btn bg-blue-600 hover:bg-blue-700 text-white">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                        </svg>
                        <span class="myanmar-text">ဘောင်ချာ ပရင့်ထုတ်မည်</span>
                    </button>
                    @endif
                    
                    @if($selectedOrder->status === 'pending')
                    <button wire:click="openPaymentModal({{ $selectedOrder->id }})" 
                            class="btn bg-green-600 hover:bg-green-700 text-white">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <span class="myanmar-text">ငွေရှင်းမည်</span>
                    </button>

                    <button wire:click="cancelOrder({{ $selectedOrder->id }})" 
                            onclick="return confirm('ဤအော်ဒါကို ပယ်ဖျက်မှာ သေချာပါသလား?')"
                            class="btn bg-yellow-600 hover:bg-yellow-700 text-white">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        <span class="myanmar-text">ပယ်ဖျက်မည်</span>
                    </button>
                    @endif
                    
                    @if($selectedOrder->status === 'cancelled')
                    <button wire:click="deleteOrder({{ $selectedOrder->id }})" 
                            onclick="return confirm('ဤအော်ဒါကို လုံးဝဖျက်မှာ သေချာပါသလား?')"
                            class="btn bg-red-600 hover:bg-red-700 text-white">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        <span class="myanmar-text">ဖျက်မည်</span>
                    </button>
                    @endif
                </div>
                
                <button wire:click="closeOrderDetails" class="btn btn-outline">
                    <span class="myanmar-text">ပိတ်မည်</span>
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- Hidden Receipt Template for Printing (only for completed orders) -->
    @if($selectedOrder && $selectedOrder->status === 'completed')
    <div id="receipt-print-{{ $selectedOrder->id }}" class="hidden">
        <div id="receipt-content" style="width: 280px; font-family: 'Courier New', monospace; font-size: 11px; padding: 5px;">
            <div style="text-align: center; margin-bottom: 10px;">
                <div style="font-size: 16px; font-weight: bold;">{{ App\Models\Setting::get('business_name_mm', 'ကျွန်ုပ်၏လုပ်ငန်း') }}</div>
                <div style="font-size: 13px; margin-top: 5px;">{{ App\Models\Setting::get('business_address', '') }}</div>
                <div style="font-size: 13px;">{{ App\Models\Setting::get('business_phone', '') }}</div>
            </div>
            
            <div style="border-top: 1px dashed #000; border-bottom: 1px dashed #000; padding: 5px 0; margin: 10px 0;">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td>Order #:</td>
                        <td style="text-align: right;">{{ $selectedOrder->order_number }}</td>
                    </tr>
                    <tr>
                        <td>Date:</td>
                        <td style="text-align: right;">{{ $selectedOrder->created_at->format('Y-m-d g:i A') }}</td>
                    </tr>
                    @if($selectedOrder->table)
                    <tr>
                        <td>Table:</td>
                        <td style="text-align: right;">{{ $selectedOrder->table->name }}</td>
                    </tr>
                    @endif
                    @if($selectedOrder->waiter)
                    <tr>
                        <td>Waiter:</td>
                        <td style="text-align: right;">{{ $selectedOrder->waiter->name }}</td>
                    </tr>
                    @endif
                    @if($selectedOrder->cashier)
                    <tr>
                        <td>Cashier:</td>
                        <td style="text-align: right;">{{ $selectedOrder->cashier->name }}</td>
                    </tr>
                    @endif
                </table>
            </div>
            
            <div style="margin: 10px 0;">
                @foreach($selectedOrder->items as $item)
                <div style="margin-bottom: 5px;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <tr>
                            <td style="width: 70%;">
                                @if($item->item)
                                    @if($item->item->name_mm)
                                    <div style="font-weight: bold;">{{ $item->item->name_mm }}</div>
                                    <div style="font-size: 10px; color: #666;">{{ $item->item->name }}</div>
                                    @else
                                    <div style="font-weight: bold;">{{ $item->item->name }}</div>
                                    @endif
                                @else
                                    <div style="font-weight: bold; color: red;">Deleted Item</div>
                                @endif
                            </td>
                            <td style="text-align: right; width: 30%;">{{ number_format($item->subtotal, 0) }}</td>
                        </tr>
                    </table>
                    <div style="font-size: 10px; color: #666; padding-left: 10px;">
                        {{ $item->quantity }} x {{ number_format($item->price, 0) }}
                        @if($item->foc_quantity > 0) 
                            <span style="font-weight: bold;">(FOC: {{ $item->foc_quantity }})</span>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            
            <div style="border-top: 1px dashed #000; padding-top: 5px; margin-top: 10px;">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td>Subtotal:</td>
                        <td style="text-align: right;">{{ number_format($selectedOrder->subtotal, 0) }}</td>
                    </tr>
                    @if($selectedOrder->tax_amount > 0)
                    <tr>
                        <td>Tax ({{ $selectedOrder->tax_percentage }}%):</td>
                        <td style="text-align: right;">{{ number_format($selectedOrder->tax_amount, 0) }}</td>
                    </tr>
                    @endif
                    @if($selectedOrder->service_charge > 0)
                    <tr>
                        <td>Service Charge:</td>
                        <td style="text-align: right;">{{ number_format($selectedOrder->service_charge, 0) }}</td>
                    </tr>
                    @endif
                    @if($selectedOrder->discount_amount > 0)
                    <tr>
                        <td>Discount:</td>
                        <td style="text-align: right;">-{{ number_format($selectedOrder->discount_amount, 0) }}</td>
                    </tr>
                    @endif
                    <tr style="font-size: 14px; font-weight: bold; border-top: 1px solid #000;">
                        <td style="padding-top: 5px;">TOTAL:</td>
                        <td style="text-align: right; padding-top: 5px;">{{ number_format($selectedOrder->total, 0) }} Ks</td>
                    </tr>
                </table>
            </div>
            
            @if($selectedOrder->status === 'completed' && $selectedOrder->payment_method)
            <div style="border-top: 1px dashed #000; margin: 10px 0; padding-top: 10px;">
                <table style="width: 100%;">
                    <tr>
                        <td style="font-weight: bold;">Payment Method:</td>
                        <td style="text-align: right;">
                            @if($selectedOrder->payment_method === 'cash')
                            ငွေသား (Cash)
                            @elseif($selectedOrder->payment_method === 'card')
                            ကတ် (Card)
                            @else
                            Mobile
                            @endif
                        </td>
                    </tr>
                    @if($selectedOrder->payment_method === 'cash')
                    <tr>
                        <td>Received:</td>
                        <td style="text-align: right;">{{ number_format($selectedOrder->amount_received ?? $selectedOrder->total, 0) }} Ks</td>
                    </tr>
                    <tr>
                        <td>Change:</td>
                        <td style="text-align: right;">{{ number_format(($selectedOrder->amount_received ?? $selectedOrder->total) - $selectedOrder->total, 0) }} Ks</td>
                    </tr>
                    @endif
                </table>
            </div>
            @endif
            
            <div style="text-align: center; margin-top: 15px; font-size: 11px;">
                <div style="font-weight: bold;">ကျေးဇူးတင်ပါသည်</div>
                <div style="margin-top: 5px;">{{ now()->format('Y-m-d g:i:s A') }}</div>
            </div>
        </div>
    </div>
    @endif

    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('print-browser-receipt', (event) => {
                const orderId = event.orderId;
                
                // Function to attempt printing
                const attemptPrint = (retries = 5) => {
                    const receiptContent = document.getElementById('receipt-print-' + orderId);
                    
                    if (receiptContent) {
                        const printWindow = window.open('', '_blank', 'width=400,height=600');
                        
                        if (!printWindow) {
                            alert('ကျေးဇူးပြု၍ Popup Blocker ကို ပိတ်ပေးပါ။\nPlease allow popups for this site to print receipts.');
                            return;
                        }
                        
                        printWindow.document.write('<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Receipt</title>');
                        printWindow.document.write('<style>');
                        printWindow.document.write('@page { size: 80mm auto; margin: 0; }');
                        printWindow.document.write('body { margin: 0; padding: 0; font-family: "Courier New", monospace; }');
                        printWindow.document.write('@media print { body { margin: 0; padding: 0; } }');
                        printWindow.document.write('</style></head><body>');
                        printWindow.document.write(receiptContent.innerHTML);
                        printWindow.document.write('</body></html>');
                        printWindow.document.close();
                        
                        // Wait for content to load, then trigger print
                        setTimeout(() => {
                            printWindow.focus();
                            printWindow.print();
                            // Close window after print dialog is closed (user may cancel)
                            setTimeout(() => {
                                printWindow.close();
                            }, 1000);
                        }, 500);
                    } else if (retries > 0) {
                        // Wait a bit for Livewire to re-render, then retry
                        setTimeout(() => attemptPrint(retries - 1), 200);
                    } else {
                        console.error('Receipt content not found for order:', orderId);
                        alert('ဘောင်ချာ ရှာမတွေ့ပါ။\nReceipt content not found. Please try again.');
                    }
                };
                
                // Start attempting to print
                attemptPrint();
            });
        });
    </script>

    <!-- Payment Modal -->
    @if($showPaymentModal && $paymentOrder)
    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50 p-4 backdrop-blur-sm">
        <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto transform transition-all">
            <!-- Header -->
            <div class="bg-gradient-to-r from-green-600 to-green-700 px-6 py-5 flex justify-between items-center sticky top-0 z-10">
                <div>
                    <h3 class="text-xl font-bold text-white myanmar-text tracking-wide">ငွေရှင်းခြင်း</h3>
                    <p class="text-green-100 text-sm mt-1">Order #{{ $paymentOrder->order_number }}</p>
                </div>
                <button wire:click="closePaymentModal" class="text-white hover:text-gray-200 transition-colors bg-white/10 rounded-full p-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="p-6 space-y-6">
                <!-- Order Info Badge -->
                <div class="flex justify-between items-center bg-gray-50 p-4 rounded-lg border border-gray-100">
                    <div class="flex items-center space-x-3">
                        <div class="bg-green-100 p-2 rounded-full">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 myanmar-text">စားပွဲ</p>
                            <p class="font-bold text-gray-900 myanmar-text">{{ $paymentOrder->table ? $paymentOrder->table->name_mm : 'Takeaway' }}</p>
                        </div>
                    </div>
                    <span class="px-4 py-1.5 bg-yellow-100 text-yellow-800 rounded-full text-sm font-bold myanmar-text shadow-sm">
                        စောင့်ဆိုင်းဆဲ
                    </span>
                </div>

                <!-- Order Items -->
                <div>
                    <h5 class="font-bold text-gray-900 mb-4 myanmar-text flex items-center">
                        <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                        မှာယူထားသော ပစ္စည်းများ
                    </h5>
                    <div class="bg-white border border-gray-200 rounded-lg divide-y divide-gray-100 overflow-hidden">
                        @foreach($paymentOrder->items as $item)
                        <div class="flex justify-between items-center p-4 hover:bg-gray-50 transition-colors">
                            <div class="flex-1">
                                @if($item->item)
                                    @if($item->item->name_mm)
                                    <div class="flex items-baseline gap-2">
                                        <span class="text-gray-900 font-bold myanmar-text">{{ $item->item->name_mm }}</span>
                                        <span class="text-gray-500 text-sm">× {{ $item->quantity }}</span>
                                    </div>
                                    <div class="text-xs text-gray-500 mt-0.5">{{ $item->item->name }}</div>
                                    @else
                                    <div class="flex items-baseline gap-2">
                                        <span class="text-gray-900 font-bold">{{ $item->item->name }}</span>
                                        <span class="text-gray-500 text-sm">× {{ $item->quantity }}</span>
                                    </div>
                                    @endif
                                @else
                                    <span class="text-red-500 font-medium myanmar-text">Deleted Item</span>
                                    <span class="text-gray-500"> × {{ $item->quantity }}</span>
                                @endif
                                @if($item->foc_quantity > 0)
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 mt-1">
                                    FOC: {{ $item->foc_quantity }}
                                </span>
                                @endif
                            </div>
                            <span class="font-bold text-gray-900 text-right min-w-[80px]">{{ number_format($item->subtotal, 0) }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Tax & Service Options -->
                <div class="bg-gray-50 rounded-xl p-5 border border-gray-100">
                    <div class="flex items-center justify-between">
                        <label class="flex items-center cursor-pointer group">
                            <div class="relative">
                                <input 
                                    type="checkbox" 
                                    wire:model.live="applyTax"
                                    class="sr-only peer"
                                >
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                            </div>
                            <div class="ml-3">
                                <span class="font-bold text-gray-900">Tax</span>
                                <span class="text-sm text-gray-500 font-medium ml-1">(5%)</span>
                            </div>
                        </label>
                        @if($applyTax)
                        <span class="font-bold text-gray-900">{{ number_format($calculatedTax, 0) }} Ks</span>
                        @else
                        <span class="text-gray-400">-</span>
                        @endif
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Payment Method -->
                    <div>
                        <label class="block font-bold text-gray-900 mb-3 myanmar-text flex items-center">
                            <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            ငွေပေးချေမှု နည်းလမ်း
                        </label>
                        <div class="grid grid-cols-3 gap-2">
                            <button 
                                wire:click="$set('paymentMethod', 'cash')"
                                class="px-2 py-3 rounded-lg text-sm font-bold transition-all border-2 {{ $paymentMethod === 'cash' ? 'border-green-600 bg-green-50 text-green-700' : 'border-transparent bg-gray-100 text-gray-600 hover:bg-gray-200' }}"
                            >
                                <span class="myanmar-text">ငွေသား</span>
                            </button>
                            <button 
                                wire:click="$set('paymentMethod', 'card')"
                                class="px-2 py-3 rounded-lg text-sm font-bold transition-all border-2 {{ $paymentMethod === 'card' ? 'border-green-600 bg-green-50 text-green-700' : 'border-transparent bg-gray-100 text-gray-600 hover:bg-gray-200' }}"
                            >
                                Card
                            </button>
                            <button 
                                wire:click="$set('paymentMethod', 'mobile')"
                                class="px-2 py-3 rounded-lg text-sm font-bold transition-all border-2 {{ $paymentMethod === 'mobile' ? 'border-green-600 bg-green-50 text-green-700' : 'border-transparent bg-gray-100 text-gray-600 hover:bg-gray-200' }}"
                            >
                                Mobile
                            </button>
                        </div>
                        
                        @if($paymentMethod === 'cash')
                        <div class="mt-4">
                            <label class="block text-sm font-bold text-gray-700 mb-2 myanmar-text">ပေးငွေ</label>
                            <div class="relative">
                                <input 
                                    type="number" 
                                    wire:model.live="amountReceived"
                                    min="{{ $calculatedTotal }}"
                                    step="100"
                                    class="w-full pl-4 pr-12 py-3 border-gray-300 rounded-lg text-lg font-bold focus:ring-green-500 focus:border-green-500"
                                    placeholder="0"
                                >
                                <span class="absolute right-4 top-3.5 text-gray-400 font-medium">Ks</span>
                            </div>
                            @if($amountReceived > 0 && $calculatedChange >= 0)
                            <div class="mt-3 p-3 bg-blue-50 rounded-lg border border-blue-100">
                                <div class="flex justify-between items-center">
                                    <span class="text-sm font-bold text-blue-800 myanmar-text">ပြန်အမ်းငွေ</span>
                                    <span class="text-lg font-bold text-blue-600">{{ number_format($calculatedChange, 0) }} Ks</span>
                                </div>
                            </div>
                            @endif
                        </div>
                        @endif
                    </div>

                    <!-- Discount -->
                    <div>
                        <label class="block font-bold text-gray-900 mb-3 myanmar-text flex items-center">
                            <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path>
                            </svg>
                            လျှော့ဈေး (Discount)
                        </label>
                        <div class="grid grid-cols-3 gap-2 mb-3">
                            <button 
                                wire:click="$set('discountType', 'none')"
                                class="px-2 py-2 rounded-lg text-sm font-bold transition-all border-2 {{ $discountType === 'none' ? 'border-gray-400 bg-gray-100 text-gray-700' : 'border-transparent bg-gray-50 text-gray-500 hover:bg-gray-100' }}"
                            >
                                None
                            </button>
                            <button 
                                wire:click="$set('discountType', 'percentage')"
                                class="px-2 py-2 rounded-lg text-sm font-bold transition-all border-2 {{ $discountType === 'percentage' ? 'border-green-600 bg-green-50 text-green-700' : 'border-transparent bg-gray-50 text-gray-500 hover:bg-gray-100' }}"
                            >
                                %
                            </button>
                            <button 
                                wire:click="$set('discountType', 'fixed')"
                                class="px-2 py-2 rounded-lg text-sm font-bold transition-all border-2 {{ $discountType === 'fixed' ? 'border-green-600 bg-green-50 text-green-700' : 'border-transparent bg-gray-50 text-gray-500 hover:bg-gray-100' }}"
                            >
                                Fixed
                            </button>
                        </div>

                        @if($discountType !== 'none')
                        <div class="relative">
                            <input 
                                type="number" 
                                wire:model.live="discountValue"
                                min="0"
                                step="0.01"
                                class="w-full pl-4 pr-12 py-3 border-gray-300 rounded-lg text-lg font-bold focus:ring-green-500 focus:border-green-500"
                                placeholder="{{ $discountType === 'percentage' ? 'Percentage' : 'Amount' }}"
                            >
                            <span class="absolute right-4 top-3.5 text-gray-400 font-medium">{{ $discountType === 'percentage' ? '%' : 'Ks' }}</span>
                        </div>
                        @if($calculatedDiscount > 0)
                        <p class="mt-2 text-sm text-green-600 font-medium myanmar-text flex items-center justify-end">
                            လျှော့ဈေး: -{{ number_format($calculatedDiscount, 0) }} Ks
                        </p>
                        @endif
                        @endif
                    </div>
                </div>

                <!-- Summary -->
                <div class="bg-gray-50 rounded-xl p-6 border border-gray-200">
                    <div class="space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600 font-medium myanmar-text">သင့်ငွေ (Subtotal)</span>
                            <span class="font-bold text-gray-900">{{ number_format($calculatedSubtotal, 0) }} Ks</span>
                        </div>
                        @if($applyTax)
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600 font-medium">Tax (5%)</span>
                            <span class="font-bold text-gray-900">{{ number_format($calculatedTax, 0) }} Ks</span>
                        </div>
                        @endif
                        @if($calculatedDiscount > 0)
                        <div class="flex justify-between text-sm">
                            <span class="text-green-600 font-medium myanmar-text">လျှော့ဈေး (Discount)</span>
                            <span class="font-bold text-green-600">-{{ number_format($calculatedDiscount, 0) }} Ks</span>
                        </div>
                        @endif
                        <div class="border-t border-gray-200 pt-3 mt-3">
                            <div class="flex justify-between items-end">
                                <span class="text-lg font-bold text-gray-900 myanmar-text">စုစုပေါင်း ကျသင့်ငွေ</span>
                                <span class="text-2xl font-bold text-green-600">{{ number_format($calculatedTotal, 0) }} Ks</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex space-x-4 pt-2">
                    <button 
                        wire:click="closePaymentModal"
                        class="flex-1 px-6 py-4 border-2 border-gray-200 rounded-xl font-bold text-gray-600 hover:bg-gray-50 hover:border-gray-300 transition-all myanmar-text"
                    >
                        မလုပ်တော့ပါ
                    </button>
                    <button 
                        wire:click="processPayment"
                        class="flex-1 px-6 py-4 bg-green-600 hover:bg-green-700 text-white rounded-xl font-bold shadow-lg hover:shadow-xl transition-all flex items-center justify-center space-x-2 transform hover:-translate-y-0.5"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <span class="myanmar-text text-lg">ငွေရှင်းမည်</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Success Modal -->
    @if($showSuccessModal)
    <div class="fixed inset-0 bg-gray-900 bg-opacity-75 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full overflow-hidden">
            <!-- Success Animation -->
            <div class="bg-gradient-to-br from-green-500 to-green-600 p-8 text-center">
                <div class="inline-flex items-center justify-center w-24 h-24 bg-white rounded-full mb-4 animate-bounce">
                    <svg class="w-16 h-16 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h2 class="text-3xl font-bold text-white mb-2 myanmar-text">အောင်မြင်ပါသည်!</h2>
                <p class="text-green-100 text-lg myanmar-text">ငွေပေးချေမှု အောင်မြင်ပါသည်</p>
            </div>

            <div class="p-6">
                <div class="text-center mb-6">
                    <p class="text-gray-600 myanmar-text">ငွေရှင်းခြင်း ပြီးစီးပါပြီ</p>
                    <p class="text-sm text-gray-500 mt-1">Payment completed successfully</p>
                </div>

                <!-- Actions -->
                <div class="space-y-3">
                    <button 
                        type="button"
                        wire:click.prevent="printCompletedReceipt"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-4 px-6 rounded-xl transition-colors flex items-center justify-center space-x-2"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                        </svg>
                        <span class="myanmar-text">ဘောင်ချာ ပရင့်ထုတ်မည်</span>
                    </button>

                    <button 
                        wire:click="closeSuccessModal"
                        class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-4 px-6 rounded-xl transition-colors myanmar-text"
                    >
                        ပြီးပါပြီ
                    </button>
                </div>

                <div class="mt-6 pt-6 border-t border-gray-200 text-center">
                    <p class="text-sm text-gray-500 flex items-center justify-center space-x-2 myanmar-text">
                        <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"></path>
                        </svg>
                        <span>ကျေးဇူးတင်ပါသည်</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
    @endif

    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('print-payment-receipt', (event) => {
                const orderId = event.orderId;
                
                // Fetch order details
                fetch(`/api/orders/${orderId}/receipt`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok: ' + response.statusText);
                        }
                        return response.json();
                    })
                    .then(order => {
                        printDetailedReceipt(order);
                    })
                    .catch(error => {
                        console.error('Error fetching receipt:', error);
                        alert('ဘောင်ချာ အချက်အလက် ရယူရာတွင် အမှားရှိနေပါသည်။\nError fetching receipt details.');
                    });
            });
        });

        function printDetailedReceipt(order) {
            const printWindow = window.open('', '_blank', 'width=400,height=800');
            
            if (!printWindow) {
                alert('ကျေးဇူးပြု၍ Popup Blocker ကို ပိတ်ပေးပါ။\nPlease allow popups for this site.');
                return;
            }
            
            const paymentMethodLabels = {
                'cash': 'ငွေသား / Cash',
                'card': 'ကတ် / Card',
                'mobile': 'မိုဘိုင်း / Mobile'
            };
            
            let itemsHtml = '';
            order.order_items.forEach(item => {
                const focText = item.foc_quantity > 0 ? ` (FOC: ${item.foc_quantity})` : '';
                itemsHtml += `
                    <tr>
                        <td style="padding: 4px 0;">${item.item.name}${focText}</td>
                        <td style="text-align: center;">${item.quantity}</td>
                        <td style="text-align: right;">${Number(item.price).toLocaleString()}</td>
                        <td style="text-align: right;">${Number(item.subtotal).toLocaleString()}</td>
                    </tr>
                `;
            });
            
            let summaryHtml = `
                <tr>
                    <td colspan="3" style="text-align: right; padding: 8px 0;">Subtotal:</td>
                    <td style="text-align: right; font-weight: bold;">${Number(order.subtotal).toLocaleString()} Ks</td>
                </tr>
            `;
            
            if (order.tax_amount > 0) {
                summaryHtml += `
                    <tr>
                        <td colspan="3" style="text-align: right; padding: 4px 0;">Tax (${order.tax_percentage}%):</td>
                        <td style="text-align: right;">${Number(order.tax_amount).toLocaleString()} Ks</td>
                    </tr>
                `;
            }
            
            if (order.service_charge > 0) {
                summaryHtml += `
                    <tr>
                        <td colspan="3" style="text-align: right; padding: 4px 0;">Service Charge:</td>
                        <td style="text-align: right;">${Number(order.service_charge).toLocaleString()} Ks</td>
                    </tr>
                `;
            }
            
            if (order.discount_amount > 0) {
                const discountLabel = order.discount_percentage > 0 
                    ? `Discount (${order.discount_percentage}%)` 
                    : 'Discount';
                summaryHtml += `
                    <tr>
                        <td colspan="3" style="text-align: right; padding: 4px 0;">${discountLabel}:</td>
                        <td style="text-align: right; color: #059669;">-${Number(order.discount_amount).toLocaleString()} Ks</td>
                    </tr>
                `;
            }
            
            summaryHtml += `
                <tr style="border-top: 2px solid #000;">
                    <td colspan="3" style="text-align: right; padding: 8px 0; font-weight: bold; font-size: 16px;">TOTAL:</td>
                    <td style="text-align: right; font-weight: bold; font-size: 16px;">${Number(order.total).toLocaleString()} Ks</td>
                </tr>
            `;
            
            let paymentHtml = `
                <tr>
                    <td colspan="3" style="text-align: right; padding: 4px 0;">Payment Method:</td>
                    <td style="text-align: right;">${paymentMethodLabels[order.payment_method] || order.payment_method}</td>
                </tr>
            `;
            
            if (order.payment_method === 'cash') {
                paymentHtml += `
                    <tr>
                        <td colspan="3" style="text-align: right; padding: 4px 0;">Amount Received:</td>
                        <td style="text-align: right;">${Number(order.amount_received).toLocaleString()} Ks</td>
                    </tr>
                    <tr>
                        <td colspan="3" style="text-align: right; padding: 4px 0;">Change:</td>
                        <td style="text-align: right; font-weight: bold;">${Number(order.change_amount).toLocaleString()} Ks</td>
                    </tr>
                `;
            }
            
            const html = `
                <!DOCTYPE html>
                <html>
                <head>
                    <meta charset="UTF-8">
                    <title>Receipt - ${order.order_number}</title>
                    <link rel="preconnect" href="https://fonts.googleapis.com">
                    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
                    <link href="https://fonts.googleapis.com/css2?family=Padauk:wght@400;700&display=swap" rel="stylesheet">
                    <style>
                        @page { size: 80mm auto; margin: 0; }
                        * { margin: 0; padding: 0; box-sizing: border-box; }
                        body {
                            font-family: 'Padauk', 'Courier New', sans-serif;
                            font-size: 12px;
                            line-height: 1.4;
                            padding: 10mm;
                            width: 80mm;
                        }
                        .header { text-align: center; margin-bottom: 10px; border-bottom: 2px dashed #000; padding-bottom: 10px; }
                        .header h1 { font-size: 18px; margin-bottom: 5px; font-weight: 700; }
                        .header p { font-size: 11px; }
                        .info { margin: 10px 0; font-size: 11px; }
                        .info div { margin: 3px 0; }
                        table { width: 100%; border-collapse: collapse; margin: 10px 0; }
                        th { text-align: left; border-bottom: 1px solid #000; padding: 5px 0; font-size: 11px; font-weight: 700; }
                        td { padding: 4px 0; font-size: 11px; }
                        .footer { text-align: center; margin-top: 15px; padding-top: 10px; border-top: 2px dashed #000; font-size: 11px; }
                        @media print {
                            body { margin: 0; padding: 10mm; }
                        }
                    </style>
                </head>
                <body>
                    <div class="header">
                        <h1>${order.shop_name || 'My Business'}</h1>
                        ${order.shop_address ? `<p style="font-size: 11px; margin-top: 5px;">${order.shop_address}</p>` : ''}
                        ${order.shop_phone ? `<p style="font-size: 11px;">Tel: ${order.shop_phone}</p>` : ''}
                    </div>
                    
                    <div class="info">
                        <div><strong>Order #:</strong> ${order.order_number}</div>
                        ${order.table ? `<div><strong>Table:</strong> ${order.table.name}</div>` : ''}
                        ${order.waiter ? `<div><strong>Waiter:</strong> ${order.waiter.name}</div>` : ''}
                        <div><strong>Cashier:</strong> ${order.cashier.name}</div>
                        <div><strong>Date:</strong> ${new Date(order.completed_at).toLocaleString()}</div>
                    </div>
                    
                    <table>
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th style="text-align: center;">Qty</th>
                                <th style="text-align: right;">Price</th>
                                <th style="text-align: right;">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${itemsHtml}
                        </tbody>
                    </table>
                    
                    <table>
                        <tbody>
                            ${summaryHtml}
                            ${paymentHtml}
                        </tbody>
                    </table>
                    
                    <div class="footer">
                        <p>*** Thank You! Come Again! ***</p>
                        <p style="margin-top: 5px;">Powered by Tea House POS</p>
                    </div>
                </body>
                </html>
            `;
            
            printWindow.document.write(html);
            printWindow.document.close();
            
            setTimeout(() => {
                printWindow.print();
                printWindow.close();
            }, 250);
        }
    </script>
    </div>
</div>

<script>
document.addEventListener('livewire:init', () => {
    Livewire.on('printReceipt', () => {
        window.print();
    });
});
</script>

<style>
@media print {
    /* Hide everything first */
    body * {
        visibility: hidden;
    }
    
    /* Show only the receipt content */
    #receipt-content, #receipt-content * {
        visibility: visible;
    }
    
    /* Position receipt for print */
    #receipt-content {
        position: absolute;
        left: 0;
        top: 0;
        width: 80mm;
        margin: 0;
        padding: 10mm;
        font-size: 10pt;
        line-height: 1.3;
    }
    
    /* Hide buttons and modals */
    .no-print,
    button,
    .fixed,
    [wire\\:click],
    [x-data] {
        display: none !important;
    }
    
    /* Myanmar font support */
    .myanmar-text {
        font-family: 'Pyidaungsu', 'Myanmar3', 'Padauk', sans-serif;
    }
}
</style>
