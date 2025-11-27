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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Create Ticket
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
                class="bg-light-eval-1 dark:bg-dark-eval-1 shadow-md p-4 border border-gray-200 dark:border-gray-700">
                <div class="overflow-x-auto py-4" id="desktop-wrapper">
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
                                    Date</th>
                                <th
                                    class="px-4 py-3 text-left text-gray-700 dark:text-gray-300 text-xs font-semibold uppercase tracking-wider">
                                    Solution</th>
                                <th
                                    class="px-4 py-3 text-left text-gray-700 dark:text-gray-300 text-xs font-semibold uppercase tracking-wider">
                                    Feedback</th>
                                <th
                                    class="px-4 py-3 text-left text-gray-700 dark:text-gray-300 text-xs font-semibold uppercase tracking-wider">
                                    IT Support</th>
                                <th
                                    class="px-4 py-3 text-left text-gray-700 dark:text-gray-300 text-xs font-semibold uppercase tracking-wider">
                                    Status</th>
                                <th
                                    class="px-4 py-3 text-center text-gray-700 dark:text-gray-300 text-xs font-semibold uppercase tracking-wider">
                                    Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($myTicket as $ticket)
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

                                    <td class="px-4 py-4 text-sm text-gray-700 dark:text-gray-300">
                                        {{ $ticket->request_date ? $ticket->request_date->format('d M Y') : '-' }}
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-700 dark:text-gray-300 max-w-xs truncate">
                                        {{ $ticket->solution ?? '-' }}
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-700 dark:text-gray-300 max-w-xs truncate">
                                        {{ $ticket->feedback->description ?? '-' }}
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-700 dark:text-gray-300 max-w-xs truncate">
                                        {{ $ticket->support->name ?? '-' }}
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

                <div class="lg:hidden" id="mobile-wrapper">
                    <div class="p-4 space-y-4">

                        {{-- Search & Filter --}}
                        <div
                            class="bg-white dark:bg-dark-eval-2 border border-gray-200 dark:border-gray-700 rounded-xl p-3 shadow-sm mb-3">

                            <div class="flex items-center gap-2 w-full">

                                <!-- SEARCH FLEX -->
                                <input type="text" id="mobileSearch" placeholder="Search..."
                                    class="flex-1 min-w-0 px-2 py-1.5 text-xs border border-gray-300 dark:border-gray-600 rounded-md
                           bg-white dark:bg-dark-eval-2 text-gray-900 dark:text-white
                           placeholder-gray-500 dark:placeholder-gray-400
                           focus:ring-2 focus:ring-gray-400 dark:focus:ring-gray-500 focus:border-transparent" />

                                <!-- SELECT FLEX -->
                                <div class="relative">
                                    <select id="mobilePerPage"
                                        class="px-2 pr-6 py-1.5 text-xs min-w-[55px] border border-gray-300 dark:border-gray-600 rounded-md
                               bg-white dark:bg-dark-eval-2 text-gray-900 dark:text-white
                               focus:ring-2 focus:ring-gray-400 dark:focus:ring-gray-500 appearance-none">
                                        <option value="5">5</option>
                                        <option value="10" selected>10</option>
                                        <option value="25">25</option>
                                        <option value="all">All</option>
                                    </select>
                                </div>

                            </div>
                        </div>

                        {{-- Cards Container --}}
                        <div id="mobileCards" class="space-y-4">

                            @php
                                $sortedMobile = $myTicket->sortByDesc(fn($t) => $t->status_id == 3 ? 1 : 0);
                            @endphp

                            @foreach ($sortedMobile as $ticket)
                                <div class="ticket-card bg-gray-50 dark:bg-dark-eval-2 rounded-xl p-4
                            border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden
                            {{ $ticket->status_id == 3 ? 'ring-2 ring-red-500 dark:ring-red-600' : '' }}"
                                    data-code="{{ strtolower($ticket->ticket_code) }}"
                                    data-category="{{ strtolower($ticket->problemCategory?->problem_category_name ?? '') }}"
                                    data-problem="{{ strtolower($ticket->problem) }}"
                                    data-status="{{ strtolower($ticket->status->status_name) }}"
                                    data-support="{{ strtolower($ticket->support->name ?? '') }}"
                                    data-feedback="{{ strtolower($ticket->feedback->description ?? '') }}"
                                    data-priority="{{ $ticket->status_id == 3 ? '1' : '0' }}">

                                    {{-- Header --}}
                                    <div class="flex justify-between items-start mb-3">
                                        <div>
                                            <div class="text-sm font-semibold text-gray-900 dark:text-white">
                                                {{ $ticket->ticket_code }}
                                            </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                                {{ $ticket->user->name ?? '-' }}
                                            </div>
                                        </div>

                                        {{-- Status Badge --}}
                                        <span
                                            class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium
                            {{ $ticket->status_id == 1 ? 'bg-gray-400 text-gray-900 dark:bg-gray-600 dark:text-white' : '' }}
                            {{ $ticket->status_id == 2 ? 'bg-yellow-500 text-gray-900 dark:bg-yellow-600 dark:text-white' : '' }}
                            {{ $ticket->status_id == 3 ? 'bg-blue-600 text-white dark:bg-blue-700 dark:text-white' : '' }}
                            {{ $ticket->status_id == 5 ? 'bg-green-500 text-white dark:bg-green-600 dark:text-white' : '' }}">
                                            {{ $ticket->status->status_name }}
                                        </span>
                                    </div>

                                    {{-- Content --}}
                                    <div class="space-y-2 text-sm">

                                        {{-- Category --}}
                                        <div class="flex items-start">
                                            <span class="text-gray-500 dark:text-gray-400 min-w-[65px] shrink text-xs">
                                                Category:
                                            </span>
                                            <span
                                                class="text-gray-900 dark:text-white text-xs flex-1 break-words whitespace-normal overflow-hidden">
                                                {{ $ticket->problemCategory?->problem_category_name ?? '-' }}
                                            </span>
                                        </div>

                                        {{-- Problem --}}
                                        <div class="flex items-start">
                                            <span class="text-gray-500 dark:text-gray-400 min-w-[65px] shrink text-xs">
                                                Problem:
                                            </span>
                                            <span
                                                class="text-gray-900 dark:text-white text-xs flex-1 break-words whitespace-normal overflow-hidden">
                                                {{ $ticket->problem }}
                                            </span>
                                        </div>

                                        {{-- Date --}}
                                        <div class="flex items-start">
                                            <span class="text-gray-500 dark:text-gray-400 min-w-[65px] shrink text-xs">
                                                Date:
                                            </span>
                                            <span
                                                class="text-gray-900 dark:text-white text-xs flex-1 break-words whitespace-normal overflow-hidden">
                                                {{ $ticket->request_date ? $ticket->request_date->format('d M Y') : '-' }}
                                            </span>
                                        </div>

                                        {{-- Solution --}}
                                        @if ($ticket->solution)
                                            <div class="flex items-start">
                                                <span
                                                    class="text-gray-500 dark:text-gray-400 min-w-[65px] shrink text-xs">
                                                    Solution:
                                                </span>
                                                <span
                                                    class="text-gray-900 dark:text-white text-xs flex-1 break-words whitespace-normal overflow-hidden">
                                                    {{ $ticket->solution }}
                                                </span>
                                            </div>
                                        @endif

                                        {{-- Feedback --}}
                                        @if ($ticket->feedback)
                                            <div class="flex items-start">
                                                <span
                                                    class="text-gray-500 dark:text-gray-400 min-w-[65px] shrink text-xs">
                                                    Feedback:
                                                </span>
                                                <span
                                                    class="text-gray-900 dark:text-white text-xs flex-1 break-words whitespace-normal overflow-hidden">
                                                    {{ $ticket->feedback->description }}
                                                </span>
                                            </div>
                                        @endif

                                        {{-- Support --}}
                                        @if ($ticket->support)
                                            <div class="flex items-start">
                                                <span
                                                    class="text-gray-500 dark:text-gray-400 min-w-[65px] shrink text-xs">
                                                    Support:
                                                </span>
                                                <span
                                                    class="text-gray-900 dark:text-white text-xs flex-1 break-words whitespace-normal font-medium overflow-hidden">
                                                    {{ $ticket->support->name }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Give Feedback Button --}}
                                    @if ($ticket->status_id == 3 && !$ticket->feedback)
                                        <div class="mt-3 pt-3 border-t border-gray-200 dark:border-gray-700">
                                            <a href="{{ route('feedback.form', $ticket->id) }}"
                                                class="block w-full text-center px-4 py-2 bg-red-500 hover:bg-red-600
                                       dark:bg-red-600 dark:hover:bg-red-700 text-white rounded-lg
                                       text-sm font-medium transition-colors">
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

    <style>
        /* default: desktop tampil */
        #mobile-wrapper {
            display: none;
        }

        /* kalau layar <=1023px (HP / Tablet) */
        @media (max-width: 1023px) {
            #desktop-wrapper {
                display: none !important;
            }

            #mobile-wrapper {
                display: block !important;
            }
        }

        /* kalau layar >=1024px (Laptop / PC) */
        @media (min-width: 1024px) {
            #desktop-wrapper {
                display: block !important;
            }

            #mobile-wrapper {
                display: none !important;
            }
        }
    </style>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            // ===== Desktop DataTable =====
            if (window.innerWidth >= 1024) {
                new DataTable(".datatable", {
                    responsive: true,
                    pageLength: 5,
                    lengthMenu: [
                        [5, 10, 25, 50, -1],
                        [5, 10, 25, 50, "All"]
                    ],
                    order: [
                        [8, "desc"],
                        [0, "desc"],
                    ],
                    layout: {
                        topStart: "pageLength",
                        topEnd: "search",
                        bottomStart: "info",
                        bottomEnd: "paging"
                    }
                });
            }

            // ===== Mobile Pagination =====
            let currentPage = 1;
            let perPage = 10;
            let filteredCards = [];

            const mobileSearch = document.getElementById("mobileSearch");
            const mobilePerPage = document.getElementById("mobilePerPage");
            const mobilePagination = document.getElementById("mobilePagination");
            const mobilePaginationButtons = document.getElementById("mobilePaginationButtons");
            const mobileInfo = document.getElementById("mobileInfo");

            function filterCards() {
                const searchTerm = mobileSearch.value.toLowerCase();
                const allCards = Array.from(document.querySelectorAll(".ticket-card"));

                filteredCards = allCards.filter(card => {
                    const searchText = [
                        card.dataset.code,
                        card.dataset.category,
                        card.dataset.problem,
                        card.dataset.status,
                        card.dataset.support,
                        card.dataset.feedback
                    ].join(" ").toLowerCase();

                    return searchText.includes(searchTerm);
                });

                // Sort by priority
                filteredCards.sort((a, b) => parseInt(b.dataset.priority) - parseInt(a.dataset.priority));
                currentPage = 1;
                displayCards();
            }

            function displayCards() {
                const allCards = document.querySelectorAll(".ticket-card");
                allCards.forEach(card => card.style.display = "none");

                if (mobilePerPage.value === "all") {
                    filteredCards.forEach(card => card.style.display = "block");
                    mobilePagination.style.display = "none";
                    return;
                }

                const start = (currentPage - 1) * perPage;
                const end = start + perPage;
                filteredCards.slice(start, end).forEach(card => card.style.display = "block");

                updatePagination();
                updateInfo(start + 1, Math.min(end, filteredCards.length), filteredCards.length);
                mobilePagination.style.display = "flex";
            }

            function updatePagination() {
                const totalPages = Math.ceil(filteredCards.length / perPage);
                mobilePaginationButtons.innerHTML = "";

                if (totalPages <= 1) {
                    mobilePaginationButtons.style.display = "none";
                    return;
                }
                mobilePaginationButtons.style.display = "flex";

                const isDark = document.documentElement.classList.contains('dark');

                // Button class
                const btnBaseClass = "px-3 py-1.5 text-xs rounded-lg font-medium transition-colors";
                const btnNormalClass = isDark ?
                    "border border-gray-600 bg-dark-eval-2 text-gray-300 hover:bg-dark-eval-3" :
                    "border border-gray-300 bg-white text-gray-700 hover:bg-gray-100";
                const btnActiveClass = isDark ?
                    "bg-gray-100 text-gray-900 border-none" :
                    "bg-gray-900 text-white border-none";

                // Prev button
                const prevBtn = document.createElement("button");
                prevBtn.textContent = "‹";
                prevBtn.className = `${btnBaseClass} ${btnNormalClass}`;
                prevBtn.disabled = currentPage === 1;
                if (!prevBtn.disabled) prevBtn.addEventListener("click", () => changePage(currentPage - 1));
                mobilePaginationButtons.appendChild(prevBtn);

                // Page buttons
                let startPage = Math.max(1, currentPage - 2);
                let endPage = Math.min(totalPages, startPage + 4);
                if (endPage - startPage < 4) startPage = Math.max(1, endPage - 4);

                for (let i = startPage; i <= endPage; i++) {
                    const pageBtn = document.createElement("button");
                    pageBtn.textContent = i;
                    pageBtn.className = `${btnBaseClass} ${i === currentPage ? btnActiveClass : btnNormalClass}`;
                    if (i !== currentPage) pageBtn.addEventListener("click", () => changePage(i));
                    pageBtn.disabled = i === currentPage;
                    mobilePaginationButtons.appendChild(pageBtn);
                }

                // Next button
                const nextBtn = document.createElement("button");
                nextBtn.textContent = "›";
                nextBtn.className = `${btnBaseClass} ${btnNormalClass}`;
                nextBtn.disabled = currentPage === totalPages;
                if (!nextBtn.disabled) nextBtn.addEventListener("click", () => changePage(currentPage + 1));
                mobilePaginationButtons.appendChild(nextBtn);
            }

            function updateInfo(start, end, total) {
                mobileInfo.textContent = `Showing ${start}-${end} of ${total} tickets`;
            }

            function changePage(page) {
                currentPage = page;
                displayCards();
                window.scrollTo({
                    top: 0,
                    behavior: "smooth"
                });
            }

            // Events
            mobileSearch.addEventListener("keyup", filterCards);
            mobilePerPage.addEventListener("change", () => {
                perPage = mobilePerPage.value === "all" ? 999999 : parseInt(mobilePerPage.value);
                currentPage = 1;
                displayCards();
            });

            // Initial load
            filterCards();
        });
    </script>


</x-app-layout>
