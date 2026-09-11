<nav x-data="{ open: false }">
    <!-- Logo -->
    <a href="{{ route('dashboard') }}" class="logo">
        Snap<span>Book</span>
    </a>

    <!-- Desktop Navigation Links -->
    <div class="nav-center">
        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
            {{ __('Dashboard') }}
        </x-nav-link>

        <x-nav-link :href="route('photographers.index')" :active="request()->routeIs('photographers.index')">
            {{ __('Find Photographers') }}
        </x-nav-link>

        @if (auth()->user()->role === 'admin')
            <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.*')">
                {{ __('Admin') }}
            </x-nav-link>
        @endif
    </div>

    <!-- Settings Dropdown (desktop) -->
    <div class="nav-right">
        <x-dropdown align="right" width="48">
            <x-slot name="trigger">
                <button class="inline-flex items-center gap-2 border border-transparent text-[13px] leading-5 text-[var(--mist)] hover:text-[var(--white)] focus:outline-none transition ease-in-out duration-150">
                    <div>{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</div>

                    <i class="ti ti-chevron-down text-[15px]"></i>
                </button>
            </x-slot>

            <x-slot name="content">
                <x-dropdown-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-dropdown-link>

                <x-dropdown-link :href="route('bookings.index')">
                    {{ __('My Bookings') }}
                </x-dropdown-link>

                @if (auth()->user() && auth()->user()->role === 'photographer')
                    <x-dropdown-link :href="route('photographer-profile.create')">
                        {{ __('My Photographer Profile') }}
                    </x-dropdown-link>
                    <x-dropdown-link :href="route('services.index')">
                        {{ __('My Services') }}
                    </x-dropdown-link>
                    <x-dropdown-link :href="route('portfolio.index')">
                        {{ __('My Portfolio') }}
                    </x-dropdown-link>
                    <x-dropdown-link :href="route('availabilities.index')">
                        {{ __('My Availability') }}
                    </x-dropdown-link>
                @endif

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-dropdown-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-dropdown-link>
                </form>
            </x-slot>
        </x-dropdown>
    </div>

    <!-- Hamburger (mobile) -->
    <div class="flex items-center sm:hidden">
        <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-[var(--mist)] hover:text-[var(--white)] hover:bg-[var(--w10)] focus:outline-none transition duration-150 ease-in-out">
            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <!-- Responsive Navigation Menu (mobile) -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden absolute top-[54px] inset-x-0 z-[90] bg-[#070910] border-b border-[var(--bd)]">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('photographers.index')" :active="request()->routeIs('photographers.index')">
                {{ __('Find Photographers') }}
            </x-responsive-nav-link>

@if (auth()->user() && auth()->user()->role === 'admin')
                <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.*')">
                    {{ __('Admin') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-[var(--bd2)]">
            <div class="px-4">
                <div class="font-medium text-sm text-[var(--white)]">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</div>
                <div class="font-medium text-xs text-[var(--mist)]">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('bookings.index')">
                    {{ __('My Bookings') }}
                </x-responsive-nav-link>

                @if (auth()->user() && auth()->user()->role === 'photographer')
                    <x-responsive-nav-link :href="route('photographer-profile.create')">
                        {{ __('My Photographer Profile') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('services.index')">
                        {{ __('My Services') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('portfolio.index')">
                        {{ __('My Portfolio') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('availabilities.index')">
                        {{ __('My Availability') }}
                    </x-responsive-nav-link>
                @endif

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>