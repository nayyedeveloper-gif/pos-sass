<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center space-x-3">
                    <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    <h2 class="text-2xl font-bold text-gray-900">Signage Analytics</h2>
                </div>
                <p class="mt-1 text-sm text-gray-600 myanmar-text">Digital Signage စွမ်းဆောင်ရည် ခွဲခြမ်းစိတ်ဖြာမှု</p>
            </div>
            
            <!-- Period Selector -->
            <div class="flex space-x-2">
                <button wire:click="setPeriod('today')" 
                        class="px-4 py-2 rounded-lg font-medium transition-colors {{ $period === 'today' ? 'bg-primary-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                    Today
                </button>
                <button wire:click="setPeriod('week')" 
                        class="px-4 py-2 rounded-lg font-medium transition-colors {{ $period === 'week' ? 'bg-primary-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                    This Week
                </button>
                <button wire:click="setPeriod('month')" 
                        class="px-4 py-2 rounded-lg font-medium transition-colors {{ $period === 'month' ? 'bg-primary-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                    This Month
                </button>
            </div>
        </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <!-- Total Views -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-2">
                <svg class="w-8 h-8 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
                <span class="text-sm font-medium opacity-80">Views</span>
            </div>
            <div class="text-3xl font-bold">{{ number_format($totalViews) }}</div>
            <p class="text-sm opacity-80 mt-1 myanmar-text">စုစုပေါင်း ကြည့်ရှုမှု</p>
        </div>

        <!-- Category Rotations -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-2">
                <svg class="w-8 h-8 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                <span class="text-sm font-medium opacity-80">Rotations</span>
            </div>
            <div class="text-3xl font-bold">{{ number_format($totalRotations) }}</div>
            <p class="text-sm opacity-80 mt-1 myanmar-text">Category လှည့်ပတ်မှု</p>
        </div>

        <!-- Media Displays -->
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-2">
                <svg class="w-8 h-8 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                </svg>
                <span class="text-sm font-medium opacity-80">Media</span>
            </div>
            <div class="text-3xl font-bold">{{ number_format($totalMediaDisplays) }}</div>
            <p class="text-sm opacity-80 mt-1 myanmar-text">Media ပြသမှု</p>
        </div>

        <!-- Uptime -->
        <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-2">
                <svg class="w-8 h-8 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="text-sm font-medium opacity-80">Uptime</span>
            </div>
            <div class="text-3xl font-bold">{{ number_format($totalUptime) }}</div>
            <p class="text-sm opacity-80 mt-1 myanmar-text">မိနစ် ({{ number_format($totalUptime / 60, 1) }} hrs)</p>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Popular Categories -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center space-x-2 mb-4">
                <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>
                </svg>
                <h3 class="text-lg font-semibold text-gray-900 myanmar-text">လူကြိုက်အများဆုံး Categories</h3>
            </div>
            
            @if(count($popularCategories) > 0)
                <div class="space-y-3">
                    @foreach($popularCategories as $index => $category)
                    <div class="flex items-center">
                        <div class="flex-shrink-0 w-8 h-8 bg-primary-100 rounded-full flex items-center justify-center mr-3">
                            <span class="text-primary-600 font-bold text-sm">{{ $index + 1 }}</span>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-sm font-medium text-gray-900 myanmar-text">{{ $category['name'] }}</span>
                                <span class="text-sm text-gray-600">{{ number_format($category['count']) }}</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-primary-600 h-2 rounded-full" style="width: {{ ($category['count'] / max(array_column($popularCategories, 'count'))) * 100 }}%"></div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 text-gray-500 myanmar-text">
                    <svg class="w-12 h-12 mx-auto mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <p>ဒေတာ ကောင်တာသေးပါ</p>
                </div>
            @endif
        </div>

        <!-- Popular Media -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center space-x-2 mb-4">
                <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                </svg>
                <h3 class="text-lg font-semibold text-gray-900 myanmar-text">လူကြိုက်အများဆုံး Media</h3>
            </div>
            
            @if(count($popularMedia) > 0)
                <div class="space-y-3">
                    @foreach($popularMedia as $index => $media)
                    <div class="flex items-center">
                        <div class="flex-shrink-0 w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center mr-3">
                            <span class="text-purple-600 font-bold text-sm">{{ $index + 1 }}</span>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center justify-between mb-1">
                                <div class="flex items-center space-x-2">
                                    @if($media['type'] === 'video')
                                        <svg class="w-4 h-4 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z"></path>
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                                        </svg>
                                    @endif
                                    <span class="text-sm font-medium text-gray-900 myanmar-text">{{ $media['title'] }}</span>
                                </div>
                                <span class="text-sm text-gray-600">{{ number_format($media['count']) }}</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-purple-600 h-2 rounded-full" style="width: {{ ($media['count'] / max(array_column($popularMedia, 'count'))) * 100 }}%"></div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 text-gray-500 myanmar-text">
                    <svg class="w-12 h-12 mx-auto mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                    </svg>
                    <p>ဒေတာ ကောင်တာသေးပါ</p>
                </div>
            @endif
        </div>
    </div>

        <!-- Info Box -->
        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex items-start space-x-3">
                <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div class="flex-1">
                    <h4 class="text-sm font-semibold text-blue-900 mb-1 myanmar-text">အချက်အလက်</h4>
                    <p class="text-sm text-blue-800 myanmar-text">
                        Analytics ဒေတာများသည် Digital Signage Display ကို အသုံးပြုသည့်အခါ အလိုအလျောက် စုဆောင်းမည်ဖြစ်ပါသည်။ 
                        Display ကို ဖွင့်ထားပြီး အချိန်ကြာလေလေ၊ ဒေတာများ ပိုမိုတိကျလေလေ ဖြစ်ပါသည်။
                    </p>
                </div>
            </div>
        </div>
</div>
