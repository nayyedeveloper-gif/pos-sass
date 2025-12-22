<div class="h-[calc(100vh-65px)] flex bg-gray-50 overflow-hidden"
    x-data="{
        init() {
            document.addEventListener('keydown', (e) => {
                if (e.key === 'F3') { e.preventDefault(); $wire.sendToKitchen(); }
                if (e.key === 'Escape') { window.history.back(); }
            });
        }
    }"
    wire:loading.class="opacity-75"
    x-cloak>
    {{-- Left Sidebar: Order Type & Quick Actions --}}
    <div class="w-20 bg-gradient-to-b from-orange-600 to-orange-700 flex flex-col items-center py-4 space-y-4">
        {{-- Logo --}}
        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mb-4">
            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
        </div>

        {{-- Order Type Buttons --}}
        <button wire:click="$set('orderType', 'dine_in')" 
            class="w-14 h-14 rounded-xl flex flex-col items-center justify-center transition-all {{ $orderType === 'dine_in' ? 'bg-white text-orange-600 shadow-lg' : 'bg-white/10 text-white hover:bg-white/20' }}">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
            <span class="text-[10px] mt-1 font-medium">Dine In</span>
        </button>

        <button wire:click="$set('orderType', 'takeaway')" 
            class="w-14 h-14 rounded-xl flex flex-col items-center justify-center transition-all {{ $orderType === 'takeaway' ? 'bg-white text-orange-600 shadow-lg' : 'bg-white/10 text-white hover:bg-white/20' }}">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
            </svg>
            <span class="text-[10px] mt-1 font-medium">Takeaway</span>
        </button>

        <div class="flex-1"></div>

        {{-- Back Button --}}
        <a href="{{ route('waiter.tables.index') }}" 
            class="w-14 h-14 rounded-xl bg-white/10 text-white hover:bg-white/20 flex flex-col items-center justify-center transition-all">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            <span class="text-[10px] mt-1 font-medium">Back</span>
        </a>

        {{-- Send to Kitchen --}}
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
                class="w-full px-3 py-2.5 rounded-lg text-left text-sm font-medium transition-all {{ !$selectedCategory ? 'bg-orange-500 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                <span class="myanmar-text">အားလုံး</span>
            </button>
            @foreach($categories as $category)
                <button wire:click="selectCategory({{ $category->id }})"
                    class="w-full px-3 py-2.5 rounded-lg text-left text-sm font-medium transition-all flex items-center justify-between {{ $selectedCategory == $category->id ? 'bg-orange-500 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                    <span class="myanmar-text truncate">{{ $category->name_mm ?? $category->name }}</span>
                    <span class="text-xs {{ $selectedCategory == $category->id ? 'bg-white/20' : 'bg-gray-100' }} px-1.5 py-0.5 rounded-full">{{ $category->active_items_count ?? 0 }}</span>
                </button>
            @endforeach
        </div>
    </div>

    {{-- Main Content: Items Grid --}}
    <div class="flex-1 flex flex-col overflow-hidden">
        {{-- Header --}}
        <div class="bg-white border-b border-gray-200 px-4 py-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    @if($table)
                        <div class="w-10 h-10 bg-orange-100 rounded-xl flex items-center justify-center">
                            <span class="font-bold text-orange-600">{{ $table->name }}</span>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900 myanmar-text">{{ $table->name_mm ?? $table->name }}</h3>
                            <p class="text-xs text-gray-500">{{ $table->capacity }} ဦးဆံ့</p>
                        </div>
                    @else
                        <div class="w-10 h-10 bg-orange-100 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900 myanmar-text">ပါဆယ်ယူမည်</h3>
                            <p class="text-xs text-gray-500">Takeaway Order</p>
                        </div>
                    @endif
                    
                    @if($isEditMode)
                        <span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs font-medium rounded-lg">
                            Editing #{{ $existingOrder->order_number }}
                        </span>
                    @endif
                </div>
                
                {{-- Search --}}
                <div class="relative w-64">
                    <input type="text" wire:model.live.debounce.300ms="searchTerm" 
                        placeholder="ရှာဖွေရန်..."
                        class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-xl text-sm focus:ring-orange-500 focus:border-orange-500">
                    <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
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
                </div>
            </div>
            
            <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-6 gap-3" wire:loading.class="opacity-50" wire:target="selectCategory, searchTerm">
                @forelse($items as $item)
                    @php
                        $cartKey = 'item_' . $item->id;
                        $inCart = isset($cart[$cartKey]);
                        $cartQty = $inCart ? $cart[$cartKey]['quantity'] : 0;
                    @endphp
                    <button wire:click="addToCart({{ $item->id }})"
                        wire:loading.attr="disabled"
                        class="bg-white rounded-xl p-3 border-2 transition-all hover:shadow-lg group relative {{ $inCart ? 'border-orange-400 bg-orange-50' : 'border-gray-100 hover:border-orange-200' }}">
                        
                        @if($inCart)
                            <div class="absolute -top-2 -right-2 w-6 h-6 bg-orange-500 text-white rounded-full flex items-center justify-center text-xs font-bold shadow-lg">
                                {{ $cartQty }}
                            </div>
                        @endif
                        
                        <div class="aspect-square mb-2 rounded-lg overflow-hidden bg-gray-100 flex items-center justify-center">
                            @if($item->image)
                                <img src="{{ Storage::url($item->image) }}" alt="{{ $item->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform">
                            @else
                                <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            @endif
                        </div>
                        
                        <h4 class="font-medium text-gray-900 text-xs truncate">{{ $item->name }}</h4>
                        <p class="text-[10px] text-gray-500 myanmar-text truncate">{{ $item->name_mm }}</p>
                        <p class="text-orange-600 font-bold text-sm mt-1">{{ number_format($item->price) }} <span class="text-[10px] text-gray-400 font-normal">Ks</span></p>
                    </button>
                @empty
                    <div class="col-span-full flex flex-col items-center justify-center py-12 text-gray-400">
                        <svg class="w-16 h-16 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                        </svg>
                        <p class="myanmar-text">ပစ္စည်း မရှိပါ</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Right Sidebar: Cart --}}
    <div class="w-80 bg-white border-l border-gray-200 flex flex-col">
        {{-- Cart Header --}}
        <div class="p-4 border-b border-gray-100">
            <div class="flex items-center justify-between">
                <h3 class="font-bold text-gray-900 myanmar-text">အော်ဒါစာရင်း</h3>
                <span class="text-sm text-gray-500">{{ count($cart) }} items</span>
            </div>
        </div>

        {{-- Cart Items --}}
        <div class="flex-1 overflow-y-auto p-4 space-y-3">
            @forelse($cart as $key => $item)
                <div class="bg-gray-50 rounded-xl p-3">
                    <div class="flex items-start justify-between mb-2">
                        <div class="flex-1 min-w-0">
                            <h4 class="font-medium text-gray-900 text-sm truncate">{{ $item['name'] }}</h4>
                            <p class="text-xs text-gray-500 myanmar-text truncate">{{ $item['name_mm'] ?? '' }}</p>
                        </div>
                        <button wire:click="removeFromCart('{{ $key }}')" class="text-red-400 hover:text-red-600 p-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <button wire:click="updateQuantity('{{ $key }}', {{ $item['quantity'] - 1 }})"
                                class="w-7 h-7 rounded-lg bg-white border border-gray-200 flex items-center justify-center text-gray-600 hover:bg-gray-100">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                </svg>
                            </button>
                            <span class="w-8 text-center font-bold text-gray-900">{{ $item['quantity'] }}</span>
                            <button wire:click="updateQuantity('{{ $key }}', {{ $item['quantity'] + 1 }})"
                                class="w-7 h-7 rounded-lg bg-orange-500 text-white flex items-center justify-center hover:bg-orange-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                            </button>
                        </div>
                        <p class="font-bold text-orange-600">{{ number_format($item['subtotal']) }} Ks</p>
                    </div>
                    
                    {{-- Item Note --}}
                    <div class="mt-2">
                        <input type="text" wire:model.blur="cart.{{ $key }}.notes" 
                            placeholder="မှတ်ချက်..."
                            class="w-full text-xs border border-gray-200 rounded-lg px-2 py-1.5 focus:ring-orange-500 focus:border-orange-500">
                    </div>
                </div>
            @empty
                <div class="flex flex-col items-center justify-center py-12 text-gray-400">
                    <svg class="w-16 h-16 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <p class="myanmar-text text-sm">ပစ္စည်းထည့်ပါ</p>
                </div>
            @endforelse
        </div>

        {{-- Order Notes --}}
        <div class="px-4 pb-2">
            <textarea wire:model="notes" rows="2" 
                placeholder="အော်ဒါ မှတ်ချက်..."
                class="w-full text-sm border border-gray-200 rounded-xl px-3 py-2 focus:ring-orange-500 focus:border-orange-500 resize-none"></textarea>
        </div>

        {{-- Cart Summary --}}
        <div class="p-4 border-t border-gray-100 bg-gray-50">
            <div class="space-y-2 mb-4">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500 myanmar-text">စုစုပေါင်း</span>
                    <span class="font-bold text-gray-900">{{ number_format($this->getSubtotal()) }} Ks</span>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="space-y-2">
                <button wire:click="sendToKitchen" 
                    class="w-full py-3 bg-gradient-to-r from-orange-500 to-orange-600 text-white rounded-xl font-bold hover:from-orange-600 hover:to-orange-700 transition-all flex items-center justify-center gap-2 disabled:opacity-50"
                    {{ empty($cart) ? 'disabled' : '' }}>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"></path>
                    </svg>
                    <span class="myanmar-text">မီးဖိုချောင်သို့ ပို့မည်</span>
                </button>
                
                @if(!empty($cart))
                    <button wire:click="clearCart" 
                        class="w-full py-2 border border-gray-200 text-gray-600 rounded-xl font-medium hover:bg-gray-100 transition-all myanmar-text">
                        ရှင်းလင်းမည်
                    </button>
                @endif
            </div>
        </div>
    </div>

    {{-- Success Modal --}}
    @if(session('success'))
        <div class="fixed inset-0 z-50 flex items-center justify-center" x-data x-init="setTimeout(() => $el.remove(), 3000)">
            <div class="fixed inset-0 bg-gray-900/50"></div>
            <div class="relative bg-white rounded-2xl shadow-2xl p-8 text-center max-w-sm">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">အောင်မြင်ပါသည်!</h3>
                <p class="text-gray-500 myanmar-text">{{ session('success') }}</p>
            </div>
        </div>
    @endif
</div>
