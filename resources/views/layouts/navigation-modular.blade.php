<nav x-data="{ open: false }" class="bg-white border-b border-gray-100 shadow-sm sticky top-0 z-40">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                        <span class="text-xl font-bold text-primary-600">NexaPOS</span>
                        @if($currentModule !== 'Core')
                            <span class="text-xs bg-{{ $currentModule === 'Restaurant' ? 'orange' : ($currentModule === 'Pharmacy' ? 'green' : 'blue') }}-100 text-{{ $currentModule === 'Restaurant' ? 'orange' : ($currentModule === 'Pharmacy' ? 'green' : 'blue') }}-700 px-2 py-0.5 rounded-full">
                                {{ $currentModule }}
                            </span>
                        @endif
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-4 sm:-my-px sm:ms-10 sm:flex">
                    {{-- Super Admin Navigation --}}
                    @role('super-admin')
                        <x-nav-link :href="route('super-admin.dashboard')" :active="request()->routeIs('super-admin.dashboard')">
                            Dashboard
                        </x-nav-link>
                        <x-nav-link :href="route('super-admin.tenants.index')" :active="request()->routeIs('super-admin.tenants.*')">
                            Tenants
                        </x-nav-link>
                    @endrole

                    {{-- Admin Navigation --}}
                    @role('admin')
                        <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                            Dashboard
                        </x-nav-link>
                        <x-nav-link :href="route('admin.categories.index')" :active="request()->routeIs('admin.categories.*')">
                            Categories
                        </x-nav-link>
                        <x-nav-link :href="route('admin.items.index')" :active="request()->routeIs('admin.items.*')">
                            Items
                        </x-nav-link>
                        
                        {{-- Restaurant Module Links --}}
                        @if(is_module_active('Restaurant'))
                            <x-nav-link :href="route('admin.tables.index')" :active="request()->routeIs('admin.tables.*')">
                                Tables
                            </x-nav-link>
                            <x-nav-link :href="route('admin.reservations.index')" :active="request()->routeIs('admin.reservations.*')">
                                Reservations
                            </x-nav-link>
                        @endif
                        
                        <x-nav-link :href="route('admin.orders.index')" :active="request()->routeIs('admin.orders.*')">
                            Orders
                        </x-nav-link>
                        <x-nav-link :href="route('admin.expenses.index')" :active="request()->routeIs('admin.expenses.*')">
                            Expenses
                        </x-nav-link>
                        <x-nav-link :href="route('admin.reports.index')" :active="request()->routeIs('admin.reports.*')">
                            Reports
                        </x-nav-link>
                    @endrole

                    {{-- Cashier Navigation --}}
                    @role('cashier')
                        <x-nav-link :href="route('cashier.pos')" :active="request()->routeIs('cashier.pos')">
                            <span class="myanmar-text">ကောင်တာ</span>
                        </x-nav-link>
                        
                        @if(is_module_active('Restaurant'))
                            <x-nav-link :href="route('cashier.tables.index')" :active="request()->routeIs('cashier.tables.*')">
                                <span class="myanmar-text">စားပွဲများ</span>
                            </x-nav-link>
                        @endif
                        
                        <x-nav-link :href="route('cashier.orders.index')" :active="request()->routeIs('cashier.orders.*')">
                            <span class="myanmar-text">အော်ဒါများ</span>
                        </x-nav-link>
                    @endrole

                    {{-- Waiter Navigation (Restaurant Only) --}}
                    @role('waiter')
                        @if(is_module_active('Restaurant'))
                            <x-nav-link :href="route('waiter.tables.index')" :active="request()->routeIs('waiter.tables.*')">
                                <span class="myanmar-text">စားပွဲများ</span>
                            </x-nav-link>
                            <x-nav-link :href="route('waiter.orders.index')" :active="request()->routeIs('waiter.orders.*')">
                                <span class="myanmar-text">အော်ဒါများ</span>
                            </x-nav-link>
                        @endif
                    @endrole
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            @role('admin')
                <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                    Dashboard
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.categories.index')" :active="request()->routeIs('admin.categories.*')">
                    Categories
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.items.index')" :active="request()->routeIs('admin.items.*')">
                    Items
                </x-responsive-nav-link>
                
                @if(is_module_active('Restaurant'))
                    <x-responsive-nav-link :href="route('admin.tables.index')" :active="request()->routeIs('admin.tables.*')">
                        Tables
                    </x-responsive-nav-link>
                @endif
                
                <x-responsive-nav-link :href="route('admin.orders.index')" :active="request()->routeIs('admin.orders.*')">
                    Orders
                </x-responsive-nav-link>
            @endrole

            @role('cashier')
                <x-responsive-nav-link :href="route('cashier.pos')" :active="request()->routeIs('cashier.pos')">
                    <span class="myanmar-text">ကောင်တာ</span>
                </x-responsive-nav-link>
                
                @if(is_module_active('Restaurant'))
                    <x-responsive-nav-link :href="route('cashier.tables.index')" :active="request()->routeIs('cashier.tables.*')">
                        <span class="myanmar-text">စားပွဲများ</span>
                    </x-responsive-nav-link>
                @endif
                
                <x-responsive-nav-link :href="route('cashier.orders.index')" :active="request()->routeIs('cashier.orders.*')">
                    <span class="myanmar-text">အော်ဒါများ</span>
                </x-responsive-nav-link>
            @endrole
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
