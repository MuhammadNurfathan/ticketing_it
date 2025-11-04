<x-app-layout>
    <x-slot name="header">
        @auth
            @if (Auth::user()->role_id != 3)
                <div class="flex justify-between items-center">
                    <h2 class="font-bold text-2xl text-light-text dark:text-dark-text">Ticket Dashboard</h2>
                    <a href="{{ route('DashboardTicketsAdmin.create') }}"
                        class="bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white px-4 py-2 rounded shadow transition duration-300">
                        Create Ticket
                    </a>
                </div>
            @endif
        @endauth

    </x-slot>

    <div class="p-6 space-y-6">
        {{-- FILTER DATE UNTUK STATUS DONE  --}}
        <form method="GET" action="{{ route('DashboardTicketsAdmin.index') }}"
            class="flex flex-wrap items-end gap-4 bg-light-eval-1 dark:bg-dark-eval-1 p-4 rounded shadow">
            <div>
                <label class="block text-sm font-semibold mb-1 text-light-text dark:text-dark-text">
                    Dari (Request Date)
                </label>
                <input type="date" name="start_date" value="{{ $start }}"
                    class="border border-gray-300 dark:border-gray-600 rounded p-2 w-48 bg-white dark:bg-dark-eval-2 text-light-text dark:text-dark-text">
            </div>

            <div>
                <label class="block text-sm font-semibold mb-1 text-light-text dark:text-dark-text">Sampai (Request
                    Date)</label>
                <input type="date" name="end_date"
                    value="{{ $end }}"class="border border-gray-300 dark:border-gray-600 rounded p-2 w-48 bg-white dark:bg-dark-eval-2 text-light-text dark:text-dark-text">
            </div>

            <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white px-4 py-2 rounded transition duration-300">
                Filter
            </button>
        </form>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @php
                $statusColors = [
                    'Waiting' => 'blue',
                    'In Progress' => 'purple',
                    'Done' => 'green',
                    'Void' => 'red',
                ];

                $statusIcons = [
                    'Waiting' => '⏳',
                    'In Progress' => '⚙️',
                    'Done' => '✅',
                    'Void' => '❌',
                ];
            @endphp

            @foreach ($statusColors as $key => $color)
                <div
                    class="relative bg-light-eval-1 dark:bg-dark-eval-1 p-5 rounded shadow hover:scale-105 transform transition duration-300 border border-gray-200 dark:border-gray-700">

                    <!-- Icon kecil di pojok kiri atas, di dalam border -->
                    <div class="absolute top-2 left-2 text-xl text-gray-400 dark:text-gray-500">
                        {{ $statusIcons[$key] }}
                    </div>

                    <div class="text-3xl font-bold text-light-text dark:text-dark-text text-center">
                        {{ $stats[strtolower(str_replace(' ', '_', $key))] }}
                    </div>
                    <div class="font-semibold text-light-text-secondary dark:text-dark-text-secondary mt-2 text-center">
                        {{ $key }}
                    </div>
                </div>
            @endforeach
        </div>


        {{-- ================= TABLE IN PROGRESS ================= --}}
        <div
            class="bg-light-eval-1 dark:bg-dark-eval-1 rounded-lg shadow-md p-4 border border-gray-200 dark:border-gray-700">
            <h3
                class="inline bg-blue-500 px-2 py-1 font-semibold text-lg mb-2 border-b border-gray-300 dark:border-gray-600 pb-1 text-light-text dark:text-dark-text">
                Tickets In Progress</h3>
            <div class="overflow-x-auto py-4">
                <table class="datatable min-w-full border border-gray-300 dark:border-gray-600 text-sm">
                    <thead class="bg-light-eval-2 dark:bg-dark-eval-2 text-left">
                        <tr>
                            <th
                                class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                Ticket Code
                            </th>
                            <th
                                class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                Requestor
                            </th>
                            <th
                                class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                Department
                            </th>
                            <th
                                class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                Assignee
                            </th>
                            <th
                                class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                Kategori
                            </th>
                            <th
                                class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                Masalah
                            </th>
                            <th
                                class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                Tanggal Req
                            </th>
                            <th
                                class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                Start Date
                            </th>
                            <th
                                class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                End Date
                            </th>
                            @auth
                                @if (Auth::user()->role_id != 3)
                                    <th
                                        class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text text-center align-middle">
                                        Action
                                    </th>
                                @endif
                            @endauth
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
                                    {{ $ticket->user->department->department_name }} -
                                    {{ $ticket->user->department->location->location_name }} </td>
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
                                    {{ $ticket->start_date?->format('Y-m-d H:i:s') }}</td>
                                <td
                                    class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                    {{ $ticket->end_date?->format('Y-m-d H:i:s') ?? '-' }}</td>

                                @auth
                                    @if (Auth::user()->role_id != 3)
                                        <td class="border border-gray-300 dark:border-gray-600 p-2 text-center space-x-1">
                                            <button type="button"
                                                class="doneBtn bg-green-600 hover:bg-green-700 text-white px-2 py-1 rounded text-xs"
                                                data-start="{{ $ticket->start_date?->format('Y-m-d H:i:s') }}">
                                                Done
                                            </button>

                                            <!-- Modal -->
                                            <div
                                                class="timeSpentModal fixed inset-0 z-50 hidden items-center justify-center bg-black/30 dark:bg-black/50 backdrop-blur-sm transition-all duration-300 ease-in-out">
                                                <div
                                                    class="modalContent bg-white dark:bg-gray-800 rounded-lg p-6 w-96 transform scale-95 opacity-0 transition-all duration-300 ease-in-out shadow-lg">
                                                    <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">
                                                        Time Spent & Solution
                                                    </h3>

                                                    <!-- Time Spent -->
                                                    <div class="mb-4">
                                                        <div class="flex items-center justify-between">
                                                            <label
                                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                                Time Spent (Menit)
                                                            </label>
                                                            <label
                                                                class="flex items-center text-sm text-gray-600 dark:text-gray-300 mb-2">
                                                                <input type="checkbox" class="manualCheckbox mr-2">
                                                                Manual
                                                                Input
                                                            </label>
                                                        </div>
                                                        <input type="number"
                                                            class="timeInput w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm px-3 py-2 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                                            readonly>
                                                    </div>

                                                    <!-- Notes -->
                                                    <div class="mb-4 hidden notesContainer">
                                                        <label
                                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                            Notes (Kenapa manual) <span class="text-red-500">*</span>
                                                        </label>
                                                        <textarea
                                                            class="notesInput w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm px-3 py-2 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                                            rows="2" placeholder="Tambahkan catatan..."></textarea>
                                                    </div>

                                                    <!-- Solution -->
                                                    <div class="mb-4">
                                                        <label
                                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                            Solution <span class="text-red-500">*</span>
                                                        </label>
                                                        <textarea name="solution"
                                                            class="solutionInput w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm px-3 py-2 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                                            rows="3" placeholder="Masukkan solusi..." required>{{ old('solution', $ticket->solution ?? '') }}</textarea>
                                                    </div>

                                                    <!-- Buttons -->
                                                    <div class="flex justify-end space-x-2">
                                                        <button type="button"
                                                            class="cancelBtn px-3 py-1 rounded bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-gray-100">Cancel</button>
                                                        <button type="button"
                                                            class="saveBtn px-3 py-1 rounded bg-green-600 hover:bg-green-700 text-white">Save</button>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Hidden form -->
                                            <form class="doneForm"
                                                action="{{ route('DashboardTicketsAdmin.updateStatusDone', $ticket->id) }}"
                                                method="POST">
                                                @csrf
                                                <input type="hidden" name="status_id" value="3">
                                                <input type="hidden" name="time_spent" class="hiddenTimeSpent">
                                                <input type="hidden" name="solution" class="hiddenSolution">
                                                <input type="hidden" name="notes" class="hiddenNotes">
                                            </form>
                                        </td>
                                    @endif
                                @endauth

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
                class="inline bg-yellow-500 px-2 py-1 font-semibold text-lg mb-2 border-b border-gray-300 dark:border-gray-600 pb-1 text-light-text dark:text-dark-text">
                Waiting Tickets</h3>
            <div class="overflow-x-auto py-4">
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
                                    Requestor</th>
                                <th
                                    class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                    Department</th>
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
                                    Image</th>

                                @auth
                                    @if (Auth::user()->role_id != 3)
                                        <th
                                            class="border border-gray-300 dark:border-gray-600 p-2 text-center text-light-text dark:text-dark-text text-center align-middle">
                                            Action</th>
                                </tr>
                                @endif
                            @endauth

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
                                        {{ $ticket->user->department->department_name }} -
                                        {{ $ticket->user->department->location->location_name }} </td>
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
                                        @if ($ticket->image)
                                            <a href="{{ asset('storage/' . $ticket->image) }}" target="_blank"
                                                class="text-blue-600 underline">
                                                Lihat File
                                            </a>
                                        @else
                                            <span class="text-gray-500 text-sm italic">No media</span>
                                        @endif
                                    </td>
                                    @auth
                                        @if (Auth::user()->role_id != 3)
                                            <td class="border border-gray-300 dark:border-gray-600 p-2 text-center"
                                                x-data="{ open: false }">

                                                {{-- Tombol Void --}}
                                                <button @click="open = true"
                                                    class="bg-gray-500 hover:bg-gray-600 text-white px-2 py-1 rounded text-xs transition-all duration-200">
                                                    Void
                                                </button>

                                                {{-- Tombol Pilih --}}
                                                <a href="{{ route('DashboardTicketsAdmin.edit', $ticket->id) }}"
                                                    class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-xs inline-block transition-all duration-200">
                                                    Execution
                                                </a>

                                                {{-- Modal Overlay --}}
                                                <div x-show="open" x-cloak
                                                    x-transition:enter="transition ease-out duration-300"
                                                    x-transition:enter-start="opacity-0"
                                                    x-transition:enter-end="opacity-100"
                                                    x-transition:leave="transition ease-in duration-200"
                                                    x-transition:leave-start="opacity-100"
                                                    x-transition:leave-end="opacity-0"
                                                    class="fixed inset-0 flex items-center justify-center bg-black/50 dark:bg-black/70 backdrop-blur-sm z-50">

                                                    {{-- Modal Content --}}
                                                    <div x-show="open"
                                                        x-transition:enter="transition ease-out duration-300 transform"
                                                        x-transition:enter-start="scale-90 opacity-0"
                                                        x-transition:enter-end="scale-100 opacity-100"
                                                        x-transition:leave="transition ease-in duration-200 transform"
                                                        x-transition:leave-start="scale-100 opacity-100"
                                                        x-transition:leave-end="scale-90 opacity-0"
                                                        class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-xl w-96 border border-gray-200 dark:border-gray-700">

                                                        {{-- Header --}}
                                                        <h2
                                                            class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-3">
                                                            Masukkan Catatan Void
                                                        </h2>

                                                        {{-- Form --}}
                                                        <form
                                                            action="{{ route('DashboardTicketsAdmin.updateStatus', $ticket->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            <input type="hidden" name="status_id" value="4">

                                                            <textarea name="notes" rows="3"
                                                                class="w-full border border-gray-300 dark:border-gray-600 rounded-lg p-2 text-sm focus:ring-2 focus:ring-blue-400 dark:bg-gray-700 dark:text-gray-100"
                                                                placeholder="Tulis alasan void di sini..." required></textarea>

                                                            {{-- Footer --}}
                                                            <div class="flex justify-end mt-4 space-x-2">
                                                                <button type="button" @click="open = false"
                                                                    class="px-3 py-1 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-100 rounded-lg text-sm transition-all duration-200">
                                                                    Cancel
                                                                </button>

                                                                <button type="submit"
                                                                    class="px-3 py-1 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm transition-all duration-200">
                                                                    Save
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </td>
                                        @endif
                                    @endauth


                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

            {{-- ================= TABLE DONE / CLOSED ================= --}}
            <div
                class="bg-light-eval-1 dark:bg-dark-eval-1 rounded-lg shadow-md p-4 border border-gray-200 dark:border-gray-700">
                <h3
                    class="inline bg-green-500 px-2 py-1 font-semibold text-lg mb-2 border-b border-gray-300 dark:border-gray-600 pb-1 text-light-text dark:text-dark-text">
                    Tickets Closed / Done</h3>
                <div class="overflow-x-auto py-4">
                    <table class="datatable min-w-full border border-gray-300 dark:border-gray-600 text-sm">
                        <thead class="bg-light-eval-2 dark:bg-dark-eval-2 text-left">
                            <tr>
                                <th
                                    class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                    Ticket Code</th>
                                <th
                                    class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                    Requestor</th>
                                <th
                                    class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                    Department</th>
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
                                    Feedback</th>
                                <th
                                    class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                    Solusi</th>
                                <th
                                    class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                    Status</th>
                                <th
                                    class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text text-center align-middle">
                                    Image</th>

                                @auth
                                    @if (Auth::user()->role_id != 3)
                                        <th
                                            class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text text-center align-middle">
                                            Action</th>
                                    @endif
                                @endauth
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
                                        {{ $ticket->user->department->department_name }} -
                                        {{ $ticket->user->department->location->location_name }} </td>
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
                                        {{ $ticket->time_spent ?? '-' }} menit</td>
                                    <td
                                        class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                        {{ $ticket->feedback->description ?? '-' }}</td>
                                    <td
                                        class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                        {{ $ticket->solution ?? '-' }}</td>

                                    <td
                                        class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                        {{ $ticket->is_late ? 'Late' : 'On Time' }}
                                    </td>



                                    <td
                                        class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                        @if ($ticket->image)
                                            <a href="{{ asset('storage/' . $ticket->image) }}" target="_blank"
                                                class="text-blue-600 underline">
                                                Lihat File
                                            </a>
                                        @else
                                            <span class="text-gray-500 text-sm italic">No media</span>
                                        @endif
                                    </td>

                                    @auth
                                        @if (Auth::user()->role_id != 3)
                                            <td class="border border-gray-300 dark:border-gray-600 p-2 text-center">
                                                <form
                                                    action="{{ route('DashboardTicketsAdmin.updateStatus', $ticket->id) }}"
                                                    method="POST" class="inline">
                                                    @csrf
                                                    <input type="hidden" name="status_id" value="2">
                                                    <button
                                                        class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-xs"
                                                        onclick="return confirm('Apakah Anda Yakin?')">
                                                        Cancel
                                                    </button>
                                                </form>
                                            </td>
                                        @endif
                                    @endauth
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
                    class="inline bg-red-500 px-2 py-1 font-semibold text-lg mb-2 border-b border-gray-300 dark:border-gray-600 pb-1 text-light-text dark:text-dark-text">
                    Tickets Void</h3>
                <div class="overflow-x-auto py-4">
                    <table class="datatable min-w-full border border-gray-300 dark:border-gray-600 text-sm">
                        <thead class="bg-light-eval-2 dark:bg-dark-eval-2 text-left">
                            <tr>
                                <th
                                    class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                    Ticket Code</th>
                                <th
                                    class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                    Requestor</th>
                                <th
                                    class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                    Department</th>
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
                                @auth
                                    @if (Auth::user()->role_id != 3)
                                        <th
                                            class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text text-center align-middle">
                                            Action</th>
                                    @endif
                                @endauth

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
                                        {{ $ticket->user->department->department_name }} -
                                        {{ $ticket->user->department->location->location_name }} </td>
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

                                    @auth
                                        @if (Auth::user()->role_id != 3)
                                            <td class="border border-gray-300 dark:border-gray-600 p-2 text-center">
                                                <form
                                                    action="{{ route('DashboardTicketsAdmin.updateStatus', $ticket->id) }}"
                                                    method="POST" class="inline">
                                                    @csrf
                                                    <input type="hidden" name="status_id" value="1">
                                                    <button onclick="return confirm('Yakin mau lanjut?')"
                                                        class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-xs"
                                                        onclick=>
                                                        Cancel
                                                    </button>
                                                </form>
                                            </td>
                                        @endif
                                    @endauth

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
        <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

      <style>
