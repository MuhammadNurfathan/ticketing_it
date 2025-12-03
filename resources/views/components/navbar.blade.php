<nav
    aria-label="secondary"
    x-data="{ open: false }"
    class="sticky top-0 z-10 flex items-center justify-between px-4 py-3 sm:px-6 transition-transform duration-500 bg-white dark:bg-dark-eval-1 border-b dark:border-gray-700"
    :class="{
        '-translate-y-full': scrollingDown,
        'translate-y-0': scrollingUp,
    }">

    <!-- Left Section -->
    <div class="flex items-center gap-3">
        <!-- Hamburger Button (Mobile Only) -->
        <x-button
            type="button"
            class="lg:hidden"
            icon-only
            variant="secondary"
            sr-text="Open main menu"
            x-on:click="toggleSidebar()"
        >
            <x-heroicon-o-menu
                aria-hidden="true"
                class="w-6 h-6"
            />
        </x-button>

        <!-- Logo & App Name (Mobile Only) -->
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2 lg:hidden">
            <span class="text-base font-bold text-gray-800 dark:text-white">TICKETING IT</span>
        </a>
    </div>

    <!-- Right Section -->
    <div class="flex items-center gap-2 sm:gap-3">
        <!-- Dark Mode Toggle -->
        <x-button
            type="button"
            icon-only
            variant="secondary"
            sr-text="Toggle dark mode"
            x-on:click="toggleTheme"
        >
            <x-heroicon-o-moon
                x-show="!isDarkMode"
                aria-hidden="true"
                class="w-5 h-5"
            />

            <x-heroicon-o-sun
                x-show="isDarkMode"
                aria-hidden="true"
                class="w-5 h-5"
            />
        </x-button>

    

        <!-- User Dropdown -->
        <x-dropdown align="right" width="48">
            <x-slot name="trigger">
                <button
                    class="flex items-center p-1.5 text-sm font-medium text-gray-500 rounded-md transition duration-150 ease-in-out hover:text-gray-700 focus:outline-none focus:ring focus:ring-purple-500 focus:ring-offset-1 focus:ring-offset-white dark:focus:ring-offset-dark-eval-1 dark:text-gray-400 dark:hover:text-gray-200"
                >
                    <!-- Avatar -->
                    <img 
                        src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}&background=3b82f6&color=fff" 
                        alt="{{ Auth::user()->name }}" 
                        class="w-7 h-7 rounded-full mr-2"
                    >

                    <!-- Name (Hidden on mobile) -->
                    <div class="hidden md:block">{{ Auth::user()->name }}</div>

                    <!-- Chevron -->
                    <div class="ml-1">
                        <svg
                            class="w-4 h-4 fill-current"
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 20 20"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                clip-rule="evenodd"
                            />
                        </svg>
                    </div>
                </button>
            </x-slot>

            <x-slot name="content">
                <!-- Profile -->
                <x-dropdown-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-dropdown-link>  

                <div class="border-t dark:border-gray-700"></div>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-dropdown-link
                        :href="route('logout')"
                        onclick="event.preventDefault(); this.closest('form').submit();"
                    >
                        {{ __('Log Out') }}
                    </x-dropdown-link>
                </form>
            </x-slot>
        </x-dropdown>
    </div>
</nav>