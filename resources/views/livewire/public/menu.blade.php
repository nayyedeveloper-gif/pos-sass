<div class="min-h-screen bg-gradient-to-br from-primary-50 to-gray-100">
    <!-- Header -->
    <div class="bg-white shadow-sm sticky top-0 z-10">
        <div class="max-w-7xl mx-auto px-4 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    @if($logo)
                        <img src="{{ Storage::url($logo) }}" alt="Logo" class="h-12 w-12 object-contain">
                    @else
                        <svg class="w-12 h-12 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    @endif
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 myanmar-text">{{ $businessNameMm }}</h1>
                        <p class="text-sm text-gray-600">{{ $businessName }}</p>
                    </div>
                </div>
                <div class="text-right text-sm text-gray-600">
                    @if($businessPhone)
                        <p>{{ $businessPhone }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 py-6">
        <!-- Search -->
        <div class="mb-6">
            <input 
                type="text" 
                wire:model.live="searchTerm" 
                placeholder="ရှာဖွေရန်..."
                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary-500 focus:border-transparent myanmar-text"
            >
        </div>

        <!-- Categories -->
        <div class="mb-6">
            <div class="flex overflow-x-auto space-x-2 pb-2">
                <button 
                    wire:click="selectCategory(null)"
                    class="px-4 py-2 rounded-full whitespace-nowrap myanmar-text {{ !$selectedCategory ? 'bg-primary-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-100' }}"
                >
                    အားလုံး
                </button>
                @foreach($categories as $category)
                    <button 
                        wire:click="selectCategory({{ $category->id }})"
                        class="px-4 py-2 rounded-full whitespace-nowrap myanmar-text {{ $selectedCategory == $category->id ? 'bg-primary-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-100' }}"
                    >
                        {{ $category->name_mm ?? $category->name }} ({{ $category->items_count }})
                    </button>
                @endforeach
            </div>
        </div>

        <!-- Menu Items Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($items as $item)
                <div class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition">
                    <div class="aspect-square w-full overflow-hidden bg-gray-100">
                        @if($item->image)
                            <img src="{{ Storage::url($item->image) }}" alt="{{ $item->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        @endif
                    </div>
                    
                    <div class="p-4">
                        <div class="flex justify-between items-start mb-2">
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-900 myanmar-text">{{ $item->name_mm ?? $item->name }}</h3>
                                <p class="text-sm text-gray-600">{{ $item->name }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-lg font-bold text-primary-600">{{ number_format($item->price, 0) }} Ks</p>
                            </div>
                        </div>
                        
                        @if($item->description || $item->description_mm)
                            <p class="text-sm text-gray-600 myanmar-text">{{ $item->description_mm ?? $item->description }}</p>
                        @endif
                        
                        <div class="mt-2 flex items-center justify-between">
                            <span class="text-xs text-gray-500 myanmar-text">{{ $item->category->name_mm ?? $item->category->name }}</span>
                            @if($item->is_available)
                                <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800 myanmar-text">ရရှိနိုင်သည်</span>
                            @else
                                <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800 myanmar-text">ကုန်ဆုံးပါပြီ</span>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12">
                    <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-gray-500 myanmar-text">ပစ္စည်း မတွေ့ပါ</p>
                </div>
            @endforelse
        </div>

        <!-- Footer -->
        <div class="mt-12 text-center text-sm text-gray-600 myanmar-text">
            @if($businessAddress)
                <p class="mb-2">{{ $businessAddress }}</p>
            @endif
            <p>© {{ date('Y') }} {{ \App\Models\Setting::get('business_name_mm', $businessName) }}. All rights reserved.</p>
        </div>
    </div>
</div>
