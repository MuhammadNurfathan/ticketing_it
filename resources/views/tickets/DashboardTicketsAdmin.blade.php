<x-app-layout>

    <x-slot name="header">
        @auth
            @if (Auth::user()->role_id != 3)
                <div class="flex items-center justify-between gap-4">

                    <h2 class="font-semibold text-xl leading-tight text-light-text dark:text-dark-text">
                        Ticket Dashboard
                    </h2>

                    <a href="{{ route('DashboardTicketsAdmin.create') }}"
                        class="inline-flex items-center justify-center px-4 py-2.5 rounded-lg text-sm font-medium
                               bg-blue-600 hover:bg-blue-700 text-white transition-colors shadow-sm">
                        Tambah Ticket
                    </a>

                </div>
            @endif
        @endauth
    </x-slot>

    @php
        $page = "min-h-screen bg-light-bg dark:bg-dark-bg text-light-text dark:text-dark-text";
        $wrap = "w-full px-4 sm:px-6 lg:px-8 py-6 space-y-6";

        $card = "rounded-2xl border shadow-sm
                 bg-light-eval-1 dark:bg-dark-eval-1
                 border-light-eval-3 dark:border-dark-eval-2";

        $muted = "text-light-text-secondary dark:text-dark-text-secondary";
        $muted2 = "text-light-text-muted dark:text-dark-text-secondary";

        $input = "w-full px-3 py-2 rounded-lg border
                  bg-light-bg dark:bg-dark-eval-2
                  text-light-text dark:text-dark-text
                  border-light-eval-3 dark:border-dark-eval-2";

        $btnPrimary = "inline-flex items-center justify-center px-4 py-2.5 rounded-lg text-sm font-medium
                       bg-blue-600 hover:bg-blue-700 text-white transition-colors shadow-sm";

        $btnGhost = "px-3 py-2 rounded-lg text-sm font-medium border transition-colors
                     border-light-eval-3 dark:border-dark-eval-2
                     text-light-text-secondary dark:text-dark-text-secondary";

        $tableWrap = "rounded-2xl border shadow-sm
                      bg-light-eval-1 dark:bg-dark-eval-1
                      border-light-eval-3 dark:border-dark-eval-2";

        $thead = "bg-light-eval-2 dark:bg-dark-eval-2 border-b
                  border-light-eval-3 dark:border-dark-eval-2";

        $th = "px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider $muted";
        $td = "px-4 py-3 text-sm $muted";

        $sectionTitle = "text-base sm:text-lg font-semibold text-light-text dark:text-dark-text";
        $badgeBase = "inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold";

        $tabs = [
            'waiting' => [
                'label' => 'Waiting',
                'count' => $stats['waiting'] ?? 0,
                'badge' => 'bg-yellow-500/15 text-yellow-700 dark:bg-yellow-400/10 dark:text-yellow-300',
                'accent' => 'bg-yellow-500',
                'subtitle' => 'Tickets yang menunggu dieksekusi',
            ],

            'in_progress' => [
                'label' => 'In Progress',
                'count' => $stats['in_progress'] ?? 0,
                'badge' => 'bg-blue-600/10 text-blue-700 dark:bg-blue-400/10 dark:text-blue-300',
                'accent' => 'bg-blue-600',
                'subtitle' => 'Tickets yang sedang dikerjakan',
            ],

            'done' => [
                'label' => 'Done',
                'count' => $stats['done'] ?? 0,
                'badge' => 'bg-green-600/10 text-green-700 dark:bg-green-400/10 dark:text-green-300',
                'accent' => 'bg-green-600',
                'subtitle' => 'Riwayat tickets yang sudah selesai',
            ],

            'void' => [
                'label' => 'Void',
                'count' => $stats['void'] ?? 0,
                'badge' => 'bg-red-600/10 text-red-700 dark:bg-red-400/10 dark:text-red-300',
                'accent' => 'bg-red-600',
                'subtitle' => 'Tickets yang dibatalkan',
            ],
        ];
    @endphp

    <div class="{{ $page }}">

        <div class="{{ $wrap }}"
            x-data="{ tab: 'waiting' }">

            {{-- FILTER --}}
            <x-dashboard-ticket.filter
                :start="$start"
                :end="$end"
                :card="$card"
                :input="$input"
                :btnPrimary="$btnPrimary"
                :btnGhost="$btnGhost" />

            {{-- TABS --}}
            <x-dashboard-ticket.stat-tabs
                :tabs="$tabs"
                :muted2="$muted2" />

            {{-- WAITING --}}
            <x-dashboard-ticket.waiting-table
                :tickets="$waitingTickets"
                :tabs="$tabs"
                :thead="$thead"
                :th="$th"
                :td="$td"
                :muted="$muted"
                :muted2="$muted2"
                :tableWrap="$tableWrap"
                :sectionTitle="$sectionTitle"
                :badgeBase="$badgeBase"
                :btnGhost="$btnGhost"
                :card="$card" />

            {{-- PROGRESS --}}
            <x-dashboard-ticket.progress-table
                :tickets="$inProgressTickets"
                :tabs="$tabs"
                :thead="$thead"
                :th="$th"
                :td="$td"
                :muted="$muted"
                :muted2="$muted2"
                :tableWrap="$tableWrap"
                :sectionTitle="$sectionTitle"
                :badgeBase="$badgeBase"
                :btnGhost="$btnGhost"
                :card="$card" />

            {{-- DONE --}}
            <x-dashboard-ticket.done-table
                :tickets="$doneTickets"
                :tabs="$tabs"
                :thead="$thead"
                :th="$th"
                :td="$td"
                :muted="$muted"
                :muted2="$muted2"
                :tableWrap="$tableWrap"
                :sectionTitle="$sectionTitle"
                :badgeBase="$badgeBase" />

            {{-- VOID --}}
            <x-dashboard-ticket.void-table
                :tickets="$voidTickets"
                :tabs="$tabs"
                :thead="$thead"
                :th="$th"
                :td="$td"
                :muted="$muted"
                :muted2="$muted2"
                :tableWrap="$tableWrap"
                :sectionTitle="$sectionTitle"
                :badgeBase="$badgeBase" />

        </div>

    </div>

    <x-dashboard-ticket.script />

</x-app-layout>

