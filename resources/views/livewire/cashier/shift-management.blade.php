<div>
    @if(!$hasOpenShift)
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-lg font-semibold mb-4 text-gray-900 myanmar-text">အဆိုင်း ဖွင့်မည် / Open Shift</h2>
            <form wire:submit.prevent="openShift">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1 myanmar-text">အဖွင့်ငွေပမာဏ / Opening Amount</label>
                    <div class="relative rounded-md shadow-sm">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <span class="text-gray-500 sm:text-sm">Ks</span>
                        </div>
                        <input type="number" wire:model="openingAmount" class="block w-full rounded-md border-gray-300 pl-10 focus:border-primary-500 focus:ring-primary-500 sm:text-sm" placeholder="0.00" step="0.01">
                    </div>
                    @error('openingAmount') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                    <span class="myanmar-text">အဆိုင်း စတင်မည်</span> / Start Shift
                </button>
            </form>
        </div>
    @else
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-lg font-semibold mb-4 text-gray-900 flex justify-between items-center">
                <span class="myanmar-text">လက်ရှိ အဆိုင်း / Current Shift</span>
                <span class="text-sm font-normal text-gray-500">{{ $currentShift->started_at->format('M d, Y h:i A') }}</span>
            </h2>
            
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div class="bg-gray-50 p-3 rounded">
                    <p class="text-sm text-gray-500 myanmar-text">အဖွင့်ငွေ</p>
                    <p class="text-lg font-bold">{{ number_format($currentShift->opening_amount) }} Ks</p>
                </div>
                <div class="bg-blue-50 p-3 rounded">
                    <p class="text-sm text-blue-500 myanmar-text">ရောင်းရငွေ (Cash)</p>
                    <p class="text-lg font-bold text-blue-700">{{ number_format($cashSales) }} Ks</p>
                </div>
                <div class="bg-green-50 p-3 rounded col-span-2">
                    <p class="text-sm text-green-500 myanmar-text">စုစုပေါင်း မျှော်မှန်းငွေ (Expected)</p>
                    <p class="text-xl font-bold text-green-700">{{ number_format($expectedAmount) }} Ks</p>
                </div>
            </div>

            <hr class="my-6 border-gray-200">

            <h3 class="text-md font-semibold mb-4 text-gray-900 myanmar-text">အဆိုင်း ပိတ်မည် / Close Shift</h3>
            <form wire:submit.prevent="closeShift">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1 myanmar-text">လက်ကျန်ငွေ / Closing Cash Amount</label>
                    <div class="relative rounded-md shadow-sm">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <span class="text-gray-500 sm:text-sm">Ks</span>
                        </div>
                        <input type="number" wire:model.live="closingAmount" class="block w-full rounded-md border-gray-300 pl-10 focus:border-primary-500 focus:ring-primary-500 sm:text-sm" placeholder="0.00" step="0.01">
                    </div>
                    @error('closingAmount') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                @if(is_numeric($closingAmount))
                    <div class="mb-4 p-3 rounded {{ $difference >= 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        <div class="flex justify-between items-center">
                            <span class="myanmar-text font-medium">{{ $difference >= 0 ? 'ငွေပို (Surplus)' : 'ငွေလို (Shortage)' }}</span>
                            <span class="font-bold">{{ number_format(abs($difference)) }} Ks</span>
                        </div>
                    </div>
                @endif

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1 myanmar-text">မှတ်ချက် / Notes</label>
                    <textarea wire:model="notes" rows="2" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm"></textarea>
                </div>

                <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500" onclick="confirm('Are you sure you want to close this shift?') || event.stopImmediatePropagation()">
                    <span class="myanmar-text">အဆိုင်း ပိတ်မည်</span> / Close Shift
                </button>
            </form>
        </div>
    @endif
</div>
