<div class="py-8 bg-gray-50 min-h-screen">
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
                <h1 class="text-2xl font-bold text-gray-900 myanmar-text">အမျိုးအစားများ စီမံခန့်ခွဲမှု</h1>
                <p class="mt-2 text-sm text-gray-600 myanmar-text">အစားအသောက် အမျိုးအစားများကို စီမံခန့်ခွဲနိုင်ပါသည်။</p>
            </div>
            <div class="mt-4 sm:mt-0">
                <button wire:click="create" class="inline-flex items-center px-4 py-2 border border-transparent rounded-xl shadow-sm text-sm font-medium text-white bg-gray-900 hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900 transition-all">
                    <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span class="myanmar-text">အမျိုးအစားသစ်</span>
                </button>
            </div>
        </div>

        <!-- Filters & Search -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-6">
            <div class="flex flex-col sm:flex-row gap-4">
                <div class="flex-1">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <input type="text" wire:model.live.debounce.300ms="search" placeholder="အမျိုးအစားအမည် ရှာရန်..." class="block w-full pl-10 pr-3 py-2.5 border border-gray-200 rounded-xl leading-5 bg-gray-50 placeholder-gray-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500 sm:text-sm transition duration-150 ease-in-out myanmar-text">
                    </div>
                </div>
                <div class="w-full sm:w-64">
                    <select wire:model.live="printerFilter" class="block w-full py-2.5 pl-3 pr-10 border border-gray-200 rounded-xl leading-5 bg-gray-50 focus:outline-none focus:bg-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500 sm:text-sm transition duration-150 ease-in-out myanmar-text">
                        <option value="">ပရင်တာ အားလုံး</option>
                        <option value="kitchen">မီးဖိုချောင် / Kitchen</option>
                        <option value="bar">သောက်စရာ / Bar</option>
                        <option value="nan_pyar">နံပြား / Nan Pyar</option>
                        <option value="none">ကောင်တာ / None</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Categories Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50/50">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider myanmar-text">အမည်</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider myanmar-text">ဖော်ပြချက်</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider myanmar-text">ပရင်တာ</th>
                            <th scope="col" class="px-6 py-4 text-center text-xs font-medium text-gray-500 uppercase tracking-wider myanmar-text">ပစ္စည်းများ</th>
                            <th scope="col" class="px-6 py-4 text-center text-xs font-medium text-gray-500 uppercase tracking-wider myanmar-text">စီစဉ်မှု</th>
                            <th scope="col" class="px-6 py-4 text-center text-xs font-medium text-gray-500 uppercase tracking-wider myanmar-text">အခြေအနေ</th>
                            <th scope="col" class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider myanmar-text">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($categories as $category)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-col">
                                    <span class="text-sm font-semibold text-gray-900 myanmar-text">{{ $category->name_mm }}</span>
                                    <span class="text-xs text-gray-500">{{ $category->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-gray-500 myanmar-text line-clamp-1">{{ $category->description ?? '-' }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($category->printer_type === 'kitchen')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-xs font-medium bg-orange-50 text-orange-700 border border-orange-100 myanmar-text">မီးဖိုချောင်</span>
                                @elseif($category->printer_type === 'bar')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-xs font-medium bg-blue-50 text-blue-700 border border-blue-100 myanmar-text">သောက်စရာ</span>
                                @elseif($category->printer_type === 'nan_pyar')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-xs font-medium bg-yellow-50 text-yellow-700 border border-yellow-100 myanmar-text">နံပြား</span>
                                @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-xs font-medium bg-gray-50 text-gray-700 border border-gray-100 myanmar-text">ကောင်တာ</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    {{ $category->items_count }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                {{ $category->sort_order }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <button wire:click="toggleActive({{ $category->id }})" 
                                        class="relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 {{ $category->is_active ? 'bg-green-500' : 'bg-gray-200' }}">
                                    <span class="sr-only">Use setting</span>
                                    <span aria-hidden="true" class="pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200 {{ $category->is_active ? 'translate-x-5' : 'translate-x-0' }}"></span>
                                </button>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-3">
                                    <button wire:click="edit({{ $category->id }})" class="text-primary-600 hover:text-primary-900 transition-colors p-1 rounded hover:bg-primary-50">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </button>
                                    <button wire:click="confirmDelete({{ $category->id }})" class="text-red-600 hover:text-red-900 transition-colors p-1 rounded hover:bg-red-50">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-500">
                                    <svg class="w-12 h-12 mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                                    <h3 class="text-lg font-medium text-gray-900 myanmar-text">အမျိုးအစားများ မရှိပါ</h3>
                                    <p class="mt-1 text-sm text-gray-500 myanmar-text">အမျိုးအစားအသစ် စတင်ထည့်သွင်းပါ။</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($categories->hasPages())
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                {{ $categories->links() }}
            </div>
            @endif
        </div>
    </div>

    <!-- Create/Edit Modal -->
    @if($showModal)
    <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm flex items-center justify-center z-50 p-4 transition-opacity">
        <div class="bg-white rounded-2xl shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto transform transition-all">
            <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center">
                <h3 class="text-xl font-bold text-gray-900 myanmar-text">
                    {{ $editMode ? 'အမျိုးအစား ပြင်ဆင်ရန်' : 'အမျိုးအစားအသစ် ထည့်ရန်' }}
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
                            <input type="text" wire:model="name" class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm p-2.5" placeholder="e.g. Coffee">
                            @error('name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2 myanmar-text">အမည် (မြန်မာ) <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="name_mm" class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm myanmar-text p-2.5" placeholder="ဥပမာ - ကော်ဖီ">
                            @error('name_mm') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2 myanmar-text">ဖော်ပြချက်</label>
                        <textarea wire:model="description" rows="3" class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm p-2.5" placeholder="အမျိုးအစားအကြောင်း အကျဉ်းချုပ်..."></textarea>
                        @error('description') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Printer & Sort Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2 myanmar-text">ပရင်တာ အမျိုးအစား <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <select wire:model="printer_type" class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm p-2.5 appearance-none myanmar-text">
                                    <option value="kitchen">မီးဖိုချောင် / Kitchen</option>
                                    <option value="bar">သောက်စရာ / Bar</option>
                                    <option value="nan_pyar">နံပြား / Nan Pyar</option>
                                    <option value="none">ကောင်တာ / None</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </div>
                            </div>
                            <p class="mt-1.5 text-xs text-gray-500 myanmar-text">
                                မှာယူသည့်ပစ္စည်းများကို မည်သည့်ပရင်တာသို့ ပို့မည်နည်း။
                            </p>
                            @error('printer_type') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2 myanmar-text">စီစဉ်မှု အစဉ်</label>
                            <input type="number" wire:model="sort_order" class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm p-2.5" min="0">
                            @error('sort_order') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
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
                <p class="text-sm text-center text-gray-500 myanmar-text">ဤအမျိုးအစားကို ဖျက်လိုက်ပါက ပြန်ယူ၍မရနိုင်ပါ။ ဆက်စပ်နေသော ပစ္စည်းများလည်း ပျက်စီးသွားနိုင်သည်။</p>
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
