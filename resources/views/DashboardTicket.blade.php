@php use Illuminate\Support\Str; @endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Dashboard Ticketing') }}
            </h2>
            <div class="flex gap-2">
                <select class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option>Month</option>
                    <option>January</option>
                    <option>February</option>
                    <option>March</option>
                </select>
                <input type="date"
                    class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    value="2024-10-10">
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- ===== Statistik ===== --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white shadow-sm rounded-lg p-6 text-center">
                    <div class="text-4xl font-bold text-gray-800">{{ $waitingTicketsCount }}</div>
                    <div class="text-sm text-gray-600 mt-2">Waiting Tickets</div>
                </div>
                <div class="bg-white shadow-sm rounded-lg p-6 text-center">
                    <div class="text-4xl font-bold text-gray-800">{{ $inProgressTicketsCount }}</div>
                    <div class="text-sm text-gray-600 mt-2">Ticket In Progress</div>
                </div>
                <div class="bg-white shadow-sm rounded-lg p-6 text-center">
                    <div class="text-4xl font-bold text-gray-800">{{ $completedThisMonthCount }}</div>
                    <div class="text-sm text-gray-600 mt-2">Tickets Closed This Month</div>
                </div>
            </div>

            <!-- Tickets In Progress Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Tickets In Progress</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Divisi
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kategori
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Masalah
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal
                                        Req</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Assignee
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Start
                                        Date</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Start
                                        Date</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">End Date
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($ticketsInProgress as $ticket)
                                    <tr>
                                        <td class="px-4 py-3 text-sm text-gray-900">{{ $ticket->ticket_code }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-900">
                                            {{ $ticket->user->department->department_name ?? '-' }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-900">
                                            {{ $ticket->user->name ?? '-' }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-900">
                                            {{ $ticket->category->category_name ?? '-' }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-900">
                                            {{ Str::limit($ticket->problem, 40) }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-900">
                                            {{ $ticket->request_date ? $ticket->request_date->format('d M Y') : '-' }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-900">
                                            {{ $ticket->support->name ?? 'Unassigned' }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-900">
                                            {{ $ticket->start_date ? $ticket->start_date->format('d M Y H:i') : '-' }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-900">
                                            {{ $ticket->end_date ? $ticket->end_date->format('d M Y H:i') : '-' }}
                                        </td>
                                        <td class="px-4 py-3 text-sm">
                                            <span
                                                class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">{{ $ticket->status->status_name ?? 'Unknown' }}</span>
                                        </td>
                                        <td class="px-4 py-3 text-sm">
                                            <div class="flex gap-1">
                                                <button
                                                    class="px-2 py-1 text-xs bg-red-500 text-white rounded hover:bg-red-600">
                                                    Cancel
                                                </button>
                                                <button
                                                    class="px-2 py-1 text-xs bg-yellow-500 text-white rounded hover:bg-yellow-600">
                                                    Pend
                                                </button>
                                                <button
                                                    class="px-2 py-1 text-xs bg-green-500 text-white rounded hover:bg-green-600">
                                                    Done
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="11" class="px-4 py-3 text-center text-sm text-gray-500">
                                            Tidak ada tiket in progress.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Tickets Waiting Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Tickets Waiting</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Divisi
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kategori
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Masalah
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal
                                        Req</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($ticketsWaiting as $ticket)
                                    <tr>
                                        <td class="px-4 py-3 text-sm text-gray-900">{{ $ticket->ticket_code }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-900">
                                            {{ $ticket->user->department->department_name ?? '-' }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-900">
                                            {{ $ticket->user->name ?? '-' }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-900">
                                            {{ $ticket->category->category_name ?? '-' }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-900">
                                            {{ Str::limit($ticket->problem, 50) }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-900">
                                            {{ $ticket->request_date ? $ticket->request_date->format('d M Y') : '-' }}
                                        </td>
                                        <td class="px-4 py-3 text-sm">
                                            <button
                                                class="px-3 py-1 text-xs bg-blue-500 text-white rounded hover:bg-blue-600">
                                                Pilih
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-4 py-3 text-center text-sm text-gray-500">
                                            Tidak ada tiket waiting.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Tickets Closed This Month Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Tickets Closed This Month</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Divisi
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kategori
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Masalah
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal
                                        Req</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Time
                                        Spent</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Feedback
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($ticketsClosedThisMonth as $ticket)
                                    <tr>
                                        <td class="px-4 py-3 text-sm text-gray-900">{{ $ticket->ticket_code ?? '-' }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-900">
                                            {{ $ticket->user->department->department_name ?? '-' }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-900">{{ $ticket->user->name ?? '-' }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-900">
                                            {{ $ticket->category->category_name ?? '-' }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-900">{{ $ticket->problem ?? '-' }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-900">
                                            {{ \Carbon\Carbon::parse($ticket->request_date)->format('d M Y') }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-900">
                                            {{ $ticket->time_spent ? $ticket->time_spent . ' Jam' : '-' }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-900">
                                            {{ $ticket->feedback->rating ?? 'N/A' }}
                                        </td>
                                        <td class="px-4 py-3 text-sm">
                                            <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">
                                                {{ $ticket->status->status_name ?? 'Completed' }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-sm">
                                            <button
                                                class="px-3 py-1 text-xs bg-gray-500 text-white rounded hover:bg-gray-600">
                                                Cancel
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="px-4 py-3 text-center text-sm text-gray-500">
                                            Tidak ada ticket yang selesai bulan ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


<!-- Bottom Stats -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6">
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-4 text-center">
            <div class="text-sm text-gray-600">Avg Response Wait Time</div>
            <div class="text-2xl font-bold text-gray-800 mt-2">
                {{ $avgResponseWaitTime }} Jam
            </div>
        </div>
    </div>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-4 text-center">
            <div class="text-sm text-gray-600">Full Resolution Time</div>
            <div class="text-2xl font-bold text-gray-800 mt-2">
                {{ $fullResolutionTime }} Jam
            </div>
        </div>
    </div>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-4 text-center">
            <div class="text-sm text-gray-600">Avg Resolution Time</div>
            <div class="text-2xl font-bold text-gray-800 mt-2">
                {{ $avgResolutionTime }} Jam
            </div>
        </div>
    </div>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-4 text-center">
            <div class="text-sm text-gray-600">SLA Meet</div>
            <div class="text-2xl font-bold text-gray-800 mt-2">
                {{ $slaPercentage }}%
            </div>
        </div>
    </div>
</div>

</div>
</div>
</x-app-layout>

