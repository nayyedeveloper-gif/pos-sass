<?php

use Illuminate\Support\Facades\Facade;

return [
    'name' => env('APP_NAME', 'POS Pro'),
    'env' => env('APP_ENV', 'production'),
    'debug' => (bool) env('APP_DEBUG', false),
    'url' => env('APP_URL', 'http://localhost'),
    'timezone' => env('APP_TIMEZONE', 'Asia/Yangon'),
    'locale' => env('APP_LOCALE', 'my'),
    'fallback_locale' => env('APP_FALLBACK_LOCALE', 'en'),
    'faker_locale' => env('APP_FAKER_LOCALE', 'en_US'),
    'cipher' => 'AES-256-CBC',
    'key' => env('APP_KEY'),
    'previous_keys' => [
        ...array_filter(
            explode(',', env('APP_PREVIOUS_KEYS', ''))
        ),
    ],
    'maintenance' => [
        'driver' => env('APP_MAINTENANCE_DRIVER', 'file'),
        'store' => env('APP_MAINTENANCE_STORE', 'database'),
    ],
    'business_name' => env('BUSINESS_NAME', 'My Business'),
    'business_name_mm' => env('BUSINESS_NAME_MM', 'ကျွန်ုပ်၏လုပ်ငန်း'),
    'business_address' => env('BUSINESS_ADDRESS', 'Yangon, Myanmar'),
    'business_address_mm' => env('BUSINESS_ADDRESS_MM', 'ရန်ကုန်မြို့၊ မြန်မာနိုင်ငံ'),
    'business_phone' => env('BUSINESS_PHONE', '+95 9 123 456 789'),
    'qr_menu_token' => env('QR_MENU_TOKEN', 'menu123'),
];
