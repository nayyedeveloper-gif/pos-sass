<div>
    <!-- Success Message -->
    @if (session()->has('message'))
    <div class="mb-4 bg-green-50 border-l-4 border-green-400 p-4">
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

    <!-- Error Message -->
    @if (session()->has('error'))
    <div class="mb-4 bg-red-50 border-l-4 border-red-400 p-4">
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
            <h2 class="text-2xl font-bold text-gray-900 myanmar-text">အသုံးပြုသူများ စီမံခန့်ခွဲမှု</h2>
            <p class="mt-1 text-sm text-gray-600 myanmar-text">အသုံးပြုသူများကို ထည့်သွင်း၊ ပြင်ဆင်၊ ဖျက်ပစ်နိုင်ပါသည်။</p>
        </div>

        <div class="flex gap-3">
            <!-- Export Button -->
            <button wire:click="exportExcel"
                    class="btn btn-outline">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <span class="myanmar-text">Excel ထုတ်မည်</span>
            </button>

            <!-- Create Button -->
            <button wire:click="create"
                    class="btn btn-primary">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                <span class="myanmar-text">အသုံးပြုသူအသစ် ထည့်ရန်</span>
            </button>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-sm p-4 md:p-6 mb-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <!-- Search -->
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1 myanmar-text">ရှာဖွေရန်</label>
                <input type="text"
                       id="search"
                       name="search"
                       wire:model.live.debounce.300ms="search"
                       placeholder="အမည်၊ အီးမေးလ် သို့မဟုတ် ဖုန်း..."
                       class="input">
            </div>

            <!-- Role Filter -->
            <div>
                <label for="roleFilter" class="block text-sm font-medium text-gray-700 mb-1 myanmar-text">အခန်းကဏ္ဍ</label>
                <select id="roleFilter"
                        name="roleFilter"
                        wire:model.live="roleFilter"
                        class="input">
                    <option value="">အားလုံး</option>
                    @foreach($roles as $role)
                    <option value="{{ $role->name }}">
                        @switch($role->name)
                            @case('admin')
                                စီမံခန့်ခွဲသူ
                                @break
                            @case('cashier')
                                ငွေကိုင်
                                @break
                            @case('waiter')
                                စားပွဲထိုး
                                @break
                            @case('kitchen')
                                မီးဖိုချောင်
                                @break
                            @case('bar')
                                သောက်စရာ
                                @break
                            @default
                                {{ $role->name }}
                        @endswitch
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Status Filter -->
            <div>
                <label for="statusFilter" class="block text-sm font-medium text-gray-700 mb-1 myanmar-text">အခြေအနေ</label>
                <select id="statusFilter"
                        name="statusFilter"
                        wire:model.live="statusFilter"
                        class="input">
                    <option value="">အားလုံး</option>
                    <option value="active">အသုံးပြုနေသော</option>
                    <option value="inactive">ပိတ်ထားသော</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Users Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
        @forelse($users as $user)
        <div class="bg-white rounded-lg shadow-sm overflow-hidden border border-gray-200 hover:shadow-md transition-shadow">
            <div class="p-6">
                <div class="flex justify-between items-start mb-4">
                    <div class="flex-1">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0 h-12 w-12">
                                <div class="h-12 w-12 rounded-full bg-primary-500 flex items-center justify-center text-white font-bold text-lg">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">{{ $user->name }}</h3>
                                @if($user->id === auth()->id())
                                <span class="text-xs text-blue-600 bg-blue-100 px-2 py-1 rounded-full myanmar-text">(သင်)</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="flex space-x-2">
                        @if($user->is_active)
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 myanmar-text">
                            အသုံးပြုနေသော
                        </span>
                        @else
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800 myanmar-text">
                            ပိတ်ထားသော
                        </span>
                        @endif
                    </div>
                </div>

                <div class="space-y-2 mb-4">
                    <div class="flex items-center text-sm text-gray-600">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        {{ $user->email }}
                    </div>

                    @if($user->phone)
                    <div class="flex items-center text-sm text-gray-600">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                        {{ $user->phone }}
                    </div>
                    @endif

                    <div class="flex items-center text-sm text-gray-600">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h2a2 2 0 012 2v4m-6 8h6m6 0v8a2 2 0 01-2 2H6a2 2 0 01-2-2v-8a2 2 0 012-2h1m10 0V7a2 2 0 00-2-2H9a2 2 0 00-2 2v2.01"></path>
                        </svg>
                        @foreach($user->roles as $role)
                        <span class="myanmar-text">
                            @switch($role->name)
                                @case('admin')
                                    စီမံခန့်ခွဲသူ
                                    @break
                                @case('cashier')
                                    ငွေကိုင်
                                    @break
                                @case('waiter')
                                    စားပွဲထိုး
                                    @break
                                @case('kitchen')
                                    မီးဖိုချောင်
                                    @break
                                @case('bar')
                                    သောက်စရာ
                                    @break
                                @default
                                    {{ $role->name }}
                            @endswitch
                        </span>
                        @endforeach
                    </div>
                </div>

                <div class="flex justify-between items-center pt-4 border-t border-gray-200">
                    <div class="text-xs text-gray-500">
                        {{ $user->created_at->format('M d, Y') }}
                    </div>
                    <div class="flex space-x-2">
                        <!-- Toggle Active/Inactive -->
                        <button wire:click="toggleActive({{ $user->id }})"
                                class="p-1 text-blue-600 hover:text-blue-800 rounded transition-colors"
                                title="အခြေအနေ ပြောင်းရန်">
                            @if($user->is_active)
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                            </svg>
                            @else
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            @endif
                        </button>

                        <!-- Edit -->
                        <button wire:click="edit({{ $user->id }})"
                                class="p-1 text-indigo-600 hover:text-indigo-800 rounded transition-colors"
                                title="ပြင်ဆင်ရန်">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </button>

                        <!-- Delete (only if not current user) -->
                        @if($user->id !== auth()->id())
                        <button wire:click="confirmDelete({{ $user->id }})"
                                class="p-1 text-red-600 hover:text-red-900 rounded transition-colors"
                                title="ဖျက်ရန်">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full">
            <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-1 myanmar-text">အသုံးပြုသူ မတွေ့ပါ</h3>
                <p class="text-gray-500 myanmar-text">သင့်ရှာဖွေမှုနှင့် ကိုက်ညီသော အသုံးပြုသူ မရှိပါ။</p>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($users->hasPages())
    <div class="mt-6">
        {{ $users->links() }}
    </div>
    @endif

    <!-- Create/Edit Modal -->
    @if($showModal)
    <div class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50 p-4 overflow-y-auto">
        <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] flex flex-col">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 rounded-t-xl">
                <h3 class="text-xl font-bold text-gray-900 myanmar-text">
                    {{ $editMode ? 'အသုံးပြုသူ ပြင်ဆင်ရန်' : 'အသုံးပြုသူအသစ် ထည့်ရန်' }}
                </h3>
            </div>

            <!-- Form -->
            <form wire:submit.prevent="save" class="flex-1 overflow-y-auto">
                <div class="px-6 py-6 space-y-6">
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2 myanmar-text">
                            အမည် <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               id="name"
                               name="name"
                               wire:model="name"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors @error('name') border-red-500 @enderror"
                               placeholder="အမည်ကို ဖြည့်သွင်းပါ">
                        @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2 myanmar-text">
                            အီးမေးလ် <span class="text-red-500">*</span>
                        </label>
                        <input type="email"
                               id="email"
                               name="email"
                               wire:model="email"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors @error('email') border-red-500 @enderror"
                               placeholder="user@example.com">
                        @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2 myanmar-text">ဖုန်းနံပါတ်</label>
                        <input type="text"
                               id="phone"
                               name="phone"
                               wire:model="phone"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors @error('phone') border-red-500 @enderror"
                               placeholder="09xxxxxxxxx">
                        @error('phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Role -->
                    <div>
                        <label for="selectedRole" class="block text-sm font-medium text-gray-700 mb-2 myanmar-text">
                            အခန်းကဏ္ဍ <span class="text-red-500">*</span>
                        </label>
                        <select id="selectedRole"
                                name="selectedRole"
                                wire:model="selectedRole"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors @error('selectedRole') border-red-500 @enderror">
                            <option value="">ရွေးချယ်ပါ</option>
                            @foreach($roles as $role)
                            <option value="{{ $role->name }}">
                                @switch($role->name)
                                    @case('admin')
                                        စီမံခန့်ခွဲသူ / Admin
                                        @break
                                    @case('cashier')
                                        ငွေကိုင် / Cashier
                                        @break
                                    @case('waiter')
                                        စားပွဲထိုး / Waiter
                                        @break
                                    @case('kitchen')
                                        မီးဖိုချောင် / Kitchen
                                        @break
                                    @case('bar')
                                        သောက်စရာ / Bar
                                        @break
                                    @default
                                        {{ $role->name }}
                                @endswitch
                            </option>
                            @endforeach
                        </select>
                        @error('selectedRole')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2 myanmar-text">
                            စကားဝှက် {{ $editMode ? '(ပြောင်းလိုပါက သာ ဖြည့်ပါ)' : '*' }}
                        </label>
                        <input type="password"
                               id="password"
                               name="password"
                               wire:model="password"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors @error('password') border-red-500 @enderror"
                               placeholder="••••••••">
                        @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Confirmation -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2 myanmar-text">
                            စကားဝှက် အတည်ပြုရန် {{ $editMode ? '' : '*' }}
                        </label>
                        <input type="password"
                               id="password_confirmation"
                               name="password_confirmation"
                               wire:model="password_confirmation"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors @error('password_confirmation') border-red-500 @enderror"
                               placeholder="••••••••">
                        @error('password_confirmation')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Active Status -->
                    <div class="flex items-center">
                        <input type="checkbox"
                               id="is_active"
                               name="is_active"
                               wire:model="is_active"
                               class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                        <label for="is_active" class="ml-2 text-sm text-gray-700 myanmar-text">
                            အသုံးပြုနေသော အကောင့်
                        </label>
                    </div>
                </div>
            </form>

            <!-- Footer -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 rounded-b-xl flex justify-end space-x-3">
                <button type="button"
                        wire:click="closeModal"
                        class="px-6 py-2.5 border border-gray-300 rounded-lg font-semibold text-gray-700 hover:bg-gray-100 transition-colors myanmar-text">
                    မလုပ်တော့ပါ
                </button>
                <button type="button"
                        wire:click="save"
                        class="px-6 py-2.5 bg-primary-600 hover:bg-primary-700 text-white font-semibold rounded-lg transition-colors myanmar-text">
                    {{ $editMode ? 'ပြင်ဆင်မည်' : 'ထည့်သွင်းမည်' }}
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- Delete Confirmation Modal -->
    @if($deleteConfirm)
    <div class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-xl shadow-xl max-w-md w-full">
            <div class="px-6 py-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    <div class="ml-3 flex-1">
                        <h3 class="text-lg font-medium text-gray-900 myanmar-text">အသုံးပြုသူ ဖျက်ရန် သေချာပါသလား?</h3>
                        <p class="mt-2 text-sm text-gray-500 myanmar-text">
                            ဤလုပ်ဆောင်ချက်ကို နောက်ပြန်ပြောင်း၍ မရပါ။ အသုံးပြုသူနှင့် သက်ဆိုင်သော အချက်အလက်အားလုံး ပျောက်ဆုံးသွားပါလိမ့်မည်။
                        </p>
                    </div>
                </div>
            </div>
            <div class="px-6 py-4 bg-gray-50 rounded-b-xl flex justify-end space-x-3">
                <button type="button"
                        wire:click="$set('deleteConfirm', false)"
                        class="px-4 py-2 border border-gray-300 rounded-lg font-semibold text-gray-700 hover:bg-gray-100 transition-colors myanmar-text">
                    မလုပ်တော့ပါ
                </button>
                <button type="button"
                        wire:click="delete"
                        class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg transition-colors myanmar-text">
                    ဖျက်မည်
                </button>
            </div>
        </div>
    </div>
    @endif
</div>
