<x-app-layout>
    {{-- ========================================================= HEADER ========================================================= --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Project') }}
        </h2>
    </x-slot>

    <div class="p-6 space-y-6">

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
        {{-- ========================================================= PENDING TABLE ========================================================= --}}
        <div
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
    </div>

    {{-- ========================================================= DONE TABLE ========================================================= --}}
    <div
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
    </div>

    {{-- ========================================================= VOID TABLE ========================================================= --}}
    <div
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
    </div>

</div>
</x-app-layout>
