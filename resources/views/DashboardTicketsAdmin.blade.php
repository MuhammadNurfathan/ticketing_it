<x-app-layout>
    {{-- Push CSS ke head --}}

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-light-text dark:text-dark-text">Ticket Dashboard</h2>
            <a href="{{ route('DashboardTicketsAdmin.create') }}"
                class="bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white px-4 py-2 rounded shadow transition duration-300">
                ➕ Tambah Ticket
            </a>
        </div>
    </x-slot>
    {{-- CSS DataTables --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">

    <div class="p-6 space-y-6">
        {{-- FILTER FORM --}}
        <form method="GET" action="{{ route('DashboardTicketsAdmin.index') }}"
            class="flex flex-wrap items-end gap-4 bg-light-eval-1 dark:bg-dark-eval-1 p-4 rounded shadow">
            <div>
                <label class="block text-sm font-semibold mb-1 text-light-text dark:text-dark-text">Dari (Request
                    Date)</label>
                <input type="date" name="start_date" value="{{ $start }}"
                    class="border border-gray-300 dark:border-gray-600 rounded p-2 w-48 bg-white dark:bg-dark-eval-2 text-light-text dark:text-dark-text">
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1 text-light-text dark:text-dark-text">Sampai (Request
                    Date)</label>
                <input type="date" name="end_date" value="{{ $end }}"
                    class="border border-gray-300 dark:border-gray-600 rounded p-2 w-48 bg-white dark:bg-dark-eval-2 text-light-text dark:text-dark-text">
            </div>
            <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white px-4 py-2 rounded transition duration-300">
                Filter
            </button>
        </form>

        {{-- STATISTIK CARDS --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @php
                $statusColors = [
                    'Waiting' => 'blue',
                    'In Progress' => 'purple',
                    'Done' => 'green',
                    'Void' => 'red',
                ];
            @endphp
            @foreach ($statusColors as $key => $color)
                <div
                    class="bg-light-eval-1 dark:bg-dark-eval-1 p-5 rounded shadow hover:scale-105 transform transition duration-300 text-center border border-gray-200 dark:border-gray-700">
                    <div class="text-3xl font-bold text-light-text dark:text-dark-text">
                        {{ $stats[strtolower(str_replace(' ', '_', $key))] }}</div>
                    <div class="font-semibold text-light-text-secondary dark:text-dark-text-secondary mt-2">
                        {{ $key }}</div>
                </div>
            @endforeach
        </div>

        {{-- ================= TABLE IN PROGRESS ================= --}}
        <div
            class="bg-light-eval-1 dark:bg-dark-eval-1 rounded-lg shadow-md p-4 border border-gray-200 dark:border-gray-700">
            <h3
                class="font-semibold text-lg mb-2 border-b border-gray-300 dark:border-gray-600 pb-1 text-light-text dark:text-dark-text">
                Tickets In Progress</h3>
            <div class="overflow-x-auto">
                <table class="datatable min-w-full border border-gray-300 dark:border-gray-600 text-sm">
                    <thead class="bg-light-eval-2 dark:bg-dark-eval-2 text-left">
                        <tr>
                            <th
                                class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                Ticket Code</th>
                            <th
                                class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                Nama</th>
                            <th
                                class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                Assignee</th>
                            <th
                                class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                Kategori</th>
                            <th
                                class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                Masalah</th>
                            <th
                                class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                Tanggal Req</th>
                            <th
                                class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                Start Date</th>
                            <th
                                class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                End Date</th>
                            <th
                                class="border border-gray-300 dark:border-gray-600 p-2 text-center text-light-text dark:text-dark-text">
                                Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-dark-eval-1">
                        @foreach ($inProgressTickets as $ticket)
                            <tr class="hover:bg-light-eval-2 dark:hover:bg-dark-eval-2">
                                <td
                                    class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                    {{ $ticket->ticket_code }}</td>
                                <td
                                    class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                    {{ $ticket->user->name ?? '-' }}</td>
                                <td
                                    class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                    {{ $ticket->support->name ?? '-' }}</td>
                                <td
                                    class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                    {{ $ticket->problemCategory?->problem_category_name ?? '-' }}</td>
                                <td
                                    class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                    {{ $ticket->problem }}</td>
                                <td
                                    class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                    {{ $ticket->request_date?->format('Y-m-d') }}</td>
                                <td
                                    class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                    {{ $ticket->start_date?->format('Y-m-d') }}</td>
                                <td
                                    class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                    {{ $ticket->end_date?->format('Y-m-d') ?? '-' }}</td>
                                <td class="border border-gray-300 dark:border-gray-600 p-2 text-center space-x-1">
                                  
                                    <form action="{{ route('DashboardTicketsAdmin.updateStatus', $ticket->id) }}"
                                        method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="status_id" value="6">
                                        <button
                                            class="bg-gray-500 hover:bg-gray-600 text-white px-2 py-1 rounded text-xs">Void</button>
                                    </form>
                                    <form action="{{ route('DashboardTicketsAdmin.updateStatus', $ticket->id) }}"
                                        method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="status_id" value="5">
                                        <button
                                            class="bg-green-600 hover:bg-green-700 text-white px-2 py-1 rounded text-xs">Done</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ================= TABLE WAITING ================= --}}
        <div
            class="bg-light-eval-1 dark:bg-dark-eval-1 rounded-lg shadow-md p-4 border border-gray-200 dark:border-gray-700">
            <h3
                class="font-semibold text-lg mb-2 border-b border-gray-300 dark:border-gray-600 pb-1 text-light-text dark:text-dark-text">
                Tickets Waiting</h3>
            {{-- <div class="flex gap-4 text-sm mb-4 text-light-text-secondary dark:text-dark-text-secondary">
                <div>Total: {{ $waitingTickets->count() }}</div>
            </div> --}}
            <div class="overflow-x-auto">
                <table class="datatable min-w-full border border-gray-300 dark:border-gray-600 text-sm">
                    <thead class="bg-light-eval-2 dark:bg-dark-eval-2 text-left">
                        <tr>
                            <th
                                class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                Ticket Code</th>
                            <th
                                class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                Nama</th>
                            <th
                                class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                Kategori</th>
                            <th
                                class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                Masalah</th>
                            <th
                                class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                Tanggal Req</th>
                            <th
                                class="border border-gray-300 dark:border-gray-600 p-2 text-center text-light-text dark:text-dark-text">
                                Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-dark-eval-1">
                        @foreach ($waitingTickets as $ticket)
                            <tr class="hover:bg-light-eval-2 dark:hover:bg-dark-eval-2">
                                <td
                                    class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                    {{ $ticket->ticket_code }}</td>
                                <td
                                    class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                    {{ $ticket->user->name ?? '-' }}</td>
                                <td
                                    class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                    {{ $ticket->problemCategory?->problem_category_name ?? '-' }}</td>
                                <td
                                    class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                    {{ $ticket->problem }}</td>
                                <td
                                    class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                    {{ $ticket->request_date?->format('Y-m-d') }}</td>
                                <td class="border border-gray-300 dark:border-gray-600 p-2 text-center">
                                    <a href="{{ route('DashboardTicketsAdmin.edit', $ticket->id) }}"
                                        class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-xs inline-block">Pilih</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ================= TABLE PENDING ================= --}}
        {{-- <div class="bg-light-eval-1 dark:bg-dark-eval-1 rounded-lg shadow-md p-4 border border-gray-200 dark:border-gray-700">
            <h3 class="font-semibold text-lg mb-2 border-b border-gray-300 dark:border-gray-600 pb-1 text-light-text dark:text-dark-text">Tickets Pending</h3>
            <div class="overflow-x-auto">
                <table class="datatable" class="min-w-full border border-gray-300 dark:border-gray-600 text-sm">
                    <thead class="bg-light-eval-2 dark:bg-dark-eval-2 text-left">
                        <tr>
                            <th class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">Ticket Code</th>
                            <th class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">Nama</th>
                            <th class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">Kategori</th>
                            <th class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">Masalah</th>
                            <th class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">Tanggal Req</th>
                            <th class="border border-gray-300 dark:border-gray-600 p-2 text-center text-light-text dark:text-dark-text">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-dark-eval-1">
                        @forelse ($pendingTickets as $ticket)
                            <tr class="hover:bg-light-eval-2 dark:hover:bg-dark-eval-2">
                                <td class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">{{ $ticket->ticket_code }}</td>
                                <td class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">{{ $ticket->user->name ?? '-' }}</td>
                                <td class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">{{ $ticket->problemCategory?->problem_category_name ?? '-' }}</td>
                                <td class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">{{ $ticket->problem }}</td>
                                <td class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">{{ $ticket->request_date?->format('Y-m-d') }}</td>
                                <td class="border border-gray-300 dark:border-gray-600 p-2 text-center">
                                    <form action="{{ route('DashboardTicketsAdmin.updateStatus', $ticket->id) }}"
                                        method="POST">
                                        @csrf
                                        <input type="hidden" name="status_id" value="2">
                                        <button class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-xs">Continue</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="border border-gray-300 dark:border-gray-600 p-2 text-center text-light-text-muted dark:text-dark-text-muted">Tidak ada data</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div> --}}

        {{-- ================= TABLE DONE / CLOSED ================= --}}
        <div
            class="bg-light-eval-1 dark:bg-dark-eval-1 rounded-lg shadow-md p-4 border border-gray-200 dark:border-gray-700">
            <h3
                class="font-semibold text-lg mb-2 border-b border-gray-300 dark:border-gray-600 pb-1 text-light-text dark:text-dark-text">
                Tickets Closed / Done</h3>
            <div class="overflow-x-auto">
                <table class="datatable min-w-full border border-gray-300 dark:border-gray-600 text-sm">
                    <thead class="bg-light-eval-2 dark:bg-dark-eval-2 text-left">
                        <tr>
                            <th
                                class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                Ticket Code</th>
                            <th
                                class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                Nama</th>
                            <th
                                class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                Assignee</th>
                            <th
                                class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                Kategori</th>
                            <th
                                class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                Masalah</th>
                            <th
                                class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                Tanggal Req</th>
                            <th
                                class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                Time Spent</th>
                            <th
                                class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                Solusi</th>
                            <th
                                class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                Status</th>
                            <th
                                class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-dark-eval-1">
                        @foreach ($doneTickets as $ticket)
                            <tr class="hover:bg-light-eval-2 dark:hover:bg-dark-eval-2">
                                <td
                                    class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                    {{ $ticket->ticket_code }}</td>
                                <td
                                    class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                    {{ $ticket->user->name ?? '-' }}</td>
                                <td
                                    class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                    {{ $ticket->support->name ?? '-' }}</td>
                                <td
                                    class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                    {{ $ticket->problemCategory?->problem_category_name ?? '-' }}</td>
                                <td
                                    class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                    {{ $ticket->problem }}</td>
                                <td
                                    class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                    {{ $ticket->request_date?->format('Y-m-d') }}</td>
                                <td
                                    class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                    {{ $ticket->time_spent ?? '-' }}</td>
                                <td
                                    class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                    {{ $ticket->solution ?? '-' }}</td>
                                <td class="border border-gray-300 dark:border-gray-600 p-2">
                                    @if ($ticket->time_spent >= 8)
                                        <span
                                            class="bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-300 px-2 py-1 rounded-full text-xs">Late</span>
                                    @else
                                        <span
                                            class="bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 px-2 py-1 rounded-full text-xs">On
                                            Time</span>
                                    @endif
                                </td>
                                <td class="border border-gray-300 dark:border-gray-600 p-2 text-center">
                                    <form action="{{ route('DashboardTicketsAdmin.updateStatus', $ticket->id) }}"
                                        method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="status_id" value="2">
                                        <button
                                            class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-xs">
                                            Cancel
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ================= TABLE VOID ================= --}}
        <div
            class="bg-light-eval-1 dark:bg-dark-eval-1 rounded-lg shadow-md p-4 border border-gray-200 dark:border-gray-700">
            <h3
                class="font-semibold text-lg mb-2 border-b border-gray-300 dark:border-gray-600 pb-1 text-light-text dark:text-dark-text">
                Tickets Void</h3>
            <div class="overflow-x-auto">
                <table class="datatable min-w-full border border-gray-300 dark:border-gray-600 text-sm">
                    <thead class="bg-light-eval-2 dark:bg-dark-eval-2 text-left">
                        <tr>
                            <th
                                class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                Ticket Code</th>
                            <th
                                class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                Nama</th>
                            <th
                                class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                Assignee</th>
                            <th
                                class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                Kategori</th>
                            <th
                                class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                Masalah</th>
                            <th
                                class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                Tanggal Req</th>
                            <th
                                class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-dark-eval-1">
                        @foreach ($voidTickets as $ticket)
                            <tr class="hover:bg-light-eval-2 dark:hover:bg-dark-eval-2">
                                <td
                                    class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                    {{ $ticket->ticket_code ?? '-' }}</td>
                                <td
                                    class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                    {{ $ticket->user->name ?? '-' }}</td>
                                <td
                                    class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                    {{ $ticket->support->name ?? '-' }}</td>
                                <td
                                    class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                    {{ $ticket->problemCategory?->problem_category_name ?? '-' }}</td>
                                <td
                                    class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                    {{ $ticket->problem ?? '-' }}</td>
                                <td
                                    class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                    {{ $ticket->request_date?->format('Y-m-d') ?? '-' }}</td>
                                <td class="border border-gray-300 dark:border-gray-600 p-2 text-center">
                                    <form action="{{ route('DashboardTicketsAdmin.updateStatus', $ticket->id) }}"
                                        method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="status_id" value="2">
                                        <button
                                            class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-xs">
                                            Cancel
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- METRIK SUMMARY --}}
        {{-- <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 text-center mt-6">
            <div
                class="bg-light-eval-1 dark:bg-dark-eval-1 p-4 rounded shadow hover:scale-105 transform transition duration-300 border border-gray-200 dark:border-gray-700">
                <span class="text-2xl">📊</span>
                <div class="text-light-text-secondary dark:text-dark-text-secondary text-sm mt-1">SLA</div>
                <div class="text-light-text dark:text-dark-text font-bold text-xl">{{ $stats['sla'] }}%</div>
            </div>
            <div
                class="bg-light-eval-1 dark:bg-dark-eval-1 p-4 rounded shadow hover:scale-105 transform transition duration-300 border border-gray-200 dark:border-gray-700">
                <span class="text-2xl">⏱</span>
                <div class="text-light-text-secondary dark:text-dark-text-secondary text-sm mt-1">Avg Waiting</div>
                <div class="text-light-text dark:text-dark-text font-bold text-xl">{{ $stats['avg_waiting'] }} jam
                </div>
            </div>
            <div
                class="bg-light-eval-1 dark:bg-dark-eval-1 p-4 rounded shadow hover:scale-105 transform transition duration-300 border border-gray-200 dark:border-gray-700">
                <span class="text-2xl">⏳</span>
                <div class="text-light-text-secondary dark:text-dark-text-secondary text-sm mt-1">Avg Resolution</div>
                <div class="text-light-text dark:text-dark-text font-bold text-xl">{{ $stats['avg_time_spent'] }} jam
                </div>
            </div>
            <div
                class="bg-light-eval-1 dark:bg-dark-eval-1 p-4 rounded shadow hover:scale-105 transform transition duration-300 border border-gray-200 dark:border-gray-700">
                <span class="text-2xl">🕒</span>
                <div class="text-light-text-secondary dark:text-dark-text-secondary text-sm mt-1">Total Time Spent
                </div>
                <div class="text-light-text dark:text-dark-text font-bold text-xl">{{ $stats['sum_time_spent'] }} jam
                </div>
            </div>
        </div> --}}
    </div>

    {{-- DataTables CSS --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">

    {{-- jQuery --}}
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

    {{-- DataTables JS --}}
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    {{-- DataTables JS --}}

    
<script>
$(document).ready(function() {
    $('.datatable').DataTable({
        responsive: true,
        paging: true,
        searching: true,
        ordering: true,
        info: true,
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Cari data...",
            emptyTable: "Tidak ada data tersedia di tabel ini",
            paginate: { next: "›", previous: "‹" },
        },
        dom: '<"flex flex-wrap justify-between items-center mb-4"<"flex gap-2"l><"flex gap-2"f>>t<"flex flex-wrap justify-between items-center mt-4"<"text-sm"i><"flex gap-2"p>>',
        initComplete: function() {
            const isDark = document.documentElement.classList.contains('dark');

            // Search input & length select
            $('div.dataTables_filter input, div.dataTables_length select').addClass(
                `rounded-md border px-2 py-1 text-sm transition ${isDark ? 'bg-gray-800 border-gray-700 text-gray-100 placeholder-gray-400' : 'bg-white border-gray-300 text-gray-800 placeholder-gray-400'}`
            );
            $('div.dataTables_length select').css('width', '3.5rem'); // lebar select

            // Pagination
            $('div.dataTables_paginate a').each(function() {
                $(this).addClass(
                    `px-3 py-1 border rounded-md mx-1 text-sm font-medium transition ${isDark ? 'border-gray-700 text-gray-100 hover:bg-gray-700' : 'border-gray-300 text-gray-800 hover:bg-gray-100'}`
                );
            });

            // Info text
            $('div.dataTables_info').addClass(isDark ? 'text-gray-400 text-sm mt-2' : 'text-gray-600 text-sm mt-2');
        }
    });
});
    </script>

</x-app-layout>
