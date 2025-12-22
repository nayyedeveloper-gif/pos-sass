<div class="min-h-screen bg-gray-50" wire:poll.5s="loadTables">
    {{-- Header --}}
    <div class="bg-white border-b border-gray-200 sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-gray-900 myanmar-text">စားပွဲများ</h1>
                        <p class="text-xs text-gray-500">{{ $tables->count() }} tables</p>
                    </div>
                </div>
                
                <div class="flex items-center gap-3">
                    {{-- Search --}}
                    <div class="relative w-64">
                        <input type="text" wire:model.live.debounce.300ms="search" 
                            placeholder="ရှာဖွေရန်..."
                            class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-xl text-sm focus:ring-orange-500 focus:border-orange-500">
                        <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    
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
                    
                    {{-- Takeaway Button --}}
                    <button wire:click="createTakeaway" class="px-4 py-2 bg-orange-500 text-white rounded-xl font-medium hover:bg-orange-600 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                        <span class="myanmar-text">ပါဆယ်</span>
                    </button>
                </div>
            </div>
            
            {{-- Section Tabs --}}
            @if($sections && count($sections) > 0)
                <div class="flex gap-2 pb-3 overflow-x-auto">
                    @foreach($sections as $section)
                        <button wire:click="selectSection({{ $section->id }})"
                            class="px-4 py-2 rounded-lg text-sm font-medium whitespace-nowrap transition-all {{ $selectedSection === $section->id ? 'bg-orange-500 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                            {{ $section->name }}
                            <span class="ml-1 text-xs opacity-75">({{ $section->tables->count() }})</span>
                        </button>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- Status Legend --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <div class="flex flex-wrap gap-3">
            <div class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                <span class="w-2.5 h-2.5 rounded-full bg-gray-400 mr-2"></span>
                <span class="myanmar-text">အားလပ်သည်</span>
            </div>
            <div class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium bg-green-100 text-green-700">
                <span class="w-2.5 h-2.5 rounded-full bg-green-500 mr-2"></span>
                <span class="myanmar-text">သုံးစွဲနေသည်</span>
            </div>
            <div class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                <span class="w-2.5 h-2.5 rounded-full bg-blue-500 mr-2"></span>
                <span class="myanmar-text">Book ထားသည်</span>
            </div>
        </div>
    </div>

    {{-- Tables View --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-8">
        @if($tableViewMode === 'grid')
            {{-- Grid View --}}
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4">
                @forelse($tables as $table)
                    @php
                        $isOccupied = $table->active_orders_count > 0;
                        $isReserved = $table->status === 'reserved';
                    @endphp
                    
                    <button wire:click="selectTable({{ $table->id }})"
                        class="relative p-4 rounded-2xl border-2 transition-all hover:shadow-lg group
                        {{ $isOccupied ? 'bg-green-50 border-green-400 hover:border-green-500' : '' }}
                        {{ $isReserved ? 'bg-blue-50 border-blue-400 hover:border-blue-500' : '' }}
                        {{ !$isOccupied && !$isReserved ? 'bg-white border-gray-200 hover:border-orange-400' : '' }}">
                        
                        {{-- Table Shape --}}
                        <div class="w-16 h-16 mx-auto mb-3 flex items-center justify-center
                            {{ $table->shape === 'round' ? 'rounded-full' : 'rounded-xl' }}
                            {{ $isOccupied ? 'bg-green-500 text-white' : '' }}
                            {{ $isReserved ? 'bg-blue-500 text-white' : '' }}
                            {{ !$isOccupied && !$isReserved ? 'bg-gray-100 text-gray-700 group-hover:bg-orange-100 group-hover:text-orange-600' : '' }}">
                            <span class="font-bold text-lg">{{ $table->name }}</span>
                        </div>
                        
                        <div class="text-center">
                            <p class="font-medium text-gray-900 text-sm myanmar-text truncate">{{ $table->name_mm ?? $table->name }}</p>
                            <div class="flex items-center justify-center gap-1 text-xs text-gray-500 mt-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                                {{ $table->capacity }} ဦး
                            </div>
                            
                            @if($isOccupied && $table->orders->first())
                                <p class="text-xs text-green-600 mt-2 font-medium">
                                    #{{ $table->orders->first()->order_number }}
                                </p>
                            @endif
                        </div>
                        
                        {{-- Status Badge --}}
                        @if($isOccupied)
                            <div class="absolute top-2 right-2 w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                        @elseif($isReserved)
                            <div class="absolute top-2 right-2 w-3 h-3 bg-blue-500 rounded-full"></div>
                        @endif
                    </button>
                @empty
                    <div class="col-span-full flex flex-col items-center justify-center py-16 text-gray-400">
                        <svg class="w-16 h-16 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                        </svg>
                        <p class="myanmar-text">စားပွဲ မရှိပါ</p>
                    </div>
                @endforelse
            </div>
        @else
            {{-- Layout View --}}
            <div class="bg-gray-800 rounded-2xl p-4 min-h-[500px] relative overflow-auto">
                <div class="absolute inset-0 opacity-10" style="background-image: repeating-linear-gradient(0deg, transparent, transparent 39px, #fff 39px, #fff 40px), repeating-linear-gradient(90deg, transparent, transparent 39px, #fff 39px, #fff 40px);"></div>
                
                {{-- Layout Elements --}}
                @foreach($layoutElements as $element)
                    <div class="absolute pointer-events-none"
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
                    @php
                        $isOccupied = $table->active_orders_count > 0;
                        $isReserved = $table->status === 'reserved';
                    @endphp
                    
                    <button wire:click="selectTable({{ $table->id }})"
                        class="absolute cursor-pointer transition-all hover:scale-105 hover:z-10"
                        style="left: {{ $table->position_x ?? 50 + ($loop->index * 100) }}px; top: {{ $table->position_y ?? 50 }}px;">
                        <div class="{{ $table->shape === 'round' ? 'rounded-full' : 'rounded-xl' }} flex flex-col items-center justify-center text-center shadow-lg
                            {{ !$isOccupied && !$isReserved ? 'bg-gray-600 border-2 border-gray-500 text-white' : '' }}
                            {{ $isOccupied ? 'bg-green-500 border-2 border-green-400 text-white' : '' }}
                            {{ $isReserved ? 'bg-blue-500 border-2 border-blue-400 text-white' : '' }}"
                            style="width: {{ $table->width ?? 80 }}px; height: {{ $table->height ?? 80 }}px;">
                            <span class="font-bold text-sm">{{ $table->name }}</span>
                            <div class="flex items-center gap-1 text-xs opacity-75">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                                {{ $table->capacity }}
                            </div>
                        </div>
                    </button>
                @endforeach
            </div>
        @endif
    </div>
</div>
