<x-perfect-scrollbar as="nav" aria-label="main" class="flex flex-col flex-1 gap-4 px-3">

    {{-- DASHBOARD --}}
    <x-sidebar.link title="Dashboard" href="{{ route('dashboard') }}" :isActive="request()->routeIs('dashboard')">
        <x-slot name="icon">
            <x-heroicon-o-home class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
        </x-slot>
    </x-sidebar.link>

    {{-- MY TICKETS --}}
    <x-sidebar.link title="My Tickets" href="{{ route('DashboardTicketsUser.indexUser') }}" :isActive="request()->routeIs('DashboardTicketsUser.indexUser')">
        <x-slot name="icon">
            <x-heroicon-o-ticket class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
        </x-slot>
    </x-sidebar.link>

    {{-- ADMIN / IT SUPPORT --}}
    @if (auth()->user()->role_id == 2)
        {{-- PROJECT MONITORING --}}
        <x-sidebar.link title="Project Monitoring" href="{{ route('reports.ProjectMonitoring') }}" :isActive="request()->routeIs('reports.ProjectMonitoring')">
            <x-slot name="icon">
                <x-heroicon-o-chart-bar class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
            </x-slot>
        </x-sidebar.link>

        {{-- PROJECT OVERVIEW --}}
        <x-sidebar.link title="Project Overview" href="{{ route('project.index') }}" :isActive="request()->routeIs('project.*')">
            <x-slot name="icon">
                <x-heroicon-o-folder-open class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
            </x-slot>
        </x-sidebar.link>

        {{-- FEEDBACKS --}}
        <x-sidebar.link title="Feedbacks" href="{{ route('feedback') }}" :isActive="request()->routeIs('feedback')">
            <x-slot name="icon">
                <x-heroicon-o-chat-alt-2 class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
            </x-slot>
        </x-sidebar.link>

        {{-- TICKETS MONITORING --}}
        <x-sidebar.dropdown title="Tickets Monitoring" :active="request()->routeIs(
            'reports.ExcecutiveTicketsInsight',
            'reports.TeamPerformanceTracker',
            'DashboardTicketsAdmin.index',
        )" :open="request()->routeIs(
            'reports.ExcecutiveTicketsInsight',
            'reports.TeamPerformanceTracker',
            'DashboardTicketsAdmin.index',
        )">
            <x-slot name="icon">
                <x-heroicon-o-collection class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
            </x-slot>

            <x-sidebar.link title="Tickets Overview" href="{{ route('DashboardTicketsAdmin.index') }}"
                :isActive="request()->routeIs('DashboardTicketsAdmin.index')" />
            <x-sidebar.link title="Executive Tickets Insight" href="{{ route('reports.ExcecutiveTicketsInsight') }}"
                :isActive="request()->routeIs('reports.ExcecutiveTicketsInsight')" />
            <x-sidebar.link title="Team Performance Tracker" href="{{ route('reports.TeamPerformanceTracker') }}"
                :isActive="request()->routeIs('reports.TeamPerformanceTracker')" />
        </x-sidebar.dropdown>

        {{-- MASTER DATA --}}
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
                <x-heroicon-o-cog class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
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

    {{-- USER --}}
    @if (auth()->user()->role_id == 1)
        <x-sidebar.link title="Tickets Overview" href="{{ route('DashboardTicketsAdmin.index') }}" :isActive="request()->routeIs('DashboardTicketsAdmin.index')">
            <x-slot name="icon">
                <x-heroicon-o-collection class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
            </x-slot>
        </x-sidebar.link>

        <x-sidebar.link title="Feedbacks" href="{{ route('feedback') }}" :isActive="request()->routeIs('feedback')">
            <x-slot name="icon">
                <x-heroicon-o-chat-alt-2 class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
            </x-slot>
        </x-sidebar.link>

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
                <x-heroicon-o-cog class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
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
