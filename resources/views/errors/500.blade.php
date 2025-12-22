<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 - Server Error</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gradient-to-br from-red-50 to-red-100 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full text-center">
        <div class="bg-white rounded-2xl shadow-xl p-8">
            <!-- Error Icon -->
            <div class="flex justify-center mb-6">
                <div class="bg-red-100 rounded-full p-6">
                    <svg class="w-16 h-16 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
            </div>

            <!-- Error Code -->
            <h1 class="text-6xl font-bold text-gray-900 mb-4">500</h1>
            
            <!-- Error Message -->
            <h2 class="text-2xl font-semibold text-gray-800 mb-2 myanmar-text">စနစ်အမှား</h2>
            <p class="text-gray-600 mb-8">Internal Server Error</p>
            
            <p class="text-sm text-gray-500 mb-8 myanmar-text">
                တစ်ခုခု မှားယွင်းနေပါသည်။ ကျေးဇူးပြု၍ ခဏစောင့်ပြီး ထပ်မံကြိုးစားကြည့်ပါ။
            </p>

            <!-- Actions -->
            <div class="space-y-3">
                <button onclick="window.location.reload()" class="block w-full btn btn-primary">
                    <svg class="w-5 h-5 mr-2 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    <span class="myanmar-text">ပြန်လည်စမ်းကြည့်ရန်</span>
                </button>
                
                <a href="{{ route('dashboard') }}" class="block w-full btn btn-outline">
                    <svg class="w-5 h-5 mr-2 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <span class="myanmar-text">ပင်မစာမျက်နှာသို့</span>
                </a>
            </div>

            <!-- Support Info -->
            <div class="mt-8 p-4 bg-gray-50 rounded-lg">
                <p class="text-xs text-gray-600 myanmar-text">
                    ပြဿနာ ဆက်လက်ဖြစ်ပွားနေပါက Admin ကို ဆက်သွယ်ပါ။
                </p>
            </div>
        </div>

        <!-- Footer -->
        <p class="mt-6 text-sm text-gray-600">
            © {{ date('Y') }} {{ config('app.name') }}
        </p>
    </div>
</body>
</html>
