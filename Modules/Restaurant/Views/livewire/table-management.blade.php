<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 myanmar-text">စားပွဲ စီမံခန့်ခွဲမှု</h1>
                <p class="text-sm text-gray-500">Manage restaurant tables</p>
            </div>
            <button wire:click="openModal" class="btn-primary">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span class="myanmar-text">စားပွဲအသစ်</span>
            </button>
        </div>

        <!-- Table Stats -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-xl p-4 border border-gray-100">
                <div class="text-2xl font-bold text-gray-900">{{ $tables->count() }}</div>
                <div class="text-sm text-gray-500 myanmar-text">စုစုပေါင်း</div>
            </div>
            <div class="bg-green-50 rounded-xl p-4 border border-green-100">
                <div class="text-2xl font-bold text-green-600">{{ $tables->where('status', 'available')->count() }}</div>
                <div class="text-sm text-green-600 myanmar-text">လွတ်နေသော</div>
            </div>
            <div class="bg-red-50 rounded-xl p-4 border border-red-100">
                <div class="text-2xl font-bold text-red-600">{{ $tables->where('status', 'occupied')->count() }}</div>
                <div class="text-sm text-red-600 myanmar-text">သုံးနေသော</div>
            </div>
            <div class="bg-yellow-50 rounded-xl p-4 border border-yellow-100">
                <div class="text-2xl font-bold text-yellow-600">{{ $tables->where('status', 'reserved')->count() }}</div>
                <div class="text-sm text-yellow-600 myanmar-text">ကြိုတင်မှာထား</div>
            </div>
        </div>

        <!-- Tables Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
            @forelse($tables as $table)
                <div class="bg-white rounded-xl border-2 p-4 cursor-pointer hover:shadow-lg transition-all
                    {{ $table->status === 'available' ? 'border-green-200 hover:border-green-400' : '' }}
                    {{ $table->status === 'occupied' ? 'border-red-200 hover:border-red-400' : '' }}
                    {{ $table->status === 'reserved' ? 'border-yellow-200 hover:border-yellow-400' : '' }}"
                    wire:click="openModal({{ $table->id }})"
                >
                    <div class="text-center">
                        <div class="w-12 h-12 mx-auto mb-2 rounded-full flex items-center justify-center
                            {{ $table->status === 'available' ? 'bg-green-100 text-green-600' : '' }}
                            {{ $table->status === 'occupied' ? 'bg-red-100 text-red-600' : '' }}
                            {{ $table->status === 'reserved' ? 'bg-yellow-100 text-yellow-600' : '' }}">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </div>
                        <h3 class="font-bold text-gray-900">{{ $table->name }}</h3>
                        <p class="text-xs text-gray-500 myanmar-text">{{ $table->name_mm }}</p>
                        <p class="text-xs text-gray-400 mt-1">{{ $table->capacity }} ဦး</p>
                        
                        <div class="mt-2">
                            @if($table->status === 'available')
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">Available</span>
                            @elseif($table->status === 'occupied')
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">Occupied</span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">Reserved</span>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12 text-gray-400">
                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                    <p class="myanmar-text">စားပွဲ မရှိသေးပါ</p>
                    <button wire:click="openModal" class="mt-4 text-primary-600 hover:text-primary-700 font-medium">
                        + Add your first table
                    </button>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Modal -->
    @if($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75" wire:click="closeModal"></div>
                
                <div class="relative bg-white rounded-2xl shadow-xl max-w-md w-full p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 myanmar-text">
                        {{ $editingTable ? 'စားပွဲ ပြင်ဆင်ရန်' : 'စားပွဲအသစ် ထည့်ရန်' }}
                    </h3>
                    
                    <form wire:submit="save" class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Name (EN)</label>
                                <input type="text" wire:model="name" class="form-input" placeholder="Table 1">
                                @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1 myanmar-text">အမည် (မြန်မာ)</label>
                                <input type="text" wire:model="name_mm" class="form-input" placeholder="စားပွဲ ၁">
                                @error('name_mm') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1 myanmar-text">ထိုင်နိုင်သူ</label>
                                <input type="number" wire:model="capacity" class="form-input" min="1" max="50">
                                @error('capacity') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Floor</label>
                                <input type="text" wire:model="floor" class="form-input" placeholder="Ground">
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Section</label>
                            <input type="text" wire:model="section" class="form-input" placeholder="Main Hall">
                        </div>
                        
                        <div class="flex items-center">
                            <input type="checkbox" wire:model="is_active" id="is_active" class="form-checkbox">
                            <label for="is_active" class="ml-2 text-sm text-gray-700">Active</label>
                        </div>
                        
                        <div class="flex justify-end gap-3 pt-4">
                            @if($editingTable)
                                <button type="button" wire:click="delete({{ $editingTable->id }})" 
                                    class="px-4 py-2 text-red-600 hover:text-red-700 font-medium"
                                    onclick="return confirm('Are you sure?')">
                                    Delete
                                </button>
                            @endif
                            <button type="button" wire:click="closeModal" class="btn-secondary">Cancel</button>
                            <button type="submit" class="btn-primary">
                                {{ $editingTable ? 'Update' : 'Create' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
