<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard Tickets User') }}
        </h2>
    </x-slot>

    <div class="p-4 sm:p-6 space-y-4 sm:space-y-6 max-w-full overflow-hidden">

        @if (session('error'))
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const isDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

                    Swal.fire({
                        icon: 'warning',
                        title: 'Tidak bisa membuat tiket baru!',
                        text: '{{ session('error') }}',
                        confirmButtonColor: isDark ? '#3b82f6' : '#2563eb',
                        background: isDark ? '#1f2937' : '#ffffff',
                        color: isDark ? '#f3f4f6' : '#111827',
                        iconColor: isDark ? '#facc15' : '#eab308',
                        customClass: {
                            popup: 'rounded-2xl shadow-lg',
                            confirmButton: 'font-semibold px-4 py-2 rounded'
                        }
                    });
                });
            </script>
        @endif

        {{-- BUTTON TAMBAH TICKET --}}
        @if (!$hasDoneTicket)
            <button onclick="openModal('ticketModal')"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                Buat Ticket Baru
            </button>
        @endif

        {{-- Panggil modal --}}
        <x-modal-form id="ticketModal" title="Buat Ticket Baru" size="max-w-4xl">
            {{-- Masukkan konten form di sini --}}
            <form action="{{ route('DashboardTicketsAdmin.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="from" value="user">

                {{-- Ticket Code --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Ticket Code <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="ticket_code" value="{{ $generateticket }}" readonly
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm px-3 py-2 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                </div>

                {{-- User --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">User</label>
                    <input type="text" value="{{ Auth::user()->name }}" readonly
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm px-3 py-2 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                    <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                </div>

                {{-- Hidden Status --}}
                <input type="hidden" name="status_id" value="1">

                {{-- Category --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Category <span class="text-red-500">*</span>
                    </label>
                    <select name="problem_category_id"
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                        <option hidden>-- Pilih Category --</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}"
                                {{ old('problem_category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->problem_category_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Problem --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Problem <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="problem" placeholder="Masukkan Kendala..."
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                </div>

                {{-- Upload Media --}}
                <div class="mb-6">
                    <label for="media" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Upload Gambar / Video (Max 10 MB)
                    </label>
                    <input type="file" name="image" id="media" accept=".jpg,.jpeg,.png,.mp4"
                        class="mt-1 block w-full text-sm text-gray-900 dark:text-gray-100
                       file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0
                       file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700
                       hover:file:bg-blue-100 dark:file:bg-gray-700 dark:file:text-gray-200
                       dark:hover:file:bg-gray-600">
                    <div id="preview-container" class="mt-4 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3"></div>
                </div>

                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                    Simpan Ticket
                </button>
            </form>
        </x-modal-form>


        {{-- MY TICKET SECTION --}}
        <div
            class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 border border-gray-200 dark:border-gray-700 mt-4">
            <h3
                class="font-semibold text-lg mb-2 border-b border-gray-300 dark:border-gray-600 pb-1 text-gray-800 dark:text-gray-200">
                My Tickets
            </h3>

            {{-- DESKTOP VIEW (TABLE) --}}
            <div class="hidden md:block overflow-x-auto">
                <table class="datatable min-w-full border border-gray-300 dark:border-gray-600 text-sm">
                    <thead class="bg-gray-100 dark:bg-gray-700 text-left text-gray-700 dark:text-gray-200">
                        <tr>
                            <th class="border border-gray-300 dark:border-gray-600 p-2">Ticket Code</th>
                            <th class="border border-gray-300 dark:border-gray-600 p-2">Nama</th>
                            <th class="border border-gray-300 dark:border-gray-600 p-2">Kategori</th>
                            <th class="border border-gray-300 dark:border-gray-600 p-2">Masalah</th>
                            <th class="border border-gray-300 dark:border-gray-600 p-2">Status</th>
                            <th class="border border-gray-300 dark:border-gray-600 p-2">Tanggal Req</th>
                            <th class="border border-gray-300 dark:border-gray-600 p-2">Start Date</th>
                            <th class="border border-gray-300 dark:border-gray-600 p-2">End Date</th>
                            <th class="border border-gray-300 dark:border-gray-600 p-2">Solution</th>
                            <th class="border border-gray-300 dark:border-gray-600 p-2">Feedback</th>
                            <th class="border border-gray-300 dark:border-gray-600 p-2">Image</th>
                            <th class="border border-gray-300 dark:border-gray-600 p-2 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-200">
                        @php
                            // Urutkan: yang ada feedback button (status_id == 3) di atas
                            $sortedTickets = $myTicket->sortByDesc(function ($ticket) {
                                return $ticket->status_id == 3 ? 1 : 0;
                            });
                        @endphp
                        @foreach ($sortedTickets as $ticket)
                            <tr
                                class="hover:bg-gray-50 dark:hover:bg-gray-700 {{ $ticket->status_id == 3 ? 'bg-green-50 dark:bg-green-900/10 border-l-4 border-l-green-500' : '' }}">
                                <td class="border border-gray-300 dark:border-gray-600 p-2">{{ $ticket->ticket_code }}
                                </td>
                                <td class="border border-gray-300 dark:border-gray-600 p-2">
                                    <span title="{{ $ticket->user->name ?? '-' }}">
                                        {{ $ticket->user->name ?? '-' }}
                                    </span>
                                </td>
                                <td class="border border-gray-300 dark:border-gray-600 p-2">
                                    <span title="{{ $ticket->problemCategory?->problem_category_name ?? '-' }}">
                                        {{ $ticket->problemCategory?->problem_category_name ?? '-' }}
                                    </span>
                                </td>
                                <td class="border border-gray-300 dark:border-gray-600 p-2">
                                    <span title="{{ $ticket->problem }}">
                                        {{ $ticket->problem }}
                                    </span>
                                </td>
                                <td class="border border-gray-300 dark:border-gray-600 p-2">
                                    <span title="{{ $ticket->status->status_name }}">
                                        {{ $ticket->status->status_name }}
                                    </span>
                                </td>
                                <td class="border border-gray-300 dark:border-gray-600 p-2">
                                    {{ $ticket->request_date ?? '-' }}
                                </td>
                                <td class="border border-gray-300 dark:border-gray-600 p-2">
                                    {{ $ticket->start_date ?? '-' }}
                                </td>
                                <td class="border border-gray-300 dark:border-gray-600 p-2">
                                    {{ $ticket->end_date ?? '-' }}
                                </td>
                                <td class="border border-gray-300 dark:border-gray-600 p-2">
                                    {{ $ticket->solution ?? '-' }}
                                </td>
                                <td class="border border-gray-300 dark:border-gray-600 p-2">
                                    <span title="{{ $ticket->feedback->description ?? '-' }}">
                                        {{ $ticket->feedback->description ?? '-' }}
                                    </span>
                                </td>
                                <td class="border border-gray-300 dark:border-gray-600 p-2">
                                    @if ($ticket->image)
                                        <a href="{{ asset('storage/' . $ticket->image) }}" target="_blank"
                                            class="text-blue-600 dark:text-blue-400 underline">Lihat File</a>
                                    @else
                                        <span class="text-gray-500 dark:text-gray-400 text-sm italic">No media</span>
                                    @endif
                                </td>
                                <td class="border border-gray-300 dark:border-gray-600 p-2 text-center">
                                    @if ($ticket->status_id == 3)
                                        <a href="{{ route('feedback.form', $ticket->id) }}"
                                            class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-xs inline-block transition-colors">
                                            Feedback
                                        </a>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- MOBILE VIEW (CARDS) --}}
            <div class="md:hidden">
                {{-- Search & Filter untuk Mobile --}}
                <div class="mb-4">
                    <div class="flex gap-2 mb-4">
                        <input type="text" id="mobileSearch" placeholder="Cari tiket..."
                            class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200 text-sm">

                        <select id="mobilePerPage"
                            class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200 text-sm">
                            <option value="5" selected>5</option>
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="all">Semua</option>
                        </select>
                    </div>

                    <div id="mobileCards" class="space-y-4">
                        @php
                            // Urutkan: yang ada feedback button (status_id == 3) di atas
                            $sortedTicketsMobile = $myTicket->sortByDesc(function ($ticket) {
                                return $ticket->status_id == 3 ? 1 : 0;
                            });
                        @endphp
                        @foreach ($sortedTicketsMobile as $ticket)
                            <div class="ticket-card bg-gray-50 dark:bg-gray-700 rounded-lg p-4 shadow border border-gray-200 dark:border-gray-600 {{ $ticket->status_id == 3 ? 'ring-2 ring-green-400 dark:ring-green-500 bg-green-50 dark:bg-green-900/10' : '' }}"
                                data-code="{{ strtolower($ticket->ticket_code) }}"
                                data-name="{{ strtolower($ticket->user->name ?? '') }}"
                                data-category="{{ strtolower($ticket->problemCategory?->problem_category_name ?? '') }}"
                                data-problem="{{ strtolower($ticket->problem) }}"
                                data-status="{{ strtolower($ticket->status->status_name) }}"
                                data-priority="{{ $ticket->status_id == 3 ? '1' : '0' }}">

                                {{-- Header Card --}}
                                <div class="flex justify-between items-start mb-3">
                                    <div>
                                        <div class="text-xs font-semibold text-blue-600 dark:text-blue-400">
                                            {{ $ticket->ticket_code }}
                                        </div>
                                        <div class="text-sm font-medium text-gray-800 dark:text-gray-200 mt-1">
                                            {{ $ticket->user->name ?? '-' }}
                                        </div>
                                    </div>
                                    <div class="flex flex-col items-end gap-1">
                                        <span
                                            class="bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 text-xs px-2 py-1 rounded">
                                            {{ $ticket->status->status_name }}
                                        </span>
                                        @if ($ticket->status_id == 3)
                                            <span
                                                class="bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 text-xs px-2 py-1 rounded font-medium">
                                                Perlu Feedback
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                {{-- Content Card --}}
                                <div class="space-y-2 text-sm">
                                    <div class="flex">
                                        <span
                                            class="text-gray-500 dark:text-gray-400 w-24 flex-shrink-0">Kategori:</span>
                                        <span
                                            class="text-gray-800 dark:text-gray-200 font-medium break-words overflow-hidden">
                                            {{ $ticket->problemCategory?->problem_category_name ?? '-' }}
                                        </span>
                                    </div>
                                    <div class="flex">
                                        <span
                                            class="text-gray-500 dark:text-gray-400 w-24 flex-shrink-0">Masalah:</span>
                                        <span
                                            class="text-gray-800 dark:text-gray-200 break-words overflow-hidden">{{ $ticket->problem }}</span>
                                    </div>
                                    <div class="flex">
                                        <span
                                            class="text-gray-500 dark:text-gray-400 w-24 flex-shrink-0">Tanggal:</span>
                                        <span
                                            class="text-gray-800 dark:text-gray-200 break-words overflow-hidden">{{ $ticket->request_date?->format('Y-m-d') ?? '-' }}</span>
                                    </div>
                                    <div class="flex">
                                        <span
                                            class="text-gray-500 dark:text-gray-400 w-24 flex-shrink-0 font-medium">Feedback:</span>
                                        <span class="text-gray-800 dark:text-gray-200 break-words overflow-hidden">
                                            {{ $ticket->feedback->description ?? '-' }}
                                        </span>
                                    </div>
                                    <div class="flex">
                                        <span class="text-gray-500 dark:text-gray-400 w-24 flex-shrink-0">Rating</span>
                                        <span
                                            class="text-gray-800 dark:text-gray-200 break-words overflow-hidden">{{ $ticket->feedback->rating ?? '-' }}/5</span>
                                    </div>
                                </div>

                                {{-- Action Button --}}
                                @if ($ticket->status_id == 3)
                                    <div class="mt-4 pt-3 border-t border-gray-200 dark:border-gray-600">
                                        <a href="{{ route('feedback.form', $ticket->id) }}"
                                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded text-sm inline-block transition-colors w-full text-center font-medium">
                                            Berikan Feedback
                                        </a>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    {{-- Mobile Pagination --}}
                    <div id="mobilePagination" class="flex flex-wrap justify-between items-center mt-4 gap-2">
                        <div id="mobileInfo" class="text-sm text-gray-600 dark:text-gray-400"></div>
                        <div id="mobilePaginationButtons" class="flex gap-1"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- MODAL FORM --}}
        <div id="ticketModal"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 w-96 max-w-full mx-4 relative">
                <button id="closeModal"
                    class="absolute top-2 right-2 text-gray-500 dark:text-gray-300 hover:text-gray-700 dark:hover:text-white text-2xl">&times;</button>
                <h3 class="font-bold text-lg mb-4 text-gray-800 dark:text-gray-200">Update Ticket</h3>
                <form method="POST" id="ticketForm">
                    @csrf
                    <input type="hidden" name="ticket_id" id="modalTicketId">
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1 text-gray-800 dark:text-gray-200">Time Spent
                            (hours)</label>
                        <input type="number" step="0.01" name="time_spent" id="timeSpentInput"
                            class="w-full border border-gray-300 dark:border-gray-600 rounded p-2 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200"
                            required>
                    </div>
                    <button type="submit"
                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded w-full transition-colors">Submit</button>
                </form>
            </div>
        </div>

        {{-- CDN --}}
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
        <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

        <style>
            /* === WRAPPER / CONTAINER === */
            div.dataTables_wrapper {
                margin-top: 1.5rem;
                margin-bottom: 1.5rem;
                padding: 1.25rem;
                border-radius: 0.75rem;
                background-color: var(--dt-bg);
                box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
                transition: background-color 0.3s ease, box-shadow 0.3s ease;
            }

            /* === MODE VARIABLE === */
            :root {
                --dt-bg: #f9fafb;
                /* Light default */
                --dt-text: #1f2937;
                --dt-border: #e5e7eb;
                --dt-hover: rgba(59, 130, 246, 0.08);
                --dt-head-bg: #f3f4f6;
                --dt-head-text: #374151;
            }

            .dark {
                --dt-bg: #1f2937;
                --dt-text: #f3f4f6;
                --dt-border: #374151;
                --dt-hover: rgba(59, 130, 246, 0.15);
                --dt-head-bg: #111827;
                --dt-head-text: #d1d5db;
            }

            /* === DATATABLE ELEMENT SPACING === */
            .dataTables_wrapper .dataTables_filter,
            .dataTables_wrapper .dataTables_length,
            .dataTables_wrapper .dataTables_info,
            .dataTables_wrapper .dataTables_paginate {
                margin-bottom: 1rem;
                color: var(--dt-text);
            }

            /* === FILTER & LENGTH === */
            .dataTables_wrapper .dataTables_filter {
                margin-right: 1rem;
            }

            .dataTables_wrapper .dataTables_length {
                margin-left: 0.5rem;
            }

            .dataTables_wrapper .dataTables_filter label,
            .dataTables_wrapper .dataTables_length label {
                padding: 0.25rem 0.5rem;
            }

            /* === TABLE CORE === */
            table.dataTable {
                margin-top: 0.75rem !important;
                margin-bottom: 0.75rem !important;
                width: 100% !important;
                border-spacing: 0 !important;
                color: var(--dt-text);
                border-color: var(--dt-border);
                background-color: transparent;
                transition: background-color 0.3s ease, color 0.3s ease;
            }

            /* === HEADER === */
            table.dataTable thead {
                background-color: var(--dt-head-bg);
                color: var(--dt-head-text);
            }

            table.dataTable th {
                padding: 0.75rem 1rem;
                text-align: center;
                border-bottom: 2px solid var(--dt-border);
                font-weight: 600;
            }

            /* === BODY === */
            table.dataTable td {
                padding: 0.75rem 1rem;
                text-align: center;
                vertical-align: middle;
                border-bottom: 1px solid var(--dt-border);
            }

            /* === HOVER EFFECT === */
            table.dataTable tbody tr {
                transition: background-color 0.2s ease;
            }

            table.dataTable tbody tr:hover {
                background-color: var(--dt-hover);
            }

            /* === PAGINATION === */
            .dataTables_wrapper .dataTables_paginate .paginate_button {
                background: transparent;
                color: var(--dt-text) !important;
                border: 1px solid var(--dt-border);
                border-radius: 0.375rem;
                padding: 0.3rem 0.6rem;
                margin: 0 2px;
                transition: all 0.
            }
        </style>

        {{-- SCRIPTS --}}
        <script>
            $(document).ready(function() {
                // DESKTOP DATATABLE
                if (window.innerWidth >= 768) {
                    if ($.fn.DataTable.isDataTable('.datatable')) {
                        $('.datatable').DataTable().destroy();
                    }

                    $('.datatable').DataTable({
                        responsive: true,
                        paging: true,
                        searching: true,
                        ordering: true,
                        info: true,
                        pageLength: 10,
                        lengthMenu: [
                            [5, 10, 25, 50, -1],
                            [5, 10, 25, 50, "Semua"]
                        ],
                        order: [], // Jangan override urutan baris (sudah diurutkan dari backend)
                        language: {
                            search: "Cari:",
                            searchPlaceholder: "Cari data...",
                            lengthMenu: "Tampilkan _MENU_ data per halaman",
                            info: "Menampilkan _START_ - _END_ dari _TOTAL_ tiket",
                            infoEmpty: "Tidak ada data",
                            infoFiltered: "(difilter dari _MAX_ total tiket)",
                            emptyTable: "Tidak ada data tersedia di tabel ini",
                            zeroRecords: "Tidak ada data yang cocok",
                            paginate: {
                                first: "Pertama",
                                last: "Terakhir",
                                next: "›",
                                previous: "‹"
                            },
                        },
                    });
                }

                // MOBILE PAGINATION
                let currentPage = 1;
                let perPage = 10;
                let filteredCards = [];

                function filterCards() {
                    const searchTerm = $('#mobileSearch').val().toLowerCase();
                    const allCards = $('.ticket-card');

                    // Filter cards berdasarkan search
                    let searchResults = allCards.filter(function() {
                        const card = $(this);
                        const searchText =
                            card.data('code') + ' ' +
                            card.data('name') + ' ' +
                            card.data('category') + ' ' +
                            card.data('problem') + ' ' +
                            card.data('status');

                        return searchText.includes(searchTerm);
                    });

                    // Urutkan: yang perlu feedback (priority=1) di atas
                    filteredCards = searchResults.sort(function(a, b) {
                        const priorityA = parseInt($(a).data('priority')) || 0;
                        const priorityB = parseInt($(b).data('priority')) || 0;
                        return priorityB - priorityA;
                    });

                    currentPage = 1;
                    displayCards();
                }

                function displayCards() {
                    const allCards = $('.ticket-card');
                    allCards.hide();

                    if ($('#mobilePerPage').val() === 'all') {
                        filteredCards.show();
                        $('#mobilePagination').hide();
                        updateInfo(1, filteredCards.length, filteredCards.length);
                        return;
                    }

                    const start = (currentPage - 1) * perPage;
                    const end = start + perPage;
                    const cardsToShow = filteredCards.slice(start, end);

                    cardsToShow.show();
                    updatePagination();
                    updateInfo(start + 1, Math.min(end, filteredCards.length), filteredCards.length);
                    $('#mobilePagination').show();
                }

                function updatePagination() {
                    const totalPages = Math.ceil(filteredCards.length / perPage);
                    const buttons = $('#mobilePaginationButtons');
                    buttons.empty();

                    if (totalPages <= 1) {
                        buttons.hide();
                        return;
                    }
                    buttons.show();

                    // Previous button
                    buttons.append(`
                    <button class="px-3 py-1 text-sm rounded border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200 ${currentPage === 1 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-100 dark:hover:bg-gray-600'}" 
                        ${currentPage === 1 ? 'disabled' : ''} 
                        onclick="changePage(${currentPage - 1})">
                        ‹
                    </button>
                `);

                    // Page numbers (show max 5 pages)
                    let startPage = Math.max(1, currentPage - 2);
                    let endPage = Math.min(totalPages, startPage + 4);

                    if (endPage - startPage < 4) {
                        startPage = Math.max(1, endPage - 4);
                    }

                    for (let i = startPage; i <= endPage; i++) {
                        buttons.append(`
                        <button class="px-3 py-1 text-sm rounded border ${i === currentPage ? 'bg-blue-600 text-white border-blue-600' : 'border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600'}" 
                            onclick="changePage(${i})">
                            ${i}
                        </button>
                    `);
                    }

                    // Next button
                    buttons.append(`
                    <button class="px-3 py-1 text-sm rounded border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200 ${currentPage === totalPages ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-100 dark:hover:bg-gray-600'}" 
                        ${currentPage === totalPages ? 'disabled' : ''} 
                        onclick="changePage(${currentPage + 1})">
                        ›
                    </button>
                `);
                }

                function updateInfo(start, end, total) {
                    $('#mobileInfo').text(`Menampilkan ${start} - ${end} dari ${total || filteredCards.length} tiket`);
                }

                window.changePage = function(page) {
                    currentPage = page;
                    displayCards();
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                };

                // Event listeners
                $('#mobileSearch').on('keyup', filterCards);

                $('#mobilePerPage').on('change', function() {
                    perPage = parseInt($(this).val());
                    currentPage = 1;
                    displayCards();
                });

                // Initial display
                filterCards();
            });

            // MODAL JS
            document.addEventListener('DOMContentLoaded', () => {
                const modal = document.getElementById('ticketModal');
                const closeBtn = document.getElementById('closeModal');
                const ticketIdInput = document.getElementById('modalTicketId');

                document.querySelectorAll('.openModalBtn').forEach(btn => {
                    btn.addEventListener('click', () => {
                        ticketIdInput.value = btn.dataset.ticketId;
                        modal.classList.remove('hidden');
                    });
                });

                closeBtn.addEventListener('click', () => modal.classList.add('hidden'));
                modal.addEventListener('click', (e) => {
                    if (e.target === modal) modal.classList.add('hidden');
                });
            });
        </script>
</x-app-layout>
