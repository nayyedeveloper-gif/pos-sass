<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <span class="myanmar-text">ပင်မစာမျက်နှာ</span> / Dashboard
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="text-center py-12">
                        <svg class="w-20 h-20 mx-auto text-primary-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <h1 class="text-3xl font-bold text-gray-900 mb-2 myanmar-text">
                            {{ config('app.business_name_mm') }}
                        </h1>
                        <h2 class="text-2xl font-semibold text-gray-700 mb-4">
                            {{ config('app.business_name') }}
                        </h2>
                        <p class="text-gray-600 myanmar-text">
                            Point of Sale System
                        </p>
                        
                        <div class="mt-8 space-y-4">
                            @role('admin')
                                <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">
                                    <span class="myanmar-text">စီမံခန့်ခွဲမှု</span> / Admin Dashboard
                                </a>
                            @endrole
                            
                            @role('cashier')
                                <a href="{{ route('cashier.pos') }}" class="btn btn-primary">
                                    <span class="myanmar-text">ငွေကောက်ခံရန်</span> / <span class="myanmar-text">ကောင်တာ အရောင်း</span>
                                </a>
                            @endrole
                            
                            @role('waiter')
                                <a href="{{ route('waiter.tables.index') }}" class="btn btn-primary">
                                    <span class="myanmar-text">စားပွဲများ</span> / View Tables
                                </a>
                            @endrole
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
