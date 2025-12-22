<div x-data="{ copied: false }" class="py-6 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Flash Messages -->
        @if (session()->has('success'))
        <div class="mb-6 bg-emerald-50 border border-emerald-100 rounded-xl p-4 flex items-center shadow-sm"
             x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
            <svg class="h-5 w-5 text-emerald-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            <p class="text-sm font-medium text-emerald-800">{{ session('success') }}</p>
        </div>
        @endif

        @if (session()->has('error'))
        <div class="mb-6 bg-red-50 border border-red-100 rounded-xl p-4 flex items-center shadow-sm"
             x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
            <svg class="h-5 w-5 text-red-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
            </svg>
            <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
        </div>
        @endif

        <!-- Header -->
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">License Management</h1>
                <p class="mt-1 text-sm text-gray-500">Generate and manage license keys for clients</p>
            </div>
        </div>

        <!-- Stats Cards - Matching Tenant Management Style -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <!-- License Status -->
            <div class="bg-gradient-to-br from-gray-700 to-gray-900 rounded-2xl shadow-lg p-5 text-white">
                <div class="flex items-center justify-between mb-3">
                    <div class="p-2 bg-white/20 rounded-xl">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    </div>
                </div>
                <p class="text-sm text-white/80">License Status</p>
                <p class="text-2xl font-bold mt-1">{{ $currentLicense && $currentLicense['valid'] ? 'Active' : 'None' }}</p>
            </div>
            
            <!-- Business Type -->
            <div class="bg-gradient-to-br from-emerald-500 to-green-600 rounded-2xl shadow-lg p-5 text-white">
                <div class="flex items-center justify-between mb-3">
                    <div class="p-2 bg-white/20 rounded-xl">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                </div>
                <p class="text-sm text-white/80">Business Type</p>
                <p class="text-lg font-bold mt-1">{{ $currentLicense['business_name'] ?? 'N/A' }}</p>
            </div>
            
            <!-- License Type -->
            <div class="bg-gradient-to-br from-cyan-500 to-blue-600 rounded-2xl shadow-lg p-5 text-white">
                <div class="flex items-center justify-between mb-3">
                    <div class="p-2 bg-white/20 rounded-xl">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                    </div>
                </div>
                <p class="text-sm text-white/80">License Type</p>
                <p class="text-2xl font-bold mt-1">{{ $currentLicense['license_type'] ?? 'N/A' }}</p>
            </div>
            
            <!-- Features -->
            <div class="bg-gradient-to-br from-violet-500 to-purple-600 rounded-2xl shadow-lg p-5 text-white">
                <div class="flex items-center justify-between mb-3">
                    <div class="p-2 bg-white/20 rounded-xl">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                    </div>
                </div>
                <p class="text-sm text-white/80">Features</p>
                <p class="text-2xl font-bold mt-1">{{ count($currentLicense['features'] ?? []) }} Enabled</p>
            </div>
        </div>

        <!-- Current License Details -->
        @if($currentLicense && $currentLicense['valid'])
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-6">
            <div class="px-6 py-4 bg-gray-50/50 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-3">
                    <div class="p-2 bg-emerald-100 rounded-lg">
                        <svg class="h-5 w-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    Current License Details
                </h3>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-emerald-100 text-emerald-800">
                    <span class="w-2 h-2 bg-emerald-500 rounded-full mr-2 animate-pulse"></span>
                    Active
                </span>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div class="bg-gray-50 rounded-xl p-4">
                        <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">License Key</p>
                        <p class="font-mono text-sm text-gray-800 break-all">{{ $currentLicense['key'] }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-4">
                        <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Business Type (Myanmar)</p>
                        <p class="font-semibold text-gray-800 myanmar-text">{{ $currentLicense['business_name_mm'] }}</p>
                    </div>
                </div>
                
                @if(isset($currentLicense['features']) && count($currentLicense['features']) > 0)
                <div class="pt-4 border-t border-gray-100">
                    <p class="text-xs text-gray-500 uppercase tracking-wider mb-3">Enabled Features</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach($currentLicense['features'] as $feature)
                        <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-medium bg-violet-100 text-violet-800">
                            {{ $feature === 'all' ? 'All Features' : ucfirst(str_replace('_', ' ', $feature)) }}
                        </span>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
        @endif

        <!-- License Generator -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 bg-gray-50/50 border-b border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                    <div class="p-2 bg-violet-100 rounded-lg">
                        <svg class="h-5 w-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                        </svg>
                    </div>
                    Generate License Key
                </h3>
            </div>
            <div class="p-6">
                <form wire:submit.prevent="generateLicense" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Machine ID Input -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Machine ID</label>
                            <input type="text" wire:model="machineIdInput" 
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg font-mono text-sm focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                                placeholder="Enter client's Machine ID (e.g., 5d992dd1b9bfc0b59d2b0d5f6a9109f9)">
                            @error('machineIdInput') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- Business Type -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Business Type</label>
                            <select wire:model="selectedBusinessType" 
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                                @foreach($businessTypes as $key => $type)
                                <option value="{{ $key }}">{{ $type['code'] }} - {{ $type['name'] }} ({{ $type['name_mm'] }})</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- License Type -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">License Type</label>
                            <select wire:model.live="selectedLicenseType" 
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                                <option value="LIFETIME">Lifetime (တစ်သက်တာ)</option>
                                <option value="SUBSCRIPTION">Subscription (သက်တမ်းသတ်မှတ်)</option>
                            </select>
                        </div>

                        <!-- Subscription Days (conditional) -->
                        @if($selectedLicenseType === 'SUBSCRIPTION')
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Subscription Days</label>
                            <input type="number" wire:model="subscriptionDays" min="1"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                                placeholder="30">
                        </div>
                        @endif
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" 
                            class="inline-flex items-center px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg shadow-sm transition-colors duration-200">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                            </svg>
                            Generate License
                        </button>
                    </div>
                </form>

                <!-- Generated License Output -->
                @if($generatedLicense)
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Generated License Key</label>
                    <div class="relative">
                        <div class="bg-emerald-50 border-2 border-emerald-200 rounded-lg p-4">
                            <code class="font-mono text-lg text-emerald-800 break-all select-all block text-center tracking-wider">{{ $generatedLicense }}</code>
                        </div>
                        <button type="button" 
                            x-on:click="navigator.clipboard.writeText('{{ $generatedLicense }}'); copied = true; setTimeout(() => copied = false, 2000)"
                            class="absolute right-3 top-1/2 -translate-y-1/2 p-2 text-emerald-600 hover:bg-emerald-100 rounded-lg transition-colors duration-200">
                            <svg x-show="!copied" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                            </svg>
                            <svg x-show="copied" x-cloak class="h-5 w-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </button>
                    </div>
                    <p class="text-xs text-gray-500 mt-2 text-center">Click the copy button or select the key to copy</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Business Types Reference -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 bg-gray-50/50 border-b border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                    <div class="p-2 bg-cyan-100 rounded-lg">
                        <svg class="h-5 w-5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    Business Types Reference
                </h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                    @foreach($businessTypes as $key => $type)
                    <div class="bg-gray-50 rounded-xl p-4 border border-gray-100 hover:border-cyan-300 hover:shadow-md transition-all duration-200">
                        <div class="flex items-center justify-between mb-3">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-gradient-to-r from-violet-500 to-purple-600 text-white">{{ $type['code'] }}</span>
                            <span class="text-xs text-gray-400">{{ $key }}</span>
                        </div>
                        <h4 class="font-semibold text-gray-900">{{ $type['name'] }}</h4>
                        <p class="text-sm text-gray-500 myanmar-text">{{ $type['name_mm'] }}</p>
                        <div class="mt-3 pt-3 border-t border-gray-200">
                            <p class="text-xs text-gray-400 mb-2">Features:</p>
                            <div class="flex flex-wrap gap-1">
                                @foreach($type['features'] as $feature)
                                <span class="text-xs px-2 py-0.5 bg-white border border-gray-200 rounded-md text-gray-600">{{ $feature }}</span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
