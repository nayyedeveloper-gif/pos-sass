<div class="py-6">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900">System Settings</h1>
            <p class="mt-1 text-sm text-gray-500">SaaS platform configuration and management</p>
        </div>

        <!-- Flash Messages -->
        @if (session()->has('message'))
        <div class="mb-6 bg-green-50 border-l-4 border-green-500 rounded-r p-4">
            <div class="flex items-center">
                <svg class="h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <p class="ml-3 text-sm font-medium text-green-800">{{ session('message') }}</p>
            </div>
        </div>
        @endif

        <div class="space-y-6">
            <!-- License Status Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-indigo-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-900">License Status</h3>
                    </div>
                </div>
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="flex items-center">
                                @if($licenseStatus === 'active')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                        Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                        </svg>
                                        Inactive
                                    </span>
                                @endif
                            </div>
                            <p class="mt-2 text-sm text-gray-500">Manage licenses from the License Management page</p>
                        </div>
                        <a href="{{ route('admin.licenses.index') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors">
                            Manage Licenses
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Cloud Sync Settings -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-900">Cloud Sync</h3>
                    </div>
                </div>
                <div class="p-6">
                    <div class="space-y-6">
                        <!-- Enable Toggle -->
                        <div class="flex items-center justify-between">
                            <div>
                                <label class="text-sm font-medium text-gray-900">Enable Cloud Sync</label>
                                <p class="text-sm text-gray-500">Sync data with cloud server</p>
                            </div>
                            <button type="button" wire:click="$toggle('cloudSyncEnabled')"
                                class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 {{ $cloudSyncEnabled ? 'bg-primary-600' : 'bg-gray-200' }}">
                                <span class="sr-only">Enable cloud sync</span>
                                <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $cloudSyncEnabled ? 'translate-x-5' : 'translate-x-0' }}"></span>
                            </button>
                        </div>

                        @if($cloudSyncEnabled)
                        <div class="space-y-4 pt-4 border-t border-gray-200">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Cloud Server URL</label>
                                <input type="url" wire:model="cloudSyncUrl" placeholder="https://cloud.example.com/api"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">API Token</label>
                                <input type="password" wire:model="cloudSyncToken" placeholder="Enter API token"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Sync Interval (minutes)</label>
                                <select wire:model="cloudSyncInterval"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                                    <option value="1">Every 1 minute</option>
                                    <option value="5">Every 5 minutes</option>
                                    <option value="15">Every 15 minutes</option>
                                    <option value="30">Every 30 minutes</option>
                                    <option value="60">Every 1 hour</option>
                                </select>
                            </div>
                        </div>
                        @endif

                        <div class="pt-4">
                            <button type="button" wire:click="saveCloudSync"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Save Cloud Sync Settings
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- System Configuration -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-gray-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-900">System Configuration</h3>
                    </div>
                </div>
                <div class="p-6">
                    <div class="space-y-6">
                        <!-- Logo Upload Section -->
                        <div class="pb-6 border-b border-gray-200">
                            <label class="block text-sm font-medium text-gray-700 mb-3">Application Logo</label>
                            <div class="flex items-start space-x-6">
                                <!-- Current Logo Preview -->
                                <div class="flex-shrink-0">
                                    <div class="w-32 h-32 rounded-xl border-2 border-dashed border-gray-300 flex items-center justify-center bg-gray-50 overflow-hidden">
                                        @if($logo)
                                            <img src="{{ $logo->temporaryUrl() }}" alt="New Logo Preview" class="w-full h-full object-contain">
                                        @elseif($currentLogo)
                                            <img src="{{ asset('storage/' . $currentLogo) }}" alt="Current Logo" class="w-full h-full object-contain">
                                        @else
                                            <div class="text-center">
                                                <svg class="mx-auto h-10 w-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                <p class="mt-1 text-xs text-gray-500">No logo</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                
                                <!-- Upload Controls -->
                                <div class="flex-1">
                                    <div class="space-y-3">
                                        <div>
                                            <label for="logo-upload" class="cursor-pointer inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                                </svg>
                                                Choose Logo
                                            </label>
                                            <input id="logo-upload" type="file" wire:model="logo" accept="image/*" class="hidden">
                                        </div>
                                        
                                        <p class="text-xs text-gray-500">PNG, JPG, GIF up to 2MB. Recommended: 512x512px</p>
                                        
                                        @error('logo') 
                                            <p class="text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                        
                                        <div wire:loading wire:target="logo" class="text-sm text-primary-600">
                                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 inline" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            Uploading...
                                        </div>
                                        
                                        @if($logo)
                                        <div class="flex space-x-2">
                                            <button type="button" wire:click="uploadLogo" wire:loading.attr="disabled"
                                                class="inline-flex items-center px-3 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium rounded-lg transition-colors">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                Save Logo
                                            </button>
                                            <button type="button" wire:click="$set('logo', null)"
                                                class="inline-flex items-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-colors">
                                                Cancel
                                            </button>
                                        </div>
                                        @endif
                                        
                                        @if($currentLogo && !$logo)
                                        <button type="button" wire:click="removeLogo" wire:loading.attr="disabled"
                                            class="inline-flex items-center px-3 py-2 bg-red-50 hover:bg-red-100 text-red-600 text-sm font-medium rounded-lg transition-colors">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                            Remove Logo
                                        </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- App Name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Application Name</label>
                            <input type="text" wire:model="appName"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Timezone</label>
                                <input type="text" value="{{ $appTimezone }}" disabled
                                    class="w-full px-4 py-2 border border-gray-200 rounded-lg bg-gray-50 text-gray-500">
                                <p class="mt-1 text-xs text-gray-400">Set in .env file</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Locale</label>
                                <input type="text" value="{{ $appLocale }}" disabled
                                    class="w-full px-4 py-2 border border-gray-200 rounded-lg bg-gray-50 text-gray-500">
                                <p class="mt-1 text-xs text-gray-400">Set in .env file</p>
                            </div>
                        </div>

                        <div class="pt-4">
                            <button type="button" wire:click="saveSystem"
                                class="inline-flex items-center px-4 py-2 bg-gray-800 hover:bg-gray-900 text-white text-sm font-medium rounded-lg transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Save System Settings
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- System Actions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-900">System Actions</h3>
                    </div>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Clear Cache -->
                        <div class="p-4 border border-gray-200 rounded-lg">
                            <h4 class="font-medium text-gray-900">Clear Cache</h4>
                            <p class="text-sm text-gray-500 mt-1">Clear all application caches</p>
                            <button type="button" wire:click="clearCache" wire:loading.attr="disabled"
                                class="mt-3 inline-flex items-center px-3 py-2 bg-yellow-500 hover:bg-yellow-600 text-white text-sm font-medium rounded-lg transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                <span wire:loading.remove wire:target="clearCache">Clear Cache</span>
                                <span wire:loading wire:target="clearCache">Clearing...</span>
                            </button>
                        </div>

                        <!-- Maintenance Mode -->
                        <div class="p-4 border border-gray-200 rounded-lg">
                            <h4 class="font-medium text-gray-900">Maintenance Mode</h4>
                            <p class="text-sm text-gray-500 mt-1">
                                @if($maintenanceMode)
                                    Application is currently in maintenance mode
                                @else
                                    Take application offline for maintenance
                                @endif
                            </p>
                            <button type="button" wire:click="toggleMaintenance" wire:loading.attr="disabled"
                                class="mt-3 inline-flex items-center px-3 py-2 {{ $maintenanceMode ? 'bg-green-500 hover:bg-green-600' : 'bg-red-500 hover:bg-red-600' }} text-white text-sm font-medium rounded-lg transition-colors">
                                @if($maintenanceMode)
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Go Live
                                @else
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                                    </svg>
                                    Enable Maintenance
                                @endif
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- System Info -->
            <div class="bg-gray-50 rounded-xl border border-gray-200 p-6">
                <h4 class="text-sm font-medium text-gray-700 mb-4">System Information</h4>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                    <div>
                        <span class="text-gray-500">PHP Version</span>
                        <p class="font-medium text-gray-900">{{ PHP_VERSION }}</p>
                    </div>
                    <div>
                        <span class="text-gray-500">Laravel Version</span>
                        <p class="font-medium text-gray-900">{{ app()->version() }}</p>
                    </div>
                    <div>
                        <span class="text-gray-500">Environment</span>
                        <p class="font-medium text-gray-900">{{ config('app.env') }}</p>
                    </div>
                    <div>
                        <span class="text-gray-500">Debug Mode</span>
                        <p class="font-medium {{ $debugMode ? 'text-red-600' : 'text-green-600' }}">
                            {{ $debugMode ? 'Enabled' : 'Disabled' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
