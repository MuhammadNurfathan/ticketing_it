<x-perfect-scrollbar as="nav" aria-label="main" class="flex flex-col flex-1 gap-4 px-3">

    {{-- Dashboard --}}
    <x-sidebar.link title="Dashboard" href="{{ route('dashboard') }}" :isActive="request()->routeIs('dashboard')">
        <x-slot name="icon">
            <x-icons.home class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
        </x-slot>
    </x-sidebar.link>

    {{-- My Tickets --}}
    <x-sidebar.link title="My Tickets" href="{{ route('DashboardTicketsUser.indexUser') }}" :isActive="request()->routeIs('DashboardTicketsUser.indexUser')">
        <x-slot name="icon">
            <x-icons.ticket class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
        </x-slot>
    </x-sidebar.link>

    {{-- Feedbacks --}}
    <x-sidebar.link title="Feedbacks" href="{{ route('feedback') }}" :isActive="request()->routeIs('feedback')">
        <x-slot name="icon">
            <x-icons.message-square class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
        </x-slot>
    </x-sidebar.link>

    {{-- All Tickets --}}
    <x-sidebar.link title="All Tickets" href="{{ route('DashboardTicketsAdmin.index') }}" :isActive="request()->routeIs('DashboardTicketsAdmin.index')">
        <x-slot name="icon">
            <x-icons.list class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
        </x-slot>
    </x-sidebar.link>

    @if (auth()->user()->role_id == 2)
        {{-- All Project --}}
        <x-sidebar.link title="All Project" href="{{ route('project.index') }}" :isActive="request()->routeIs('project.*')">
            <x-slot name="icon">
                <x-icons.briefcase class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
            </x-slot>
        </x-sidebar.link>

        {{-- Master Data --}}
        <x-sidebar.dropdown title="Master Data"
            :active="request()->routeIs('locations.*','departments.*','users.*','roles.*','problem_categories.*','status.*','priority.*','assets.*')"
            :open="request()->routeIs('locations.*','departments.*','users.*','roles.*','problem_categories.*','status.*','priority.*','assets.*')">
            <x-slot name="icon">
                <x-icons.database class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
            </x-slot>

            <x-sidebar.link title="Location" href="{{ route('locations.index') }}" :isActive="request()->routeIs('locations.*')">
                <x-slot name="icon"><x-icons.map-pin class="w-4 h-4" /></x-slot>
            </x-sidebar.link>
            <x-sidebar.link title="Department" href="{{ route('departments.index') }}" :isActive="request()->routeIs('departments.*')">
                <x-slot name="icon"><x-icons.building class="w-4 h-4" /></x-slot>
            </x-sidebar.link>
            <x-sidebar.link title="Users" href="{{ route('users.index') }}" :isActive="request()->routeIs('users.*')">
                <x-slot name="icon"><x-icons.users class="w-4 h-4" /></x-slot>
            </x-sidebar.link>
            <x-sidebar.link title="Roles" href="{{ route('roles.index') }}" :isActive="request()->routeIs('roles.*')">
                <x-slot name="icon"><x-icons.badge-check class="w-4 h-4" /></x-slot>
            </x-sidebar.link>
            <x-sidebar.link title="Problem Category" href="{{ route('problem_categories.index') }}" :isActive="request()->routeIs('problem_categories.*')">
                <x-slot name="icon"><x-icons.alert-triangle class="w-4 h-4" /></x-slot>
            </x-sidebar.link>
            <x-sidebar.link title="Status" href="{{ route('status.index') }}" :isActive="request()->routeIs('status.*')">
                <x-slot name="icon"><x-icons.check-circle class="w-4 h-4" /></x-slot>
            </x-sidebar.link>
            <x-sidebar.link title="Priority" href="{{ route('priority.index') }}" :isActive="request()->routeIs('priority.*')">
                <x-slot name="icon"><x-icons.arrow-up-circle class="w-4 h-4" /></x-slot>
            </x-sidebar.link>
            <x-sidebar.link title="Assets" href="{{ route('assets.index') }}" :isActive="request()->routeIs('assets.*')">
                <x-slot name="icon"><x-icons.hard-drive class="w-4 h-4" /></x-slot>
            </x-sidebar.link>
        </x-sidebar.dropdown>
    @endif

    @if (auth()->user()->role_id == 1)
        {{-- Master Data untuk Role Admin --}}
        <x-sidebar.dropdown title="Master Data"
            :active="request()->routeIs('locations.*','departments.*','users.*','roles.*','problem_categories.*','status.*','priority.*','assets.*')"
            :open="request()->routeIs('locations.*','departments.*','users.*','roles.*','problem_categories.*','status.*','priority.*','assets.*')">
            <x-slot name="icon">
                <x-icons.database class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
            </x-slot>

            <x-sidebar.link title="Location" href="{{ route('locations.index') }}" :isActive="request()->routeIs('locations.*')">
                <x-slot name="icon"><x-icons.map-pin class="w-4 h-4" /></x-slot>
            </x-sidebar.link>
            <x-sidebar.link title="Department" href="{{ route('departments.index') }}" :isActive="request()->routeIs('departments.*')">
                <x-slot name="icon"><x-icons.building class="w-4 h-4" /></x-slot>
            </x-sidebar.link>
            <x-sidebar.link title="Users" href="{{ route('users.index') }}" :isActive="request()->routeIs('users.*')">
                <x-slot name="icon"><x-icons.users class="w-4 h-4" /></x-slot>
            </x-sidebar.link>
            <x-sidebar.link title="Roles" href="{{ route('roles.index') }}" :isActive="request()->routeIs('roles.*')">
                <x-slot name="icon"><x-icons.badge-check class="w-4 h-4" /></x-slot>
            </x-sidebar.link>
            <x-sidebar.link title="Problem Category" href="{{ route('problem_categories.index') }}" :isActive="request()->routeIs('problem_categories.*')">
                <x-slot name="icon"><x-icons.alert-triangle class="w-4 h-4" /></x-slot>
            </x-sidebar.link>
            <x-sidebar.link title="Status" href="{{ route('status.index') }}" :isActive="request()->routeIs('status.*')">
                <x-slot name="icon"><x-icons.check-circle class="w-4 h-4" /></x-slot>
            </x-sidebar.link>
            <x-sidebar.link title="Priority" href="{{ route('priority.index') }}" :isActive="request()->routeIs('priority.*')">
                <x-slot name="icon"><x-icons.arrow-up-circle class="w-4 h-4" /></x-slot>
            </x-sidebar.link>
            <x-sidebar.link title="Assets" href="{{ route('assets.index') }}" :isActive="request()->routeIs('assets.*')">
                <x-slot name="icon"><x-icons.hard-drive class="w-4 h-4" /></x-slot>
            </x-sidebar.link>
        </x-sidebar.dropdown>
    @endif

</x-perfect-scrollbar>
