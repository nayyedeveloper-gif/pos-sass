<div class="p-6">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 myanmar-text">Loyalty Program</h1>
        <p class="text-gray-500 text-sm">ဖောက်သည် အမှတ်စနစ် စီမံခန့်ခွဲမှု</p>
    </div>

    <!-- Flash Messages -->
    @if(session('message'))
        <div class="mb-4 p-4 bg-green-100 border border-green-200 text-green-700 rounded-lg">{{ session('message') }}</div>
    @endif
    @if(session('error'))
        <div class="mb-4 p-4 bg-red-100 border border-red-200 text-red-700 rounded-lg">{{ session('error') }}</div>
    @endif

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 myanmar-text">စုစုပေါင်း ဖောက်သည်</p>
                    <p class="text-2xl font-bold text-gray-800">{{ number_format($totalCustomers) }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-full bg-yellow-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 myanmar-text">လက်ရှိ အမှတ်စုစုပေါင်း</p>
                    <p class="text-2xl font-bold text-gray-800">{{ number_format($totalPoints) }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 myanmar-text">ရရှိခဲ့သော အမှတ်</p>
                    <p class="text-2xl font-bold text-green-600">+{{ number_format($totalEarned) }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 myanmar-text">အသုံးပြုခဲ့သော အမှတ်</p>
                    <p class="text-2xl font-bold text-red-600">-{{ number_format($totalRedeemed) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Search -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-6">
        <div class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="ဖောက်သည်အမည်၊ ဖုန်းနံပါတ် သို့မဟုတ် ကုဒ်ဖြင့် ရှာဖွေပါ..." class="form-input w-full">
            </div>
        </div>
    </div>

    <!-- Customers Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ဖောက်သည်</th>
                        <th wire:click="sortBy('loyalty_points')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                            <div class="flex items-center gap-1">
                                အမှတ်
                                @if($sortBy === 'loyalty_points')
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $sortDirection === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"></path>
                                    </svg>
                                @endif
                            </div>
                        </th>
                        <th wire:click="sortBy('total_spent')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                            <div class="flex items-center gap-1">
                                စုစုပေါင်းသုံးစွဲမှု
                                @if($sortBy === 'total_spent')
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $sortDirection === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"></path>
                                    </svg>
                                @endif
                            </div>
                        </th>
                        <th wire:click="sortBy('visit_count')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                            <div class="flex items-center gap-1">
                                လာရောက်မှု
                                @if($sortBy === 'visit_count')
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $sortDirection === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"></path>
                                    </svg>
                                @endif
                            </div>
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($customers as $customer)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-full bg-primary-100 flex items-center justify-center text-primary-700 font-bold">
                                        {{ substr($customer->name, 0, 1) }}
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $customer->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $customer->phone }}</div>
                                        <div class="text-xs text-gray-400">{{ $customer->customer_code }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-1">
                                    <svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                    </svg>
                                    <span class="text-lg font-bold text-gray-900">{{ number_format($customer->loyalty_points) }}</span>
                                </div>
                                <div class="text-xs text-gray-500">≈ {{ number_format($customer->points_value) }} Ks</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ number_format($customer->total_spent) }} Ks
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $customer->visit_count }} ကြိမ်
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-2">
                                    <button wire:click="viewHistory({{ $customer->id }})" class="text-blue-600 hover:text-blue-900" title="View History">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </button>
                                    <button wire:click="openAdjustModal({{ $customer->id }})" class="text-green-600 hover:text-green-900" title="Adjust Points">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                ဖောက်သည် မရှိပါ
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $customers->links() }}
        </div>
    </div>

    <!-- Adjust Points Modal -->
    @if($showAdjustModal && $selectedCustomer)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75" wire:click="closeModals"></div>
                <div class="bg-white rounded-lg shadow-xl max-w-md w-full relative z-10">
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">အမှတ် ပြင်ဆင်ရန်</h3>
                        <p class="text-sm text-gray-600 mb-4">{{ $selectedCustomer->name }} - လက်ရှိ: {{ number_format($selectedCustomer->loyalty_points) }} pts</p>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="form-label">လုပ်ဆောင်မှု</label>
                                <select wire:model="adjustmentType" class="form-select">
                                    <option value="add">အမှတ်ထည့်ရန်</option>
                                    <option value="deduct">အမှတ်နုတ်ရန်</option>
                                </select>
                            </div>
                            <div>
                                <label class="form-label">အမှတ်အရေအတွက်</label>
                                <input type="number" wire:model="adjustmentPoints" class="form-input" min="1">
                                @error('adjustmentPoints') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="form-label">အကြောင်းပြချက်</label>
                                <input type="text" wire:model="adjustmentReason" class="form-input" placeholder="e.g., Birthday bonus, Correction...">
                                @error('adjustmentReason') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-6 py-3 flex justify-end gap-2">
                        <button wire:click="closeModals" class="btn btn-secondary">ပယ်ဖျက်ရန်</button>
                        <button wire:click="adjustPoints" class="btn btn-primary">အတည်ပြုရန်</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- History Modal -->
    @if($showHistoryModal && $selectedCustomer)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75" wire:click="closeModals"></div>
                <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full relative z-10 max-h-[80vh] overflow-hidden flex flex-col">
                    <div class="p-6 border-b">
                        <h3 class="text-lg font-bold text-gray-900">အမှတ်မှတ်တမ်း - {{ $selectedCustomer->name }}</h3>
                        <p class="text-sm text-gray-500">လက်ရှိ: {{ number_format($selectedCustomer->loyalty_points) }} pts</p>
                    </div>
                    <div class="flex-1 overflow-y-auto p-6">
                        <div class="space-y-3">
                            @forelse($transactions as $tx)
                                <div class="flex items-center justify-between p-3 rounded-lg {{ $tx->type === 'earn' ? 'bg-green-50' : 'bg-red-50' }}">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $tx->description }}</p>
                                        <p class="text-xs text-gray-500">{{ $tx->created_at->format('M d, Y h:i A') }}</p>
                                        @if($tx->order)
                                            <p class="text-xs text-gray-400">Order #{{ $tx->order->order_number }}</p>
                                        @endif
                                    </div>
                                    <div class="text-right">
                                        <span class="text-lg font-bold {{ $tx->type === 'earn' ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $tx->type === 'earn' ? '+' : '' }}{{ number_format($tx->points) }}
                                        </span>
                                        <p class="text-xs text-gray-500">Balance: {{ number_format($tx->balance_after) }}</p>
                                    </div>
                                </div>
                            @empty
                                <p class="text-center text-gray-500 py-8">မှတ်တမ်း မရှိပါ</p>
                            @endforelse
                        </div>
                    </div>
                    <div class="bg-gray-50 px-6 py-3 flex justify-end">
                        <button wire:click="closeModals" class="btn btn-secondary">ပိတ်ရန်</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
