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
                        Create Ticket
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
                  border-light-eval-3 dark:border-dark-eval-2
                  focus:ring-2 focus:ring-blue-500/25 focus:border-blue-500/40";

        $btnPrimary = "inline-flex items-center justify-center px-4 py-2.5 rounded-lg text-sm font-medium
                       bg-blue-600 hover:bg-blue-700 text-white transition-colors shadow-sm";

        $btnGhost = "px-3 py-2 rounded-lg text-sm font-medium border transition-colors
                     border-light-eval-3 dark:border-dark-eval-2
                     text-light-text-secondary dark:text-dark-text-secondary
                     hover:bg-light-eval-2 dark:hover:bg-dark-eval-2";

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
             x-data="{
                tab: 'waiting',
                dt: null,
                initDT() {
                    this.dt = new DataTable('.datatable', {
                        responsive: false,
                        pageLength: 3,
                        lengthMenu: [
                            [3, 5, 10, 25, 50, -1],
                            [3, 5, 10, 25, 50, 'All']
                        ],
                        order: [[0, 'desc']],
                        layout: {
                            topStart: 'pageLength',
                            topEnd: 'search',
                            bottomStart: 'info',
                            bottomEnd: 'paging'
                        }
                    });
                },
                onTabChange() {
                    setTimeout(() => {
                        try {
                            document.querySelectorAll('table.datatable').forEach(t => {
                                t.style.width = '100%';
                            });
                            window.dispatchEvent(new Event('resize'));
                        } catch (e) {}
                    }, 50);
                }
             }"
             x-init="initDT()"
        >

            {{-- FILTER DATE --}}
            <form method="GET" action="{{ route('DashboardTicketsAdmin.index') }}"
                  class="{{ $card }} p-4 sm:p-5">
                <div class="flex items-end gap-4">
                    <div>
                        <label class="block text-sm font-semibold mb-1 text-light-text dark:text-dark-text">
                            Request Date
                        </label>
                        <input type="date" name="start_date" value="{{ $start }}"
                               class="date-input w-56 {{ $input }}">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold mb-1 text-light-text dark:text-dark-text">
                            End Date
                        </label>
                        <input type="date" name="end_date" value="{{ $end }}"
                               class="date-input w-56 {{ $input }}">
                    </div>

                    <div class="flex items-center gap-2">
                        <button type="submit" class="{{ $btnPrimary }}">Filter</button>
                        <a href="{{ route('DashboardTicketsAdmin.index') }}" class="{{ $btnGhost }}">Reset</a>
                    </div>
                </div>
            </form>

            {{-- STAT TABS --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach ($tabs as $key => $t)
                    <button type="button"
                        @click="tab='{{ $key }}'; onTabChange(); $nextTick(() => document.getElementById('table-area')?.scrollIntoView({behavior:'smooth', block:'start'}))"
                        class="group text-left rounded-2xl border shadow-sm p-5 transition-all duration-200
                               bg-light-eval-1 dark:bg-dark-eval-1
                               border-light-eval-3 dark:border-dark-eval-2
                               hover:-translate-y-0.5 hover:shadow-md focus:outline-none"
                        :class="tab === '{{ $key }}' ? 'ring-2 ring-blue-500/25 dark:ring-blue-400/20 border-blue-500/30' : ''">

                        <div class="h-1.5 w-full rounded-full {{ $t['accent'] }}"
                             :class="tab === '{{ $key }}' ? 'opacity-100' : 'opacity-50'"></div>

                        <div class="mt-4">
                            <div class="text-sm font-semibold text-light-text dark:text-dark-text">
                                {{ $t['label'] }}
                            </div>
                            <div class="text-xs mt-0.5 {{ $muted2 }}">
                                Click to filter
                            </div>
                        </div>

                        <div class="mt-4 text-3xl font-bold tracking-tight text-light-text dark:text-dark-text">
                            {{ $t['count'] }}
                        </div>
                    </button>
                @endforeach
            </div>

            <div id="table-area" class="space-y-6">

                {{-- ================= WAITING ================= --}}
                <div x-show="tab==='waiting'" x-cloak class="{{ $tableWrap }} p-4 sm:p-5">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <div class="{{ $sectionTitle }}">Waiting Tickets</div>
                            <div class="text-xs mt-1 {{ $muted2 }}">{{ $tabs['waiting']['subtitle'] }}</div>
                        </div>
                        <span class="{{ $badgeBase }} {{ $tabs['waiting']['badge'] }}">Waiting</span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="datatable w-full text-light-text dark:text-dark-text">
                            <thead class="{{ $thead }}">
                                <tr>
                                    <th class="{{ $th }}">Ticket Code</th>
                                    <th class="{{ $th }}">Requestor</th>
                                    <th class="{{ $th }}">Department</th>
                                    <th class="{{ $th }}">Location</th>
                                    <th class="{{ $th }}">Category</th>
                                    <th class="{{ $th }}">Problem</th>
                                    <th class="{{ $th }}">Request Date</th>

                                    @auth
                                        @if (Auth::user()->role_id != 3)
                                            <th class="{{ $th }} text-center">Action</th>
                                        @endif
                                    @endauth
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-light-eval-3 dark:divide-dark-eval-2">
                                @foreach ($waitingTickets as $ticket)
                                    <tr class="hover:bg-light-eval-2 dark:hover:bg-dark-eval-2 transition-colors">
                                        <td class="px-4 py-3 text-sm font-semibold text-light-text dark:text-dark-text">
                                            {{ $ticket->ticket_code }}
                                        </td>
                                        <td class="{{ $td }}">{{ $ticket->nama_pembuat ?? '-' }}</td>
                                        <td class="{{ $td }}">{{ $ticket->user?->department?->name ?? '-' }}</td>
                                        <td class="{{ $td }}">{{ $ticket->user?->department?->location?->name ?? '-' }}</td>
                                        <td class="{{ $td }}">{{ $ticket->category?->name ?? '-' }}</td>
                                        <td class="px-4 py-3 text-sm {{ $muted }} max-w-xs break-words">{{ $ticket->problem }}</td>
                                        <td class="{{ $td }}">{{ $ticket->request_date?->format('Y-m-d H:i:s') ?? '-' }}</td>

                                        @auth
                                            @if (Auth::user()->role_id != 3)
                                                <td class="px-4 py-3 text-center" x-data="{ open: false }">
                                                    <button @click="open = true"
                                                        class="inline-flex items-center justify-center px-3 py-1.5 rounded-lg text-xs font-semibold
                                                               bg-light-eval-3 hover:bg-light-eval-4
                                                               dark:bg-dark-eval-2 dark:hover:bg-dark-eval-3
                                                               text-light-text dark:text-dark-text transition-colors">
                                                        Void
                                                    </button>

                                                    <a href="{{ route('DashboardTicketsAdmin.edit', $ticket->id) }}"
                                                       class="inline-flex items-center justify-center px-3 py-1.5 rounded-lg text-xs font-semibold
                                                              bg-blue-600 hover:bg-blue-700 text-white transition-colors ml-1">
                                                        Execution
                                                    </a>

                                                    <div x-show="open" x-cloak
                                                         class="fixed inset-0 flex items-center justify-center bg-black/50 dark:bg-black/70 backdrop-blur-sm z-50">
                                                        <div x-show="open" class="{{ $card }} w-[420px] p-6">
                                                            <h2 class="text-lg font-semibold mb-3 text-light-text dark:text-dark-text">
                                                                Masukkan Catatan Void
                                                            </h2>

                                                            <form action="{{ route('DashboardTicketsAdmin.updateStatus', $ticket->id) }}"
                                                                  method="POST">
                                                                @csrf
                                                                <input type="hidden" name="status_id" value="4">

                                                                <textarea name="notes" rows="3"
                                                                    class="w-full px-3 py-2 rounded-lg border
                                                                           bg-light-bg dark:bg-dark-eval-2
                                                                           border-light-eval-3 dark:border-dark-eval-2
                                                                           text-light-text dark:text-dark-text
                                                                           focus:ring-2 focus:ring-blue-500/25 focus:border-blue-500/40"
                                                                    placeholder="Enter Notes..." required></textarea>

                                                                <div class="flex justify-end mt-4 gap-2">
                                                                    <button type="button" @click="open = false" class="{{ $btnGhost }}">
                                                                        Cancel
                                                                    </button>
                                                                    <button type="submit"
                                                                            class="inline-flex items-center justify-center px-4 py-2 rounded-lg text-sm font-medium
                                                                                   bg-red-600 hover:bg-red-700 text-white transition-colors">
                                                                        Save
                                                                    </button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </td>
                                            @endif
                                        @endauth
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- ================= IN PROGRESS ================= --}}
                <div x-show="tab==='in_progress'" x-cloak class="{{ $tableWrap }} p-4 sm:p-5">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <div class="{{ $sectionTitle }}">Tickets In Progress</div>
                            <div class="text-xs mt-1 {{ $muted2 }}">{{ $tabs['in_progress']['subtitle'] }}</div>
                        </div>
                        <span class="{{ $badgeBase }} {{ $tabs['in_progress']['badge'] }}">In Progress</span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="datatable w-full text-light-text dark:text-dark-text">
                            <thead class="{{ $thead }}">
                                <tr>
                                    <th class="{{ $th }}">Ticket Code</th>
                                    <th class="{{ $th }}">Requestor</th>
                                    <th class="{{ $th }}">Department</th>
                                    <th class="{{ $th }}">Location</th>
                                    <th class="{{ $th }}">Assignee</th>
                                    <th class="{{ $th }}">Category</th>
                                    <th class="{{ $th }}">Problem</th>
                                    <th class="{{ $th }}">Request Date</th>
                                    <th class="{{ $th }}">Start Date</th>
                                    <th class="{{ $th }}">End Date</th>

                                    @auth
                                        @if (Auth::user()->role_id != 3)
                                            <th class="{{ $th }} text-center">Action</th>
                                        @endif
                                    @endauth
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-light-eval-3 dark:divide-dark-eval-2">
                                @foreach ($inProgressTickets as $ticket)
                                    <tr class="hover:bg-light-eval-2 dark:hover:bg-dark-eval-2 transition-colors">
                                        <td class="px-4 py-3 text-sm font-semibold text-light-text dark:text-dark-text">
                                            {{ $ticket->ticket_code }}
                                        </td>
                                        <td class="{{ $td }}">{{ $ticket->nama_pembuat ?? '-' }}</td>
                                        <td class="{{ $td }}">{{ $ticket->user?->department?->name ?? '-' }}</td>
                                        <td class="{{ $td }}">{{ $ticket->user?->department?->location?->name ?? '-' }}</td>
                                        <td class="{{ $td }}">{{ $ticket->support?->name ?? '-' }}</td>
                                        <td class="{{ $td }}">{{ $ticket->category?->name ?? '-' }}</td>
                                        <td class="px-4 py-3 text-sm {{ $muted }} max-w-xs break-words">{{ $ticket->problem }}</td>
                                        <td class="{{ $td }}">{{ $ticket->request_date?->format('Y-m-d') ?? '-' }}</td>
                                        <td class="{{ $td }}">{{ $ticket->start_date?->format('Y-m-d H:i:s') ?? '-' }}</td>
                                        <td class="{{ $td }}">{{ $ticket->end_date?->format('Y-m-d H:i:s') ?? '-' }}</td>

                                        @auth
                                            @if (Auth::user()->role_id != 3)
                                                <td class="px-4 py-3 text-center">
                                                    <button type="button"
                                                        class="doneBtn inline-flex items-center justify-center px-3 py-1.5 rounded-lg text-xs font-semibold
                                                               bg-green-600 hover:bg-green-700 text-white transition-colors"
                                                        data-start="{{ $ticket->start_date?->format('Y-m-d H:i:s') }}">
                                                        Done
                                                    </button>

                                                    <div
                                                        class="timeSpentModal fixed inset-0 z-50 hidden items-center justify-center
                                                               bg-black/40 dark:bg-black/60 backdrop-blur-sm">
                                                        <div
                                                            class="modalContent w-[420px]
                                                                   {{ $card }} p-6
                                                                   transform scale-95 opacity-0 transition-all duration-200">
                                                            <h3 class="text-lg font-semibold mb-4 text-light-text dark:text-dark-text">
                                                                Time Spent & Solution
                                                            </h3>

                                                            <div class="mb-4">
                                                                <div class="flex items-center justify-between gap-3">
                                                                    <label class="block text-sm font-medium {{ $muted }}">
                                                                        Time Spent (Menit)
                                                                    </label>
                                                                    <label class="flex items-center text-sm {{ $muted }}">
                                                                        <input type="checkbox" class="manualCheckbox mr-2">
                                                                        Manual Input
                                                                    </label>
                                                                </div>

                                                                <input type="number"
                                                                    class="timeInput w-full mt-2 px-3 py-2 rounded-lg border
                                                                           bg-light-eval-2 dark:bg-dark-eval-2
                                                                           border-light-eval-3 dark:border-dark-eval-2
                                                                           text-light-text dark:text-dark-text"
                                                                    readonly>
                                                            </div>

                                                            <div class="mb-4 hidden notesContainer">
                                                                <label class="block text-sm font-medium {{ $muted }} mb-2">
                                                                    Notes (Kenapa manual) <span class="text-red-500">*</span>
                                                                </label>
                                                                <textarea
                                                                    class="notesInput w-full px-3 py-2 rounded-lg border
                                                                           bg-light-bg dark:bg-dark-eval-2
                                                                           border-light-eval-3 dark:border-dark-eval-2
                                                                           text-light-text dark:text-dark-text"
                                                                    rows="2" placeholder="Enter Notes..."></textarea>
                                                            </div>

                                                            <div class="mb-5">
                                                                <label class="block text-sm font-medium {{ $muted }} mb-2">
                                                                    Solution <span class="text-red-500">*</span>
                                                                </label>
                                                                <textarea
                                                                    class="solutionInput w-full px-3 py-2 rounded-lg border
                                                                           bg-light-bg dark:bg-dark-eval-2
                                                                           border-light-eval-3 dark:border-dark-eval-2
                                                                           text-light-text dark:text-dark-text"
                                                                    rows="3" placeholder="Enter Solution..." required>{{ $ticket->solution ?? '' }}</textarea>
                                                            </div>

                                                            <div class="flex justify-end gap-2">
                                                                <button type="button" class="cancelBtn {{ $btnGhost }}">Cancel</button>
                                                                <button type="button"
                                                                    class="saveBtn inline-flex items-center justify-center px-4 py-2 rounded-lg text-sm font-medium
                                                                           bg-green-600 hover:bg-green-700 text-white transition-colors">
                                                                    Save
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <form class="doneForm"
                                                        action="{{ route('DashboardTicketsAdmin.updateStatusDone', $ticket->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        <input type="hidden" name="status_id" value="3">
                                                        <input type="hidden" name="time_spent" class="hiddenTimeSpent">
                                                        <input type="hidden" name="solution" class="hiddenSolution">
                                                        <input type="hidden" name="notes" class="hiddenNotes">
                                                    </form>
                                                </td>
                                            @endif
                                        @endauth
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- ================= DONE ================= --}}
                <div x-show="tab==='done'" x-cloak class="{{ $tableWrap }} p-4 sm:p-5">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <div class="{{ $sectionTitle }}">Tickets Closed / Done</div>
                            <div class="text-xs mt-1 {{ $muted2 }}">{{ $tabs['done']['subtitle'] }}</div>
                        </div>
                        <span class="{{ $badgeBase }} {{ $tabs['done']['badge'] }}">Done</span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="datatable w-full text-light-text dark:text-dark-text">
                            <thead class="{{ $thead }}">
                                <tr>
                                    <th class="{{ $th }}">Ticket Code</th>
                                    <th class="{{ $th }}">Requestor</th>
                                    <th class="{{ $th }}">Department</th>
                                    <th class="{{ $th }}">Location</th>
                                    <th class="{{ $th }}">Assignee</th>
                                    <th class="{{ $th }}">Category</th>
                                    <th class="{{ $th }}">Problem</th>
                                    <th class="{{ $th }}">Request Date</th>
                                    <th class="{{ $th }}">Time Spent</th>
                                    <th class="{{ $th }}">Feedback</th>
                                    <th class="{{ $th }}">Solution</th>
                                    <th class="{{ $th }}">SLA</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-light-eval-3 dark:divide-dark-eval-2">
                                @foreach ($doneTickets as $ticket)
                                    <tr class="hover:bg-light-eval-2 dark:hover:bg-dark-eval-2 transition-colors">
                                        <td class="px-4 py-3 text-sm font-semibold text-light-text dark:text-dark-text">
                                            {{ $ticket->ticket_code }}
                                        </td>
                                        <td class="{{ $td }}">{{ $ticket->nama_pembuat ?? '-' }}</td>
                                        <td class="{{ $td }}">{{ $ticket->user?->department?->name ?? '-' }}</td>
                                        <td class="{{ $td }}">{{ $ticket->user?->department?->location?->name ?? '-' }}</td>
                                        <td class="{{ $td }}">{{ $ticket->support?->name ?? '-' }}</td>
                                        <td class="{{ $td }}">{{ $ticket->category?->name ?? '-' }}</td>
                                        <td class="px-4 py-3 text-sm {{ $muted }} max-w-xs break-words">{{ $ticket->problem }}</td>
                                        <td class="{{ $td }}">{{ $ticket->request_date?->format('Y-m-d') ?? '-' }}</td>
                                        <td class="{{ $td }}">{{ $ticket->time_spent ?? '-' }} menit</td>
                                        <td class="px-4 py-3 text-sm {{ $muted }} max-w-xs break-words">
                                            {{ $ticket->feedback?->description ?? '-' }}
                                        </td>
                                        <td class="px-4 py-3 text-sm {{ $muted }} max-w-xs break-words">
                                            {{ $ticket->solution ?? '-' }}
                                        </td>
                                        <td class="{{ $td }}">
                                            {{ $ticket->is_late ? 'Late' : 'On Time' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- ================= VOID ================= --}}
                <div x-show="tab==='void'" x-cloak class="{{ $tableWrap }} p-4 sm:p-5">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <div class="{{ $sectionTitle }}">Tickets Void</div>
                            <div class="text-xs mt-1 {{ $muted2 }}">{{ $tabs['void']['subtitle'] }}</div>
                        </div>
                        <span class="{{ $badgeBase }} {{ $tabs['void']['badge'] }}">Void</span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="datatable w-full text-light-text dark:text-dark-text">
                            <thead class="{{ $thead }}">
                                <tr>
                                    <th class="{{ $th }}">Ticket Code</th>
                                    <th class="{{ $th }}">Requestor</th>
                                    <th class="{{ $th }}">Department</th>
                                    <th class="{{ $th }}">Location</th>
                                    <th class="{{ $th }}">Assignee</th>
                                    <th class="{{ $th }}">Category</th>
                                    <th class="{{ $th }}">Problem</th>
                                    <th class="{{ $th }}">Request Date</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-light-eval-3 dark:divide-dark-eval-2">
                                @foreach ($voidTickets as $ticket)
                                    <tr class="hover:bg-light-eval-2 dark:hover:bg-dark-eval-2 transition-colors">
                                        <td class="px-4 py-3 text-sm font-semibold text-light-text dark:text-dark-text">
                                            {{ $ticket->ticket_code ?? '-' }}
                                        </td>
                                        <td class="{{ $td }}">{{ $ticket->nama_pembuat ?? '-' }}</td>
                                        <td class="{{ $td }}">{{ $ticket->user?->department?->name ?? '-' }}</td>
                                        <td class="{{ $td }}">{{ $ticket->user?->department?->location?->name ?? '-' }}</td>
                                        <td class="{{ $td }}">{{ $ticket->support?->name ?? '-' }}</td>
                                        <td class="{{ $td }}">{{ $ticket->category?->name ?? '-' }}</td>
                                        <td class="px-4 py-3 text-sm {{ $muted }} max-w-xs break-words">{{ $ticket->problem ?? '-' }}</td>
                                        <td class="{{ $td }}">{{ $ticket->request_date?->format('Y-m-d') ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <style>
        [x-cloak] { display: none !important; }
        .date-input::-webkit-calendar-picker-indicator { filter: invert(0); cursor: pointer; }
        .dark .date-input::-webkit-calendar-picker-indicator { filter: invert(1); cursor: pointer; }
        .date-input::-webkit-calendar-picker-indicator:hover { opacity: 0.7; }
    </style>

    <script>
        document.querySelectorAll('.doneBtn').forEach(btn => {
            btn.addEventListener('click', function() {
                const modal = this.nextElementSibling;
                const modalContent = modal.querySelector('.modalContent');
                const start = new Date(this.dataset.start);
                const timeInput = modal.querySelector('.timeInput');
                const solutionInput = modal.querySelector('.solutionInput');
                const notesContainer = modal.querySelector('.notesContainer');
                const notesInput = modal.querySelector('.notesInput');
                const manualCheckbox = modal.querySelector('.manualCheckbox');
                const form = modal.parentElement.querySelector('.doneForm');

                modal.classList.remove('hidden');
                setTimeout(() => {
                    modal.classList.add('flex');
                    modalContent.classList.remove('opacity-0', 'scale-95');
                    modalContent.classList.add('opacity-100', 'scale-100');
                }, 10);

                const calcAutoTime = () => {
                    if (!manualCheckbox.checked && start && !isNaN(start)) {
                        const now = new Date();
                        const diff = Math.floor((now - start) / (1000 * 60));
                        timeInput.value = diff > 0 ? diff : 0;
                    }
                };
                calcAutoTime();

                manualCheckbox.addEventListener('change', () => {
                    if (manualCheckbox.checked) {
                        timeInput.removeAttribute('readonly');
                        notesContainer.classList.remove('hidden');
                    } else {
                        timeInput.setAttribute('readonly', true);
                        notesContainer.classList.add('hidden');
                        notesInput.value = '';
                        calcAutoTime();
                    }
                });

                modal.querySelector('.cancelBtn').onclick = () => {
                    modalContent.classList.remove('opacity-100', 'scale-100');
                    modalContent.classList.add('opacity-0', 'scale-95');
                    setTimeout(() => {
                        modal.classList.remove('flex');
                        modal.classList.add('hidden');
                    }, 150);
                };

                modal.querySelector('.saveBtn').onclick = () => {
                    if (!solutionInput.value.trim()) {
                        alert('Kolom Solution wajib diisi!');
                        solutionInput.focus();
                        return;
                    }
                    if (!notesContainer.classList.contains('hidden') && !notesInput.value.trim()) {
                        alert('Kolom Notes wajib diisi saat manual!');
                        notesInput.focus();
                        return;
                    }

                    form.querySelector('.hiddenTimeSpent').value = timeInput.value;
                    form.querySelector('.hiddenSolution').value = solutionInput.value;
                    form.querySelector('.hiddenNotes').value = notesInput.value;
                    form.submit();
                };
            });
        });
    </script>
</x-app-layout>