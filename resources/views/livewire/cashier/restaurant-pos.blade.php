<div class="h-[calc(100vh-65px)] flex bg-gray-50 overflow-hidden" 
    x-data="{ 
        showQuickActions: false,
        init() {
            // Keyboard shortcuts
            document.addEventListener('keydown', (e) => {
                if (e.key === 'F2') { e.preventDefault(); $wire.showPaymentModal = true; }
                if (e.key === 'F3') { e.preventDefault(); $wire.sendToKitchen(); }
                if (e.key === 'F4') { e.preventDefault(); $wire.showTableModal = true; }
                if (e.key === 'Escape') { $wire.showPaymentModal = false; $wire.showTableModal = false; }
            });
        }
    }"
    wire:loading.class="opacity-75 pointer-events-none"
    x-cloak>
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

    {{-- Left Sidebar: Tables Overview --}}
    <div class="w-20 bg-gradient-to-b from-orange-600 to-orange-700 flex flex-col items-center py-4 space-y-4">
        {{-- Logo --}}
        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mb-4">
            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
        </div>

        {{-- Order Type Buttons --}}
        <button wire:click="setOrderType('dine_in')" 
            class="w-14 h-14 rounded-xl flex flex-col items-center justify-center transition-all {{ $orderType === 'dine_in' ? 'bg-white text-orange-600 shadow-lg' : 'bg-white/10 text-white hover:bg-white/20' }}">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
            <span class="text-[10px] mt-1 font-medium">Dine In</span>
        </button>

        <button wire:click="setOrderType('takeaway')" 
            class="w-14 h-14 rounded-xl flex flex-col items-center justify-center transition-all {{ $orderType === 'takeaway' ? 'bg-white text-orange-600 shadow-lg' : 'bg-white/10 text-white hover:bg-white/20' }}">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
            </svg>
            <span class="text-[10px] mt-1 font-medium">Takeaway</span>
        </button>

        <button wire:click="setOrderType('delivery')" 
            class="w-14 h-14 rounded-xl flex flex-col items-center justify-center transition-all {{ $orderType === 'delivery' ? 'bg-white text-orange-600 shadow-lg' : 'bg-white/10 text-white hover:bg-white/20' }}">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"></path>
            </svg>
            <span class="text-[10px] mt-1 font-medium">Delivery</span>
        </button>

        <div class="flex-1"></div>

        {{-- Tables Button --}}
        <button wire:click="$set('showTableModal', true)" 
            class="w-14 h-14 rounded-xl bg-white/10 text-white hover:bg-white/20 flex flex-col items-center justify-center transition-all relative">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
            </svg>
            <span class="text-[10px] mt-1 font-medium">Tables</span>
            @if($tables->where('status', 'available')->count() > 0)
                <span class="absolute -top-1 -right-1 w-5 h-5 bg-green-500 text-white text-[10px] rounded-full flex items-center justify-center font-bold">
                    {{ $tables->where('status', 'available')->count() }}
                </span>
            @endif
        </button>

        {{-- Kitchen Button --}}
        <button wire:click="sendToKitchen" 
            class="w-14 h-14 rounded-xl bg-red-500 text-white hover:bg-red-600 flex flex-col items-center justify-center transition-all">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"></path>
            </svg>
            <span class="text-[10px] mt-1 font-medium">Kitchen</span>
        </button>
    </div>

    {{-- Categories Sidebar --}}
    <div class="w-48 bg-white border-r border-gray-200 flex flex-col">
        <div class="p-4 border-b border-gray-100">
            <h2 class="font-bold text-gray-800 text-sm myanmar-text">အမျိုးအစား</h2>
        </div>
        <div class="flex-1 overflow-y-auto py-2 px-2 space-y-1">
            <button wire:click="selectCategory(null)"
                class="w-full px-3 py-2.5 rounded-xl text-left text-sm transition-all flex items-center gap-2 {{ !$selectedCategory ? 'bg-orange-50 text-orange-700 font-semibold border border-orange-200' : 'text-gray-600 hover:bg-gray-50' }}">
                <span class="w-8 h-8 rounded-lg {{ !$selectedCategory ? 'bg-orange-100' : 'bg-gray-100' }} flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                    </svg>
                </span>
                <div>
                    <span class="myanmar-text">အားလုံး</span>
                    <span class="text-xs text-gray-400 block">{{ $items->count() }} items</span>
                </div>
            </button>
            @foreach($categories as $category)
                <button wire:click="selectCategory({{ $category->id }})"
                    class="w-full px-3 py-2.5 rounded-xl text-left text-sm transition-all flex items-center gap-2 {{ $selectedCategory == $category->id ? 'bg-orange-50 text-orange-700 font-semibold border border-orange-200' : 'text-gray-600 hover:bg-gray-50' }}">
                    <span class="w-8 h-8 rounded-lg {{ $selectedCategory == $category->id ? 'bg-orange-100' : 'bg-gray-100' }} flex items-center justify-center text-xs font-bold">
                        {{ substr($category->name, 0, 2) }}
                    </span>
                    <div class="flex-1 min-w-0">
                        <span class="myanmar-text truncate block">{{ $category->name_mm }}</span>
                        <span class="text-xs text-gray-400">{{ $category->active_items_count }} items</span>
                    </div>
                </button>
            @endforeach
        </div>
    </div>

    {{-- Main Content: Items Grid --}}
    <div class="flex-1 flex flex-col min-w-0">
        {{-- Header --}}
        <div class="bg-white border-b border-gray-200 p-4">
            <div class="flex items-center justify-between gap-4">
                {{-- Selected Table Info --}}
                @if($orderType === 'dine_in')
                    @if($selectedTableData)
                        <div class="flex items-center gap-3 bg-orange-50 px-4 py-2 rounded-xl border border-orange-200">
                            <div class="w-10 h-10 bg-orange-500 text-white rounded-lg flex items-center justify-center font-bold">
                                {{ $selectedTableData->name }}
                            </div>
                            <div>
                                <p class="font-bold text-orange-800">{{ $selectedTableData->name_mm }}</p>
                                <p class="text-xs text-orange-600">{{ $selectedTableData->capacity }} ဦးထိုင်နိုင်</p>
                            </div>
                            <button wire:click="clearTable" class="ml-2 text-orange-400 hover:text-orange-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    @else
                        <button wire:click="$set('showTableModal', true)" class="flex items-center gap-2 bg-gray-100 hover:bg-gray-200 px-4 py-2 rounded-xl transition-all">
                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            <span class="text-sm font-medium text-gray-600 myanmar-text">စားပွဲရွေးရန်</span>
                        </button>
                    @endif
                @elseif($orderType === 'takeaway')
                    <div class="flex items-center gap-2 bg-blue-50 px-4 py-2 rounded-xl border border-blue-200">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                        <span class="font-medium text-blue-800 myanmar-text">ပါဆယ် အော်ဒါ</span>
                    </div>
                @else
                    <div class="flex items-center gap-2 bg-green-50 px-4 py-2 rounded-xl border border-green-200">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"></path>
                        </svg>
                        <span class="font-medium text-green-800 myanmar-text">Delivery အော်ဒါ</span>
                    </div>
                @endif

                {{-- Search --}}
                <div class="relative flex-1 max-w-md">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input type="text" wire:model.live="searchTerm" 
                        placeholder="ပစ္စည်းရှာရန်..." 
                        class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-sm">
                </div>

                {{-- Guest Count (Dine In) --}}
                @if($orderType === 'dine_in')
                    <div class="flex items-center gap-2 bg-gray-100 rounded-xl px-3 py-1">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <input type="number" wire:model="guestCount" min="1" max="50" class="w-12 text-center border-0 bg-transparent text-sm font-medium focus:ring-0">
                        <span class="text-xs text-gray-500">ဦး</span>
                    </div>
                @endif
            </div>
        </div>

        {{-- Items Grid --}}
        <div class="flex-1 overflow-y-auto p-4 relative">
            {{-- Loading Overlay --}}
            <div wire:loading.flex wire:target="selectCategory, searchTerm, addToCart" class="absolute inset-0 bg-white/60 z-10 items-center justify-center">
                <div class="flex flex-col items-center">
                    <svg class="animate-spin h-8 w-8 text-orange-500" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-sm text-gray-500 mt-2">Loading...</span>
                </div>
            </div>
            
            <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-6 gap-3" wire:loading.class="opacity-50" wire:target="selectCategory, searchTerm">
                @forelse($items as $item)
                    @php
                        $inCart = collect($cart)->where('item_id', $item->id)->first();
                        $cartQty = $inCart ? $inCart['quantity'] : 0;
                    @endphp
                    <button wire:click="addToCart({{ $item->id }})"
                        wire:loading.attr="disabled"
                        wire:target="addToCart({{ $item->id }})"
                        class="bg-white rounded-xl border-2 p-3 hover:shadow-lg hover:-translate-y-0.5 transition-all text-left group relative
                        {{ $inCart ? 'border-orange-400 bg-orange-50' : 'border-gray-100 hover:border-orange-300' }}">
                        
                        {{-- Cart Badge --}}
                        @if($cartQty > 0)
                            <div class="absolute -top-2 -right-2 w-6 h-6 bg-orange-500 text-white rounded-full flex items-center justify-center text-xs font-bold shadow-lg z-10">
                                {{ $cartQty }}
                            </div>
                        @endif
                        
                        <div class="aspect-square bg-gradient-to-br from-orange-50 to-orange-100 rounded-lg mb-2 flex items-center justify-center overflow-hidden">
                            @if($item->image)
                                <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300" loading="lazy">
                            @else
                                <svg class="w-10 h-10 text-orange-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                            @endif
                        </div>
                        <h3 class="font-medium text-gray-900 text-xs truncate">{{ $item->name }}</h3>
                        <p class="text-xs text-gray-500 myanmar-text truncate">{{ $item->name_mm }}</p>
                        <p class="text-sm font-bold text-orange-600 mt-1">{{ number_format($item->price) }} <span class="text-xs font-normal">Ks</span></p>
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
        
        {{-- Keyboard Shortcuts Help --}}
        <div class="bg-gray-100 border-t border-gray-200 px-4 py-2 flex items-center justify-center gap-6 text-xs text-gray-500">
            <span><kbd class="px-1.5 py-0.5 bg-white rounded border text-gray-700 font-mono">F2</kbd> Payment</span>
            <span><kbd class="px-1.5 py-0.5 bg-white rounded border text-gray-700 font-mono">F3</kbd> Kitchen</span>
            <span><kbd class="px-1.5 py-0.5 bg-white rounded border text-gray-700 font-mono">F4</kbd> Tables</span>
            <span><kbd class="px-1.5 py-0.5 bg-white rounded border text-gray-700 font-mono">ESC</kbd> Close</span>
        </div>
    </div>

    {{-- Right Sidebar: Cart --}}
    <div class="w-96 bg-white border-l border-gray-200 flex flex-col">
        {{-- Cart Header --}}
        <div class="p-4 border-b border-gray-100 bg-gradient-to-r from-orange-500 to-orange-600">
            <div class="flex justify-between items-center">
                <div class="text-white">
                    <h2 class="font-bold text-lg myanmar-text">အော်ဒါစာရင်း</h2>
                    <p class="text-orange-100 text-xs">{{ count($cart) }} items</p>
                </div>
                @if(count($cart) > 0)
                    <button wire:click="clearCart" class="text-orange-200 hover:text-white text-xs font-medium px-3 py-1 rounded-lg bg-white/10 hover:bg-white/20 transition-all">
                        Clear All
                    </button>
                @endif
            </div>
        </div>

        {{-- Customer Info (Takeaway/Delivery) --}}
        @if($orderType !== 'dine_in')
            <div class="p-3 border-b border-gray-100 bg-gray-50 space-y-2">
                <div class="grid grid-cols-2 gap-2">
                    <input type="text" wire:model="customerName" placeholder="အမည်" class="text-sm border border-gray-200 rounded-lg px-3 py-2 focus:ring-orange-500 focus:border-orange-500">
                    <input type="text" wire:model="customerPhone" placeholder="ဖုန်းနံပါတ်" class="text-sm border border-gray-200 rounded-lg px-3 py-2 focus:ring-orange-500 focus:border-orange-500">
                </div>
                @if($orderType === 'delivery')
                    <textarea wire:model="deliveryAddress" placeholder="ပို့ဆောင်ရမည့်လိပ်စာ" rows="2" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 focus:ring-orange-500 focus:border-orange-500"></textarea>
                @endif
            </div>
        @endif

        {{-- Cart Items --}}
        <div class="flex-1 overflow-y-auto p-3 space-y-2">
            @forelse($cart as $index => $item)
                <div class="bg-gray-50 rounded-xl p-3 {{ $item['sent_to_kitchen'] ? 'border-l-4 border-green-500' : '' }}">
                    <div class="flex justify-between items-start mb-2">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2">
                                <h4 class="font-medium text-gray-900 text-sm truncate">{{ $item['name'] }}</h4>
                                @if($item['sent_to_kitchen'])
                                    <span class="text-[10px] bg-green-100 text-green-700 px-1.5 py-0.5 rounded">Sent</span>
                                @endif
                            </div>
                            <p class="text-xs text-gray-500">{{ number_format($item['price']) }} Ks</p>
                            @if(!empty($item['notes']))
                                <p class="text-xs text-orange-600 mt-1 italic">📝 {{ $item['notes'] }}</p>
                            @endif
                        </div>
                        <div class="flex items-center gap-1">
                            <button wire:click="openKitchenNoteModal({{ $index }})" class="text-gray-400 hover:text-orange-500 p-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </button>
                            <button wire:click="removeFromCart({{ $index }})" class="text-gray-400 hover:text-red-500 p-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-1 bg-white rounded-lg border border-gray-200">
                            <button wire:click="updateQuantity({{ $index }}, {{ $item['quantity'] - 1 }})"
                                class="w-8 h-8 flex items-center justify-center text-gray-500 hover:text-orange-600 hover:bg-orange-50 rounded-l-lg transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                </svg>
                            </button>
                            <span class="w-10 text-center font-bold text-gray-900">{{ $item['quantity'] }}</span>
                            <button wire:click="updateQuantity({{ $index }}, {{ $item['quantity'] + 1 }})"
                                class="w-8 h-8 flex items-center justify-center text-gray-500 hover:text-orange-600 hover:bg-orange-50 rounded-r-lg transition-colors">
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <p class="text-sm myanmar-text">ပစ္စည်းထည့်ပါ</p>
                </div>
            @endforelse
        </div>

        {{-- Totals --}}
        <div class="border-t border-gray-200 p-4 space-y-2 bg-gray-50">
            <div class="flex justify-between text-sm">
                <span class="text-gray-500 myanmar-text">စုစုပေါင်း</span>
                <span class="font-medium">{{ number_format($subtotal) }} Ks</span>
            </div>
            
            @if($orderType === 'dine_in' && $serviceChargePercentage > 0)
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Service ({{ $serviceChargePercentage }}%)</span>
                    <span class="font-medium">{{ number_format($serviceChargeAmount) }} Ks</span>
                </div>
            @endif
            
            @if($taxPercentage > 0)
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Tax ({{ $taxPercentage }}%)</span>
                    <span class="font-medium">{{ number_format($taxAmount) }} Ks</span>
                </div>
            @endif
            
            <div class="flex items-center gap-2">
                <span class="text-gray-500 text-sm myanmar-text">လျှော့စျေး</span>
                <input type="number" wire:model.live="discountPercentage" 
                    class="w-16 text-center text-sm border border-gray-200 rounded-lg px-2 py-1 focus:ring-orange-500 focus:border-orange-500" min="0" max="100">
                <span class="text-gray-500 text-sm">%</span>
                <span class="ml-auto text-red-500 font-medium">-{{ number_format($discountAmount) }} Ks</span>
            </div>

            <div class="flex justify-between text-xl font-bold border-t border-gray-200 pt-3 mt-3">
                <span class="myanmar-text">ပေးရန်</span>
                <span class="text-orange-600">{{ number_format($total) }} <span class="text-sm font-normal">Ks</span></span>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="p-4 border-t border-gray-200 space-y-2">
            @if(count($cart) > 0 && $orderType === 'dine_in')
                <button wire:click="sendToKitchen" 
                    class="w-full bg-red-500 hover:bg-red-600 text-white py-3 rounded-xl font-bold flex items-center justify-center gap-2 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"></path>
                    </svg>
                    <span class="myanmar-text">မီးဖိုချောင်သို့ပို့မည်</span>
                </button>
            @endif
            
            <button wire:click="openPaymentModal" 
                class="w-full bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white py-4 rounded-xl font-bold text-lg flex items-center justify-center gap-2 transition-all shadow-lg shadow-orange-500/30 {{ empty($cart) ? 'opacity-50 cursor-not-allowed' : '' }}"
                {{ empty($cart) ? 'disabled' : '' }}>
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <span class="myanmar-text">ငွေရှင်းမည်</span>
            </button>
        </div>
    </div>

    {{-- Table Selection Modal --}}
    @if($showTableModal)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-gray-900/80 backdrop-blur-sm" wire:click="$set('showTableModal', false)"></div>
                <div class="relative bg-white rounded-2xl shadow-2xl max-w-5xl w-full p-6 max-h-[85vh] overflow-hidden flex flex-col">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-bold text-gray-900 myanmar-text">စားပွဲရွေးချယ်ရန်</h3>
                        <div class="flex items-center gap-3">
                            {{-- View Mode Toggle --}}
                            <div class="flex bg-gray-100 rounded-lg p-1">
                                <button wire:click="$set('tableViewMode', 'grid')" 
                                    class="p-2 rounded-md transition-all {{ $tableViewMode === 'grid' ? 'bg-white shadow text-gray-900' : 'text-gray-500 hover:text-gray-700' }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                                    </svg>
                                </button>
                                <button wire:click="$set('tableViewMode', 'layout')" 
                                    class="p-2 rounded-md transition-all {{ $tableViewMode === 'layout' ? 'bg-white shadow text-gray-900' : 'text-gray-500 hover:text-gray-700' }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"></path>
                                    </svg>
                                </button>
                            </div>
                            <button wire:click="$set('showTableModal', false)" class="text-gray-400 hover:text-gray-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    
                    {{-- Section Tabs --}}
                    @if($sections && count($sections) > 0)
                        <div class="flex gap-2 mb-4 overflow-x-auto pb-2">
                            @foreach($sections as $section)
                                <button wire:click="selectSection({{ $section->id }})"
                                    class="px-4 py-2 rounded-lg text-sm font-medium whitespace-nowrap transition-all {{ $selectedSection === $section->id ? 'bg-orange-500 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                                    {{ $section->name }}
                                    <span class="ml-1 text-xs opacity-75">({{ $section->tables->where('status', 'available')->count() }}/{{ $section->tables->count() }})</span>
                                </button>
                            @endforeach
                        </div>
                    @endif
                    
                    {{-- Filter Tabs --}}
                    <div class="flex gap-2 mb-4">
                        <button wire:click="$set('tableFilter', 'all')" class="px-4 py-2 rounded-lg text-sm font-medium transition-all {{ $tableFilter === 'all' ? 'bg-orange-500 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                            All Tables
                        </button>
                        <button wire:click="$set('tableFilter', 'available')" class="px-4 py-2 rounded-lg text-sm font-medium transition-all {{ $tableFilter === 'available' ? 'bg-green-500 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                            Available ({{ $tables->where('status', 'available')->count() }})
                        </button>
                        <button wire:click="$set('tableFilter', 'occupied')" class="px-4 py-2 rounded-lg text-sm font-medium transition-all {{ $tableFilter === 'occupied' ? 'bg-red-500 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                            Occupied ({{ $tables->where('status', 'occupied')->count() }})
                        </button>
                    </div>
                    
                    {{-- Tables View --}}
                    <div class="flex-1 overflow-y-auto">
                        @if($tableViewMode === 'grid')
                            {{-- Grid View --}}
                            <div class="grid grid-cols-4 sm:grid-cols-5 md:grid-cols-6 gap-3">
                                @forelse($tables as $table)
                                    <button wire:click="selectTable({{ $table->id }})"
                                        class="aspect-square rounded-xl border-2 p-3 flex flex-col items-center justify-center transition-all hover:shadow-lg
                                        {{ $table->status === 'available' ? 'border-green-300 bg-green-50 hover:border-green-500' : '' }}
                                        {{ $table->status === 'occupied' ? 'border-red-300 bg-red-50 hover:border-red-500' : '' }}
                                        {{ $table->status === 'reserved' ? 'border-yellow-300 bg-yellow-50 hover:border-yellow-500' : '' }}">
                                        <div class="w-10 h-10 {{ $table->shape === 'round' ? 'rounded-full' : 'rounded-lg' }} flex items-center justify-center mb-1 font-bold text-lg
                                            {{ $table->status === 'available' ? 'bg-green-500 text-white' : '' }}
                                            {{ $table->status === 'occupied' ? 'bg-red-500 text-white' : '' }}
                                            {{ $table->status === 'reserved' ? 'bg-yellow-500 text-white' : '' }}">
                                            {{ $table->name }}
                                        </div>
                                        <span class="text-xs text-gray-600 myanmar-text">{{ $table->name_mm }}</span>
                                        <span class="text-[10px] text-gray-400">{{ $table->capacity }} ဦး</span>
                                        @if($table->occupied_at)
                                            <span class="text-[10px] text-orange-500 mt-1">{{ $table->occupied_at->diffForHumans(null, true) }}</span>
                                        @endif
                                    </button>
                                @empty
                                    <div class="col-span-full text-center py-8 text-gray-400">
                                        <p class="myanmar-text">စားပွဲ မရှိပါ</p>
                                    </div>
                                @endforelse
                            </div>
                        @else
                            {{-- Layout View --}}
                            <div class="bg-gray-800 rounded-2xl p-4 min-h-[400px] relative overflow-auto">
                                <div class="absolute inset-0 opacity-10" style="background-image: repeating-linear-gradient(0deg, transparent, transparent 39px, #fff 39px, #fff 40px), repeating-linear-gradient(90deg, transparent, transparent 39px, #fff 39px, #fff 40px);"></div>
                                
                                {{-- Layout Elements (Barriers, Labels) --}}
                                @foreach($layoutElements as $element)
                                    <div class="absolute"
                                        style="left: {{ $element->position_x }}px; top: {{ $element->position_y }}px; width: {{ $element->width }}px; height: {{ $element->height }}px;">
                                        @if($element->type === 'barrier')
                                            <div class="w-full h-full rounded flex items-center justify-center text-white text-xs font-medium"
                                                style="background-color: {{ $element->color }};">
                                                {{ $element->name }}
                                            </div>
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-gray-400 text-xs">
                                                {{ $element->name }}
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                                
                                {{-- Tables --}}
                                @foreach($tables as $table)
                                    <button wire:click="selectTable({{ $table->id }})"
                                        class="absolute cursor-pointer transition-all hover:scale-105 hover:z-10"
                                        style="left: {{ $table->position_x ?? 50 + ($loop->index * 100) }}px; top: {{ $table->position_y ?? 50 }}px;">
                                        <div class="{{ $table->shape === 'round' ? 'rounded-full' : 'rounded-xl' }} flex flex-col items-center justify-center text-center shadow-lg
                                            {{ $table->status === 'available' ? 'bg-gray-600 border-2 border-gray-500 text-white' : '' }}
                                            {{ $table->status === 'occupied' ? 'bg-green-500 border-2 border-green-400 text-white' : '' }}
                                            {{ $table->status === 'reserved' ? 'bg-blue-500 border-2 border-blue-400 text-white' : '' }}"
                                            style="width: {{ $table->width ?? 80 }}px; height: {{ $table->height ?? 80 }}px;">
                                            <span class="font-bold text-sm">{{ $table->name }}</span>
                                            <div class="flex items-center gap-1 text-xs opacity-75">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                                </svg>
                                                {{ $table->capacity }}
                                            </div>
                                            @if($table->status === 'occupied' && $table->occupied_at)
                                                <span class="text-[10px] mt-1 opacity-75">{{ $table->occupied_at->diffForHumans(null, true) }}</span>
                                            @endif
                                        </div>
                                    </button>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Kitchen Note Modal --}}
    @if($showKitchenNoteModal)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-gray-900/80 backdrop-blur-sm" wire:click="$set('showKitchenNoteModal', false)"></div>
                <div class="relative bg-white rounded-2xl shadow-2xl max-w-md w-full p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 myanmar-text">မီးဖိုချောင် မှတ်ချက်</h3>
                    <textarea wire:model="kitchenNote" rows="3" 
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-orange-500 focus:border-orange-500"
                        placeholder="ဥပမာ: အစပ်လျှော့ပါ, ဟင်းသီးဟင်းရွက်မပါပါနဲ့..."></textarea>
                    <div class="flex gap-3 mt-4">
                        <button wire:click="$set('showKitchenNoteModal', false)" class="flex-1 px-4 py-2 border border-gray-200 rounded-xl text-gray-600 hover:bg-gray-50">Cancel</button>
                        <button wire:click="saveKitchenNote" class="flex-1 px-4 py-2 bg-orange-500 text-white rounded-xl hover:bg-orange-600">Save</button>
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
                        <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-2xl p-6 text-center text-white">
                            <p class="text-orange-100 text-sm myanmar-text">ပေးရန်ပမာဏ</p>
                            <p class="text-4xl font-bold">{{ number_format($total) }} <span class="text-lg">Ks</span></p>
                        </div>

                        {{-- Payment Methods --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3 myanmar-text">ငွေပေးချေမှုနည်းလမ်း</label>
                            <div class="grid grid-cols-4 gap-2">
                                <button type="button" wire:click="$set('paymentMethod', 'cash')"
                                    class="p-3 rounded-xl border-2 text-center transition-all {{ $paymentMethod === 'cash' ? 'border-orange-500 bg-orange-50' : 'border-gray-200 hover:border-gray-300' }}">
                                    <svg class="w-8 h-8 mx-auto mb-1 {{ $paymentMethod === 'cash' ? 'text-orange-600' : 'text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    <span class="text-xs font-medium">Cash</span>
                                </button>
                                <button type="button" wire:click="$set('paymentMethod', 'kpay')"
                                    class="p-3 rounded-xl border-2 text-center transition-all {{ $paymentMethod === 'kpay' ? 'border-orange-500 bg-orange-50' : 'border-gray-200 hover:border-gray-300' }}">
                                    <div class="w-8 h-8 mx-auto mb-1 rounded-lg {{ $paymentMethod === 'kpay' ? 'bg-orange-600 text-white' : 'bg-gray-200 text-gray-600' }} flex items-center justify-center font-bold text-lg">K</div>
                                    <span class="text-xs font-medium">KPay</span>
                                </button>
                                <button type="button" wire:click="$set('paymentMethod', 'wave')"
                                    class="p-3 rounded-xl border-2 text-center transition-all {{ $paymentMethod === 'wave' ? 'border-orange-500 bg-orange-50' : 'border-gray-200 hover:border-gray-300' }}">
                                    <div class="w-8 h-8 mx-auto mb-1 rounded-lg {{ $paymentMethod === 'wave' ? 'bg-yellow-500 text-white' : 'bg-gray-200 text-gray-600' }} flex items-center justify-center font-bold text-lg">W</div>
                                    <span class="text-xs font-medium">Wave</span>
                                </button>
                                <button type="button" wire:click="$set('paymentMethod', 'card')"
                                    class="p-3 rounded-xl border-2 text-center transition-all {{ $paymentMethod === 'card' ? 'border-orange-500 bg-orange-50' : 'border-gray-200 hover:border-gray-300' }}">
                                    <svg class="w-8 h-8 mx-auto mb-1 {{ $paymentMethod === 'card' ? 'text-orange-600' : 'text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                        class="py-2 px-3 rounded-lg border border-gray-200 text-sm font-medium hover:bg-orange-50 hover:border-orange-300 transition-all {{ $amountReceived == $amount ? 'bg-orange-50 border-orange-300' : '' }}">
                                        {{ number_format($amount) }}
                                    </button>
                                @endforeach
                            </div>

                            {{-- Amount Received --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2 myanmar-text">လက်ခံငွေ</label>
                                <input type="number" wire:model.live="amountReceived" 
                                    class="w-full text-2xl font-bold text-center border border-gray-200 rounded-xl py-4 focus:ring-orange-500 focus:border-orange-500">
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
                                class="flex-1 px-6 py-3 bg-gradient-to-r from-orange-500 to-orange-600 text-white rounded-xl font-bold hover:from-orange-600 hover:to-orange-700 transition-all flex items-center justify-center gap-2">
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
                        <button wire:click="closeSuccessModal" class="flex-1 px-4 py-2 bg-orange-500 text-white rounded-xl hover:bg-orange-600 font-medium myanmar-text">
                            အော်ဒါအသစ်
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
