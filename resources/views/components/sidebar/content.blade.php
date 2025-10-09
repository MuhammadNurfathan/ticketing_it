<x-perfect-scrollbar as="nav" aria-label="main" class="flex flex-col flex-1 gap-4 px-3">

    {{-- Dashboard Ticket --}}
    <x-sidebar.link title="Dashboard Ticket" href="{{ route('dashboard') }}" :isActive="request()->routeIs('dashboard')">
        <x-slot name="icon">
            <x-icons.dashboard class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
        </x-slot>
    </x-sidebar.link>

    {{-- Dashboard Project --}}
    <x-sidebar.link title="Dashboard Project" href="{{ route('dashboard') }}" :isActive="request()->routeIs('dashboard')">
        <x-slot name="icon">
            <x-icons.dashboard class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
        </x-slot>
    </x-sidebar.link>

    {{-- Master Data Dropdown --}}
<x-sidebar.dropdown 
    title="Master Data" 
    :active="request()->routeIs('locations.*|departments.*|users.*')" 
    :open="request()->routeIs('locations.*') || request()->routeIs('departments.*') || request()->routeIs('users.*') ? true : false"


>
    <x-slot name="icon">
        <x-icons.dashboard class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
    </x-slot>

    <x-sidebar.link title="Location" href="{{ route('locations.index') }}" :isActive="request()->routeIs('locations.*')" />
    <x-sidebar.link title="Department" href="{{ route('departments.index') }}" :isActive="request()->routeIs('departments.*')" />
    <x-sidebar.link title="Users" href="{{ route('users.index') }}" :isActive="request()->routeIs('users.*')" />
</x-sidebar.dropdown>

</x-perfect-scrollbar>
