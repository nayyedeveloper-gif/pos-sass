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
                <h1 class="text-2xl font-bold text-gray-900 myanmar-text">အသုံးစရိတ်များ စီမံခန့်ခွဲမှု</h1>
                <p class="mt-2 text-sm text-gray-600 myanmar-text">ဆိုင်၏ နေ့စဉ် အသုံးစရိတ်များကို မှတ်တမ်းတင်နိုင်သည်။</p>
            </div>
            <div class="mt-4 sm:mt-0 flex space-x-3">
                <button wire:click="export" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-xl shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all">
                    <svg class="w-5 h-5 mr-2 -ml-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span class="myanmar-text">Excel ထုတ်မည်</span>
                </button>
                <button wire:click="create" class="inline-flex items-center px-4 py-2 border border-transparent rounded-xl shadow-sm text-sm font-medium text-white bg-gray-900 hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900 transition-all">
                    <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    <span class="myanmar-text">အသုံးစရိတ်သစ်</span>
                </button>
            </div>
        </div>

        <!-- Summary Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            <!-- Total Amount Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 relative overflow-hidden lg:col-span-1">
                <div class="absolute top-0 right-0 -mr-4 -mt-4 w-32 h-32 rounded-full bg-rose-50 opacity-50"></div>
                <div class="relative z-10 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 myanmar-text uppercase tracking-wider">စုစုပေါင်း အသုံးစရိတ် ({{ $dateFilter === 'today' ? 'ယနေ့' : ($dateFilter === 'month' ? 'ယခုလ' : 'အားလုံး') }})</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($totalAmount, 0) }} <span class="text-sm font-normal text-gray-500">Ks</span></p>
                    </div>
                    <div class="bg-rose-100 p-3 rounded-xl">
                        <svg class="w-8 h-8 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Category Breakdown -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 lg:col-span-2 overflow-hidden">
                <h3 class="text-sm font-bold text-gray-900 myanmar-text mb-3">အမျိုးအစားအလိုက် အသုံးစရိတ်များ</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 max-h-40 overflow-y-auto pr-2 custom-scrollbar">
                    @foreach($expensesByCategory as $cat)
                    <div class="flex items-center justify-between p-3 rounded-lg bg-gray-50 border border-gray-100">
                        <div class="flex items-center space-x-3">
                            <span class="w-2 h-2 rounded-full bg-primary-500"></span>
                            <span class="text-sm text-gray-700 myanmar-text">{{ $categories[$cat->category] ?? $cat->category }}</span>
                        </div>
                        <span class="text-sm font-bold text-gray-900">{{ number_format($cat->total_amount, 0) }} Ks</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <!-- Search -->
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="ဖော်ပြချက် ရှာရန်..." class="block w-full pl-10 pr-3 py-2.5 border border-gray-200 rounded-xl leading-5 bg-gray-50 placeholder-gray-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500 sm:text-sm transition duration-150 ease-in-out myanmar-text">
                </div>

                <!-- Category Filter -->
                <div>
                    <select wire:model.live="categoryFilter" class="block w-full py-2.5 pl-3 pr-10 border border-gray-200 rounded-xl leading-5 bg-gray-50 focus:outline-none focus:bg-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500 sm:text-sm transition duration-150 ease-in-out myanmar-text">
                        <option value="">အမျိုးအစား အားလုံး</option>
                        @foreach($categories as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Date Filter -->
                <div>
                    <select wire:model.live="dateFilter" class="block w-full py-2.5 pl-3 pr-10 border border-gray-200 rounded-xl leading-5 bg-gray-50 focus:outline-none focus:bg-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500 sm:text-sm transition duration-150 ease-in-out myanmar-text">
                        <option value="today">ယနေ့ / Today</option>
                        <option value="yesterday">မနေ့က / Yesterday</option>
                        <option value="week">ဤအပတ် / This Week</option>
                        <option value="month">ဤလ / This Month</option>
                        <option value="">အားလုံး / All</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Expenses Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50/50">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider myanmar-text">ရက်စွဲ</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider myanmar-text">အမျိုးအစား</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider myanmar-text">ဖော်ပြချက်</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider myanmar-text">ပမာဏ</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider myanmar-text">ပေးချေမှု</th>
                            <th scope="col" class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider myanmar-text">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($expenses as $expense)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $expense->expense_date->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-xs font-medium bg-blue-50 text-blue-700 border border-blue-100 myanmar-text">
                                    {{ $categories[$expense->category] ?? $expense->category }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900 myanmar-text">{{ $expense->description }}</div>
                                @if($expense->receipt_number)
                                <div class="text-xs text-gray-500 mt-0.5">No: {{ $expense->receipt_number }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-bold text-rose-600">{{ number_format($expense->amount, 0) }} Ks</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ ucfirst($expense->payment_method ?? 'Cash') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-3">
                                    <button wire:click="edit({{ $expense->id }})" class="text-primary-600 hover:text-primary-900 transition-colors p-1 rounded hover:bg-primary-50" title="ပြင်ဆင်ရန်">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </button>
                                    <button wire:click="confirmDelete({{ $expense->id }})" class="text-red-600 hover:text-red-900 transition-colors p-1 rounded hover:bg-red-50" title="ဖျက်ရန်">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-500">
                                    <svg class="w-12 h-12 mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    <h3 class="text-lg font-medium text-gray-900 myanmar-text">အသုံးစရိတ် မတွေ့ပါ</h3>
                                    <p class="mt-1 text-sm text-gray-500 myanmar-text">အသုံးစရိတ်အသစ် ထည့်သွင်းပါ။</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($expenses->hasPages())
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                {{ $expenses->links() }}
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
                    {{ $editMode ? 'အသုံးစရိတ် ပြင်ဆင်ရန်' : 'အသုံးစရိတ်အသစ် ထည့်ရန်' }}
                </h3>
                <button wire:click="closeModal" class="text-gray-400 hover:text-gray-500 focus:outline-none transition-colors">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <form wire:submit.prevent="save">
                <div class="px-6 py-6 space-y-6">
                    <!-- Category & Date -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2 myanmar-text">အမျိုးအစား <span class="text-red-500">*</span></label>
                            <select wire:model="category" class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm p-2.5 appearance-none myanmar-text">
                                <option value="">ရွေးချယ်ပါ</option>
                                @foreach($categories as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('category') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2 myanmar-text">ရက်စွဲ <span class="text-red-500">*</span></label>
                            <input type="date" wire:model="expense_date" class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm p-2.5">
                            @error('expense_date') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2 myanmar-text">ဖော်ပြချက် <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="description" class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm p-2.5 myanmar-text" placeholder="ဥပမာ - မီးဖိုချောင်သုံး ပစ္စည်းများ ဝယ်ယူခြင်း">
                        @error('description') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Amount & Payment Method -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2 myanmar-text">ပမာဏ (Ks) <span class="text-red-500">*</span></label>
                            <input type="number" wire:model="amount" class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm p-2.5" placeholder="0" min="0" step="0.01">
                            @error('amount') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2 myanmar-text">ပေးချေမှု နည်းလမ်း</label>
                            <select wire:model="payment_method" class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm p-2.5 appearance-none myanmar-text">
                                <option value="cash">ငွေသား / Cash</option>
                                <option value="bank_transfer">ဘဏ်လွှဲ / Bank Transfer</option>
                                <option value="card">ကတ် / Card</option>
                                <option value="mobile_payment">မိုဘိုင်းငွေ / Mobile Payment</option>
                            </select>
                            @error('payment_method') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Receipt Number -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2 myanmar-text">ငွေလက်ခံ နံပါတ်</label>
                        <input type="text" wire:model="receipt_number" class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm p-2.5" placeholder="Optional">
                        @error('receipt_number') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Notes -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2 myanmar-text">မှတ်ချက်</label>
                        <textarea wire:model="notes" rows="3" class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm p-2.5 myanmar-text" placeholder="အခြား မှတ်ချက်များ..."></textarea>
                        @error('notes') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
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
                <p class="text-sm text-center text-gray-500 myanmar-text">ဤအသုံးစရိတ်ကို ဖျက်လိုက်ပါက ပြန်ယူ၍မရနိုင်ပါ။</p>
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
