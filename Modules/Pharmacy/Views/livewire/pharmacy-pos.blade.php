<div class="h-[calc(100vh-65px)] flex bg-gray-100 overflow-hidden">
    <!-- Shift Modal -->
    @if($showShiftModal)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75"></div>
                <div class="relative bg-white rounded-lg shadow-xl max-w-lg w-full p-6">
                    @livewire('cashier.shift-management')
                </div>
            </div>
        </div>
    @endif

    <!-- Left: Categories -->
    <div class="w-48 bg-white border-r border-gray-200 flex flex-col hidden md:flex">
        <div class="p-3 border-b border-gray-100 bg-green-50">
            <h2 class="font-bold text-green-800 myanmar-text text-sm">ဆေးအမျိုးအစား</h2>
        </div>
        <div class="flex-1 overflow-y-auto py-2 px-2 space-y-1">
            <button wire:click="selectCategory(null)"
                class="w-full px-3 py-2 rounded-lg text-left text-sm transition-all {{ !$selectedCategory ? 'bg-green-50 text-green-700 font-semibold' : 'text-gray-600 hover:bg-gray-50' }}">
                <span class="myanmar-text">အားလုံး</span>
            </button>
            @foreach($categories as $category)
                <button wire:click="selectCategory({{ $category->id }})"
                    class="w-full px-3 py-2 rounded-lg text-left text-sm transition-all {{ $selectedCategory == $category->id ? 'bg-green-50 text-green-700 font-semibold' : 'text-gray-600 hover:bg-gray-50' }}">
                    <span class="myanmar-text">{{ $category->name_mm }}</span>
                    <span class="text-xs text-gray-400">({{ $category->active_items_count }})</span>
                </button>
            @endforeach
        </div>
    </div>

    <!-- Middle: Items Grid -->
    <div class="flex-1 flex flex-col min-w-0">
        <!-- Search & Barcode -->
        <div class="bg-white border-b border-gray-200 p-4">
            <div class="flex gap-3">
                <!-- Barcode Input -->
                <div class="relative flex-1 max-w-xs">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                        </svg>
                    </div>
                    <input type="text" wire:model="barcodeInput" wire:keydown.enter="scanBarcode"
                        placeholder="Barcode စကင်ဖတ်ရန်..." 
                        class="form-input pl-10 py-2 text-sm border-green-200 focus:border-green-500 focus:ring-green-500" autofocus>
                </div>
                
                <!-- Search -->
                <div class="relative flex-1">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input type="text" wire:model.live="searchTerm" 
                        placeholder="ဆေးဝါးရှာရန်..." 
                        class="form-input pl-10 py-2 text-sm">
                </div>
            </div>
        </div>

        <!-- Items Grid -->
        <div class="flex-1 overflow-y-auto p-4">
            <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-6 gap-3">
                @forelse($items as $item)
                    <button wire:click="addToCart({{ $item->id }})"
                        class="bg-white rounded-xl border border-gray-100 p-3 hover:shadow-md hover:border-green-200 transition-all text-left relative">
                        @if($item->requires_prescription ?? false)
                            <div class="absolute top-1 right-1 bg-red-500 text-white text-xs px-1.5 py-0.5 rounded">Rx</div>
                        @endif
                        <div class="aspect-square bg-green-50 rounded-lg mb-2 flex items-center justify-center">
                            @if($item->image)
                                <img src="{{ asset('storage/' . $item->image) }}" class="w-full h-full object-cover rounded-lg">
                            @else
                                <svg class="w-8 h-8 text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                                </svg>
                            @endif
                        </div>
                        <h3 class="font-medium text-gray-900 text-xs truncate">{{ $item->name }}</h3>
                        <p class="text-xs text-gray-500 myanmar-text truncate">{{ $item->name_mm }}</p>
                        <p class="text-sm font-bold text-green-600 mt-1">{{ number_format($item->price) }} Ks</p>
                        @if($item->stock_quantity !== null)
                            <p class="text-xs {{ $item->stock_quantity <= 10 ? 'text-red-500' : 'text-gray-400' }}">
                                Stock: {{ $item->stock_quantity }}
                            </p>
                        @endif
                    </button>
                @empty
                    <div class="col-span-full text-center py-12 text-gray-400">
                        <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                        </svg>
                        <p class="myanmar-text">ဆေးဝါး မရှိပါ</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Right: Cart -->
    <div class="w-80 bg-white border-l border-gray-200 flex flex-col">
        <div class="p-4 border-b border-gray-100 bg-green-50">
            <div class="flex justify-between items-center">
                <h2 class="font-bold text-green-800 myanmar-text">ဆေးစာရင်း</h2>
                @if(count($cart) > 0)
                    <button wire:click="clearCart" class="text-red-500 text-xs hover:text-red-700">Clear</button>
                @endif
            </div>
        </div>

        <!-- Patient Info -->
        <div class="p-3 border-b border-gray-100 bg-gray-50">
            <div class="grid grid-cols-2 gap-2">
                <input type="text" wire:model="patientName" placeholder="လူနာအမည်" class="form-input text-xs py-1.5">
                <input type="text" wire:model="patientPhone" placeholder="ဖုန်းနံပါတ်" class="form-input text-xs py-1.5">
            </div>
        </div>

        <!-- Cart Items -->
        <div class="flex-1 overflow-y-auto p-3 space-y-2">
            @forelse($cart as $index => $item)
                <div class="bg-gray-50 rounded-lg p-3 {{ $item['requires_prescription'] ? 'border-l-4 border-red-400' : '' }}">
                    <div class="flex justify-between items-start mb-2">
                        <div class="flex-1 min-w-0">
                            <h4 class="font-medium text-gray-900 text-sm truncate">{{ $item['name'] }}</h4>
                            <p class="text-xs text-gray-500">{{ number_format($item['price']) }} Ks</p>
                            @if($item['requires_prescription'])
                                <span class="text-xs text-red-500">Rx Required</span>
                            @endif
                        </div>
                        <button wire:click="removeFromCart({{ $index }})" class="text-red-400 hover:text-red-600 ml-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <button wire:click="updateQuantity({{ $index }}, {{ $item['quantity'] - 1 }})"
                                class="w-7 h-7 rounded-full bg-gray-200 hover:bg-gray-300 flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                </svg>
                            </button>
                            <span class="w-8 text-center font-medium">{{ $item['quantity'] }}</span>
                            <button wire:click="updateQuantity({{ $index }}, {{ $item['quantity'] + 1 }})"
                                class="w-7 h-7 rounded-full bg-green-100 hover:bg-green-200 text-green-600 flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                            </button>
                        </div>
                        <span class="font-bold text-gray-900">{{ number_format($item['total']) }}</span>
                    </div>
                </div>
            @empty
                <div class="text-center py-8 text-gray-400">
                    <svg class="w-12 h-12 mx-auto mb-2 text-green-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                    </svg>
                    <p class="text-sm myanmar-text">ဆေးဝါး ထည့်ပါ</p>
                </div>
            @endforelse
        </div>

        <!-- Totals & Payment -->
        <div class="border-t border-gray-200 p-4 space-y-3">
            <div class="flex justify-between text-sm">
                <span class="text-gray-500 myanmar-text">စုစုပေါင်း</span>
                <span class="font-medium">{{ number_format($subtotal) }} Ks</span>
            </div>
            
            <div class="flex items-center gap-2">
                <span class="text-gray-500 text-sm myanmar-text">လျှော့စျေး</span>
                <input type="number" wire:model.live="discountPercentage" 
                    class="w-16 text-center text-sm border rounded px-2 py-1" min="0" max="100">
                <span class="text-gray-500 text-sm">%</span>
                <span class="ml-auto text-red-500 font-medium">-{{ number_format($discountAmount) }}</span>
            </div>

            <div class="flex justify-between text-lg font-bold border-t pt-3">
                <span class="myanmar-text">ပေးရန်</span>
                <span class="text-green-600">{{ number_format($total) }} Ks</span>
            </div>

            <button wire:click="openPaymentModal" 
                class="w-full bg-green-600 hover:bg-green-700 text-white py-3 rounded-xl text-lg font-bold flex items-center justify-center {{ empty($cart) ? 'opacity-50 cursor-not-allowed' : '' }}"
                {{ empty($cart) ? 'disabled' : '' }}>
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <span class="myanmar-text">ငွေရှင်းမည်</span>
            </button>
        </div>
    </div>

    <!-- Prescription Modal -->
    @if($showPrescriptionModal)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75" wire:click="closePrescriptionModal"></div>
                <div class="relative bg-white rounded-2xl shadow-xl max-w-md w-full p-6">
                    <div class="text-center mb-4">
                        <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <span class="text-2xl font-bold text-red-600">Rx</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 myanmar-text">ဆရာဝန်ညွှန်ကြားချက် လိုအပ်သည်</h3>
                        <p class="text-sm text-gray-500 mt-1 myanmar-text">ဤဆေးဝါးများအတွက် ဆရာဝန်ညွှန်ကြားချက် နံပါတ် ထည့်ပါ</p>
                    </div>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1 myanmar-text">ညွှန်ကြားချက် နံပါတ်</label>
                            <input type="text" wire:model="prescriptionNumber" class="form-input text-center text-lg font-mono" placeholder="RX-XXXXXX">
                        </div>

                        <div class="flex gap-3">
                            <button wire:click="closePrescriptionModal" class="flex-1 btn-secondary">Cancel</button>
                            <button wire:click="confirmPrescription" class="flex-1 bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-lg font-medium">
                                <span class="myanmar-text">အတည်ပြု</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Payment Modal -->
    @if($showPaymentModal)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75" wire:click="closePaymentModal"></div>
                <div class="relative bg-white rounded-2xl shadow-xl max-w-md w-full p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-4 myanmar-text">ငွေပေးချေမှု</h3>
                    
                    <div class="space-y-4">
                        <div class="bg-green-50 rounded-xl p-4 text-center">
                            <p class="text-sm text-gray-500 myanmar-text">ပေးရန်ပမာဏ</p>
                            <p class="text-3xl font-bold text-green-600">{{ number_format($total) }} Ks</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2 myanmar-text">ငွေပေးချေမှုနည်းလမ်း</label>
                            <div class="grid grid-cols-3 gap-2">
                                <button wire:click="$set('paymentMethod', 'cash')"
                                    class="p-3 rounded-lg border-2 text-center transition-all {{ $paymentMethod === 'cash' ? 'border-green-500 bg-green-50' : 'border-gray-200' }}">
                                    <svg class="w-6 h-6 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    <span class="text-xs">Cash</span>
                                </button>
                                <button wire:click="$set('paymentMethod', 'kpay')"
                                    class="p-3 rounded-lg border-2 text-center transition-all {{ $paymentMethod === 'kpay' ? 'border-green-500 bg-green-50' : 'border-gray-200' }}">
                                    <span class="text-lg font-bold text-blue-600">K</span>
                                    <span class="text-xs block">KPay</span>
                                </button>
                                <button wire:click="$set('paymentMethod', 'wave')"
                                    class="p-3 rounded-lg border-2 text-center transition-all {{ $paymentMethod === 'wave' ? 'border-green-500 bg-green-50' : 'border-gray-200' }}">
                                    <span class="text-lg font-bold text-yellow-600">W</span>
                                    <span class="text-xs block">Wave</span>
                                </button>
                            </div>
                        </div>

                        @if($paymentMethod === 'cash')
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1 myanmar-text">လက်ခံငွေ</label>
                                <input type="number" wire:model.live="amountReceived" class="form-input text-lg font-bold text-center">
                            </div>
                            <div class="flex justify-between items-center bg-green-50 rounded-lg p-3">
                                <span class="text-gray-600 myanmar-text">ပြန်အမ်းငွေ</span>
                                <span class="text-2xl font-bold text-green-600">{{ number_format($change) }} Ks</span>
                            </div>
                        @endif

                        <div class="flex gap-3 pt-4">
                            <button wire:click="closePaymentModal" class="flex-1 btn-secondary">Cancel</button>
                            <button wire:click="processPayment" class="flex-1 bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-lg font-medium">
                                <span class="myanmar-text">အတည်ပြုမည်</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Success Modal -->
    @if($showSuccessModal)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75"></div>
                <div class="relative bg-white rounded-2xl shadow-xl max-w-sm w-full p-8 text-center">
                    <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2 myanmar-text">အောင်မြင်ပါသည်!</h3>
                    <p class="text-gray-500 mb-6 myanmar-text">ဆေးဝါးရောင်းချမှု ပြီးဆုံးပါပြီ</p>
                    <button wire:click="closeSuccessModal" class="bg-green-600 hover:bg-green-700 text-white py-2 px-6 rounded-lg font-medium w-full">
                        <span class="myanmar-text">ဆက်လုပ်မည်</span>
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
