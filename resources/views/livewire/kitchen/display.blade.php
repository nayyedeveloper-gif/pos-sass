<div class="p-6 bg-gray-100 min-h-screen" wire:poll.10s="loadOrders">
    <!-- Header & Filters -->
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
                <span class="myanmar-text">မီးဖိုချောင်</span> / Kitchen Display
            </h1>
        </div>
        
        <div class="flex bg-white p-1 rounded-lg shadow-sm">
            <button wire:click="setFilter('all')" class="px-4 py-2 rounded-md text-sm font-medium transition-colors {{ $filter === 'all' ? 'bg-primary-600 text-white' : 'text-gray-600 hover:bg-gray-50' }}">
                All
            </button>
            <button wire:click="setFilter('pending')" class="px-4 py-2 rounded-md text-sm font-medium transition-colors {{ $filter === 'pending' ? 'bg-yellow-500 text-white' : 'text-gray-600 hover:bg-gray-50' }}">
                Pending
            </button>
            <button wire:click="setFilter('preparing')" class="px-4 py-2 rounded-md text-sm font-medium transition-colors {{ $filter === 'preparing' ? 'bg-blue-500 text-white' : 'text-gray-600 hover:bg-gray-50' }}">
                Preparing
            </button>
            <button wire:click="setFilter('ready')" class="px-4 py-2 rounded-md text-sm font-medium transition-colors {{ $filter === 'ready' ? 'bg-green-500 text-white' : 'text-gray-600 hover:bg-gray-50' }}">
                Ready
            </button>
        </div>
    </div>

    <!-- Orders Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($orders as $order)
            <div class="bg-white rounded-xl shadow-md border-l-4 overflow-hidden flex flex-col h-full {{ $order['status'] === 'pending' ? 'border-yellow-500' : ($order['status'] === 'preparing' ? 'border-blue-500' : 'border-green-500') }}">
                <!-- Card Header -->
                <div class="p-4 bg-gray-50 border-b border-gray-100 flex justify-between items-start">
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-xl font-bold text-gray-800">#{{ substr($order['order_number'], -4) }}</span>
                            <span class="px-2 py-0.5 text-xs rounded-full font-medium {{ $order['status'] === 'pending' ? 'bg-yellow-100 text-yellow-800' : ($order['status'] === 'preparing' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }}">
                                {{ ucfirst($order['status']) }}
                            </span>
                        </div>
                        <div class="text-sm text-gray-600 font-medium">{{ $order['table_name'] }}</div>
                        <div class="text-xs text-gray-500">Waiter: {{ $order['waiter_name'] }}</div>
                    </div>
                    <div class="text-right">
                        <div class="text-xs font-mono text-gray-500">{{ $order['created_at']->format('h:i A') }}</div>
                        <div class="text-xs text-gray-400 mt-1">{{ $order['created_at']->diffForHumans() }}</div>
                    </div>
                </div>

                <!-- Items List -->
                <div class="p-4 flex-1 overflow-y-auto max-h-96 space-y-3">
                    @foreach($order['items'] as $item)
                        <div class="flex items-start justify-between group p-2 rounded-lg hover:bg-gray-50 transition-colors {{ $item->status === 'served' ? 'opacity-50' : '' }}">
                            <div class="flex-1">
                                <div class="flex items-center gap-2">
                                    <span class="font-bold text-gray-800 {{ $item->status === 'served' ? 'line-through' : '' }}">
                                        {{ $item->quantity }}x
                                    </span>
                                    <span class="text-gray-800 font-medium {{ $item->status === 'served' ? 'line-through' : '' }}">
                                        {{ $item->item->name }}
                                    </span>
                                </div>
                                <div class="text-xs text-gray-500 myanmar-text ml-6">{{ $item->item->name_mm }}</div>
                                @if($item->notes)
                                    <div class="text-xs text-red-500 ml-6 mt-1 font-medium bg-red-50 px-2 py-1 rounded inline-block">
                                        Note: {{ $item->notes }}
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Item Status Actions -->
                            <div class="flex items-center gap-1">
                                @if($item->status === 'pending')
                                    <button wire:click="updateStatus({{ $item->id }}, 'preparing')" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-full" title="Start Preparing">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </button>
                                @elseif($item->status === 'preparing')
                                    <button wire:click="updateStatus({{ $item->id }}, 'ready')" class="p-1.5 text-green-600 hover:bg-green-50 rounded-full" title="Mark Ready">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    </button>
                                @elseif($item->status === 'ready')
                                    <span class="text-green-600 font-bold text-xs">Ready</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Card Actions -->
                <div class="p-4 bg-gray-50 border-t border-gray-100 grid grid-cols-2 gap-2">
                    @if($order['status'] === 'pending')
                        <button wire:click="updateOrderStatus({{ $order['order_id'] }}, 'preparing')" class="col-span-2 w-full py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition-colors flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path></svg>
                            Start All
                        </button>
                    @elseif($order['status'] === 'preparing')
                        <button wire:click="updateOrderStatus({{ $order['order_id'] }}, 'ready')" class="col-span-2 w-full py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-medium transition-colors flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Mark All Ready
                        </button>
                    @elseif($order['status'] === 'ready')
                         <button wire:click="updateOrderStatus({{ $order['order_id'] }}, 'served')" class="col-span-2 w-full py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg text-sm font-medium transition-colors flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Complete
                        </button>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-full flex flex-col items-center justify-center py-20 text-gray-400">
                <div class="bg-gray-200 p-6 rounded-full mb-4">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-medium text-gray-600">No Orders Found</h3>
                <p class="text-gray-500 mt-2">There are no orders in the {{ $filter }} status.</p>
            </div>
        @endforelse
    </div>
</div>
