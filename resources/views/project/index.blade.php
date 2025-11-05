<x-app-layout>
    {{-- ========================================================= HEADER ========================================================= --}}
    <x-slot name="header">
        @auth
            @if (Auth::user()->role_id != 3)
                <div class="flex justify-between items-center">
                    <h2 class="font-bold text-2xl text-light-text dark:text-dark-text">Project</h2>
                    <button onclick="openModal('projectModal')"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        Create Project
                    </button>
                </div>
            @endif
        @endauth
    </x-slot>

    <div class="p-6 space-y-6">
        {{-- ========================================================= FILTER DATE ========================================================= --}}
        <form method="GET" action="{{ route('project.index') }}"
            class="flex flex-wrap items-end gap-4 bg-light-eval-1 dark:bg-dark-eval-1 p-4 rounded shadow">
            <div>
                <label class="block text-sm font-semibold mb-1 text-light-text dark:text-dark-text">
                    Start Date
                </label>
                <input type="date" name="start_date" value="{{ $start }}"
                    class="border border-gray-300 dark:border-gray-600 rounded p-2 w-48 bg-white dark:bg-dark-eval-2 text-light-text dark:text-dark-text">
            </div>

            <div>
                <label class="block text-sm font-semibold mb-1 text-light-text dark:text-dark-text">End Date</label>
                <input type="date" name="end_date"
                    value="{{ $end }}"class="border border-gray-300 dark:border-gray-600 rounded p-2 w-48 bg-white dark:bg-dark-eval-2 text-light-text dark:text-dark-text">
            </div>

            <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 dark:bg-blue-600 dark:hover:bg-blue-700 text-white px-4 py-2 rounded transition duration-300">
                Filter
            </button>
        </form>

        {{-- ========================================================= STATISTIK CARDS ========================================================= --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
            @php
                $statusColors = [
                    'Waiting' => 'blue',
                    'In Progress' => 'purple',
                    'Done' => 'green',
                    'Void' => 'red',
                    'Pending' => 'orange',
                ];

                $statusIcons = [
                    'Waiting' => '⏳',
                    'In Progress' => '⚙️',
                    'Done' => '✅',
                    'Void' => '❌',
                    'Pending' => '🕘',
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

        {{-- ========================================================= PROGRESS TABLE ========================================================= --}}
        <div
            class="bg-light-eval-1 dark:bg-dark-eval-1 rounded-lg shadow-md p-4 border border-gray-200 dark:border-gray-700">
            <h3
                class="inline bg-blue-500 px-2 py-1 to-blue-50 font-semibold text-lg mb-2 border-b border-gray-300 dark:border-gray-600 pb-1 text-light-text dark:text-dark-text">
                Project In Progress</h3>
            <div class="overflow-x-auto py-4">
                <table class="datatable min-w-full border border-gray-300 dark:border-gray-600 text-sm">
                    <thead class="bg-light-eval-2 dark:bg-dark-eval-2 text-left">
                        <tr>
                            <th
                                class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                project Code
                            </th>
                            <th
                                class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                Requestor Date
                            </th>
                            <th
                                class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                Requestor Name
                            </th>
                            <th
                                class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                Priority
                            </th>
                            <th
                                class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                Progress Percent
                            </th>
                            <th
                                class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                Progress Date
                            </th>
                            <th
                                class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                Description
                            </th>


                            <th
                                class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                Start Date
                            </th>
                            <th
                                class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                End Date
                            </th>
                            <th
                                class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                Actual Start Date
                            </th>
                            <th
                                class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                Total Pending Minutes
                            </th>
                            <th
                                class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                History
                            </th>
                            <th
                                class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text text-center align-middle">
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-dark-eval-1">
                        @foreach ($inProgressProject as $project)
                            <tr class="hover:bg-light-eval-2 dark:hover:bg-dark-eval-2">
                                <td
                                    class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                    {{ $project->project_code?? '-' }}</td>
                                <td
                                    class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                    {{ $project->project_name ?? '-' }}</td>
                                <td
                                    class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                    {{ $project->requestor->name ?? '-' }}</td>
                                <td
                                    class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                    {{ $project->priority->priority_name }}</td>
                                <td
                                    class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                    {{ $project->progress_percent ?? '-' }}%</td>
                                <td
                                    class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                    {{ $project->progress_date ?? '-' }}</td>
                                <td
                                    class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                    {{ $project->description ?? '-' }}</td>


                                <td
                                    class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                    {{ $project->start_date ?? '-' }}</td>
                                <td
                                    class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                    {{ $project->end_date ?? '-' }}</td>
                                <td
                                    class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                    {{ $project->actual_start_date ?? '-' }}</td>
                                <td
                                    class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                    {{ $project->total_pending_minutes ?? '-' }}</td>
                                <td
                                    class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                    <button onclick="showProjectModal({{ $project->id }})"
                                        class="text-blue-500 hover:underline">
                                        Lihat Detail
                                    </button>
                                </td>

                                <td class="border border-gray-300 dark:border-gray-600 p-2 text-center">
                                    <div class="flex justify-center items-center gap-2">
                                        <!-- Update Progress -->
                                        <button onclick="openEditModal(this)" data-id="{{ $project->id }}"
                                            data-code="{{ $project->project_code }}"
                                            data-name="{{ $project->project_name }}"
                                            data-progress="{{ $project->progress_percent }}"
                                            data-status="{{ $project->status_id }}"
                                            class="px-3 py-1 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-all duration-200">
                                            Update
                                        </button>

                                        <!-- Pending -->
                                        <button onclick="openPendingModal({{ $project->id }})"
                                            class="px-3 py-1 bg-yellow-500 text-white rounded-md hover:bg-yellow-600 transition-all duration-200">
                                            Pending
                                        </button>

                                        <!-- Void -->
                                        <button onclick="openVoidModal({{ $project->id }})"
                                            class="px-3 py-1 bg-red-500 text-white rounded-md hover:bg-red-600 transition-all duration-200">
                                            Void
                                        </button>
                                    </div>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ========================================================= WAITING TABLE ========================================================= --}}
        <div
            class="bg-light-eval-1 dark:bg-dark-eval-1 rounded-lg shadow-md p-4 border border-gray-200 dark:border-gray-700">
            <h3
                class="inline bg-yellow-500 px-2 py-1 to-blue-50 font-semibold text-lg mb-2 border-b border-gray-300 dark:border-gray-600 pb-1 text-light-text dark:text-dark-text">
                Project Waiting</h3>
            <div class="overflow-x-auto py-4">
                <table class="datatable min-w-full border border-gray-300 dark:border-gray-600 text-sm">
                    <thead class="bg-light-eval-2 dark:bg-dark-eval-2 text-left">
                        <tr>
                            <th
                                class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                project Code
                            </th>
                            <th
                                class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                Project Name
                            </th>
                            <th
                                class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                Request Date
                            </th>
                            <th
                                class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                Requestor
                            </th>
                            <th
                                class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                Priority
                            </th>
                            <th
                                class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                Description
                            </th>
                            <th
                                class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                Start Date
                            </th>
                            <th
                                class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                End Date
                            </th>
                            <th
                                class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text text-center align-middle">
                                Action
                            </th>
                        </tr>

                    </thead>
                    <tbody class="bg-white dark:bg-dark-eval-1">
                        @foreach ($waitingProject as $project)
                            <tr class="hover:bg-light-eval-2 dark:hover:bg-dark-eval-2">
                                <td
                                    class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                    {{ $project->project_code?? '-' }}</td>
                                <td
                                    class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                    {{ $project->project_name ?? '-' }}</td>
                                <td
                                    class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                    {{ $project->request_date ?? '-' }}</td>
                                <td
                                    class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                    {{ $project->requestor->name ?? '-' }}</td>

                                <td
                                    class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                    {{ $project->priority->priority_name }}</td>
                                <td
                                    class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                    {{ $project->description ?? '-' }}</td>
                                <td
                                    class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                    {{ $project->start_date ?? '-' }}</td>
                                <td
                                    class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                    {{ $project->end_date ?? '-' }}</td>
                                <td class="border border-gray-300 dark:border-gray-600 p-2 text-center">
                                    <div class="flex gap-2 justify-center">
                                        <button onclick="openVoidModal({{ $project->id }})"
                                            class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 transition-colors duration-200">
                                            Void
                                        </button>

                                        <form action="{{ route('project.updateProgress', $project->id) }}"
                                            method="POST">
                                            @csrf
                                            <input type="hidden" name="status_id" value="2">
                                            <button type="submit" onclick="return confirm('Apakah Anda Yakin')"
                                                class="px-3 py-1 bg-blue-600 text-white rounded">
                                                Pilih
                                            </button>

                                        </form>
                                    </div>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ========================================================= DONE TABLE ========================================================= --}}
        <div
            class="bg-light-eval-1 dark:bg-dark-eval-1 rounded-lg shadow-md p-4 border border-gray-200 dark:border-gray-700">
            <h3
                class="inline bg-green-500 px-2 py-1 font-semibold text-lg mb-2 border-b border-gray-300 dark:border-gray-600 pb-1 text-light-text dark:text-dark-text">
                Project Done</h3>
            <div class="overflow-x-auto py-4">
                <table class="datatable min-w-full border border-gray-300 dark:border-gray-600 text-sm">
                    <thead class="bg-light-eval-2 dark:bg-dark-eval-2 text-left">
                        <tr>
                            <th
                                class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                project Code
                            </th>
                            <th
                                class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                Nama
                            </th>
                            <th
                                class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                Request Date
                            </th>
                            <th
                                class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                Requestor
                            </th>


                            <th
                                class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                Priority
                            </th>
                            <th
                                class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                Description
                            </th>
                            <th
                                class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                Start Date
                            </th>
                            <th
                                class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                End Date
                            </th>
                            <th
                                class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                History
                            </th>
                            <th
                                class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text text-center align-middle">
                                Action
                            </th>
                        </tr>

                    </thead>
                    <tbody class="bg-white dark:bg-dark-eval-1">
                        @foreach ($doneProject as $project)
                            <tr class="hover:bg-light-eval-2 dark:hover:bg-dark-eval-2">
                                <td
                                    class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                    {{ $project->project_code ?? '-' }}</td>
                                <td
                                    class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                    {{ $project->project_name ?? '-' }}</td>
                                <td
                                    class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                    {{ $project->request_date ?? '-' }}</td>
                                <td
                                    class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                    {{ $project->requestor->name ?? '-' }}</td>

                                <td
                                    class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                    {{ $project->priority->priority_name }}</td>
                                <td
                                    class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                    {{ $project->description ?? '-' }}</td>
                                <td
                                    class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                    {{ $project->start_date ?? '-' }}</td>
                                <td
                                    class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                    {{ $project->end_date ?? '-' }}</td>
                                <td
                                    class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                    <button onclick="showProjectModal({{ $project->id }})"
                                        class="text-blue-500 hover:underline">
                                        Lihat Detail
                                    </button>
                                </td>
                                <td class="border border-gray-300 dark:border-gray-600 p-2 text-center">
                                    <form action="{{ route('project.updateStatus', $project->id) }}" method="POST"
                                        class="inline">
                                        @csrf
                                        <input type="hidden" name="status_id" value="2">
                                        <button
                                            class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-xs"
                                            onclick="return confirm('Apakah Anda Yakin?')">
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

        {{-- ========================================================= PENDING TABLE ========================================================= --}}
        <div
            class="bg-light-eval-1 dark:bg-dark-eval-1 rounded-lg shadow-md p-4 border border-gray-200 dark:border-gray-700">
            <h3
                class="inline bg-orange-600 px-2 py-1 font-semibold text-lg mb-2 border-b border-gray-300 dark:border-gray-600 pb-1 text-light-text dark:text-dark-text">
                Project Pending</h3>
            <div class="overflow-x-auto py-4">
                <table class="datatable min-w-full border border-gray-300 dark:border-gray-600 text-sm">
                    <thead class="bg-light-eval-2 dark:bg-dark-eval-2 text-left">
                        <tr>
                            <th
                                class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                project Code
                            </th>
                            <th
                                class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                Project Nmae
                            </th>
                            <th
                                class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                Request Date
                            </th>
                            <th
                                class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                Requestor
                            </th>
                            <th
                                class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                Priority
                            </th>
                            <th
                                class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                Progress Percent
                            </th>
                            <th
                                class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                Progress Date
                            </th>
                            <th
                                class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                Description
                            </th>
                            <th
                                class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                Start Date
                            </th>
                            <th
                                class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                End Date
                            </th>
                            <th
                                class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                History
                            </th>
                            <th
                                class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text text-center align-middle">
                                Action
                            </th>
                        </tr>

                    </thead>
                    <tbody class="bg-white dark:bg-dark-eval-1">
                        @foreach ($pendingProject as $project)
                            <tr class="hover:bg-light-eval-2 dark:hover:bg-dark-eval-2">
                                <td
                                    class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                    {{ $project->project_code ?? '-' }}</td>
                                <td
                                    class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                    {{ $project->project_name ?? '-' }}</td>
                                <td
                                    class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                    {{ $project->request_date ?? '-' }}</td>
                                <td
                                    class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                    {{ $project->requestor->name ?? '-' }}</td>
                                <td
                                    class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                    {{ $project->priority->priority_name ?? '-' }}</td>
                                <td
                                    class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                    {{ $project->progress_percent ?? '-' }}%</td>
                                <td
                                    class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                    {{ $project->progress_date ?? '-' }} </td>
                                <td
                                    class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                    {{ $project->description ?? '-' }} </td>
                                <td
                                    class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                    {{ $project->start_date ?? '-' }} </td>
                                <td
                                    class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                    {{ $project->end_date ?? '-' }} </td>
                               
                                <td
                                    class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                    <button onclick="showProjectModal({{ $project->id }})"
                                        class="text-blue-500 hover:underline">
                                        Lihat Detail
                                    </button>
                                </td>
                                </td>
                                <td class="border border-gray-300 dark:border-gray-600 p-2 text-center">
                                    <form action="{{ route('project.continueProgress', $project->id) }}"
                                        method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="status_id" value="2">
                                        <button
                                            class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-xs"
                                            onclick="return confirm('Apakah Anda Yakin?')">
                                            continue
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ========================================================= VOID TABLE ========================================================= --}}
        <div
            class="bg-light-eval-1 dark:bg-dark-eval-1 rounded-lg shadow-md p-4 border border-gray-200 dark:border-gray-700">
            <h3
                class="inline bg-red-500 px-2 py-1 font-semibold text-lg mb-2 border-b border-gray-300 dark:border-gray-600 pb-1 text-light-text dark:text-dark-text">
                Project Void</h3>
            <div class="overflow-x-auto py-4">
                <table class="datatable min-w-full border border-gray-300 dark:border-gray-600 text-sm">
                    <thead class="bg-light-eval-2 dark:bg-dark-eval-2 text-left">
                        <tr>
                            <th
                                class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                project Code
                            </th>
                            <th
                                class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                Nama
                            </th>
                            <th
                                class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                Request Date
                            </th>
                            <th
                                class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                Requestor
                            </th>


                            <th
                                class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                Priority
                            </th>
                            <th
                                class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                Description
                            </th>
                            <th
                                class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                notes
                            </th>
                            <th
                                class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                Start Date
                            </th>
                            <th
                                class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                End Date
                            </th>

                        </tr>

                    </thead>
                    <tbody class="bg-white dark:bg-dark-eval-1">
                        @foreach ($voidProject as $project)
                            <tr class="hover:bg-light-eval-2 dark:hover:bg-dark-eval-2">
                                <td
                                    class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                    {{ $project->project_code ?? '-' }}</td>
                                <td
                                    class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                    {{ $project->project_name ?? '-' }}</td>
                                <td
                                    class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                    {{ $project->request_date ?? '-' }}</td>
                                <td
                                    class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                    {{ $project->requestor->name ?? '-' }}</td>

                                <td
                                    class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                    {{ $project->priority->priority_name }}</td>
                                <td
                                    class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                    {{ $project->description ?? '-' }}</td>
                                <td
                                    class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                    {{ $project->notes ?? '-' }}</td>
                                <td
                                    class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                    {{ $project->start_date ?? '-' }}</td>
                                <td
                                    class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                    {{ $project->end_date ?? '-' }}</td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <x-modal-table name="project-detail" title="Detail Project">
        <div id="projectModalBody">
            <div class="p-6 text-center text-gray-500">
                <svg class="animate-spin h-5 w-5 mx-auto mb-2 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                    fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                        stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                </svg>
                Loading...
            </div>
        </div>
    </x-modal-table>

    <script>
        function showProjectModal(projectId) {
            // tampilkan modal dulu (biar ada efek transisi)
            window.dispatchEvent(new CustomEvent('open-modal', {
                detail: 'project-detail'
            }));

            // tampilkan indikator loading
            const modalBody = document.getElementById('projectModalBody');
            modalBody.innerHTML = `
        <div class="p-6 text-center text-gray-500">
            <svg class="animate-spin h-5 w-5 mx-auto mb-2 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
            </svg>
            Loading...
        </div>
    `;

            // ambil konten dari route history
            fetch(`/projects/${projectId}/history`)
                .then(response => {
                    if (!response.ok) throw new Error('Gagal memuat data');
                    return response.text();
                })
                .then(html => {
                    modalBody.innerHTML = html;
                })
                .catch(err => {
                    modalBody.innerHTML = `
                <div class="p-6 text-center text-red-500">
                    ⚠️ Gagal memuat data project (${err.message})
                </div>
            `;
                });
        }
    </script>


    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

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
    color: #111827; /* teks abu */
    padding: 2rem 0; /* beri jarak agar tidak terlalu mepet */
}
.dark table.dataTable tbody td.dataTables_empty {
    color: #d1d5db; /* teks abu terang untuk dark mode */
}

