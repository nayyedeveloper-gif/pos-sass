<div class="min-h-screen flex flex-col" x-data="{ 
    currentIndex: 0, 
    categories: @js($categories),
    autoRotate() {
        setInterval(() => {
            this.currentIndex = (this.currentIndex + 1) % this.categories.length;
        }, 10000); // Change category every 10 seconds
    }
}" x-init="autoRotate()">
    
    <!-- Header with Logo and Promotional Message -->
    <div class="bg-gradient-to-r from-primary-600 to-primary-700 py-6 px-8 shadow-2xl">
        <div class="max-w-7xl mx-auto">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center space-x-4">
                    @if($logo)
                        <img src="{{ Storage::url($logo) }}" alt="Logo" class="h-20 w-20 object-contain bg-white rounded-lg p-2">
                    @endif
                    <div>
                        <h1 class="text-4xl font-bold text-white myanmar-text">{{ $appName }}</h1>
                        <p class="text-primary-100 text-lg">Digital Signage</p>
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-3xl font-bold text-white" x-data x-text="new Date().toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' })"></div>
                    <div class="text-primary-100" x-data x-text="new Date().toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })"></div>
                </div>
            </div>
            
            <!-- Promotional Message Scrolling -->
            @if($promotionalMessage)
            <div class="bg-white/10 backdrop-blur-sm rounded-lg py-3 px-4 overflow-hidden">
                <div class="promotional-scroll whitespace-nowrap">
                    <span class="text-xl text-white font-semibold myanmar-text">
                        🎉 {{ $promotionalMessage }} 🎉
                    </span>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Category Tabs -->
    <div class="bg-gray-800 py-4 px-8 shadow-lg">
        <div class="max-w-7xl mx-auto">
            <div class="flex space-x-3 overflow-x-auto">
                @foreach($categories as $index => $category)
                <button 
                    @click="currentIndex = {{ $index }}"
                    :class="currentIndex === {{ $index }} ? 'bg-primary-600 text-white' : 'bg-gray-700 text-gray-300 hover:bg-gray-600'"
                    class="px-6 py-3 rounded-lg font-semibold transition-all duration-300 whitespace-nowrap">
                    <span class="myanmar-text">{{ $category['name_mm'] ?? $category['name'] }}</span>
                    <span class="ml-2 text-sm opacity-75">({{ $category['items_count'] }})</span>
                </button>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Menu Items Grid -->
    <div class="flex-1 overflow-y-auto py-8 px-8">
        <div class="max-w-7xl mx-auto">
            @foreach($categories as $index => $category)
            <div x-show="currentIndex === {{ $index }}" 
                 x-transition:enter="slide-enter"
                 class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                
                @if(isset($items[$category['id']]))
                    @foreach($items[$category['id']] as $item)
                    <div class="bg-gray-800 rounded-xl overflow-hidden shadow-xl hover:shadow-2xl transition-all duration-300 hover:scale-105">
                        <!-- Item Image -->
                        <div class="aspect-square w-full overflow-hidden bg-gray-700">
                            @if(isset($item['image']) && $item['image'])
                                <img src="{{ Storage::url($item['image']) }}" 
                                     alt="{{ $item['name'] }}" 
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <svg class="w-20 h-20 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Item Info -->
                        <div class="p-4">
                            <h3 class="text-xl font-bold text-white mb-1 myanmar-text truncate">
                                {{ $item['name_mm'] ?? $item['name'] }}
                            </h3>
                            <p class="text-sm text-gray-400 mb-3 truncate">{{ $item['name'] }}</p>
                            
                            @if(isset($item['description_mm']) || isset($item['description']))
                            <p class="text-sm text-gray-300 mb-3 line-clamp-2 myanmar-text">
                                {{ $item['description_mm'] ?? $item['description'] ?? '' }}
                            </p>
                            @endif
                            
                            <div class="flex items-center justify-between">
                                <div class="text-3xl font-bold text-primary-400">
                                    {{ number_format($item['price'], 0) }}
                                    <span class="text-lg">Ks</span>
                                </div>
                                
                                @if($item['is_available'])
                                <span class="px-3 py-1 bg-green-500 text-white text-xs font-semibold rounded-full">
                                    Available
                                </span>
                                @else
                                <span class="px-3 py-1 bg-red-500 text-white text-xs font-semibold rounded-full">
                                    Sold Out
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="col-span-full text-center py-12">
                        <p class="text-gray-400 text-xl myanmar-text">ဤအမျိုးအစားတွင် ပစ္စည်းကောင်တာပါ</p>
                    </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>

    <!-- Footer -->
    <div class="bg-gray-800 py-4 px-8 border-t border-gray-700">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div class="text-gray-400 text-sm">
                <span class="myanmar-text">စျေးနှုန်းများသည် အချိန်နှင့်အမျှ ပြောင်းလဲနိုင်ပါသည်</span>
            </div>
            <div class="flex items-center space-x-4">
                <div class="flex items-center space-x-2">
                    <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                    <span class="text-gray-400 text-sm">Live Updates</span>
                </div>
                <span class="text-gray-600">|</span>
                <span class="text-gray-400 text-sm">© {{ date('Y') }} {{ $appName }}</span>
            </div>
        </div>
    </div>
</div>

@script
<script>
    // Livewire polling for real-time updates
    setInterval(() => {
        @this.call('loadData');
    }, 60000); // Refresh every minute
</script>
@endscript
