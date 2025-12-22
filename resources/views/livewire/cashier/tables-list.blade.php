<div class="min-h-screen bg-gray-50 pb-20" wire:poll.5s="loadTables">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 myanmar-text flex items-center gap-3">
                    <span>စားပွဲများ</span>
                    <span class="inline-flex items-center justify-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-gray-100 text-gray-600">
                        {{ $tables->count() }}
                    </span>
                </h1>
                <p class="mt-1 text-sm text-gray-500 myanmar-text">စားပွဲနှိပ်၍ ငွေရှင်းနိုင်ပါသည်။</p>
            </div>
            
            <!-- Search -->
            <div class="flex-1 max-w-md">
                <div class="relative">
                    <input 
                        type="text" 
                        wire:model.live="search" 
                        placeholder="စားပွဲနံပါတ် ရှာရန်..."
                        class="w-full pl-10 pr-4 py-2.5 border-gray-200 shadow-sm rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 myanmar-text text-sm"
                    >
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Legend -->
        <div class="flex flex-wrap gap-3 mb-8">
            <div class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-100">
                <span class="w-2 h-2 rounded-full bg-emerald-500 mr-2"></span>
                <span class="myanmar-text">အားလပ်သည်</span>
            </div>
            <div class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-rose-50 text-rose-700 border border-rose-100">
                <span class="w-2 h-2 rounded-full bg-rose-500 mr-2"></span>
                <span class="myanmar-text">ငွေရှင်းရန်ရှိသည်</span>
            </div>
        </div>

        <!-- Tables Grid -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 sm:gap-6">
            @forelse($tables as $table)
                @php
                    $isOccupied = $table->active_orders_count > 0;
                    $pendingOrder = $table->orders->first();
                    $totalAmount = $table->orders->sum('total');
                    
                    if ($isOccupied) {
                        $cardClass = 'bg-gradient-to-br from-rose-500 to-rose-600 text-white shadow-md ring-1 ring-rose-600 hover:shadow-lg transform hover:-translate-y-1 cursor-pointer';
                        $iconClass = 'text-rose-100';
                        $textClass = 'text-white';
                        $subTextClass = 'text-rose-100';
                        $badgeClass = 'bg-white/20 text-white backdrop-blur-sm';
                    } else {
                        $cardClass = 'bg-white text-gray-900 shadow-sm ring-1 ring-gray-200 hover:shadow-md hover:ring-emerald-500 hover:ring-2 transform hover:-translate-y-1';
                        $iconClass = 'text-emerald-500';
                        $textClass = 'text-gray-900';
                        $subTextClass = 'text-gray-500';
                        $badgeClass = 'bg-gray-100 text-gray-600';
                    }
                @endphp
                
                <button 
                    wire:click="selectTable({{ $table->id }})"
                    class="relative flex flex-col p-5 rounded-2xl transition-all duration-200 {{ $cardClass }} group overflow-hidden text-left h-full justify-between"
                >
                    <!-- Background Decoration -->
                    <div class="absolute -right-4 -top-4 w-24 h-24 rounded-full opacity-10 bg-white pointer-events-none"></div>
                    
                    <!-- Top Row -->
                    <div class="flex justify-between items-start w-full mb-4 relative z-10">
                        <div class="p-2 rounded-xl {{ $isOccupied ? 'bg-white/20' : 'bg-emerald-50' }}">
                            <svg class="w-6 h-6 {{ $iconClass }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        @if($isOccupied)
                            <div class="flex items-center px-2 py-1 rounded-lg text-xs font-medium bg-white/20 text-white backdrop-blur-sm">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                {{ $table->active_orders_count }}
                            </div>
                        @else
                            <div class="flex items-center px-2 py-1 rounded-lg text-xs font-medium {{ $badgeClass }}">
                                <svg class="w-3 h-3 mr-1 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                {{ $table->capacity }}
                            </div>
                        @endif
                    </div>

                    <!-- Table Info -->
                    <div class="w-full relative z-10 mb-2">
                        <h3 class="text-lg font-bold {{ $textClass }} leading-tight">{{ $table->name }}</h3>
                        <p class="text-xs {{ $subTextClass }} myanmar-text mt-1 line-clamp-1">{{ $table->name_mm }}</p>
                    </div>

                    <!-- Status / Action -->
                    <div class="w-full relative z-10 mt-auto pt-3 border-t {{ $isOccupied ? 'border-white/20' : 'border-gray-100' }}">
                        @if($isOccupied)
                            <div class="flex items-center justify-between">
                                <div class="text-sm font-bold text-white">
                                    {{ number_format($totalAmount, 0) }} Ks
                                </div>
                                <div class="flex items-center text-xs font-medium text-white/90">
                                    <span class="myanmar-text">ငွေရှင်းမည်</span>
                                    <svg class="w-3 h-3 ml-1 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </div>
                            </div>
                        @else
                            <div class="flex items-center text-xs font-medium text-emerald-600">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="myanmar-text">အားလပ်သည်</span>
                            </div>
                        @endif
                    </div>
                </button>
            @empty
                <div class="col-span-full flex flex-col items-center justify-center py-16 text-center bg-white rounded-2xl border border-dashed border-gray-200">
                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    <h3 class="text-base font-medium text-gray-900 myanmar-text">စားပွဲများ မတွေ့ရှိပါ</h3>
                    <p class="text-sm text-gray-500 mt-1 myanmar-text">ကျေးဇူးပြု၍ Admin ထံ ဆက်သွယ်ပါ</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Flash Messages -->
    @if(session()->has('info'))
    <div class="fixed top-4 right-4 z-50 bg-blue-500 text-white px-6 py-3 rounded-lg shadow-lg animate-pulse">
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span class="myanmar-text">{{ session('info') }}</span>
        </div>
    </div>
    @endif
</div>
