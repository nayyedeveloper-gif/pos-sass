<div class="py-8 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Success Message -->
        @if (session()->has('message'))
        <div class="mb-6 rounded-xl bg-emerald-50 border border-emerald-100 p-4 flex items-center shadow-sm"
             x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-emerald-800 myanmar-text">{{ session('message') }}</p>
            </div>
        </div>
        @endif

        <!-- Header -->
        <div class="sm:flex sm:items-center sm:justify-between mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 myanmar-text">ဝန်ထမ်းများ စီမံခန့်ခွဲမှု</h1>
                <p class="mt-2 text-sm text-gray-600 myanmar-text">စနစ်အသုံးပြုသူ ဝန်ထမ်းများနှင့် ၎င်းတို့၏ လုပ်ပိုင်ခွင့်များကို စီမံခန့်ခွဲနိုင်ပါသည်။</p>
            </div>
            <div class="mt-4 sm:mt-0">
                <button wire:click="openCreateModal" class="inline-flex items-center px-4 py-2 border border-transparent rounded-xl shadow-sm text-sm font-medium text-white bg-gray-900 hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900 transition-all">
                    <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span class="myanmar-text">ဝန်ထမ်းအသစ်</span>
                </button>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-6">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <!-- Search -->
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="အမည်၊ အီးမေးလ်၊ ဖုန်း ရှာရန်..." class="block w-full pl-10 pr-3 py-2.5 border border-gray-200 rounded-xl leading-5 bg-gray-50 placeholder-gray-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500 sm:text-sm transition duration-150 ease-in-out myanmar-text">
                </div>

                <!-- Role Filter -->
                <div>
                    <select wire:model.live="roleFilter" class="block w-full py-2.5 pl-3 pr-10 border border-gray-200 rounded-xl leading-5 bg-gray-50 focus:outline-none focus:bg-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500 sm:text-sm transition duration-150 ease-in-out myanmar-text">
                        <option value="">ရာထူး အားလုံး</option>
                        @foreach($availableRoles as $role)
                        <option value="{{ $role }}">{{ $roleInfo[$role]['name'] ?? ucfirst($role) }} - {{ $roleInfo[$role]['name_mm'] ?? '' }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Status Filter -->
                <div>
                    <select wire:model.live="statusFilter" class="block w-full py-2.5 pl-3 pr-10 border border-gray-200 rounded-xl leading-5 bg-gray-50 focus:outline-none focus:bg-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500 sm:text-sm transition duration-150 ease-in-out myanmar-text">
                        <option value="">အခြေအနေ အားလုံး</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Stats Cards - Dynamic based on available roles -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
            <!-- Total Users -->
            <div class="bg-gradient-to-br from-gray-700 to-gray-900 rounded-2xl shadow-lg p-5 text-white">
                <div class="flex items-center justify-between mb-3">
                    <div class="p-2 bg-white/20 rounded-xl">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                </div>
                <p class="text-sm text-white/80 myanmar-text">စုစုပေါင်း</p>
                <p class="text-2xl font-bold mt-1">{{ $users->total() }}</p>
            </div>
            
            <!-- Dynamic Role Stats (show first 3 roles) -->
            @php
                $roleGradients = [
                    'owner' => 'from-violet-500 to-purple-600',
                    'manager' => 'from-indigo-500 to-blue-600',
                    'cashier' => 'from-cyan-500 to-blue-600',
                    'waiter' => 'from-orange-500 to-amber-600',
                    'kitchen' => 'from-red-500 to-rose-600',
                    'bar' => 'from-amber-500 to-yellow-600',
                    'barista' => 'from-yellow-500 to-orange-600',
                    'inventory' => 'from-teal-500 to-cyan-600',
                    'sales' => 'from-emerald-500 to-green-600',
                    'pharmacist' => 'from-cyan-500 to-teal-600',
                    'stylist' => 'from-pink-500 to-rose-600',
                    'staff' => 'from-gray-500 to-slate-600',
                    'admin' => 'from-violet-500 to-purple-600',
                ];
                $displayRoles = array_slice($availableRoles, 0, 3);
            @endphp
            
            @foreach($displayRoles as $role)
            @php
                $gradient = $roleGradients[$role] ?? 'from-gray-500 to-slate-600';
                $count = $users->filter(fn($u) => $u->roles->first()?->name === $role)->count();
            @endphp
            <div class="bg-gradient-to-br {{ $gradient }} rounded-2xl shadow-lg p-5 text-white">
                <div class="flex items-center justify-between mb-3">
                    <div class="p-2 bg-white/20 rounded-xl">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                </div>
                <p class="text-sm text-white/80">{{ $roleInfo[$role]['name'] ?? ucfirst($role) }}</p>
                <p class="text-2xl font-bold mt-1">{{ $count }}</p>
            </div>
            @endforeach
        </div>

        <!-- Users Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50/50">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider myanmar-text">ဝန်ထမ်း</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider myanmar-text">ဆက်သွယ်ရန်</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider myanmar-text">ရာထူး</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider myanmar-text">အခြေအနေ</th>
                            <th scope="col" class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider myanmar-text">လုပ်ဆောင်ချက်</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($users as $user)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    @php
                                        $role = $user->roles->first()?->name;
                                        $avatarBg = match($role) {
                                            'admin' => 'bg-purple-100 text-purple-700',
                                            'cashier' => 'bg-blue-100 text-blue-700',
                                            'waiter' => 'bg-orange-100 text-orange-700',
                                            default => 'bg-gray-100 text-gray-700'
                                        };
                                    @endphp
                                    <div class="h-10 w-10 rounded-xl {{ $avatarBg }} flex items-center justify-center font-bold text-sm">
                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-semibold text-gray-900">{{ $user->name }}</div>
                                        <div class="text-xs text-gray-500">ID: #{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-col">
                                    <div class="flex items-center text-sm text-gray-900">
                                        <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                        {{ $user->email }}
                                    </div>
                                    @if($user->phone)
                                    <div class="flex items-center text-xs text-gray-500 mt-1">
                                        <svg class="w-3.5 h-3.5 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                        {{ $user->phone }}
                                    </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $roleClass = match($role) {
                                        'admin' => 'bg-purple-50 text-purple-700 border-purple-200 ring-purple-600/20',
                                        'cashier' => 'bg-blue-50 text-blue-700 border-blue-200 ring-blue-600/20',
                                        'waiter' => 'bg-orange-50 text-orange-700 border-orange-200 ring-orange-600/20',
                                        default => 'bg-gray-50 text-gray-700 border-gray-200 ring-gray-600/20'
                                    };
                                    $roleIcon = match($role) {
                                        'admin' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>',
                                        'cashier' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>',
                                        'waiter' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>',
                                        default => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>'
                                    };
                                @endphp
                                <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-medium border ring-1 ring-inset {{ $roleClass }}">
                                    <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $roleIcon !!}</svg>
                                    {{ ucfirst($role ?? 'No Role') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <button wire:click="toggleUserStatus({{ $user->id }})" class="group">
                                    @if($user->is_active)
                                    <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200 ring-1 ring-inset ring-emerald-600/20 group-hover:bg-emerald-100 transition-colors">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5 animate-pulse"></span>
                                        Active
                                    </span>
                                    @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-medium bg-gray-50 text-gray-600 border border-gray-200 ring-1 ring-inset ring-gray-600/20 group-hover:bg-gray-100 transition-colors">
                                        <span class="w-1.5 h-1.5 rounded-full bg-gray-400 mr-1.5"></span>
                                        Inactive
                                    </span>
                                    @endif
                                </button>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex justify-center space-x-1">
                                    <button wire:click="openEditModal({{ $user->id }})" class="p-2 rounded-lg text-primary-600 hover:text-primary-700 hover:bg-primary-50 transition-colors" title="ပြင်ဆင်ရန်">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </button>
                                    <button wire:click="confirmDelete({{ $user->id }})" class="p-2 rounded-lg text-rose-600 hover:text-rose-700 hover:bg-rose-50 transition-colors" title="ဖျက်ရန်">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-900 myanmar-text">ဝန်ထမ်းများ မရှိပါ</h3>
                                    <p class="mt-1 text-sm text-gray-500 myanmar-text">ဝန်ထမ်းအသစ် စတင်ထည့်သွင်းပါ။</p>
                                    <button wire:click="openCreateModal" class="mt-4 inline-flex items-center px-4 py-2 border border-gray-300 rounded-xl shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                        <span class="myanmar-text">ဝန်ထမ်းအသစ် ထည့်ရန်</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($users->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $users->links() }}
            </div>
            @endif
        </div>
    </div>

    <!-- Create/Edit Modal -->
    @if($showCreateForm || $showEditForm)
    <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm flex items-center justify-center z-50 p-4 transition-opacity">
        <div class="bg-white rounded-2xl shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto transform transition-all">
            <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center">
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-xl {{ $showCreateForm ? 'bg-emerald-100' : 'bg-blue-100' }} flex items-center justify-center mr-3">
                        @if($showCreateForm)
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                        @else
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        @endif
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 myanmar-text">
                            {{ $showCreateForm ? 'ဝန်ထမ်းအသစ် ထည့်ရန်' : 'ဝန်ထမ်းအချက်အလက် ပြင်ဆင်ရန်' }}
                        </h3>
                        <p class="text-xs text-gray-500 myanmar-text">{{ $showCreateForm ? 'ဝန်ထမ်းအချက်အလက်များ ဖြည့်သွင်းပါ' : 'လိုအပ်သော အချက်အလက်များကို ပြင်ဆင်ပါ' }}</p>
                    </div>
                </div>
                <button wire:click="cancelForm" class="text-gray-400 hover:text-gray-500 focus:outline-none transition-colors p-1 rounded-lg hover:bg-gray-100">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <form wire:submit.prevent="{{ $showCreateForm ? 'createUser' : 'updateUser' }}">
                <div class="px-6 py-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2 myanmar-text">အမည် <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                </div>
                                <input type="text" wire:model="name" class="block w-full pl-10 rounded-xl border-gray-200 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm p-2.5 bg-gray-50 focus:bg-white transition-colors" placeholder="ဝန်ထမ်းအမည်">
                            </div>
                            @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2 myanmar-text">အီးမေးလ် <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                </div>
                                <input type="email" wire:model="email" class="block w-full pl-10 rounded-xl border-gray-200 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm p-2.5 bg-gray-50 focus:bg-white transition-colors" placeholder="example@email.com">
                            </div>
                            @error('email') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2 myanmar-text">ဖုန်းနံပါတ်</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                </div>
                                <input type="text" wire:model="phone" class="block w-full pl-10 rounded-xl border-gray-200 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm p-2.5 bg-gray-50 focus:bg-white transition-colors" placeholder="09xxxxxxxxx">
                            </div>
                            @error('phone') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2 myanmar-text">ရာထူး <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                </div>
                                <select wire:model="selectedRole" class="block w-full pl-10 rounded-xl border-gray-200 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm p-2.5 bg-gray-50 focus:bg-white transition-colors appearance-none myanmar-text">
                                    <option value="">ရာထူးရွေးချယ်ပါ</option>
                                    @foreach($availableRoles as $role)
                                    <option value="{{ $role }}">{{ $roleInfo[$role]['name'] ?? ucfirst($role) }} - {{ $roleInfo[$role]['name_mm'] ?? '' }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('selectedRole') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        @if($showCreateForm)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2 myanmar-text">စကားဝှက် <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                </div>
                                <input type="password" wire:model="password" class="block w-full pl-10 rounded-xl border-gray-200 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm p-2.5 bg-gray-50 focus:bg-white transition-colors" placeholder="••••••••">
                            </div>
                            @error('password') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2 myanmar-text">စကားဝှက် အတည်ပြုရန် <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <input type="password" wire:model="password_confirmation" class="block w-full pl-10 rounded-xl border-gray-200 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm p-2.5 bg-gray-50 focus:bg-white transition-colors" placeholder="••••••••">
                            </div>
                            @error('password_confirmation') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        @endif

                        @if($showEditForm)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2 myanmar-text">စကားဝှက်အသစ် <span class="text-xs text-gray-500 font-normal">(မလိုအပ်ပါ)</span></label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                </div>
                                <input type="password" wire:model="password" class="block w-full pl-10 rounded-xl border-gray-200 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm p-2.5 bg-gray-50 focus:bg-white transition-colors" placeholder="••••••••">
                            </div>
                            @error('password') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2 myanmar-text">စကားဝှက်အသစ် အတည်ပြုရန်</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <input type="password" wire:model="password_confirmation" class="block w-full pl-10 rounded-xl border-gray-200 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm p-2.5 bg-gray-50 focus:bg-white transition-colors" placeholder="••••••••">
                            </div>
                            @error('password_confirmation') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        @endif
                    </div>

                    <div class="flex items-center justify-between bg-gray-50 p-4 rounded-xl border border-gray-100">
                        <span class="flex flex-col">
                            <span class="text-sm font-medium text-gray-900 myanmar-text">အသုံးပြုခွင့်</span>
                            <span class="text-xs text-gray-500 myanmar-text">ဖွင့်ထားပါက စနစ်ကို ဝင်ရောက်အသုံးပြုနိုင်မည်ဖြစ်သည်။</span>
                        </span>
                        <button type="button" wire:click="$toggle('is_active')" class="relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 {{ $is_active ? 'bg-emerald-500' : 'bg-gray-200' }}">
                            <span class="sr-only">Toggle active</span>
                            <span aria-hidden="true" class="pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200 {{ $is_active ? 'translate-x-5' : 'translate-x-0' }}"></span>
                        </button>
                    </div>
                </div>

                <div class="px-6 py-5 bg-gray-50 border-t border-gray-100 flex justify-end space-x-3 rounded-b-2xl">
                    <button type="button" wire:click="cancelForm" class="px-4 py-2.5 bg-white border border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50 font-medium text-sm transition-colors shadow-sm myanmar-text">
                        မလုပ်တော့ပါ
                    </button>
                    <button type="submit" class="px-4 py-2.5 bg-gray-900 border border-transparent rounded-xl text-white hover:bg-gray-800 font-medium text-sm transition-colors shadow-sm myanmar-text inline-flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        {{ $showCreateForm ? 'ထည့်သွင်းမည်' : 'ပြင်ဆင်မည်' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <!-- Delete Confirmation Modal -->
    @if($deleteConfirmId)
    <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm flex items-center justify-center z-50 p-4 transition-opacity">
        <div class="bg-white rounded-2xl shadow-xl max-w-sm w-full overflow-hidden transform transition-all">
            <div class="p-6">
                <div class="flex items-center justify-center w-14 h-14 mx-auto bg-red-100 rounded-full mb-4">
                    <svg class="w-7 h-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-center text-gray-900 mb-2 myanmar-text">ဖျက်ရန် သေချာပါသလား?</h3>
                <p class="text-sm text-center text-gray-500 myanmar-text">ဤဝန်ထမ်းကို ဖျက်လိုက်ပါက ပြန်ယူ၍မရနိုင်ပါ။ ဆက်လက်ဆောင်ရွက်မှာ သေချာပါသလား?</p>
            </div>
            <div class="px-6 py-4 bg-gray-50 flex justify-center space-x-3 border-t border-gray-100">
                <button type="button" wire:click="cancelDelete" class="px-4 py-2.5 bg-white border border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50 font-medium text-sm transition-colors shadow-sm myanmar-text">
                    မလုပ်တော့ပါ
                </button>
                <button type="button" wire:click="deleteUser" class="px-4 py-2.5 bg-red-600 border border-transparent rounded-xl text-white hover:bg-red-700 font-medium text-sm transition-colors shadow-sm myanmar-text inline-flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    ဖျက်မည်
                </button>
            </div>
        </div>
    </div>
    @endif
</div>
