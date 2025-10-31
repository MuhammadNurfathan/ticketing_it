<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">
            {{ __('My Tickets') }}
        </h2>
    </x-slot>

    <div class="min-h-screen bg-light-bg dark:bg-dark-bg">
        <div class="w-full px-4 sm:px-6 lg:px-8 py-6">

            {{-- Alert Error --}}
            @if (session('error'))
                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const isDark = document.documentElement.classList.contains('dark');
                        Swal.fire({
                            icon: 'warning',
                            title: 'Tidak bisa membuat tiket baru!',
                            text: '{{ session('error') }}',
                            confirmButtonColor: '#6b7280',
                            background: isDark ? '#222738' : '#ffffff',
                            color: isDark ? '#f3f4f6' : '#111827',
                            customClass: {
                                popup: 'rounded-xl shadow-2xl',
                                confirmButton: 'font-medium px-6 py-2.5 rounded-lg'
                            }
                        });
                    });
                </script>
            @endif

            {{-- Header Section --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">Ticket Management</h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Track and manage your support requests</p>
                </div>

                @if (!$hasDoneTicket)
                        <a href="{{ route('DashboardTicketsUser.createUser') }}"
                            class="inline-flex items-center justify-center px-4 py-2.5 bg-blue-500 dark:bg-blue-500 text-white dark:text-white rounded-lg hover:bg-blue-800 dark:hover:bg-blue-800 transition-colors font-medium text-sm shadow-sm">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                            Tambah Ticket
                        </a>
                    @endif
            </div>

            {{-- Stats Cards --}}
            <div class="grid grid-cols-3 gap-3 sm:gap-4 mb-6">
                <div
                    class="bg-white dark:bg-dark-eval-1 rounded-xl p-3 sm:p-6 border border-gray-200 dark:border-gray-700">
                    <div
                        class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1 sm:mb-2">
                        Done</div>
                    <div class="text-xl sm:text-3xl font-bold text-gray-900 dark:text-white">
                        {{ $myTicket->whereIn('status_id', [3, 5])->count() }}
                    </div>
                </div>
                <div
                    class="bg-white dark:bg-dark-eval-1 rounded-xl p-3 sm:p-6 border border-gray-200 dark:border-gray-700">
                    <div
                        class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1 sm:mb-2">
                        Feedback</div>
                    <div class="text-xl sm:text-3xl font-bold text-gray-900 dark:text-white">
                        {{ $myTicket->where('status_id', 5)->count() }}
                    </div>
                </div>
                <div
                    class="bg-white dark:bg-dark-eval-1 rounded-xl p-3 sm:p-6 border border-gray-200 dark:border-gray-700">
                    <div
                        class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1 sm:mb-2">
                        Total</div>
                    <div class="text-xl sm:text-3xl font-bold text-gray-900 dark:text-white">
                        {{ $myTicket->count() }}
                    </div>
                </div>
            </div>

            {{-- Tickets Table/List --}}
            <div
                class="bg-white dark:bg-dark-eval-1 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">

                {{-- Desktop Table --}}
                <div class="hidden lg:block overflow-x-auto">
                    <table class="datatable w-full text-gray-900 dark:text-gray-100">
                        <thead class="bg-gray-50 dark:bg-dark-eval-2 border-b border-gray-200 dark:border-gray-700">
                            <tr>
                                <th
                                    class="px-4 py-3 text-left text-gray-700 dark:text-gray-300 text-xs font-semibold uppercase tracking-wider">
                                    Ticket</th>
                                <th
                                    class="px-4 py-3 text-left text-gray-700 dark:text-gray-300 text-xs font-semibold uppercase tracking-wider">
                                    Category</th>
                                <th
                                    class="px-4 py-3 text-left text-gray-700 dark:text-gray-300 text-xs font-semibold uppercase tracking-wider">
                                    Problem</th>
                                <th
                                    class="px-4 py-3 text-left text-gray-700 dark:text-gray-300 text-xs font-semibold uppercase tracking-wider">
                                    Status</th>
                                <th
                                    class="px-4 py-3 text-left text-gray-700 dark:text-gray-300 text-xs font-semibold uppercase tracking-wider">
                                    Date</th>
                                <th
                                    class="px-4 py-3 text-left text-gray-700 dark:text-gray-300 text-xs font-semibold uppercase tracking-wider">
                                    Solution</th>
                                <th
                                    class="px-4 py-3 text-center text-gray-700 dark:text-gray-300 text-xs font-semibold uppercase tracking-wider">
                                    Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @php
                                $sortedTickets = $myTicket->sortByDesc(fn($t) => $t->status_id == 3 ? 1 : 0);
                            @endphp
                            @foreach ($sortedTickets as $ticket)
                                <tr
                                    class="transition-colors hover:bg-gray-100 dark:hover:bg-dark-eval-2 {{ $ticket->status_id == 3 ? 'bg-gray-50 dark:bg-dark-eval-2/50' : '' }}">
                                    <td class="px-4 py-4">
                                        <div class="font-medium text-gray-900 dark:text-white">
                                            {{ $ticket->ticket_code }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                            {{ $ticket->user->name ?? '-' }}</div>
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-700 dark:text-gray-300">
                                        {{ $ticket->problemCategory?->problem_category_name ?? '-' }}
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-700 dark:text-gray-300 max-w-xs truncate">
                                        {{ $ticket->problem }}
                                    </td>
                                    <td class="px-4 py-4">
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium
                                            {{ $ticket->status_id == 1 ? 'bg-gray-400 text-gray-900 dark:bg-gray-600 dark:text-white' : '' }}
                                            {{ $ticket->status_id == 2 ? 'bg-yellow-500 text-gray-900 dark:bg-yellow-600 dark:text-white' : '' }}
                                            {{ $ticket->status_id == 3 ? 'bg-blue-600 text-white dark:bg-blue-700 dark:text-white' : '' }}
                                            {{ $ticket->status_id == 5 ? 'bg-green-500 text-white dark:bg-green-600 dark:text-white' : '' }}">
                                            {{ $ticket->status->status_name }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-700 dark:text-gray-300">
                                        {{ $ticket->request_date ? $ticket->request_date->format('d M Y') : '-' }}
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-700 dark:text-gray-300 max-w-xs truncate">
                                        {{ $ticket->solution ?? '-' }}
                                    </td>
                                    <td class="px-4 py-4 text-center">
                                        @if ($ticket->status_id == 3)
                                            <a href="{{ route('feedback.form', $ticket->id) }}"
                                                class="inline-flex items-center px-3 py-1.5 bg-red-500 hover:bg-red-600 dark:bg-red-600 dark:hover:bg-red-700 text-white rounded-lg text-xs font-medium transition-colors">
                                                Berikan Feedback
                                            </a>
                                        @else
                                            <span class="text-gray-400 dark:text-gray-500 text-xs">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Mobile Cards --}}
                <div class="lg:hidden">
                    <div class="p-4 space-y-3">
                        {{-- Search & Filter --}}
                        <div class="flex gap-2">
                            <input type="text" id="mobileSearch" placeholder="Search tickets..."
                                class="flex-1 px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-dark-eval-2 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-gray-400 dark:focus:ring-gray-500 focus:border-transparent">
                            <select id="mobilePerPage"
                                class="px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-dark-eval-2 text-gray-900 dark:text-white focus:ring-2 focus:ring-gray-400 dark:focus:ring-gray-500">
                                <option value="5">5</option>
                                <option value="10" selected>10</option>
                                <option value="25">25</option>
                                <option value="all">All</option>
                            </select>
                        </div>

                        {{-- Cards Container --}}
                        <div id="mobileCards" class="space-y-3">
                            @php
                                $sortedMobile = $myTicket->sortByDesc(fn($t) => $t->status_id == 3 ? 1 : 0);
                            @endphp
                            @foreach ($sortedMobile as $ticket)
                                <div class="ticket-card bg-gray-50 dark:bg-dark-eval-2 rounded-lg p-4 border border-gray-200 dark:border-gray-700 
                                    {{ $ticket->status_id == 3 ? 'ring-2 ring-red-500 dark:ring-red-600' : '' }}"
                                    data-code="{{ strtolower($ticket->ticket_code) }}"
                                    data-category="{{ strtolower($ticket->problemCategory?->problem_category_name ?? '') }}"
                                    data-problem="{{ strtolower($ticket->problem) }}"
                                    data-status="{{ strtolower($ticket->status->status_name) }}"
                                    data-priority="{{ $ticket->status_id == 3 ? '1' : '0' }}">

                                    <div class="flex justify-between items-start mb-3">
                                        <div>
                                            <div class="text-sm font-semibold text-gray-900 dark:text-white">
                                                {{ $ticket->ticket_code }}</div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                                {{ $ticket->user->name ?? '-' }}</div>
                                        </div>
                                        <span
                                            class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium
                                            {{ $ticket->status_id == 1 ? 'bg-gray-400 text-gray-900 dark:bg-gray-600 dark:text-white' : '' }}
                                            {{ $ticket->status_id == 2 ? 'bg-yellow-500 text-gray-900 dark:bg-yellow-600 dark:text-white' : '' }}
                                            {{ $ticket->status_id == 3 ? 'bg-blue-600 text-white dark:bg-blue-700 dark:text-white' : '' }}
                                            {{ $ticket->status_id == 5 ? 'bg-green-500 text-white dark:bg-green-600 dark:text-white' : '' }}">
                                            {{ $ticket->status->status_name }}
                                        </span>
                                    </div>

                                    <div class="space-y-2 text-sm">
                                        <div class="flex">
                                            <span
                                                class="text-gray-500 dark:text-gray-400 w-24 flex-shrink-0 text-xs">Category:</span>
                                            <span
                                                class="text-gray-900 dark:text-white text-xs font-medium">{{ $ticket->problemCategory?->problem_category_name ?? '-' }}</span>
                                        </div>
                                        <div class="flex">
                                            <span
                                                class="text-gray-500 dark:text-gray-400 w-24 flex-shrink-0 text-xs">Problem:</span>
                                            <span
                                                class="text-gray-900 dark:text-white text-xs">{{ Str::limit($ticket->problem, 60) }}</span>
                                        </div>
                                        <div class="flex">
                                            <span
                                                class="text-gray-500 dark:text-gray-400 w-24 flex-shrink-0 text-xs">Date:</span>
                                            <span
                                                class="text-gray-900 dark:text-white text-xs">{{ $ticket->request_date ? $ticket->request_date->format('d M Y') : '-' }}</span>
                                        </div>
                                        @if ($ticket->solution)
                                            <div class="flex">
                                                <span
                                                    class="text-gray-500 dark:text-gray-400 w-24 flex-shrink-0 text-xs">Solution:</span>
                                                <span
                                                    class="text-gray-900 dark:text-white text-xs">{{ Str::limit($ticket->solution, 60) }}</span>
                                            </div>
                                        @endif
                                    </div>

                                    @if ($ticket->status_id == 3)
                                        <div class="mt-3 pt-3 border-t border-gray-200 dark:border-gray-700">
                                            <a href="{{ route('feedback.form', $ticket->id) }}"
                                                class="block w-full text-center px-4 py-2 bg-red-500 hover:bg-red-600 dark:bg-red-600 dark:hover:bg-red-700 text-white rounded-lg text-sm font-medium transition-colors">
                                                Give Feedback
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        {{-- Pagination --}}
                        <div id="mobilePagination"
                            class="flex flex-col sm:flex-row justify-between items-center gap-3 pt-3 border-t border-gray-200 dark:border-gray-700">
                            <div id="mobileInfo" class="text-xs text-gray-600 dark:text-gray-400 font-medium"></div>
                            <div id="mobilePaginationButtons" class="flex gap-1 flex-wrap justify-center"></div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Create Ticket Modal --}}
    <x-modal-form id="ticketModal" title="Create New Ticket" size="max-w-2xl">
        <form action="{{ route('DashboardTicketsAdmin.store') }}" method="POST" enctype="multipart/form-data"
            class="space-y-4">
            @csrf
            <input type="hidden" name="from" value="user">
            <input type="hidden" name="status_id" value="1">

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Ticket Code <span class="text-red-500">*</span>
                </label>
                <input type="text" name="ticket_code" value="{{ $generateticket }}" readonly
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-dark-eval-2 text-gray-900 dark:text-gray-100 text-sm">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">User</label>
                <input type="text" value="{{ Auth::user()->name }}" readonly
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-dark-eval-2 text-gray-900 dark:text-gray-100 text-sm">
                <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Category <span class="text-red-500">*</span>
                </label>
                <select name="problem_category_id" required
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-dark-eval-2 text-gray-900 dark:text-gray-100 text-sm focus:ring-2 focus:ring-gray-400 dark:focus:ring-gray-500">
                    <option value="">-- Select Category --</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->problem_category_name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Problem Description <span class="text-red-500">*</span>
                </label>
                <textarea name="problem" rows="3" required placeholder="Describe your issue..."
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-dark-eval-2 text-gray-900 dark:text-gray-100 text-sm focus:ring-2 focus:ring-gray-400 dark:focus:ring-gray-500"></textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Attachment (Image/Video, Max 10MB)
                </label>
                <input type="file" name="image" accept=".jpg,.jpeg,.png,.mp4"
                    class="block w-full text-sm text-gray-900 dark:text-gray-100
                    file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0
                    file:text-sm file:font-medium file:bg-gray-900 dark:file:bg-gray-100
                    file:text-white dark:file:text-gray-900 hover:file:bg-gray-800 dark:hover:file:bg-gray-200
                    file:cursor-pointer cursor-pointer">
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <button type="button" onclick="closeModal('ticketModal')"
                    class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-dark-eval-2 text-sm font-medium transition-colors">
                    Cancel
                </button>

                <button type="submit"
                    class="px-4 py-2 bg-gray-900 dark:bg-gray-100 text-white dark:text-gray-900 rounded-lg hover:bg-gray-800 dark:hover:bg-gray-200 text-sm font-medium transition-colors">
                    Create Ticket
                </button>
            </div>
        </form>
    </x-modal-form>

    {{-- DataTables CSS --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

    <style>
        /* DataTables Custom Styling */
        table.dataTable {
            border-collapse: separate;
            border-spacing: 0;
            border: 1px solid #d1d5db;
            background-color: #f9fafb;
            width: 100%;
        }

        .dark table.dataTable {
            border-color: #4b5563;
            background-color: #1f2937;
        }

        table.dataTable thead th {
            background-color: #f3f4f6;
            color: #111827;
            padding: 0.75rem 1rem;
            text-align: left;
            border-bottom: 2px solid #d1d5db;
        }

        .dark table.dataTable thead th {
            background-color: #374151;
            color: #f9fafb;
            border-bottom-color: #4b5563;
        }

        table.dataTable tbody tr:hover {
            background-color: #f3f4f6;
        }

        .dark table.dataTable tbody tr:hover {
            background-color: #374151;
        }

        table.dataTable tbody td {
            border-bottom: 1px solid #e5e7eb;
        }

        .dark table.dataTable tbody td {
            border-bottom-color: #4b5563;
        }

        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter,
        .dataTables_wrapper .dataTables_info,
        .dataTables_wrapper .dataTables_paginate {
            padding: 1rem;
            color: inherit;
        }

        .dataTables_wrapper .dataTables_filter input,
        .dataTables_wrapper .dataTables_length select {
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            padding: 0.375rem 0.75rem;
            background-color: #ffffff;
            color: #111827;
            font-size: 0.875rem;
        }

        .dark .dataTables_wrapper .dataTables_filter input,
        .dark .dataTables_wrapper .dataTables_length select {
            border-color: #4b5563;
            background-color: #374151;
            color: #f9fafb;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 0.375rem 0.75rem;
            border-radius: 0.5rem;
            background-color: #f3f4f6;
            color: #111827 !important;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.2s;
            border: none !important;
            margin: 0 2px;
        }

        .dark .dataTables_wrapper .dataTables_paginate .paginate_button {
            background-color: #374151;
            color: #f9fafb !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:not(.disabled):hover {
            background-color: #e5e7eb !important;
            color: #111827 !important;
            border: none !important;
        }

        .dark .dataTables_wrapper .dataTables_paginate .paginate_button:not(.disabled):hover {
            background-color: #4b5563 !important;
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
    </style>

    <script>
        $(document).ready(function() {
            // Desktop DataTable
            if (window.innerWidth >= 1024) {
                $('.datatable').DataTable({
                    responsive: true,
                    pageLength: 10,
                    lengthMenu: [
                        [5, 10, 25, 50, -1],
                        [5, 10, 25, 50, "All"]
                    ],
                    order: [],
                    language: {
                        search: "Search:",
                        lengthMenu: "Show _MENU_ entries",
                        info: "Showing _START_ to _END_ of _TOTAL_ tickets",
                        infoEmpty: "No tickets available",
                        zeroRecords: "No matching tickets found",
                        paginate: {
                            first: "First",
                            last: "Last",
                            next: "›",
                            previous: "‹"
                        }
                    }
                });
            }

            // Mobile Pagination
            let currentPage = 1;
            let perPage = 10;
            let filteredCards = [];

            function filterCards() {
                const searchTerm = $('#mobileSearch').val().toLowerCase();
                const allCards = $('.ticket-card');

                filteredCards = allCards.filter(function() {
                    const card = $(this);
                    const searchText = [
                        card.data('code'),
                        card.data('category'),
                        card.data('problem'),
                        card.data('status')
                    ].join(' ');
                    return searchText.includes(searchTerm);
                }).toArray();

                // Sort by priority
                filteredCards.sort((a, b) => {
                    return parseInt($(b).data('priority')) - parseInt($(a).data('priority'));
                });

                filteredCards = $(filteredCards);
                currentPage = 1;
                displayCards();
            }

            function displayCards() {
                const allCards = $('.ticket-card');
                allCards.hide();

                if ($('#mobilePerPage').val() === 'all') {
                    filteredCards.show();
                    $('#mobilePagination').hide();
                    return;
                }

                const start = (currentPage - 1) * perPage;
                const end = start + perPage;
                filteredCards.slice(start, end).show();

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

                const isDark = document.documentElement.classList.contains('dark');
                const btnBaseClass = 'px-3 py-1.5 text-xs rounded-lg font-medium transition-colors';
                const btnNormalClass = isDark ?
                    'border border-gray-600 bg-dark-eval-2 text-gray-300 hover:bg-dark-eval-3' :
                    'border border-gray-300 bg-white text-gray-700 hover:bg-gray-100';
                const btnActiveClass = isDark ?
                    'bg-gray-100 text-gray-900 border-none' :
                    'bg-gray-900 text-white border-none';

                // Previous button
                const prevBtn = $(`
                    <button class="${btnBaseClass} ${btnNormalClass} ${currentPage === 1 ? 'opacity-50 cursor-not-allowed' : ''}" 
                        ${currentPage === 1 ? 'disabled' : ''}>
                        ‹
                    </button>
                `);
                if (currentPage > 1) {
                    prevBtn.on('click', function() {
                        changePage(currentPage - 1);
                    });
                }
                buttons.append(prevBtn);

                // Page numbers
                let startPage = Math.max(1, currentPage - 2);
                let endPage = Math.min(totalPages, startPage + 4);
                if (endPage - startPage < 4) {
                    startPage = Math.max(1, endPage - 4);
                }

                for (let i = startPage; i <= endPage; i++) {
                    const pageBtn = $(`
                        <button class="${btnBaseClass} ${i === currentPage ? btnActiveClass : btnNormalClass}">${i}</button>
                    `);
                    if (i !== currentPage) {
                        pageBtn.on('click', function() {
                            changePage(i);
                        });
                    }
                    buttons.append(pageBtn);
                }

                // Next button
                const nextBtn = $(`
                    <button class="${btnBaseClass} ${btnNormalClass} ${currentPage === totalPages ? 'opacity-50 cursor-not-allowed' : ''}">
                        ›
                    </button>
                `);
                if (currentPage < totalPages) {
                    nextBtn.on('click', function() {
                        changePage(currentPage + 1);
                    });
                }
                buttons.append(nextBtn);
            }

            function updateInfo(start, end, total) {
                $('#mobileInfo').text(`Showing ${start}-${end} of ${total} tickets`);
            }

            function changePage(page) {
                currentPage = page;
                displayCards();
                $('html, body').animate({
                    scrollTop: 0
                }, 300);
            }

            // Event listeners
            $('#mobileSearch').on('keyup', function() {
                filterCards();
            });

            $('#mobilePerPage').on('change', function() {
                const val = $(this).val();
                perPage = val === 'all' ? 999999 : parseInt(val);
                currentPage = 1;
                displayCards();
            });

            // Initial load
            filterCards();
        });
    </script>
</x-app-layout>
