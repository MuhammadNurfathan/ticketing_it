<x-app-layout>

    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 leading-tight">
            {{ __('Dashboard Tickets User') }}
        </h2>
    </x-slot>



    <div class="p-6 space-y-6">

       
        @if (!$hasDoneTicket)
            <button type="button" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                 <a href="{{ route('DashboardTicketsUser.create') }}"
                      >
                        Tambah Ticket
                    </a>
            </button>
        @endif

        <div
            class="bg-light-eval-1 dark:bg-dark-eval-1 rounded-lg shadow-md p-4 border border-gray-200 dark:border-gray-700">
            <h3
                class="font-semibold text-lg mb-2 border-b border-gray-300 dark:border-gray-600 pb-1 text-light-text dark:text-dark-text">
                My Tickets</h3>
            <div class="flex gap-4 text-sm mb-4 text-light-text-secondary dark:text-dark-text-secondary">
                <div>Total: {{ $myTicket->count() }}</div>
            </div>
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
                                class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                Tanggal Req</th>
                            <th
                                class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                Image</th>


                            <th
                                class="bord     er border-gray-300 dark:border-gray-600 p-2 text-center text-light-text dark:text-dark-text text-center align-middle">
                                Action</th>

                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-dark-eval-1">
                        @foreach ($myTicket as $ticket)
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
                                    {{ $ticket->status->status_name }}</td>
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


                                @if ($ticket->status_id == 3)
                                    <td class="border border-gray-300 dark:border-gray-600 p-2 text-center">
                                        <a href="{{ route('DashboardTicketsAdmin.edit', $ticket->id) }}"
                                            class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-xs inline-block">
                                            Feedback
                                        </a>
                                    </td>
                                @else
                                    <td class="border border-gray-300 dark:border-gray-600 p-2 text-center">
                                        -
                                    </td>
                                @endif
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
    <script>
        // JS
        document.querySelectorAll('.doneBtn').forEach(btn => {
            btn.addEventListener('click', function() {
                const modal = this.nextElementSibling;
                modal.classList.remove('hidden');

                const start = new Date(this.dataset.start);
                const timeInput = modal.querySelector('.timeInput');
                const solutionInput = modal.querySelector('.solutionInput');
                const notesContainer = modal.querySelector('.notesContainer');
                const notesInput = modal.querySelector('.notesInput');
                const manualCheckbox = modal.querySelector('.manualCheckbox');

                // Auto hitung time spent kalau ada start & end (opsional)
                function autoTime() {
                    if (!manualCheckbox.checked && start) {
                        const now = new Date();
                        const diff = Math.floor((now - start) / (1000 * 60));
                        timeInput.value = diff > 0 ? diff : 0;
                    }
                }
                autoTime();

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
                        autoTime();
                    }
                });

                // Cancel
                modal.querySelector('.cancelBtn').onclick = () => modal.classList.add('hidden');

                // Save
                modal.querySelector('.saveBtn').onclick = () => {
                    document.querySelector('.doneForm .hiddenTimeSpent').value = timeInput.value;
                    document.querySelector('.doneForm .hiddenSolution').value = solutionInput.value;
                    document.querySelector('.doneForm .hiddenNotes').value = notesInput.value;
                    document.querySelector('.doneForm').submit();
                }
            });
        });
    </script>
    <script>
        document.querySelectorAll('.saveBtn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.stopImmediatePropagation(); // hentikan semua event lain
                e.preventDefault(); // cegah submit otomatis

                const modal = button.closest('.timeSpentModal');
                const solutionInput = modal.querySelector('.solutionInput');
                const hiddenSolution = document.querySelector('.hiddenSolution');
                const doneForm = document.querySelector('.doneForm');

                const solution = solutionInput.value.trim();

                if (!solution) {
                    alert('Field Solution wajib diisi!');
                    solutionInput.focus();
                    return false; // pastikan berhenti total
                }

                // kalau lolos validasi baru submit
                hiddenSolution.value = solution;
                doneForm.submit();
            });
        });
    </script>

</x-app-layout>
