<div class="min-h-screen bg-gray-100" 
    x-data="tableLayoutDesigner(@js($tables), @js($layoutElements), {{ $canvasWidth }}, {{ $canvasHeight }}, {{ $gridSize }})" 
    x-init="init()"
    @keydown.delete.window="deleteSelected()"
    @keydown.backspace.window="deleteSelected()"
    @keydown.escape.window="deselectAll()">
    {{-- Header --}}
    <div class="bg-white border-b border-gray-200 sticky top-0 z-40">
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-14">
                <div class="flex items-center gap-4">
                    <a href="{{ route('admin.tables.index') }}" class="text-gray-500 hover:text-gray-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                    </a>
                    <h1 class="text-lg font-bold text-gray-900">Table Layout Designer</h1>
                </div>
                
                <div class="flex items-center gap-3">
                    <button wire:click="openSettingsModal" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Settings
                    </button>
                    <button wire:click="openMergeSectionModal" class="px-4 py-2 border border-purple-300 text-purple-700 rounded-lg hover:bg-purple-50 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                        </svg>
                        Merge Section
                    </button>
                    <button wire:click="openSectionModal" class="px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        New Section
                    </button>
                </div>
            </div>
            
            {{-- Section Tabs --}}
            <div class="flex items-center gap-2 pb-3 overflow-x-auto">
                @foreach($sections as $section)
                    <button wire:click="selectSection({{ $section->id }})"
                        class="px-4 py-2 rounded-lg text-sm font-medium whitespace-nowrap transition-all {{ $selectedSection === $section->id ? 'bg-orange-500 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                        {{ $section->name }}
                        <span class="ml-1 text-xs opacity-75">({{ $section->tables->count() }})</span>
                    </button>
                @endforeach
            </div>
        </div>
    </div>

    <div class="flex">
        {{-- Left Sidebar: Components --}}
        <div class="w-64 bg-white border-r border-gray-200 min-h-[calc(100vh-110px)] p-4">
            <h3 class="text-sm font-bold text-gray-900 mb-4">Components</h3>
            
            {{-- Current Section Info --}}
            @if($currentSection)
                <div class="mb-4 p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-700">{{ $currentSection->name }}</span>
                        <button wire:click="openSectionModal({{ $currentSection->id }})" class="text-gray-400 hover:text-blue-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                            </svg>
                        </button>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">{{ $currentSection->layout_size }}</p>
                </div>
            @endif

            {{-- Tables --}}
            <div class="mb-6">
                <h4 class="text-xs font-semibold text-gray-500 uppercase mb-3">Tables</h4>
                <div class="flex gap-3">
                    <button wire:click="addComponentToCanvas('square')"
                        class="flex-1 p-4 border-2 border-dashed border-gray-300 rounded-xl hover:border-orange-400 hover:bg-orange-50 transition-all cursor-pointer group">
                        <div class="w-12 h-12 bg-gray-200 rounded-lg mx-auto mb-2 group-hover:bg-orange-200"></div>
                        <span class="text-xs font-medium text-gray-600 group-hover:text-orange-600">Square</span>
                    </button>
                    <button wire:click="addComponentToCanvas('round')"
                        class="flex-1 p-4 border-2 border-dashed border-gray-300 rounded-xl hover:border-orange-400 hover:bg-orange-50 transition-all cursor-pointer group">
                        <div class="w-12 h-12 bg-gray-200 rounded-full mx-auto mb-2 group-hover:bg-orange-200"></div>
                        <span class="text-xs font-medium text-gray-600 group-hover:text-orange-600">Round</span>
                    </button>
                </div>
            </div>

            {{-- Barrier & Label --}}
            <div class="mb-6">
                <h4 class="text-xs font-semibold text-gray-500 uppercase mb-3">Barrier & Label</h4>
                <div class="space-y-2">
                    <button wire:click="addComponentToCanvas('label')"
                        class="w-full p-3 border-2 border-dashed border-gray-300 rounded-lg hover:border-blue-400 hover:bg-blue-50 transition-all cursor-pointer text-left">
                        <span class="text-sm text-gray-500">Label</span>
                    </button>
                    <button wire:click="addComponentToCanvas('barrier')"
                        class="w-full p-3 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-all cursor-pointer text-center">
                        <span class="text-sm font-medium">Barrier</span>
                    </button>
                </div>
            </div>

            {{-- Other Section Tables --}}
            <div>
                <h4 class="text-xs font-semibold text-gray-500 uppercase mb-3">Other Section Tables</h4>
                <button wire:click="openAddTableModal" class="w-full px-4 py-2 border border-blue-300 text-blue-600 rounded-lg hover:bg-blue-50 text-sm font-medium">
                    Add to layout
                </button>
            </div>
        </div>

        {{-- Main Canvas Area --}}
        <div class="flex-1 p-6 overflow-auto">
            {{-- Toolbar --}}
            @if($selectedElement)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-4 flex items-center gap-4">
                    <div class="flex items-center gap-2">
                        <label class="text-sm text-gray-500">Name:</label>
                        <input type="text" wire:model.live="elementName" class="w-32 border border-gray-200 rounded-lg px-3 py-1.5 text-sm">
                    </div>
                    <div class="flex items-center gap-2">
                        <label class="text-sm text-gray-500">Width:</label>
                        <div class="flex items-center">
                            <button wire:click="$set('elementWidth', max(40, $elementWidth - 10))" class="px-2 py-1 bg-gray-100 rounded-l-lg hover:bg-gray-200">-</button>
                            <span class="px-3 py-1 bg-gray-50 text-sm">{{ $elementWidth }}</span>
                            <button wire:click="$set('elementWidth', $elementWidth + 10)" class="px-2 py-1 bg-gray-100 rounded-r-lg hover:bg-gray-200">+</button>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <label class="text-sm text-gray-500">Height:</label>
                        <div class="flex items-center">
                            <button wire:click="$set('elementHeight', max(20, $elementHeight - 10))" class="px-2 py-1 bg-gray-100 rounded-l-lg hover:bg-gray-200">-</button>
                            <span class="px-3 py-1 bg-gray-50 text-sm">{{ $elementHeight }}</span>
                            <button wire:click="$set('elementHeight', $elementHeight + 10)" class="px-2 py-1 bg-gray-100 rounded-r-lg hover:bg-gray-200">+</button>
                        </div>
                    </div>
                    @if($selectedElementType === 'element')
                        <div class="flex items-center gap-2">
                            <label class="text-sm text-gray-500">Color:</label>
                            <div class="flex gap-1">
                                @foreach(['#e5e7eb', '#9ca3af', '#6b7280', '#374151', '#1f2937'] as $color)
                                    <button wire:click="$set('elementColor', '{{ $color }}')"
                                        class="w-6 h-6 rounded-full border-2 {{ $elementColor === $color ? 'border-orange-500' : 'border-transparent' }}"
                                        style="background-color: {{ $color }}"></button>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    <div class="flex items-center gap-1 ml-auto">
                        <button wire:click="duplicateElement" class="p-2 text-gray-400 hover:text-blue-500 hover:bg-blue-50 rounded-lg" title="Duplicate">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                            </svg>
                        </button>
                        <button wire:click="updateElementProperties" class="p-2 text-gray-400 hover:text-green-500 hover:bg-green-50 rounded-lg" title="Apply">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </button>
                        <button wire:click="deleteSelectedElement" class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg" title="Delete">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            @endif

            {{-- Canvas --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-auto">
                <div class="relative bg-gray-200" 
                    x-ref="canvas"
                    :style="`width: ${canvasWidth}px; height: ${canvasHeight}px;`"
                    @click.self="deselectAll()"
                    @mousemove="onMouseMove($event)"
                    @mouseup="onMouseUp($event)"
                    @mouseleave="onMouseUp($event)">
                    
                    {{-- Grid --}}
                    <div class="absolute inset-0 opacity-30 pointer-events-none" 
                        :style="`background-image: repeating-linear-gradient(0deg, transparent, transparent ${gridSize - 1}px, #9ca3af ${gridSize - 1}px, #9ca3af ${gridSize}px), repeating-linear-gradient(90deg, transparent, transparent ${gridSize - 1}px, #9ca3af ${gridSize - 1}px, #9ca3af ${gridSize}px);`"></div>

                    {{-- Layout Elements (Barriers, Labels) --}}
                    <template x-for="element in elements" :key="'element-' + element.id">
                        <div class="absolute cursor-move select-none"
                            :data-id="element.id"
                            :data-type="'element'"
                            :style="`left: ${element.position_x}px; top: ${element.position_y}px; width: ${element.width}px; height: ${element.height}px; transform: rotate(${element.rotation || 0}deg);`"
                            :class="{ 'ring-2 ring-blue-500 ring-offset-2 z-20': selectedId === element.id && selectedType === 'element' }"
                            @mousedown.stop="startDrag($event, element.id, 'element')">
                            
                            {{-- Barrier --}}
                            <template x-if="element.type === 'barrier'">
                                <div class="w-full h-full rounded flex items-center justify-center text-white text-xs font-medium"
                                    :style="`background-color: ${element.color};`"
                                    x-text="element.name"></div>
                            </template>
                            
                            {{-- Label --}}
                            <template x-if="element.type === 'label'">
                                <div class="w-full h-full border-2 border-dashed border-gray-400 rounded flex items-center justify-center text-gray-500 text-xs"
                                    x-text="element.name"></div>
                            </template>
                            
                            {{-- Resize Handles --}}
                            <template x-if="selectedId === element.id && selectedType === 'element'">
                                <div>
                                    <div class="absolute -top-1 -left-1 w-3 h-3 bg-blue-500 rounded-full cursor-nw-resize z-30" @mousedown.stop="startResize($event, 'nw')"></div>
                                    <div class="absolute -top-1 -right-1 w-3 h-3 bg-blue-500 rounded-full cursor-ne-resize z-30" @mousedown.stop="startResize($event, 'ne')"></div>
                                    <div class="absolute -bottom-1 -left-1 w-3 h-3 bg-blue-500 rounded-full cursor-sw-resize z-30" @mousedown.stop="startResize($event, 'sw')"></div>
                                    <div class="absolute -bottom-1 -right-1 w-3 h-3 bg-blue-500 rounded-full cursor-se-resize z-30" @mousedown.stop="startResize($event, 'se')"></div>
                                    <div class="absolute top-1/2 -left-1 w-3 h-3 bg-blue-500 rounded-full cursor-w-resize z-30 -translate-y-1/2" @mousedown.stop="startResize($event, 'w')"></div>
                                    <div class="absolute top-1/2 -right-1 w-3 h-3 bg-blue-500 rounded-full cursor-e-resize z-30 -translate-y-1/2" @mousedown.stop="startResize($event, 'e')"></div>
                                    <div class="absolute -top-1 left-1/2 w-3 h-3 bg-blue-500 rounded-full cursor-n-resize z-30 -translate-x-1/2" @mousedown.stop="startResize($event, 'n')"></div>
                                    <div class="absolute -bottom-1 left-1/2 w-3 h-3 bg-blue-500 rounded-full cursor-s-resize z-30 -translate-x-1/2" @mousedown.stop="startResize($event, 's')"></div>
                                </div>
                            </template>
                        </div>
                    </template>

                    {{-- Tables --}}
                    <template x-for="table in tables" :key="'table-' + table.id">
                        <div class="absolute cursor-move select-none"
                            :data-id="table.id"
                            :data-type="'table'"
                            :style="`left: ${table.position_x || 100}px; top: ${table.position_y || 100}px;`"
                            :class="{ 'ring-2 ring-orange-500 ring-offset-2 z-20': selectedId === table.id && selectedType === 'table' }"
                            @mousedown.stop="startDrag($event, table.id, 'table')">
                            
                            <div class="flex flex-col items-center justify-center text-center"
                                :class="[
                                    table.shape === 'round' ? 'rounded-full' : 'rounded-xl',
                                    table.status === 'available' ? 'bg-gray-100 border-2 border-gray-300 text-gray-700' : '',
                                    table.status === 'occupied' ? 'bg-green-500 border-2 border-green-400 text-white' : '',
                                    table.status === 'reserved' ? 'bg-blue-500 border-2 border-blue-400 text-white' : ''
                                ]"
                                :style="`width: ${table.width || 80}px; height: ${table.height || 80}px;`">
                                <span class="font-bold text-sm" x-text="table.name"></span>
                                <div class="flex items-center gap-1 text-xs opacity-75 mt-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                    </svg>
                                    <span x-text="table.capacity"></span>
                                </div>
                            </div>
                            
                            {{-- Resize Handles for Tables --}}
                            <template x-if="selectedId === table.id && selectedType === 'table'">
                                <div>
                                    <div class="absolute -top-1 -left-1 w-3 h-3 bg-orange-500 rounded-full cursor-nw-resize z-30" @mousedown.stop="startResize($event, 'nw')"></div>
                                    <div class="absolute -top-1 -right-1 w-3 h-3 bg-orange-500 rounded-full cursor-ne-resize z-30" @mousedown.stop="startResize($event, 'ne')"></div>
                                    <div class="absolute -bottom-1 -left-1 w-3 h-3 bg-orange-500 rounded-full cursor-sw-resize z-30" @mousedown.stop="startResize($event, 'sw')"></div>
                                    <div class="absolute -bottom-1 -right-1 w-3 h-3 bg-orange-500 rounded-full cursor-se-resize z-30" @mousedown.stop="startResize($event, 'se')"></div>
                                </div>
                            </template>
                        </div>
                    </template>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex justify-end gap-3 mt-4">
                <a href="{{ route('admin.tables.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50">
                    Cancel
                </a>
                <button wire:click="saveLayout" class="px-6 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 font-medium">
                    Save
                </button>
            </div>
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
                            <label class="block text-sm font-medium text-gray-700 mb-1">* Section name</label>
                            <input type="text" wire:model="sectionForm.name" class="w-full border border-gray-200 rounded-xl px-4 py-2 focus:ring-orange-500 focus:border-orange-500" placeholder="e.g. VIP, Outdoor">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">* Layout size</label>
                            <div class="flex gap-2">
                                @foreach(['1280x620' => '1280x620', '800x800' => '800x800', '620x1280' => '620x1280'] as $size => $label)
                                    <label class="flex-1">
                                        <input type="radio" wire:model="sectionForm.layout_size" value="{{ $size }}" class="sr-only peer">
                                        <div class="p-3 border-2 rounded-xl text-center cursor-pointer transition-all peer-checked:border-orange-500 peer-checked:bg-orange-50 border-gray-200 hover:border-gray-300">
                                            <span class="text-sm font-medium">{{ $label }}</span>
                                        </div>
                                    </label>
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

    {{-- Add Table Modal --}}
    @if($showAddTableModal)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-gray-900/80 backdrop-blur-sm" wire:click="$set('showAddTableModal', false)"></div>
                <div class="relative bg-white rounded-2xl shadow-2xl max-w-md w-full p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Add Table</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Table Name</label>
                            <input type="text" wire:model="newTableName" class="w-full border border-gray-200 rounded-xl px-4 py-2 focus:ring-orange-500 focus:border-orange-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Capacity</label>
                            <input type="number" wire:model="newTableCapacity" min="1" class="w-full border border-gray-200 rounded-xl px-4 py-2 focus:ring-orange-500 focus:border-orange-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Shape</label>
                            <div class="flex gap-3">
                                <label class="flex-1">
                                    <input type="radio" wire:model="newTableShape" value="square" class="sr-only peer">
                                    <div class="p-4 border-2 rounded-xl text-center cursor-pointer transition-all peer-checked:border-orange-500 peer-checked:bg-orange-50 border-gray-200">
                                        <div class="w-10 h-10 bg-gray-300 rounded-lg mx-auto mb-2"></div>
                                        <span class="text-sm font-medium">Square</span>
                                    </div>
                                </label>
                                <label class="flex-1">
                                    <input type="radio" wire:model="newTableShape" value="round" class="sr-only peer">
                                    <div class="p-4 border-2 rounded-xl text-center cursor-pointer transition-all peer-checked:border-orange-500 peer-checked:bg-orange-50 border-gray-200">
                                        <div class="w-10 h-10 bg-gray-300 rounded-full mx-auto mb-2"></div>
                                        <span class="text-sm font-medium">Round</span>
                                    </div>
                                </label>
                            </div>
                        </div>
                        <div class="flex gap-3 pt-2">
                            <button type="button" wire:click="$set('showAddTableModal', false)" class="flex-1 px-4 py-2 border border-gray-200 rounded-xl text-gray-600 hover:bg-gray-50">Cancel</button>
                            <button type="button" wire:click="addTableToLayout" class="flex-1 px-4 py-2 bg-orange-500 text-white rounded-xl hover:bg-orange-600 font-medium">Add Table</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Settings Modal --}}
    @if($showSettingsModal)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-gray-900/80 backdrop-blur-sm" wire:click="$set('showSettingsModal', false)"></div>
                <div class="relative bg-white rounded-2xl shadow-2xl max-w-md w-full p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Table Layout Settings</h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                            <div>
                                <p class="font-medium text-gray-900">Enable Table Layout</p>
                                <p class="text-sm text-gray-500">Show visual table layout in POS</p>
                            </div>
                            <button wire:click="$toggle('enableTableLayout')"
                                class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors {{ $enableTableLayout ? 'bg-orange-500' : 'bg-gray-300' }}">
                                <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform {{ $enableTableLayout ? 'translate-x-6' : 'translate-x-1' }}"></span>
                            </button>
                        </div>
                        <div class="flex gap-3 pt-2">
                            <button type="button" wire:click="$set('showSettingsModal', false)" class="flex-1 px-4 py-2 border border-gray-200 rounded-xl text-gray-600 hover:bg-gray-50">Cancel</button>
                            <button type="button" wire:click="saveSettings" class="flex-1 px-4 py-2 bg-orange-500 text-white rounded-xl hover:bg-orange-600 font-medium">Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Merge Section Modal --}}
    @if($showMergeSectionModal)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-gray-900/80 backdrop-blur-sm" wire:click="$set('showMergeSectionModal', false)"></div>
                <div class="relative bg-white rounded-2xl shadow-2xl max-w-md w-full p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Merge Sections</h3>
                    <p class="text-sm text-gray-500 mb-4">Select sections to merge. Tables will be moved to the first selected section.</p>
                    <div class="space-y-2 mb-4 max-h-64 overflow-y-auto">
                        @foreach($sections as $section)
                            <label class="flex items-center p-3 border rounded-xl cursor-pointer hover:bg-gray-50 {{ in_array($section->id, $sectionsToMerge) ? 'border-purple-500 bg-purple-50' : 'border-gray-200' }}">
                                <input type="checkbox" wire:click="toggleSectionForMerge({{ $section->id }})" {{ in_array($section->id, $sectionsToMerge) ? 'checked' : '' }} class="sr-only">
                                <div class="w-5 h-5 rounded border-2 flex items-center justify-center mr-3 {{ in_array($section->id, $sectionsToMerge) ? 'bg-purple-500 border-purple-500' : 'border-gray-300' }}">
                                    @if(in_array($section->id, $sectionsToMerge))
                                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    @endif
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">{{ $section->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $section->tables->count() }} tables</p>
                                </div>
                            </label>
                        @endforeach
                    </div>
                    <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-3 mb-4">
                        <p class="text-sm text-yellow-700">⚠️ Merging will reset the original layout. Operate with caution.</p>
                    </div>
                    <div class="flex gap-3">
                        <button type="button" wire:click="$set('showMergeSectionModal', false)" class="flex-1 px-4 py-2 border border-gray-200 rounded-xl text-gray-600 hover:bg-gray-50">Cancel</button>
                        <button type="button" wire:click="confirmMergeSections" class="flex-1 px-4 py-2 bg-purple-500 text-white rounded-xl hover:bg-purple-600 font-medium" {{ count($sectionsToMerge) < 2 ? 'disabled' : '' }}>Merge</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-xl shadow-lg" x-data x-init="setTimeout(() => $el.remove(), 3000)">
            {{ session('success') }}
        </div>
    @endif

    <script>
        function tableLayoutDesigner(initialTables, initialElements, width, height, grid) {
            return {
                // Data
                tables: initialTables || [],
                elements: initialElements || [],
                canvasWidth: width || 1280,
                canvasHeight: height || 620,
                gridSize: grid || 20,
                
                // Selection
                selectedId: null,
                selectedType: null,
                
                // Dragging
                isDragging: false,
                dragStartX: 0,
                dragStartY: 0,
                dragOffsetX: 0,
                dragOffsetY: 0,
                
                // Resizing
                isResizing: false,
                resizeHandle: null,
                resizeStartX: 0,
                resizeStartY: 0,
                resizeStartWidth: 0,
                resizeStartHeight: 0,
                resizeStartPosX: 0,
                resizeStartPosY: 0,
                
                init() {
                    // Listen for Livewire updates
                    Livewire.on('tablesUpdated', (data) => {
                        if (data.tables) this.tables = data.tables;
                        if (data.elements) this.elements = data.elements;
                    });
                },
                
                getSelectedItem() {
                    if (this.selectedType === 'table') {
                        return this.tables.find(t => t.id === this.selectedId);
                    } else if (this.selectedType === 'element') {
                        return this.elements.find(e => e.id === this.selectedId);
                    }
                    return null;
                },
                
                deselectAll() {
                    this.selectedId = null;
                    this.selectedType = null;
                    this.$wire.set('selectedElement', null);
                },
                
                deleteSelected() {
                    if (this.selectedId && this.selectedType) {
                        this.$wire.deleteSelectedElement();
                        if (this.selectedType === 'table') {
                            this.tables = this.tables.filter(t => t.id !== this.selectedId);
                        } else {
                            this.elements = this.elements.filter(e => e.id !== this.selectedId);
                        }
                        this.deselectAll();
                    }
                },
                
                startDrag(event, id, type) {
                    this.selectedId = id;
                    this.selectedType = type;
                    this.isDragging = true;
                    
                    const item = this.getSelectedItem();
                    if (!item) return;
                    
                    const canvas = this.$refs.canvas;
                    const canvasRect = canvas.getBoundingClientRect();
                    
                    this.dragStartX = event.clientX;
                    this.dragStartY = event.clientY;
                    this.dragOffsetX = event.clientX - canvasRect.left - (item.position_x || 100);
                    this.dragOffsetY = event.clientY - canvasRect.top - (item.position_y || 100);
                    
                    this.$wire.selectElement(id, type);
                    event.preventDefault();
                },
                
                startResize(event, handle) {
                    this.isResizing = true;
                    this.resizeHandle = handle;
                    
                    const item = this.getSelectedItem();
                    if (!item) return;
                    
                    this.resizeStartX = event.clientX;
                    this.resizeStartY = event.clientY;
                    this.resizeStartWidth = item.width || 80;
                    this.resizeStartHeight = item.height || 80;
                    this.resizeStartPosX = item.position_x || 100;
                    this.resizeStartPosY = item.position_y || 100;
                    
                    event.preventDefault();
                },
                
                onMouseMove(event) {
                    if (this.isDragging && !this.isResizing) {
                        this.handleDrag(event);
                    } else if (this.isResizing) {
                        this.handleResize(event);
                    }
                },
                
                handleDrag(event) {
                    const item = this.getSelectedItem();
                    if (!item) return;
                    
                    const canvas = this.$refs.canvas;
                    const canvasRect = canvas.getBoundingClientRect();
                    
                    let x = event.clientX - canvasRect.left - this.dragOffsetX;
                    let y = event.clientY - canvasRect.top - this.dragOffsetY;
                    
                    // Snap to grid
                    x = Math.round(x / this.gridSize) * this.gridSize;
                    y = Math.round(y / this.gridSize) * this.gridSize;
                    
                    // Clamp to canvas
                    const itemWidth = item.width || 80;
                    const itemHeight = item.height || 80;
                    x = Math.max(0, Math.min(x, this.canvasWidth - itemWidth));
                    y = Math.max(0, Math.min(y, this.canvasHeight - itemHeight));
                    
                    // Update local state
                    item.position_x = x;
                    item.position_y = y;
                },
                
                handleResize(event) {
                    const item = this.getSelectedItem();
                    if (!item) return;
                    
                    const deltaX = event.clientX - this.resizeStartX;
                    const deltaY = event.clientY - this.resizeStartY;
                    
                    let newWidth = this.resizeStartWidth;
                    let newHeight = this.resizeStartHeight;
                    let newX = this.resizeStartPosX;
                    let newY = this.resizeStartPosY;
                    
                    // Handle different resize directions
                    switch (this.resizeHandle) {
                        case 'se':
                            newWidth = Math.max(40, this.resizeStartWidth + deltaX);
                            newHeight = Math.max(40, this.resizeStartHeight + deltaY);
                            break;
                        case 'sw':
                            newWidth = Math.max(40, this.resizeStartWidth - deltaX);
                            newHeight = Math.max(40, this.resizeStartHeight + deltaY);
                            newX = this.resizeStartPosX + (this.resizeStartWidth - newWidth);
                            break;
                        case 'ne':
                            newWidth = Math.max(40, this.resizeStartWidth + deltaX);
                            newHeight = Math.max(40, this.resizeStartHeight - deltaY);
                            newY = this.resizeStartPosY + (this.resizeStartHeight - newHeight);
                            break;
                        case 'nw':
                            newWidth = Math.max(40, this.resizeStartWidth - deltaX);
                            newHeight = Math.max(40, this.resizeStartHeight - deltaY);
                            newX = this.resizeStartPosX + (this.resizeStartWidth - newWidth);
                            newY = this.resizeStartPosY + (this.resizeStartHeight - newHeight);
                            break;
                        case 'e':
                            newWidth = Math.max(40, this.resizeStartWidth + deltaX);
                            break;
                        case 'w':
                            newWidth = Math.max(40, this.resizeStartWidth - deltaX);
                            newX = this.resizeStartPosX + (this.resizeStartWidth - newWidth);
                            break;
                        case 'n':
                            newHeight = Math.max(40, this.resizeStartHeight - deltaY);
                            newY = this.resizeStartPosY + (this.resizeStartHeight - newHeight);
                            break;
                        case 's':
                            newHeight = Math.max(40, this.resizeStartHeight + deltaY);
                            break;
                    }
                    
                    // Snap to grid
                    newWidth = Math.round(newWidth / this.gridSize) * this.gridSize;
                    newHeight = Math.round(newHeight / this.gridSize) * this.gridSize;
                    newX = Math.round(newX / this.gridSize) * this.gridSize;
                    newY = Math.round(newY / this.gridSize) * this.gridSize;
                    
                    // Ensure minimum size
                    newWidth = Math.max(40, newWidth);
                    newHeight = Math.max(40, newHeight);
                    
                    // Update local state
                    item.width = newWidth;
                    item.height = newHeight;
                    item.position_x = newX;
                    item.position_y = newY;
                },
                
                onMouseUp(event) {
                    if (this.isDragging || this.isResizing) {
                        const item = this.getSelectedItem();
                        if (item) {
                            // Save to server
                            if (this.selectedType === 'table') {
                                this.$wire.handleElementMoved(this.selectedId, 'table', item.position_x, item.position_y);
                                if (this.isResizing) {
                                    this.$wire.set('elementWidth', item.width);
                                    this.$wire.set('elementHeight', item.height);
                                    this.$wire.updateElementProperties();
                                }
                            } else {
                                this.$wire.handleElementMoved(this.selectedId, 'element', item.position_x, item.position_y);
                                if (this.isResizing) {
                                    this.$wire.set('elementWidth', item.width);
                                    this.$wire.set('elementHeight', item.height);
                                    this.$wire.updateElementProperties();
                                }
                            }
                        }
                    }
                    
                    this.isDragging = false;
                    this.isResizing = false;
                    this.resizeHandle = null;
                }
            }
        }
    </script>
</div>
