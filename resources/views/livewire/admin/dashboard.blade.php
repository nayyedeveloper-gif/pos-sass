<div class="py-8 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900 myanmar-text">စီမံခန့်ခွဲမှု ဒက်ရှ်ဘုတ်</h1>
            <p class="text-sm text-gray-500 mt-1">{{ now()->format('F j, Y - l') }}</p>
        </div>

        <div class="space-y-8">
            <!-- Key Metrics Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Today's Sales -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center">
                    <div class="p-3 rounded-xl bg-emerald-50 text-emerald-600">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 myanmar-text">ယနေ့ ရောင်းအား</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($todaySales, 0) }} <span class="text-xs font-normal text-gray-400">Ks</span></p>
                    </div>
                </div>

                <!-- Today's Orders -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center">
                    <div class="p-3 rounded-xl bg-blue-50 text-blue-600">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 myanmar-text">ယနေ့ အော်ဒါ</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $todayOrders }}</p>
                        <p class="text-xs text-gray-400 myanmar-text">ပြီးစီး: {{ $completedOrdersToday }}</p>
                    </div>
                </div>

                <!-- Net Profit -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center">
                    <div class="p-3 rounded-xl {{ $todayNetProfit >= 0 ? 'bg-purple-50 text-purple-600' : 'bg-red-50 text-red-600' }}">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 myanmar-text">{{ $todayNetProfit >= 0 ? 'အသားတင်အမြတ်' : 'အရှုံး' }}</p>
                        <p class="text-2xl font-bold {{ $todayNetProfit >= 0 ? 'text-gray-900' : 'text-red-600' }}">{{ number_format(abs($todayNetProfit), 0) }} <span class="text-xs font-normal text-gray-400">Ks</span></p>
                    </div>
                </div>

                <!-- Expenses -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center">
                    <div class="p-3 rounded-xl bg-orange-50 text-orange-600">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 myanmar-text">ယနေ့ အသုံးစရိတ်</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($todayExpenses, 0) }} <span class="text-xs font-normal text-gray-400">Ks</span></p>
                    </div>
                </div>
            </div>

            <!-- Income Overview Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Weekly Income -->
                <div class="bg-gradient-to-br from-violet-500 to-purple-600 rounded-2xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-2 bg-white/20 rounded-xl">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <span class="flex items-center text-xs font-medium {{ $weeklyGrowth >= 0 ? 'text-green-200' : 'text-red-200' }}">
                            @if($weeklyGrowth >= 0)
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                            @else
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                            @endif
                            {{ abs($weeklyGrowth) }}%
                        </span>
                    </div>
                    <p class="text-sm text-white/80 myanmar-text">ဤအပတ် ဝင်ငွေ</p>
                    <p class="text-2xl font-bold mt-1">{{ number_format($weeklyIncome, 0) }} <span class="text-sm font-normal">Ks</span></p>
                </div>

                <!-- Monthly Income -->
                <div class="bg-gradient-to-br from-cyan-500 to-blue-600 rounded-2xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-2 bg-white/20 rounded-xl">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        </div>
                        <span class="flex items-center text-xs font-medium {{ $monthlyGrowth >= 0 ? 'text-green-200' : 'text-red-200' }}">
                            @if($monthlyGrowth >= 0)
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                            @else
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                            @endif
                            {{ abs($monthlyGrowth) }}%
                        </span>
                    </div>
                    <p class="text-sm text-white/80 myanmar-text">ဤလ ဝင်ငွေ</p>
                    <p class="text-2xl font-bold mt-1">{{ number_format($monthlyIncome, 0) }} <span class="text-sm font-normal">Ks</span></p>
                </div>

                <!-- Yearly Income -->
                <div class="bg-gradient-to-br from-emerald-500 to-green-600 rounded-2xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-2 bg-white/20 rounded-xl">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                        </div>
                    </div>
                    <p class="text-sm text-white/80 myanmar-text">ဤနှစ် ဝင်ငွေ</p>
                    <p class="text-2xl font-bold mt-1">{{ number_format($yearlyIncome, 0) }} <span class="text-sm font-normal">Ks</span></p>
                </div>

                <!-- Average Order Value -->
                <div class="bg-gradient-to-br from-orange-500 to-amber-600 rounded-2xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-2 bg-white/20 rounded-xl">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                        </div>
                    </div>
                    <p class="text-sm text-white/80 myanmar-text">ပျမ်းမျှ အော်ဒါတန်ဖိုး</p>
                    <p class="text-2xl font-bold mt-1">{{ number_format($averageOrderValue, 0) }} <span class="text-sm font-normal">Ks</span></p>
                </div>
            </div>

            <!-- Sales Chart Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Daily Sales Chart -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h3 class="font-bold text-gray-900 myanmar-text">နေ့စဉ် ရောင်းအား (လွန်ခဲ့သော ၇ ရက်)</h3>
                    </div>
                    <div class="p-6">
                        <div class="flex items-end justify-between h-48 gap-2">
                            @php
                                $maxSales = max(array_column($dailySalesData, 'sales')) ?: 1;
                            @endphp
                            @foreach($dailySalesData as $data)
                            <div class="flex-1 flex flex-col items-center">
                                <div class="w-full bg-gray-100 rounded-t-lg relative" style="height: {{ ($data['sales'] / $maxSales) * 100 }}%">
                                    <div class="absolute inset-0 bg-gradient-to-t from-violet-500 to-purple-400 rounded-t-lg"></div>
                                </div>
                                <p class="text-xs text-gray-500 mt-2">{{ $data['day'] }}</p>
                                <p class="text-xs font-medium text-gray-700">{{ number_format($data['sales'] / 1000, 0) }}K</p>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Monthly Sales Chart -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h3 class="font-bold text-gray-900 myanmar-text">လစဉ် ရောင်းအား (လွန်ခဲ့သော ၆ လ)</h3>
                    </div>
                    <div class="p-6">
                        <div class="flex items-end justify-between h-48 gap-3">
                            @php
                                $maxMonthlySales = max(array_column($monthlySalesData, 'sales')) ?: 1;
                            @endphp
                            @foreach($monthlySalesData as $data)
                            <div class="flex-1 flex flex-col items-center">
                                <div class="w-full bg-gray-100 rounded-t-lg relative" style="height: {{ ($data['sales'] / $maxMonthlySales) * 100 }}%">
                                    <div class="absolute inset-0 bg-gradient-to-t from-cyan-500 to-blue-400 rounded-t-lg"></div>
                                </div>
                                <p class="text-xs text-gray-500 mt-2">{{ $data['short'] }}</p>
                                <p class="text-xs font-medium text-gray-700">{{ number_format($data['sales'] / 1000000, 1) }}M</p>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Financial Transparency -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="font-bold text-gray-900 myanmar-text">ယနေ့ ငွေကြေးအနှစ်ချုပ်</h3>
                    <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded-lg">{{ now()->format('d M Y') }}</span>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                        <div class="space-y-1">
                            <p class="text-xs text-gray-500 uppercase font-semibold tracking-wider">Subtotal</p>
                            <p class="text-lg font-medium text-gray-900">{{ number_format($todaySubtotal, 0) }} Ks</p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-xs text-gray-500 uppercase font-semibold tracking-wider">Tax</p>
                            <p class="text-lg font-medium text-gray-900">{{ number_format($todayTax, 0) }} Ks</p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-xs text-gray-500 uppercase font-semibold tracking-wider">Service Charge</p>
                            <p class="text-lg font-medium text-gray-900">{{ number_format($todayServiceCharge, 0) }} Ks</p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-xs text-gray-500 uppercase font-semibold tracking-wider">Discount / FOC</p>
                            <p class="text-lg font-medium text-red-500">-{{ number_format($todayDiscount + $todayFOC, 0) }} Ks</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Recent Orders -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                        <h3 class="font-bold text-gray-900 myanmar-text">လတ်တလော အော်ဒါများ</h3>
                        <button wire:click="refresh" class="text-sm text-primary-600 hover:text-primary-700 font-medium flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                            Refresh
                        </button>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-gray-50/50">
                                <tr>
                                    <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">ID</th>
                                    @if(\App\Helpers\FeatureHelper::has('tables'))
                                    <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">စားပွဲ</th>
                                    @endif
                                    <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase text-right">ပမာဏ</th>
                                    <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase text-center">အခြေအနေ</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse($recentOrders as $order)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-6 py-3 font-mono text-gray-600">#{{ $order->order_number }}</td>
                                    @if(\App\Helpers\FeatureHelper::has('tables'))
                                    <td class="px-6 py-3">
                                        <span class="myanmar-text text-gray-900 font-medium">{{ $order->table ? $order->table->name_mm : 'Takeaway' }}</span>
                                    </td>
                                    @endif
                                    <td class="px-6 py-3 text-right font-medium text-gray-900">{{ number_format($order->total, 0) }}</td>
                                    <td class="px-6 py-3 text-center">
                                        @if($order->status === 'pending')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800 border border-yellow-200">Pending</span>
                                        @elseif($order->status === 'completed')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 border border-green-200">Paid</span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">Cancelled</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="{{ \App\Helpers\FeatureHelper::has('tables') ? 4 : 3 }}" class="px-6 py-8 text-center text-gray-400 text-xs myanmar-text">အော်ဒါ မရှိပါ</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Recent Expenses -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                        <h3 class="font-bold text-gray-900 myanmar-text">မကြာသေးမီ အသုံးစရိတ်များ</h3>
                        <a href="{{ route('admin.expenses.index') }}" class="text-sm text-primary-600 hover:text-primary-700 font-medium">View All</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-gray-50/50">
                                <tr>
                                    <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">ခေါင်းစဉ်</th>
                                    <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">ဖော်ပြချက်</th>
                                    <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase text-right">ပမာဏ</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse($recentExpenses as $expense)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-6 py-3 font-medium text-gray-900 myanmar-text">{{ $expense->category }}</td>
                                    <td class="px-6 py-3 text-gray-500 truncate max-w-xs">{{ $expense->description }}</td>
                                    <td class="px-6 py-3 text-right font-medium text-red-600">{{ number_format($expense->amount, 0) }}</td>
                                </tr>
                                @empty
                                <tr><td colspan="3" class="px-6 py-8 text-center text-gray-400 text-xs myanmar-text">အသုံးစရိတ် မရှိပါ</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-gradient-to-r from-gray-900 to-gray-800 rounded-2xl shadow-sm p-6 text-white">
                <h3 class="font-bold text-lg mb-4 myanmar-text">အမြန်လုပ်ဆောင်ချက်များ</h3>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <a href="{{ route('admin.items.index') }}" class="flex flex-col items-center justify-center p-4 bg-white/10 hover:bg-white/20 rounded-xl transition-all backdrop-blur-sm">
                        <svg class="w-6 h-6 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                        <span class="text-sm font-medium myanmar-text">ပစ္စည်းများ</span>
                    </a>
                    @if(\App\Helpers\FeatureHelper::has('tables'))
                    <a href="{{ route('admin.tables.index') }}" class="flex flex-col items-center justify-center p-4 bg-white/10 hover:bg-white/20 rounded-xl transition-all backdrop-blur-sm">
                        <svg class="w-6 h-6 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                        <span class="text-sm font-medium myanmar-text">စားပွဲများ</span>
                    </a>
                    @endif
                    <a href="{{ route('admin.reports.index') }}" class="flex flex-col items-center justify-center p-4 bg-white/10 hover:bg-white/20 rounded-xl transition-all backdrop-blur-sm">
                        <svg class="w-6 h-6 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        <span class="text-sm font-medium myanmar-text">အစီရင်ခံစာ</span>
                    </a>
                    <a href="{{ route('admin.roles-permissions.index') }}" class="flex flex-col items-center justify-center p-4 bg-white/10 hover:bg-white/20 rounded-xl transition-all backdrop-blur-sm">
                        <svg class="w-6 h-6 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        <span class="text-sm font-medium myanmar-text">ဝန်ထမ်းများ</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
