@php
    $userRole = auth()->user()->role_id;

    $isAdmin   = $userRole == 1;
    $isManager = $userRole == 2;

    $reportRoutes = [
        'reports.ProjectMonitoring',
        'reports.ProjectTracking',
        'reports.ExcecutiveTicketsInsight',
        'reports.TeamPerformanceTracker',
    ];

    $masterRoutes  = ['locations.*','departments.*','users.*','roles.*','categories.*','status.*','priority.*','assets.*'];

    $reportActive = request()->routeIs(...$reportRoutes);
    $masterActive = request()->routeIs(...$masterRoutes);
@endphp

<x-perfect-scrollbar as="nav" aria-label="main" class="flex flex-col flex-1 px-3 py-2 gap-2">

    {{-- ================= MAIN (SEMUA ROLE) ================= --}}
    <div class="space-y-1">
        <x-sidebar.link title="Dashboard" href="{{ route('dashboard') }}"
            :isActive="request()->routeIs('dashboard')">
            <x-slot name="icon">
                <x-heroicon-o-home class="w-6 h-6" />
            </x-slot>
        </x-sidebar.link>

        <x-sidebar.link title="My Tickets" href="{{ route('DashboardTicketsUser.index') }}"
            :isActive="request()->routeIs('DashboardTicketsUser.*')">
            <x-slot name="icon">
                <x-heroicon-o-ticket class="w-6 h-6" />
            </x-slot>
        </x-sidebar.link>
    </div>

    {{-- ================= MANAGER ONLY ================= --}}
    @if ($isManager)
        <div class="my-2 border-t border-light-eval-3 dark:border-dark-eval-2"></div>

        <x-sidebar.link title="Feedbacks" href="{{ route('feedback') }}"
            :isActive="request()->routeIs('feedback')">
            <x-slot name="icon">
                <x-heroicon-o-ticket class="w-6 h-6" />
            </x-slot>
        </x-sidebar.link>

        <x-sidebar.dropdown title="Reports" :active="$reportActive" :open="$reportActive">
            <x-slot name="icon">
                <x-heroicon-o-chart-bar class="w-6 h-6" />
            </x-slot>

            <x-sidebar.link title="Monitoring Project Queue"
                href="{{ route('reports.ProjectMonitoring') }}"
                :isActive="request()->routeIs('reports.ProjectMonitoring')" />

            <x-sidebar.link title="Project Tracking"
                href="{{ route('reports.ProjectTracking') }}"
                :isActive="request()->routeIs('reports.ProjectTracking')" />

            <x-sidebar.link title="Executive Tickets Insight"
                href="{{ route('reports.ExcecutiveTicketsInsight') }}"
                :isActive="request()->routeIs('reports.ExcecutiveTicketsInsight')" />

            <x-sidebar.link title="Team Performance Tracker"
                href="{{ route('reports.TeamPerformanceTracker') }}"
                :isActive="request()->routeIs('reports.TeamPerformanceTracker')" />
        </x-sidebar.dropdown>
    @endif

    {{-- ================= ADMIN ONLY ================= --}}
    @if ($isAdmin)
        <div class="my-2 border-t border-light-eval-3 dark:border-dark-eval-2"></div>

        <x-sidebar.link title="Project Overview"
            href="{{ route('project.index') }}"
            :isActive="request()->routeIs('project.*')">
            <x-slot name="icon">
                <x-heroicon-o-chart-bar class="w-6 h-6" />
            </x-slot>
        </x-sidebar.link>

        <x-sidebar.link title="Ticket Overview"
            href="{{ route('DashboardTicketsAdmin.index') }}"
            :isActive="request()->routeIs('DashboardTicketsAdmin.*')">
            <x-slot name="icon">
                <x-heroicon-o-collection class="w-6 h-6" />
            </x-slot>
        </x-sidebar.link>
         <x-sidebar.link title="Feedbacks" href="{{ route('feedback') }}"
            :isActive="request()->routeIs('feedback')">
            <x-slot name="icon">
                <x-heroicon-o-ticket class="w-6 h-6" />
            </x-slot>
        </x-sidebar.link>

        <x-sidebar.dropdown title="Master Data"
            :active="$masterActive"
            :open="$masterActive">
            <x-slot name="icon">
                <x-heroicon-o-cog class="w-6 h-6" />
            </x-slot>

            <x-sidebar.link title="Location" href="{{ route('locations.index') }}" :isActive="request()->routeIs('locations.*')" />
            <x-sidebar.link title="Department" href="{{ route('departments.index') }}" :isActive="request()->routeIs('departments.*')" />
            <x-sidebar.link title="Users" href="{{ route('users.index') }}" :isActive="request()->routeIs('users.*')" />
            <x-sidebar.link title="Roles" href="{{ route('roles.index') }}" :isActive="request()->routeIs('roles.*')" />
            <x-sidebar.link title="Problem Category" href="{{ route('categories.index') }}" :isActive="request()->routeIs('categories.*')" />
            <x-sidebar.link title="Status" href="{{ route('status.index') }}" :isActive="request()->routeIs('status.*')" />
            <x-sidebar.link title="Priority" href="{{ route('priority.index') }}" :isActive="request()->routeIs('priority.*')" />
            <x-sidebar.link title="Assets" href="{{ route('assets.index') }}" :isActive="request()->routeIs('assets.*')" />
        </x-sidebar.dropdown>
    @endif

</x-perfect-scrollbar>