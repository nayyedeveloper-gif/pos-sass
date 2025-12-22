<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Digital Signage - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <style>
        body {
            overflow: hidden;
        }
        .slide-enter {
            animation: slideIn 0.5s ease-out;
        }
        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        .promotional-scroll {
            animation: scroll 20s linear infinite;
        }
        @keyframes scroll {
            0% {
                transform: translateX(100%);
            }
            100% {
                transform: translateX(-100%);
            }
        }
    </style>
</head>
<body class="text-white" x-data="{ theme: @js($theme ?? 'dark') }" 
      :class="theme === 'dark' ? 'bg-gradient-to-br from-gray-900 to-gray-800' : 'bg-gradient-to-br from-gray-100 to-gray-200'">
    {{ $slot }}
    
    @livewireScripts
    
    <script>
        // Auto-refresh based on settings (default 5 minutes)
        const refreshMinutes = {{ $autoRefresh ?? 5 }};
        setInterval(() => {
            window.location.reload();
        }, refreshMinutes * 60000);
    </script>
</body>
</html>
