@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <!-- Header -->
            <div class="mb-6 flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 myanmar-text">{{ $title }}</h2>
                    <p class="text-sm text-gray-600 mt-1">Order #{{ $order->order_number }}</p>
                </div>
                <div class="flex space-x-2">
                    <button onclick="window.print()" class="btn btn-primary">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                        </svg>
                        <span class="myanmar-text">ပရင့်ထုတ်မည်</span>
                    </button>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline">
                        <span class="myanmar-text">နောက်သို့</span>
                    </a>
                </div>
            </div>

            <!-- Print Preview -->
            <div class="bg-gray-50 border-2 border-gray-300 rounded-lg p-6">
                <div class="bg-white p-4 rounded border border-gray-200">
                    <pre class="font-mono text-sm whitespace-pre-wrap myanmar-text" style="font-family: 'Courier New', monospace; line-height: 1.5;">{{ $content }}</pre>
                </div>
            </div>

            <!-- Info -->
            <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                <p class="text-sm text-blue-800 myanmar-text">
                    <strong>မှတ်ချက်:</strong> ဤသည် printer သို့ ပို့မည့် text format ကို preview လုပ်ထားခြင်း ဖြစ်ပါသည်။ 
                    Physical printer ကောင်တာပဲ မြန်မာစာ format မှန်မမှန် စစ်ဆေးနိုင်ပါသည်။
                </p>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    .btn, a.btn, .bg-blue-50 {
        display: none !important;
    }
    
    pre {
        font-size: 10pt !important;
        line-height: 1.3 !important;
    }
}
</style>
@endsection

