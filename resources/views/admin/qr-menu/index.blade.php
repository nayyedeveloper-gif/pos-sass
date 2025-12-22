<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <span class="myanmar-text">QR မီနူး</span> / QR Menu
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 text-center py-12">
                    <p class="myanmar-text">QR မီနူးစီမံခန့်ခွဲမှု</p>
                    <p class="text-gray-600 mt-4">QR Menu URL: <a href="{{ url('/qr-menu/menu123') }}" target="_blank" class="text-primary-600 hover:underline">{{ url('/qr-menu/menu123') }}</a></p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
