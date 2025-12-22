<div class="py-6">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900 myanmar-text">ကိုယ်ရေးအချက်အလက်</h1>
            <p class="mt-2 text-sm text-gray-600 myanmar-text">
                သင့်အကောင့်အချက်အလက်များကို စီမံခန့်ခွဲပါ
            </p>
        </div>

        <!-- Success Messages -->
        @if (session()->has('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if (session()->has('password_success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                {{ session('password_success') }}
            </div>
        @endif

        <div class="space-y-6">
            <!-- Profile Information -->
            <div class="card">
                <div class="card-header">
                    <h2 class="text-xl font-semibold text-gray-900 myanmar-text">
                        ကိုယ်ရေးအချက်အလက်
                    </h2>
                </div>
                <div class="card-body">
                    <form wire:submit.prevent="updateProfile" class="space-y-4">
                        <!-- Name -->
                        <div>
                            <label class="form-label myanmar-text">အမည်</label>
                            <input type="text" wire:model="name" class="form-input" required>
                            @error('name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="form-label myanmar-text">အီးမေးလ်</label>
                            <input type="email" wire:model="email" class="form-input" required>
                            @error('email') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Phone -->
                        <div>
                            <label class="form-label myanmar-text">ဖုန်းနံပါတ်</label>
                            <input type="text" wire:model="phone" class="form-input">
                            @error('phone') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Role (Read-only) -->
                        <div>
                            <label class="form-label myanmar-text">ရာထူး</label>
                            <input type="text" value="{{ auth()->user()->roles->first()->name ?? 'N/A' }}" class="form-input bg-gray-100" readonly>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end">
                            <button type="submit" class="btn btn-primary">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="myanmar-text">သိမ်းဆံးမည်</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Change Password -->
            <div class="card">
                <div class="card-header">
                    <h2 class="text-xl font-semibold text-gray-900 myanmar-text">
                        လျို့ဝှက်နံပါတ်ပြောင်လဲရန်
                    </h2>
                </div>
                <div class="card-body">
                    <form wire:submit.prevent="updatePassword" class="space-y-4">
                        <!-- Current Password -->
                        <div>
                            <label class="form-label myanmar-text">လက်ရှိလျို့ဝှက်နံပါတ်</label>
                            <input type="password" wire:model="current_password" class="form-input" required>
                            @error('current_password') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- New Password -->
                        <div>
                            <label class="form-label myanmar-text">လျို့ဝှက်နံပါတ်အသသ်</label>
                            <input type="password" wire:model="password" class="form-input" required>
                            @error('password') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label class="form-label myanmar-text">လျို့ဝှက်နံပါတ်အတည်ပြုရန်</label>
                            <input type="password" wire:model="password_confirmation" class="form-input" required>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end">
                            <button type="submit" class="btn btn-primary">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                                <span class="myanmar-text">လျို့ဝှက်နံပါတ်ပြောင်မည်</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
