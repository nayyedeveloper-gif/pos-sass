<div class="py-8 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Success Message -->
        @if (session()->has('message'))
        <div class="mb-6 rounded-xl bg-emerald-50 border border-emerald-100 p-4 flex items-center shadow-sm"
             x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-emerald-800 myanmar-text">{{ session('message') }}</p>
            </div>
        </div>
        @endif

        <!-- Header -->
        <div class="sm:flex sm:items-center sm:justify-between mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 myanmar-text">အော်ဒါများ စီမံခန့်ခွဲမှု</h1>
                <p class="mt-2 text-sm text-gray-600 myanmar-text">ဆိုင်၏ အော်ဒါမှတ်တမ်းများကို ကြည့်ရှု၊ စီမံခန့်ခွဲနိုင်ပါသည်။</p>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Search -->
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="အော်ဒါနံပါတ်..." class="block w-full pl-10 pr-3 py-2.5 border border-gray-200 rounded-xl leading-5 bg-gray-50 placeholder-gray-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500 sm:text-sm transition duration-150 ease-in-out myanmar-text">
                </div>

                <!-- Status Filter -->
                <div>
                    <select wire:model.live="statusFilter" class="block w-full py-2.5 pl-3 pr-10 border border-gray-200 rounded-xl leading-5 bg-gray-50 focus:outline-none focus:bg-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500 sm:text-sm transition duration-150 ease-in-out myanmar-text">
                        <option value="">အခြေအနေ အားလုံး</option>
                        <option value="pending">စောင့်ဆိုင်းဆဲ / Pending</option>
                        <option value="completed">ငွေရှင်းပြီး / Completed</option>
                        <option value="cancelled">ပယ်ဖျက် / Cancelled</option>
                    </select>
                </div>

                <!-- Table Filter -->
                <div>
                    <select wire:model.live="tableFilter" class="block w-full py-2.5 pl-3 pr-10 border border-gray-200 rounded-xl leading-5 bg-gray-50 focus:outline-none focus:bg-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500 sm:text-sm transition duration-150 ease-in-out myanmar-text">
                        <option value="">စားပွဲ အားလုံး</option>
                        @foreach($tables as $table)
                        <option value="{{ $table->id }}">{{ $table->name_mm }} / {{ $table->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Date Filter -->
                <div>
                    <select wire:model.live="dateFilter" class="block w-full py-2.5 pl-3 pr-10 border border-gray-200 rounded-xl leading-5 bg-gray-50 focus:outline-none focus:bg-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500 sm:text-sm transition duration-150 ease-in-out myanmar-text">
                        <option value="today">ယနေ့ / Today</option>
                        <option value="yesterday">မနေ့က / Yesterday</option>
                        <option value="week">ဤအပတ် / This Week</option>
                        <option value="month">ဤလ / This Month</option>
                        <option value="">အားလုံး / All</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Orders Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50/50">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider myanmar-text">အော်ဒါနံပါတ်</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider myanmar-text">စားပွဲ</th>
                            <th scope="col" class="px-6 py-4 text-center text-xs font-medium text-gray-500 uppercase tracking-wider myanmar-text">ပစ္စည်းများ</th>
                            <th scope="col" class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider myanmar-text">စုစုပေါင်း</th>
                            <th scope="col" class="px-6 py-4 text-center text-xs font-medium text-gray-500 uppercase tracking-wider myanmar-text">အခြေအနေ</th>
                            <th scope="col" class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider myanmar-text">အချိန်</th>
                            <th scope="col" class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider myanmar-text">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($orders as $order)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-col">
                                    <span class="text-sm font-semibold text-gray-900">#{{ $order->order_number }}</span>
                                    <span class="text-xs text-gray-500 myanmar-text">{{ $order->order_type === 'dine_in' ? 'ဆိုင်တွင်း' : 'ပါဆယ်' }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($order->table)
                                <div class="flex flex-col">
                                    <span class="text-sm font-medium text-gray-900 myanmar-text">{{ $order->table->name_mm }}</span>
                                    <span class="text-xs text-gray-500">{{ $order->table->name }}</span>
                                </div>
                                @else
                                <span class="text-sm text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    {{ $order->orderItems->count() }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <span class="text-sm font-bold text-gray-900">{{ number_format($order->total, 0) }} <span class="text-xs font-normal text-gray-500">Ks</span></span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @if($order->status === 'pending')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-xs font-medium bg-yellow-50 text-yellow-700 border border-yellow-100 myanmar-text">
                                    စောင့်ဆိုင်းဆဲ
                                </span>
                                @elseif($order->status === 'completed')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-100 myanmar-text">
                                    ပြီးစီး
                                </span>
                                @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-xs font-medium bg-rose-50 text-rose-700 border border-rose-100 myanmar-text">
                                    ပယ်ဖျက်
                                </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-500">
                                {{ $order->created_at->format('g:i A') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-3">
                                    <button wire:click="viewOrder({{ $order->id }})" class="text-primary-600 hover:text-primary-900 transition-colors p-1 rounded hover:bg-primary-50" title="ကြည့်ရန်">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </button>
                                    @if($order->status === 'cancelled')
                                    <button wire:click="deleteOrder({{ $order->id }})" 
                                            wire:confirm="ဤအော်ဒါကို ဖျက်ပစ်မှာ သေချာပါသလား?"
                                            class="text-rose-600 hover:text-rose-900 transition-colors p-1 rounded hover:bg-rose-50" title="ဖျက်ရန်">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-500">
                                    <svg class="w-12 h-12 mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                    <h3 class="text-lg font-medium text-gray-900 myanmar-text">အော်ဒါများ မရှိပါ</h3>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($orders->hasPages())
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                {{ $orders->links() }}
            </div>
            @endif
        </div>
    </div>

    <!-- Order Details Modal -->
    @if($showOrderDetails && $selectedOrder)
    <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm flex items-center justify-center z-50 p-4 transition-opacity">
        <div class="bg-white rounded-2xl shadow-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto transform transition-all">
            <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center">
                <div>
                    <h3 class="text-xl font-bold text-gray-900 myanmar-text">အော်ဒါ အသေးစိတ်</h3>
                    <p class="text-sm text-gray-500 font-mono mt-1">#{{ $selectedOrder->order_number }}</p>
                </div>
                <button wire:click="closeOrderDetails" class="text-gray-400 hover:text-gray-500 focus:outline-none transition-colors">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <div class="px-6 py-6">
                <!-- Order Info Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8 bg-gray-50 p-6 rounded-xl border border-gray-100">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1 myanmar-text">စားပွဲ</p>
                        <p class="text-base font-semibold text-gray-900 myanmar-text">
                            {{ $selectedOrder->table ? $selectedOrder->table->name_mm : '-' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1 myanmar-text">အမျိုးအစား</p>
                        <p class="text-base font-semibold text-gray-900 myanmar-text">
                            {{ $selectedOrder->order_type === 'dine_in' ? 'ဆိုင်တွင်းစားမည်' : 'ပါဆယ်ယူမည်' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1 myanmar-text">စားပွဲထိုး</p>
                        <p class="text-base font-medium text-gray-900">
                            {{ $selectedOrder->waiter ? $selectedOrder->waiter->name : '-' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1 myanmar-text">အချိန်</p>
                        <p class="text-base font-medium text-gray-900">
                            {{ $selectedOrder->created_at->format('Y-m-d g:i A') }}
                        </p>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="mb-8">
                    <h4 class="text-sm font-bold text-gray-900 mb-4 uppercase tracking-wider myanmar-text">မှာယူထားသော ပစ္စည်းများ</h4>
                    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
                        @foreach($selectedOrder->orderItems as $item)
                        <div class="flex justify-between items-center p-4 border-b border-gray-100 last:border-0 hover:bg-gray-50 transition-colors">
                            <div class="flex-1">
                                <p class="text-sm font-semibold text-gray-900 myanmar-text">{{ $item->item->name_mm }}</p>
                                <p class="text-xs text-gray-500">{{ $item->item->name }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-600">{{ $item->quantity }} x {{ number_format($item->price, 0) }}</p>
                                <p class="text-sm font-bold text-gray-900">{{ number_format($item->subtotal, 0) }} Ks</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Order Total -->
                <div class="bg-gray-50 rounded-xl p-6 border border-gray-100 space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600 myanmar-text">စုစုပေါင်း</span>
                        <span class="font-medium text-gray-900">{{ number_format($selectedOrder->subtotal, 0) }} Ks</span>
                    </div>
                    @if($selectedOrder->tax_amount > 0)
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600 myanmar-text">အခွန် ({{ $selectedOrder->tax_percentage }}%)</span>
                        <span class="font-medium text-gray-900">{{ number_format($selectedOrder->tax_amount, 0) }} Ks</span>
                    </div>
                    @endif
                    @if($selectedOrder->service_charge > 0)
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600 myanmar-text">ဝန်ဆောင်မှု ကြေး</span>
                        <span class="font-medium text-gray-900">{{ number_format($selectedOrder->service_charge, 0) }} Ks</span>
                    </div>
                    @endif
                    @if($selectedOrder->discount_amount > 0)
                    <div class="flex justify-between text-sm text-rose-600">
                        <span class="myanmar-text">လျှော့ဈေး</span>
                        <span class="font-medium">-{{ number_format($selectedOrder->discount_amount, 0) }} Ks</span>
                    </div>
                    @endif
                    <div class="flex justify-between text-lg font-bold border-t border-gray-200 pt-3 mt-3">
                        <span class="myanmar-text">စုစုပေါင်း ပေးရန်</span>
                        <span class="text-primary-600">{{ number_format($selectedOrder->total, 0) }} Ks</span>
                    </div>
                </div>
            </div>

            <div class="px-6 py-5 bg-gray-50 border-t border-gray-100 flex justify-between items-center rounded-b-2xl">
                <div class="flex space-x-3">
                    <button wire:click="printReceipt({{ $selectedOrder->id }})" class="inline-flex items-center px-4 py-2 border border-transparent rounded-xl shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all">
                        <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                        </svg>
                        <span class="myanmar-text">ပရင့်ထုတ်မည်</span>
                    </button>
                    
                    @if($selectedOrder->status === 'pending')
                    <button wire:click="cancelOrder({{ $selectedOrder->id }})" 
                            onclick="return confirm('ဤအော်ဒါကို ပယ်ဖျက်မှာ သေချာပါသလား?')"
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-xl shadow-sm text-sm font-medium text-white bg-amber-500 hover:bg-amber-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 transition-all">
                        <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        <span class="myanmar-text">ပယ်ဖျက်မည်</span>
                    </button>
                    @endif
                    
                    @if($selectedOrder->status === 'cancelled')
                    <button wire:click="deleteOrder({{ $selectedOrder->id }})" 
                            wire:confirm="ဤအော်ဒါကို လုံးဝဖျက်ပစ်မှာ သေချာပါသလား? ဤလုပ်ဆောင်ချက်ကို နောက်ပြန်ပြောင်း၍မရပါ။"
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-xl shadow-sm text-sm font-medium text-white bg-rose-600 hover:bg-rose-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-rose-500 transition-all">
                        <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        <span class="myanmar-text">ဖျက်မည်</span>
                    </button>
                    @endif
                </div>
                
                <button wire:click="closeOrderDetails" class="px-4 py-2 bg-white border border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50 font-medium text-sm transition-colors shadow-sm myanmar-text">
                    ပိတ်မည်
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- Hidden Receipt Template for Printing -->
    @if($selectedOrder)
    <div id="receipt-print-{{ $selectedOrder->id }}" class="hidden">
        <div style="width: 280px; font-family: 'Courier New', monospace; font-size: 11px; padding: 5px;">
            <div style="text-align: center; margin-bottom: 10px;">
                <div style="font-size: 16px; font-weight: bold;">{{ \App\Models\Setting::get('business_name_mm', 'ကျွန်ုပ်၏လုပ်ငန်း') }}</div>
                <div style="font-size: 13px; margin-top: 5px;">{{ \App\Models\Setting::get('business_address', '') }}</div>
                <div style="font-size: 13px;">Tel: {{ \App\Models\Setting::get('business_phone', '') }}</div>
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
                </table>
            </div>
            
            <div style="margin: 10px 0;">
                @foreach($selectedOrder->orderItems as $item)
                <div style="margin-bottom: 5px;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <tr>
                            <td style="width: 70%;">
                                @if($item->item->name_mm)
                                <div style="font-weight: bold;">{{ $item->item->name_mm }}</div>
                                <div style="font-size: 10px; color: #666;">{{ $item->item->name }}</div>
                                @else
                                <div style="font-weight: bold;">{{ $item->item->name }}</div>
                                @endif
                            </td>
                            <td style="text-align: right; width: 30%;">{{ number_format($item->subtotal, 0) }}</td>
                        </tr>
                    </table>
                    <div style="font-size: 10px; color: #666; padding-left: 10px;">
                        {{ $item->quantity }} x {{ number_format($item->price, 0) }}
                        @if($item->is_foc) (FOC) @endif
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
            
            <div style="text-align: center; margin-top: 15px; font-size: 11px;">
                <div>{{ \App\Models\Setting::get('receipt_footer', 'Thank you for your visit!') }}</div>
                <div style="margin-top: 5px;">{{ now()->format('Y-m-d g:i:s A') }}</div>
            </div>
        </div>
    </div>
    @endif

    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('print-receipt', (event) => {
                const orderId = event.orderId;
                const receiptContent = document.getElementById('receipt-print-' + orderId);
                
                if (receiptContent) {
                    const printWindow = window.open('', '_blank', 'width=400,height=600');
                    printWindow.document.write('<html><head><title>Receipt</title>');
                    printWindow.document.write('<style>@page { size: 80mm auto; margin: 0; } body { margin: 0; padding: 0; } @media print { body { margin: 0; padding: 0; } }</style>');
                    printWindow.document.write('</head><body>');
                    printWindow.document.write(receiptContent.innerHTML);
                    printWindow.document.write('</body></html>');
                    printWindow.document.close();
                    
                    setTimeout(() => {
                        printWindow.print();
                        printWindow.close();
                    }, 250);
                }
            });
        });
    </script>
</div>
