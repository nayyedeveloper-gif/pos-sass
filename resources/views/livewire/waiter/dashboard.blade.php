<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900 myanmar-text">မင်္ဂလာပါ, {{ auth()->user()->name }}</h1>
            <p class="mt-2 text-sm text-gray-600 myanmar-text">
                ယနေ့ အလုပ်အခြေအနေ
            </p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <!-- Active Orders -->
            <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-yellow-100 text-sm myanmar-text">လက်ရှိ အော်ဒါ</p>
                        <p class="text-3xl font-bold mt-2">{{ $todayStats['active'] }}</p>
                    </div>
                    <div class="bg-white bg-opacity-30 rounded-full p-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Completed Today -->
            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm myanmar-text">ပြီးစီးပြီး</p>
                        <p class="text-3xl font-bold mt-2">{{ $todayStats['completed'] }}</p>
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
                        <p class="text-blue-100 text-sm myanmar-text">ယနေ့ ရောင်းရငွေ</p>
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

            <!-- Total Orders -->
            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100 text-sm myanmar-text">စုစုပေါင်း</p>
                        <p class="text-3xl font-bold mt-2">{{ $todayStats['total_orders'] }}</p>
                    </div>
                    <div class="bg-white bg-opacity-30 rounded-full p-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
            <a href="{{ route('waiter.tables.index') }}" class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow p-6 border-2 border-green-200 hover:border-green-400">
                <div class="flex items-center">
                    <div class="bg-green-100 rounded-full p-3 mr-4">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 myanmar-text">စားပွဲများ</h3>
                        <p class="text-sm text-gray-600 myanmar-text">အော်ဒါယူရန်</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('waiter.orders.create', ['type' => 'takeaway']) }}" class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow p-6 border-2 border-blue-200 hover:border-blue-400">
                <div class="flex items-center">
                    <div class="bg-blue-100 rounded-full p-3 mr-4">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 myanmar-text">ပါဆယ်ယူမည်</h3>
                        <p class="text-sm text-gray-600 myanmar-text">Takeaway Order</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('waiter.orders.index') }}" class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow p-6 border-2 border-purple-200 hover:border-purple-400">
                <div class="flex items-center">
                    <div class="bg-purple-100 rounded-full p-3 mr-4">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 myanmar-text">ကျွန်ုပ်၏ အော်ဒါများ</h3>
                        <p class="text-sm text-gray-600 myanmar-text">My Orders</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Active Orders -->
        @if($activeOrders->count() > 0)
        <div class="mb-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-gray-900 myanmar-text">လက်ရှိ အော်ဒါများ</h2>
                <a href="{{ route('waiter.orders.index') }}" class="text-sm text-blue-600 hover:text-blue-800 myanmar-text">အားလုံးကြည့်ရန် →</a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($activeOrders as $order)
                <div class="bg-white rounded-xl shadow-md p-4 border-l-4 border-yellow-500">
                    <div class="flex items-center justify-between mb-3">
                        <span class="font-bold text-gray-900">{{ $order->order_number }}</span>
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 myanmar-text">
                            စောင့်ဆိုင်းဆဲ
                        </span>
                    </div>
                    @if($order->table)
                    <div class="flex items-center text-sm text-gray-600 mb-2">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                        </svg>
                        {{ $order->table->name }} - <span class="myanmar-text">{{ $order->table->name_mm }}</span>
                    </div>
                    @endif
                    <div class="text-sm text-gray-600 mb-2">
                        {{ $order->orderItems->count() }} <span class="myanmar-text">မျိုး</span>
                    </div>
                    <div class="flex items-center justify-between mt-3 pt-3 border-t border-gray-200">
                        <span class="font-bold text-gray-900">{{ number_format($order->total, 0) }} Ks</span>
                        <a href="{{ route('waiter.orders.create', ['order' => $order->id]) }}" class="text-xs text-blue-600 hover:text-blue-800 myanmar-text">
                            ထပ်ထည့်မည် →
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Recent Completed Orders -->
        @if($recentOrders->count() > 0)
        <div>
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-gray-900 myanmar-text">မကြာသေးမီ ပြီးစီးသော အော်ဒါများ</h2>
            </div>
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider myanmar-text">အော်ဒါ</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider myanmar-text">စားပွဲ</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider myanmar-text">ပစ္စည်း</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider myanmar-text">စုစုပေါင်း</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider myanmar-text">အချိန်</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($recentOrders as $order)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $order->order_number }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    @if($order->table)
                                        {{ $order->table->name }}
                                    @else
                                        <span class="myanmar-text">ပါဆယ်ယူမည်</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ $order->orderItems->count() }} <span class="myanmar-text">မျိုး</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ number_format($order->total, 0) }} Ks
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $order->created_at->format('g:i A') }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
