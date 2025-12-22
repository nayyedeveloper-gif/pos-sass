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
                <h1 class="text-2xl font-bold text-gray-900 myanmar-text">အစီရင်ခံစာများ</h1>
                <p class="mt-2 text-sm text-gray-600 myanmar-text">ရောင်းအားနှင့် လုပ်ငန်းဆောင်ရွက်မှု အစီရင်ခံစာများကို ကြည့်ရှုနိုင်ပါသည်။</p>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Report Type -->
                <div>
                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1 myanmar-text">အစီရင်ခံစာ အမျိုးအစား</label>
                    <select wire:model.live="reportType" class="block w-full py-2.5 pl-3 pr-10 border border-gray-200 rounded-xl leading-5 bg-gray-50 focus:outline-none focus:bg-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500 sm:text-sm transition duration-150 ease-in-out myanmar-text">
                        <option value="daily">ယနေ့ / Daily</option>
                        <option value="weekly">ဤအပတ် / Weekly</option>
                        <option value="monthly">ဤလ / Monthly</option>
                        <option value="yearly">ဤနှစ် / Yearly</option>
                        <option value="custom">စိတ်ကြိုက် / Custom</option>
                    </select>
                </div>

                <!-- Start Date -->
                <div>
                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1 myanmar-text">စတင်သည့်ရက်</label>
                    <input type="date" wire:model="startDate" class="block w-full py-2.5 pl-3 pr-3 border border-gray-200 rounded-xl leading-5 bg-gray-50 focus:outline-none focus:bg-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500 sm:text-sm transition duration-150 ease-in-out">
                    @error('startDate') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- End Date -->
                <div>
                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1 myanmar-text">ပြီးဆုံးသည့်ရက်</label>
                    <input type="date" wire:model="endDate" class="block w-full py-2.5 pl-3 pr-3 border border-gray-200 rounded-xl leading-5 bg-gray-50 focus:outline-none focus:bg-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500 sm:text-sm transition duration-150 ease-in-out">
                    @error('endDate') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Export Button -->
                <div class="flex items-end">
                    <button wire:click="exportReport" class="w-full inline-flex justify-center items-center px-4 py-2.5 border border-transparent rounded-xl shadow-sm text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span class="myanmar-text">Excel ထုတ်မည်</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-4 md:gap-6 mb-6">
            <!-- Total Sales -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-center">
                <div class="p-3 rounded-xl bg-emerald-50 text-emerald-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div class="ml-4">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider myanmar-text">စုစုပေါင်း ရောင်းအား</p>
                    <p class="text-lg font-bold text-gray-900">{{ number_format($totalSales, 0) }} <span class="text-xs font-normal text-gray-400">Ks</span></p>
                </div>
            </div>

            <!-- Total Orders -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-center">
                <div class="p-3 rounded-xl bg-blue-50 text-blue-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                </div>
                <div class="ml-4">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider myanmar-text">စုစုပေါင်း အော်ဒါ</p>
                    <p class="text-lg font-bold text-gray-900">{{ number_format($totalOrders) }}</p>
                </div>
            </div>

            <!-- Average Order -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-center">
                <div class="p-3 rounded-xl bg-purple-50 text-purple-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                </div>
                <div class="ml-4">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider myanmar-text">ပျမ်းမျှ တန်ဖိုး</p>
                    <p class="text-lg font-bold text-gray-900">{{ number_format($averageOrderValue, 0) }} <span class="text-xs font-normal text-gray-400">Ks</span></p>
                </div>
            </div>

            <!-- Expenses -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-center">
                <div class="p-3 rounded-xl bg-rose-50 text-rose-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                <div class="ml-4">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider myanmar-text">အသုံးစရိတ်</p>
                    <p class="text-lg font-bold text-rose-600">{{ number_format($totalExpenses, 0) }} <span class="text-xs font-normal text-rose-400">Ks</span></p>
                </div>
            </div>

            <!-- FOC -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-center">
                <div class="p-3 rounded-xl bg-orange-50 text-orange-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path></svg>
                </div>
                <div class="ml-4">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider myanmar-text">FOC တန်ဖိုး ({{ $totalFocCount }})</p>
                    <p class="text-lg font-bold text-orange-600">{{ number_format($totalFocValue, 0) }} <span class="text-xs font-normal text-orange-400">Ks</span></p>
                </div>
            </div>

            <!-- Net Profit -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-center">
                <div class="p-3 rounded-xl {{ $netProfit >= 0 ? 'bg-green-50 text-green-600' : 'bg-red-50 text-red-600' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        @if($netProfit >= 0)
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        @else
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        @endif
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider myanmar-text">{{ $netProfit >= 0 ? 'အသားတင်အမြတ်' : 'အရှုံး' }}</p>
                    <p class="text-lg font-bold {{ $netProfit >= 0 ? 'text-green-600' : 'text-red-600' }}">{{ number_format(abs($netProfit), 0) }} <span class="text-xs font-normal text-gray-400">Ks</span></p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Top Selling Items -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="font-bold text-gray-900 myanmar-text">အရောင်းရဆုံး ပစ္စည်းများ</h3>
                </div>
                <div class="p-0">
                    @if($topSellingItems->count() > 0)
                    <div class="divide-y divide-gray-50">
                        @foreach($topSellingItems as $item)
                        <div class="flex items-center justify-between px-6 py-3 hover:bg-gray-50 transition-colors">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900 myanmar-text">{{ $item->name_mm }}</p>
                                <p class="text-xs text-gray-500">{{ $item->name }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-semibold text-gray-900">{{ $item->total_quantity }} <span class="myanmar-text text-xs text-gray-500">ခု</span></p>
                                <p class="text-xs text-gray-500">{{ number_format($item->total_sales, 0) }} Ks</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <p class="text-sm text-gray-500 text-center py-8 myanmar-text">ဒေတာ မရှိပါ</p>
                    @endif
                </div>
            </div>

            <!-- Sales by Category -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="font-bold text-gray-900 myanmar-text">အမျိုးအစားအလိုက် ရောင်းအား</h3>
                </div>
                <div class="p-0">
                    @if($salesByCategory->count() > 0)
                    <div class="divide-y divide-gray-50">
                        @foreach($salesByCategory as $category)
                        <div class="flex items-center justify-between px-6 py-3 hover:bg-gray-50 transition-colors">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900 myanmar-text">{{ $category->name_mm }}</p>
                                <p class="text-xs text-gray-500">{{ $category->name }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-semibold text-gray-900">{{ number_format($category->total_sales, 0) }} Ks</p>
                                <p class="text-xs text-gray-500 myanmar-text">{{ $category->order_count }} အော်ဒါ</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <p class="text-sm text-gray-500 text-center py-8 myanmar-text">ဒေတာ မရှိပါ</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sales by Order Type -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
            <h3 class="font-bold text-gray-900 mb-4 myanmar-text">အော်ဒါ အမျိုးအစားအလိုက် ရောင်းအား</h3>
            @if($salesByOrderType->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                @foreach($salesByOrderType as $type)
                <div class="border border-gray-100 rounded-xl p-4 bg-gray-50/50">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 myanmar-text mb-1">
                                {{ $type->order_type === 'dine_in' ? 'ဆိုင်တွင်းစားမည်' : 'ပါဆယ်ယူမည်' }}
                            </p>
                            <p class="text-2xl font-bold text-gray-900">{{ number_format($type->total_sales, 0) }} <span class="text-xs font-normal text-gray-400">Ks</span></p>
                            <p class="text-xs text-gray-500 mt-1 myanmar-text">{{ $type->count }} အော်ဒါ</p>
                        </div>
                        <div class="p-3 rounded-full bg-white shadow-sm">
                            @if($type->order_type === 'dine_in')
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            @else
                            <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <p class="text-sm text-gray-500 text-center py-4 myanmar-text">ဒေတာ မရှိပါ</p>
            @endif
        </div>

        <!-- Hourly Breakdown (for daily reports) -->
        @if($reportType === 'daily' && $hourlyBreakdown->count() > 0)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="font-bold text-gray-900 myanmar-text">နာရီအလိုက် ရောင်းအား</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50/50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider myanmar-text">အချိန်</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider myanmar-text">အော်ဒါ အရေအတွက်</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider myanmar-text">ရောင်းအား</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider myanmar-text">ရာခိုင်နှုန်း</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($hourlyBreakdown as $hour)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ str_pad($hour->hour, 2, '0', STR_PAD_LEFT) }}:00 - {{ str_pad($hour->hour + 1, 2, '0', STR_PAD_LEFT) }}:00
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $hour->order_count }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                {{ number_format($hour->total_sales, 0) }} Ks
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="w-full bg-gray-200 rounded-full h-1.5 max-w-xs">
                                    <div class="bg-primary-600 h-1.5 rounded-full" style="width: {{ ($hour->total_sales / $totalSales) * 100 }}%"></div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>
</div>
