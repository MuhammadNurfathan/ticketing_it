<x-app-layout>
    {{-- ========================================================= HEADER ========================================================= --}}
    <x-slot name="header">
        @auth
            @if (Auth::user()->role_id != 3)
                <div class="flex justify-between items-center">
                    <h2 class="font-bold text-2xl text-light-text dark:text-dark-text">Project</h2>
                    <button onclick="openModal('projectModal')"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-green-700">
                        Tambah Project
                    </button>
                </div>
            @endif
        @endauth
    </x-slot>



    <div class="p-6 space-y-6">
        {{-- FILTER DATE UNTUK STATUS DONE  --}}
        <form method="GET" action="{{ route('project.index') }}"
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
        {{-- ========================================================= STATISTIK CARDS ========================================================= --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 ">
            @php
                $statusColors = [
                    'Waiting' => 'blue',
                    'In Progress' => 'purple',
                    'Done' => 'green',
                    'Void' => 'red',
                    'Pending' => 'yellow',
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

        {{-- ========================================================= PROGRESS TABLE ========================================================= --}}
        <div
            class="bg-light-eval-1 dark:bg-dark-eval-1 rounded-lg shadow-md p-4 border border-gray-200 dark:border-gray-700 p-6 space-y-6">
            <h3
                class="font-semibold text-lg mb-2 border-b border-gray-300 dark:border-gray-600 pb-1 text-light-text dark:text-dark-text">
                Project In Progress</h3>
            <div class="overflow-x-auto">
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
                                Requestor
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
                                Progress Percent
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
                                    {{ $project->project_code }}</td>
                                <td
                                    class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                    {{ $project->project_name ?? '-' }}</td>
                                <td
                                    class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                    {{ $project->requestor->name ?? '-' }}</td>
                               
                                <td
                                    class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                    {{ $project->start_date ?? '-' }}</td>
                                <td
                                    class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                    {{ $project->end_date ?? '-' }}</td>
                                <td
                                    class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                    {{ $project->progress_percent ?? '-' }} %</td>

                                <td class="border border-gray-300 dark:border-gray-600 p-2 text-center space-x-1">
                                    <button type="button"
                                        class="doneBtn bg-green-600 hover:bg-green-700 text-white px-2 py-1 rounded text-xs"
                                        data-start="{{ $project->start_date ?? '-' }}">
                                        update
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

          

            {{-- ========================================================= WAITING TABLE ========================================================= --}}
            <div
                class="bg-light-eval-1 dark:bg-dark-eval-1 rounded-lg shadow-md p-4 border border-gray-200 dark:border-gray-700">
                <h3
                    class="font-semibold text-lg mb-2 border-b border-gray-300 dark:border-gray-600 pb-1 text-light-text dark:text-dark-text">
                    Project Waiting</h3>
                <div class="overflow-x-auto">
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
                                        {{ $project->project_code }}</td>
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
                                        {{ $project->priority_id }}</td>
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
                                        <form action="{{ route('project.updateStatus', $project->id) }}"
                                            method="POST" class="inline">
                                            @csrf
                                            <input type="hidden" name="status_id" value="4">
                                            <button
                                                class="bg-gray-500 hover:bg-gray-600 text-white px-2 py-1 rounded text-xs">Void</button>
                                        </form>

                                        <form action="{{ route('project.updateProgress', $project->id) }}"
                                            method="POST">
                                            @csrf
                                            <input type="hidden" name="status_id" value="2">
                                            <button type="submit"
                                                class="px-3 py-1 bg-blue-600 text-white rounded">Pilih</button>
                                        </form>


                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- ========================================================= DONE TABLE ========================================================= --}}
            {{-- <div
                class="bg-light-eval-1 dark:bg-dark-eval-1 rounded-lg shadow-md p-4 border border-gray-200 dark:border-gray-700">
                <h3
                    class="font-semibold text-lg mb-2 border-b border-gray-300 dark:border-gray-600 pb-1 text-light-text dark:text-dark-text">
                    Project Done</h3>
                <div class="overflow-x-auto">
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
                                    Developer
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
                            @foreach ($doneProject as $project)
                                <tr class="hover:bg-light-eval-2 dark:hover:bg-dark-eval-2">
                                    <td
                                        class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                        {{ $project->project_code }}</td>
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
                                        {{ $project->developer->name ?? '-' }}</td>
                                    <td
                                        class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                        {{ $project->priority_id }}</td>
                                    <td
                                        class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                        {{ $project->description ?? '-' }}</td>
                                    <td
                                        class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                        {{ $project->start_date ?? '-' }}</td>
                                    <td
                                        class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                        {{ $project->end_date ?? '-' }}</td>
                                    <td class="border border-gray-300 dark:border-gray-600 p-2 text-center space-x-1">
                                        <button type="button"
                                            class="doneBtn bg-green-600 hover:bg-green-700 text-white px-2 py-1 rounded text-xs"
                                            data-start="{{ $project->start_date ?? '-' }}">
                                            Done
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div> --}}

              {{-- ========================================================= PENDING TABLE ========================================================= --}}
            {{-- <div
                class="bg-light-eval-1 dark:bg-dark-eval-1 rounded-lg shadow-md p-4 border border-gray-200 dark:border-gray-700">
                <h3
                    class="font-semibold text-lg mb-2 border-b border-gray-300 dark:border-gray-600 pb-1 text-light-text dark:text-dark-text">
                    Project Pending</h3>
                <div class="overflow-x-auto">
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
                                    Requestor
                                </th>
                                <th
                                    class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                    Developer
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
                                    Progress Percent
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
                                        {{ $project->project_code }}</td>
                                    <td
                                        class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                        {{ $project->project_name ?? '-' }}</td>
                                    <td
                                        class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                        {{ $project->requestor->name ?? '-' }}</td>
                                    <td
                                        class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                        {{ $project->developer->name ?? '-' }}</td>
                                    <td
                                        class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                        {{ $project->start_date ?? '-' }}</td>
                                    <td
                                        class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                        {{ $project->end_date ?? '-' }}</td>
                                    <td
                                        class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                        {{ $project->progress_percent ?? '-' }} %</td>

                                    <td class="border border-gray-300 dark:border-gray-600 p-2 text-center space-x-1">
                                        <button type="button"
                                            class="doneBtn bg-green-600 hover:bg-green-700 text-white px-2 py-1 rounded text-xs"
                                            data-start="{{ $project->start_date ?? '-' }}">
                                            Done
                                        </button>

                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div> --}}

            {{-- ========================================================= VOID TABLE ========================================================= --}}
            {{-- <div
                class="bg-light-eval-1 dark:bg-dark-eval-1 rounded-lg shadow-md p-4 border border-gray-200 dark:border-gray-700">
                <h3
                    class="font-semibold text-lg mb-2 border-b border-gray-300 dark:border-gray-600 pb-1 text-light-text dark:text-dark-text">
                    Project Void</h3>
                <div class="overflow-x-auto">
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
                                    Developer
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
                            @foreach ($voidProject as $project)
                                <tr class="hover:bg-light-eval-2 dark:hover:bg-dark-eval-2">
                                    <td
                                        class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                        {{ $project->project_code }}</td>
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
                                        {{ $project->developer->name ?? '-' }}</td>
                                    <td
                                        class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                        {{ $project->priority_id }}</td>
                                    <td
                                        class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                        {{ $project->description ?? '-' }}</td>
                                    <td
                                        class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                        {{ $project->start_date ?? '-' }}</td>
                                    <td
                                        class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                        {{ $project->end_date ?? '-' }}</td>
                                    <td class="border border-gray-300 dark:border-gray-600 p-2 text-center space-x-1">
                                        <button type="button"
                                            class="doneBtn bg-green-600 hover:bg-green-700 text-white px-2 py-1 rounded text-xs"
                                            data-start="{{ $project->start_date ?? '-' }}">
                                            Done
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div> --}}
        </div>

        {{-- ============================================ Modal Form ============================================ --}}
        <x-modal-form id="projectModal" title="Tambah Project" size="max-w-4xl">
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
                        Pilih User <span class="text-red-500">*</span>
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
                            <option value="{{ $prt->id }}"
                                {{ old('priority_id') == $prt->id ? 'selected' : '' }}>
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

                {{-- Notes --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Notes <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="notes" value=""
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                </div>

                {{-- Tombol Submit --}}
                <div class="flex justify-end">
                    <button type="button" onclick="closeModal('projectModal')"
                        class="px-4 py-2 bg-red-800 rounded mr-2">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Simpan
                        Project</button>
                </div>
            </form>

            {{-- Script untuk User Search --}}
            <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
            <script>
                $(function() {
                    const $input = $('#user-search');
                    const $results = $('#search-results');
                    const $hidden = $('#user-id');

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
                });
            </script>

        </x-modal-form>
</x-app-layout>