/* Wrapper styling agar flexible */
.dataTables_wrapper .dataTables_length,
.dataTables_wrapper .dataTables_filter {
    display: inline-block;
    margin: 0;
}

.dataTables_wrapper .top-flex {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.5rem;
}

.dataTables_wrapper .bottom-flex {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 0.5rem;
}

/* Input & select styling */
.dataTables_wrapper .dataTables_filter input,
.dataTables_wrapper .dataTables_length select {
    padding: 0.375rem 0.75rem;
    border-radius: 0.375rem;
    border: 1px solid #d1d5db;
    font-size: 0.875rem;
}

.dark .dataTables_wrapper .dataTables_filter input,
.dark .dataTables_wrapper .dataTables_length select {
    border-color: #4b5563;
    background-color: #374151;
    color: #f9fafb;
}

/* Pagination */
.dataTables_wrapper .dataTables_paginate {
    margin: 0;
}

.dataTables_wrapper .dataTables_paginate .paginate_button {
    margin: 0 2px;
    border-radius: 0.375rem;
    padding: 0.375rem 0.75rem;
    border: none !important;
    background-color: #f3f4f6;
    color: #111827 !important;
}

.dark .dataTables_wrapper .dataTables_paginate .paginate_button {
    background-color: #374151;
    color: #f9fafb !important;
}

