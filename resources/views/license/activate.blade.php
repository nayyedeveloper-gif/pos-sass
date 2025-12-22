<!DOCTYPE html>
<html lang="my">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>License Activation - {{ config('app.name', 'Cafe Pro') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Padauk:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Padauk', 'Inter', sans-serif; }
        .font-mono { font-family: 'SF Mono', 'Monaco', 'Inconsolata', 'Fira Mono', monospace; }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-50 to-slate-100 min-h-screen flex items-center justify-center p-4">

    <div class="max-w-md w-full">
        <div class="bg-white rounded-2xl shadow-xl border border-slate-200 overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 px-8 py-6 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-white/20 backdrop-blur rounded-xl mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-white">License Activation</h1>
                <p class="text-emerald-100 mt-1 text-sm">{{ config('app.name', 'Cafe Pro') }} အသုံးပြုရန် လိုင်စင်ထည့်သွင်းပါ</p>
            </div>

            <div class="p-8">
                @if(session('error'))
                    <div class="bg-red-50 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded-lg mb-6 flex items-start gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500 mt-0.5 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-sm">{{ session('error') }}</span>
                    </div>
                @endif

                <!-- Machine ID Section -->
                <div class="mb-6">
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Machine ID</label>
                    <div class="relative">
                        <div class="bg-slate-50 border border-slate-200 rounded-xl p-4">
                            <code class="font-mono text-sm text-slate-700 break-all select-all block text-center tracking-wide" id="machineId">{{ $machineId }}</code>
                        </div>
                        <button onclick="copyMachineId()" id="copyBtn" class="absolute right-3 top-1/2 -translate-y-1/2 p-2 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-all duration-200" title="Copy Machine ID">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" id="copyIcon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden" id="checkIcon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                        </button>
                    </div>
                    <p class="text-xs text-slate-500 mt-2 text-center">ဤ Machine ID ကို Admin ထံပေးပို့၍ License Key ရယူပါ</p>
                </div>

                <!-- Divider -->
                <div class="relative my-6">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-slate-200"></div>
                    </div>
                    <div class="relative flex justify-center">
                        <span class="bg-white px-3 text-xs text-slate-400 uppercase tracking-wider">License Key ထည့်သွင်းရန်</span>
                    </div>
                </div>

                <!-- License Form -->
                <form action="{{ route('license.activate.post') }}" method="POST">
                    @csrf
                    <div class="mb-6">
                        <label for="license_key" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">License Key</label>
                        <input type="text" name="license_key" id="license_key" 
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-700 font-mono text-sm tracking-wider uppercase placeholder:text-slate-400 placeholder:normal-case focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-200" 
                            placeholder="POS-XXXXXXXX-LIFETIME-XXXXXXXX" required>
                    </div>

                    <button type="submit" class="w-full bg-gradient-to-r from-emerald-600 to-emerald-700 hover:from-emerald-700 hover:to-emerald-800 text-white font-semibold py-3.5 px-6 rounded-xl shadow-lg shadow-emerald-500/25 hover:shadow-emerald-500/40 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition-all duration-200 flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Activate License</span>
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="mt-6 text-center text-xs text-slate-400">
            &copy; {{ date('Y') }} {{ config('app.name', 'Cafe Pro') }}. All rights reserved.
        </div>
    </div>

    <script>
        function copyMachineId() {
            const machineId = document.getElementById("machineId").innerText;
            const copyIcon = document.getElementById("copyIcon");
            const checkIcon = document.getElementById("checkIcon");
            
            navigator.clipboard.writeText(machineId).then(() => {
                copyIcon.classList.add("hidden");
                checkIcon.classList.remove("hidden");
                
                setTimeout(() => {
                    copyIcon.classList.remove("hidden");
                    checkIcon.classList.add("hidden");
                }, 2000);
            });
        }
    </script>
</body>
</html>
