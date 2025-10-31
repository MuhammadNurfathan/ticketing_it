<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-dark-bg leading-tight dark:text-white">
                {{ __('List Feedback') }}
            </h2>
        </div>
    </x-slot>

    <div class="p-6 space-y-6">

        {{-- Alert Success --}}
        @if (session('success'))
            <div class="bg-green-800 border border-green-700 text-green-200 px-4 py-3 rounded relative mb-4"
                role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        {{-- Alert Error --}}
        @if (session('error'))
            <div class="bg-red-800 border border-red-700 text-red-200 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <div
            class="bg-light-eval-1 dark:bg-dark-eval-1 rounded-lg shadow-md p-4 border border-gray-200 dark:border-gray-700">
            <div class="overflow-x-auto">
                <table class="datatable min-w-full border border-gray-300 dark:border-gray-600 text-sm">
                    <div class="flex gap-4 text-sm mb-4 text-gray-600 dark:text-gray-400">
                        <div>Total: {{ $feedback->count() }} | Average Rate: {{ $Rate}} ⭐</div>
                    </div>
                    <thead class="bg-light-eval-2 dark:bg-dark-eval-2 text-left">
                        <tr>

                            <th
                                class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                Ticket</th>
                            <th
                                class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                Requestor</th>
                            <th
                                class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                IT Support</th>
                            <th
                                class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                Deskripsi</th>
                            <th
                                class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                Rating</th>

                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-dark-eval-1">
                        @foreach ($feedback as $index => $f)
                            <tr class="hover:bg-light-eval-2 dark:hover:bg-dark-eval-2">
                                <td
                                    class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                    {{ $f->ticket->ticket_code ?? '-' }}
                                </td>
                                <td
                                    class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                    {{ $f->ticket->user->name ?? '-' }}
                                </td>
                                <td
                                    class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                    {{ $f->ticket->support->name ?? '-' }}
                                </td>
                                <td
                                    class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                    {{ $f->description ?? '-' }}
                                </td>
                                <td
                                    class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                    {{ $f->rating ?? '-' }}/5
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    {{-- DataTables CSS --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">

    {{-- jQuery --}}
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

    {{-- DataTables JS --}}
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
</x-app-layout>
