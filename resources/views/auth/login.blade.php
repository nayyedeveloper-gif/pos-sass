<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .gradient-text {
            background: linear-gradient(135deg, #7c3aed 0%, #06b6d4 50%, #10b981 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .gradient-border {
            background: linear-gradient(135deg, #7c3aed, #06b6d4, #10b981);
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
        }
    </style>
</head>
<body class="font-sans antialiased text-gray-900">
    <!-- Full Page White Background with Subtle Pattern -->
    <div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-100 flex items-center justify-center p-4 relative overflow-hidden">
        <!-- Decorative Background Elements -->
        <div class="absolute top-0 left-0 w-96 h-96 bg-gradient-to-br from-violet-200/40 to-transparent rounded-full -translate-x-1/2 -translate-y-1/2 blur-3xl"></div>
        <div class="absolute top-1/4 right-0 w-80 h-80 bg-gradient-to-bl from-cyan-200/40 to-transparent rounded-full translate-x-1/3 blur-3xl"></div>
        <div class="absolute bottom-0 left-1/4 w-72 h-72 bg-gradient-to-tr from-emerald-200/40 to-transparent rounded-full translate-y-1/2 blur-3xl"></div>
        <div class="absolute bottom-1/4 right-1/4 w-64 h-64 bg-gradient-to-tl from-purple-200/30 to-transparent rounded-full blur-3xl"></div>

        <!-- Login Card -->
        <div class="relative z-10 w-full max-w-md">
            <!-- Card with gradient border effect -->
            <div class="p-[2px] rounded-3xl gradient-border shadow-2xl shadow-gray-200/50">
                <div class="glass-card rounded-3xl p-8 sm:p-10">
                    <!-- Logo & Branding -->
                    <div class="text-center mb-8">
                        @php
                            $logo = App\Models\Setting::get('app_logo');
                        @endphp
                        <div class="inline-flex p-4 bg-white rounded-2xl shadow-lg shadow-gray-100 border border-gray-100 mb-6">
                            @if($logo)
                                <img src="{{ asset('storage/' . $logo) }}" alt="Logo" class="h-20 w-20 object-contain">
                            @else
                                <div class="h-20 w-20 bg-gradient-to-br from-violet-500 via-cyan-500 to-emerald-500 rounded-xl flex items-center justify-center">
                                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <h1 class="text-2xl font-bold gradient-text mb-1">{{ App\Models\Setting::get('app_name', config('app.name')) }}</h1>
                        <p class="text-sm text-gray-500 myanmar-text">{{ App\Models\Setting::get('business_name_mm', 'ဘက်စုံသုံး POS စနစ်') }}</p>
                    </div>

                    <!-- Welcome Text -->
                    <div class="text-center mb-6">
                        <h2 class="text-xl font-semibold text-gray-800 myanmar-text">ကြိုဆိုပါသည်</h2>
                        <p class="text-sm text-gray-500 mt-1 myanmar-text">အကောင့်ဝင်ရောက်ရန်</p>
                    </div>

                    @if ($errors->any())
                        <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-100 text-red-600 text-sm flex items-start">
                            <svg class="w-5 h-5 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                @foreach ($errors->all() as $error)
                                    <p>{{ $error }}</p>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}" class="space-y-5">
                        @csrf

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2 myanmar-text">အီးမေးလ်</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                                    </svg>
                                </div>
                                <input 
                                    id="email" 
                                    type="email" 
                                    name="email" 
                                    value="{{ old('email') }}" 
                                    required 
                                    autofocus 
                                    autocomplete="username"
                                    class="w-full pl-12 pr-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500 transition-all duration-200"
                                    placeholder="example@email.com"
                                >
                            </div>
                        </div>

                        <!-- Password -->
                        <div x-data="{ show: false }">
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2 myanmar-text">စကားဝှက်</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                </div>
                                <input 
                                    id="password" 
                                    :type="show ? 'text' : 'password'" 
                                    name="password" 
                                    required 
                                    autocomplete="current-password"
                                    class="w-full pl-12 pr-12 py-3.5 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500 transition-all duration-200"
                                    placeholder="••••••••"
                                >
                                <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-4 text-gray-400 hover:text-gray-600 focus:outline-none transition-colors">
                                    <svg x-show="!show" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    <svg x-show="show" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Remember Me -->
                        <div class="flex items-center justify-between">
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" name="remember" class="w-4 h-4 rounded border-gray-300 text-violet-600 focus:ring-violet-500 focus:ring-offset-0">
                                <span class="ml-2 text-sm text-gray-600 myanmar-text">မှတ်ထားမည်</span>
                            </label>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="w-full py-3.5 px-4 bg-gradient-to-r from-violet-600 via-cyan-600 to-emerald-600 hover:from-violet-700 hover:via-cyan-700 hover:to-emerald-700 text-white font-semibold rounded-xl shadow-lg shadow-violet-500/25 hover:shadow-xl hover:shadow-violet-500/30 transform hover:-translate-y-0.5 transition-all duration-200">
                            <span class="myanmar-text">အကောင့်ဝင်မည်</span>
                        </button>
                    </form>

                    <!-- Footer -->
                    <div class="mt-8 pt-6 border-t border-gray-100 text-center">
                        <p class="text-xs text-gray-400">
                            © {{ date('Y') }} {{ App\Models\Setting::get('app_name', config('app.name')) }}
                        </p>
                        <p class="text-xs text-gray-300 mt-1">Enterprise Point of Sale System</p>
                    </div>
                </div>
            </div>

            <!-- Powered By -->
            <p class="text-center text-xs text-gray-400 mt-6">
                Powered by <span class="font-medium gradient-text">NexaPOS</span>
            </p>
        </div>
    </div>
</body>
</html>
