<div class="h-[calc(100vh-65px)] flex bg-gray-50 overflow-hidden" 
    x-data="{ barcodeMode: true }" 
    x-init="$refs.barcodeInput?.focus()"
    @keydown.window="if(barcodeMode && $event.target.tagName !== 'INPUT') $refs.barcodeInput?.focus()">
    
    {{-- Shift Modal --}}
    @if($showShiftModal)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-gray-900/80 backdrop-blur-sm"></div>
                <div class="relative bg-white rounded-2xl shadow-2xl max-w-lg w-full p-6">
                    @livewire('cashier.shift-management')
                </div>
            </div>
        </div>
    @endif

    {{-- Left Sidebar: Quick Actions --}}
    <div class="w-20 bg-gradient-to-b from-blue-600 to-blue-700 flex flex-col items-center py-4 space-y-3">
        {{-- Logo --}}
        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mb-4">
            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
            </svg>
        </div>

        {{-- Barcode Toggle --}}
        <button @click="barcodeMode = !barcodeMode; if(barcodeMode) $nextTick(() => $refs.barcodeInput?.focus())"
            :class="barcodeMode ? 'bg-white text-blue-600 shadow-lg' : 'bg-white/10 text-white hover:bg-white/20'"
            class="w-14 h-14 rounded-xl flex flex-col items-center justify-center transition-all">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
            </svg>
            <span class="text-[10px] mt-1 font-medium">Barcode</span>
        </button>

        {{-- Customer --}}
        <button wire:click="$set('showCustomerModal', true)"
            class="w-14 h-14 rounded-xl bg-white/10 text-white hover:bg-white/20 flex flex-col items-center justify-center transition-all">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
            <span class="text-[10px] mt-1 font-medium">Customer</span>
        </button>

        <div class="flex-1"></div>

        {{-- Low Stock Alert --}}
        @if(count($lowStockItems) > 0)
            <div class="relative">
                <button class="w-14 h-14 rounded-xl bg-yellow-500 text-white hover:bg-yellow-600 flex flex-col items-center justify-center transition-all animate-pulse">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    <span class="text-[10px] mt-1 font-medium">Stock</span>
                </button>
                <span class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-[10px] rounded-full flex items-center justify-center font-bold">
                    {{ count($lowStockItems) }}
                </span>
            </div>
        @endif

        {{-- Calculator --}}
        <button wire:click="$set('showCalculatorModal', true)" class="w-14 h-14 rounded-xl bg-white/10 text-white hover:bg-white/20 flex flex-col items-center justify-center transition-all">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
            </svg>
            <span class="text-[10px] mt-1 font-medium">Calc</span>
        </button>
    </div>

    {{-- Categories Sidebar --}}
    <div class="w-44 bg-white border-r border-gray-200 flex flex-col">
        <div class="p-3 border-b border-gray-100">
            <h2 class="font-bold text-gray-800 text-sm myanmar-text">အမျိုးအစား</h2>
        </div>
        <div class="flex-1 overflow-y-auto py-2 px-2 space-y-1">
            <button wire:click="selectCategory(null)"
                class="w-full px-3 py-2 rounded-lg text-left text-sm transition-all {{ !$selectedCategory ? 'bg-blue-50 text-blue-700 font-semibold border border-blue-200' : 'text-gray-600 hover:bg-gray-50' }}">
                <span class="myanmar-text">အားလုံး</span>
                <span class="text-xs text-gray-400 ml-1">({{ $items->count() }})</span>
            </button>
            @foreach($categories as $category)
                <button wire:click="selectCategory({{ $category->id }})"
                    class="w-full px-3 py-2 rounded-lg text-left text-sm transition-all {{ $selectedCategory == $category->id ? 'bg-blue-50 text-blue-700 font-semibold border border-blue-200' : 'text-gray-600 hover:bg-gray-50' }}">
                    <span class="myanmar-text truncate block">{{ $category->name_mm }}</span>
                    <span class="text-xs text-gray-400">({{ $category->active_items_count }})</span>
                </button>
            @endforeach
        </div>
    </div>

    {{-- Main Content: Items Grid --}}
    <div class="flex-1 flex flex-col min-w-0">
        {{-- Header with Barcode & Search --}}
        <div class="bg-white border-b border-gray-200 p-4">
            <div class="flex items-center gap-4">
                {{-- Barcode Input --}}
                <div class="relative flex-1 max-w-sm" x-show="barcodeMode">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                        </svg>
                    </div>
                    <input type="text" 
                        wire:model="barcodeInput" 
                        wire:keydown.enter="scanBarcode"
                        x-ref="barcodeInput"
                        placeholder="Barcode စကင်ဖတ်ရန်..." 
                        class="w-full pl-10 pr-4 py-3 border-2 border-blue-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-lg font-mono bg-blue-50">
                </div>

                {{-- Search --}}
                <div class="relative flex-1">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input type="text" wire:model.live="searchTerm" 
                        placeholder="ပစ္စည်းရှာရန်..." 
                        class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                </div>

                {{-- Customer Badge --}}
                @if($customer)
                    <div class="flex items-center gap-2 bg-green-50 px-3 py-2 rounded-xl border border-green-200">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span class="font-medium text-green-800">{{ $customer->name }}</span>
                        <button wire:click="$set('customer', null)" class="text-green-400 hover:text-green-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                @endif
            </div>
        </div>

        {{-- Items Grid --}}
        <div class="flex-1 overflow-y-auto p-4">
            <div class="grid grid-cols-4 sm:grid-cols-5 md:grid-cols-6 lg:grid-cols-7 gap-3">
                @forelse($items as $item)
                    <button wire:click="addToCart({{ $item->id }})"
                        class="bg-white rounded-xl border border-gray-100 p-3 hover:shadow-lg hover:border-blue-300 hover:-translate-y-0.5 transition-all text-left group relative">
                        {{-- Stock Badge --}}
                        @if($item->stock_quantity !== null)
                            <div class="absolute top-2 right-2 text-[10px] font-bold px-1.5 py-0.5 rounded
                                {{ $item->stock_quantity <= 0 ? 'bg-red-100 text-red-700' : ($item->stock_quantity <= ($item->reorder_level ?? 10) ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700') }}">
                                {{ $item->stock_quantity }}
                            </div>
                        @endif
                        
                        <div class="aspect-square bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg mb-2 flex items-center justify-center overflow-hidden">
                            @if($item->image)
                                <img src="{{ asset('storage/' . $item->image) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform">
                            @else
                                <svg class="w-8 h-8 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            @endif
                        </div>
                        <h3 class="font-medium text-gray-900 text-xs truncate">{{ $item->name }}</h3>
                        <p class="text-[10px] text-gray-400 font-mono">{{ $item->barcode ?: $item->sku ?: '-' }}</p>
                        <p class="text-sm font-bold text-blue-600 mt-1">{{ number_format($item->price) }} <span class="text-xs font-normal">Ks</span></p>
                    </button>
                @empty
                    <div class="col-span-full text-center py-16 text-gray-400">
                        <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                        </svg>
                        <p class="myanmar-text">ပစ္စည်း မရှိပါ</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Right Sidebar: Cart --}}
    <div class="w-96 bg-white border-l border-gray-200 flex flex-col">
        {{-- Cart Header --}}
        <div class="p-4 border-b border-gray-100 bg-gradient-to-r from-blue-500 to-blue-600">
            <div class="flex justify-between items-center">
                <div class="text-white">
                    <h2 class="font-bold text-lg myanmar-text">ဈေးခြင်း</h2>
                    <p class="text-blue-100 text-xs">{{ count($cart) }} items</p>
                </div>
                @if(count($cart) > 0)
                    <button wire:click="clearCart" class="text-blue-200 hover:text-white text-xs font-medium px-3 py-1 rounded-lg bg-white/10 hover:bg-white/20 transition-all">
                        Clear
                    </button>
                @endif
            </div>
        </div>

        {{-- Cart Items --}}
        <div class="flex-1 overflow-y-auto p-3 space-y-2">
            @forelse($cart as $index => $item)
                <div class="bg-gray-50 rounded-xl p-3">
                    <div class="flex justify-between items-start mb-2">
                        <div class="flex-1 min-w-0">
                            <h4 class="font-medium text-gray-900 text-sm truncate">{{ $item['name'] }}</h4>
                            <p class="text-[10px] text-gray-400 font-mono">{{ $item['barcode'] ?: $item['sku'] ?: '-' }}</p>
                            <p class="text-xs text-gray-500">{{ number_format($item['price']) }} Ks</p>
                        </div>
                        <button wire:click="removeFromCart({{ $index }})" class="text-gray-400 hover:text-red-500 p-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-1 bg-white rounded-lg border border-gray-200">
                            <button wire:click="updateQuantity({{ $index }}, {{ $item['quantity'] - 1 }})"
                                class="w-8 h-8 flex items-center justify-center text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-l-lg transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                </svg>
                            </button>
                            <input type="number" 
                                value="{{ $item['quantity'] }}" 
                                wire:change="updateQuantity({{ $index }}, $event.target.value)"
                                class="w-12 text-center font-bold text-gray-900 border-0 focus:ring-0 p-0">
                            <button wire:click="updateQuantity({{ $index }}, {{ $item['quantity'] + 1 }})"
                                class="w-8 h-8 flex items-center justify-center text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-r-lg transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                            </button>
                        </div>
                        <span class="font-bold text-gray-900">{{ number_format($item['total']) }} <span class="text-xs font-normal text-gray-500">Ks</span></span>
                    </div>
                </div>
            @empty
                <div class="text-center py-12 text-gray-400">
                    <svg class="w-16 h-16 mx-auto mb-3 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    <p class="text-sm myanmar-text">Barcode စကင်ဖတ်ပါ</p>
                    <p class="text-xs text-gray-300 mt-1">သို့မဟုတ် ပစ္စည်းကိုနှိပ်ပါ</p>
                </div>
            @endforelse
        </div>

        {{-- Totals --}}
        <div class="border-t border-gray-200 p-4 space-y-2 bg-gray-50">
            <div class="flex justify-between text-sm">
                <span class="text-gray-500 myanmar-text">စုစုပေါင်း</span>
                <span class="font-medium">{{ number_format($subtotal) }} Ks</span>
            </div>
            
            @if($taxPercentage > 0)
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Tax ({{ $taxPercentage }}%)</span>
                    <span class="font-medium">{{ number_format($taxAmount) }} Ks</span>
                </div>
            @endif
            
            <div class="flex items-center gap-2">
                <span class="text-gray-500 text-sm myanmar-text">လျှော့စျေး</span>
                <input type="number" wire:model.live="discountPercentage" 
                    class="w-16 text-center text-sm border border-gray-200 rounded-lg px-2 py-1 focus:ring-blue-500 focus:border-blue-500" min="0" max="100">
                <span class="text-gray-500 text-sm">%</span>
                <span class="ml-auto text-red-500 font-medium">-{{ number_format($discountAmount) }} Ks</span>
            </div>

            <div class="flex justify-between text-xl font-bold border-t border-gray-200 pt-3 mt-3">
                <span class="myanmar-text">ပေးရန်</span>
                <span class="text-blue-600">{{ number_format($total) }} <span class="text-sm font-normal">Ks</span></span>
            </div>
        </div>

        {{-- Numpad & Payment --}}
        <div class="p-4 border-t border-gray-200 space-y-3">
            {{-- Quick Amount Buttons --}}
            <div class="grid grid-cols-4 gap-2">
                @foreach([1000, 5000, 10000, 50000] as $amount)
                    <button wire:click="setQuickAmount({{ $amount }})"
                        class="py-2 rounded-lg border border-gray-200 text-xs font-medium hover:bg-blue-50 hover:border-blue-300 transition-all">
                        {{ number_format($amount/1000) }}K
                    </button>
                @endforeach
            </div>
            
            <button wire:click="openPaymentModal" 
                class="w-full bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white py-4 rounded-xl font-bold text-lg flex items-center justify-center gap-2 transition-all shadow-lg shadow-blue-500/30 {{ empty($cart) ? 'opacity-50 cursor-not-allowed' : '' }}"
                {{ empty($cart) ? 'disabled' : '' }}>
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <span class="myanmar-text">ငွေရှင်းမည်</span>
                <span class="bg-white/20 px-2 py-0.5 rounded text-sm ml-2">F12</span>
            </button>
        </div>
    </div>

    {{-- Payment Modal --}}
    @if($showPaymentModal)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-gray-900/80 backdrop-blur-sm" wire:click="closePaymentModal"></div>
                <div class="relative bg-white rounded-2xl shadow-2xl max-w-lg w-full p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-6 myanmar-text">ငွေပေးချေမှု</h3>
                    
                    <div class="space-y-6">
                        {{-- Total Amount --}}
                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-2xl p-6 text-center text-white">
                            <p class="text-blue-100 text-sm myanmar-text">ပေးရန်ပမာဏ</p>
                            <p class="text-4xl font-bold">{{ number_format($total) }} <span class="text-lg">Ks</span></p>
                        </div>

                        {{-- Payment Methods --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3 myanmar-text">ငွေပေးချေမှုနည်းလမ်း</label>
                            <div class="grid grid-cols-4 gap-2">
                                <button type="button" wire:click="$set('paymentMethod', 'cash')"
                                    class="p-3 rounded-xl border-2 text-center transition-all {{ $paymentMethod === 'cash' ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:border-gray-300' }}">
                                    <svg class="w-8 h-8 mx-auto mb-1 {{ $paymentMethod === 'cash' ? 'text-blue-600' : 'text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    <span class="text-xs font-medium">Cash</span>
                                </button>
                                <button type="button" wire:click="$set('paymentMethod', 'kpay')"
                                    class="p-3 rounded-xl border-2 text-center transition-all {{ $paymentMethod === 'kpay' ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:border-gray-300' }}">
                                    <div class="w-8 h-8 mx-auto mb-1 rounded-lg {{ $paymentMethod === 'kpay' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-600' }} flex items-center justify-center font-bold text-lg">K</div>
                                    <span class="text-xs font-medium">KPay</span>
                                </button>
                                <button type="button" wire:click="$set('paymentMethod', 'wave')"
                                    class="p-3 rounded-xl border-2 text-center transition-all {{ $paymentMethod === 'wave' ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:border-gray-300' }}">
                                    <div class="w-8 h-8 mx-auto mb-1 rounded-lg {{ $paymentMethod === 'wave' ? 'bg-yellow-500 text-white' : 'bg-gray-200 text-gray-600' }} flex items-center justify-center font-bold text-lg">W</div>
                                    <span class="text-xs font-medium">Wave</span>
                                </button>
                                <button type="button" wire:click="$set('paymentMethod', 'card')"
                                    class="p-3 rounded-xl border-2 text-center transition-all {{ $paymentMethod === 'card' ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:border-gray-300' }}">
                                    <svg class="w-8 h-8 mx-auto mb-1 {{ $paymentMethod === 'card' ? 'text-blue-600' : 'text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                    </svg>
                                    <span class="text-xs font-medium">Card</span>
                                </button>
                            </div>
                        </div>

                        @if($paymentMethod === 'cash')
                            {{-- Quick Amount Buttons --}}
                            <div class="grid grid-cols-4 gap-2">
                                @foreach([1000, 5000, 10000, 20000, 50000, 100000] as $amount)
                                    <button wire:click="setQuickAmount({{ $amount }})"
                                        class="py-2 px-3 rounded-lg border border-gray-200 text-sm font-medium hover:bg-blue-50 hover:border-blue-300 transition-all {{ $amountReceived == $amount ? 'bg-blue-50 border-blue-300' : '' }}">
                                        {{ number_format($amount) }}
                                    </button>
                                @endforeach
                            </div>

                            {{-- Amount Received --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2 myanmar-text">လက်ခံငွေ</label>
                                <input type="number" wire:model.live="amountReceived" 
                                    class="w-full text-2xl font-bold text-center border border-gray-200 rounded-xl py-4 focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            {{-- Change --}}
                            <div class="flex justify-between items-center bg-green-50 rounded-xl p-4 border border-green-200">
                                <span class="text-green-700 font-medium myanmar-text">ပြန်အမ်းငွေ</span>
                                <span class="text-3xl font-bold text-green-600">{{ number_format($change) }} <span class="text-sm">Ks</span></span>
                            </div>
                        @endif

                        {{-- Action Buttons --}}
                        <div class="flex gap-3 pt-2">
                            <button type="button" wire:click="closePaymentModal" class="flex-1 px-6 py-3 border border-gray-200 rounded-xl text-gray-600 font-medium hover:bg-gray-50 transition-all">
                                Cancel
                            </button>
                            <button type="button" wire:click="processPayment" 
                                class="flex-1 px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-xl font-bold hover:from-blue-600 hover:to-blue-700 transition-all flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="myanmar-text">အတည်ပြုမည်</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Customer Modal --}}
    @if($showCustomerModal)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-gray-900/80 backdrop-blur-sm" wire:click="$set('showCustomerModal', false)"></div>
                <div class="relative bg-white rounded-2xl shadow-2xl max-w-md w-full p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 myanmar-text">ဖောက်သည်ရှာရန်</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1 myanmar-text">ဖုန်းနံပါတ်</label>
                            <input type="text" wire:model="customerPhone" placeholder="09xxxxxxxxx" 
                                class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div class="flex gap-3">
                            <button type="button" wire:click="$set('showCustomerModal', false)" class="flex-1 px-4 py-2 border border-gray-200 rounded-xl text-gray-600 hover:bg-gray-50">Cancel</button>
                            <button type="button" wire:click="$set('showCustomerModal', false)" class="flex-1 px-4 py-2 bg-blue-500 text-white rounded-xl hover:bg-blue-600 font-medium myanmar-text">ရှာမည်</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Calculator Modal --}}
    @if($showCalculatorModal ?? false)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-gray-900/80 backdrop-blur-sm" wire:click="$set('showCalculatorModal', false)"></div>
                <div class="relative bg-white rounded-2xl shadow-2xl max-w-xs w-full p-4">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 text-center">Calculator</h3>
                    <div class="bg-gray-100 rounded-xl p-4 mb-4">
                        <input type="text" class="w-full text-right text-2xl font-mono bg-transparent border-0 focus:ring-0" value="0" readonly>
                    </div>
                    <div class="grid grid-cols-4 gap-2">
                        @foreach(['7','8','9','÷','4','5','6','×','1','2','3','-','0','.','=','+'] as $key)
                            <button type="button" class="p-3 rounded-lg {{ in_array($key, ['÷','×','-','+','=']) ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-800' }} font-bold text-lg hover:opacity-80 transition-all">
                                {{ $key }}
                            </button>
                        @endforeach
                    </div>
                    <button type="button" wire:click="$set('showCalculatorModal', false)" class="w-full mt-4 px-4 py-2 border border-gray-200 rounded-xl text-gray-600 hover:bg-gray-50">Close</button>
                </div>
            </div>
        </div>
    @endif

    {{-- Success Modal --}}
    @if($showSuccessModal)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-gray-900/80 backdrop-blur-sm"></div>
                <div class="relative bg-white rounded-2xl shadow-2xl max-w-sm w-full p-8 text-center">
                    <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2 myanmar-text">အောင်မြင်ပါသည်!</h3>
                    <p class="text-gray-500 mb-2">Order #{{ $completedOrder?->order_number }}</p>
                    @if($change > 0)
                        <p class="text-lg font-bold text-green-600 mb-4">ပြန်အမ်းငွေ: {{ number_format($change) }} Ks</p>
                    @endif
                    <div class="flex gap-3">
                        <button wire:click="printReceipt" class="flex-1 px-4 py-2 border border-gray-200 rounded-xl text-gray-600 hover:bg-gray-50 flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                            </svg>
                            Print
                        </button>
                        <button wire:click="closeSuccessModal" class="flex-1 px-4 py-2 bg-blue-500 text-white rounded-xl hover:bg-blue-600 font-medium myanmar-text">
                            အော်ဒါအသစ်
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