.dataTables_wrapper .dataTables_paginate .paginate_button.current {
    background-color: #3b82f6 !important;
    color: #ffffff !important;
}

.dark .dataTables_wrapper .dataTables_paginate .paginate_button.current {
    background-color: #2563eb !important;
    color: #f9fafb !important;
}

/* Center empty table message */
table.dataTable tbody td.dataTables_empty {
    text-align: center;   /* teks di tengah */
    vertical-align: middle; /* vertikal di tengah */
    font-weight: 500;
    color: #6b7280; /* teks abu */
    padding: 2rem 0; /* beri jarak agar tidak terlalu mepet */
}
.dark table.dataTable tbody td.dataTables_empty {
    color: #d1d5db; /* teks abu terang untuk dark mode */
}

</style>

        {{-- ================= DATATABLE ================= --}}
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
                        paginate: {
                            next: "›",
                            previous: "‹"
                        },
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
                        $('div.dataTables_info').addClass(isDark ? 'text-gray-400 text-sm mt-2' :
                            'text-gray-600 text-sm mt-2');
                    }
                });
            });
        </script>

        {{-- ================= DONE BUTTON ================= --}}

        <script>
            document.querySelectorAll('.doneBtn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const modal = this.nextElementSibling;
                    const modalContent = modal.querySelector('.modalContent');
                    const start = new Date(this.dataset.start);
                    const timeInput = modal.querySelector('.timeInput');
                    const solutionInput = modal.querySelector('.solutionInput');
                    const notesContainer = modal.querySelector('.notesContainer');
                    const notesInput = modal.querySelector('.notesInput');
                    const manualCheckbox = modal.querySelector('.manualCheckbox');
                    const form = modal.parentElement.querySelector('.doneForm');

                    // Tampilkan modal dengan transisi
                    modal.classList.remove('hidden');
                    setTimeout(() => {
                        modal.classList.add('flex');
                        modalContent.classList.remove('opacity-0', 'scale-95');
                        modalContent.classList.add('opacity-100', 'scale-100');
                    }, 10);

                    // Auto hitung time spent
                    const calcAutoTime = () => {
                        if (!manualCheckbox.checked && start) {
                            const now = new Date();
                            const diff = Math.floor((now - start) / (1000 * 60));
                            timeInput.value = diff > 0 ? diff : 0;
                        }
                    };
                    calcAutoTime();

                    // Toggle manual
                    manualCheckbox.addEventListener('change', () => {
                        if (manualCheckbox.checked) {
                            timeInput.removeAttribute('readonly');
                            timeInput.classList.remove('bg-gray-100');
                            notesContainer.classList.remove('hidden');
                        } else {
                            timeInput.setAttribute('readonly', true);
                            timeInput.classList.add('bg-gray-100');
                            notesContainer.classList.add('hidden');
                            notesInput.value = '';
                            calcAutoTime();
                        }
                    });

                    // Cancel
                    modal.querySelector('.cancelBtn').onclick = () => {
                        modalContent.classList.remove('opacity-100', 'scale-100');
                        modalContent.classList.add('opacity-0', 'scale-95');
                        setTimeout(() => {
                            modal.classList.remove('flex');
                            modal.classList.add('hidden');
                        }, 200);
                    };

                    // Save dengan validasi
                    modal.querySelector('.saveBtn').onclick = () => {
                        if (!solutionInput.value.trim()) {
                            alert('Kolom Solution wajib diisi!');
                            solutionInput.focus();
                            return;
                        }
                        if (!notesContainer.classList.contains('hidden') && !notesInput.value.trim()) {
                            alert('Kolom Notes wajib diisi saat manual!');
                            notesInput.focus();
                            return;
                        }

                        form.querySelector('.hiddenTimeSpent').value = timeInput.value;
                        form.querySelector('.hiddenSolution').value = solutionInput.value;
                        form.querySelector('.hiddenNotes').value = notesInput.value;
                        form.submit();
                    };
                });
            });
        </script>


        {{-- ================= SAVE BUTTON ================= --}}
        <script>
            modal.querySelector('.saveBtn').onclick = () => {
                // ambil form hidden yang berada di satu td dengan modal
                const form = modal.parentElement.querySelector('.doneForm');

                form.querySelector('.hiddenTimeSpent').value = timeInput.value;
                form.querySelector('.hiddenSolution').value = solutionInput.value;
                form.querySelector('.hiddenNotes').value = notesInput.value;

                form.submit();
            }
        </script>

</x-app-layout>
