<div class="h-[calc(100vh-65px)] flex bg-gray-50 overflow-hidden" x-data="{ }">
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
    <div class="w-20 bg-gradient-to-b from-emerald-600 to-emerald-700 flex flex-col items-center py-4 space-y-3">
        {{-- Logo --}}
        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mb-4">
            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
            </svg>
        </div>

        {{-- Prescription --}}
        <button wire:click="$set('showPatientModal', true)"
            class="w-14 h-14 rounded-xl bg-white/10 text-white hover:bg-white/20 flex flex-col items-center justify-center transition-all {{ $prescriptionNumber ? 'ring-2 ring-white' : '' }}">
            <span class="text-xl font-bold">Rx</span>
            <span class="text-[10px] mt-0.5 font-medium">Patient</span>
        </button>

        {{-- Drug Info --}}
        <button class="w-14 h-14 rounded-xl bg-white/10 text-white hover:bg-white/20 flex flex-col items-center justify-center transition-all">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span class="text-[10px] mt-0.5 font-medium">Info</span>
        </button>

        <div class="flex-1"></div>

        {{-- Expiry Alert --}}
        @if(count($expiringItems) > 0)
            <div class="relative">
                <button class="w-14 h-14 rounded-xl bg-orange-500 text-white hover:bg-orange-600 flex flex-col items-center justify-center transition-all">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="text-[10px] mt-0.5 font-medium">Expiry</span>
                </button>
                <span class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-[10px] rounded-full flex items-center justify-center font-bold animate-pulse">
                    {{ count($expiringItems) }}
                </span>
            </div>
        @endif

        {{-- Low Stock Alert --}}
        @if(count($lowStockItems) > 0)
            <div class="relative">
                <button class="w-14 h-14 rounded-xl bg-yellow-500 text-white hover:bg-yellow-600 flex flex-col items-center justify-center transition-all">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    <span class="text-[10px] mt-0.5 font-medium">Stock</span>
                </button>
                <span class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-[10px] rounded-full flex items-center justify-center font-bold">
                    {{ count($lowStockItems) }}
                </span>
            </div>
        @endif
    </div>

    {{-- Categories Sidebar --}}
    <div class="w-44 bg-white border-r border-gray-200 flex flex-col">
        <div class="p-3 border-b border-gray-100 bg-emerald-50">
            <h2 class="font-bold text-emerald-800 text-sm myanmar-text">ဆေးအမျိုးအစား</h2>
        </div>
        <div class="flex-1 overflow-y-auto py-2 px-2 space-y-1">
            <button wire:click="selectCategory(null)"
                class="w-full px-3 py-2 rounded-lg text-left text-sm transition-all {{ !$selectedCategory ? 'bg-emerald-50 text-emerald-700 font-semibold border border-emerald-200' : 'text-gray-600 hover:bg-gray-50' }}">
                <span class="myanmar-text">အားလုံး</span>
                <span class="text-xs text-gray-400 ml-1">({{ $items->count() }})</span>
            </button>
            @foreach($categories as $category)
                <button wire:click="selectCategory({{ $category->id }})"
                    class="w-full px-3 py-2 rounded-lg text-left text-sm transition-all {{ $selectedCategory == $category->id ? 'bg-emerald-50 text-emerald-700 font-semibold border border-emerald-200' : 'text-gray-600 hover:bg-gray-50' }}">
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
                <div class="relative flex-1 max-w-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                        </svg>
                    </div>
                    <input type="text" 
                        wire:model="barcodeInput" 
                        wire:keydown.enter="scanBarcode"
                        placeholder="Barcode စကင်ဖတ်ရန်..." 
                        class="w-full pl-10 pr-4 py-3 border-2 border-emerald-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-lg font-mono bg-emerald-50"
                        autofocus>
                </div>

                {{-- Search --}}
                <div class="relative flex-1">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input type="text" wire:model.live="searchTerm" 
                        placeholder="ဆေးဝါးရှာရန်..." 
                        class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm">
                </div>

                {{-- Patient Badge --}}
                @if($patientName)
                    <div class="flex items-center gap-2 bg-emerald-50 px-3 py-2 rounded-xl border border-emerald-200">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <div>
                            <span class="font-medium text-emerald-800 block text-sm">{{ $patientName }}</span>
                            @if($prescriptionNumber)
                                <span class="text-xs text-emerald-600">Rx: {{ $prescriptionNumber }}</span>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Items Grid --}}
        <div class="flex-1 overflow-y-auto p-4">
            <div class="grid grid-cols-4 sm:grid-cols-5 md:grid-cols-6 lg:grid-cols-7 gap-3">
                @forelse($items as $item)
                    <button wire:click="addToCart({{ $item->id }})"
                        class="bg-white rounded-xl border border-gray-100 p-3 hover:shadow-lg hover:border-emerald-300 hover:-translate-y-0.5 transition-all text-left group relative
                        {{ $item->expiry_date && $item->expiry_date->diffInDays(now()) <= 30 ? 'ring-2 ring-orange-300' : '' }}
                        {{ $item->expiry_date && $item->expiry_date->isPast() ? 'opacity-50 ring-2 ring-red-300' : '' }}">
                        
                        {{-- Prescription Badge --}}
                        @if($item->requires_prescription ?? false)
                            <div class="absolute top-1 right-1 bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded">Rx</div>
                        @endif
                        
                        {{-- Stock Badge --}}
                        @if($item->stock_quantity !== null)
                            <div class="absolute top-1 left-1 text-[10px] font-bold px-1.5 py-0.5 rounded
                                {{ $item->stock_quantity <= 0 ? 'bg-red-100 text-red-700' : ($item->stock_quantity <= ($item->reorder_level ?? 10) ? 'bg-yellow-100 text-yellow-700' : 'bg-emerald-100 text-emerald-700') }}">
                                {{ $item->stock_quantity }}
                            </div>
                        @endif
                        
                        <div class="aspect-square bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-lg mb-2 flex items-center justify-center overflow-hidden mt-4">
                            @if($item->image)
                                <img src="{{ asset('storage/' . $item->image) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform">
                            @else
                                <svg class="w-8 h-8 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                                </svg>
                            @endif
                        </div>
                        <h3 class="font-medium text-gray-900 text-xs truncate">{{ $item->name }}</h3>
                        @if($item->generic_name)
                            <p class="text-[10px] text-gray-400 truncate">{{ $item->generic_name }}</p>
                        @endif
                        <p class="text-sm font-bold text-emerald-600 mt-1">{{ number_format($item->price) }} <span class="text-xs font-normal">Ks</span></p>
                        
                        {{-- Expiry Warning --}}
                        @if($item->expiry_date)
                            <p class="text-[10px] {{ $item->expiry_date->diffInDays(now()) <= 30 ? 'text-orange-600 font-medium' : 'text-gray-400' }}">
                                Exp: {{ $item->expiry_date->format('M Y') }}
                            </p>
                        @endif
                    </button>
                @empty
                    <div class="col-span-full text-center py-16 text-gray-400">
                        <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                        </svg>
                        <p class="myanmar-text">ဆေးဝါး မရှိပါ</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Right Sidebar: Cart --}}
    <div class="w-96 bg-white border-l border-gray-200 flex flex-col">
        {{-- Cart Header --}}
        <div class="p-4 border-b border-gray-100 bg-gradient-to-r from-emerald-500 to-emerald-600">
            <div class="flex justify-between items-center">
                <div class="text-white">
                    <h2 class="font-bold text-lg myanmar-text">ဆေးစာရင်း</h2>
                    <p class="text-emerald-100 text-xs">{{ count($cart) }} items</p>
                </div>
                @if(count($cart) > 0)
                    <button wire:click="clearCart" class="text-emerald-200 hover:text-white text-xs font-medium px-3 py-1 rounded-lg bg-white/10 hover:bg-white/20 transition-all">
                        Clear
                    </button>
                @endif
            </div>
        </div>

        {{-- Patient Info Section --}}
        <div class="p-3 border-b border-gray-100 bg-emerald-50">
            <div class="grid grid-cols-2 gap-2">
                <input type="text" wire:model="patientName" placeholder="လူနာအမည်" class="text-sm border border-emerald-200 rounded-lg px-3 py-2 focus:ring-emerald-500 focus:border-emerald-500 bg-white">
                <input type="text" wire:model="patientPhone" placeholder="ဖုန်းနံပါတ်" class="text-sm border border-emerald-200 rounded-lg px-3 py-2 focus:ring-emerald-500 focus:border-emerald-500 bg-white">
            </div>
            @if($hasPrescriptionItems())
                <div class="mt-2 flex gap-2">
                    <input type="text" wire:model="prescriptionNumber" placeholder="Rx နံပါတ် *" class="flex-1 text-sm border border-red-200 rounded-lg px-3 py-2 focus:ring-red-500 focus:border-red-500 bg-red-50">
                    <input type="text" wire:model="doctorName" placeholder="ဆရာဝန်အမည်" class="flex-1 text-sm border border-emerald-200 rounded-lg px-3 py-2 focus:ring-emerald-500 focus:border-emerald-500 bg-white">
                </div>
            @endif
        </div>

        {{-- Cart Items --}}
        <div class="flex-1 overflow-y-auto p-3 space-y-2">
            @forelse($cart as $index => $item)
                <div class="bg-gray-50 rounded-xl p-3 {{ $item['requires_prescription'] ? 'border-l-4 border-red-400' : '' }}">
                    <div class="flex justify-between items-start mb-2">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-1">
                                <h4 class="font-medium text-gray-900 text-sm truncate">{{ $item['name'] }}</h4>
                                @if($item['requires_prescription'])
                                    <span class="text-[10px] bg-red-100 text-red-700 px-1 rounded font-bold">Rx</span>
                                @endif
                            </div>
                            @if($item['generic_name'])
                                <p class="text-[10px] text-gray-400">{{ $item['generic_name'] }}</p>
                            @endif
                            <p class="text-xs text-gray-500">{{ number_format($item['price']) }} Ks</p>
                            @if($item['expiry_date'])
                                <p class="text-[10px] text-orange-500">Exp: {{ $item['expiry_date'] }}</p>
                            @endif
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
                                class="w-8 h-8 flex items-center justify-center text-gray-500 hover:text-emerald-600 hover:bg-emerald-50 rounded-l-lg transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                </svg>
                            </button>
                            <span class="w-10 text-center font-bold text-gray-900">{{ $item['quantity'] }}</span>
                            <button wire:click="updateQuantity({{ $index }}, {{ $item['quantity'] + 1 }})"
                                class="w-8 h-8 flex items-center justify-center text-gray-500 hover:text-emerald-600 hover:bg-emerald-50 rounded-r-lg transition-colors">
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
                    <svg class="w-16 h-16 mx-auto mb-3 text-emerald-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                    </svg>
                    <p class="text-sm myanmar-text">ဆေးဝါး ထည့်ပါ</p>
                </div>
            @endforelse
        </div>

        {{-- Totals --}}
        <div class="border-t border-gray-200 p-4 space-y-2 bg-gray-50">
            <div class="flex justify-between text-sm">
                <span class="text-gray-500 myanmar-text">စုစုပေါင်း</span>
                <span class="font-medium">{{ number_format($subtotal) }} Ks</span>
            </div>
            
            <div class="flex items-center gap-2">
                <span class="text-gray-500 text-sm myanmar-text">လျှော့စျေး</span>
                <input type="number" wire:model.live="discountPercentage" 
                    class="w-16 text-center text-sm border border-gray-200 rounded-lg px-2 py-1 focus:ring-emerald-500 focus:border-emerald-500" min="0" max="100">
                <span class="text-gray-500 text-sm">%</span>
                <span class="ml-auto text-red-500 font-medium">-{{ number_format($discountAmount) }} Ks</span>
            </div>

            <div class="flex justify-between text-xl font-bold border-t border-gray-200 pt-3 mt-3">
                <span class="myanmar-text">ပေးရန်</span>
                <span class="text-emerald-600">{{ number_format($total) }} <span class="text-sm font-normal">Ks</span></span>
            </div>
        </div>

        {{-- Payment Button --}}
        <div class="p-4 border-t border-gray-200">
            <button wire:click="openPaymentModal" 
                class="w-full bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white py-4 rounded-xl font-bold text-lg flex items-center justify-center gap-2 transition-all shadow-lg shadow-emerald-500/30 {{ empty($cart) ? 'opacity-50 cursor-not-allowed' : '' }}"
                {{ empty($cart) ? 'disabled' : '' }}>
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <span class="myanmar-text">ငွေရှင်းမည်</span>
            </button>
        </div>
    </div>

    {{-- Patient Info Modal --}}
    @if($showPatientModal)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-gray-900/80 backdrop-blur-sm" wire:click="$set('showPatientModal', false)"></div>
                <div class="relative bg-white rounded-2xl shadow-2xl max-w-md w-full p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 myanmar-text">လူနာအချက်အလက်</h3>
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1 myanmar-text">အမည်</label>
                                <input type="text" wire:model="patientName" class="w-full border border-gray-200 rounded-lg px-3 py-2 focus:ring-emerald-500 focus:border-emerald-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1 myanmar-text">ဖုန်းနံပါတ်</label>
                                <input type="text" wire:model="patientPhone" class="w-full border border-gray-200 rounded-lg px-3 py-2 focus:ring-emerald-500 focus:border-emerald-500">
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1 myanmar-text">အသက်</label>
                                <input type="text" wire:model="patientAge" class="w-full border border-gray-200 rounded-lg px-3 py-2 focus:ring-emerald-500 focus:border-emerald-500" placeholder="e.g. 35">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Rx နံပါတ်</label>
                                <input type="text" wire:model="prescriptionNumber" class="w-full border border-gray-200 rounded-lg px-3 py-2 focus:ring-emerald-500 focus:border-emerald-500 font-mono">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1 myanmar-text">ဆရာဝန်အမည်</label>
                            <input type="text" wire:model="doctorName" class="w-full border border-gray-200 rounded-lg px-3 py-2 focus:ring-emerald-500 focus:border-emerald-500">
                        </div>
                        <div class="flex gap-3 pt-2">
                            <button wire:click="$set('showPatientModal', false)" class="flex-1 px-4 py-2 border border-gray-200 rounded-xl text-gray-600 hover:bg-gray-50">Cancel</button>
                            <button wire:click="$set('showPatientModal', false)" class="flex-1 px-4 py-2 bg-emerald-500 text-white rounded-xl hover:bg-emerald-600 font-medium">Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Prescription Required Modal --}}
    @if($showPrescriptionModal)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-gray-900/80 backdrop-blur-sm" wire:click="closePrescriptionModal"></div>
                <div class="relative bg-white rounded-2xl shadow-2xl max-w-md w-full p-6">
                    <div class="text-center mb-4">
                        <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <span class="text-2xl font-bold text-red-600">Rx</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 myanmar-text">ဆရာဝန်ညွှန်ကြားချက် လိုအပ်သည်</h3>
                        <p class="text-sm text-gray-500 mt-1 myanmar-text">ဤဆေးဝါးများအတွက် ဆရာဝန်ညွှန်ကြားချက် နံပါတ် ထည့်ပါ</p>
                    </div>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Prescription Number</label>
                            <input type="text" wire:model="prescriptionNumber" class="w-full text-center text-lg font-mono border border-gray-200 rounded-xl px-4 py-3 focus:ring-emerald-500 focus:border-emerald-500" placeholder="RX-XXXXXX">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1 myanmar-text">ဆရာဝန်အမည်</label>
                            <input type="text" wire:model="doctorName" class="w-full border border-gray-200 rounded-xl px-4 py-2 focus:ring-emerald-500 focus:border-emerald-500">
                        </div>

                        <div class="flex gap-3">
                            <button wire:click="closePrescriptionModal" class="flex-1 px-4 py-2 border border-gray-200 rounded-xl text-gray-600 hover:bg-gray-50">Cancel</button>
                            <button wire:click="confirmPrescription" class="flex-1 px-4 py-2 bg-emerald-500 text-white rounded-xl hover:bg-emerald-600 font-medium myanmar-text">အတည်ပြု</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Payment Modal --}}
    @if($showPaymentModal)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-gray-900/80 backdrop-blur-sm" wire:click="closePaymentModal"></div>
                <div class="relative bg-white rounded-2xl shadow-2xl max-w-lg w-full p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-6 myanmar-text">ငွေပေးချေမှု</h3>
                    
                    <div class="space-y-6">
                        {{-- Total Amount --}}
                        <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 rounded-2xl p-6 text-center text-white">
                            <p class="text-emerald-100 text-sm myanmar-text">ပေးရန်ပမာဏ</p>
                            <p class="text-4xl font-bold">{{ number_format($total) }} <span class="text-lg">Ks</span></p>
                        </div>

                        {{-- Payment Methods --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3 myanmar-text">ငွေပေးချေမှုနည်းလမ်း</label>
                            <div class="grid grid-cols-4 gap-2">
                                <button type="button" wire:click="$set('paymentMethod', 'cash')"
                                    class="p-3 rounded-xl border-2 text-center transition-all {{ $paymentMethod === 'cash' ? 'border-emerald-500 bg-emerald-50' : 'border-gray-200 hover:border-gray-300' }}">
                                    <svg class="w-8 h-8 mx-auto mb-1 {{ $paymentMethod === 'cash' ? 'text-emerald-600' : 'text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    <span class="text-xs font-medium">Cash</span>
                                </button>
                                <button type="button" wire:click="$set('paymentMethod', 'kpay')"
                                    class="p-3 rounded-xl border-2 text-center transition-all {{ $paymentMethod === 'kpay' ? 'border-emerald-500 bg-emerald-50' : 'border-gray-200 hover:border-gray-300' }}">
                                    <div class="w-8 h-8 mx-auto mb-1 rounded-lg {{ $paymentMethod === 'kpay' ? 'bg-emerald-600 text-white' : 'bg-gray-200 text-gray-600' }} flex items-center justify-center font-bold text-lg">K</div>
                                    <span class="text-xs font-medium">KPay</span>
                                </button>
                                <button type="button" wire:click="$set('paymentMethod', 'wave')"
                                    class="p-3 rounded-xl border-2 text-center transition-all {{ $paymentMethod === 'wave' ? 'border-emerald-500 bg-emerald-50' : 'border-gray-200 hover:border-gray-300' }}">
                                    <div class="w-8 h-8 mx-auto mb-1 rounded-lg {{ $paymentMethod === 'wave' ? 'bg-yellow-500 text-white' : 'bg-gray-200 text-gray-600' }} flex items-center justify-center font-bold text-lg">W</div>
                                    <span class="text-xs font-medium">Wave</span>
                                </button>
                                <button type="button" wire:click="$set('paymentMethod', 'card')"
                                    class="p-3 rounded-xl border-2 text-center transition-all {{ $paymentMethod === 'card' ? 'border-emerald-500 bg-emerald-50' : 'border-gray-200 hover:border-gray-300' }}">
                                    <svg class="w-8 h-8 mx-auto mb-1 {{ $paymentMethod === 'card' ? 'text-emerald-600' : 'text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                        class="py-2 px-3 rounded-lg border border-gray-200 text-sm font-medium hover:bg-emerald-50 hover:border-emerald-300 transition-all {{ $amountReceived == $amount ? 'bg-emerald-50 border-emerald-300' : '' }}">
                                        {{ number_format($amount) }}
                                    </button>
                                @endforeach
                            </div>

                            {{-- Amount Received --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2 myanmar-text">လက်ခံငွေ</label>
                                <input type="number" wire:model.live="amountReceived" 
                                    class="w-full text-2xl font-bold text-center border border-gray-200 rounded-xl py-4 focus:ring-emerald-500 focus:border-emerald-500">
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
                                class="flex-1 px-6 py-3 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white rounded-xl font-bold hover:from-emerald-600 hover:to-emerald-700 transition-all flex items-center justify-center gap-2">
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
                        <button wire:click="closeSuccessModal" class="flex-1 px-4 py-2 bg-emerald-500 text-white rounded-xl hover:bg-emerald-600 font-medium myanmar-text">
                            ဆက်လုပ်မည်
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
