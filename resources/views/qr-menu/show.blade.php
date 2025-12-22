<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu - {{ config('app.business_name') }}</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <!-- Header -->
        <div class="bg-primary-600 text-white py-6 sticky top-0 z-10 shadow-lg">
            <div class="max-w-4xl mx-auto px-4">
                <div class="text-center">
                    <h1 class="text-3xl font-bold myanmar-text">{{ \App\Models\Setting::get('business_name_mm', 'စျေးခြင်းစနစ်') }}</h1>
                    <h2 class="text-xl">{{ \App\Models\Setting::get('business_name', config('app.name')) }}</h2>
                    <p class="mt-2 text-primary-100 myanmar-text">မီနူး / Menu</p>
                </div>
            </div>
        </div>

        <!-- Menu Content -->
        <div class="max-w-4xl mx-auto px-4 py-8">
            @foreach($categories as $category)
                <div class="mb-8">
                    <!-- Category Header -->
                    <div class="bg-white rounded-lg shadow-sm p-4 mb-4 sticky top-24 z-5">
                        <h3 class="text-2xl font-bold text-gray-900">{{ $category->name }}</h3>
                        <p class="text-lg text-gray-600 myanmar-text">{{ $category->name_mm }}</p>
                    </div>

                    <!-- Items -->
                    <div class="space-y-3">
                        @foreach($category->activeItems as $item)
                            <div class="bg-white rounded-lg shadow-sm p-4 hover:shadow-md transition-shadow">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <h4 class="text-lg font-semibold text-gray-900">{{ $item->name }}</h4>
                                        <p class="text-gray-600 myanmar-text">{{ $item->name_mm }}</p>
                                        @if($item->description)
                                            <p class="text-sm text-gray-500 mt-1">{{ $item->description }}</p>
                                        @endif
                                    </div>
                                    <div class="ml-4 text-right">
                                        <div class="text-xl font-bold text-primary-600">
                                            {{ number_format($item->price, 0) }} <span class="text-sm">Ks</span>
                                        </div>
                                        @if(!$item->is_available)
                                            <span class="inline-block mt-1 px-2 py-1 text-xs font-semibold text-red-700 bg-red-100 rounded">
                                                <span class="myanmar-text">မရရှိနိုင်ပါ</span>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach

            @if($categories->isEmpty())
                <div class="text-center py-12">
                    <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                    <p class="text-gray-500 myanmar-text">မီနူး ကောင်တာသေးပါ</p>
                    <p class="text-gray-500">No menu available</p>
                </div>
            @endif
        </div>

        <!-- Footer -->
        <div class="bg-white border-t border-gray-200 py-6 mt-12">
            <div class="max-w-4xl mx-auto px-4 text-center">
                <p class="text-gray-600 myanmar-text">{{ \App\Models\Setting::get('business_address_mm') }}</p>
                <p class="text-gray-600">{{ \App\Models\Setting::get('business_address') }}</p>
                <p class="text-gray-600 mt-2">{{ \App\Models\Setting::get('business_phone') }}</p>
                <p class="text-sm text-gray-500 mt-4 myanmar-text">
                    © {{ date('Y') }} {{ \App\Models\Setting::get('business_name', config('app.name')) }}. All rights reserved.
                </p>
            </div>
        </div>
    </div>
</body>
</html>
