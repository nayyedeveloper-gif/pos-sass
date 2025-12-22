<div class="payment-qr-code">
    @if(count($enabledMethods) > 0)
        <!-- Payment Method Tabs -->
        <div class="flex flex-wrap gap-2 mb-4">
            @foreach($enabledMethods as $key => $method)
            <button 
                wire:click="selectMethod('{{ $key }}')"
                class="px-4 py-2 rounded-xl text-sm font-medium transition-all {{ $selectedMethod === $key ? 'text-white shadow-lg' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}"
                style="{{ $selectedMethod === $key ? 'background-color: ' . $method['color'] : '' }}"
            >
                {{ $method['name'] }}
            </button>
            @endforeach
        </div>

        <!-- QR Code Display -->
        @if($qrCodeSvg && $paymentInfo)
        <div class="bg-white rounded-2xl border border-gray-200 p-6 text-center">
            <!-- Payment Method Header -->
            <div class="mb-4">
                <h3 class="text-lg font-bold" style="color: {{ $paymentInfo['color'] }}">
                    {{ $paymentInfo['method'] }}
                </h3>
                <p class="text-sm text-gray-500 myanmar-text">{{ $paymentInfo['method_mm'] }}</p>
            </div>

            <!-- QR Code -->
            <div class="flex justify-center mb-4">
                <div class="p-4 bg-white rounded-xl border-2" style="border-color: {{ $paymentInfo['color'] }}">
                    {!! $qrCodeSvg !!}
                </div>
            </div>

            <!-- Payment Details -->
            <div class="space-y-2 text-sm">
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-gray-500 myanmar-text">ဖုန်းနံပါတ်</span>
                    <span class="font-mono font-medium">{{ $paymentInfo['phone'] }}</span>
                </div>
                @if($paymentInfo['account_name'])
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-gray-500 myanmar-text">အကောင့်အမည်</span>
                    <span class="font-medium">{{ $paymentInfo['account_name'] }}</span>
                </div>
                @endif
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-gray-500 myanmar-text">အော်ဒါနံပါတ်</span>
                    <span class="font-mono font-medium">#{{ $paymentInfo['order_number'] }}</span>
                </div>
                <div class="flex justify-between items-center py-3 bg-gray-50 rounded-xl px-4 mt-3">
                    <span class="text-gray-700 font-medium myanmar-text">ပေးချေရမည့်ပမာဏ</span>
                    <span class="text-xl font-bold" style="color: {{ $paymentInfo['color'] }}">
                        {{ number_format($paymentInfo['amount'], 0) }} Ks
                    </span>
                </div>
            </div>

            <!-- Instructions -->
            <div class="mt-4 p-3 bg-gray-50 rounded-xl">
                <p class="text-xs text-gray-500 myanmar-text">
                    {{ $paymentInfo['method'] }} app ဖြင့် QR Code ကို scan ဖတ်ပြီး ငွေလွှဲပါ။
                    ငွေလွှဲပြီးပါက ဝန်ထမ်းကို အကြောင်းကြားပါ။
                </p>
            </div>
        </div>
        @endif
    @else
        <!-- No Payment Methods Configured -->
        <div class="bg-gray-50 rounded-2xl p-6 text-center">
            <div class="w-16 h-16 mx-auto mb-4 bg-gray-200 rounded-full flex items-center justify-center">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                </svg>
            </div>
            <h3 class="text-gray-700 font-medium mb-2 myanmar-text">Mobile Payment မရှိသေးပါ</h3>
            <p class="text-sm text-gray-500 myanmar-text">
                Admin Settings မှ Payment Settings တွင် mobile payment methods များ ထည့်သွင်းပါ။
            </p>
        </div>
    @endif
</div>
