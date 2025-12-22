<div class="min-h-screen bg-gray-50">
    {{-- Header --}}
    <div class="bg-white border-b border-gray-200 sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center gap-4">
                    <h1 class="text-xl font-bold text-gray-900 myanmar-text">အော်ဒါစီမံခန့်ခွဲမှု</h1>
                </div>
                
                <div class="flex items-center gap-3">
                    {{-- Search --}}
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <input type="text" wire:model.live="searchTerm" placeholder="Order # ရှာရန်..." 
                            class="pl-10 pr-4 py-2 border border-gray-200 rounded-xl focus:ring-orange-500 focus:border-orange-500 w-64">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        {{-- Stats Cards --}}
        <div class="grid grid-cols-5 gap-4 mb-6">
            <div class="bg-white rounded-xl p-4 border border-gray-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
                        <p class="text-xs text-gray-500 myanmar-text">ယနေ့စုစုပေါင်း</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl p-4 border border-yellow-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-yellow-600">{{ $stats['pending'] }}</p>
                        <p class="text-xs text-gray-500 myanmar-text">စောင့်ဆိုင်းနေသော</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl p-4 border border-blue-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-blue-600">{{ $stats['preparing'] }}</p>
                        <p class="text-xs text-gray-500 myanmar-text">ပြင်ဆင်နေသော</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl p-4 border border-green-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-green-600">{{ $stats['completed'] }}</p>
                        <p class="text-xs text-gray-500 myanmar-text">ပြီးစီးပြီး</p>
                    </div>
                </div>
            </div>
            <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-xl p-4 text-white">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold">{{ number_format($stats['revenue']) }}</p>
                        <p class="text-xs text-orange-100 myanmar-text">ယနေ့ဝင်ငွေ</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filters --}}
        <div class="bg-white rounded-xl p-4 mb-6 border border-gray-100">
            <div class="flex items-center gap-6">
                {{-- Status Filter --}}
                <div class="flex items-center gap-2">
                    <span class="text-sm text-gray-500 myanmar-text">အခြေအနေ:</span>
                    <div class="flex bg-gray-100 rounded-lg p-1">
                        @foreach(['all' => 'အားလုံး', 'pending' => 'စောင့်ဆိုင်း', 'preparing' => 'ပြင်ဆင်နေ', 'ready' => 'အဆင်သင့်', 'completed' => 'ပြီးစီး', 'cancelled' => 'ပယ်ဖျက်'] as $status => $label)
                            <button wire:click="filterByStatus('{{ $status }}')"
                                class="px-3 py-1.5 rounded-md text-sm font-medium transition-all {{ $statusFilter === $status ? 'bg-white shadow text-gray-900' : 'text-gray-500 hover:text-gray-700' }}">
                                <span class="myanmar-text">{{ $label }}</span>
                            </button>
                        @endforeach
                    </div>
                </div>

                {{-- Order Type Filter --}}
                <div class="flex items-center gap-2">
                    <span class="text-sm text-gray-500 myanmar-text">အမျိုးအစား:</span>
                    <div class="flex bg-gray-100 rounded-lg p-1">
                        @foreach(['all' => 'အားလုံး', 'dine_in' => 'ဆိုင်တွင်း', 'takeaway' => 'ထုပ်ယူ', 'delivery' => 'ပို့ဆောင်'] as $type => $label)
                            <button wire:click="filterByType('{{ $type }}')"
                                class="px-3 py-1.5 rounded-md text-sm font-medium transition-all {{ $orderTypeFilter === $type ? 'bg-white shadow text-gray-900' : 'text-gray-500 hover:text-gray-700' }}">
                                <span class="myanmar-text">{{ $label }}</span>
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- Orders Table --}}
        <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Order #</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase myanmar-text">အမျိုးအစား</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase myanmar-text">စားပွဲ</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase myanmar-text">ပမာဏ</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase myanmar-text">အခြေအနေ</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase myanmar-text">အချိန်</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($orders as $order)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <span class="font-mono font-bold text-gray-900">#{{ $order->order_number }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium
                                    {{ $order->order_type === 'dine_in' ? 'bg-blue-100 text-blue-700' : '' }}
                                    {{ $order->order_type === 'takeaway' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                    {{ $order->order_type === 'delivery' ? 'bg-purple-100 text-purple-700' : '' }}">
                                    @if($order->order_type === 'dine_in')
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                        </svg>
                                        <span class="myanmar-text">ဆိုင်တွင်း</span>
                                    @elseif($order->order_type === 'takeaway')
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                        </svg>
                                        <span class="myanmar-text">ထုပ်ယူ</span>
                                    @else
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"></path>
                                        </svg>
                                        <span class="myanmar-text">ပို့ဆောင်</span>
                                    @endif
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if($order->table)
                                    <span class="font-medium text-gray-900">{{ $order->table->name }}</span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-bold text-gray-900">{{ number_format($order->total) }} <span class="text-xs font-normal text-gray-500">Ks</span></span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium
                                    {{ $order->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                    {{ $order->status === 'preparing' ? 'bg-blue-100 text-blue-700' : '' }}
                                    {{ $order->status === 'ready' ? 'bg-purple-100 text-purple-700' : '' }}
                                    {{ $order->status === 'completed' ? 'bg-green-100 text-green-700' : '' }}
                                    {{ $order->status === 'cancelled' ? 'bg-red-100 text-red-700' : '' }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $order->created_at->format('H:i') }}
                                <span class="text-gray-400 text-xs block">{{ $order->created_at->diffForHumans() }}</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <button wire:click="viewOrder({{ $order->id }})" class="px-3 py-1.5 text-sm font-medium text-orange-600 hover:text-orange-700 hover:bg-orange-50 rounded-lg transition-all">
                                    View
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-16 text-center text-gray-400">
                                <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                <p class="myanmar-text">အော်ဒါများ မရှိသေးပါ</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- Pagination --}}
            @if($orders->hasPages())
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $orders->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- Order Detail Modal --}}
    @if($showOrderModal && $selectedOrder)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-gray-900/80 backdrop-blur-sm" wire:click="$set('showOrderModal', false)"></div>
                <div class="relative bg-white rounded-2xl shadow-2xl max-w-2xl w-full p-6 max-h-[90vh] overflow-y-auto">
                    {{-- Header --}}
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">Order #{{ $selectedOrder->order_number }}</h3>
                            <p class="text-sm text-gray-500">{{ $selectedOrder->created_at->format('M d, Y H:i') }}</p>
                        </div>
                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium
                            {{ $selectedOrder->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : '' }}
                            {{ $selectedOrder->status === 'preparing' ? 'bg-blue-100 text-blue-700' : '' }}
                            {{ $selectedOrder->status === 'ready' ? 'bg-purple-100 text-purple-700' : '' }}
                            {{ $selectedOrder->status === 'completed' ? 'bg-green-100 text-green-700' : '' }}
                            {{ $selectedOrder->status === 'cancelled' ? 'bg-red-100 text-red-700' : '' }}">
                            {{ ucfirst($selectedOrder->status) }}
                        </span>
                    </div>

                    {{-- Order Info --}}
                    <div class="grid grid-cols-3 gap-4 mb-6">
                        <div class="bg-gray-50 rounded-xl p-4">
                            <p class="text-xs text-gray-500 mb-1 myanmar-text">အမျိုးအစား</p>
                            <p class="font-medium text-gray-900">{{ ucfirst(str_replace('_', ' ', $selectedOrder->order_type)) }}</p>
                        </div>
                        @if($selectedOrder->table)
                            <div class="bg-gray-50 rounded-xl p-4">
                                <p class="text-xs text-gray-500 mb-1 myanmar-text">စားပွဲ</p>
                                <p class="font-medium text-gray-900">{{ $selectedOrder->table->name }}</p>
                            </div>
                        @endif
                        <div class="bg-gray-50 rounded-xl p-4">
                            <p class="text-xs text-gray-500 mb-1 myanmar-text">ငွေပေးချေမှု</p>
                            <p class="font-medium text-gray-900">{{ ucfirst($selectedOrder->payment_method ?? 'Cash') }}</p>
                        </div>
                    </div>

                    {{-- Items --}}
                    <div class="border border-gray-200 rounded-xl overflow-hidden mb-6">
                        <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                            <h4 class="font-semibold text-gray-900 myanmar-text">မှာယူထားသောပစ္စည်းများ</h4>
                        </div>
                        <div class="divide-y divide-gray-100">
                            @foreach($selectedOrder->items as $item)
                                <div class="px-4 py-3 flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <span class="w-8 h-8 bg-orange-100 text-orange-600 rounded-lg flex items-center justify-center font-bold text-sm">
                                            {{ $item->pivot->quantity }}
                                        </span>
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $item->name }}</p>
                                            @if($item->pivot->notes)
                                                <p class="text-xs text-gray-500">{{ $item->pivot->notes }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <span class="font-medium text-gray-900">{{ number_format($item->pivot->subtotal) }} Ks</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Totals --}}
                    <div class="bg-gray-50 rounded-xl p-4 mb-6">
                        <div class="flex justify-between text-sm mb-2">
                            <span class="text-gray-500 myanmar-text">စုစုပေါင်း</span>
                            <span class="font-medium">{{ number_format($selectedOrder->subtotal) }} Ks</span>
                        </div>
                        @if($selectedOrder->tax_amount > 0)
                            <div class="flex justify-between text-sm mb-2">
                                <span class="text-gray-500">Tax</span>
                                <span class="font-medium">{{ number_format($selectedOrder->tax_amount) }} Ks</span>
                            </div>
                        @endif
                        @if($selectedOrder->service_charge > 0)
                            <div class="flex justify-between text-sm mb-2">
                                <span class="text-gray-500">Service Charge</span>
                                <span class="font-medium">{{ number_format($selectedOrder->service_charge) }} Ks</span>
                            </div>
                        @endif
                        @if($selectedOrder->discount_amount > 0)
                            <div class="flex justify-between text-sm mb-2">
                                <span class="text-gray-500 myanmar-text">လျှော့စျေး</span>
                                <span class="font-medium text-red-500">-{{ number_format($selectedOrder->discount_amount) }} Ks</span>
                            </div>
                        @endif
                        <div class="flex justify-between text-lg font-bold border-t border-gray-200 pt-2 mt-2">
                            <span class="myanmar-text">ပေးရန်</span>
                            <span class="text-orange-600">{{ number_format($selectedOrder->total) }} Ks</span>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="flex gap-3">
                        <button wire:click="$set('showOrderModal', false)" class="flex-1 px-4 py-2 border border-gray-200 rounded-xl text-gray-600 hover:bg-gray-50">
                            Close
                        </button>
                        @if($selectedOrder->status === 'pending')
                            <button wire:click="updateOrderStatus({{ $selectedOrder->id }}, 'preparing')" class="flex-1 px-4 py-2 bg-blue-500 text-white rounded-xl hover:bg-blue-600 font-medium">
                                Start Preparing
                            </button>
                        @elseif($selectedOrder->status === 'preparing')
                            <button wire:click="updateOrderStatus({{ $selectedOrder->id }}, 'ready')" class="flex-1 px-4 py-2 bg-purple-500 text-white rounded-xl hover:bg-purple-600 font-medium">
                                Mark Ready
                            </button>
                        @elseif($selectedOrder->status === 'ready')
                            <button wire:click="updateOrderStatus({{ $selectedOrder->id }}, 'completed')" class="flex-1 px-4 py-2 bg-green-500 text-white rounded-xl hover:bg-green-600 font-medium">
                                Complete
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
