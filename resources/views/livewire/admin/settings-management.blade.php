<div x-data="{ activeTab: 'general', isSaving: false, searchQuery: '', showAdvanced: false }" x-init="
    $watch('activeTab', value => {
        // Smooth scroll to top when changing tabs
        window.scrollTo({ top: 0, behavior: 'smooth' });
        // Reset search when switching tabs
        searchQuery = '';
    });
">
<form wire:submit.prevent="save">
    <div class="space-y-4 mb-6">
        @if (session()->has('message'))
        <div class="bg-green-50 border-l-4 border-green-500 rounded-r p-4 shadow-sm">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800 myanmar-text">{{ session('message') }}</p>
                </div>
                <div class="ml-auto pl-3">
                    <div class="-mx-1.5 -my-1.5">
                        <button type="button" class="inline-flex rounded-md p-1.5 text-green-500 hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <span class="sr-only">Dismiss</span>
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endif
        
        @if ($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 rounded-r p-4 shadow-sm">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">
                        အောက်ပါအချက်များတွင် အမှားများရှိနေပါသည်။
                    </h3>
                    <div class="mt-2 text-sm text-red-700">
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li class="myanmar-text">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center">
            <div class="flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-lg bg-primary-100 text-primary-600">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <h2 class="text-2xl font-bold text-gray-900 myanmar-text">စနစ် ဆက်တင်များ</h2>
                <p class="mt-1 text-sm text-gray-600 myanmar-text">လုပ်ငန်းနှင့် စနစ် ဆက်တင်များကို သတ်မှတ်ပါ။</p>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="mb-8">
        <div class="sm:hidden">
            <label for="tabs" class="sr-only">Select a tab</label>
            <select id="tabs" x-model="activeTab" class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md">
                <option value="general">General</option>
                <option value="signage">Digital Signage</option>
                <option value="system">System Settings</option>
                <option value="cloudsync">Cloud Sync</option>
            </select>
        </div>
        <div class="hidden sm:block">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                    <button type="button" @click="activeTab = 'general'" 
                        :class="activeTab === 'general' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="group flex items-center py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-150">
                        <svg :class="activeTab === 'general' ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500'" class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span class="myanmar-text">General</span>
                    </button>
                    <button type="button" @click="activeTab = 'signage'" 
                        :class="activeTab === 'signage' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="group flex items-center py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-150">
                        <svg :class="activeTab === 'signage' ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500'" class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                        </svg>
                        Digital Signage
                    </button>
                    <button type="button" @click="activeTab = 'system'" 
                        :class="activeTab === 'system' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="group flex items-center py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-150">
                        <svg :class="activeTab === 'system' ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500'" class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span class="myanmar-text">System Settings</span>
                    </button>
                    <button type="button" @click="activeTab = 'cloudsync'" 
                        :class="activeTab === 'cloudsync' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="group flex items-center py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-150">
                        <svg :class="activeTab === 'cloudsync' ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500'" class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>
                        <span>Cloud Sync</span>
                    </button>
                </nav>
            </div>
        </div>
    <!-- Advanced Search & Filters (Professional Feature) -->
    <div class="mb-6 bg-white rounded-lg shadow-sm border border-gray-200 p-4" x-show="activeTab === 'general'" x-transition>
        <div class="flex items-center justify-between mb-4">
            <h4 class="text-sm font-medium text-gray-900 myanmar-text">အဆင့်မြင့် ရှာဖွေရန်</h4>
            <button @click="showAdvanced = !showAdvanced" class="inline-flex items-center text-sm text-primary-600 hover:text-primary-800">
                <svg :class="showAdvanced ? 'rotate-180' : ''" class="w-4 h-4 mr-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
                <span x-text="showAdvanced ? 'ပိတ်မည်' : 'ဖွင့်မည်'"></span>
            </button>
        </div>
        
        <div x-show="showAdvanced" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 max-h-0" x-transition:enter-end="opacity-100 max-h-96" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 max-h-96" x-transition:leave-end="opacity-0 max-h-0" class="overflow-hidden">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1 myanmar-text">အစိတ်အပိုင်း ရှာဖွေရန်</label>
                    <input type="text" x-model="searchQuery" placeholder="အက်ပ်၊ လုပ်ငန်း၊ အခွန်..." class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1 myanmar-text">အမျိုးအစား</label>
                    <select class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        <option value="">အားလုံး</option>
                        <option value="app">အက်ပ်ဆက်တင်</option>
                        <option value="business">လုပ်ငန်းအချက်အလက်</option>
                        <option value="tax">အခွန်နှင့်ဝန်ဆောင်မှု</option>
                        <option value="receipt">ငွေလက်ခံ</option>
                        <option value="system">စနစ်ဆက်တင်</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button @click="searchQuery = ''" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        ရှင်းလင်းရန်
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Professional Progress Indicator -->
    <div class="mb-6" x-show="activeTab === 'general'" x-transition>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <div class="flex items-center justify-between mb-3">
                <h4 class="text-sm font-medium text-gray-900 myanmar-text">ဆက်တင်ခြင်း ပြီးစီးမှု</h4>
                <span class="text-xs text-gray-500 myanmar-text">လိုအပ်သည်များ: 5/7</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-primary-600 h-2 rounded-full transition-all duration-500 ease-out" style="width: 71%"></div>
            </div>
            <div class="mt-2 flex justify-between text-xs text-gray-600">
                <span myanmar-text>လိုအပ်သော ဆက်တင်များ ပြီးစီးပါပြီ</span>
                <span myanmar-text>71% ပြီးစီးပါပြီ</span>
            </div>
        </div>
    </div>

        <!-- General Tab -->
        <div x-show="activeTab === 'general'" class="space-y-6" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 transform translate-y-0" x-transition:leave-end="opacity-0 transform translate-y-2">
            <!-- App Settings Card -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200 hover:shadow-lg transition-all duration-300 hover:border-primary-300" :class="searchQuery && !('အက်ပ်ဆက်တင်အက်ပ်လုပ်ငန်းအခွန်ဝန်ဆောင်မှုငွေလက်ခံစနစ်ဆက်တင်'.includes(searchQuery)) ? 'opacity-50' : 'opacity-100'">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-primary-50 to-primary-100">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0 flex items-center justify-center h-8 w-8 rounded-lg bg-primary-100 text-primary-600">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg leading-6 font-semibold text-gray-900 flex items-center">
                                <span class="myanmar-text">အက်ပ်ဆက်တင်များ</span>
                                <svg class="ml-2 h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                            </h3>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 myanmar-text">ပြီးစီးပြီ</span>
                        </div>
                    </div>
                    <p class="mt-2 max-w-2xl text-sm text-gray-600 myanmar-text">
                        သင့်အက်ပ်နှင့်ပတ်သက်သော အခြေခံဆက်တင်များကို သတ်မှတ်ပါ။ ဤဆက်တင်များသည် စနစ်တစ်ခုလုံး၏ အလုပ်လုပ်ပုံကို သက်ရောက်မှုရှိပါသည်။
                    </p>
                </div>
                <div class="px-6 py-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4 myanmar-text">အက်ပ်ဆက်တင်များ</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- App Name -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1 myanmar-text">အက်ပ်အမည် *</label>
                        <input type="text" wire:model="app_name" class="input myanmar-text" placeholder="POS Pro">
                        @error('app_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Logo Upload -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1 myanmar-text">လိုဂို</label>
                        
                        @if($current_logo)
                            <div class="mb-3 flex items-center space-x-4">
                                <img src="{{ Storage::url($current_logo) }}" alt="Current Logo" class="h-20 w-20 object-contain border rounded">
                                <button type="button" wire:click="deleteLogo" wire:confirm="လိုဂိုကို ဖျက်မှာ သေချာပါသလား?" class="inline-flex items-center px-3 py-2 border border-red-300 shadow-sm text-sm leading-4 font-medium rounded-md text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    <span class="myanmar-text">ဖျက်မည်</span>
                                </button>
                            </div>
                        @endif
                        
                        <input type="file" wire:model="logo" accept="image/*" class="input">
                        @error('logo') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        <p class="text-xs text-gray-500 mt-1 myanmar-text">PNG, JPG သို့မဟုတ် GIF (အများဆုံး 2MB)</p>
                        
                        @if($logo)
                            <div class="mt-2">
                                <p class="text-sm text-gray-600 myanmar-text">အသစ်ရွေးချယ်ထားသော ပုံ:</p>
                                <img src="{{ $logo->temporaryUrl() }}" alt="Preview" class="mt-2 h-20 w-20 object-contain border rounded">
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Business Information Card -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200 hover:shadow-lg transition-all duration-300 hover:border-primary-300" :class="searchQuery && !('လုပ်ငန်းအချက်အလက်လိပ်စာဖုန်းနံပါတ်အီးမေးလ်'.includes(searchQuery)) ? 'opacity-50' : 'opacity-100'">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-blue-100">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0 flex items-center justify-center h-8 w-8 rounded-lg bg-blue-100 text-blue-600">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg leading-6 font-semibold text-gray-900 flex items-center">
                                <span class="myanmar-text">လုပ်ငန်း အချက်အလက်များ</span>
                                <svg class="ml-2 h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                            </h3>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 myanmar-text">ပြီးစီးပြီ</span>
                        </div>
                    </div>
                    <p class="mt-2 max-w-2xl text-sm text-gray-600 myanmar-text">
                        သင့်လုပ်ငန်း၏ အချက်အလက်များကို ထည့်သွင်းပါ။ ဤအချက်အလက်များသည် ငွေလက်ခံပိုင်းနှင့် အစီရင်ခံစာများတွင် အသုံးပြုပါမည်။
                    </p>
                </div>
                <div class="px-6 py-6">
                    <!-- Business Name (English) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Business Name (English) *</label>
                        <input type="text" wire:model="business_name" class="input" placeholder="My Business">
                        @error('business_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Business Name (Myanmar) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1 myanmar-text">လုပ်ငန်းအမည် (မြန်မာ) *</label>
                        <input type="text" wire:model="business_name_mm" class="input myanmar-text" placeholder="ကျွန်ုပ်၏လုပ်ငန်း">
                        @error('business_name_mm') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Business Address -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1 myanmar-text">လိပ်စာ</label>
                        <textarea wire:model="business_address" rows="2" class="input" placeholder="လုပ်ငန်း လိပ်စာ..."></textarea>
                        @error('business_address') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Business Phone -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1 myanmar-text">ဖုန်းနံပါတ်</label>
                        <input type="text" wire:model="business_phone" class="input" placeholder="09xxxxxxxxx">
                        @error('business_phone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Business Email -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1 myanmar-text">အီးမေးလ်</label>
                        <input type="email" wire:model="business_email" class="input" placeholder="info@tharchocafe.com">
                        @error('business_email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- Tax & Charges Card -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200 hover:shadow-lg transition-all duration-300 hover:border-primary-300" :class="searchQuery && !('အခွန်နှင့်ဝန်ဆောင်မှုကြေး'.includes(searchQuery)) ? 'opacity-50' : 'opacity-100'">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-green-50 to-green-100">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0 flex items-center justify-center h-8 w-8 rounded-lg bg-green-100 text-green-600">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg leading-6 font-semibold text-gray-900 flex items-center">
                                <span class="myanmar-text">အခွန်နှင့် ဝန်ဆောင်မှု ကြေး</span>
                                <svg class="ml-2 h-5 w-5 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                            </h3>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800 myanmar-text">စစ်ဆေးရန်လိုအပ်</span>
                        </div>
                    </div>
                    <p class="mt-2 max-w-2xl text-sm text-gray-600 myanmar-text">
                        အခွန်နှင့် ဝန်ဆောင်မှု ကြေးနှုန်းများကို သတ်မှတ်ပါ။ ဤတန်ဖိုးများသည် Cashier POS တွင် အလိုအလျောက် အသုံးပြုပါမည်။
                    </p>
                </div>
                <div class="px-6 py-6">
                    <!-- Default Tax Percentage -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1 myanmar-text">မူလ အခွန် ရာခိုင်နှုန်း *</label>
                        <div class="relative">
                            <input type="number" wire:model="default_tax_percentage" class="input pr-8" placeholder="0" min="0" max="100" step="0.01">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">%</span>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1 myanmar-text">Cashier POS တွင် အလိုအလျောက် ထည့်သွင်းမည်</p>
                        @error('default_tax_percentage') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Default Service Charge Percentage -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1 myanmar-text">မူလ ဝန်ဆောင်မှု ကြေး ရာခိုင်နှုန်း *</label>
                        <div class="relative">
                            <input type="number" wire:model="default_service_charge_percentage" class="input pr-8" placeholder="10" min="0" max="100" step="0.01">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">%</span>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1 myanmar-text">Cashier POS တွင် အလိုအလျောက် ထည့်သွင်းမည်</p>
                        @error('default_service_charge_percentage') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>

                <p class="mt-2 text-xs text-gray-500 myanmar-text">
                    ဤတန်ဖိုးများသည် အော်ဒါအသစ်များအတွက် မူလတန်ဖိုး ဖြစ်ပါသည်။ အော်ဒါတစ်ခုချင်းစီတွင် ပြောင်းလဲနိုင်ပါသည်။
                </p>
            </div>

            <!-- Receipt Settings Card -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200 hover:shadow-lg transition-all duration-300 hover:border-primary-300" :class="searchQuery && !('ငွေလက်ခံခေါင်းစီးအောက်ခြေလိုဂို'.includes(searchQuery)) ? 'opacity-50' : 'opacity-100'">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-purple-50 to-purple-100">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0 flex items-center justify-center h-8 w-8 rounded-lg bg-purple-100 text-purple-600">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg leading-6 font-semibold text-gray-900 flex items-center">
                                <span class="myanmar-text">ငွေလက်ခံ ဆက်တင်များ</span>
                                <svg class="ml-2 h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                            </h3>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 myanmar-text">ပြီးစီးပြီ</span>
                        </div>
                    </div>
                    <p class="mt-2 max-w-2xl text-sm text-gray-600 myanmar-text">
                        ငွေလက်ခံပိုင်းနှင့် ပုံစံအချက်အလက်များကို သတ်မှတ်ပါ။ ဤဆက်တင်များသည် ငွေပေးချေမှု ပြီးစီးသည့်အခါ ထုတ်လုပ်မည့် ငွေလက်ခံပိုင်းကို သက်ရောက်မှုရှိပါသည်။
                    </p>
                </div>
                <div class="px-6 py-6">
                    <!-- Receipt Header -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1 myanmar-text">ငွေလက်ခံ ခေါင်းစီး</label>
                        <textarea wire:model="receipt_header" rows="3" class="input myanmar-text" placeholder="ကြိုဆိုပါသည်..."></textarea>
                        <p class="mt-1 text-xs text-gray-500 myanmar-text">ငွေလက်ခံ အပေါ်ဆုံးတွင် ပြသမည့် စာသား</p>
                        @error('receipt_header') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Receipt Footer -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1 myanmar-text">ငွေလက်ခံ အောက်ခြေ</label>
                        <textarea wire:model="receipt_footer" rows="3" class="input myanmar-text" placeholder="ကျေးဇူးတင်ပါသည်..."></textarea>
                        <p class="mt-1 text-xs text-gray-500 myanmar-text">ငွေလက်ခံ အောက်ဆုံးတွင် ပြသမည့် စာသား</p>
                        @error('receipt_footer') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Show Logo on Receipt -->
                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="show_logo_on_receipt" class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-700 myanmar-text">ငွေလက်ခံတွင် လိုဂို ပြသရန်</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- System Settings Card -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200 hover:shadow-lg transition-all duration-300 hover:border-primary-300" :class="searchQuery && !('စနစ်ဆက်တင်ငွေကြေးသင်္ကေတ်အချိန်ဇုန်ရက်စွဲအချိန်'.includes(searchQuery)) ? 'opacity-50' : 'opacity-100'">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0 flex items-center justify-center h-8 w-8 rounded-lg bg-gray-100 text-gray-600">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            </div>
                            <h3 class="text-lg leading-6 font-semibold text-gray-900 flex items-center">
                                <span class="myanmar-text">စနစ် ဆက်တင်များ</span>
                                <svg class="ml-2 h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                            </h3>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 myanmar-text">ပြီးစီးပြီ</span>
                        </div>
                    </div>
                    <p class="mt-2 max-w-2xl text-sm text-gray-600 myanmar-text">
                        စနစ်၏ အခြေခံ လုပ်ဆောင်ချက်များနှင့် ပုံစံအချက်အလက်များကို သတ်မှတ်ပါ။
                    </p>
                </div>
                <div class="px-6 py-6">
                    <!-- Currency Symbol -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1 myanmar-text">ငွေကြေး သင်္ကေတ *</label>
                        <input type="text" wire:model="currency_symbol" class="input" placeholder="Ks">
                        @error('currency_symbol') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Timezone -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1 myanmar-text">အချိန်ဇုန် *</label>
                        <select wire:model="timezone" class="input">
                            <option value="Asia/Yangon">Asia/Yangon (Myanmar)</option>
                            <option value="Asia/Bangkok">Asia/Bangkok (Thailand)</option>
                            <option value="Asia/Singapore">Asia/Singapore</option>
                            <option value="UTC">UTC</option>
                        </select>
                        @error('timezone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Date Format -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1 myanmar-text">ရက်စွဲ ပုံစံ *</label>
                        <select wire:model="date_format" class="input">
                            <option value="Y-m-d">YYYY-MM-DD (2024-01-15)</option>
                            <option value="d/m/Y">DD/MM/YYYY (15/01/2024)</option>
                            <option value="m/d/Y">MM/DD/YYYY (01/15/2024)</option>
                            <option value="d-M-Y">DD-MMM-YYYY (15-Jan-2024)</option>
                        </select>
                        @error('date_format') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Time Format -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1 myanmar-text">အချိန် ပုံစံ *</label>
                        <select wire:model="time_format" class="input">
                            <option value="H:i">24-hour (14:30)</option>
                            <option value="h:i A">12-hour (02:30 PM)</option>
                        </select>
                        @error('time_format') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- Receipt Preview -->
            <div class="bg-white rounded-lg shadow-sm p-4 md:p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4 myanmar-text">ငွေလက်ခံ အစမ်းကြည့်ရှုမှု</h3>
                
                <div class="bg-gray-50 p-6 rounded border-2 border-dashed border-gray-300 font-mono text-sm">
                    <div class="text-center mb-4">
                        <!-- Logo Preview -->
                        @if($show_logo_on_receipt && ($current_logo || $logo))
                            <div class="mb-3 flex justify-center">
                                @if($logo)
                                    <img src="{{ $logo->temporaryUrl() }}" alt="Logo Preview" class="h-16 w-16 object-contain">
                                @elseif($current_logo)
                                    <img src="{{ Storage::url($current_logo) }}" alt="Logo" class="h-16 w-16 object-contain">
                                @endif
                            </div>
                        @endif
                        
                        @if($receipt_header)
                        <div class="mb-2 myanmar-text">{{ $receipt_header }}</div>
                        @endif
                        <div class="font-bold text-lg myanmar-text">{{ $business_name_mm }}</div>
                        <div>{{ $business_name }}</div>
                        @if($business_address)
                        <div class="text-xs mt-1">{{ $business_address }}</div>
                        @endif
                        @if($business_phone)
                        <div class="text-xs">Tel: {{ $business_phone }}</div>
                        @endif
                    </div>
                    
                    <div class="border-t border-b border-gray-400 py-2 my-2">
                        <div class="flex justify-between">
                            <span>Order #:</span>
                            <span>20240001</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Date:</span>
                            <span>{{ now()->format($date_format) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Time:</span>
                            <span>{{ now()->format($time_format) }}</span>
                        </div>
                    </div>
                    
                    <div class="my-2">
                        <div class="flex justify-between">
                            <span>Sample Item 1</span>
                            <span>5,000 {{ $currency_symbol }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Sample Item 2</span>
                            <span>3,000 {{ $currency_symbol }}</span>
                        </div>
                    </div>
                    
                    <div class="border-t border-gray-400 pt-2 mt-2">
                        <div class="flex justify-between">
                            <span>Subtotal:</span>
                            <span>8,000 {{ $currency_symbol }}</span>
                        </div>
                        @if($default_tax_percentage > 0)
                        <div class="flex justify-between">
                            <span>Tax ({{ $default_tax_percentage }}%):</span>
                            <span>{{ number_format(8000 * $default_tax_percentage / 100) }} {{ $currency_symbol }}</span>
                        </div>
                        @endif
                        @if($default_service_charge_percentage > 0)
                        <div class="flex justify-between">
                            <span>Service Charge ({{ $default_service_charge_percentage }}%):</span>
                            <span>{{ number_format(8000 * $default_service_charge_percentage / 100) }} {{ $currency_symbol }}</span>
                        </div>
                        @endif
                        <div class="flex justify-between font-bold text-base mt-1">
                            <span>TOTAL:</span>
                            <span>{{ number_format(8000 + (8000 * $default_tax_percentage / 100) + (8000 * $default_service_charge_percentage / 100)) }} {{ $currency_symbol }}</span>
                        </div>
                    </div>
                    
                    @if($receipt_footer)
                    <div class="text-center mt-4 text-xs myanmar-text">
                        {{ $receipt_footer }}
                    </div>
                    @endif
        <!-- Professional Quick Actions -->
        <div class="mt-8 pt-6 border-t border-gray-200 bg-gradient-to-r from-gray-50 to-white rounded-lg p-6" x-show="activeTab === 'general'" x-transition>
            <div class="flex items-center justify-between mb-4">
                <h4 class="text-lg font-semibold text-gray-900 myanmar-text">အမြန် လုပ်ဆောင်ချက်များ</h4>
                <span class="text-sm text-gray-500 myanmar-text">အသုံးများသော လုပ်ဆောင်ချက်များ</span>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <button class="flex flex-col items-center p-4 bg-white rounded-lg border border-gray-200 hover:border-primary-300 hover:shadow-md transition-all duration-200 group" onclick="document.querySelector('[name=app_name]').focus()">
                    <div class="flex-shrink-0 flex items-center justify-center h-10 w-10 rounded-lg bg-primary-100 text-primary-600 group-hover:bg-primary-200 transition-colors duration-200">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <span class="mt-2 text-sm font-medium text-gray-900 myanmar-text">အက်ပ်အမည်</span>
                    <span class="text-xs text-gray-500 myanmar-text">ပြုပြင်ရန်</span>
                </button>
                
                <button class="flex flex-col items-center p-4 bg-white rounded-lg border border-gray-200 hover:border-blue-300 hover:shadow-md transition-all duration-200 group" onclick="document.querySelector('[name=business_name]').focus()">
                    <div class="flex-shrink-0 flex items-center justify-center h-10 w-10 rounded-lg bg-blue-100 text-blue-600 group-hover:bg-blue-200 transition-colors duration-200">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <span class="mt-2 text-sm font-medium text-gray-900 myanmar-text">လုပ်ငန်းအမည်</span>
                    <span class="text-xs text-gray-500 myanmar-text">ပြုပြင်ရန်</span>
                </button>
                
                <a href="{{ route('display.signage') }}" target="_blank" class="flex flex-col items-center p-4 bg-white rounded-lg border border-gray-200 hover:border-green-300 hover:shadow-md transition-all duration-200 group">
                    <div class="flex-shrink-0 flex items-center justify-center h-10 w-10 rounded-lg bg-green-100 text-green-600 group-hover:bg-green-200 transition-colors duration-200">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <span class="mt-2 text-sm font-medium text-gray-900 myanmar-text">Display</span>
                    <span class="text-xs text-gray-500 myanmar-text">ဖွင့်ရန်</span>
                </a>
                
                <a href="{{ route('admin.reports.index') }}" class="flex flex-col items-center p-4 bg-white rounded-lg border border-gray-200 hover:border-purple-300 hover:shadow-md transition-all duration-200 group">
                    <div class="flex-shrink-0 flex items-center justify-center h-10 w-10 rounded-lg bg-purple-100 text-purple-600 group-hover:bg-purple-200 transition-colors duration-200">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <span class="mt-2 text-sm font-medium text-gray-900 myanmar-text">အစီရင်ခံစာ</span>
                    <span class="text-xs text-gray-500 myanmar-text">ကြည့်ရန်</span>
                </a>
            </div>
        </div>
        </div>
        <div x-show="activeTab === 'signage'" class="space-y-6" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 transform translate-y-0" x-transition:leave-end="opacity-0 transform translate-y-2">
            <!-- Signage Control -->
            <div class="bg-white rounded-lg shadow-sm p-4 md:p-6">
                <div class="flex items-center space-x-2 mb-4">
                    <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900">Digital Signage Control</h3>
                </div>
                
                <div class="space-y-4">
                    <!-- Enable/Disable -->
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div>
                            <label class="text-sm font-medium text-gray-900 myanmar-text">Digital Signage ဖွင့်ရန်</label>
                            <p class="text-xs text-gray-500 myanmar-text">Digital Signage Display ကို ဖွင့်/ပိတ် လုပ်ပါ</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" wire:model="signage_enabled" class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-600"></div>
                        </label>
                    </div>

                    <!-- Promotional Message -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1 myanmar-text">Promotional Message</label>
                        <textarea wire:model="promotional_message" rows="3" class="input" placeholder="🎉 Welcome! Special offers available today! 🎉"></textarea>
                        <p class="mt-1 text-xs text-gray-500 myanmar-text">ဤစာသားသည် Digital Signage Display ထိပ်တွင် scroll လုပ်ပြီး ပြသမည်။</p>
                        @error('promotional_message') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- Display Settings -->
            <div class="bg-white rounded-lg shadow-sm p-4 md:p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4 myanmar-text">Display ဆက်တင်များ</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Rotation Speed -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1 myanmar-text">Category Rotation Speed (seconds)</label>
                        <input type="number" wire:model="signage_rotation_speed" class="input" min="5" max="60" step="5">
                        <p class="mt-1 text-xs text-gray-500 myanmar-text">Category များ အလိုအလျောက် ပြောင်းမည့် အချိန် (စက္ကန့်)</p>
                        @error('signage_rotation_speed') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Auto Refresh -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1 myanmar-text">Auto Refresh (minutes)</label>
                        <input type="number" wire:model="signage_auto_refresh" class="input" min="1" max="60" step="1">
                        <p class="mt-1 text-xs text-gray-500 myanmar-text">စျေးနှုန်းများ အလိုအလျောက် update လုပ်မည့် အချိန် (မိနစ်)</p>
                        @error('signage_auto_refresh') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Theme -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1 myanmar-text">Theme</label>
                        <select wire:model="signage_theme" class="input">
                            <option value="dark">Dark (Recommended for TV)</option>
                            <option value="light">Light</option>
                        </select>
                        @error('signage_theme') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- Content Settings -->
            <div class="bg-white rounded-lg shadow-sm p-4 md:p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4 myanmar-text">Content ဆက်တင်များ</h3>
                
                <div class="space-y-3">
                    <!-- Show Prices -->
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div>
                            <label class="text-sm font-medium text-gray-900 myanmar-text">စျေးနှုန်းများ ပြသရန်</label>
                            <p class="text-xs text-gray-500 myanmar-text">Item စျေးနှုန်းများကို ပြသမည်</p>
                        </div>
                        <input type="checkbox" wire:model="signage_show_prices" class="w-4 h-4 text-primary-600 bg-gray-100 border-gray-300 rounded focus:ring-primary-500">
                    </div>

                    <!-- Show Descriptions -->
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div>
                            <label class="text-sm font-medium text-gray-900 myanmar-text">အကြောင်းအရာ ပြသရန်</label>
                            <p class="text-xs text-gray-500 myanmar-text">Item ၏ အကြောင်းအရာကို ပြသမည်</p>
                        </div>
                        <input type="checkbox" wire:model="signage_show_descriptions" class="w-4 h-4 text-primary-600 bg-gray-100 border-gray-300 rounded focus:ring-primary-500">
                    </div>

                    <!-- Show Availability -->
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div>
                            <label class="text-sm font-medium text-gray-900 myanmar-text">ရရှိနိုင်မှု Status ပြသရန်</label>
                            <p class="text-xs text-gray-500 myanmar-text">Available/Sold Out badge များကို ပြသမည်</p>
                        </div>
                        <input type="checkbox" wire:model="signage_show_availability" class="w-4 h-4 text-primary-600 bg-gray-100 border-gray-300 rounded focus:ring-primary-500">
                    </div>

                    <!-- Show Media/Ads -->
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div>
                            <label class="text-sm font-medium text-gray-900 myanmar-text">Videos/Ads ပြသရန်</label>
                            <p class="text-xs text-gray-500 myanmar-text">Menu items များကြားတွင် videos နဲ့ promotional images များ ပြသမည်</p>
                        </div>
                        <input type="checkbox" wire:model="signage_show_media" class="w-4 h-4 text-primary-600 bg-gray-100 border-gray-300 rounded focus:ring-primary-500">
                    </div>
                </div>
            </div>

            <!-- Quick Access -->
            <div class="bg-gradient-to-r from-primary-50 to-primary-100 rounded-lg shadow-sm p-4 md:p-6 border border-primary-200">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <div class="flex items-center space-x-2 mb-1">
                            <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900">Quick Access</h3>
                        </div>
                        <p class="text-sm text-gray-600 myanmar-text">Digital Signage Display ကို ဖွင့်ရန်</p>
                    </div>
                    <a href="{{ route('display.signage') }}" target="_blank" class="btn btn-primary">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                        </svg>
                        Open Display
                    </a>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3">
                    <div class="p-3 bg-white rounded border border-primary-200">
                        <p class="text-xs text-gray-500 mb-1 myanmar-text">Display URL:</p>
                        <p class="text-xs text-gray-600 font-mono break-all">{{ url('/display/signage') }}</p>
                        <button onclick="navigator.clipboard.writeText('{{ url('/display/signage') }}')" class="mt-2 text-xs text-primary-600 hover:text-primary-800 flex items-center space-x-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path>
                            </svg>
                            <span>Copy URL</span>
                        </button>
                    </div>
                    <div class="p-3 bg-white rounded border border-primary-200">
                        <a href="{{ route('admin.signage-media.index') }}" class="flex items-center text-sm text-primary-600 hover:text-primary-800">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                            </svg>
                            <span class="myanmar-text">Manage Media</span>
                        </a>
                    </div>
<div class="p-3 bg-white rounded border border-primary-200 text-center">
                        <p class="text-xs text-gray-500 mb-2 myanmar-text">QR Code:</p>
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data={{ urlencode(url('/display/signage')) }}" alt="QR Code" class="mx-auto">
                        <p class="text-xs text-gray-500 mt-1 myanmar-text">Scan to open</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- System Settings Tab -->
        <div x-show="activeTab === 'system'" class="space-y-6" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 transform translate-y-0" x-transition:leave-end="opacity-0 transform translate-y-2">
            <!-- Professional System Overview -->
            <div class="bg-gradient-to-r from-indigo-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-bold mb-2">System Control Center</h3>
                        <p class="text-indigo-100">Manage your system configuration and monitoring</p>
                    </div>
                    <div class="flex-shrink-0">
                        <svg class="h-12 w-12 text-indigo-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Quick Actions Dashboard -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 hover:shadow-md transition-shadow">
                    <div class="flex items-center space-x-3">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-sm font-medium text-gray-900">Print Management</h4>
                            <p class="text-xs text-gray-500">Configure auto-print settings</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 hover:shadow-md transition-shadow">
                    <div class="flex items-center space-x-3">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-sm font-medium text-gray-900">Card System</h4>
                            <p class="text-xs text-gray-500">Manage payment cards</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 hover:shadow-md transition-shadow">
                    <div class="flex items-center space-x-3">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-sm font-medium text-gray-900">System Info</h4>
                            <p class="text-xs text-gray-500">View system details</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Auto Print Settings -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200 hover:shadow-lg transition-all duration-300">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-blue-100">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0 flex items-center justify-center h-8 w-8 rounded-lg bg-blue-100 text-blue-600">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg leading-6 font-semibold text-gray-900 flex items-center">
                                <span class="myanmar-text">Auto Print Settings</span>
                                <svg class="ml-2 h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                            </h3>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 myanmar-text">Active</span>
                        </div>
                    </div>
                    <p class="mt-2 max-w-2xl text-sm text-gray-600 myanmar-text">
                        Configure automatic printing for different departments when orders are created.
                    </p>
                </div>
                <div class="px-6 py-6">
                    <!-- Kitchen Auto Print -->
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div>
                            <label class="text-sm font-medium text-gray-900 myanmar-text">မီးဖိုချောင်သို့ အလိုအလျောက် Print လုပ်ရန်</label>
                            <p class="text-xs text-gray-500 myanmar-text">အော်ဒါ အသစ်တစ်ခု ဖန်တီးသည့်အခါ မီးဖိုချောင်သို့ အလိုအလျောက် print လုပ်မည်</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" wire:model="auto_print_kitchen" class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-600"></div>
                        </label>
                    </div>

                    <!-- Bar Auto Print -->
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div>
                            <label class="text-sm font-medium text-gray-900 myanmar-text">သောက်စရာသို့ အလိုအလျောက် Print လုပ်ရန်</label>
                            <p class="text-xs text-gray-500 myanmar-text">အော်ဒါ အသစ်တစ်ခု ဖန်တီးသည့်အခါ သောက်စရာသို့ အလိုအလျောက် print လုပ်မည်</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" wire:model="auto_print_Bar" class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-600"></div>
                        </label>
                    </div>

                    <!-- Receipt Auto Print -->
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div>
                            <label class="text-sm font-medium text-gray-900 myanmar-text">ငွေလက်ခံဖြတ်ပိုင်း အလိုအလျောက် Print လုပ်ရန်</label>
                            <p class="text-xs text-gray-500 myanmar-text">ငွေပေးချေမှု ပြီးစီးသည့်အခါ ငွေလက်ခံဖြတ်ပိုင်း အလိုအလျောက် print လုပ်မည်</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" wire:model="auto_print_receipt" class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-600"></div>
                        </label>
                    </div>
                </div>

                <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                    <div class="flex items-start space-x-2">
                        <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div class="flex-1">
                            <p class="text-sm text-blue-800 myanmar-text">
                                <strong>မှတ်ချက်:</strong> Printer များကို Printer Management စာမျက်နှာတွင် configure လုပ်ရန် လိုအပ်ပါသည်။ 
                                Auto-print ကို ပိတ်ထားပါက manual print လုပ်ရန် လိုအပ်ပါမည်။
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Food Court Card System -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200 hover:shadow-lg transition-all duration-300">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-green-50 to-green-100">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0 flex items-center justify-center h-8 w-8 rounded-lg bg-green-100 text-green-600">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg leading-6 font-semibold text-gray-900 flex items-center">
                                <span class="myanmar-text">Payment Card System</span>
                                <svg class="ml-2 h-5 w-5 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                            </h3>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800 myanmar-text">Configure</span>
                        </div>
                    </div>
                    <p class="mt-2 max-w-2xl text-sm text-gray-600 myanmar-text">
                        Enable and configure prepaid card system for customer payments and loyalty programs.
                    </p>
                </div>
                <div class="px-6 py-6">
                    <!-- Enable Card System -->
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border-2 border-primary-200">
                        <div>
                            <label class="text-sm font-medium text-gray-900 myanmar-text">Card System ကို အသုံးပြုမည်</label>
                            <p class="text-xs text-gray-500 myanmar-text">Prepaid card system ကို ဖွင့်/ပိတ် လုပ်နိုင်ပါသည်</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" wire:model="card_system_enabled" class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-600"></div>
                        </label>
                    </div>

                    <div x-show="$wire.card_system_enabled" class="space-y-4">
                        <!-- Bonus Promotion -->
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div class="flex-1">
                                <label class="text-sm font-medium text-gray-900 myanmar-text">Bonus Promotion ကို အသုံးပြုမည်</label>
                                <p class="text-xs text-gray-500 myanmar-text">Card load လုပ်သည့်အခါ bonus ပေးမည်</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" wire:model="card_bonus_enabled" class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-600"></div>
                            </label>
                        </div>

                        <div x-show="$wire.card_bonus_enabled" class="pl-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1 myanmar-text">Bonus ရာခိုင်နှုန်း (%)</label>
                            <div class="relative">
                                <input type="number" wire:model="card_bonus_percentage" class="input pr-8" placeholder="10" min="0" max="100" step="0.1">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">%</span>
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 mt-1 myanmar-text">
                                ဥပမာ: 10% ဆိုလျှင် 10,000 Ks load လုပ်သည့်အခါ 11,000 Ks balance ရရှိမည်
                            </p>
                        </div>

                        <!-- Card Expiry -->
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div class="flex-1">
                                <label class="text-sm font-medium text-gray-900 myanmar-text">Card သက်တမ်း သတ်မှတ်မည်</label>
                                <p class="text-xs text-gray-500 myanmar-text">Card များကို သက်တမ်း သတ်မှတ်မည်</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" wire:model="card_expiry_enabled" class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-600"></div>
                            </label>
                        </div>

                        <div x-show="$wire.card_expiry_enabled" class="pl-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1 myanmar-text">သက်တမ်း (လ)</label>
                            <input type="number" wire:model="card_expiry_months" class="input" placeholder="12" min="1" max="60">
                            <p class="text-xs text-gray-500 mt-1 myanmar-text">
                                Card ထုတ်ပေးသည့်နေ့မှ စတင်၍ သက်တမ်း သတ်မှတ်မည်
                            </p>
                        </div>
                    </div>
                </div>

                <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <div class="flex items-start space-x-2">
                        <svg class="w-5 h-5 text-yellow-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        <div class="flex-1">
                            <p class="text-sm text-yellow-800 myanmar-text">
                                <strong>သတိပြုရန်:</strong> Card System ကို ပိတ်ထားပါက Cashier POS တွင် card payment option မပေါ်ပါ။ 
                                Card Management သည် Admin Menu တွင် ရှိပါသည်။
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        
            <!-- System Information -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200 hover:shadow-lg transition-all duration-300">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-purple-50 to-purple-100">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0 flex items-center justify-center h-8 w-8 rounded-lg bg-purple-100 text-purple-600">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg leading-6 font-semibold text-gray-900 flex items-center">
                                <span class="myanmar-text">System Information</span>
                                <svg class="ml-2 h-5 w-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                </svg>
                            </h3>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 myanmar-text">Live</span>
                        </div>
                    </div>
                    <p class="mt-2 max-w-2xl text-sm text-gray-600 myanmar-text">
                        View detailed system information, version details, and environment configuration.
                    </p>
                </div>
                <div class="px-6 py-6">
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <dt class="font-medium text-gray-700">Developer:</dt>
                            <dd class="text-gray-600 mt-1">Nay Ye Maung</dd>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-700">Version:</dt>
                            <dd class="text-gray-600 mt-1">2.0</dd>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-700">Laravel:</dt>
                            <dd class="text-gray-600 mt-1">{{ app()->version() }}</dd>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-700">PHP:</dt>
                            <dd class="text-gray-600 mt-1">{{ PHP_VERSION }}</dd>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-700">Environment:</dt>
                            <dd class="text-gray-600 mt-1">{{ config('app.env') }}</dd>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-700">Debug Mode:</dt>
                            <dd class="text-gray-600 mt-1">{{ config('app.debug') ? 'Enabled' : 'Disabled' }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- System Monitoring -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200 hover:shadow-lg transition-all duration-300">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-red-50 to-orange-100">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0 flex items-center justify-center h-8 w-8 rounded-lg bg-red-100 text-red-600">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg leading-6 font-semibold text-gray-900 flex items-center">
                                <span class="myanmar-text">System Monitoring</span>
                                <svg class="ml-2 h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                            </h3>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 myanmar-text">Critical</span>
                        </div>
                    </div>
                    <p class="mt-2 max-w-2xl text-sm text-gray-600 myanmar-text">
                        Monitor system health, view error logs, and track system performance metrics.
                    </p>
                </div>
                <div class="px-6 py-6">
                    <!-- Error Logs Section -->
                    <div class="mt-6">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="text-sm font-medium text-gray-900">Recent Error Logs</h4>
                            <span class="text-xs text-gray-500">Last 10 entries</span>
                        </div>
                </div>
            </div>

        </div>

        <!-- Cloud Sync Tab -->
        <div x-show="activeTab === 'cloudsync'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="space-y-6">
            
            <!-- Cloud Sync Status -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-cyan-100">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0 flex items-center justify-center h-8 w-8 rounded-lg bg-blue-100 text-blue-600">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg leading-6 font-semibold text-gray-900">
                                Cloud Sync Configuration
                            </h3>
                        </div>
                        <div class="flex items-center space-x-2">
                            @if($cloud_sync_enabled)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <span class="w-2 h-2 bg-green-500 rounded-full mr-1.5 animate-pulse"></span>
                                    Enabled
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    Disabled
                                </span>
                            @endif
                        </div>
                    </div>
                    <p class="mt-2 max-w-2xl text-sm text-gray-600">
                        Sync data between local device and cloud server automatically.
                    </p>
                </div>
                <div class="px-6 py-6 space-y-6">
                    <!-- Enable Cloud Sync -->
                    <div class="flex items-center justify-between">
                        <div>
                            <label class="text-sm font-medium text-gray-900">Enable Cloud Sync</label>
                            <p class="text-xs text-gray-500 mt-1">Automatically sync data with cloud server</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" wire:model="cloud_sync_enabled" class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>

                    <!-- Cloud Server URL -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Cloud Server URL</label>
                        <input type="url" wire:model="cloud_sync_url" 
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="https://your-cloud-server.com">
                        <p class="text-xs text-gray-500 mt-1">The URL of your cloud POS server</p>
                    </div>

                    <!-- API Token -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">API Token</label>
                        <input type="password" wire:model="cloud_sync_token" 
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent font-mono"
                            placeholder="Enter your API token">
                        <p class="text-xs text-gray-500 mt-1">Authentication token for cloud server</p>
                    </div>

                    <!-- Sync Interval -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Auto Sync Interval (minutes)</label>
                        <select wire:model="cloud_sync_interval" 
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="1">Every 1 minute</option>
                            <option value="5">Every 5 minutes</option>
                            <option value="10">Every 10 minutes</option>
                            <option value="15">Every 15 minutes</option>
                            <option value="30">Every 30 minutes</option>
                            <option value="60">Every 1 hour</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Sync Status Info -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Sync Information</h3>
                </div>
                <div class="px-6 py-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-gray-50 rounded-lg p-4 text-center">
                            <div class="text-2xl font-bold text-blue-600" x-data x-text="navigator.onLine ? 'Online' : 'Offline'"></div>
                            <div class="text-xs text-gray-500 mt-1">Connection Status</div>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4 text-center">
                            <div class="text-2xl font-bold text-gray-700" x-data x-text="localStorage.getItem('last_cloud_sync') ? new Date(localStorage.getItem('last_cloud_sync')).toLocaleString() : 'Never'"></div>
                            <div class="text-xs text-gray-500 mt-1">Last Sync</div>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4 text-center">
                            <div class="text-2xl font-bold text-orange-600" x-data x-text="JSON.parse(localStorage.getItem('pending_sync_changes') || '[]').length"></div>
                            <div class="text-xs text-gray-500 mt-1">Pending Changes</div>
                        </div>
                    </div>
                    
                    <!-- Manual Sync Button -->
                    <div class="mt-6 flex justify-center">
                        <button type="button" 
                            x-data
                            @click="if(window.CloudSync) { window.CloudSync.syncNow(); $el.textContent = 'Syncing...'; setTimeout(() => $el.textContent = 'Sync Now', 3000); }"
                            class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-sm transition-colors duration-200">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Sync Now
                        </button>
                    </div>
                </div>
            </div>

        </div>

        <!-- Save Button (Fixed at bottom for all tabs) -->
        <div class="mt-6 flex justify-end">
            <button type="submit" class="btn btn-primary">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                </svg>
                <span class="myanmar-text">သိမ်းဆည်းမည်</span>
            </button>
        </div>
    </form>

</div>
