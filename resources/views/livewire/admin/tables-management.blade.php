<div class="py-8 bg-gray-50 min-h-screen" wire:poll.5s>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Success Message -->
        @if (session()->has('message'))
        <div class="mb-6 rounded-xl bg-emerald-50 border border-emerald-100 p-4 flex items-center shadow-sm"
             x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-emerald-800 myanmar-text">{{ session('message') }}</p>
            </div>
        </div>
        @endif

        <!-- Header -->
        <div class="sm:flex sm:items-center sm:justify-between mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 myanmar-text">စားပွဲများ စီမံခန့်ခွဲမှု</h1>
                <p class="mt-2 text-sm text-gray-600 myanmar-text">ဆိုင်အတွင်းရှိ စားပွဲများကို စီမံခန့်ခွဲနိုင်ပါသည်။</p>
            </div>
            <div class="mt-4 sm:mt-0">
                <button wire:click="create" class="inline-flex items-center px-4 py-2 border border-transparent rounded-xl shadow-sm text-sm font-medium text-white bg-gray-900 hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900 transition-all">
                    <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span class="myanmar-text">စားပွဲသစ်</span>
                </button>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Search -->
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="စားပွဲအမည် ရှာရန်..." class="block w-full pl-10 pr-3 py-2.5 border border-gray-200 rounded-xl leading-5 bg-gray-50 placeholder-gray-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500 sm:text-sm transition duration-150 ease-in-out myanmar-text">
                </div>

                <!-- Status Filter -->
                <div>
                    <select wire:model.live="statusFilter" class="block w-full py-2.5 pl-3 pr-10 border border-gray-200 rounded-xl leading-5 bg-gray-50 focus:outline-none focus:bg-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500 sm:text-sm transition duration-150 ease-in-out myanmar-text">
                        <option value="">အခြေအနေ အားလုံး</option>
                        <option value="available">ရရှိနိုင်သော / Available</option>
                        <option value="occupied">အသုံးပြုနေသော / Occupied</option>
                        <option value="reserved">ကြိုတင်မှာထားသော / Reserved</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Tables Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 md:gap-6">
            @forelse($tables as $table)
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden border 
                @if($table->status === 'available') border-emerald-200
                @elseif($table->status === 'occupied') border-rose-200
                @else border-amber-200
                @endif
                hover:shadow-md transition-all duration-200 group">
                
                <div class="p-5">
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex-1">
                            <h3 class="text-lg font-bold text-gray-900 myanmar-text">{{ $table->name_mm }}</h3>
                            <p class="text-xs text-gray-500 font-medium">{{ $table->name }}</p>
                        </div>
                        <div>
                            @if($table->status === 'available')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-100 myanmar-text">
                                ရရှိနိုင်သော
                            </span>
                            @elseif($table->status === 'occupied')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-xs font-medium bg-rose-50 text-rose-700 border border-rose-100 myanmar-text">
                                အသုံးပြုနေသော
                            </span>
                            @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-xs font-medium bg-amber-50 text-amber-700 border border-amber-100 myanmar-text">
                                မှာထားသော
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="space-y-3 mb-4">
                        <div class="flex items-center text-sm">
                            <div class="w-8 h-8 rounded-lg bg-gray-50 flex items-center justify-center mr-3 text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-xs text-gray-500 myanmar-text">ဧည့်သည်</span>
                                <span class="font-semibold text-gray-900">{{ $table->capacity }} <span class="text-xs font-normal myanmar-text">ဦး</span></span>
                            </div>
                        </div>

                        <div class="flex items-center text-sm">
                            <div class="w-8 h-8 rounded-lg bg-gray-50 flex items-center justify-center mr-3 text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-xs text-gray-500 myanmar-text">စုစုပေါင်း အော်ဒါ</span>
                                <span class="font-semibold text-gray-900">{{ $table->orders_count }}</span>
                            </div>
                        </div>

                        @if(!$table->is_active)
                        <div class="flex items-center text-sm bg-gray-50 rounded-lg p-2">
                            <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                            <span class="myanmar-text text-gray-600 text-xs">ယာယီပိတ်ထားသည်</span>
                        </div>
                        @endif

                        @if($table->activeOrder)
                        <div class="flex items-center text-sm bg-blue-50 rounded-lg p-2 border border-blue-100">
                            <svg class="w-4 h-4 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <div class="flex flex-col">
                                <span class="text-xs text-blue-600 myanmar-text">လက်ရှိအော်ဒါ</span>
                                <span class="font-semibold text-blue-700">#{{ $table->activeOrder->order_number }}</span>
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Actions Footer -->
                    <div class="pt-4 border-t border-gray-100 flex items-center justify-between">
                        <div class="flex space-x-1">
                            <button wire:click="toggleActive({{ $table->id }})" class="p-1.5 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-50 transition-colors" title="အခြေအနေ ပြောင်းရန်">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </button>
                            <button wire:click="edit({{ $table->id }})" class="p-1.5 rounded-lg text-primary-600 hover:text-primary-700 hover:bg-primary-50 transition-colors" title="ပြင်ဆင်ရန်">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </button>
                            <button wire:click="confirmDelete({{ $table->id }})" class="p-1.5 rounded-lg text-rose-600 hover:text-rose-700 hover:bg-rose-50 transition-colors" title="ဖျက်ရန်">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </div>
                        
                        <!-- Quick Status Toggle -->
                        <div class="flex space-x-1">
                            @if($table->status !== 'available')
                            <button wire:click="changeStatus({{ $table->id }}, 'available')" class="p-1.5 rounded-lg text-emerald-600 hover:bg-emerald-50 transition-colors text-xs font-medium" title="Set Available">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </button>
                            @endif
                            @if($table->status !== 'occupied')
                            <button wire:click="changeStatus({{ $table->id }}, 'occupied')" class="p-1.5 rounded-lg text-rose-600 hover:bg-rose-50 transition-colors text-xs font-medium" title="Set Occupied">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full">
                <div class="bg-white rounded-2xl shadow-sm p-12 text-center border border-gray-100">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 myanmar-text">စားပွဲများ မရှိပါ</h3>
                    <p class="mt-1 text-sm text-gray-500 myanmar-text">စားပွဲအသစ် စတင်ထည့်သွင်းပါ။</p>
                </div>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($tables->hasPages())
        <div class="mt-6">
            {{ $tables->links() }}
        </div>
        @endif
    </div>

    <!-- Create/Edit Modal -->
    @if($showModal)
    <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm flex items-center justify-center z-50 p-4 transition-opacity">
        <div class="bg-white rounded-2xl shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto transform transition-all">
            <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center">
                <h3 class="text-xl font-bold text-gray-900 myanmar-text">
                    {{ $editMode ? 'စားပွဲ ပြင်ဆင်ရန်' : 'စားပွဲအသစ် ထည့်ရန်' }}
                </h3>
                <button wire:click="closeModal" class="text-gray-400 hover:text-gray-500 focus:outline-none transition-colors">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <form wire:submit.prevent="save">
                <div class="px-6 py-6 space-y-6">
                    <!-- Names Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Name (English) <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="name" class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm p-2.5" placeholder="e.g. Table 1">
                            @error('name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2 myanmar-text">အမည် (မြန်မာ) <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="name_mm" class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm myanmar-text p-2.5" placeholder="ဥပမာ - စားပွဲ ၁">
                            @error('name_mm') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Capacity & Status -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2 myanmar-text">ဧည့်သည် အရေအတွက် <span class="text-red-500">*</span></label>
                            <input type="number" wire:model="capacity" class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm p-2.5" min="1" max="50">
                            @error('capacity') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2 myanmar-text">အခြေအနေ <span class="text-red-500">*</span></label>
                            <select wire:model="status" class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm p-2.5 appearance-none myanmar-text">
                                <option value="available">ရရှိနိုင်သော / Available</option>
                                <option value="occupied">အသုံးပြုနေသော / Occupied</option>
                                <option value="reserved">ကြိုတင်မှာထားသော / Reserved</option>
                            </select>
                            @error('status') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Sort Order -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2 myanmar-text">စီစဉ်မှု အစဉ်</label>
                        <input type="number" wire:model="sort_order" class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm p-2.5" min="0">
                        @error('sort_order') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Active Toggle -->
                    <div class="flex items-center justify-between bg-gray-50 p-4 rounded-xl border border-gray-100">
                        <span class="flex flex-col">
                            <span class="text-sm font-medium text-gray-900 myanmar-text">အသုံးပြုမည်</span>
                            <span class="text-xs text-gray-500 myanmar-text">ဖွင့်ထားပါက အရောင်းစနစ်တွင် ပေါ်နေမည်ဖြစ်သည်။</span>
                        </span>
                        <button type="button" wire:click="$toggle('is_active')" class="relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 {{ $is_active ? 'bg-green-500' : 'bg-gray-200' }}">
                            <span class="sr-only">Use setting</span>
                            <span aria-hidden="true" class="pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200 {{ $is_active ? 'translate-x-5' : 'translate-x-0' }}"></span>
                        </button>
                    </div>
                </div>

                <div class="px-6 py-5 bg-gray-50 border-t border-gray-100 flex justify-end space-x-3 rounded-b-2xl">
                    <button type="button" wire:click="closeModal" class="px-4 py-2.5 bg-white border border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50 font-medium text-sm transition-colors shadow-sm myanmar-text">
                        မလုပ်တော့ပါ
                    </button>
                    <button type="submit" class="px-4 py-2.5 bg-gray-900 border border-transparent rounded-xl text-white hover:bg-gray-800 font-medium text-sm transition-colors shadow-sm myanmar-text">
                        {{ $editMode ? 'ပြင်ဆင်မည်' : 'ထည့်သွင်းမည်' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <!-- Delete Confirmation Modal -->
    @if($deleteConfirm)
    <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm flex items-center justify-center z-50 p-4 transition-opacity">
        <div class="bg-white rounded-2xl shadow-xl max-w-sm w-full overflow-hidden transform transition-all">
            <div class="p-6">
                <div class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 rounded-full mb-4">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-center text-gray-900 mb-2 myanmar-text">ဖျက်ရန် သေချာပါသလား?</h3>
                <p class="text-sm text-center text-gray-500 myanmar-text">ဤစားပွဲကို ဖျက်လိုက်ပါက ပြန်ယူ၍မရနိုင်ပါ။</p>
            </div>
            <div class="px-6 py-4 bg-gray-50 flex justify-center space-x-3 border-t border-gray-100">
                <button type="button" wire:click="$set('deleteConfirm', false)" class="px-4 py-2.5 bg-white border border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50 font-medium text-sm transition-colors shadow-sm myanmar-text">
                    မလုပ်တော့ပါ
                </button>
                <button type="button" wire:click="delete" class="px-4 py-2.5 bg-red-600 border border-transparent rounded-xl text-white hover:bg-red-700 font-medium text-sm transition-colors shadow-sm myanmar-text">
                    ဖျက်မည်
                </button>
            </div>
        </div>
    </div>
    @endif
</div>
