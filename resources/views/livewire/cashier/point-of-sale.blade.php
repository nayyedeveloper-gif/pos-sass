<div id="pos-root">
    <!-- Shift Management Modal -->
    @if($showShiftModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
                
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        @livewire('cashier.shift-management')
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="h-[calc(100vh-65px)] flex bg-gray-100 overflow-hidden {{ $showShiftModal ? 'filter blur-sm pointer-events-none' : '' }}">
    <!-- Left Sidebar: Categories -->
    <div class="w-64 bg-white border-r border-gray-200 flex flex-col hidden md:flex">
        <div class="p-4 border-b border-gray-100">
            <h2 class="font-bold text-gray-800 myanmar-text text-lg">အမျိုးအစားများ</h2>
        </div>
        <div class="flex-1 overflow-y-auto py-2 space-y-1 px-2">
            <button 
                wire:click="selectCategory(null)"
                class="w-full flex items-center justify-between px-4 py-3 rounded-xl text-left transition-all {{ !$selectedCategory ? 'bg-primary-50 text-primary-700 font-semibold ring-1 ring-primary-200' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}"
            >
                <span class="myanmar-text">အားလုံး</span>
                <span class="bg-white text-xs py-0.5 px-2 rounded-full border {{ !$selectedCategory ? 'border-primary-200 text-primary-600' : 'border-gray-200 text-gray-500' }}">All</span>
            </button>
            
            @foreach($categories as $category)
                <button 
                    wire:click="selectCategory({{ $category->id }})"
                    class="w-full flex items-center justify-between px-4 py-3 rounded-xl text-left transition-all {{ $selectedCategory == $category->id ? 'bg-primary-50 text-primary-700 font-semibold ring-1 ring-primary-200' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}"
                >
                    <span class="myanmar-text">{{ $category->name_mm }}</span>
                    <span class="bg-white text-xs py-0.5 px-2 rounded-full border {{ $selectedCategory == $category->id ? 'border-primary-200 text-primary-600' : 'border-gray-200 text-gray-500' }}">
                        {{ $category->active_items_count }}
                    </span>
                </button>
            @endforeach
        </div>
    </div>

    <!-- Middle: Items Grid -->
    <div class="flex-1 flex flex-col min-w-0 bg-gray-100">
        <!-- Header / Search / Mobile Categories -->
        <div class="bg-white border-b border-gray-200 p-4 shadow-sm z-10">
            <div class="flex flex-col sm:flex-row gap-4 justify-between items-center mb-4">
                <!-- Order Type Toggle - Only show if dine_in feature is enabled -->
                @if(\App\Helpers\FeatureHelper::has('dine_in'))
                <div class="flex bg-gray-100 p-1 rounded-xl w-full sm:w-auto">
                    <button 
                        wire:click="$set('orderType', 'dine_in')"
                        class="flex-1 sm:flex-none px-6 py-2 rounded-lg text-sm font-medium transition-all {{ $orderType === 'dine_in' ? 'bg-white text-primary-700 shadow-sm ring-1 ring-black/5' : 'text-gray-500 hover:text-gray-700' }}"
                    >
                        <span class="myanmar-text">စားပွဲ</span>
                    </button>
                    <button 
                        wire:click="$set('orderType', 'takeaway')"
                        class="flex-1 sm:flex-none px-6 py-2 rounded-lg text-sm font-medium transition-all {{ $orderType === 'takeaway' ? 'bg-white text-primary-700 shadow-sm ring-1 ring-black/5' : 'text-gray-500 hover:text-gray-700' }}"
                    >
                        <span class="myanmar-text">ပါဆယ်</span>
                    </button>
                </div>
                @endif

                <!-- Search -->
                <div class="relative w-full sm:max-w-md">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input 
                        type="text" 
                        wire:model.live="searchTerm" 
                        placeholder="ပစ္စည်း ရှာရန်..." 
                        class="form-input pl-10 py-2.5"
                    >
                </div>
            </div>

            <!-- Table Selection (Dine In Only) - Only show if tables feature is enabled -->
            @if(\App\Helpers\FeatureHelper::has('tables'))
            @if($orderType === 'dine_in')
                <div class="flex items-center gap-3 bg-blue-50 px-4 py-2 rounded-xl border border-blue-100">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    <span class="text-sm font-medium text-blue-800 myanmar-text">စားပွဲရွေးရန်:</span>
                    <select wire:model="selectedTable" class="form-select border-none bg-transparent focus:ring-0 text-blue-900 font-bold py-0 pl-0">
                        <option value="">ရွေးချယ်ပါ...</option>
                        @foreach($availableTables as $table)
                            <option value="{{ $table->id }}">{{ $table->name }} ({{ $table->name_mm }})</option>
                        @endforeach
                    </select>
                </div>
            @endif
            @endif

            <!-- Mobile Categories (Horizontal Scroll) -->
            <div class="md:hidden mt-4 flex overflow-x-auto space-x-2 pb-2 hide-scrollbar">
                <button wire:click="selectCategory(null)" class="flex-shrink-0 px-4 py-2 rounded-full text-sm font-medium {{ !$selectedCategory ? 'bg-primary-600 text-white' : 'bg-gray-100 text-gray-700' }}">All</button>
                @foreach($categories as $category)
                    <button wire:click="selectCategory({{ $category->id }})" class="flex-shrink-0 px-4 py-2 rounded-full text-sm font-medium {{ $selectedCategory == $category->id ? 'bg-primary-600 text-white' : 'bg-gray-100 text-gray-700' }}">
                        {{ $category->name_mm }}
                    </button>
                @endforeach
            </div>
        </div>

        <!-- Items Content -->
        <div class="flex-1 overflow-y-auto p-4 sm:p-6">
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-4 sm:gap-6">
                @forelse($items as $item)
                    <button 
                        wire:click="addToCart({{ $item->id }})"
                        class="group relative bg-white rounded-2xl shadow-sm border border-gray-100 hover:shadow-lg hover:border-primary-500 hover:-translate-y-1 transition-all duration-200 overflow-hidden flex flex-col h-full text-left"
                    >
                        <!-- Image / Icon Placeholder -->
                        <div class="aspect-[4/3] w-full bg-gray-50 flex items-center justify-center group-hover:bg-primary-50 transition-colors">
                            <div class="w-12 h-12 rounded-full bg-white shadow-sm flex items-center justify-center text-primary-600 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                            </div>
                        </div>
                        
                        <div class="p-4 flex-1 flex flex-col">
                            <h3 class="font-bold text-gray-900 text-sm sm:text-base line-clamp-1 mb-1 group-hover:text-primary-700 transition-colors">{{ $item->name }}</h3>
                            <p class="text-xs text-gray-500 myanmar-text line-clamp-1 mb-3">{{ $item->name_mm }}</p>
                            <div class="mt-auto flex items-center justify-between">
                                <span class="text-base sm:text-lg font-bold text-primary-600">{{ number_format($item->price, 0) }}</span>
                                <span class="text-xs text-gray-400">Ks</span>
                            </div>
                        </div>
                    </button>
                @empty
                    <div class="col-span-full flex flex-col items-center justify-center py-20 text-gray-400">
                        <svg class="w-16 h-16 mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="myanmar-text text-lg">ပစ္စည်း မတွေ့ရှိပါ</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Right Sidebar: Cart -->
    <div class="w-96 bg-white border-l border-gray-200 flex flex-col shadow-xl z-20">
        <!-- Customer Section -->
        <div class="p-4 border-b border-gray-100 bg-gray-50/50">
            <div class="flex items-center justify-between mb-2">
                <h2 class="font-bold text-gray-800 text-lg myanmar-text">လက်ရှိအော်ဒါ</h2>
                <span class="text-xs font-medium bg-primary-100 text-primary-700 px-2 py-1 rounded-full">{{ count($cart) }} items</span>
            </div>
            
            @if($customer)
                <div class="flex items-center justify-between bg-white p-3 rounded-xl border border-gray-200 shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-primary-100 flex items-center justify-center text-primary-700 font-bold text-xs">
                            {{ substr($customer->name, 0, 1) }}
                        </div>
                        <div>
                            <p class="text-sm font-bold text-gray-900">{{ $customer->name }}</p>
                            <p class="text-xs text-green-600 font-medium">{{ number_format($customer->loyalty_points) }} pts</p>
                        </div>
                    </div>
                    <button wire:click="clearCustomer" class="text-gray-400 hover:text-red-500 p-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            @else
                <button wire:click="$toggle('showCustomerLookup')" class="w-full flex items-center justify-center gap-2 py-2.5 border-2 border-dashed border-gray-300 rounded-xl text-sm text-gray-500 hover:border-primary-400 hover:text-primary-600 hover:bg-primary-50 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    <span class="myanmar-text">ဖောက်သည် ထည့်ရန်</span>
                </button>
            @endif

            @if($showCustomerLookup)
                <div class="mt-3 relative animate-fadeIn">
                    <div class="flex gap-2">
                        <input type="text" wire:model="customer_search_phone" placeholder="09xxxxxxxxx" class="form-input text-sm py-2">
                        <button wire:click="searchCustomer" class="btn btn-primary py-2 px-3">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </button>
                    </div>
                    @if(session('customer_error'))
                        <p class="text-red-500 text-xs mt-1">{{ session('customer_error') }}</p>
                    @endif
                </div>
            @endif
        </div>

        <!-- Cart Items (Scrollable) -->
        <div class="flex-1 overflow-y-auto p-4 space-y-3">
            @forelse($cart as $key => $item)
                <div class="group flex flex-col bg-white rounded-xl border border-gray-100 shadow-sm hover:shadow-md hover:border-primary-200 transition-all p-3">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <h4 class="font-semibold text-gray-900 text-sm">{{ $item['name'] }}</h4>
                            <p class="text-xs text-gray-500 myanmar-text">{{ $item['name_mm'] }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-gray-900 text-sm">{{ number_format($item['price'] * ($item['quantity'] - ($item['foc_quantity'] ?? 0)), 0) }}</p>
                            @if(($item['foc_quantity'] ?? 0) > 0)
                                <span class="text-[10px] font-bold bg-green-100 text-green-700 px-1.5 py-0.5 rounded">FOC: {{ $item['foc_quantity'] }}</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between mt-1">
                        <div class="flex items-center bg-gray-100 rounded-lg p-1">
                            <button wire:click="updateQuantity('{{ $key }}', {{ $item['quantity'] - 1 }})" class="w-7 h-7 flex items-center justify-center rounded-md bg-white shadow-sm text-gray-600 hover:text-red-600 hover:bg-red-50 disabled:opacity-50">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
                            </button>
                            <span class="w-8 text-center text-sm font-bold text-gray-800">{{ $item['quantity'] }}</span>
                            <button wire:click="updateQuantity('{{ $key }}', {{ $item['quantity'] + 1 }})" class="w-7 h-7 flex items-center justify-center rounded-md bg-white shadow-sm text-gray-600 hover:text-primary-600 hover:bg-primary-50">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            </button>
                        </div>
                        
                        <div class="flex items-center gap-2">
                            <button wire:click="toggleFoc('{{ $key }}')" class="text-xs {{ ($item['foc_quantity'] ?? 0) > 0 ? 'text-green-600 font-bold' : 'text-gray-400 hover:text-green-600' }} transition-colors">
                                FOC {{ ($item['foc_quantity'] ?? 0) > 0 ? '('.$item['foc_quantity'].')' : '' }}
                            </button>
                            <button wire:click="removeFromCart('{{ $key }}')" class="text-gray-400 hover:text-red-500 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="h-full flex flex-col items-center justify-center text-gray-400 opacity-60">
                    <svg class="w-16 h-16 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    <p class="text-sm font-medium myanmar-text">အော်ဒါစာရင်း အလွတ်</p>
                </div>
            @endforelse
        </div>

        <!-- Footer Totals -->
        <div class="bg-white border-t border-gray-200 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)] z-30">
            <!-- Toggles Row -->
            <div class="flex items-center justify-between px-4 py-2 border-b border-gray-100 text-xs bg-gray-50">
                <div class="flex items-center gap-4">
                    <label class="flex items-center gap-1.5 cursor-pointer">
                        <input type="checkbox" wire:model.live="enable_tax" class="rounded text-primary-600 focus:ring-primary-500 w-4 h-4 border-gray-300">
                        <span class="text-gray-600 myanmar-text">အခွန်</span>
                    </label>
                    <label class="flex items-center gap-1.5 cursor-pointer">
                        <input type="checkbox" wire:model.live="enable_service_charge" class="rounded text-primary-600 focus:ring-primary-500 w-4 h-4 border-gray-300">
                        <span class="text-gray-600 myanmar-text">ဝန်ဆောင်ခ</span>
                    </label>
                </div>
                <div class="flex items-center gap-1">
                    <span class="text-gray-500 myanmar-text">Discount:</span>
                    <input type="number" wire:model.live="discountPercentage" class="w-12 py-0.5 px-1 text-right text-xs border-gray-300 rounded focus:border-primary-500 focus:ring-primary-500" placeholder="0">%
                </div>
            </div>

            <div class="p-4 space-y-3">
                <div class="space-y-1 text-sm">
                    <div class="flex justify-between text-gray-600">
                        <span class="myanmar-text">စုစုပေါင်း</span>
                        <span>{{ number_format($subtotal, 0) }}</span>
                    </div>
                    @if($taxAmount > 0)
                        <div class="flex justify-between text-gray-600">
                            <span class="myanmar-text">Tax ({{ $taxPercentage }}%)</span>
                            <span>+{{ number_format($taxAmount, 0) }}</span>
                        </div>
                    @endif
                    @if($serviceChargeAmount > 0)
                        <div class="flex justify-between text-gray-600">
                            <span class="myanmar-text">Service ({{ $serviceChargePercentage }}%)</span>
                            <span>+{{ number_format($serviceChargeAmount, 0) }}</span>
                        </div>
                    @endif
                    @if($discountAmount > 0 || ($customer && $loyalty_points_to_redeem > 0))
                        <div class="flex justify-between text-red-600 font-medium">
                            <span class="myanmar-text">လျှော့ဈေး</span>
                            <span>-{{ number_format($discountAmount + (($loyalty_points_to_redeem ?? 0) / 100 * 1000), 0) }}</span>
                        </div>
                    @endif
                </div>

                <div class="flex justify-between items-end pt-2 border-t border-gray-100">
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-bold tracking-wider">Net Total</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($total, 0) }} <span class="text-sm text-gray-500 font-normal">Ks</span></p>
                    </div>
                </div>

                <div class="grid grid-cols-4 gap-2 mt-3">
                    <button wire:click="resetForm" class="col-span-1 btn btn-secondary py-3 text-gray-600 hover:text-red-600 border-gray-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </button>
                    <button wire:click="openPaymentModal" class="col-span-3 btn btn-primary py-3 text-base shadow-lg shadow-primary-500/30">
                        <span class="myanmar-text font-bold">ငွေရှင်းမည်</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
    <!-- Payment Modal -->
    @if($showPaymentModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closePaymentModal"></div>

                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-bold text-gray-900 mb-4" id="modal-title">
                                    <span class="myanmar-text">ငွေကောက်ခံမည်</span> / Process Payment
                                </h3>
                                
                                <div class="space-y-4">
                                    <!-- Payment Method -->
                                    <div>
                                        <label class="form-label myanmar-text">ငွေပေးချေမှုနည်းလမ်း</label>
                                        <div class="grid {{ $cardSystemEnabled ? 'grid-cols-3' : 'grid-cols-2' }} gap-2">
                                            <button 
                                                wire:click="$set('paymentMethod', 'cash')"
                                                class="px-4 py-2 rounded-lg font-medium {{ $paymentMethod === 'cash' ? 'bg-primary-600 text-white' : 'bg-gray-200 text-gray-700' }}"
                                            >
                                                <svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                                </svg>
                                                Cash
                                            </button>
                                            @if($cardSystemEnabled)
                                            <button 
                                                wire:click="$set('paymentMethod', 'card')"
                                                class="px-4 py-2 rounded-lg font-medium {{ $paymentMethod === 'card' ? 'bg-primary-600 text-white' : 'bg-gray-200 text-gray-700' }}"
                                            >
                                                <svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                                </svg>
                                                Card
                                            </button>
                                            @endif
                                            <button 
                                                wire:click="$set('paymentMethod', 'mobile')"
                                                class="px-4 py-2 rounded-lg font-medium {{ $paymentMethod === 'mobile' ? 'bg-primary-600 text-white' : 'bg-gray-200 text-gray-700' }}"
                                            >
                                                <svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                                </svg>
                                                Mobile
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Total Amount Breakdown -->
                                    <div class="bg-gray-50 p-4 rounded-lg space-y-2">
                                        <div class="flex justify-between items-center text-sm">
                                            <span class="text-gray-600 myanmar-text">စုစုပေါင်း</span>
                                            <span class="font-semibold">{{ number_format($subtotal, 0) }} Ks</span>
                                        </div>
                                        
                                        @if($customer && $loyalty_points_to_redeem > 0)
                                        <div class="flex justify-between items-center text-sm">
                                            <span class="text-green-600 myanmar-text">Loyalty လျှော့ဈေး</span>
                                            <span class="font-semibold text-green-600">-{{ number_format(($loyalty_points_to_redeem / 100) * 1000, 0) }} Ks</span>
                                        </div>
                                        @endif
                                        
                                        @if($discountAmount > 0)
                                        <div class="flex justify-between items-center text-sm">
                                            <span class="text-gray-600 myanmar-text">လျှော့ဈေး</span>
                                            <span class="font-semibold text-red-600">-{{ number_format($discountAmount, 0) }} Ks</span>
                                        </div>
                                        @endif
                                        
                                        @if($taxAmount > 0)
                                        <div class="flex justify-between items-center text-sm">
                                            <span class="text-gray-600 myanmar-text">အခွန်</span>
                                            <span class="font-semibold">+{{ number_format($taxAmount, 0) }} Ks</span>
                                        </div>
                                        @endif
                                        
                                        @if($serviceChargeAmount > 0)
                                        <div class="flex justify-between items-center text-sm">
                                            <span class="text-gray-600 myanmar-text">ဝန်ဆောင်မှုခ ({{ $serviceChargePercentage }}%)</span>
                                            <span class="font-semibold">+{{ number_format($serviceChargeAmount, 0) }} Ks</span>
                                        </div>
                                        @endif
                                        
                                        <div class="pt-2 border-t border-gray-300">
                                            <div class="flex justify-between items-center">
                                                <span class="text-lg font-semibold myanmar-text">ပေးရန်</span>
                                                <span class="text-2xl font-bold text-primary-600">{{ number_format($total, 0) }} Ks</span>
                                            </div>
                                        </div>
                                    </div>

                                    @if($paymentMethod === 'card' && $cardSystemEnabled)
                                        <!-- Card Payment Section -->
                                        <div class="space-y-3">
                                            <div>
                                                <label class="form-label myanmar-text">Card Number</label>
                                                <div class="flex gap-2">
                                                    <input 
                                                        type="text" 
                                                        wire:model="card_number"
                                                        class="form-input flex-1 font-mono"
                                                        placeholder="TC12345678"
                                                        maxlength="10"
                                                    >
                                                    <button 
                                                        wire:click="checkCardBalance"
                                                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
                                                    >
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                                        </svg>
                                                    </button>
                                                </div>
                                                @if(session('card_error'))
                                                    <p class="text-red-600 text-sm mt-1">{{ session('card_error') }}</p>
                                                @endif
                                            </div>

                                            @if($card)
                                                <div class="bg-blue-50 p-4 rounded-lg space-y-2">
                                                    <div class="flex justify-between items-center">
                                                        <span class="text-sm text-gray-700 myanmar-text">Card Number:</span>
                                                        <span class="font-mono font-semibold">{{ $card->card_number }}</span>
                                                    </div>
                                                    <div class="flex justify-between items-center">
                                                        <span class="text-sm text-gray-700 myanmar-text">လက်ရှိ Balance:</span>
                                                        <span class="text-lg font-bold text-blue-600">{{ number_format($card_balance) }} Ks</span>
                                                    </div>
                                                    @if($card_balance < $total)
                                                        <div class="flex justify-between items-center pt-2 border-t">
                                                            <span class="text-sm text-red-600 myanmar-text">Balance မလုံလောက်ပါ</span>
                                                            <button 
                                                                wire:click="openCardReloadModal"
                                                                class="text-sm px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700"
                                                            >
                                                                <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                                </svg>
                                                                Reload
                                                            </button>
                                                        </div>
                                                    @else
                                                        <div class="flex items-center text-green-600 text-sm pt-2 border-t">
                                                            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                            </svg>
                                                            <span class="myanmar-text">Balance လုံလောက်ပါသည်</span>
                                                        </div>
                                                    @endif
                                                    <button 
                                                        wire:click="clearCard"
                                                        class="text-sm text-red-600 hover:text-red-800"
                                                    >
                                                        <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                        </svg>
                                                        Clear Card
                                                    </button>
                                                </div>
                                            @endif
                                        </div>
                                    @elseif($paymentMethod === 'cash')
                                        <!-- Amount Received -->
                                        <div>
                                            <label class="form-label myanmar-text">လက်ခံငွေ</label>
                                            <input 
                                                type="number" 
                                                wire:model.live="amountReceived"
                                                class="form-input text-lg font-semibold"
                                                min="0"
                                                step="100"
                                            >
                                        </div>

                                        <!-- Change -->
                                        @if($change > 0)
                                            <div class="bg-green-50 p-4 rounded-lg">
                                                <div class="flex justify-between items-center">
                                                    <span class="text-lg font-semibold myanmar-text text-green-800">ပြန်အမ်းငွေ</span>
                                                    <span class="text-2xl font-bold text-green-600">{{ number_format($change, 0) }} Ks</span>
                                                </div>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </div>
                            
                            @if (session()->has('error'))
                                <div class="mt-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                                    <p class="text-sm text-red-600 flex items-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        {{ session('error') }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button 
                            wire:click="processPayment"
                            type="button" 
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary-600 text-base font-medium text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:ml-3 sm:w-auto sm:text-sm"
                        >
                            <span class="myanmar-text">အတည်ပြုမည်</span> / Confirm
                        </button>
                        <button 
                            wire:click="closePaymentModal"
                            type="button" 
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                        >
                            <span class="myanmar-text">မလုပ်တော့</span> / Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Success Modal -->
    @if($showSuccessModal)
    <div class="fixed inset-0 bg-gray-900 bg-opacity-75 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full overflow-hidden">
            <!-- Success Animation -->
            <div class="bg-gradient-to-br from-green-500 to-green-600 p-8 text-center">
                <div class="inline-flex items-center justify-center w-24 h-24 bg-white rounded-full mb-4 animate-bounce">
                    <svg class="w-16 h-16 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h2 class="text-3xl font-bold text-white mb-2 myanmar-text">အောင်မြင်ပါသည်!</h2>
                <p class="text-green-100 text-lg myanmar-text">အော်ဒါ အောင်မြင်စွာ ပြီးစီးပါပြီ</p>
            </div>

            <div class="p-6">
                <div class="text-center mb-6">
                    <p class="text-gray-600 myanmar-text">ငွေရှင်းခြင်း ပြီးစီးပါပြီ</p>
                    <p class="text-sm text-gray-500 mt-1">Order completed successfully</p>
                </div>

                <!-- Actions -->
                <div class="space-y-3">
                    <button 
                        type="button"
                        wire:click.prevent="printCompletedReceipt"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-4 px-6 rounded-xl transition-colors flex items-center justify-center space-x-2"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                        </svg>
                        <span class="myanmar-text">ဘောင်ချာ ပရင့်ထုတ်မည်</span>
                    </button>

                    <button 
                        wire:click="closeSuccessModal"
                        class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-4 px-6 rounded-xl transition-colors myanmar-text"
                    >
                        ပြီးပါပြီ
                    </button>
                </div>

                <div class="mt-6 pt-6 border-t border-gray-200 text-center">
                    <p class="text-sm text-gray-500 flex items-center justify-center space-x-2 myanmar-text">
                        <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"></path>
                        </svg>
                        <span>ကျေးဇူးတင်ပါသည်</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
    @endif

    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('print-payment-receipt', (event) => {
                const orderId = event.orderId;
                
                // Fetch order details
                fetch(`/api/orders/${orderId}/receipt`)
                    .then(response => response.json())
                    .then(order => {
                        printDetailedReceipt(order);
                    });
            });
        });

        function printDetailedReceipt(order) {
            const printWindow = window.open('', '_blank', 'width=400,height=800');
            
            if (!printWindow) {
                alert('ကျေးဇူးပြု၍ Popup Blocker ကို ပိတ်ပေးပါ။\nPlease allow popups for this site.');
                return;
            }
            
            const paymentMethodLabels = {
                'cash': 'ငွေသား / Cash',
                'card': 'ကတ် / Card',
                'mobile': 'မိုဘိုင်း / Mobile'
            };
            
            let itemsHtml = '';
            order.order_items.forEach(item => {
                const focText = item.foc_quantity > 0 ? ` \x3Cspan style="font-weight: bold;">(FOC: ${item.foc_quantity})\x3C/span>` : '';
                
                // Use Burmese name if available, fallback to English
                let itemNameHtml = '';
                if (item.item.name_mm) {
                    itemNameHtml = `\x3Cdiv style="font-weight: bold;">${item.item.name_mm}\x3C/div>
                                    \x3Cdiv style="font-size: 10px; color: #666;">${item.item.name}\x3C/div>`;
                } else {
                    itemNameHtml = `\x3Cdiv style="font-weight: bold;">${item.item.name}\x3C/div>`;
                }

                itemsHtml += `
                    \x3Ctr>
                        \x3Ctd style="padding: 4px 0;">
                            ${itemNameHtml}
                            ${focText}
                        \x3C/td>
                        \x3Ctd style="text-align: center; vertical-align: top; padding-top: 4px;">${item.quantity}\x3C/td>
                        \x3Ctd style="text-align: right; vertical-align: top; padding-top: 4px;">${Number(item.price).toLocaleString()}\x3C/td>
                        \x3Ctd style="text-align: right; vertical-align: top; padding-top: 4px;">${Number(item.subtotal).toLocaleString()}\x3C/td>
                    \x3C/tr>
                `;
            });
            
            let summaryHtml = `
                <tr>
                    <td colspan="3" style="text-align: right; padding: 8px 0;">Subtotal:</td>
                    <td style="text-align: right; font-weight: bold;">${Number(order.subtotal).toLocaleString()} Ks</td>
                </tr>
            `;
            
            if (order.tax_amount > 0) {
                summaryHtml += `
                    <tr>
                        <td colspan="3" style="text-align: right; padding: 4px 0;">Tax (${order.tax_percentage}%):</td>
                        <td style="text-align: right;">${Number(order.tax_amount).toLocaleString()} Ks</td>
                    </tr>
                `;
            }
            
            if (order.service_charge > 0) {
                summaryHtml += `
                    <tr>
                        <td colspan="3" style="text-align: right; padding: 4px 0;">Service Charge:</td>
                        <td style="text-align: right;">${Number(order.service_charge).toLocaleString()} Ks</td>
                    </tr>
                `;
            }
            
            if (order.discount_amount > 0) {
                const discountLabel = order.discount_percentage > 0 
                    ? `Discount (${order.discount_percentage}%)` 
                    : 'Discount';
                summaryHtml += `
                    <tr>
                        <td colspan="3" style="text-align: right; padding: 4px 0;">${discountLabel}:</td>
                        <td style="text-align: right; color: #059669;">-${Number(order.discount_amount).toLocaleString()} Ks</td>
                    </tr>
                `;
            }
            
            summaryHtml += `
                <tr style="border-top: 2px solid #000;">
                    <td colspan="3" style="text-align: right; padding: 8px 0; font-weight: bold; font-size: 16px;">TOTAL:</td>
                    <td style="text-align: right; font-weight: bold; font-size: 16px;">${Number(order.total).toLocaleString()} Ks</td>
                </tr>
            `;
            
            let paymentHtml = `
                <tr>
                    <td colspan="3" style="text-align: right; padding: 4px 0;">Payment Method:</td>
                    <td style="text-align: right;">${paymentMethodLabels[order.payment_method] || order.payment_method}</td>
                </tr>
            `;
            
            if (order.payment_method === 'cash' && order.amount_received) {
                paymentHtml += `
                    <tr>
                        <td colspan="3" style="text-align: right; padding: 4px 0;">Amount Received:</td>
                        <td style="text-align: right;">${Number(order.amount_received).toLocaleString()} Ks</td>
                    </tr>
                    <tr>
                        <td colspan="3" style="text-align: right; padding: 4px 0;">Change:</td>
                        <td style="text-align: right; font-weight: bold;">${Number(order.change_amount).toLocaleString()} Ks</td>
                    </tr>
                `;
            }
            
            const html = `
                \x3C!DOCTYPE html>
                \x3Chtml>
                \x3Chead>
                    \x3Cmeta charset="UTF-8">
                    \x3Ctitle>Receipt - ${order.order_number}\x3C/title>
                    \x3Cstyle>
                        @page { size: 80mm auto; margin: 0; }
                        * { margin: 0; padding: 0; box-sizing: border-box; }
                        body {
                            font-family: 'Courier New', monospace;
                            font-size: 12px;
                            line-height: 1.4;
                            padding: 10mm;
                            width: 80mm;
                        }
                        .header { text-align: center; margin-bottom: 10px; border-bottom: 2px dashed #000; padding-bottom: 10px; }
                        .header h1 { font-size: 18px; margin-bottom: 5px; }
                        .header p { font-size: 11px; }
                        .info { margin: 10px 0; font-size: 11px; }
                        .info div { margin: 3px 0; }
                        table { width: 100%; border-collapse: collapse; margin: 10px 0; }
                        th { text-align: left; border-bottom: 1px solid #000; padding: 5px 0; font-size: 11px; }
                        td { padding: 4px 0; font-size: 11px; }
                        .footer { text-align: center; margin-top: 15px; padding-top: 10px; border-top: 2px dashed #000; font-size: 11px; }
                        @media print {
                            body { margin: 0; padding: 10mm; }
                        }
                    \x3C/style>
                \x3C/head>
                \x3Cbody>
                    \x3Cdiv class="header">
                        \x3Ch1>${order.shop_name || 'My Business'}\x3C/h1>
                        ${order.shop_address ? `\x3Cp style="font-size: 11px; margin-top: 5px;">${order.shop_address}\x3C/p>` : ''}
                        ${order.shop_phone ? `\x3Cp style="font-size: 11px;">Tel: ${order.shop_phone}\x3C/p>` : ''}
                    \x3C/div>
                    
                    \x3Cdiv class="info">
                        \x3Cdiv>\x3Cstrong>Order #:\x3C/strong> ${order.order_number}\x3C/div>
                        ${order.table ? `\x3Cdiv>\x3Cstrong>Table:\x3C/strong> ${order.table.name}\x3C/div>` : ''}
                        ${order.waiter ? `\x3Cdiv>\x3Cstrong>Waiter:\x3C/strong> ${order.waiter.name}\x3C/div>` : ''}
                        \x3Cdiv>\x3Cstrong>Cashier:\x3C/strong> ${order.cashier.name}\x3C/div>
                        \x3Cdiv>\x3Cstrong>Date:\x3C/strong> ${new Date(order.completed_at).toLocaleString()}\x3C/div>
                    \x3C/div>
                    
                    \x3Ctable>
                        \x3Cthead>
                            \x3Ctr>
                                \x3Cth>Item\x3C/th>
                                \x3Cth style="text-align: center;">Qty\x3C/th>
                                \x3Cth style="text-align: right;">Price\x3C/th>
                                \x3Cth style="text-align: right;">Total\x3C/th>
                            \x3C/tr>
                        \x3C/thead>
                        \x3Ctbody>
                            ${itemsHtml}
                        \x3C/tbody>
                    \x3C/table>
                    
                    \x3Ctable>
                        \x3Ctbody>
                            ${summaryHtml}
                            ${paymentHtml}
                        \x3C/tbody>
                    \x3C/table>
                    
                    \x3Cdiv class="footer">
                        \x3Cp>*** Thank You! Come Again! ***\x3C/p>
                        \x3Cp style="margin-top: 5px;">Powered by Tea House POS\x3C/p>
                    \x3C/div>
                \x3C/body>
                \x3C/html>
            `;
            
            printWindow.document.write(html);
            printWindow.document.close();
            
            setTimeout(() => {
                printWindow.print();
                printWindow.close();
            }, 250);
        }
    </script>

    <!-- Card Reload Modal -->
    @if($showCardReloadModal)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75" wire:click="closeCardReloadModal"></div>
                
                <div class="relative bg-white rounded-lg shadow-xl max-w-md w-full p-6">
                    <h3 class="text-lg font-bold mb-4 myanmar-text">Card သို့ ငွေထည့်သွင်းမည်</h3>
                    
                    @if($card)
                    <div class="mb-4 p-3 bg-gray-50 rounded">
                        <p class="text-sm text-gray-600">Card: <span class="font-mono font-semibold">{{ $card->card_number }}</span></p>
                        <p class="text-sm text-gray-600">လက်ရှိ Balance: <span class="font-semibold">{{ number_format($card_balance) }} Ks</span></p>
                    </div>
                    @endif
                    
                    <div class="mb-4">
                        <label class="form-label myanmar-text">ထည့်သွင်းမည့် ပမာဏ (Ks)</label>
                        <input 
                            type="number" 
                            wire:model="card_reload_amount"
                            class="form-input text-lg font-semibold"
                            min="100"
                            step="100"
                            autofocus
                        >
                        @error('card_reload_amount') 
                            <span class="text-red-500 text-sm">{{ $message }}</span> 
                        @enderror
                    </div>
                    
                    @if(session('card_error'))
                        <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded">
                            <p class="text-sm text-red-600">{{ session('card_error') }}</p>
                        </div>
                    @endif
                    
                    <div class="flex justify-end space-x-2">
                        <button 
                            wire:click="closeCardReloadModal"
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300"
                        >
                            Cancel
                        </button>
                        <button 
                            wire:click="reloadCard"
                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700"
                        >
                            <svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Reload Card
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Global Flash Messages -->
    <div class="fixed bottom-4 right-4 z-[60] space-y-2 pointer-events-none">
        @if (session()->has('success'))
            <div class="bg-emerald-600 text-white px-6 py-3 rounded-xl shadow-lg flex items-center gap-3 animate-fade-in-up pointer-events-auto">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <div>
                    <p class="font-bold myanmar-text">အောင်မြင်သည်</p>
                    <p class="text-sm opacity-90 myanmar-text">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="bg-rose-600 text-white px-6 py-3 rounded-xl shadow-lg flex items-center gap-3 animate-fade-in-up pointer-events-auto">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <div>
                    <p class="font-bold myanmar-text">မှားယွင်းနေသည်</p>
                    <p class="text-sm opacity-90 myanmar-text">{{ session('error') }}</p>
                </div>
            </div>
        @endif
    </div>
</div>
