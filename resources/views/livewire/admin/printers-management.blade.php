<div>
    <!-- Success Message -->
    @if (session()->has('message'))
    <div class="mb-4 bg-green-50 border-l-4 border-green-400 p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm text-green-700 myanmar-text">{{ session('message') }}</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Error Message -->
    @if (session()->has('error'))
    <div class="mb-4 bg-red-50 border-l-4 border-red-400 p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm text-red-700 myanmar-text">{{ session('error') }}</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Header -->
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900 myanmar-text">ပရင်တာ ပြင်ဆင်သတ်မှတ်ခြင်း</h2>
        <p class="mt-1 text-sm text-gray-600 myanmar-text">ပရင်တာများ၏ IP လိပ်စာနှင့် ဆက်တင်များကို သတ်မှတ်ပါ။</p>
    </div>

    <!-- Info Box -->
    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 md:p-6 mb-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm text-blue-700 myanmar-text">
                    <strong>သတိပြုရန်:</strong> ပရင်တာများသည် ESC/POS protocol ကို ပံ့ပိုးပေးရမည်။ 
                    ပရင်တာများသည် network ပေါ်တွင် ရှိရမည်ဖြစ်ပြီး IP လိပ်စာ သတ်မှတ်ထားရမည်။
                </p>
            </div>
        </div>
    </div>

    <!-- Printers Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
        @foreach($printers as $printer)
        <div class="bg-white rounded-lg shadow-sm overflow-hidden border-2 
            @if($printer->is_active) border-green-200
            @else border-gray-200
            @endif">
            <div class="p-6">
                <div class="flex justify-between items-start mb-4">
                    <div class="flex-1">
                        <div class="flex items-center">
                            <svg class="w-8 h-8 mr-3 
                                @if($printer->type === 'kitchen') text-orange-500
                                @elseif(strtolower($printer->type) === 'bar') text-pink-500
                                @elseif($printer->type === 'nan_pyar') text-purple-500
                                @else text-blue-500
                                @endif" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                            </svg>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 myanmar-text">{{ $printer->name }}</h3>
                                <p class="text-xs text-gray-500">
                                    @if($printer->type === 'kitchen')
                                    <span class="myanmar-text">မီးဖိုချောင် ပရင်တာ</span>
                                    @elseif(strtolower($printer->type) === 'bar')
                                    <span class="myanmar-text">သောက်စရာ ပရင်တာ</span>
                                    @elseif($printer->type === 'nan_pyar')
                                    <span class="myanmar-text">နံပြား ပရင်တာ</span>
                                    @else
                                    <span class="myanmar-text">ငွေလက်ခံ ပရင်တာ</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                    <div>
                        @if($printer->is_active)
                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 myanmar-text">
                            အသုံးပြုနေသော
                        </span>
                        @else
                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800 myanmar-text">
                            ပိတ်ထားသော
                        </span>
                        @endif
                    </div>
                </div>

                <div class="space-y-3 mb-4">
                    <div class="flex items-center text-sm">
                        <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                        </svg>
                        <span class="text-gray-600 myanmar-text">IP လိပ်စာ:</span>
                        <span class="ml-2 font-mono font-medium">{{ $printer->ip_address }}</span>
                    </div>

                    <div class="flex items-center text-sm">
                        <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span class="text-gray-600 myanmar-text">Port:</span>
                        <span class="ml-2 font-mono font-medium">{{ $printer->port }}</span>
                    </div>

                    <div class="flex items-center text-sm">
                        <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path>
                        </svg>
                        <span class="text-gray-600 myanmar-text">စက္ကူ အကျယ်:</span>
                        <span class="ml-2 font-medium">{{ $printer->paper_width }}mm</span>
                    </div>
                </div>

                <div class="flex space-x-2 pt-4 border-t border-gray-200">
                    <button wire:click="testPrinter({{ $printer->id }})" 
                            wire:loading.attr="disabled"
                            wire:target="testPrinter({{ $printer->id }})"
                            class="flex-1 btn btn-outline text-sm">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="myanmar-text" wire:loading.remove wire:target="testPrinter({{ $printer->id }})">စမ်းသပ်ရန်</span>
                        <span class="myanmar-text" wire:loading wire:target="testPrinter({{ $printer->id }})">စမ်းနေသည်...</span>
                    </button>
                    <button wire:click="toggleActive({{ $printer->id }})" class="btn btn-outline text-sm" title="အခြေအနေ ပြောင်းရန်">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </button>
                    <button wire:click="edit({{ $printer->id }})" class="btn btn-primary text-sm" title="ပြင်ဆင်ရန်">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Setup Instructions -->
    <div class="mt-8 bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4 myanmar-text">ပရင်တာ တပ်ဆင်ခြင်း လမ်းညွှန်</h3>
        
        <div class="space-y-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <span class="flex items-center justify-center h-8 w-8 rounded-full bg-primary-500 text-white font-semibold">1</span>
                </div>
                <div class="ml-4">
                    <h4 class="text-sm font-medium text-gray-900 myanmar-text">ပရင်တာကို network နှင့် ချိတ်ဆက်ပါ</h4>
                    <p class="mt-1 text-sm text-gray-600 myanmar-text">
                        ပရင်တာသည် WiFi သို့မဟုတ် Ethernet ဖြင့် network နှင့် ချိတ်ဆက်ထားရမည်။
                    </p>
                </div>
            </div>

            <div class="flex">
                <div class="flex-shrink-0">
                    <span class="flex items-center justify-center h-8 w-8 rounded-full bg-primary-500 text-white font-semibold">2</span>
                </div>
                <div class="ml-4">
                    <h4 class="text-sm font-medium text-gray-900 myanmar-text">ပရင်တာ၏ IP လိပ်စာကို ရှာပါ</h4>
                    <p class="mt-1 text-sm text-gray-600 myanmar-text">
                        ပရင်တာ၏ configuration page မှ သို့မဟုတ် router မှ IP လိပ်စာကို ရှာပါ။
                    </p>
                </div>
            </div>

            <div class="flex">
                <div class="flex-shrink-0">
                    <span class="flex items-center justify-center h-8 w-8 rounded-full bg-primary-500 text-white font-semibold">3</span>
                </div>
                <div class="ml-4">
                    <h4 class="text-sm font-medium text-gray-900 myanmar-text">IP လိပ်စာကို ထည့်သွင်းပါ</h4>
                    <p class="mt-1 text-sm text-gray-600 myanmar-text">
                        ပရင်တာ ပြင်ဆင်ရန် ခလုတ်ကို နှိပ်ပြီး IP လိပ်စာနှင့် port (များသောအားဖြင့် 9100) ကို ထည့်သွင်းပါ။
                    </p>
                </div>
            </div>

            <div class="flex">
                <div class="flex-shrink-0">
                    <span class="flex items-center justify-center h-8 w-8 rounded-full bg-primary-500 text-white font-semibold">4</span>
                </div>
                <div class="ml-4">
                    <h4 class="text-sm font-medium text-gray-900 myanmar-text">စမ်းသပ်ပါ</h4>
                    <p class="mt-1 text-sm text-gray-600 myanmar-text">
                        "စမ်းသပ်ရန်" ခလုတ်ကို နှိပ်ပြီး ပရင်တာမှ test page ထွက်မထွက် စစ်ဆေးပါ။
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    @if($showModal)
    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900 myanmar-text">ပရင်တာ ပြင်ဆင်ရန်</h3>
            </div>

            <form wire:submit.prevent="save">
                <div class="px-6 py-4 space-y-4">
                    <!-- Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1 myanmar-text">အမည် *</label>
                        <input type="text" wire:model="name" class="input" placeholder="Kitchen Printer">
                        @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Type (Read-only) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1 myanmar-text">အမျိုးအစား</label>
                        <input type="text" value="
                            @if($type === 'kitchen') မီးဖိုချောင် / Kitchen
                            @elseif(strtolower($type) === 'bar') သောက်စရာ / Bar
                            @elseif($type === 'nan_pyar') နံပြား / Nan Pyar
                            @else ငွေလက်ခံ / Receipt
                            @endif
                        " class="input bg-gray-100" readonly>
                    </div>

                    <!-- IP Address -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1 myanmar-text">IP လိပ်စာ *</label>
                        <input type="text" wire:model="ip_address" class="input font-mono" placeholder="192.168.1.100">
                        <p class="mt-1 text-xs text-gray-500 myanmar-text">
                            ပရင်တာ၏ network IP လိပ်စာကို ထည့်သွင်းပါ
                        </p>
                        @error('ip_address') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Port -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Port *</label>
                        <input type="number" wire:model="port" class="input font-mono" placeholder="9100" min="1" max="65535">
                        <p class="mt-1 text-xs text-gray-500 myanmar-text">
                            များသောအားဖြင့် 9100 ဖြစ်ပါသည်
                        </p>
                        @error('port') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Paper Width -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1 myanmar-text">စက္ကူ အကျယ် *</label>
                        <select wire:model="paper_width" class="input">
                            <option value="58">58mm</option>
                            <option value="80">80mm</option>
                        </select>
                        @error('paper_width') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Active Checkbox -->
                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="is_active" class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-700 myanmar-text">အသုံးပြုနေသော</span>
                        </label>
                    </div>
                </div>

                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3">
                    <button type="button" wire:click="closeModal" class="btn btn-outline">
                        <span class="myanmar-text">မလုပ်တော့ပါ</span>
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <span class="myanmar-text">သိမ်းဆည်းမည်</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
