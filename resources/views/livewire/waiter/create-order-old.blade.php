<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm sticky top-0 z-20 border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 py-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <a href="{{ route('waiter.tables.index') }}" class="p-2 rounded-xl hover:bg-gray-100 text-gray-500 hover:text-gray-700 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                    </a>
                    <div>
                        <h2 class="text-lg font-bold text-gray-900 myanmar-text flex items-center gap-2">
                            @if($table)
                                <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                {{ $table->name_mm ?? $table->name }}
                            @else
                                <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                                ပါဆယ်ယူမည်
                            @endif
                        </h2>
                        <p class="text-xs text-gray-500 myanmar-text mt-0.5">
                            @if($isEditMode)
                                အော်ဒါ #{{ $existingOrder->order_number }} ကို ပြင်ဆင်နေသည်
                            @else
                                {{ now()->format('h:i A • d M Y') }}
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 py-6">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- Left Side: Menu Items -->
            <div class="lg:col-span-8 space-y-6">
                <!-- Search & Filter Bar -->
                <div class="flex gap-4 sticky top-20 z-10 bg-gray-50 py-2 -my-2">
                    <div class="relative flex-1">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input 
                            type="text" 
                            wire:model.live="searchTerm" 
                            placeholder="ရှာဖွေရန်..."
                            class="w-full pl-10 pr-4 py-2.5 border-gray-200 shadow-sm rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 myanmar-text text-sm"
                        >
                    </div>
                </div>

                <!-- Categories -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-2">
                    <div class="flex overflow-x-auto space-x-2 pb-1 hide-scrollbar">
                        @foreach($categories as $category)
                            <button 
                                wire:key="category-{{ $category->id }}"
                                wire:click="selectCategory({{ $category->id }})"
                                class="flex-shrink-0 px-4 py-2 rounded-lg text-sm font-medium transition-all whitespace-nowrap flex items-center gap-2 {{ $selectedCategory == $category->id ? 'bg-primary-600 text-white shadow-md' : 'bg-gray-50 text-gray-600 hover:bg-gray-100' }}"
                            >
                                <span class="myanmar-text">{{ $category->name_mm }}</span>
                                <span class="text-xs opacity-75 bg-black/10 px-1.5 py-0.5 rounded-full">{{ $category->active_items_count }}</span>
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- Items Grid -->
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                    @forelse($items as $item)
                        <button 
                            wire:key="item-{{ $item->id }}"
                            wire:click="addToCart({{ $item->id }})"
                            class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 hover:shadow-md hover:border-primary-200 transition-all group text-left relative overflow-hidden h-full flex flex-col"
                        >
                            <div class="mb-3 flex justify-center">
                                @if($item->image)
                                    <img src="{{ Storage::url($item->image) }}" alt="{{ $item->name }}" class="h-20 w-20 object-cover rounded-lg shadow-sm group-hover:scale-105 transition-transform duration-300">
                                @else
                                    <div class="w-16 h-16 bg-primary-50 rounded-full flex items-center justify-center group-hover:bg-primary-100 transition-colors">
                                        <svg class="w-8 h-8 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="flex-1">
                                <h3 class="font-bold text-gray-900 text-sm mb-1 line-clamp-1 group-hover:text-primary-700">{{ $item->name }}</h3>
                                <p class="text-xs text-gray-500 myanmar-text mb-2 line-clamp-1">{{ $item->name_mm }}</p>
                            </div>
                            
                            <div class="mt-auto flex items-center justify-between pt-2 border-t border-gray-50">
                                <p class="text-primary-600 font-bold text-sm">{{ number_format($item->price, 0) }} <span class="text-xs font-normal text-gray-400">Ks</span></p>
                                <div class="w-6 h-6 rounded-full bg-gray-100 flex items-center justify-center text-gray-400 group-hover:bg-primary-600 group-hover:text-white transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                </div>
                            </div>
                        </button>
                    @empty
                        <div class="col-span-full text-center py-16">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <p class="text-gray-500 myanmar-text font-medium">ပစ္စည်းများ ရှာမတွေ့ပါ</p>
                            <button wire:click="$set('searchTerm', '')" class="text-primary-600 text-sm mt-2 hover:underline myanmar-text">အားလုံးပြန်ကြည့်မည်</button>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Right Side: Cart -->
            <div class="lg:col-span-4 space-y-4">
                <!-- Customer Lookup -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <button 
                        wire:click="$toggle('showCustomerLookup')"
                        class="w-full flex items-center justify-between p-4 hover:bg-gray-50 transition-colors"
                    >
                        <div class="flex items-center gap-2">
                            <div class="p-1.5 bg-blue-50 rounded-lg text-blue-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            </div>
                            <span class="font-medium text-gray-900 myanmar-text">ဖောက်သည် {{ $customer ? ': ' . $customer->name : 'ရှာဖွေရန်' }}</span>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 transform transition-transform {{ $showCustomerLookup ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    @if($showCustomerLookup || $customer)
                    <div class="px-4 pb-4 pt-0">
                        @if(!$customer)
                            <div class="flex gap-2 mt-2">
                                <input 
                                    type="text" 
                                    wire:model="customer_phone"
                                    placeholder="ဖုန်းနံပါတ် (09...)"
                                    class="flex-1 px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                >
                                <button 
                                    wire:click="searchCustomer"
                                    class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700 shadow-sm"
                                >
                                    <span class="myanmar-text">ရှာမည်</span>
                                </button>
                            </div>
                        @else
                            <div class="mt-2 p-3 bg-blue-50 border border-blue-100 rounded-lg">
                                <div class="flex justify-between items-start mb-2">
                                    <div>
                                        <p class="font-bold text-blue-900 text-sm">{{ $customer->name }}</p>
                                        @if($customer->name_mm)
                                        <p class="text-xs text-blue-700 myanmar-text">{{ $customer->name_mm }}</p>
                                        @endif
                                        <p class="text-xs text-blue-600 mt-0.5 font-mono">{{ $customer->customer_code }}</p>
                                    </div>
                                    <button wire:click="clearCustomer" class="text-blue-400 hover:text-blue-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                </div>
                                <div class="grid grid-cols-2 gap-2 text-xs border-t border-blue-100 pt-2 mt-2">
                                    <div>
                                        <span class="text-blue-600 block">Points</span>
                                        <span class="font-bold text-blue-800">{{ number_format($customer->loyalty_points) }}</span>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-blue-600 block">Total Spent</span>
                                        <span class="font-bold text-blue-800">{{ number_format($customer->total_spent) }} Ks</span>
                                    </div>
                                </div>

                                @if($customer->loyalty_points >= 100)
                                <div class="mt-3 pt-2 border-t border-blue-100">
                                    <label class="flex items-center text-xs text-blue-800 font-medium mb-1">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        Points အသုံးပြုမည် (Max: {{ $customer->loyalty_points }})
                                    </label>
                                    <div class="flex gap-2">
                                        <input 
                                            type="number" 
                                            wire:model.live="loyalty_points_to_redeem"
                                            min="0"
                                            max="{{ $customer->loyalty_points }}"
                                            step="100"
                                            class="w-full px-2 py-1 border border-blue-200 rounded text-xs"
                                            placeholder="0"
                                        >
                                    </div>
                                    @if($loyalty_points_to_redeem > 0)
                                    <p class="text-xs text-green-600 mt-1 font-medium text-right">
                                        Discount: -{{ number_format(($loyalty_points_to_redeem / 100) * 1000) }} Ks
                                    </p>
                                    @endif
                                </div>
                                @endif
                            </div>
                        @endif

                        @if(session()->has('customer_error'))
                        <p class="text-xs text-red-500 mt-2 myanmar-text">{{ session('customer_error') }}</p>
                        @endif
                    </div>
                    @endif
                </div>

                <!-- Cart -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 flex flex-col h-[calc(100vh-12rem)] sticky top-24">
                    <div class="p-4 border-b border-gray-100 bg-gray-50/50 rounded-t-xl flex justify-between items-center">
                        <h3 class="font-bold text-gray-900 myanmar-text flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            အော်ဒါစာရင်း
                        </h3>
                        <span class="bg-gray-900 text-white text-xs font-bold px-2 py-1 rounded-full">{{ count($cart) }}</span>
                    </div>

                    <div class="flex-1 overflow-y-auto p-4 space-y-4">
                        @forelse($cart as $key => $item)
                            <div wire:key="cart-item-{{ $key }}" class="group relative bg-gray-50 rounded-xl p-3 border border-gray-100 hover:border-gray-200 transition-colors">
                                <button 
                                    wire:click="removeFromCart('{{ $key }}')"
                                    class="absolute top-2 right-2 text-gray-400 hover:text-red-500 transition-colors p-1"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>

                                <div class="pr-6 mb-2">
                                    <h4 class="font-bold text-gray-900 text-sm">{{ $item['name'] }}</h4>
                                    <p class="text-xs text-gray-500 myanmar-text">{{ $item['name_mm'] }}</p>
                                </div>

                                <div class="flex items-end justify-between">
                                    <div class="flex items-center bg-white rounded-lg border border-gray-200 shadow-sm h-8">
                                        <button 
                                            wire:click="updateQuantity('{{ $key }}', {{ $item['quantity'] - 1 }})"
                                            class="w-8 h-full flex items-center justify-center text-gray-500 hover:text-gray-700 hover:bg-gray-50 rounded-l-lg transition-colors"
                                        >
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
                                        </button>
                                        <span class="w-8 text-center text-sm font-bold text-gray-900 select-none">{{ $item['quantity'] }}</span>
                                        <button 
                                            wire:click="updateQuantity('{{ $key }}', {{ $item['quantity'] + 1 }})"
                                            class="w-8 h-full flex items-center justify-center text-primary-600 hover:text-primary-700 hover:bg-primary-50 rounded-r-lg transition-colors"
                                        >
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                        </button>
                                    </div>
                                    <div class="text-right">
                                        <span class="block font-bold text-gray-900">{{ number_format($item['subtotal'], 0) }}</span>
                                        <span class="text-[10px] text-gray-500">{{ number_format($item['price'], 0) }} ea</span>
                                    </div>
                                </div>

                                <!-- Extra Options Toggle -->
                                <div x-data="{ open: false }" class="mt-2 pt-2 border-t border-gray-200 border-dashed">
                                    <button @click="open = !open" class="text-[10px] text-gray-500 hover:text-gray-700 flex items-center gap-1 myanmar-text">
                                        <svg class="w-3 h-3 transition-transform duration-200" :class="{'rotate-90': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                        မှတ်ချက် / FOC
                                    </button>
                                    
                                    <div x-show="open" class="mt-2 space-y-2" style="display: none;">
                                        <input 
                                            type="text" 
                                            wire:change="updateItemNotes('{{ $key }}', $event.target.value)"
                                            value="{{ $item['notes'] }}"
                                            placeholder="မှတ်ချက်..."
                                            class="w-full text-xs border-gray-200 rounded bg-white focus:ring-1 focus:ring-primary-500"
                                        >
                                        <div class="flex items-center gap-2">
                                            <label class="text-[10px] text-gray-500 uppercase tracking-wider font-bold">FOC:</label>
                                            <input 
                                                type="number" 
                                                wire:change="updateFocQuantity('{{ $key }}', $event.target.value)"
                                                value="{{ $item['foc_quantity'] ?? 0 }}"
                                                min="0"
                                                max="{{ $item['quantity'] }}"
                                                class="w-16 text-xs border-gray-200 rounded bg-white focus:ring-1 focus:ring-primary-500 text-center p-1"
                                            >
                                        </div>
                                    </div>
                                    @if(($item['foc_quantity'] ?? 0) > 0)
                                        <p class="text-[10px] text-green-600 mt-1 myanmar-text font-medium flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            {{ $item['foc_quantity'] }} ခု အခမဲ့
                                        </p>
                                    @endif
                                    @if(!empty($item['notes']))
                                        <p class="text-[10px] text-gray-600 mt-1 italic truncate">"{{ $item['notes'] }}"</p>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="flex flex-col items-center justify-center h-full text-center text-gray-400">
                                <svg class="w-12 h-12 mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                                <p class="text-sm myanmar-text">ဘာမှမရှိသေးပါ</p>
                                <p class="text-xs mt-1">ဘယ်ဘက်မှ ပစ္စည်းများကို ရွေးချယ်ပါ</p>
                            </div>
                        @endforelse
                    </div>

                    @if(count($cart) > 0)
                        <div class="p-4 bg-white border-t border-gray-100 rounded-b-xl shadow-up">
                            <div class="mb-3">
                                <textarea 
                                    wire:model="notes"
                                    rows="1"
                                    placeholder="အော်ဒါမှတ်ချက် (Optional)..."
                                    class="w-full text-sm border-gray-200 rounded-lg bg-gray-50 focus:bg-white focus:ring-primary-500 focus:border-primary-500 resize-none"
                                ></textarea>
                            </div>

                            <div class="space-y-2 mb-4">
                                <div class="flex justify-between text-sm text-gray-600">
                                    <span class="myanmar-text">စုစုပေါင်း</span>
                                    <span>{{ number_format($this->subtotal, 0) }} Ks</span>
                                </div>
                                @if($customer && $loyalty_points_to_redeem > 0)
                                <div class="flex justify-between text-sm text-green-600">
                                    <span class="myanmar-text">Discount</span>
                                    <span>-{{ number_format(($loyalty_points_to_redeem / 100) * 1000, 0) }} Ks</span>
                                </div>
                                @endif
                                <div class="flex justify-between items-baseline pt-2 border-t border-gray-100">
                                    <span class="text-base font-bold text-gray-900 myanmar-text">ပေးရန်</span>
                                    <span class="text-xl font-bold text-primary-600">{{ number_format($this->total, 0) }} <span class="text-xs font-normal text-gray-500">Ks</span></span>
                                </div>
                            </div>

                            <button 
                                wire:click="submitOrder"
                                class="w-full btn btn-primary py-3.5 rounded-xl text-base font-bold shadow-lg shadow-primary-500/30 hover:shadow-primary-500/50 hover:-translate-y-0.5 transition-all flex items-center justify-center gap-2"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <span class="myanmar-text">
                                    @if($isEditMode) အော်ဒါပြင်ဆင်မည် @else အော်ဒါတင်မည် @endif
                                </span>
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    @if (session()->has('success'))
        <div class="fixed bottom-4 right-4 z-50 bg-emerald-600 text-white px-6 py-3 rounded-xl shadow-lg flex items-center gap-3 animate-fade-in-up">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <div>
                <p class="font-bold myanmar-text">အောင်မြင်သည်</p>
                <p class="text-sm opacity-90 myanmar-text">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="fixed bottom-4 right-4 z-50 bg-rose-600 text-white px-6 py-3 rounded-xl shadow-lg flex items-center gap-3 animate-fade-in-up">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <div>
                <p class="font-bold myanmar-text">မှားယွင်းနေသည်</p>
                <p class="text-sm opacity-90 myanmar-text">{{ session('error') }}</p>
            </div>
        </div>
    @endif
</div>
