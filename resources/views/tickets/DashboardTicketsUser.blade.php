<x-app-layout>
    <x-slot name="header"></x-slot>

    <div class="min-h-screen bg-light-bg dark:bg-dark-bg text-light-text dark:text-dark-text">
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
                            text: '{{ session('
                                                                                error ') }}',
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

            @php
                // ===== Base Styles (biar konsisten & pendek) =====
                $cardBase = "rounded-xl border shadow-sm
            bg-light-eval-1 dark:bg-dark-eval-1
            border-light-eval-3 dark:border-dark-eval-2";

                $muted = 'text-light-text-secondary dark:text-dark-text-secondary';
                $muted2 = 'text-light-text-muted dark:text-dark-text-secondary';

                $tableWrap = "rounded-xl p-4 border shadow-sm
            bg-light-bg dark:bg-dark-eval-1
            border-light-eval-3 dark:border-dark-eval-2";

                $theadBase = "bg-light-eval-2 dark:bg-dark-eval-2 border-b
            border-light-eval-3 dark:border-dark-eval-2";

                $thBase = "px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider $muted";

                $tdBase = "px-4 py-4 text-sm $muted";

                // Status badge classes
                $statusClasses = [
                    1 => 'bg-light-eval-3 text-light-text dark:bg-dark-eval-2 dark:text-dark-text',
                    2 => 'bg-yellow-500/90 text-gray-900 dark:bg-yellow-600 dark:text-white',
                    3 => 'bg-blue-600 text-white dark:bg-blue-700 dark:text-white',
                    4 => 'bg-red-600 text-white dark:bg-red-700 dark:text-white',
                    5 => 'bg-green-600 text-white dark:bg-green-700 dark:text-white',
                ];
            @endphp

            {{-- Header Section --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-light-text dark:text-dark-text">
                        Ticket Management
                    </h1>
                    <p class="text-sm mt-1 {{ $muted }}">
                        Track and manage your support requests
                    </p>
                </div>

                @if (!$hasDoneWithoutFeedback)
                    <a href="{{ route('DashboardTicketsUser.create') }}"
                        class="inline-flex items-center justify-center px-4 py-2.5 rounded-lg text-sm font-medium
                              bg-blue-600 text-white hover:bg-blue-700
                              dark:bg-blue-600 dark:hover:bg-blue-700
                              transition-colors shadow-sm">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Create Ticket
                    </a>
                @endif
            </div>

            {{-- Stats Cards --}}
            <div class="grid grid-cols-3 gap-3 sm:gap-4 mb-6">
                <div class="{{ $cardBase }} p-3 sm:p-6">
                    <div class="text-xs font-medium uppercase tracking-wide mb-1 sm:mb-2 {{ $muted2 }}">Done</div>
                    <div class="text-xl sm:text-3xl font-bold text-light-text dark:text-dark-text">
                        {{ $myTicket->whereIn('status_id', [3, 5])->count() }}
                    </div>
                </div>

                <div class="{{ $cardBase }} p-3 sm:p-6">
                    <div class="text-xs font-medium uppercase tracking-wide mb-1 sm:mb-2 {{ $muted2 }}">Feedback
                    </div>
                    <div class="text-xl sm:text-3xl font-bold text-light-text dark:text-dark-text">
                        {{ $myTicket->where('status_id', 5)->count() }}
                    </div>
                </div>

                <div class="{{ $cardBase }} p-3 sm:p-6">
                    <div class="text-xs font-medium uppercase tracking-wide mb-1 sm:mb-2 {{ $muted2 }}">Total
                    </div>
                    <div class="text-xl sm:text-3xl font-bold text-light-text dark:text-dark-text">
                        {{ $myTicket->count() }}
                    </div>
                </div>
            </div>

            {{-- Tickets Table/List --}}
            <div class="{{ $tableWrap }}">
                {{-- Desktop --}}
                <div class="overflow-x-auto py-4" id="desktop-wrapper">
                    <table class="datatable w-full text-light-text dark:text-dark-text">
                        <thead class="{{ $theadBase }}">
                            <tr>
                                <th class="{{ $thBase }}">Ticket</th>
                                <th class="{{ $thBase }}">Date</th>
                                <th class="{{ $thBase }}">Category</th>
                                <th class="{{ $thBase }}">Problem</th>
                                <th class="{{ $thBase }}">Solution</th>
                                <th class="{{ $thBase }}">Feedback</th>
                                <th class="{{ $thBase }}">IT Support</th>
                                <th class="{{ $thBase }}">Status</th>
                                <th class="{{ $thBase }} text-center">Action</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-light-eval-3 dark:divide-dark-eval-2">
                            @foreach ($myTicket as $ticket)
                                <tr
                                    class="transition-colors
                            hover:bg-light-eval-1 dark:hover:bg-dark-eval-2
                            {{ $ticket->status_id == 3 ? 'bg-light-eval-2 dark:bg-dark-eval-2/60' : '' }}">
                                    <td class="px-4 py-4">
                                        <div class="font-medium text-light-text dark:text-dark-text">
                                            {{ $ticket->ticket_code }}
                                        </div>
                                        <div class="text-xs mt-0.5 {{ $muted2 }}">
                                            {{ $ticket->user->name ?? '-' }}
                                        </div>
                                    </td>
                                    <td class="{{ $tdBase }}">
                                        {{ $ticket->request_date ? $ticket->request_date->format('d M Y') : '-' }}
                                    </td>

                                    <td class="{{ $tdBase }}">
                                        {{ $ticket->Category?->name ?? '-' }}
                                    </td>

                                    <td
                                        class="px-4 py-4 text-sm {{ $muted }} max-w-xs whitespace-normal break-words">
                                        {{ $ticket->problem }}
                                    </td>


                                    <td class="px-4 py-4 text-sm {{ $muted }} max-w-xs break-words">
                                        {{ $ticket->solution ?? '-' }}
                                    </td>

                                    <td class="px-4 py-4 text-sm {{ $muted }} max-w-xs break-words">
                                        {{ $ticket->feedback->description ?? '-' }}
                                    </td>
                                    <td class="px-4 py-4 text-sm {{ $muted }} max-w-xs truncate">
                                        {{ $ticket->support->name ?? '-' }}
                                    </td>

                                    <td class="px-4 py-4">
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium
                                                     {{ $statusClasses[$ticket->status_id] ?? 'bg-light-eval-3 text-light-text dark:bg-dark-eval-2 dark:text-dark-text' }}">
                                            {{ $ticket->status->name }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 text-center">
                                        @if ($ticket->status_id == 3)
                                            <a href="{{ route('feedback.form', $ticket->id) }}"
                                                class="inline-flex items-center px-3 py-1.5 bg-red-500 hover:bg-red-600 dark:bg-red-600 dark:hover:bg-red-700 text-white rounded-lg text-xs font-medium transition-colors">
                                                Berikan Feedback
                                            </a>
                                        @elseif ($ticket->status_id == 1)
                                            <a href="{{ route('DashboardTicketsUser.edit', $ticket->id) }}"
                                                class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-medium
                                                      bg-blue-600 hover:bg-blue-700 text-white transition-colors">
                                                Edit
                                            </a>
                                        @else
                                            <span class="text-xs {{ $muted2 }}">-</span>
                                        @endif
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Mobile --}}
                <div class="lg:hidden" id="mobile-wrapper">
                    <div class="p-4 space-y-4">

                        {{-- Search & PerPage --}}
                        <div class="flex items-center gap-2 w-full">
                            <input type="text" id="mobileSearch" placeholder="Search..."
                                class="flex-1 min-w-0 px-3 py-2 text-xs rounded-lg border
                                          bg-light-bg dark:bg-dark-eval-2
                                          text-light-text dark:text-dark-text
                                          placeholder:text-light-text-muted dark:placeholder:text-dark-text-secondary
                                          border-light-eval-3 dark:border-dark-eval-2
                                          focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500/40" />

                            <select id="mobilePerPage"
                                class="px-3 pr-7 py-2 text-xs min-w-[70px] rounded-lg border appearance-none
                                           bg-light-bg dark:bg-dark-eval-2
                                           text-light-text dark:text-dark-text
                                           border-light-eval-3 dark:border-dark-eval-2
                                           focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500/40">
                                <option value="3" selected>3</option>
                                <option value="5">5</option>
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="all">All</option>
                            </select>
                        </div>

                        {{-- Cards Container --}}
                        <div id="mobileCards" class="space-y-4">
                            @php
                                $sortedMobile = $myTicket->sort(function ($a, $b) {
                                    $priorityA = $a->status_id == 3 ? 1 : (in_array($a->status_id, [1, 2]) ? 2 : 3);
                                    $priorityB = $b->status_id == 3 ? 1 : (in_array($b->status_id, [1, 2]) ? 2 : 3);

                                    if ($priorityA != $priorityB) {
                                        return $priorityA - $priorityB;
                                    }
                                    return $b->request_date <=> $a->request_date;
                                });
                            @endphp

                            @foreach ($sortedMobile as $ticket)
                                <div class="ticket-card rounded-xl p-4 border shadow-sm overflow-hidden
                                            bg-light-eval-1 dark:bg-dark-eval-2
                                            border-light-eval-3 dark:border-dark-eval-2
                                            {{ $ticket->status_id == 3 ? 'ring-2 ring-blue-500/40 dark:ring-blue-400/35' : '' }}"
                                    data-code="{{ strtolower($ticket->ticket_code) }}"
                                    data-category="{{ strtolower($ticket->Category?->name ?? '') }}"
                                    data-problem="{{ strtolower($ticket->problem) }}"
                                    data-status="{{ strtolower($ticket->status->status_name) }}"
                                    data-support="{{ strtolower($ticket->support->name ?? '') }}"
                                    data-feedback="{{ strtolower($ticket->feedback->description ?? '') }}"
                                    data-priority="{{ $ticket->status_id == 3
                                        ? 4
                                        : ($ticket->status_id == 1 || $ticket->status_id == 2
                                            ? 3
                                            : ($ticket->status_id == 5
                                                ? 2
                                                : 1)) }}">

                                    {{-- Header --}}
                                    <div class="flex justify-between items-start mb-3">
                                        <div>
                                            <div class="text-sm font-semibold text-light-text dark:text-dark-text">
                                                {{ $ticket->ticket_code }}
                                            </div>
                                            <div class="text-xs mt-0.5 {{ $muted2 }}">
                                                {{ $ticket->user->name ?? '-' }}
                                            </div>
                                        </div>

                                        {{-- Status Badge --}}
                                        <span
                                            class="shrink-0 inline-flex items-center px-2 py-1 rounded-md text-xs font-medium
             {{ $statusClasses[$ticket->status_id] ?? 'bg-light-eval-3 text-light-text dark:bg-dark-eval-2 dark:text-dark-text' }}">
                                            {{ $ticket->status->status_name }}
                                        </span>

                                    </div>

                                    {{-- Content --}}
                                    <div class="space-y-2 text-sm">
                                        <div class="flex flex-wrap items-start gap-x-2 gap-y-1">
                                            <span
                                                class="shrink-0 w-[80px] text-xs {{ $muted2 }}">Category:</span>
                                            <span
                                                class="min-w-0 flex-1 text-xs text-light-text dark:text-dark-text break-words whitespace-normal">
                                                {{ $ticket->Category?->name ?? '-' }}
                                            </span>
                                        </div>


                                        <div class="flex flex-wrap items-start gap-x-2 gap-y-1">
                                            <span class="shrink-0 w-[80px] text-xs {{ $muted2 }}">Problem:</span>
                                            <span
                                                class="min-w-0 flex-1 text-xs text-light-text dark:text-dark-text break-words whitespace-normal">
                                                {{ $ticket->problem }}
                                            </span>
                                        </div>


                                        <div class="flex flex-wrap items-start gap-x-2 gap-y-1">
                                            <span class="shrink-0 w-[80px] text-xs {{ $muted2 }}">Date:</span>
                                            <span
                                                class="min-w-0 flex-1 text-xs text-light-text dark:text-dark-text break-words whitespace-normal">
                                                {{ $ticket->request_date ? $ticket->request_date->format('d M Y') : '-' }}
                                            </span>
                                        </div>

                                        @if ($ticket->solution)
                                            <div class="flex flex-wrap items-start gap-x-2 gap-y-1">
                                                <span
                                                    class="shrink-0 w-[80px] text-xs {{ $muted2 }}">Solution:</span>
                                                <span
                                                    class="min-w-0 flex-1 text-xs text-light-text dark:text-dark-text break-words whitespace-normal">
                                                    {{ $ticket->solution }}
                                                </span>
                                            </div>
                                        @endif

                                        @if ($ticket->feedback)
                                            <div class="flex flex-wrap items-start gap-x-2 gap-y-1">
                                                <span
                                                    class="shrink-0 w-[80px] text-xs {{ $muted2 }}">Feedback:</span>
                                                <span
                                                    class="min-w-0 flex-1 text-xs text-light-text dark:text-dark-text break-words whitespace-normal">
                                                    {{ $ticket->feedback->description }}
                                                </span>
                                            </div>
                                        @endif

                                        @if ($ticket->support)
                                            <div class="flex flex-wrap items-start gap-x-2 gap-y-1">
                                                <span
                                                    class="shrink-0 w-[80px] text-xs {{ $muted2 }}">Support:</span>
                                                <span
                                                    class="min-w-0 flex-1 text-xs text-light-text dark:text-dark-text break-words whitespace-normal">
                                                    {{ $ticket->support->name }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Action --}}
                                    @if ($ticket->status_id == 3)
                                        <div class="mt-3 pt-3 border-t border-light-eval-3 dark:border-dark-eval-2">
                                            <a href="{{ route('feedback.form', $ticket->id) }}"
                                                class="block w-full text-center px-4 py-2 bg-red-500 hover:bg-red-600
            dark:bg-red-600 dark:hover:bg-red-700 text-white rounded-lg
            text-sm font-medium transition-colors shadow-sm">
                                                Berikan Feedback
                                            </a>
                                        </div>
                                    @elseif ($ticket->status_id == 1)
                                        <div class="mt-3 pt-3 border-t border-light-eval-3 dark:border-dark-eval-2">
                                            <a href="{{ route('DashboardTicketsUser.edit', $ticket->id) }}"
                                                class="block w-full text-center px-4 py-2 rounded-lg text-sm font-medium
                                                      bg-blue-600 hover:bg-blue-700 text-white transition-colors shadow-sm">
                                                Edit
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        {{-- Pagination --}}
                        <div id="mobilePagination"
                            class="flex flex-col sm:flex-row justify-between items-center gap-3 pt-3
                                    border-t border-light-eval-3 dark:border-dark-eval-2">
                            <div id="mobileInfo" class="text-xs {{ $muted }} font-medium"></div>
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
                <label class="block text-sm font-medium mb-1 {{ $muted }}">User</label>
                <input type="text" value="{{ Auth::user()->name }}" readonly
                    class="w-full px-3 py-2 rounded-lg border
                              bg-light-eval-1 dark:bg-dark-eval-2
                              text-light-text dark:text-dark-text text-sm
                              border-light-eval-3 dark:border-dark-eval-2">
                <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
            </div>

            <div>
                <label class="block text-sm font-medium mb-1 {{ $muted }}">
                    Category <span class="text-red-500">*</span>
                </label>
                <select name="category_id" required
                    class="w-full px-3 py-2 rounded-lg border
                               bg-light-bg dark:bg-dark-eval-2
                               text-light-text dark:text-dark-text text-sm
                               border-light-eval-3 dark:border-dark-eval-2
                               focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500/40">
                    <option value="">-- Select Category --</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1 {{ $muted }}">
                    Problem Description <span class="text-red-500">*</span>
                </label>
                <textarea name="problem" rows="3" required placeholder="Describe your issue..."
                    class="w-full px-3 py-2 rounded-lg border
                                 bg-light-bg dark:bg-dark-eval-2
                                 text-light-text dark:text-dark-text text-sm
                                 border-light-eval-3 dark:border-dark-eval-2
                                 placeholder:text-light-text-muted dark:placeholder:text-dark-text-secondary
                                 focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500/40"></textarea>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1 {{ $muted }}">
                    Attachment (Image/Video, Max 10MB)
                </label>
                <input type="file" name="image" accept=".jpg,.jpeg,.png,.mp4"
                    class="block w-full text-sm text-light-text dark:text-dark-text
                              file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0
                              file:text-sm file:font-medium
                              file:bg-light-eval-2 dark:file:bg-dark-eval-2
                              file:text-light-text dark:file:text-dark-text
                              hover:file:bg-light-eval-3 dark:hover:file:bg-dark-eval-3
                              file:cursor-pointer cursor-pointer">
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <button type="button" onclick="closeModal('ticketModal')"
                    class="px-4 py-2 rounded-lg text-sm font-medium border
                               border-light-eval-3 dark:border-dark-eval-2
                               text-light-text-secondary dark:text-dark-text-secondary
                               hover:bg-light-eval-1 dark:hover:bg-dark-eval-2 transition-colors">
                    Batal
                </button>

                <button type="submit"
                    class="px-4 py-2 rounded-lg text-sm font-medium
                               bg-blue-600 hover:bg-blue-700 text-white transition-colors">
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

    let dtInstance = null;

    const tableEl = document.querySelector(".datatable");

    // =========================
    // DATA TABLE INIT SAFE
    // =========================
    function initDataTable() {
        if (!tableEl) return;

        // kalau sudah pernah di-init → destroy dulu
        if ($.fn.dataTable.isDataTable(tableEl)) {
            $(tableEl).DataTable().destroy();
        }

        // init ulang
        dtInstance = new DataTable(tableEl);
    }

    function destroyDataTable() {
        if (!tableEl) return;

        if ($.fn.dataTable.isDataTable(tableEl)) {
            $(tableEl).DataTable().destroy();
        }
    }

    function handleTableMode() {
        if (!tableEl) return;

        if (window.innerWidth >= 1024) {
            initDataTable();
        } else {
            destroyDataTable();
        }
    }

    // =========================
    // MOBILE PAGINATION SYSTEM
    // =========================
    let currentPage = 1;
    let perPage = 3;
    let filteredCards = [];

    const mobileSearch = document.getElementById("mobileSearch");
    const mobilePerPage = document.getElementById("mobilePerPage");
    const mobilePagination = document.getElementById("mobilePagination");
    const mobilePaginationButtons = document.getElementById("mobilePaginationButtons");
    const mobileInfo = document.getElementById("mobileInfo");
    const mobileCardsContainer = document.getElementById("mobileCards");

    function getAllCards() {
        return Array.from(document.querySelectorAll(".ticket-card"));
    }

    function filterCards() {
        const searchTerm = (mobileSearch?.value || "").toLowerCase().trim();

        const allCards = getAllCards();

        filteredCards = allCards.filter(card => {
            const searchText = [
                card.dataset.code || '',
                card.dataset.category || '',
                card.dataset.problem || '',
                card.dataset.status || '',
                card.dataset.support || '',
                card.dataset.feedback || ''
            ].join(" ").toLowerCase();

            return searchText.includes(searchTerm);
        });

        // sort priority
        filteredCards.sort((a, b) =>
            (parseInt(b.dataset.priority) || 0) - (parseInt(a.dataset.priority) || 0)
        );

        currentPage = 1;
        displayCards();
    }

    function displayCards() {
        const allCards = getAllCards();

        allCards.forEach(card => card.style.display = "none");

        // ALL MODE
        if (mobilePerPage?.value === "all") {
            filteredCards.forEach(card => card.style.display = "block");

            if (mobilePagination) mobilePagination.style.display = "none";
            return;
        }

        const start = (currentPage - 1) * perPage;
        const end = start + perPage;

        filteredCards
            .slice(start, end)
            .forEach(card => card.style.display = "block");

        updatePagination();
        updateInfo(start + 1, Math.min(end, filteredCards.length), filteredCards.length);

        if (mobilePagination) mobilePagination.style.display = "flex";
    }

    function updatePagination() {
        if (!mobilePaginationButtons) return;

        const totalPages = Math.ceil(filteredCards.length / perPage);

        mobilePaginationButtons.innerHTML = "";

        if (totalPages <= 1) {
            mobilePaginationButtons.style.display = "none";
            return;
        }

        mobilePaginationButtons.style.display = "flex";

        const isDark = document.documentElement.classList.contains("dark");

        const base = "px-3 py-1.5 text-xs rounded-lg font-medium transition-colors";

        const normal = isDark
            ? "border border-gray-600 bg-dark-eval-2 text-gray-300 hover:bg-dark-eval-3"
            : "border border-gray-300 bg-white text-gray-700 hover:bg-gray-100";

        const active = isDark
            ? "bg-gray-100 text-gray-900 border-none"
            : "bg-gray-900 text-white border-none";

        const disabled = "opacity-50 cursor-not-allowed";

        // PREV
        const prev = document.createElement("button");
        prev.innerHTML = "‹";
        prev.className = `${base} ${normal} ${currentPage === 1 ? disabled : ""}`;
        prev.disabled = currentPage === 1;
        if (!prev.disabled) prev.onclick = () => changePage(currentPage - 1);
        mobilePaginationButtons.appendChild(prev);

        // pages
        let startPage = Math.max(1, currentPage - 2);
        let endPage = Math.min(totalPages, startPage + 4);

        for (let i = startPage; i <= endPage; i++) {
            const btn = document.createElement("button");
            btn.textContent = i;

            btn.className = `${base} ${i === currentPage ? active : normal}`;

            if (i === currentPage) {
                btn.disabled = true;
            } else {
                btn.onclick = () => changePage(i);
            }

            mobilePaginationButtons.appendChild(btn);
        }

        // NEXT
        const next = document.createElement("button");
        next.innerHTML = "›";
        next.className = `${base} ${normal} ${currentPage === totalPages ? disabled : ""}`;
        next.disabled = currentPage === totalPages;
        if (!next.disabled) next.onclick = () => changePage(currentPage + 1);
        mobilePaginationButtons.appendChild(next);
    }

    function updateInfo(start, end, total) {
        if (!mobileInfo) return;

        mobileInfo.textContent =
            total === 0
                ? "No tickets found"
                : `Showing ${start}-${end} of ${total} tickets`;
    }

    function changePage(page) {
        currentPage = page;
        displayCards();

        mobileCardsContainer?.scrollIntoView({
            behavior: "smooth",
            block: "start"
        });
    }

    // =========================
    // EVENTS
    // =========================
    mobileSearch?.addEventListener("input", filterCards);

    mobilePerPage?.addEventListener("change", () => {
        perPage =
            mobilePerPage.value === "all"
                ? 999999
                : parseInt(mobilePerPage.value);

        currentPage = 1;
        displayCards();
    });

    // first load
    if (window.innerWidth < 1024) {
        filterCards();
    } else {
        initDataTable();
    }

    // resize handler (ANTI DOUBLE INIT FIXED)
    let resizeTimer;

    window.addEventListener("resize", () => {
        clearTimeout(resizeTimer);

        resizeTimer = setTimeout(() => {
            handleTableMode();

            if (window.innerWidth < 1024) {
                filterCards();
            }
        }, 200);
    });

});
</script>
</x-app-layout>
