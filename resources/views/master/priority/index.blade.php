<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
                       <h2 class="font-semibold text-xl text-dark-eval-1 dark:text-light-eval-1 leading-tight">

                {{ __('Kelola Priority') }}
            </h2>
            <a href="{{ route('priority.create') }}"
               class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow">
                Tambah Priority
            </a>
        </div>
    </x-slot>

    <div class="p-6 space-y-6">

        @if (session('success'))
            <div class="bg-green-800 border border-green-700 text-green-200 px-4 py-3 rounded relative mb-4"
                 role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-800 border border-red-700 text-red-200 px-4 py-3 rounded relative mb-4"
                 role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <div class="bg-light-eval-1 dark:bg-dark-eval-1 rounded-lg shadow-md p-4 border border-gray-200 dark:border-gray-700">
            <h3 class="font-semibold text-lg mb-2 border-b border-gray-300 dark:border-gray-600 pb-1 text-light-text dark:text-dark-text">
                Daftar Priority
            </h3>

            <div class="overflow-x-auto">
                <table class="datatable min-w-full border border-gray-300 dark:border-gray-600 text-sm">
                    <thead class="bg-light-eval-2 dark:bg-dark-eval-2 text-left">
                        <tr>
                            <th class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">No</th>
                            <th class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">Nama Priority</th>
                            <th class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-dark-eval-1">
                        @foreach ($priorities as $index => $priority)
                            <tr class="hover:bg-light-eval-2 dark:hover:bg-dark-eval-2">
                                <td class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                    {{ $index + 1 }}
                                </td>
                                <td class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                    {{ $priority->priority_name }}
                                </td>
                                <td class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('priority.edit', $priority) }}"
                                           class="border border-gray-300 dark:border-gray-600 px-3 py-1 text-light-text dark:text-dark-text rounded">
                                            Edit
                                        </a>
                                        <form action="{{ route('priority.destroy', $priority) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus priority ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="px-3 py-1 text-red-400 hover:text-red-300 border border-gray-300 dark:border-gray-600 rounded">
                                                Hapus
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

    </div>

    {{-- DataTables CSS --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    {{-- jQuery --}}
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    {{-- DataTables JS --}}
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

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
                    $('div.dataTables_length select').css('width', '3.5rem');

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
