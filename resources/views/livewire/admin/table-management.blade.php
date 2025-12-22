<div class="min-h-screen bg-gray-50">
    {{-- Header --}}
    <div class="bg-white border-b border-gray-200 sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center gap-4">
                    <h1 class="text-xl font-bold text-gray-900 myanmar-text">စားပွဲစီမံခန့်ခွဲမှု</h1>
                    <span class="text-sm text-gray-500">{{ $tables->count() }} tables</span>
                </div>
                
                <div class="flex items-center gap-3">
                    {{-- View Mode Toggle --}}
                    <div class="flex bg-gray-100 rounded-lg p-1">
                        <button wire:click="$set('viewMode', 'grid')" 
                            class="px-3 py-1.5 rounded-md text-sm font-medium transition-all {{ $viewMode === 'grid' ? 'bg-white shadow text-gray-900' : 'text-gray-500 hover:text-gray-700' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                            </svg>
                        </button>
                        <button wire:click="$set('viewMode', 'layout')" 
                            class="px-3 py-1.5 rounded-md text-sm font-medium transition-all {{ $viewMode === 'layout' ? 'bg-white shadow text-gray-900' : 'text-gray-500 hover:text-gray-700' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"></path>
                            </svg>
                        </button>
                    </div>

                    {{-- Merge Button --}}
                    @if(count($selectedTables) >= 2)
                        <button wire:click="openMergeModal" class="px-4 py-2 bg-purple-500 text-white rounded-lg hover:bg-purple-600 flex items-center gap-2 font-medium">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                            </svg>
                            <span class="myanmar-text">Merge ({{ count($selectedTables) }})</span>
                        </button>
                    @endif

                    {{-- Batch Add --}}
                    <button wire:click="batchAddTables" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 flex items-center gap-2 font-medium">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 14v6m-3-3h6M6 10h2a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2zm10 0h2a2 2 0 002-2V6a2 2 0 00-2-2h-2a2 2 0 00-2 2v2a2 2 0 002 2zM6 20h2a2 2 0 002-2v-2a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2z"></path>
                        </svg>
                        <span>Batch Add</span>
                    </button>

                    {{-- Add Table --}}
                    <button wire:click="openTableModal" class="px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 flex items-center gap-2 font-medium">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        <span class="myanmar-text">စားပွဲထည့်ရန်</span>
                    </button>

                    {{-- Table Layout --}}
                    <a href="{{ route('admin.tables.layout') }}" class="px-4 py-2 border border-blue-300 text-blue-600 rounded-lg hover:bg-blue-50 flex items-center gap-2 font-medium">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"></path>
                        </svg>
                        <span>Table Layout</span>
                    </a>

                    {{-- New Section --}}
                    <button wire:click="openSectionModal" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 flex items-center gap-2 font-medium">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        <span>New Section</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="flex">
        {{-- Left Sidebar: Sections & Floors --}}
        <div class="w-64 bg-white border-r border-gray-200 min-h-[calc(100vh-64px)]">
            {{-- Floor Tabs --}}
            <div class="p-4 border-b border-gray-100">
                <h3 class="text-xs font-semibold text-gray-500 uppercase mb-3">Floors / Levels</h3>
                <div class="flex flex-wrap gap-2">
                    @foreach($floors as $floor)
                        <button wire:click="selectFloor({{ $floor }})"
                            class="px-3 py-1.5 rounded-lg text-sm font-medium transition-all {{ $selectedFloor === $floor && !$selectedSection ? 'bg-orange-500 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                            {{ $floor < 0 ? 'B' . abs($floor) : 'L' . $floor }}
                        </button>
                    @endforeach
                </div>
            </div>

            {{-- Sections List --}}
            <div class="p-4">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-xs font-semibold text-gray-500 uppercase">Sections</h3>
                    <button wire:click="openSectionModal" class="text-blue-500 hover:text-blue-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                    </button>
                </div>
                
                <div class="space-y-2">
                    @forelse($sections as $section)
                        <div class="group flex items-center justify-between p-3 rounded-lg cursor-pointer transition-all {{ $selectedSection === $section->id ? 'bg-orange-50 border border-orange-200' : 'hover:bg-gray-50 border border-transparent' }}"
                            wire:click="selectSection({{ $section->id }})">
                            <div>
                                <p class="font-medium text-gray-900">{{ $section->name }}</p>
                                <p class="text-xs text-gray-500">
                                    {{ $section->floor < 0 ? 'Basement ' . abs($section->floor) : 'Level ' . $section->floor }}
                                    <span class="mx-1">•</span>
                                    {{ $section->tables->count() }} tables
                                </p>
                            </div>
                            <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button wire:click.stop="openSectionModal({{ $section->id }})" class="p-1 text-gray-400 hover:text-blue-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                    </svg>
                                </button>
                                <button wire:click.stop="deleteSection({{ $section->id }})" wire:confirm="Are you sure?" class="p-1 text-gray-400 hover:text-red-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-gray-400">
                            <svg class="w-12 h-12 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                            <p class="text-sm">No sections yet</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Main Content: Tables Grid/Layout --}}
        <div class="flex-1 p-6">
            {{-- Stats --}}
            <div class="grid grid-cols-4 gap-4 mb-6">
                <div class="bg-white rounded-xl p-4 border border-gray-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-gray-900">{{ $tables->count() }}</p>
                            <p class="text-xs text-gray-500 myanmar-text">စုစုပေါင်း</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl p-4 border border-green-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-green-600">{{ $tables->where('status', 'available')->count() }}</p>
                            <p class="text-xs text-gray-500 myanmar-text">လွတ်နေသော</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl p-4 border border-orange-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-orange-600">{{ $tables->where('status', 'occupied')->count() }}</p>
                            <p class="text-xs text-gray-500 myanmar-text">အသုံးပြုနေသော</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl p-4 border border-purple-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-purple-600">{{ $tables->where('is_merged', true)->count() }}</p>
                            <p class="text-xs text-gray-500">Merged</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tables Grid --}}
            @if($viewMode === 'grid')
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4">
                    @forelse($tables as $table)
                        <div wire:click="toggleTableSelection({{ $table->id }})"
                            class="relative bg-white rounded-xl border-2 p-4 cursor-pointer transition-all hover:shadow-lg
                            {{ in_array($table->id, $selectedTables) ? 'border-purple-500 ring-2 ring-purple-200' : '' }}
                            {{ $table->status === 'available' ? 'border-gray-200 hover:border-green-300' : '' }}
                            {{ $table->status === 'occupied' ? 'border-orange-300 bg-orange-50' : '' }}
                            {{ $table->status === 'reserved' ? 'border-blue-300 bg-blue-50' : '' }}
                            {{ $table->is_merged ? 'border-purple-300 bg-purple-50' : '' }}">
                            
                            {{-- Selection Checkbox --}}
                            <div class="absolute top-2 left-2">
                                <div class="w-5 h-5 rounded border-2 flex items-center justify-center
                                    {{ in_array($table->id, $selectedTables) ? 'bg-purple-500 border-purple-500' : 'border-gray-300' }}">
                                    @if(in_array($table->id, $selectedTables))
                                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    @endif
                                </div>
                            </div>

                            {{-- Actions --}}
                            <div class="absolute top-2 right-2 flex gap-1">
                                <button wire:click.stop="openTableModal({{ $table->id }})" class="p-1 text-gray-400 hover:text-blue-500 bg-white rounded">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                    </svg>
                                </button>
                                <button wire:click.stop="deleteTable({{ $table->id }})" wire:confirm="Delete this table?" class="p-1 text-gray-400 hover:text-red-500 bg-white rounded">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>

                            {{-- Table Icon --}}
                            <div class="flex justify-center mb-3 mt-4">
                                <div class="w-16 h-16 {{ $table->shape === 'round' ? 'rounded-full' : 'rounded-lg' }} 
                                    {{ $table->status === 'available' ? 'bg-gray-100 border-2 border-gray-300' : '' }}
                                    {{ $table->status === 'occupied' ? 'bg-orange-200 border-2 border-orange-400' : '' }}
                                    {{ $table->status === 'reserved' ? 'bg-blue-200 border-2 border-blue-400' : '' }}
                                    flex items-center justify-center">
                                    <span class="text-lg font-bold {{ $table->status === 'available' ? 'text-gray-600' : 'text-white' }}">
                                        {{ $table->name }}
                                    </span>
                                </div>
                            </div>

                            {{-- Table Info --}}
                            <div class="text-center">
                                <div class="flex items-center justify-center gap-1 text-sm text-gray-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <span>{{ $table->capacity }}</span>
                                </div>
                                
                                @if($table->is_merged)
                                    <span class="inline-block mt-1 px-2 py-0.5 bg-purple-100 text-purple-700 text-xs rounded-full">Merged</span>
                                @endif
                                
                                @if($table->merged_with)
                                    <button wire:click.stop="unmergeTables({{ $table->id }})" class="mt-2 text-xs text-purple-600 hover:text-purple-800 underline">
                                        Unmerge
                                    </button>
                                @endif
                                
                                @if($table->status === 'occupied' && $table->occupied_at)
                                    <p class="text-xs text-orange-600 mt-1">
                                        {{ $table->occupied_at->diffForHumans() }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-16 text-gray-400">
                            <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                            <p class="myanmar-text">စားပွဲများ မရှိသေးပါ</p>
                            <button wire:click="openTableModal" class="mt-4 px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600">
                                <span class="myanmar-text">စားပွဲထည့်ရန်</span>
                            </button>
                        </div>
                    @endforelse
                </div>
            @else
                {{-- Layout View --}}
                <div class="bg-gray-800 rounded-2xl p-4 min-h-[600px] relative overflow-hidden">
                    <div class="absolute inset-0 opacity-10" style="background-image: repeating-linear-gradient(0deg, transparent, transparent 40px, #fff 40px, #fff 41px), repeating-linear-gradient(90deg, transparent, transparent 40px, #fff 40px, #fff 41px);"></div>
                    
                    @foreach($tables as $table)
                        <div class="absolute cursor-pointer transition-all hover:scale-105"
                            style="left: {{ $table->position_x ?? rand(50, 400) }}px; top: {{ $table->position_y ?? rand(50, 300) }}px;">
                            <div class="w-20 h-20 {{ $table->shape === 'round' ? 'rounded-full' : 'rounded-lg' }} 
                                {{ $table->status === 'available' ? 'bg-gray-600 border-2 border-gray-500' : '' }}
                                {{ $table->status === 'occupied' ? 'bg-green-500 border-2 border-green-400' : '' }}
                                {{ $table->status === 'reserved' ? 'bg-blue-500 border-2 border-blue-400' : '' }}
                                flex flex-col items-center justify-center text-white shadow-lg">
                                <span class="font-bold">{{ $table->name }}</span>
                                <div class="flex items-center gap-1 text-xs opacity-75">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                    </svg>
                                    {{ $table->capacity }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- Section Modal --}}
    @if($showSectionModal)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-gray-900/80 backdrop-blur-sm" wire:click="$set('showSectionModal', false)"></div>
                <div class="relative bg-white rounded-2xl shadow-2xl max-w-md w-full p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">{{ $sectionForm['id'] ? 'Edit Section' : 'New Section' }}</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Section Name</label>
                            <input type="text" wire:model="sectionForm.name" class="w-full border border-gray-200 rounded-xl px-4 py-2 focus:ring-orange-500 focus:border-orange-500" placeholder="e.g. VIP, Outdoor, Main Hall">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1 myanmar-text">အမည် (မြန်မာ)</label>
                            <input type="text" wire:model="sectionForm.name_mm" class="w-full border border-gray-200 rounded-xl px-4 py-2 focus:ring-orange-500 focus:border-orange-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Floor / Level</label>
                            <div class="flex gap-2">
                                @foreach([-1, 1, 2, 3] as $floor)
                                    <button type="button" wire:click="$set('sectionForm.floor', {{ $floor }})"
                                        class="px-4 py-2 rounded-lg border-2 font-medium transition-all {{ $sectionForm['floor'] == $floor ? 'border-orange-500 bg-orange-50 text-orange-700' : 'border-gray-200 text-gray-600 hover:border-gray-300' }}">
                                        {{ $floor < 0 ? 'B' . abs($floor) : 'Level ' . $floor }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Layout Size</label>
                            <div class="flex gap-2">
                                @foreach(['1280x620', '800x800', '620x1280'] as $size)
                                    <button type="button" wire:click="$set('sectionForm.layout_size', '{{ $size }}')"
                                        class="px-4 py-2 rounded-lg border-2 font-medium text-sm transition-all {{ $sectionForm['layout_size'] == $size ? 'border-orange-500 bg-orange-50 text-orange-700' : 'border-gray-200 text-gray-600 hover:border-gray-300' }}">
                                        {{ $size }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                        <div class="flex gap-3 pt-2">
                            <button type="button" wire:click="$set('showSectionModal', false)" class="flex-1 px-4 py-2 border border-gray-200 rounded-xl text-gray-600 hover:bg-gray-50">Cancel</button>
                            <button type="button" wire:click="saveSection" class="flex-1 px-4 py-2 bg-orange-500 text-white rounded-xl hover:bg-orange-600 font-medium">Confirm</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Table Modal --}}
    @if($showTableModal)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-gray-900/80 backdrop-blur-sm" wire:click="$set('showTableModal', false)"></div>
                <div class="relative bg-white rounded-2xl shadow-2xl max-w-md w-full p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 myanmar-text">{{ $tableForm['id'] ? 'စားပွဲပြင်ဆင်ရန်' : 'စားပွဲအသစ်ထည့်ရန်' }}</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1 myanmar-text">စားပွဲအမည်</label>
                            <input type="text" wire:model="tableForm.name" class="w-full border border-gray-200 rounded-xl px-4 py-2 focus:ring-orange-500 focus:border-orange-500" placeholder="e.g. #1, VIP1, A1">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1 myanmar-text">ထိုင်နိုင်သူဦးရေ</label>
                            <input type="number" wire:model="tableForm.capacity" min="1" class="w-full border border-gray-200 rounded-xl px-4 py-2 focus:ring-orange-500 focus:border-orange-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Shape</label>
                            <div class="flex gap-3">
                                <button type="button" wire:click="$set('tableForm.shape', 'square')"
                                    class="flex-1 p-4 rounded-xl border-2 text-center transition-all {{ $tableForm['shape'] === 'square' ? 'border-orange-500 bg-orange-50' : 'border-gray-200 hover:border-gray-300' }}">
                                    <div class="w-10 h-10 bg-gray-300 rounded-lg mx-auto mb-2"></div>
                                    <span class="text-sm font-medium">Square</span>
                                </button>
                                <button type="button" wire:click="$set('tableForm.shape', 'round')"
                                    class="flex-1 p-4 rounded-xl border-2 text-center transition-all {{ $tableForm['shape'] === 'round' ? 'border-orange-500 bg-orange-50' : 'border-gray-200 hover:border-gray-300' }}">
                                    <div class="w-10 h-10 bg-gray-300 rounded-full mx-auto mb-2"></div>
                                    <span class="text-sm font-medium">Round</span>
                                </button>
                            </div>
                        </div>
                        <div class="flex gap-3 pt-2">
                            <button type="button" wire:click="$set('showTableModal', false)" class="flex-1 px-4 py-2 border border-gray-200 rounded-xl text-gray-600 hover:bg-gray-50">Cancel</button>
                            <button type="button" wire:click="saveTable" class="flex-1 px-4 py-2 bg-orange-500 text-white rounded-xl hover:bg-orange-600 font-medium myanmar-text">သိမ်းဆည်းမည်</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Merge Modal --}}
    @if($showMergeModal)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-gray-900/80 backdrop-blur-sm" wire:click="$set('showMergeModal', false)"></div>
                <div class="relative bg-white rounded-2xl shadow-2xl max-w-md w-full p-6">
                    <div class="text-center mb-4">
                        <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900">Merge Tables</h3>
                        <p class="text-sm text-gray-500 mt-1">Combine {{ count($selectedTables) }} tables into one</p>
                    </div>
                    
                    <div class="flex flex-wrap gap-2 justify-center mb-6">
                        @foreach($mergeTables as $table)
                            <div class="px-4 py-2 bg-purple-100 text-purple-700 rounded-lg font-medium">
                                {{ $table->name }}
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="bg-gray-50 rounded-xl p-4 mb-4">
                        <p class="text-sm text-gray-600">
                            <strong>Total Capacity:</strong> {{ $mergeTables->sum('capacity') }} seats
                        </p>
                    </div>
                    
                    <div class="flex gap-3">
                        <button type="button" wire:click="$set('showMergeModal', false)" class="flex-1 px-4 py-2 border border-gray-200 rounded-xl text-gray-600 hover:bg-gray-50">Cancel</button>
                        <button type="button" wire:click="confirmMerge" class="flex-1 px-4 py-2 bg-purple-500 text-white rounded-xl hover:bg-purple-600 font-medium">Merge Tables</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
