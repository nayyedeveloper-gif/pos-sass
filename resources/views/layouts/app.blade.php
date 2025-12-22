<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#10b981">
    
    <title>{{ config('app.name', 'POS Pro') }}</title>
    
    <!-- PWA Meta Tags -->
    <link rel="manifest" href="/manifest.json">
    <link rel="apple-touch-icon" href="/images/icon-192x192.png">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-Bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="POS Pro">
    
    <!-- Fonts -->
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    
    <!-- Print Agent Configuration -->
    <script>
        window.printAgentUrl = '{{ config("printing.agent_url") }}';
        window.printerConfig = @json(config('printing.printers'));
        window.printApiKey = '{{ env("PRINT_API_KEY", "pos-pro-2025") }}';
    </script>
</head>
<body class="font-sans antialiased bg-gray-100 text-gray-900">
    <div class="min-h-screen">
        <!-- Navigation -->
        @if(auth()->check())
            @include('layouts.navigation')
        @endif
        
        <!-- Page Heading -->
        @if (isset($header))
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif
        
        <!-- Page Content -->
        <main>
            @yield('content')
        </main>
    </div>
    
    @livewireScripts
    
    <!-- Toast Container -->
    <div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2"></div>
    
    <!-- PWA Install Prompt -->
    <div id="pwa-install-prompt" class="hidden fixed bottom-4 left-4 right-4 md:left-auto md:right-4 md:w-96 bg-white rounded-lg shadow-lg p-4 z-50 border border-gray-200">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                </svg>
            </div>
            <div class="ml-3 flex-1">
                <h3 class="text-sm font-medium text-gray-900 myanmar-text">App အဖြစ် Install လုပ်မလား?</h3>
                <p class="mt-1 text-xs text-gray-600">Offline အသုံးပြုနိုင်ပြီး မြန်ဆန်ပါတယ်</p>
                <div class="mt-3 flex space-x-2">
                    <button onclick="installPWA()" class="px-4 py-2 bg-green-600 text-white text-sm rounded-md hover:bg-green-700">
                        Install
                    </button>
                    <button onclick="dismissInstallPrompt()" class="px-4 py-2 bg-gray-200 text-gray-700 text-sm rounded-md hover:bg-gray-300">
                        Not Now
                    </button>
                </div>
            </div>
            <button onclick="dismissInstallPrompt()" class="ml-2 text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
            </button>
        </div>
    </div>
    
    <!-- PWA Service Worker Registration -->
    <!-- Myanmar PWA Install Prompt is handled below -->

    <script>
        // Register Service Worker
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                // Check if service worker is already registered
                navigator.serviceWorker.getRegistration('/sw.js').then(registration => {
                    if (registration) {
                        // Update existing registration
                        registration.update();
                    } else {
                        // Register new service worker
                        navigator.serviceWorker.register('/sw.js')
                            .then(registration => {
                                console.log('✅ Service Worker registered:', registration.scope);
                            })
                            .catch(error => {
                                console.log('❌ Service Worker registration failed:', error);
                            });
                    }
                }).catch(() => {
                    // If getRegistration fails, try to register
                    navigator.serviceWorker.register('/sw.js')
                        .then(registration => {
                            console.log('✅ Service Worker registered:', registration.scope);
                        })
                        .catch(error => {
                            console.log('❌ Service Worker registration failed:', error);
                        });
                });
            });
        }
        
        // PWA Install Prompt
        let deferredPrompt;
        const installPrompt = document.getElementById('pwa-install-prompt');
        
        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;
            
            // Show install prompt after 5 seconds
            setTimeout(() => {
                if (!localStorage.getItem('pwa-install-dismissed')) {
                    installPrompt.classList.remove('hidden');
                }
            }, 5000);
        });
        
        window.installPWA = function() {
            if (deferredPrompt) {
                deferredPrompt.prompt();
                deferredPrompt.userChoice.then((choiceResult) => {
                    if (choiceResult.outcome === 'accepted') {
                        console.log('✅ PWA installed');
                        // Optionally report install to server
                        try {
                            fetch('/api/pwa/installed', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                                },
                                body: JSON.stringify({ action: 'install' })
                            });
                        } catch(e) { console.error(e); }
                    }
                    deferredPrompt = null;
                    installPrompt.classList.add('hidden');
                });
            }
        };
        
        window.dismissInstallPrompt = function() {
            installPrompt.classList.add('hidden');
            localStorage.setItem('pwa-install-dismissed', 'true');
        };
        
        // Listen for app installed event
        window.addEventListener('appinstalled', () => {
            console.log('✅ PWA was installed');
            installPrompt.classList.add('hidden');
        });
        
        // Online/Offline status
        window.addEventListener('online', () => {
            console.log('✅ Back online');
        });
        
        window.addEventListener('offline', () => {
            console.log('❌ Gone offline');
        });
    </script>
</body>
</html>
