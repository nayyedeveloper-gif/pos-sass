<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gradient-to-br from-primary-50 to-primary-100 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full text-center">
        <div class="bg-white rounded-2xl shadow-xl p-8">
            <!-- Error Icon -->
            <div class="flex justify-center mb-6">
                <div class="bg-red-100 rounded-full p-6">
                    <svg class="w-16 h-16 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>

            <!-- Error Code -->
            <h1 class="text-6xl font-bold text-gray-900 mb-4">404</h1>
            
            <!-- Error Message -->
            <h2 class="text-2xl font-semibold text-gray-800 mb-2 myanmar-text">စာမျက်နှာ မတွေ့ပါ</h2>
            <p class="text-gray-600 mb-8">Page Not Found</p>
            
            <p class="text-sm text-gray-500 mb-8 myanmar-text">
                သင်ရှာနေသော စာမျက်နှာကို ရှာမတွေ့ပါ။ URL ကို စစ်ဆေးပြီး ထပ်မံကြိုးစားကြည့်ပါ။
            </p>

            <!-- Actions -->
            <div class="space-y-3">
                <a href="{{ route('dashboard') }}" class="block w-full btn btn-primary">
                    <svg class="w-5 h-5 mr-2 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <span class="myanmar-text">ပင်မစာမျက်နှာသို့</span>
                </a>
                
                <button onclick="window.history.back()" class="block w-full btn btn-outline">
                    <svg class="w-5 h-5 mr-2 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    <span class="myanmar-text">နောက်သို့</span>
                </button>
            </div>
        </div>

        <!-- Footer -->
        <p class="mt-6 text-sm text-gray-600">
            © {{ date('Y') }} {{ config('app.name') }}
        </p>
    </div>
</body>
</html>
