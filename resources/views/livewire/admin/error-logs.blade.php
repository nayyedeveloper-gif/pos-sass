<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Success/Error Messages -->
        @if (session()->has('message'))
        <div class="mb-4 bg-green-50 border-l-4 border-green-400 p-4 rounded-lg">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-700 myanmar-text">{{ session('message') }}</p>
                </div>
            </div>
        </div>
        @endif

        @if (session()->has('error'))
        <div class="mb-4 bg-red-50 border-l-4 border-red-400 p-4 rounded-lg">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-red-700 myanmar-text">{{ session('error') }}</p>
                </div>
            </div>
        </div>
        @endif

        <!-- Header -->
        <div class="mb-6 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Error Logs</h2>
                <p class="mt-1 text-sm text-gray-600 myanmar-text">စနစ်အမှားများ မှတ်တမ်း</p>
            </div>
            <div class="flex gap-3">
                <button wire:click="downloadLogs" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Download
                </button>
                <button wire:click="clearLogs" 
                        wire:confirm="Log files အားလုံးကို ရှင်းလင်းမှာ သေချာပါသလား?"
                        class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    <span class="myanmar-text">ရှင်းလင်းမည်</span>
                </button>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow-sm p-4 md:p-6 mb-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <!-- Search -->
                <div class="lg:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1 myanmar-text">ရှာဖွေရန်</label>
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search in logs..." class="input">
                </div>

                <!-- Level Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Level</label>
                    <select wire:model.live="levelFilter" class="input">
                        <option value="">All Levels</option>
                        <option value="ERROR">ERROR</option>
                        <option value="WARNING">WARNING</option>
                        <option value="INFO">INFO</option>
                        <option value="DEBUG">DEBUG</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Logs Table -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                @if($logs->isEmpty())
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="mt-2 text-sm text-gray-600 myanmar-text">အမှားအယွင်း မတွေ့ပါ</p>
                    </div>
                @else
                    <div class="space-y-2 p-4">
                        @foreach($logs as $log)
                        <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors">
                            <div class="flex items-start justify-between mb-2">
                                <div class="flex items-center space-x-3">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if(strtoupper($log['level']) === 'ERROR') bg-red-100 text-red-800
                                        @elseif(strtoupper($log['level']) === 'WARNING') bg-yellow-100 text-yellow-800
                                        @elseif(strtoupper($log['level']) === 'INFO') bg-blue-100 text-blue-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ strtoupper($log['level']) }}
                                    </span>
                                    <span class="text-xs text-gray-500 font-mono">{{ $log['environment'] }}</span>
                                </div>
                                <span class="text-xs text-gray-500 font-mono">{{ $log['timestamp'] }}</span>
                            </div>
                            <div class="text-sm text-gray-700 font-mono bg-gray-50 p-3 rounded overflow-x-auto">
                                <pre class="whitespace-pre-wrap break-words">{{ $log['message'] }}</pre>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Pagination Info -->
                    <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-between">
                        <div class="text-sm text-gray-700">
                            Showing <span class="font-medium">{{ ($currentPage - 1) * $perPage + 1 }}</span> 
                            to <span class="font-medium">{{ min($currentPage * $perPage, $total) }}</span> 
                            of <span class="font-medium">{{ $total }}</span> logs
                        </div>
                        
                        @if($lastPage > 1)
                        <div class="flex space-x-2">
                            @if($currentPage > 1)
                                <button wire:click="previousPage" class="px-3 py-1 border border-gray-300 rounded hover:bg-gray-50">
                                    Previous
                                </button>
                            @endif
                            
                            <span class="px-3 py-1 text-sm text-gray-700">
                                Page {{ $currentPage }} of {{ $lastPage }}
                            </span>
                            
                            @if($currentPage < $lastPage)
                                <button wire:click="nextPage" class="px-3 py-1 border border-gray-300 rounded hover:bg-gray-50">
                                    Next
                                </button>
                            @endif
                        </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
</div>
