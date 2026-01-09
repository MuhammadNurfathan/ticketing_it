<x-perfect-scrollbar as="nav" aria-label="main" class="flex flex-col flex-1 gap-4 px-3">

    {{-- DASHBOARD --}}
    <x-sidebar.link title="Dashboard" href="{{ route('dashboard') }}" :isActive="request()->routeIs('dashboard')">
        <x-slot name="icon">
            <x-heroicon-o-home class="w-6 h-6" />
        </x-slot>
    </x-sidebar.link>

    {{-- MY TICKETS --}}
    <x-sidebar.link title="My Tickets" href="{{ route('DashboardTicketsUser.indexUser') }}" :isActive="request()->routeIs('DashboardTicketsUser.indexUser')">
        <x-slot name="icon">
            <x-heroicon-o-ticket class="w-6 h-6" />
        </x-slot>
    </x-sidebar.link>

    {{-- ADMIN / IT SUPPORT --}}
    @if (in_array(auth()->user()->role_id, [1, 2]))
        <x-sidebar.link title="Feedbacks" href="{{ route('feedback') }}" :isActive="request()->routeIs('feedback')">
            <x-slot name="icon">
                <x-heroicon-o-chat-alt-2 class="w-6 h-6" />
            </x-slot>
        </x-sidebar.link>

        {{-- PROJECT MONITORING --}}
        <x-sidebar.dropdown title="Project Monitoring" :active="request()->routeIs('reports.ProjectMonitoring', 'project.*')" :open="request()->routeIs('reports.ProjectMonitoring', 'project.*')">

            <x-slot name="icon">
                <x-heroicon-o-chart-bar class="w-6 h-6" />
            </x-slot>

            <x-sidebar.link title="Project Overview" href="{{ route('project.index') }}" :isActive="request()->routeIs('project.*')" />

            <x-sidebar.link title="Monitoring Project Queue" href="{{ route('reports.ProjectMonitoring') }}"
                :isActive="request()->routeIs('reports.ProjectMonitoring')" />
            <x-sidebar.link title="Project Tracking" href="{{ route('reports.ProjectTracking') }}" :isActive="request()->routeIs('reports.ProjectTracking')" />

        </x-sidebar.dropdown>

        {{-- TICKETS MONITORING --}}
        <x-sidebar.dropdown title="Tickets Monitoring" :active="request()->routeIs(
            'DashboardTicketsAdmin.index',
            'reports.ExcecutiveTicketsInsight',
            'reports.TeamPerformanceTracker',
        )" :open="request()->routeIs(
            'DashboardTicketsAdmin.index',
            'reports.ExcecutiveTicketsInsight',
            'reports.TeamPerformanceTracker',
        )">

            <x-slot name="icon">
                <x-heroicon-o-collection class="w-6 h-6" />
            </x-slot>

            <x-sidebar.link title="Tickets Overview" href="{{ route('DashboardTicketsAdmin.index') }}"
                :isActive="request()->routeIs('DashboardTicketsAdmin.index')" />

            <x-sidebar.link title="Executive Tickets Insight" href="{{ route('reports.ExcecutiveTicketsInsight') }}"
                :isActive="request()->routeIs('reports.ExcecutiveTicketsInsight')" />

            <x-sidebar.link title="Team Performance Tracker" href="{{ route('reports.TeamPerformanceTracker') }}"
                :isActive="request()->routeIs('reports.TeamPerformanceTracker')" />
        </x-sidebar.dropdown>

        {{-- MASTER DATA --}}
        <x-sidebar.dropdown title="Master Data" :active="request()->routeIs(
            'locations.*',
            'departments.*',
            'users.*',
            'roles.*',
            'problem_categories.*',
            'status.*',
            'priority.*',
            'assets.*',
        )" :open="request()->routeIs(
            'locations.*',
            'departments.*',
            'users.*',
            'roles.*',
            'problem_categories.*',
            'status.*',
            'priority.*',
            'assets.*',
        )">

            <x-slot name="icon">
                <x-heroicon-o-cog class="w-6 h-6" />
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

</x-perfect-scrollbar>
