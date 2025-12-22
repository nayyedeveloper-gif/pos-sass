<div class="p-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 myanmar-text">ကြိုတင်စာရင်းသွင်းမှုများ</h1>
            <p class="text-gray-500 text-sm">Reservation Management</p>
        </div>
        <button wire:click="openCreateModal" class="btn btn-primary flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            <span class="myanmar-text">အသစ်ထည့်ရန်</span>
        </button>
    </div>

    <!-- Flash Messages -->
    @if(session('message'))
        <div class="mb-4 p-4 bg-green-100 border border-green-200 text-green-700 rounded-lg">
            {{ session('message') }}
        </div>
    @endif

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Search -->
            <div>
                <label class="form-label myanmar-text">ရှာဖွေရန်</label>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="နာမည် သို့မဟုတ် ဖုန်းနံပါတ်..." class="form-input">
            </div>
            
            <!-- Date Filter -->
            <div>
                <label class="form-label myanmar-text">ရက်စွဲ</label>
                <input type="date" wire:model.live="dateFilter" class="form-input">
            </div>
            
            <!-- Status Filter -->
            <div>
                <label class="form-label myanmar-text">အခြေအနေ</label>
                <select wire:model.live="statusFilter" class="form-select">
                    <option value="all">အားလုံး</option>
                    <option value="pending">စောင့်ဆိုင်းဆဲ</option>
                    <option value="confirmed">အတည်ပြုပြီး</option>
                    <option value="completed">ပြီးဆုံး</option>
                    <option value="cancelled">ပယ်ဖျက်</option>
                    <option value="no_show">မလာ</option>
                </select>
            </div>
            
            <!-- Quick Filters -->
            <div class="flex items-end gap-2">
                <button wire:click="$set('dateFilter', '{{ now()->format('Y-m-d') }}')" class="btn btn-secondary text-sm">
                    ယနေ့
                </button>
                <button wire:click="$set('dateFilter', '{{ now()->addDay()->format('Y-m-d') }}')" class="btn btn-secondary text-sm">
                    မနက်ဖြန်
                </button>
                <button wire:click="$set('dateFilter', '')" class="btn btn-secondary text-sm">
                    အားလုံး
                </button>
            </div>
        </div>
    </div>

    <!-- Reservations Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider myanmar-text">ဖောက်သည်</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider myanmar-text">အချိန်</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider myanmar-text">လူဦးရေ</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider myanmar-text">စားပွဲ</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider myanmar-text">အခြေအနေ</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($reservations as $reservation)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-full bg-primary-100 flex items-center justify-center text-primary-700 font-bold">
                                        {{ substr($reservation->customer_name, 0, 1) }}
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $reservation->customer_name }}</div>
                                        <div class="text-sm text-gray-500">{{ $reservation->customer_phone }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $reservation->reservation_time->format('M d, Y') }}</div>
                                <div class="text-sm text-gray-500">{{ $reservation->reservation_time->format('h:i A') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-1 text-sm text-gray-900">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    {{ $reservation->guest_count }} ဦး
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $reservation->table ? $reservation->table->name : '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'confirmed' => 'bg-blue-100 text-blue-800',
                                        'completed' => 'bg-green-100 text-green-800',
                                        'cancelled' => 'bg-red-100 text-red-800',
                                        'no_show' => 'bg-gray-100 text-gray-800',
                                    ];
                                    $statusLabels = [
                                        'pending' => 'စောင့်ဆိုင်းဆဲ',
                                        'confirmed' => 'အတည်ပြုပြီး',
                                        'completed' => 'ပြီးဆုံး',
                                        'cancelled' => 'ပယ်ဖျက်',
                                        'no_show' => 'မလာ',
                                    ];
                                @endphp
                                <span class="px-2 py-1 text-xs font-medium rounded-full {{ $statusColors[$reservation->status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ $statusLabels[$reservation->status] ?? $reservation->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-2">
                                    @if($reservation->status === 'pending')
                                        <button wire:click="updateStatus({{ $reservation->id }}, 'confirmed')" class="text-blue-600 hover:text-blue-900" title="Confirm">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </button>
                                    @endif
                                    @if($reservation->status === 'confirmed')
                                        <button wire:click="updateStatus({{ $reservation->id }}, 'completed')" class="text-green-600 hover:text-green-900" title="Complete">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </button>
                                    @endif
                                    <button wire:click="edit({{ $reservation->id }})" class="text-gray-600 hover:text-gray-900" title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </button>
                                    @if(in_array($reservation->status, ['pending', 'confirmed']))
                                        <button wire:click="updateStatus({{ $reservation->id }}, 'cancelled')" class="text-red-600 hover:text-red-900" title="Cancel">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <p class="text-gray-500 myanmar-text">ကြိုတင်စာရင်းသွင်းမှု မရှိပါ</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $reservations->links() }}
        </div>
    </div>

    <!-- Create/Edit Modal -->
    @if($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="$set('showModal', false)"></div>

                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form wire:submit="save">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg font-bold text-gray-900 mb-4 myanmar-text">
                                {{ $isEditMode ? 'ပြင်ဆင်ရန်' : 'အသစ်ထည့်ရန်' }}
                            </h3>
                            
                            <div class="space-y-4">
                                <!-- Customer Name -->
                                <div>
                                    <label class="form-label myanmar-text">ဖောက်သည်အမည် *</label>
                                    <input type="text" wire:model="customer_name" class="form-input" required>
                                    @error('customer_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                
                                <!-- Customer Phone -->
                                <div>
                                    <label class="form-label myanmar-text">ဖုန်းနံပါတ် *</label>
                                    <input type="text" wire:model="customer_phone" class="form-input" placeholder="09xxxxxxxxx" required>
                                    @error('customer_phone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                
                                <!-- Reservation Time -->
                                <div>
                                    <label class="form-label myanmar-text">ရက်စွဲနှင့်အချိန် *</label>
                                    <input type="datetime-local" wire:model="reservation_time" class="form-input" required>
                                    @error('reservation_time') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                
                                <!-- Guest Count -->
                                <div>
                                    <label class="form-label myanmar-text">လူဦးရေ *</label>
                                    <input type="number" wire:model="guest_count" class="form-input" min="1" required>
                                    @error('guest_count') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                
                                <!-- Table Selection -->
                                <div>
                                    <label class="form-label myanmar-text">စားပွဲ (ရွေးချယ်နိုင်)</label>
                                    <select wire:model="table_id" class="form-select">
                                        <option value="">မရွေးချယ်ပါ</option>
                                        @foreach($tables as $table)
                                            <option value="{{ $table->id }}">{{ $table->name }} ({{ $table->capacity }} ဦးဆံ့)</option>
                                        @endforeach
                                    </select>
                                    @error('table_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                
                                <!-- Status (Edit Mode Only) -->
                                @if($isEditMode)
                                <div>
                                    <label class="form-label myanmar-text">အခြေအနေ</label>
                                    <select wire:model="status" class="form-select">
                                        <option value="pending">စောင့်ဆိုင်းဆဲ</option>
                                        <option value="confirmed">အတည်ပြုပြီး</option>
                                        <option value="completed">ပြီးဆုံး</option>
                                        <option value="cancelled">ပယ်ဖျက်</option>
                                        <option value="no_show">မလာ</option>
                                    </select>
                                </div>
                                @endif
                                
                                <!-- Notes -->
                                <div>
                                    <label class="form-label myanmar-text">မှတ်ချက်</label>
                                    <textarea wire:model="notes" class="form-input" rows="2" placeholder="အထူးတောင်းဆိုချက်များ..."></textarea>
                                    @error('notes') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                            <button type="submit" class="btn btn-primary">
                                <span class="myanmar-text">{{ $isEditMode ? 'သိမ်းဆည်းရန်' : 'ထည့်သွင်းရန်' }}</span>
                            </button>
                            <button type="button" wire:click="$set('showModal', false)" class="btn btn-secondary">
                                <span class="myanmar-text">ပယ်ဖျက်ရန်</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
