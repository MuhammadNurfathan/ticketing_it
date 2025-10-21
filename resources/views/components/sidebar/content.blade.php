<x-perfect-scrollbar as="nav" aria-label="main" class="flex flex-col flex-1 gap-4 px-3">
    {{-- Dashboard --}}
    <x-sidebar.link title="Dashboard" href="{{ route('dashboard') }}" :isActive="request()->routeIs('dashboard')">
        <x-slot name="icon">
            <x-icons.dashboard class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
        </x-slot>
    </x-sidebar.link>

    {{-- Dashboard Ticket --}}
    <x-sidebar.link title="All Tickets" href="{{ route('DashboardTicketsAdmin.index') }}" :isActive="request()->routeIs('DashboardTicketsAdmin.index')">
        <x-slot name="icon">
            <x-icons.dashboard class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
        </x-slot>
    </x-sidebar.link>

    @if (auth()->user()->role_id == 2)
        <x-sidebar.link title="All Project" href="{{ route('project.index') }}" :isActive="request()->routeIs('project.*')">
            <x-slot name="icon">
                <x-icons.dashboard class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
            </x-slot>
        </x-sidebar.link>

        {{-- <x-sidebar.link title="Dashboard Ticket" href="{{ route('dashboard') }}" :isActive="request()->routeIs('dashboard')">
            <x-slot name="icon">
                <x-icons.dashboard class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
            </x-slot>
        </x-sidebar.link> --}}

        {{-- Master Data Dropdown --}}
        <x-sidebar.dropdown title="Master Data" :active="request()->routeIs('locations.*') ||
            request()->routeIs('departments.*') ||
            request()->routeIs('users.*') ||
            request()->routeIs('roles.*') ||
            request()->routeIs('problem_categories.*') ||
            request()->routeIs('status.*') ||
            request()->routeIs('priority.*') ||
            request()->routeIs('assets.*')" :open="request()->routeIs('locations.*') ||
            request()->routeIs('departments.*') ||
            request()->routeIs('users.*') ||
            request()->routeIs('roles.*') ||
            request()->routeIs('problem_categories.*') ||
            request()->routeIs('status.*') ||
            request()->routeIs('priority.*') ||
            request()->routeIs('assets.*')">
            <x-slot name="icon">
                <x-icons.dashboard class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
            </x-slot>

            <x-sidebar.link title="Location" href="{{ route('locations.index') }}" :isActive="request()->routeIs('locations.*')" />
            <x-sidebar.link title="Department" href="{{ route('departments.index') }}" :isActive="request()->routeIs('departments.*')" />
            <x-sidebar.link title="Users" href="{{ route('users.index') }}" :isActive="request()->routeIs('users.*')" />
            <x-sidebar.link title="Roles" href="{{ route('roles.index') }}" :isActive="request()->routeIs('roles.*')" />
            <x-sidebar.link title="Problem Category" href="{{ route('problem_categories.index') }}"
                :isActive="request()->routeIs('problem_categories.*')" />
            <x-sidebar.link title="Status" href="{{ route('status.index') }}" :isActive="request()->routeIs('status.*')" />
            <x-sidebar.link title="Priority" href="{{ route('priority.index') }}" :isActive="request()->routeIs('priority.*')" />
            <x-sidebar.link title="Assets" href="{{ route('assets.index') }}" :isActive="request()->routeIs('assets.*')" />
        </x-sidebar.dropdown>
    @endif

    @if (auth()->user()->role_id == 1)
        <x-sidebar.dropdown title="Master Data" :active="request()->routeIs('locations.*') ||
            request()->routeIs('departments.*') ||
            request()->routeIs('users.*') ||
            request()->routeIs('roles.*') ||
            request()->routeIs('problem_categories.*') ||
            request()->routeIs('status.*') ||
            request()->routeIs('priority.*') ||
            request()->routeIs('assets.*')" :open="request()->routeIs('locations.*') ||
            request()->routeIs('departments.*') ||
            request()->routeIs('users.*') ||
            request()->routeIs('roles.*') ||
            request()->routeIs('problem_categories.*') ||
            request()->routeIs('status.*') ||
            request()->routeIs('priority.*') ||
            request()->routeIs('assets.*')">
            <x-slot name="icon">
                <x-icons.dashboard class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
            </x-slot>

            <x-sidebar.link title="Location" href="{{ route('locations.index') }}" :isActive="request()->routeIs('locations.*')" />
            <x-sidebar.link title="Department" href="{{ route('departments.index') }}" :isActive="request()->routeIs('departments.*')" />
            <x-sidebar.link title="Users" href="{{ route('users.index') }}" :isActive="request()->routeIs('users.*')" />
            <x-sidebar.link title="Roles" href="{{ route('roles.index') }}" :isActive="request()->routeIs('roles.*')" />
            <x-sidebar.link title="Problem Category" href="{{ route('problem_categories.index') }}"
                :isActive="request()->routeIs('problem_categories.*')" />
            <x-sidebar.link title="Status" href="{{ route('status.index') }}" :isActive="request()->routeIs('status.*')" />
            <x-sidebar.link title="Priority" href="{{ route('priority.index') }}" :isActive="request()->routeIs('priority.*')" />
            <x-sidebar.link title="Assets" href="{{ route('assets.index') }}" :isActive="request()->routeIs('assets.*')" />
        </x-sidebar.dropdown>
    @endif


        {{-- Dashboard Ticket --}}
        <x-sidebar.link title="My Tickets" href="{{ route('DashboardTicketsUser.indexUser') }}" :isActive="request()->routeIs('DashboardTicketsUser.indexUser')">
            <x-slot name="icon">
                <x-icons.dashboard class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
            </x-slot>
        </x-sidebar.link>


</x-perfect-scrollbar>
