<div class="min-h-screen flex flex-col" 
     x-data="{ 
        currentIndex: 0, 
        showingMedia: false,
        currentMediaIndex: 0,
        showHelp: false,
        categories: @js($categories),
        media: @js($media),
        showMedia: @js($showMedia),
        rotationSpeed: @js($rotationSpeed * 1000),
        
        init() {
            this.startRotation();
        },
        
        startRotation() {
            setInterval(() => {
                // Show media if enabled and available
                if (this.showMedia && this.media.length > 0 && !this.showingMedia) {
                    // Every 3 rotations, show a media
                    if (Math.random() > 0.7) {
                        this.showingMedia = true;
                        this.currentMediaIndex = Math.floor(Math.random() * this.media.length);
                        
                        // Get media duration
                        const mediaDuration = this.media[this.currentMediaIndex].duration * 1000;
                        
                        setTimeout(() => {
                            this.showingMedia = false;
                            this.currentIndex = (this.currentIndex + 1) % this.categories.length;
                        }, mediaDuration);
                        return;
                    }
                }
                
                if (!this.showingMedia) {
                    this.currentIndex = (this.currentIndex + 1) % this.categories.length;
                }
            }, this.rotationSpeed);
        }
    }" 
     x-init="init()">
    
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
                <div class="promotional-scroll whitespace-nowrap flex items-center">
                    <svg class="w-5 h-5 text-yellow-300 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                    </svg>
                    <span class="text-xl text-white font-semibold myanmar-text">
                        {{ $promotionalMessage }}
                    </span>
                    <svg class="w-5 h-5 text-yellow-300 ml-2" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                    </svg>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Media Slideshow (Full Screen) -->
    <div x-show="showingMedia" 
         x-transition:enter="transition ease-out duration-500"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         class="flex-1 flex items-center justify-center bg-black p-8">
        <template x-for="(mediaItem, index) in media" :key="index">
            <div x-show="showingMedia && currentMediaIndex === index" class="w-full h-full flex items-center justify-center">
                <!-- Video -->
                <template x-if="mediaItem.type === 'video'">
                    <video class="max-w-full max-h-full object-contain" autoplay muted loop>
                        <source :src="`/storage/${mediaItem.file_path}`" type="video/mp4">
                    </video>
                </template>
                
                <!-- Image -->
                <template x-if="mediaItem.type === 'image'">
                    <img :src="`/storage/${mediaItem.file_path}`" :alt="mediaItem.title" class="max-w-full max-h-full object-contain">
                </template>
            </div>
        </template>
    </div>

    <!-- Category Tabs -->
    <div x-show="!showingMedia" class="bg-gray-800 py-4 px-8 shadow-lg">
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
    <div x-show="!showingMedia" class="flex-1 overflow-y-auto py-8 px-8">
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
                            
                            @if($showDescriptions && (isset($item['description_mm']) || isset($item['description'])))
                            <p class="text-sm text-gray-300 mb-3 line-clamp-2 myanmar-text">
                                {{ $item['description_mm'] ?? $item['description'] ?? '' }}
                            </p>
                            @endif
                            
                            <div class="flex items-center justify-between">
                                @if($showPrices)
                                <div class="text-3xl font-bold text-primary-400">
                                    {{ number_format($item['price'], 0) }}
                                    <span class="text-lg">Ks</span>
                                </div>
                                @endif
                                
                                @if($showAvailability)
                                    @if($item['is_available'])
                                    <span class="px-3 py-1 bg-green-500 text-white text-xs font-semibold rounded-full">
                                        Available
                                    </span>
                                    @else
                                    <span class="px-3 py-1 bg-red-500 text-white text-xs font-semibold rounded-full">
                                        Sold Out
                                    </span>
                                    @endif
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
    <div x-show="!showingMedia" class="bg-gray-800 py-4 px-8 border-t border-gray-700">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div class="text-gray-400 text-sm">
                <span class="myanmar-text">စျေးနှုန်းများသည် အချိန်နှင့်အမျှ ပြောင်းလဲနိုင်ပါသည်</span>
            </div>
            <div class="flex items-center space-x-4">
                <button @click="showHelp = true" class="text-gray-400 hover:text-white text-sm flex items-center space-x-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>Help</span>
                </button>
                <span class="text-gray-600">|</span>
                <div class="flex items-center space-x-2">
                    <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                    <span class="text-gray-400 text-sm">Live Updates</span>
                </div>
                <span class="text-gray-600">|</span>
                <span class="text-gray-400 text-sm">© {{ date('Y') }} {{ $appName }}</span>
            </div>
        </div>
    </div>

    <!-- Help Overlay -->
    <div x-show="showHelp" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="showHelp = false"
         class="fixed inset-0 bg-black/80 backdrop-blur-sm flex items-center justify-center z-50">
        <div @click.stop class="bg-gray-800 rounded-2xl shadow-2xl max-w-2xl w-full mx-4 p-8 border border-gray-700">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center space-x-3">
                    <svg class="w-7 h-7 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                    </svg>
                    <h2 class="text-2xl font-bold text-white">Keyboard Shortcuts</h2>
                </div>
                <button @click="showHelp = false" class="text-gray-400 hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-gray-900 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-300">Fullscreen</span>
                        <kbd class="px-3 py-1 bg-gray-700 text-white rounded text-sm font-mono">F11</kbd>
                    </div>
                </div>
                
                <div class="bg-gray-900 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-300">Refresh</span>
                        <kbd class="px-3 py-1 bg-gray-700 text-white rounded text-sm font-mono">R</kbd>
                    </div>
                </div>
                
                <div class="bg-gray-900 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-300">Previous Category</span>
                        <kbd class="px-3 py-1 bg-gray-700 text-white rounded text-sm font-mono">←</kbd>
                    </div>
                </div>
                
                <div class="bg-gray-900 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-300">Next Category</span>
                        <kbd class="px-3 py-1 bg-gray-700 text-white rounded text-sm font-mono">→</kbd>
                    </div>
                </div>
                
                <div class="bg-gray-900 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-300">Exit Fullscreen</span>
                        <kbd class="px-3 py-1 bg-gray-700 text-white rounded text-sm font-mono">ESC</kbd>
                    </div>
                </div>
                
                <div class="bg-gray-900 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-300">Show Help</span>
                        <kbd class="px-3 py-1 bg-gray-700 text-white rounded text-sm font-mono">?</kbd>
                    </div>
                </div>
            </div>
            
            <div class="mt-6 p-4 bg-primary-900/30 border border-primary-700 rounded-lg">
                <h3 class="text-primary-300 font-semibold mb-2 myanmar-text">အကြံပြုချက်များ:</h3>
                <ul class="text-gray-300 text-sm space-y-1 myanmar-text">
                    <li>• F11 ကို နှိပ်ပြီး fullscreen mode ဖွင့်ပါ</li>
                    <li>• Display သည် အလိုအလျောက် rotate လုပ်မည်</li>
                    <li>• စျေးနှုန်းများ အလိုအလျောက် update လုပ်မည်</li>
                    <li>• Right-click နှင့် text selection ကို ပိတ်ထားပါသည်</li>
                </ul>
            </div>
            
            <div class="mt-6 text-center">
                <button @click="showHelp = false" class="btn btn-primary">
                    Got it!
                </button>
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
    
    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // F11 - Fullscreen toggle
        if (e.key === 'F11') {
            e.preventDefault();
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen();
            } else {
                document.exitFullscreen();
            }
        }
        
        // R - Refresh/Reload
        if (e.key === 'r' || e.key === 'R') {
            window.location.reload();
        }
        
        // Left Arrow - Previous category
        if (e.key === 'ArrowLeft') {
            Alpine.store('display').previousCategory();
        }
        
        // Right Arrow - Next category
        if (e.key === 'ArrowRight') {
            Alpine.store('display').nextCategory();
        }
        
        // Space - Pause/Resume rotation
        if (e.key === ' ') {
            e.preventDefault();
            Alpine.store('display').togglePause();
        }
        
        // ESC - Exit fullscreen
        if (e.key === 'Escape' && document.fullscreenElement) {
            document.exitFullscreen();
        }
        
        // ? - Show help
        if (e.key === '?') {
            Alpine.store('display').showHelp = !Alpine.store('display').showHelp;
        }
    });
    
    // Prevent context menu (right-click)
    document.addEventListener('contextmenu', e => e.preventDefault());
    
    // Prevent text selection
    document.addEventListener('selectstart', e => e.preventDefault());
</script>
@endscript
