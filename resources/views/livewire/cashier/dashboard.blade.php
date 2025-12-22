<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900 myanmar-text">ငွေကောင်တာ Dashboard</h1>
            <p class="mt-2 text-sm text-gray-600 myanmar-text">
                ယနေ့ ရောင်းချမှု အခြေအနေ
            </p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <!-- Pending Payments -->
            <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-red-100 text-sm myanmar-text">စောင့်ဆိုင်းဆဲ</p>
                        <p class="text-3xl font-bold mt-2">{{ $todayStats['pending'] }}</p>
                        <p class="text-red-100 text-xs myanmar-text">ငွေရှင်းရန်</p>
                    </div>
                    <div class="bg-white bg-opacity-30 rounded-full p-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Completed Today -->
            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm myanmar-text">ပြီးစီးပြီး</p>
                        <p class="text-3xl font-bold mt-2">{{ $todayStats['completed_today'] }}</p>
                        <p class="text-green-100 text-xs myanmar-text">ယနေ့</p>
                    </div>
                    <div class="bg-white bg-opacity-30 rounded-full p-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Sales -->
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm myanmar-text">စုစုပေါင်း ရောင်းရငွေ</p>
                        <p class="text-2xl font-bold mt-2">{{ number_format($todayStats['total_sales'], 0) }}</p>
                        <p class="text-blue-100 text-xs">Ks</p>
                    </div>
                    <div class="bg-white bg-opacity-30 rounded-full p-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Average Sale -->
            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100 text-sm myanmar-text">ပျမ်းမျှ</p>
                        <p class="text-2xl font-bold mt-2">{{ number_format($todayStats['average_sale'], 0) }}</p>
                        <p class="text-purple-100 text-xs">Ks / order</p>
                    </div>
                    <div class="bg-white bg-opacity-30 rounded-full p-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <a href="{{ route('cashier.pos') }}" class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow p-6 border-2 border-green-200 hover:border-green-400">
                <div class="flex items-center">
                    <div class="bg-green-100 rounded-full p-3 mr-4">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900">POS</h3>
                        <p class="text-sm text-gray-600 myanmar-text">တိုက်ရိုက်ရောင်းချရန်</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('cashier.tables.index') }}" class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow p-6 border-2 border-rose-200 hover:border-rose-400">
                <div class="flex items-center">
                    <div class="bg-rose-100 rounded-full p-3 mr-4">
                        <svg class="w-6 h-6 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 myanmar-text">စားပွဲများ</h3>
                        <p class="text-sm text-gray-600 myanmar-text">စားပွဲအလိုက် ငွေရှင်းရန်</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('cashier.orders.index') }}" class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow p-6 border-2 border-yellow-200 hover:border-yellow-400">
                <div class="flex items-center">
                    <div class="bg-yellow-100 rounded-full p-3 mr-4">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 myanmar-text">အော်ဒါများ</h3>
                        <p class="text-sm text-gray-600 myanmar-text">ငွေရှင်းရန်</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('cashier.orders.index') }}" class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow p-6 border-2 border-blue-200 hover:border-blue-400">
                <div class="flex items-center">
                    <div class="bg-blue-100 rounded-full p-3 mr-4">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 myanmar-text">ယနေ့ အစီရင်ခံစာ</h3>
                        <p class="text-sm text-gray-600 myanmar-text">ရောင်းချမှု</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Pending Orders -->
        @if($pendingOrders->count() > 0)
        <div class="mb-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-gray-900 myanmar-text">ငွေရှင်းရန် စောင့်ဆိုင်းနေသော အော်ဒါများ</h2>
                <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm font-semibold">
                    {{ $pendingOrders->count() }} <span class="myanmar-text">ခု</span>
                </span>
            </div>
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider myanmar-text">အော်ဒါ</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider myanmar-text">စားပွဲ</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider myanmar-text">စားပွဲထိုး</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider myanmar-text">ပစ္စည်း</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider myanmar-text">စုစုပေါင်း</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider myanmar-text">အချိန်</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($pendingOrders as $order)
                            <tr class="hover:bg-gray-50 cursor-pointer" onclick="window.location='{{ route('cashier.orders.index') }}''">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $order->order_number }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    @if($order->table)
                                        {{ $order->table->name }}
                                    @else
                                        <span class="myanmar-text">ပါဆယ်ယူမည်</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ $order->waiter->name ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ $order->orderItems->count() }} <span class="myanmar-text">မျိုး</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-gray-900">{{ number_format($order->total, 0) }} Ks</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $order->created_at->diffForHumans() }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @else
        <div class="bg-white rounded-xl shadow-md p-12 text-center mb-6">
            <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p class="text-gray-500 myanmar-text text-lg">ငွေရှင်းရန် စောင့်ဆိုင်းနေသော အော်ဒါ ကောင်တာပါ</p>
            <p class="text-gray-400 text-sm mt-2 myanmar-text">အားလုံး ပြီးစီးပါပြီ!</p>
        </div>
        @endif

        <!-- Recent Sales -->
        @if($recentSales->count() > 0)
        <div>
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-gray-900 myanmar-text">မကြာသေးမီ ရောင်းချမှုများ</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($recentSales as $sale)
                <div class="bg-white rounded-xl shadow-md p-4 border-l-4 border-green-500">
                    <div class="flex items-center justify-between mb-2">
                        <span class="font-bold text-gray-900">{{ $sale->order_number }}</span>
                        <span class="text-xs text-gray-500">{{ $sale->completed_at ? $sale->completed_at->format('g:i A') : $sale->created_at->format('g:i A') }}</span>
                    </div>
                    @if($sale->table)
                    <div class="text-sm text-gray-600 mb-2">
                        <span class="myanmar-text">စားပွဲ:</span> {{ $sale->table->name }}
                    </div>
                    @endif
                    <div class="flex items-center justify-between mt-3 pt-3 border-t border-gray-200">
                        <span class="text-sm text-gray-600 myanmar-text">စုစုပေါင်း</span>
                        <span class="font-bold text-green-600">{{ number_format($sale->total, 0) }} Ks</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
