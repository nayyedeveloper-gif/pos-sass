<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        @if (session()->has('message'))
        <div class="mb-4 bg-green-50 border-l-4 border-green-400 p-4 rounded-lg">
            <p class="text-sm text-green-700 myanmar-text">{{ session('message') }}</p>
        </div>
        @endif

        @if (session()->has('error'))
        <div class="mb-4 bg-red-50 border-l-4 border-red-400 p-4 rounded-lg">
            <p class="text-sm text-red-700 myanmar-text">{{ session('error') }}</p>
        </div>
        @endif

        <div class="mb-6 flex justify-between items-center">
            <h2 class="text-2xl font-bold text-gray-900 myanmar-text">ကုန်ပစ္စည်းစာရင်း စီမံခန့်ခွဲမှု</h2>
            <button wire:click="openModal" class="px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg">
                <span class="myanmar-text">ကုန်ပစ္စည်းအသစ် ထည့်ရန်</span>
            </button>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="ရှာဖွေရန်..." class="w-full px-4 py-2 border rounded-lg">
        </div>

        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase myanmar-text">အမည်</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">SKU</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase myanmar-text">ယူနစ်</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase myanmar-text">လက်ရှိစတော့</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase myanmar-text">အနည်းဆုံး</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase myanmar-text">ယူနစ်စျေး</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase myanmar-text">လုပ်ဆောင်ချက်</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($stockItems as $item)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $item->name }}</div>
                            @if($item->name_mm)
                            <div class="text-sm text-gray-500 myanmar-text">{{ $item->name_mm }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $item->sku ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $item->unit }}</td>
                        <td class="px-6 py-4 text-right">
                            <span class="text-sm font-medium {{ $item->is_low_stock ? 'text-red-600' : 'text-gray-900' }}">
                                {{ number_format($item->current_stock, 2) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right text-sm text-gray-900">{{ number_format($item->minimum_stock, 2) }}</td>
                        <td class="px-6 py-4 text-right text-sm text-gray-900">{{ number_format($item->unit_cost) }} Ks</td>
                        <td class="px-6 py-4 text-sm">
                            <button wire:click="edit({{ $item->id }})" class="text-primary-600 hover:text-primary-900 mr-3">Edit</button>
                            <button wire:click="delete({{ $item->id }})" wire:confirm="ဖျက်မှာ သေချာပါသလား?" class="text-red-600 hover:text-red-900">Delete</button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500 myanmar-text">ကုန်ပစ္စည်း ကောင်တာသေးပါ</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="px-6 py-4">{{ $stockItems->links() }}</div>
        </div>

        @if($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75" wire:click="closeModal"></div>
                <div class="relative bg-white rounded-lg max-w-2xl w-full p-6">
                    <h3 class="text-lg font-medium mb-4 myanmar-text">{{ $editMode ? 'ပြင်ဆင်ရန်' : 'အသစ်ထည့်ရန်' }}</h3>
                    <form wire:submit.prevent="save">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium mb-1">Name *</label>
                                <input type="text" wire:model="name" class="w-full px-4 py-2 border rounded-lg" required>
                                @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1 myanmar-text">အမည် (မြန်မာ)</label>
                                <input type="text" wire:model="name_mm" class="w-full px-4 py-2 border rounded-lg myanmar-text">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">SKU</label>
                                <input type="text" wire:model="sku" class="w-full px-4 py-2 border rounded-lg">
                                @error('sku') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1 myanmar-text">ယူနစ် *</label>
                                <input type="text" wire:model="unit" class="w-full px-4 py-2 border rounded-lg" placeholder="kg, liter, piece..." required>
                                @error('unit') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1 myanmar-text">လက်ရှိစတော့ *</label>
                                <input type="number" step="0.01" wire:model="current_stock" class="w-full px-4 py-2 border rounded-lg" required>
                                @error('current_stock') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1 myanmar-text">အနည်းဆုံးစတော့ *</label>
                                <input type="number" step="0.01" wire:model="minimum_stock" class="w-full px-4 py-2 border rounded-lg" required>
                                @error('minimum_stock') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-span-2">
                                <label class="block text-sm font-medium mb-1 myanmar-text">ယူနစ်စျေး *</label>
                                <input type="number" step="0.01" wire:model="unit_cost" class="w-full px-4 py-2 border rounded-lg" required>
                                @error('unit_cost') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-span-2">
                                <label class="flex items-center">
                                    <input type="checkbox" wire:model="is_active" class="rounded">
                                    <span class="ml-2 text-sm myanmar-text">အသုံးပြုနိုင်သည်</span>
                                </label>
                            </div>
                        </div>
                        <div class="mt-6 flex justify-end gap-3">
                            <button type="button" wire:click="closeModal" class="px-4 py-2 border rounded-lg myanmar-text">ပိတ်မည်</button>
                            <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg myanmar-text">သိမ်းဆည်းမည်</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif
</div>
