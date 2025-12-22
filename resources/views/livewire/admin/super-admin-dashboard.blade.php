<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">SaaS Dashboard</h1>
                <p class="mt-1 text-sm text-gray-500">Platform overview and revenue analytics</p>
            </div>
            <button wire:click="refresh" class="inline-flex items-center px-3 py-2 bg-white border border-gray-200 rounded-lg text-sm text-gray-600 hover:bg-gray-50 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                Refresh
            </button>
        </div>

        <!-- Platform Revenue Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <!-- Today's Revenue -->
            <div class="bg-gradient-to-br from-emerald-500 to-green-600 rounded-2xl shadow-lg p-5 text-white">
                <div class="flex items-center justify-between mb-3">
                    <div class="p-2 bg-white/20 rounded-xl">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
                <p class="text-sm text-white/80">Today's Revenue</p>
                <p class="text-2xl font-bold mt-1">{{ number_format($todayRevenue, 0) }} <span class="text-sm font-normal">Ks</span></p>
            </div>

            <!-- Weekly Revenue -->
            <div class="bg-gradient-to-br from-violet-500 to-purple-600 rounded-2xl shadow-lg p-5 text-white">
                <div class="flex items-center justify-between mb-3">
                    <div class="p-2 bg-white/20 rounded-xl">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <span class="flex items-center text-xs font-medium {{ $weeklyGrowth >= 0 ? 'text-green-200' : 'text-red-200' }}">
                        @if($weeklyGrowth >= 0)
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                        @else
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                        @endif
                        {{ abs($weeklyGrowth) }}%
                    </span>
                </div>
                <p class="text-sm text-white/80">This Week</p>
                <p class="text-2xl font-bold mt-1">{{ number_format($weeklyRevenue, 0) }} <span class="text-sm font-normal">Ks</span></p>
            </div>

            <!-- Monthly Revenue -->
            <div class="bg-gradient-to-br from-cyan-500 to-blue-600 rounded-2xl shadow-lg p-5 text-white">
                <div class="flex items-center justify-between mb-3">
                    <div class="p-2 bg-white/20 rounded-xl">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    </div>
                    <span class="flex items-center text-xs font-medium {{ $monthlyGrowth >= 0 ? 'text-green-200' : 'text-red-200' }}">
                        @if($monthlyGrowth >= 0)
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                        @else
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                        @endif
                        {{ abs($monthlyGrowth) }}%
                    </span>
                </div>
                <p class="text-sm text-white/80">This Month</p>
                <p class="text-2xl font-bold mt-1">{{ number_format($monthlyRevenue, 0) }} <span class="text-sm font-normal">Ks</span></p>
            </div>

            <!-- Yearly Revenue -->
            <div class="bg-gradient-to-br from-orange-500 to-amber-600 rounded-2xl shadow-lg p-5 text-white">
                <div class="flex items-center justify-between mb-3">
                    <div class="p-2 bg-white/20 rounded-xl">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                    </div>
                </div>
                <p class="text-sm text-white/80">This Year</p>
                <p class="text-2xl font-bold mt-1">{{ number_format($yearlyRevenue, 0) }} <span class="text-sm font-normal">Ks</span></p>
            </div>
        </div>

        <!-- Revenue Charts -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Daily Revenue Chart -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-900">Daily Revenue (Last 7 Days)</h3>
                </div>
                <div class="p-5">
                    <div class="flex items-end justify-between h-40 gap-2">
                        @php
                            $maxDaily = max(array_column($dailyRevenueData, 'revenue')) ?: 1;
                        @endphp
                        @foreach($dailyRevenueData as $data)
                        <div class="flex-1 flex flex-col items-center">
                            <div class="w-full bg-gray-100 rounded-t-lg relative" style="height: {{ max(($data['revenue'] / $maxDaily) * 100, 2) }}%">
                                <div class="absolute inset-0 bg-gradient-to-t from-emerald-500 to-green-400 rounded-t-lg"></div>
                            </div>
                            <p class="text-xs text-gray-500 mt-2">{{ $data['day'] }}</p>
                            <p class="text-xs font-medium text-gray-700">{{ number_format($data['revenue'] / 1000, 0) }}K</p>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Monthly Revenue Chart -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-900">Monthly Revenue (Last 6 Months)</h3>
                </div>
                <div class="p-5">
                    <div class="flex items-end justify-between h-40 gap-3">
                        @php
                            $maxMonthly = max(array_column($monthlyRevenueData, 'revenue')) ?: 1;
                        @endphp
                        @foreach($monthlyRevenueData as $data)
                        <div class="flex-1 flex flex-col items-center">
                            <div class="w-full bg-gray-100 rounded-t-lg relative" style="height: {{ max(($data['revenue'] / $maxMonthly) * 100, 2) }}%">
                                <div class="absolute inset-0 bg-gradient-to-t from-cyan-500 to-blue-400 rounded-t-lg"></div>
                            </div>
                            <p class="text-xs text-gray-500 mt-2">{{ $data['short'] }}</p>
                            <p class="text-xs font-medium text-gray-700">{{ number_format($data['revenue'] / 1000000, 1) }}M</p>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Tenants & Platform Stats -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Top Tenants by Revenue -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="font-semibold text-gray-900">Top Tenants (This Month)</h3>
                    <a href="{{ route('admin.tenants.index') }}" class="text-sm text-primary-600 hover:text-primary-800">View All →</a>
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse($topTenantsByRevenue as $index => $tenant)
                    <div class="px-5 py-3 flex items-center justify-between">
                        <div class="flex items-center">
                            <span class="w-6 h-6 rounded-full bg-gradient-to-br from-violet-500 to-purple-600 text-white text-xs font-bold flex items-center justify-center mr-3">{{ $index + 1 }}</span>
                            <div>
                                <p class="font-medium text-gray-900">{{ $tenant['tenant_name'] }}</p>
                                <p class="text-xs text-gray-500">{{ ucfirst($tenant['business_type']) }} • {{ $tenant['orders'] }} orders</p>
                            </div>
                        </div>
                        <p class="font-semibold text-gray-900">{{ number_format($tenant['revenue'], 0) }} Ks</p>
                    </div>
                    @empty
                    <div class="px-5 py-8 text-center text-gray-400 text-sm">No revenue data this month</div>
                    @endforelse
                </div>
            </div>

            <!-- Platform Stats -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-900">Platform Statistics</h3>
                </div>
                <div class="p-5 grid grid-cols-2 gap-4">
                    <div class="bg-gray-50 rounded-xl p-4">
                        <p class="text-sm text-gray-500">Total Tenants</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $totalTenants }}</p>
                        <div class="mt-2 flex items-center gap-2 text-xs">
                            <span class="px-2 py-0.5 bg-green-100 text-green-700 rounded">{{ $activeTenants }} Active</span>
                            <span class="px-2 py-0.5 bg-blue-100 text-blue-700 rounded">{{ $trialTenants }} Trial</span>
                        </div>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-4">
                        <p class="text-sm text-gray-500">Total Users</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $totalUsers }}</p>
                        <p class="mt-2 text-xs text-gray-500">{{ $activeUsers }} active users</p>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-4">
                        <p class="text-sm text-gray-500">Lifetime Revenue</p>
                        <p class="text-xl font-bold text-emerald-600">{{ number_format($totalLifetimeRevenue, 0) }} Ks</p>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-4">
                        <p class="text-sm text-gray-500">Roles & Permissions</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $totalRoles }}</p>
                        <p class="mt-2 text-xs text-gray-500">{{ $totalPermissions }} permissions</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- License Status -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-200 flex items-center justify-between">
                <h3 class="font-semibold text-gray-900">License Status</h3>
                <a href="{{ route('admin.licenses.index') }}" class="text-sm text-primary-600 hover:text-primary-800">Manage →</a>
            </div>
            <div class="p-5">
                @if($currentLicense)
                    <div class="flex items-center">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            Active
                        </span>
                        <span class="ml-3 text-sm text-gray-600">{{ $currentLicense['business_name'] }}</span>
                    </div>
                @else
                    <div class="flex items-center">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                            No License
                        </span>
                        <a href="{{ route('admin.licenses.index') }}" class="ml-3 text-sm text-primary-600 hover:underline">Activate now</a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
            <a href="{{ route('admin.tenants.index') }}" class="bg-white rounded-lg shadow-sm border border-gray-200 p-5 hover:border-primary-300 hover:shadow-md transition-all">
                <div class="flex items-center">
                    <div class="p-2 bg-teal-100 rounded-lg">
                        <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h4 class="font-medium text-gray-900">Tenants</h4>
                        <p class="text-xs text-gray-500">Manage clients</p>
                    </div>
                </div>
            </a>
            <a href="{{ route('admin.licenses.index') }}" class="bg-white rounded-lg shadow-sm border border-gray-200 p-5 hover:border-primary-300 hover:shadow-md transition-all">
                <div class="flex items-center">
                    <div class="p-2 bg-indigo-100 rounded-lg">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h4 class="font-medium text-gray-900">Licenses</h4>
                        <p class="text-xs text-gray-500">Manage licenses</p>
                    </div>
                </div>
            </a>
            <a href="{{ route('admin.roles-permissions.index') }}" class="bg-white rounded-lg shadow-sm border border-gray-200 p-5 hover:border-primary-300 hover:shadow-md transition-all">
                <div class="flex items-center">
                    <div class="p-2 bg-purple-100 rounded-lg">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h4 class="font-medium text-gray-900">Users</h4>
                        <p class="text-xs text-gray-500">Manage access</p>
                    </div>
                </div>
            </a>
            <a href="{{ route('admin.super-settings') }}" class="bg-white rounded-lg shadow-sm border border-gray-200 p-5 hover:border-primary-300 hover:shadow-md transition-all">
                <div class="flex items-center">
                    <div class="p-2 bg-gray-100 rounded-lg">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h4 class="font-medium text-gray-900">Settings</h4>
                        <p class="text-xs text-gray-500">System config</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- System Info -->
        <div class="bg-gray-50 rounded-lg border border-gray-200 p-5">
            <h4 class="text-sm font-medium text-gray-700 mb-3">System Info</h4>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                <div>
                    <span class="text-gray-500">PHP</span>
                    <p class="font-medium">{{ $systemInfo['php_version'] }}</p>
                </div>
                <div>
                    <span class="text-gray-500">Laravel</span>
                    <p class="font-medium">{{ $systemInfo['laravel_version'] }}</p>
                </div>
                <div>
                    <span class="text-gray-500">Environment</span>
                    <p class="font-medium">{{ ucfirst($systemInfo['environment']) }}</p>
                </div>
                <div>
                    <span class="text-gray-500">Timezone</span>
                    <p class="font-medium">{{ $systemInfo['timezone'] }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
