<div class="flex items-center justify-between flex-shrink-0 px-3">
    <!-- Logo -->
    <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2">
        <!-- Logo kecil (collapse) -->
        <template x-if="!(isSidebarOpen || isSidebarHovered)">
            <div x-transition.opacity.duration.300ms>
                <x-application-logo aria-hidden="true" class="w-10 h-auto transition-all duration-300" />
            </div>
        </template>

        <!-- Logo besar (expand / hover) -->
        <template x-if="isSidebarOpen || isSidebarHovered">
            <div x-transition.opacity.duration.300ms>
                <x-application-logo-2 aria-hidden="true" class="w-40 h-auto transition-all duration-300" />
            </div>
        </template>
    </a>

    <!-- Toggle button - HANYA untuk Desktop & Mobile Close -->
    <x-button
        type="button"
        iconOnly
        srText="Toggle sidebar"
        variant="secondary"
        x-show="isSidebarOpen || isSidebarHovered"
        @click="toggleSidebar()"
    >
        <!-- Desktop: Fold icons -->
        <x-icons.menu-fold-right x-show="!isSidebarOpen" aria-hidden="true" class="hidden w-6 h-6 lg:block" />
        <x-icons.menu-fold-left x-show="isSidebarOpen" aria-hidden="true" class="hidden w-6 h-6 lg:block" />
        
        <!-- Mobile: X icon untuk close -->
        <x-heroicon-o-x aria-hidden="true" class="w-6 h-6 lg:hidden" />
    </x-button>
</div>
