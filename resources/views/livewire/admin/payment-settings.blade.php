<div class="py-6 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Flash Message -->
        @if (session()->has('message'))
        <div class="mb-6 bg-emerald-50 border border-emerald-100 rounded-xl p-4 flex items-center shadow-sm"
             x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
            <svg class="h-5 w-5 text-emerald-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            <p class="text-sm font-medium text-emerald-800">{{ session('message') }}</p>
        </div>
        @endif

        <!-- Header -->
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 myanmar-text">ငွေပေးချေမှု ဆက်တင်များ</h1>
                <p class="mt-1 text-sm text-gray-500">Configure mobile payment methods (KBZPay, Wave Pay, CB Pay, AYA Pay)</p>
            </div>
        </div>

        <!-- Payment Methods Stats -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <!-- Total Methods -->
            <div class="bg-gradient-to-br from-gray-700 to-gray-900 rounded-2xl shadow-lg p-5 text-white">
                <div class="flex items-center justify-between mb-3">
                    <div class="p-2 bg-white/20 rounded-xl">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                </div>
                <p class="text-sm text-white/80 myanmar-text">ငွေပေးချေနည်းများ</p>
                <p class="text-2xl font-bold mt-1">{{ ($kbzpay_enabled ? 1 : 0) + ($wavepay_enabled ? 1 : 0) + ($cbpay_enabled ? 1 : 0) + ($ayapay_enabled ? 1 : 0) + 1 }}</p>
            </div>

            <!-- Active Mobile Pay -->
            <div class="bg-gradient-to-br from-emerald-500 to-green-600 rounded-2xl shadow-lg p-5 text-white">
                <div class="flex items-center justify-between mb-3">
                    <div class="p-2 bg-white/20 rounded-xl">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
                <p class="text-sm text-white/80 myanmar-text">ဖွင့်ထားသော</p>
                <p class="text-2xl font-bold mt-1">{{ ($kbzpay_enabled ? 1 : 0) + ($wavepay_enabled ? 1 : 0) + ($cbpay_enabled ? 1 : 0) + ($ayapay_enabled ? 1 : 0) }}</p>
            </div>

            <!-- KBZPay Status -->
            <div class="bg-gradient-to-br from-cyan-500 to-blue-600 rounded-2xl shadow-lg p-5 text-white">
                <div class="flex items-center justify-between mb-3">
                    <div class="p-2 bg-white/20 rounded-xl">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                    </div>
                    @if($kbzpay_enabled)
                    <span class="px-2 py-0.5 bg-white/20 rounded-full text-xs">Active</span>
                    @endif
                </div>
                <p class="text-sm text-white/80">KBZPay</p>
                <p class="text-lg font-bold mt-1 myanmar-text">{{ $kbzpay_enabled ? 'ဖွင့်ထား' : 'ပိတ်ထား' }}</p>
            </div>

            <!-- API Status -->
            <div class="bg-gradient-to-br from-violet-500 to-purple-600 rounded-2xl shadow-lg p-5 text-white">
                <div class="flex items-center justify-between mb-3">
                    <div class="p-2 bg-white/20 rounded-xl">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path></svg>
                    </div>
                </div>
                <p class="text-sm text-white/80">API Status</p>
                <p class="text-lg font-bold mt-1">{{ $kbzpay_app_id ? 'Configured' : 'Static QR' }}</p>
            </div>
        </div>

        <!-- Payment Provider Cards -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- KBZPay Settings -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 bg-gray-50/50 border-b border-gray-100 flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="p-2 bg-emerald-100 rounded-xl mr-3">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">KBZPay</h3>
                            <p class="text-sm text-gray-500 myanmar-text">ကေဘီဇက်ပေး</p>
                        </div>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" wire:model.live="kbzpay_enabled" class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border after:border-gray-300 after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500"></div>
                    </label>
                </div>
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1 myanmar-text">ဖုန်းနံပါတ်</label>
                            <input type="text" wire:model="kbzpay_phone" placeholder="09xxxxxxxxx" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1 myanmar-text">အကောင့်အမည်</label>
                            <input type="text" wire:model="kbzpay_account_name" placeholder="Account Name" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        </div>
                    </div>
                    
                    <div class="pt-4 border-t border-gray-100">
                        <p class="text-xs text-gray-500 mb-3 myanmar-text">API Credentials (Optional)</p>
                        <div class="space-y-3">
                            <input type="text" wire:model="kbzpay_app_id" placeholder="App ID" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 font-mono text-sm">
                            <input type="password" wire:model="kbzpay_app_key" placeholder="App Key (Secret)" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 font-mono text-sm">
                            <input type="text" wire:model="kbzpay_merchant_code" placeholder="Merchant Code" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 font-mono text-sm">
                        </div>
                    </div>
                    
                    <button wire:click="saveKBZPay" class="w-full px-4 py-2.5 bg-gray-900 hover:bg-gray-800 text-white font-medium rounded-xl transition-colors">
                        Save Settings
                    </button>
                </div>
            </div>

            <!-- Wave Pay Settings -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 bg-gray-50/50 border-b border-gray-100 flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="p-2 bg-cyan-100 rounded-xl mr-3">
                            <svg class="w-5 h-5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Wave Pay</h3>
                            <p class="text-sm text-gray-500 myanmar-text">ဝေ့ပေး</p>
                        </div>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" wire:model.live="wavepay_enabled" class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border after:border-gray-300 after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500"></div>
                    </label>
                </div>
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1 myanmar-text">ဖုန်းနံပါတ်</label>
                            <input type="text" wire:model="wavepay_phone" placeholder="09xxxxxxxxx" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1 myanmar-text">အကောင့်အမည်</label>
                            <input type="text" wire:model="wavepay_account_name" placeholder="Account Name" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        </div>
                    </div>
                    <button wire:click="saveWavePay" class="w-full px-4 py-2.5 bg-gray-900 hover:bg-gray-800 text-white font-medium rounded-xl transition-colors">
                        Save Settings
                    </button>
                </div>
            </div>

            <!-- CB Pay Settings -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 bg-gray-50/50 border-b border-gray-100 flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="p-2 bg-blue-100 rounded-xl mr-3">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">CB Pay</h3>
                            <p class="text-sm text-gray-500 myanmar-text">စီဘီပေး</p>
                        </div>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" wire:model.live="cbpay_enabled" class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border after:border-gray-300 after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500"></div>
                    </label>
                </div>
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1 myanmar-text">ဖုန်းနံပါတ်</label>
                            <input type="text" wire:model="cbpay_phone" placeholder="09xxxxxxxxx" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1 myanmar-text">အကောင့်အမည်</label>
                            <input type="text" wire:model="cbpay_account_name" placeholder="Account Name" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        </div>
                    </div>
                    <button wire:click="saveCBPay" class="w-full px-4 py-2.5 bg-gray-900 hover:bg-gray-800 text-white font-medium rounded-xl transition-colors">
                        Save Settings
                    </button>
                </div>
            </div>

            <!-- AYA Pay Settings -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 bg-gray-50/50 border-b border-gray-100 flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="p-2 bg-violet-100 rounded-xl mr-3">
                            <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">AYA Pay</h3>
                            <p class="text-sm text-gray-500 myanmar-text">အေရာပေး</p>
                        </div>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" wire:model.live="ayapay_enabled" class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border after:border-gray-300 after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500"></div>
                    </label>
                </div>
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1 myanmar-text">ဖုန်းနံပါတ်</label>
                            <input type="text" wire:model="ayapay_phone" placeholder="09xxxxxxxxx" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1 myanmar-text">အကောင့်အမည်</label>
                            <input type="text" wire:model="ayapay_account_name" placeholder="Account Name" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        </div>
                    </div>
                    <button wire:click="saveAYAPay" class="w-full px-4 py-2.5 bg-gray-900 hover:bg-gray-800 text-white font-medium rounded-xl transition-colors">
                        Save Settings
                    </button>
                </div>
            </div>
        </div>

        <!-- General Settings -->
        <div class="mt-6 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 bg-gray-50/50 border-b border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-3">
                    <div class="p-2 bg-gray-100 rounded-lg">
                        <svg class="h-5 w-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <span class="myanmar-text">အထွေထွေ ဆက်တင်များ</span>
                </h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2 myanmar-text">မူလ ငွေပေးချေနည်း</label>
                        <select wire:model="default_payment_method" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                            <option value="cash">Cash (ငွေသား)</option>
                            <option value="kbzpay">KBZPay</option>
                            <option value="wavepay">Wave Pay</option>
                            <option value="cbpay">CB Pay</option>
                            <option value="ayapay">AYA Pay</option>
                        </select>
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" wire:model="show_qr_on_receipt" id="show_qr_on_receipt" class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                        <label for="show_qr_on_receipt" class="ml-2 text-sm text-gray-700 myanmar-text">ပြေစာတွင် QR Code ပြသရန်</label>
                    </div>
                </div>
                <div class="mt-6">
                    <button wire:click="saveGeneralSettings" class="px-6 py-2.5 bg-gray-900 hover:bg-gray-800 text-white font-medium rounded-xl transition-colors">
                        Save General Settings
                    </button>
                </div>
            </div>
        </div>

        <!-- Help Section -->
        <div class="mt-6 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <div class="p-2 bg-gray-100 rounded-lg">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <span class="myanmar-text">အကူအညီ</span>
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div class="bg-gray-50 rounded-xl p-4">
                    <h4 class="font-semibold text-gray-900 mb-2">Static QR Payment</h4>
                    <p class="text-gray-600 myanmar-text">ဖုန်းနံပါတ်နှင့် အကောင့်အမည် ထည့်သွင်းပါက QR Code ကို အလိုအလျောက် ဖန်တီးပေးပါမည်။</p>
                </div>
                <div class="bg-gray-50 rounded-xl p-4">
                    <h4 class="font-semibold text-gray-900 mb-2">API Integration</h4>
                    <p class="text-gray-600 myanmar-text">KBZPay Merchant Account ရှိပါက API credentials ထည့်သွင်းပြီး အလိုအလျောက် အတည်ပြုနိုင်ပါသည်။</p>
                </div>
            </div>
        </div>
    </div>
</div>
