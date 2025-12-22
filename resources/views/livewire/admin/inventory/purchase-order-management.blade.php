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
            <h2 class="text-2xl font-bold text-gray-900 myanmar-text">ဝယ်ယူမှုမှတ်တမ်းများ စီမံခန့်ခွဲမှု</h2>
            <button wire:click="openModal" class="px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg">
                <span class="myanmar-text">ဝယ်ယူမှုအသစ် ထည့်ရန်</span>
            </button>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="PO Number, Supplier..." class="w-full px-4 py-2 border rounded-lg">
        </div>

        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">PO Number</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase myanmar-text">ပစ္စည်းပေးသွင်းသူ</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase myanmar-text">ရက်စွဲ</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase myanmar-text">တန်ဖိုး</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase myanmar-text">အခြေအနေ</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase myanmar-text">လုပ်ဆောင်ချက်</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($purchaseOrders as $po)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $po->po_number }}</td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">{{ $po->supplier->name }}</div>
                            @if($po->supplier->name_mm)
                            <div class="text-sm text-gray-500 myanmar-text">{{ $po->supplier->name_mm }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $po->order_date->format('Y-m-d') }}</td>
                        <td class="px-6 py-4 text-right text-sm font-medium text-gray-900">{{ number_format($po->total_amount) }} Ks</td>
                        <td class="px-6 py-4">
                            @if($po->status === 'pending')
                            <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800 myanmar-text">စောင့်ဆိုင်းဆဲ</span>
                            @elseif($po->status === 'received')
                            <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800 myanmar-text">ရောက်ရှိပြီး</span>
                            @else
                            <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800 myanmar-text">ပယ်ဖျက်ပြီး</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <button wire:click="edit({{ $po->id }})" class="text-primary-600 hover:text-primary-900 mr-3">Edit</button>
                            <button wire:click="delete({{ $po->id }})" wire:confirm="ဖျက်မှာ သေချာပါသလား?" class="text-red-600 hover:text-red-900">Delete</button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500 myanmar-text">ဝယ်ယူမှုမှတ်တမ်း ကောင်တာသေးပါ</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="px-6 py-4">{{ $purchaseOrders->links() }}</div>
        </div>

        @if($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75" wire:click="closeModal"></div>
                <div class="relative bg-white rounded-lg max-w-2xl w-full p-6">
                    <h3 class="text-lg font-medium mb-4 myanmar-text">{{ $editMode ? 'ပြင်ဆင်ရန်' : 'အသစ်ထည့်ရန်' }}</h3>
                    <form wire:submit.prevent="save">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="col-span-2">
                                <label class="block text-sm font-medium mb-1 myanmar-text">ပစ္စည်းပေးသွင်းသူ *</label>
                                <select wire:model="supplier_id" class="w-full px-4 py-2 border rounded-lg" required>
                                    <option value="">ရွေးချယ်ပါ...</option>
                                    @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}">{{ $supplier->name }} @if($supplier->name_mm)({{ $supplier->name_mm }})@endif</option>
                                    @endforeach
                                </select>
                                @error('supplier_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1 myanmar-text">မှာယူသည့်ရက် *</label>
                                <input type="date" wire:model="order_date" class="w-full px-4 py-2 border rounded-lg" required>
                                @error('order_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1 myanmar-text">ရောက်မည့်ရက်</label>
                                <input type="date" wire:model="expected_delivery_date" class="w-full px-4 py-2 border rounded-lg">
                                @error('expected_delivery_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1 myanmar-text">စုစုပေါင်းတန်ဖိုး *</label>
                                <input type="number" step="0.01" wire:model="total_amount" class="w-full px-4 py-2 border rounded-lg" required>
                                @error('total_amount') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1 myanmar-text">အခြေအနေ *</label>
                                <select wire:model="status" class="w-full px-4 py-2 border rounded-lg" required>
                                    <option value="pending">စောင့်ဆိုင်းဆဲ</option>
                                    <option value="received">ရောက်ရှိပြီး</option>
                                    <option value="cancelled">ပယ်ဖျက်ပြီး</option>
                                </select>
                                @error('status') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-span-2">
                                <label class="block text-sm font-medium mb-1 myanmar-text">မှတ်ချက်</label>
                                <textarea wire:model="notes" rows="3" class="w-full px-4 py-2 border rounded-lg"></textarea>
                                @error('notes') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
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