</style>

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
                    searchPlaceholder: "Search Data...",
                        emptyTable: "No data available in this table",
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
                    $('div.dataTables_length select').css('width', '3.5rem');

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


    <script>
        $(document).ready(function() {
            const isDark = document.documentElement.classList.contains('dark');

            // =================== Developer Dropdown ===================
            $(document).on('click', '#selected-developers', function() {
                $('#developer-dropdown').toggleClass('hidden');
                $('#dropdown-icon').toggleClass('rotate-180');
            });

            $(document).on('click', function(e) {
                if (!$(e.target).closest('#selected-developers, #developer-dropdown').length) {
                    $('#developer-dropdown').addClass('hidden');
                    $('#dropdown-icon').removeClass('rotate-180');
                }
            });

            $(document).on('change', '.dev-checkbox', function() {
                const selected = [];
                $('.dev-checkbox:checked').each(function() {
                    selected.push($(this).val());
                });
                const result = selected.join(' | ') || 'Pilih Developer...';
                $('#selected-text').text(result);
                $('#developer_name').val(selected.join(' | '));
            });

            // =================== Edit Modal ===================
            window.openEditModal = function(button) {
                const projectId = $(button).data('id');
                const projectCode = $(button).data('code');
                const projectName = $(button).data('name');
                const progress = $(button).data('progress');
                const statusId = $(button).data('status');
                const developerName = $(button).data('developer');
                const memo = $(button).data('memo');

                $('#editProgressForm')[0].reset();
                $('.dev-checkbox').prop('checked', false);
                $('#dropdown-icon').removeClass('rotate-180');
                $('#developer-dropdown').addClass('hidden');

                $('#editProgressForm').attr('action', `/project/${projectId}`);
                $('#modal_project_code').val(projectCode);
                $('#modal_project_name').val(projectName);
                $('#modal_progress_percent').val(progress);
                $('#modal_status_id').val(statusId);
                $('#modal_memo').val(memo);

                if (developerName) {
                    developerName.split(' | ').forEach(devName => {
                        $(`.dev-checkbox[value="${devName.trim()}"]`).prop('checked', true);
                    });
                    $('#selected-text').text(developerName);
                    $('#developer_name').val(developerName);
                } else {
                    $('#selected-text').text('Pilih Developer...');
                    $('#developer_name').val('');
                }

                const now = new Date();
                const datetime =
                    `${now.getFullYear()}-${String(now.getMonth()+1).padStart(2,'0')}-${String(now.getDate()).padStart(2,'0')}T${String(now.getHours()).padStart(2,'0')}:${String(now.getMinutes()).padStart(2,'0')}`;
                $('#modal_progress_date').val(datetime);

                openModal('editProgressModal');
            };

            // =================== Pending Modal ===================
            window.openPendingModal = function(projectId) {
                $('#pending_project_id').val(projectId);
                $('#pendingForm').attr('action', '/project/' + projectId + '/pending');
                openModal('pendingModal');
            };

            // =================== User Search ===================
            const $input = $('#user-search');
            const $results = $('#search-results');
            const $hidden = $('#user-id');

            if ($input.length) {
                $input.on('focus', () => $results.show());
                $input.on('input', function() {
                    const val = $(this).val().toLowerCase();
                    let visible = false;
                    $results.children('li').each(function() {
                        const match = $(this).text().toLowerCase().includes(val);
                        $(this).toggle(match);
                        if (match) visible = true;
                    });
                    $results.toggle(visible);
                });

                $results.on('click', 'li', function() {
                    $input.val($(this).text());
                    $hidden.val($(this).data('id'));
                    $results.hide();
                });

                $(document).on('click', (e) => {
                    if (!$(e.target).closest('#user-search, #search-results').length) {
                        $results.hide();
                    }
                });
            }

            // Tambahkan di dalam $(document).ready() bersama function lainnya

            // =================== Void Modal ===================
            window.openVoidModal = function(projectId) {
                // Reset form
                $('#voidForm')[0].reset();

                // Set action dengan route yang benar
                const actionUrl = "{{ route('project.updateStatus', ':id') }}".replace(':id', projectId);
                $('#voidForm').attr('action', actionUrl);

                // Buka modal
                openModal('voidModal');
            };

            // =================== Dark Mode Toggle Observer ===================
            const observer = new MutationObserver(() => {
                const dark = document.documentElement.classList.contains('dark');
                // Update DataTable inputs, selects, pagination, info text
                $('div.dataTables_filter input, div.dataTables_length select').removeClass().addClass(
                    `rounded-md border px-2 py-1 text-sm transition ${dark ? 'bg-gray-800 border-gray-700 text-gray-100 placeholder-gray-400' : 'bg-white border-gray-300 text-gray-800 placeholder-gray-400'}`
                );
                $('div.dataTables_paginate a').each(function() {
                    $(this).removeClass().addClass(
                        `px-3 py-1 border rounded-md mx-1 text-sm font-medium transition ${dark ? 'border-gray-700 text-gray-100 hover:bg-gray-700' : 'border-gray-300 text-gray-800 hover:bg-gray-100'}`
                    );
                });
                $('div.dataTables_info').removeClass().addClass(dark ? 'text-gray-400 text-sm mt-2' :
                    'text-gray-600 text-sm mt-2');
            });
            observer.observe(document.documentElement, {
                attributes: true,
                attributeFilter: ['class']
            });
        });
    </script>


    <x-modal-table name="project-history-1" title="Project History" max-width="7xl">
        @include('project.history')
    </x-modal-table>

    {{-- ============================================ MODAL FORM PENDING ============================================ --}}
    <x-modal-form id="pendingModal" title="Pending" size="max-w-md">
        <form id="pendingForm" method="POST">
            @csrf
            <input type="hidden" name="id_project_header" id="pending_project_id">


            {{-- Reason --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Reason <span class="text-red-500">*</span>
                </label>
                <input type="text" name="reason" required placeholder="Tulis alasan pending..."
                    class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm px-3 py-2
                            bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100
                            focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            </div>

            {{-- Tombol --}}
            <div class="flex justify-end">
                <button type="button" onclick="closeModal('pendingModal')"
                    class="px-4 py-2 bg-red-800 text-white rounded mr-2 hover:bg-red-900">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Save</button>
            </div>
        </form>
    </x-modal-form>

    {{-- ============================================ MODAL FORM EDIT ============================================ --}}
    <x-modal-form id="editProgressModal" title="Update Progress Project" size="max-w-3xl">
        <form id="editProgressForm" method="POST">
            @csrf
            @method('PUT')

            {{-- Project Code (readonly) --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Project Code
                </label>
                <input type="text" id="modal_project_code" readonly
                    class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm px-3 py-2 
                bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100">
            </div>

            {{-- Project Name (readonly) --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Project Name
                </label>
                <input type="text" id="modal_project_name" readonly
                    class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm px-3 py-2 
                bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100">
            </div>

            {{-- Developer Name --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Developer Name
                </label>

                <div id="selected-developers"
                    class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 cursor-pointer flex justify-between items-center"
                    onclick="toggleDropdown()">
                    <span id="selected-text" class="truncate">Pilih Developer...</span>
                    <svg id="dropdown-icon" class="w-4 h-4 transform transition-transform duration-200"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>

                <input type="hidden" name="developer_name" id="developer_name">

                <div id="developer-dropdown"
                    class="hidden mt-1 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-800 shadow-lg max-h-48 overflow-y-auto p-2">
                    @foreach ($developers as $dev)
                        <label
                            class="flex items-center space-x-2 py-1 px-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded cursor-pointer">
                            <input type="checkbox" value="{{ $dev->name }}" class="dev-checkbox">
                            <span>{{ $dev->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            {{-- Progress Date --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Progress Date
                </label>
                <input type="datetime-local" name="progress_date" id="modal_progress_date"
                    class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm px-3 py-2 
                bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
            </div>

            {{-- Progress Percent --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Progress Percent <span class="text-red-500">*</span>
                </label>
                <input type="number" name="progress_percent" id="modal_progress_percent" min="0"
                    max="100" step="1"
                    class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm px-3 py-2 
        bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                    required>
            </div>




            {{-- Memo --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Memo
                </label>
                <textarea name="memo" id="modal_memo" rows="3"
                    class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm px-3 py-2 
                bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"></textarea>
            </div>

            {{-- Status --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Status <span class="text-red-500">*</span>
                </label>
                <select name="status_id" id="modal_status_id" required
                    class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 
                bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    <option value="">-- Pilih Status --</option>
                    @foreach ($statuses as $status)
                        @if (in_array($status->id, [2, 3]))
                            <option value="{{ $status->id }}">{{ $status->status_name }}</option>
                        @endif
                    @endforeach

                </select>
            </div>

            {{-- Tombol --}}
            <div class="flex justify-end">
                <button type="button" onclick="closeModal('editProgressModal')"
                    class="px-4 py-2 bg-red-800 rounded mr-2 text-white">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Save
                </button>
            </div>
        </form>

    </x-modal-form>

    {{-- ============================================ MODAL FORM TAMBAH ============================================ --}}
    <x-modal-form id="projectModal" title="Create Project" size="max-w-4xl">
        <form action="{{ route('project.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="from" value="user">

            {{-- Project Code --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Project Code <span class="text-red-500">*</span>
                </label>
                <input type="text" name="project_code" value="{{ $generateticket }}" readonly
                    class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm px-3 py-2 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100">
            </div>

            {{-- Project Name --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Project Name <span class="text-red-500">*</span>
                </label>
                <input type="text" name="project_name" value=""
                    class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
            </div>

            {{-- User --}}
            <div class="mb-4 relative">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Pilih Requestor <span class="text-red-500">*</span>
                </label>
                <input type="text" id="user-search" placeholder="Cari user..." required
                    class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                <input type="hidden" name="requestor_id" id="user-id" required>
                <ul id="search-results"
                    class="hidden absolute z-50 w-full border border-gray-300 dark:border-gray-600 rounded-md mt-1 overflow-y-auto bg-white dark:bg-gray-800 shadow-lg max-h-32">
                    @foreach ($users as $user)
                        <li class="px-3 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer border-b border-gray-100 dark:border-gray-700 text-gray-900 dark:text-gray-100"
                            data-id="{{ $user->id }}">{{ $user->name }}</li>
                    @endforeach
                </ul>
            </div>


            {{-- Priority --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Priority <span class="text-red-500">*</span>
                </label>
                <select name="priority_id" required
                    class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    <option value="" hidden>-- Pilih Priority --</option>
                    @foreach ($priorities as $prt)
                        <option value="{{ $prt->id }}" {{ old('priority_id') == $prt->id ? 'selected' : '' }}>
                            {{ $prt->priority_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Status (hidden) --}}
            <input type="hidden" name="status_id" value="1">

            {{-- Description --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Description <span class="text-red-500">*</span>
                </label>
                <input type="text" name="description" value=""
                    class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
            </div>

            {{-- Start & End Date --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Start Date & Time <span class="text-red-500">*</span>
                    </label>
                    <input type="datetime-local" name="start_date"
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        End Date & Time <span class="text-red-500">*</span>
                    </label>
                    <input type="datetime-local" name="end_date"
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                </div>
            </div>



            {{-- Tombol Submit --}}
            <div class="flex justify-end">
                <button type="button" onclick="closeModal('projectModal')"
                    class="px-4 py-2 bg-red-800 rounded mr-2">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Save</button>
            </div>
        </form>
    </x-modal-form>

    {{-- ============================================ MODAL FORM VOID ============================================ --}}
    <x-modal-form id="voidModal" title="Void Project" size="max-w-md">
        <form id="voidForm" method="POST">
            @csrf
            <input type="hidden" name="status_id" value="4">

            {{-- Notes --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Notes <span class="text-red-500">*</span>
                </label>
                <textarea name="notes" required placeholder="Tulis alasan void..." rows="4"
                    class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm px-3 py-2
                    bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100
                    focus:outline-none focus:ring-red-500 focus:border-red-500 resize-none"></textarea>
            </div>

            {{-- Tombol --}}
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeModal('voidModal')"
                    class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 transition-colors">
                    Cancel
                </button>
                <button type="submit"
                    class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition-colors">
                    Save
                </button>
            </div>
        </form>
    </x-modal-form>
</x-app-layout>
