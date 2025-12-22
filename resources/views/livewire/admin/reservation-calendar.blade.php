<div class="p-6">
    {{-- Knowing others is intelligence; knowing yourself is true wisdom. --}}
    
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 myanmar-text">ကြိုတင်စာရင်းသွင်းမှုများက္ခဒိန်</h1>
            <p class="text-gray-500">Reservation Calendar</p>
        </div>
        <div class="flex items-center gap-2">
            
    <!-- Calendar Navigation -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-6">
        <div class="flex items-center justify-between mb-4">
            <button wire:click="previousMonth" class="p-2 rounded-full hover:bg-gray-100">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </button>
            <h2 class="text-lg font-semibold text-gray-800">{{ $monthName }}</h2>
            <button wire:click="nextMonth" class="p-2 rounded-full hover:bg-gray-100">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
        </div>
        
        <!-- Calendar Grid -->
        <div class="grid grid-cols-7 gap-1">
            <!-- Weekday Headers -->
            @foreach(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
                <div class="text-center text-sm font-medium text-gray-500 py-2">{{ $day }}</div>
            @endforeach
            
            <!-- Calendar Days -->
            @foreach($calendarDays as $day)
                @php
                    $dayClass = 'p-2 text-center cursor-pointer rounded-full ';
                    $dayClass .= $day['isToday'] ? 'bg-blue-100 text-blue-800 font-medium ' : '';
                    $dayClass .= $day['isCurrentMonth'] ? 'text-gray-900' : 'text-gray-400';
                    $dayClass .= $day['hasReservations'] ? ' bg-yellow-50' : '';
                    
                    $isSelected = $day['date']->format('Y-m-d') === $selectedDate;
                    if ($isSelected) {
                        $dayClass .= ' bg-primary-100 text-primary-800 font-medium';
                    }
                @endphp
                <div class="flex flex-col items-center">
                    <div 
                        wire:click="selectDate('{{ $day['date']->format('Y-m-d') }}')
                        @if($day['isCurrentMonth'])
                            @click="$dispatch('date-selected', { date: '{{ $day['date']->format('Y-m-d') }}' })"
                        @endif"
                        class="{{ $dayClass }} w-10 h-10 flex items-center justify-center"
                    >
                        {{ $day['date']->format('j') }}
                    </div>
                    @if($day['hasReservations'] && $isSelected)
                        <div class="w-1.5 h-1.5 bg-yellow-500 rounded-full mt-1"></div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
    
    <!-- Time Slots -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-800 myanmar-text">
                {{ \Carbon\Carbon::parse($selectedDate)->format('F j, Y') }} 
                <span class="text-sm text-gray-500">({{ \Carbon\Carbon::parse($selectedDate)->isoFormat('dddd') }})</span>
            </h3>
        </div>
        
        <div class="divide-y divide-gray-200 max-h-[calc(100vh-400px)] overflow-y-auto">
            @foreach($timeSlots as $timeSlot)
                @php
                    $reservationsAtThisTime = $reservations[$timeSlot] ?? [];
                    $hasReservations = count($reservationsAtThisTime) > 0;
                @endphp
                <div class="p-4 hover:bg-gray-50 transition-colors duration-150">
                    <div class="flex items-start">
                        <div class="w-24 flex-shrink-0">
                            <button 
                                wire:click="selectTime('{{ $timeSlot }}')
                                @if(!$hasReservations)
                                    @click="$dispatch('time-selected', { time: '{{ $timeSlot }}' })"
                                @endif"
                                class="text-left text-sm font-medium {{ $hasReservations ? 'text-gray-400' : 'text-primary-600 hover:text-primary-800' }}"
                                {{ $hasReservations ? 'disabled' : '' }}
                            >
                                {{ \Carbon\Carbon::parse($timeSlot)->format('h:i A') }}
                            </button>
                        </div>
                        <div class="flex-1 min-w-0">
                            @if($hasReservations)
                                <div class="space-y-2">
                                    @foreach($reservationsAtThisTime as $reservation)
                                        <div class="flex items-center justify-between p-2 bg-gray-50 rounded-lg">
                                            <div>
                                                <div class="font-medium">{{ $reservation->customer_name }}</div>
                                                <div class="text-sm text-gray-500">
                                                    {{ $reservation->guest_count }} ဦး • 
                                                    {{ $reservation->table ? $reservation->table->name : 'No Table' }}
                                                    @if($reservation->notes)
                                                        • {{ Str::limit($reservation->notes, 30) }}
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <span class="px-2 py-1 text-xs rounded-full 
                                                    {{ [
                                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                                        'confirmed' => 'bg-blue-100 text-blue-800',
                                                        'completed' => 'bg-green-100 text-green-800',
                                                        'cancelled' => 'bg-red-100 text-red-800',
                                                        'no_show' => 'bg-gray-100 text-gray-800',
                                                    ][$reservation->status] ?? 'bg-gray-100 text-gray-800' }}">
                                                    {{ [
                                                        'pending' => 'စောင့်ဆိုင်းဆဲ',
                                                        'confirmed' => 'အတည်ပြုပြီး',
                                                        'completed' => 'ပြီးဆုံး',
                                                        'cancelled' => 'ပယ်ဖျက်',
                                                        'no_show' => 'မလာ',
                                                    ][$reservation->status] ?? $reservation->status }}
                                                </span>
                                                <button 
                                                    wire:click="$dispatch('edit-reservation', { id: {{ $reservation->id }} })"
                                                    class="text-gray-400 hover:text-gray-600"
                                                    title="Edit">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-sm text-gray-400">No reservations</div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    
    <!-- Add Reservation Modal -->
    <x-modal wire:model="showReservationModal">
        <x-slot name="title">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-medium text-gray-900 myanmar-text">
                    အချိန်ဇယား ထည့်ရန်
                </h3>
                <button wire:click="$set('showReservationModal', false)" class="text-gray-400 hover:text-gray-500">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </x-slot>
        
        <div class="mt-4 space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 myanmar-text">
                        ရက်စွဲ
                    </label>
                    <div class="mt-1">
                        <input type="date" 
                            value="{{ $selectedDate }}" 
                            class="form-input block w-full" 
                            disabled>
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 myanmar-text">
                        အချိန်
                    </label>
                    <div class="mt-1">
                        <input type="time" 
                            value="{{ $selectedTime }}" 
                            class="form-input block w-full" 
                            disabled>
                    </div>
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 myanmar-text">
                    ဖောက်သည်အမည် <span class="text-red-500">*</span>
                </label>
                <div class="mt-1">
                    <input type="text" 
                        wire:model="reservationDetails.customer_name" 
                        class="form-input block w-full" 
                        placeholder="ဖောက်သည်အမည်ထည့်ပါ">
                    @error('reservationDetails.customer_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 myanmar-text">
                    ဖုန်းနံပါတ် <span class="text-red-500">*</span>
                </label>
                <div class="mt-1">
                    <input type="tel" 
                        wire:model="reservationDetails.customer_phone" 
                        class="form-input block w-full" 
                        placeholder="09xxxxxxxx">
                    @error('reservationDetails.customer_phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 myanmar-text">
                        ဧည့်ဦးရေ <span class="text-red-500">*</span>
                    </label>
                    <div class="mt-1">
                        <input type="number" 
                            wire:model="reservationDetails.guest_count" 
                            min="1" 
                            max="20" 
                            class="form-input block w-full">
                        @error('reservationDetails.guest_count')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 myanmar-text">
                        စားပွဲ <span class="text-red-500">*</span>
                    </label>
                    <div class="mt-1">
                        <select wire:model="selectedTable" class="form-select block w-full">
                            <option value="">-- ရွေးချယ်ပါ --</option>
                            @foreach($tables as $table)
                                <option value="{{ $table->id }}">{{ $table->name }} ({{ $table->capacity }} ဦး)</option>
                            @endforeach
                        </select>
                        @error('selectedTable')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 myanmar-text">
                    မှတ်ချက်
                </label>
                <div class="mt-1">
                    <textarea 
                        wire:model="reservationDetails.notes" 
                        rows="3" 
                        class="form-textarea block w-full" 
                        placeholder="မှတ်ချက်များထည့်ရန်"></textarea>
                </div>
            </div>
        </div>
        
        <x-slot name="footer">
            <div class="flex justify-end gap-3">
                <button 
                    type="button" 
                    wire:click="$set('showReservationModal', false)" 
                    class="btn btn-secondary">
                    <span class="myanmar-text">ပယ်ဖျက်မည်</span>
                </button>
                <button 
                    type="button" 
                    wire:click="createReservation" 
                    class="btn btn-primary">
                    <span class="myanmar-text">သိမ်းဆည်းမည်</span>
                </button>
            </div>
        </x-slot>
    </x-modal>
    
    @push('scripts')
    <script>
        document.addEventListener('livewire:load', function () {
            // Listen for date selection from the calendar
            window.addEventListener('date-selected', event => {
                Livewire.emit('dateSelected', event.detail);
            });
            
            // Listen for time slot selection
            window.addEventListener('time-selected', event => {
                Livewire.emit('timeSelected', event.detail);
            });
            
            // Listen for edit reservation event
            window.addEventListener('edit-reservation', event => {
                // You can implement edit functionality here if needed
                console.log('Edit reservation:', event.detail.id);
            });
        });
    </script>
    @endpush
</div>
