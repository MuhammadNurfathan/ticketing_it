<x-app-layout>
    {{-- ========================================================= HEADER ========================================================= --}}
    <x-slot name="header">
        @auth
            @if (Auth::user()->role_id != 3)
                <div class="flex items-center justify-between gap-4">
                    <h2 class="font-semibold text-xl leading-tight text-light-text dark:text-dark-text">
                        Project
                    </h2>

                    <button onclick="openModal('projectModal')"
                        class="inline-flex items-center justify-center px-4 py-2.5 rounded-lg text-sm font-medium
                               bg-blue-600 hover:bg-blue-700 text-white transition-colors shadow-sm">
                        Create Project
                    </button>
                </div>
            @endif
        @endauth
    </x-slot>

    @php
        $card = "rounded-2xl border shadow-sm
                 bg-light-eval-1 dark:bg-dark-eval-1
                 border-light-eval-3 dark:border-dark-eval-2";

        $muted = 'text-light-text-secondary dark:text-dark-text-secondary';
        $muted2 = 'text-light-text-muted dark:text-dark-text-secondary';

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

        $thead = "bg-light-eval-2 dark:bg-dark-eval-2 border-b
                  border-light-eval-3 dark:border-dark-eval-2";

        $th = "px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider $muted";
        $td = "px-4 py-3 text-sm $muted";

        $badgeBase = 'inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold';

        $tabs = [
            'waiting' => [
                'label' => 'Waiting',
                'count' => $stats['waiting'] ?? 0,
                'badge' => 'bg-yellow-500/15 text-yellow-700 dark:bg-yellow-400/10 dark:text-yellow-300',
                'accent' => 'bg-yellow-500',
                'subtitle' => 'Project yang menunggu dieksekusi',
            ],
            'in_progress' => [
                'label' => 'In Progress',
                'count' => $stats['in_progress'] ?? 0,
                'badge' => 'bg-blue-600/10 text-blue-700 dark:bg-blue-400/10 dark:text-blue-300',
                'accent' => 'bg-blue-600',
                'subtitle' => 'Project yang sedang berjalan',
            ],
            'resolved' => [
                'label' => 'resolved',
                'count' => $stats['resolved'] ?? 0,
                'badge' => 'bg-green-600/10 text-green-700 dark:bg-green-400/10 dark:text-green-300',
                'accent' => 'bg-green-600',
                'subtitle' => 'Project yang sudah selesai',
            ],
            'void' => [
                'label' => 'Void',
                'count' => $stats['void'] ?? 0,
                'badge' => 'bg-red-600/10 text-red-700 dark:bg-red-400/10 dark:text-red-300',
                'accent' => 'bg-red-600',
                'subtitle' => 'Project yang dibatalkan',
            ],
            'pending' => [
                'label' => 'Pending',
                'count' => $stats['pending'] ?? 0,
                'badge' => 'bg-orange-600/10 text-orange-700 dark:bg-orange-400/10 dark:text-orange-300',
                'accent' => 'bg-orange-600',
                'subtitle' => 'Project yang sedang pending',
            ],
        ];

        // ✅ mapping status id by type (biar ga hardcode)
        $statusMap = collect($statuses ?? [])->keyBy('type');
        $stWaiting = optional($statusMap->get('waiting'))->id;
        $stInProgress = optional($statusMap->get('in_progress'))->id;
        $stresolved = optional($statusMap->get('resolved'))->id;
        $stVoid = optional($statusMap->get('void'))->id;
        $stPending = optional($statusMap->get('pending'))->id;
    @endphp

    <div class="px-4 sm:px-6 lg:px-8 py-6 space-y-6" x-data="{
        tab: 'waiting',
        dts: [],
        initDT() {
            if (this.dts.length) return;
    
            document.querySelectorAll('table.datatable').forEach((table) => {
                if (table.dataset.dtInited === '1') return;
    
                const dt = new DataTable(table, {
                    responsive: false,
                    pageLength: 3,
                    lengthMenu: [
                        [3, 5, 10, 25, 50, -1],
                        [3, 5, 10, 25, 50, 'All']
                    ],
                    order: [
                        [0, 'desc']
                    ],
                    layout: {
                        topStart: 'pageLength',
                        topEnd: 'search',
                        bottomStart: 'info',
                        bottomEnd: 'paging'
                    }
                });
    
                table.dataset.dtInited = '1';
                this.dts.push(dt);
            });
        },
        onTab() {
            setTimeout(() => {
                try {
                    document.querySelectorAll('table.datatable').forEach(t => t.style.width = '100%');
                    window.dispatchEvent(new Event('resize'));
                } catch (e) {}
            }, 60);
        }
    }" x-init="initDT()">

        {{-- ========================================================= FILTER DATE ========================================================= --}}
        <form method="GET" action="{{ route('project.index') }}" class="{{ $card }} p-4 sm:p-5">
            <div class="flex flex-col lg:flex-row lg:items-end gap-4">
                <div class="w-full sm:w-auto">
                    <label class="block text-sm font-semibold mb-1 text-light-text dark:text-dark-text">
                        Start Date
                    </label>
                    <input type="date" name="start_date" value="{{ $start }}"
                        class="date-input w-56 max-w-full {{ $input }}">
                </div>

                <div class="w-full sm:w-auto">
                    <label class="block text-sm font-semibold mb-1 text-light-text dark:text-dark-text">
                        End Date
                    </label>
                    <input type="date" name="end_date" value="{{ $end }}"
                        class="date-input w-56 max-w-full {{ $input }}">
                </div>

                <div class="flex items-center gap-2">
                    <button type="submit" class="{{ $btnPrimary }}">Filter</button>
                    <a href="{{ route('project.index') }}" class="{{ $btnGhost }}">Reset</a>
                </div>
            </div>
        </form>

        {{-- ========================================================= STAT TABS ========================================================= --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
            @foreach ($tabs as $key => $t)
                <button type="button"
                    @click="tab='{{ $key }}'; onTab(); $nextTick(() => document.getElementById('table-area')?.scrollIntoView({behavior:'smooth', block:'start'}))"
                    class="group text-left rounded-2xl border shadow-sm p-5 transition-all duration-200
                           bg-light-eval-1 dark:bg-dark-eval-1
                           border-light-eval-3 dark:border-dark-eval-2
                           hover:-translate-y-0.5 hover:shadow-md focus:outline-none"
                    :class="tab === '{{ $key }}'
                        ?
                        'ring-2 ring-blue-500/25 dark:ring-blue-400/20 border-blue-500/30' :
                        ''">

                    <div class="h-1.5 w-full rounded-full {{ $t['accent'] }}"
                        :class="tab === '{{ $key }}' ? 'opacity-100' : 'opacity-50'"></div>

                    <div class="mt-4">
                        <div class="text-sm font-semibold text-light-text dark:text-dark-text">
                            {{ $t['label'] }}
                        </div>
                        <div class="text-xs mt-0.5 {{ $muted2 }}">
                            {{ $t['subtitle'] }}
                        </div>
                    </div>

                    <div class="mt-4 text-3xl font-bold tracking-tight text-light-text dark:text-dark-text">
                        {{ $t['count'] }}
                    </div>
                </button>
            @endforeach
        </div>

        {{-- ========================================================= TABLE AREA ========================================================= --}}
        <div id="table-area" class="space-y-6">
            
            {{-- ================= IN PROGRESS ================= --}}
            <div x-show="tab==='in_progress'" x-cloak class="{{ $card }} p-4 sm:p-5">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <div class="text-base sm:text-lg font-semibold text-light-text dark:text-dark-text">
                            Project In Progress
                        </div>
                        <div class="text-xs mt-1 {{ $muted2 }}">{{ $tabs['in_progress']['subtitle'] }}</div>
                    </div>
                    <span class="{{ $badgeBase }} {{ $tabs['in_progress']['badge'] }}">In Progress</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="datatable w-full text-light-text dark:text-dark-text">
                        <thead class="{{ $thead }}">
                            <tr>
                                <th class="{{ $th }}">Project Code</th>
                                <th class="{{ $th }}">Project Name</th>
                                <th class="{{ $th }}">Requestor Name</th>
                                <th class="{{ $th }}">Priority</th>
                                <th class="{{ $th }}">Progress %</th>
                                <th class="{{ $th }}">Progress Date</th>
                                <th class="{{ $th }}">Description</th>
                                <th class="{{ $th }}">Start Date</th>
                                <th class="{{ $th }}">End Date</th>
                                <th class="{{ $th }}">Actual Start</th>
                                <th class="{{ $th }}">Pending Minutes</th>
                                <th class="{{ $th }}">History</th>
                                <th class="{{ $th }} text-center">Action</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-light-eval-3 dark:divide-dark-eval-2">
                            @foreach ($inProgressProject as $project)
                                <tr class="transition-colors hover:bg-light-eval-2 dark:hover:bg-dark-eval-2">
                                    <td
                                        class="px-4 py-3 text-sm font-semibold text-center text-light-text dark:text-dark-text">
                                        {{ $project->project_code ?? '-' }}
                                    </td>
                                    <td class="{{ $td }}">{{ $project->project_name ?? '-' }}</td>
                                    <td class="{{ $td }}">{{ $project->requestor->name ?? '-' }}</td>
                                    <td class="{{ $td }} text-center">{{ $project->priority->name ?? '-' }}
                                    </td>
                                    <td class="{{ $td }} text-right">
                                        {{ $project->progress_percent ?? '-' }}%</td>
                                    <td class="{{ $td }} text-center">{{ $project->progress_date ?? '-' }}
                                    </td>
                                    <td
                                        class="px-4 py-3 text-sm {{ $muted }} max-w-xs break-words whitespace-normal">
                                        {{ $project->description ?? '-' }}
                                    </td>
                                    <td class="{{ $td }} text-center">{{ $project->start_date ?? '-' }}</td>
                                    <td class="{{ $td }} text-center">{{ $project->end_date ?? '-' }}</td>
                                    <td class="{{ $td }} text-center">
                                        {{ $project->actual_start_date ?? '-' }}</td>
                                    <td class="{{ $td }} text-right">
                                        {{ $project->total_pending_minutes ?? '-' }}</td>
                                    <td class="px-4 py-3 text-center">
                                        <button onclick="showProjectModal({{ $project->id }})"
                                            class="text-blue-600 dark:text-blue-400 hover:underline text-sm font-medium">
                                            Lihat Detail
                                        </button>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <div class="flex justify-center items-center gap-2 flex-wrap">

                                            {{-- UPDATE PROGRESS --}}
                                            <button onclick="openEditModal(this)" data-id="{{ $project->id }}"
                                                data-code="{{ $project->project_code }}"
                                                data-name="{{ $project->project_name }}"
                                                data-progress="{{ $project->progress_percent }}"
                                                data-developer-id="{{ $project->dev_id ?? ($project->developer_id ?? '') }}"
                                                data-developer-name="{{ $project->developer->name ?? '' }}"
                                                data-description="{{ $project->description ?? '' }}"
                                                class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-blue-600 hover:bg-blue-700 text-white transition-colors">
                                                Update
                                            </button>

                                            {{-- resolved --}}
                                            <button onclick="openresolvedModal(this)" data-id="{{ $project->id }}"
                                                data-code="{{ $project->project_code }}"
                                                data-name="{{ $project->project_name }}"
                                                data-developer-id="{{ $project->dev_id ?? ($project->developer_id ?? '') }}"
                                                data-developer-name="{{ $project->developer->name ?? '' }}"
                                                data-description="{{ $project->description ?? '' }}"
                                                class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-green-600 hover:bg-green-700 text-white transition-colors">
                                                resolved
                                            </button>

                                            {{-- PENDING --}}
                                            <button onclick="openPendingModal({{ $project->id }})"
                                                class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-orange-600 hover:bg-orange-700 text-white transition-colors">
                                                Pending
                                            </button>

                                            {{-- VOID --}}
                                            <button onclick="openVoidModal({{ $project->id }})"
                                                class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-red-600 hover:bg-red-700 text-white transition-colors">
                                                Void
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>
            </div>

            {{-- ================= WAITING ================= --}}
            <div x-show="tab==='waiting'" x-cloak class="{{ $card }} p-4 sm:p-5">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <div class="text-base sm:text-lg font-semibold text-light-text dark:text-dark-text">
                            Project Waiting
                        </div>
                        <div class="text-xs mt-1 {{ $muted2 }}">{{ $tabs['waiting']['subtitle'] }}</div>
                    </div>
                    <span class="{{ $badgeBase }} {{ $tabs['waiting']['badge'] }}">Waiting</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="datatable w-full text-light-text dark:text-dark-text">
                        <thead class="{{ $thead }}">
                            <tr>
                                <th class="{{ $th }}">Project Code</th>
                                <th class="{{ $th }}">Project Name</th>
                                <th class="{{ $th }}">Request Date</th>
                                <th class="{{ $th }}">Requestor</th>
                                <th class="{{ $th }}">Priority</th>
                                <th class="{{ $th }}">Description</th>
                                <th class="{{ $th }}">Start Date</th>
                                <th class="{{ $th }}">End Date</th>
                                <th class="{{ $th }} text-center">Action</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-light-eval-3 dark:divide-dark-eval-2">
                            @foreach ($waitingProject as $project)
                                <tr class="transition-colors hover:bg-light-eval-2 dark:hover:bg-dark-eval-2">
                                    <td class="px-4 py-3 text-sm font-semibold text-light-text dark:text-dark-text">
                                        {{ $project->project_code ?? '-' }}
                                    </td>
                                    <td class="{{ $td }}">{{ $project->project_name ?? '-' }}</td>
                                    <td class="{{ $td }}">{{ $project->request_date ?? '-' }}</td>
                                    <td class="{{ $td }}">{{ $project->requestor->name ?? '-' }}</td>
                                    <td class="{{ $td }}">{{ $project->priority->name ?? '-' }}</td>
                                    <td
                                        class="px-4 py-3 text-sm {{ $muted }} max-w-xs break-words whitespace-normal">
                                        {{ $project->description ?? '-' }}
                                    </td>
                                    <td class="{{ $td }}">{{ $project->start_date ?? '-' }}</td>
                                    <td class="{{ $td }}">{{ $project->end_date ?? '-' }}</td>
                                    <td class="px-4 py-3 text-center">
                                        <div class="flex gap-2 justify-center">
                                            <button onclick="openVoidModal({{ $project->id }})"
                                                class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-red-600 hover:bg-red-700 text-white transition-colors">
                                                Void
                                            </button>

                                            <form action="{{ route('project.updateProgress', $project->id) }}"
                                                method="POST">
                                                @csrf
                                                <button type="submit" onclick="return confirm('Apakah Anda Yakin?')"
                                                    class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-blue-600 hover:bg-blue-700 text-white transition-colors">
                                                    Pilih
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

            {{-- ================= resolved ================= --}}
            <div x-show="tab==='resolved'" x-cloak class="{{ $card }} p-4 sm:p-5">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <div class="text-base sm:text-lg font-semibold text-light-text dark:text-dark-text">
                            Project resolved
                        </div>
                        <div class="text-xs mt-1 {{ $muted2 }}">{{ $tabs['resolved']['subtitle'] }}</div>
                    </div>
                    <span class="{{ $badgeBase }} {{ $tabs['resolved']['badge'] }}">resolved</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="datatable w-full text-light-text dark:text-dark-text">
                        <thead class="{{ $thead }}">
                            <tr>
                                <th class="{{ $th }}">Project Code</th>
                                <th class="{{ $th }}">Nama</th>
                                <th class="{{ $th }}">Request Date</th>
                                <th class="{{ $th }}">Requestor</th>
                                <th class="{{ $th }}">Priority</th>
                                <th class="{{ $th }}">Description</th>
                                <th class="{{ $th }}">Start Date</th>
                                <th class="{{ $th }}">End Date</th>
                                <th class="{{ $th }}">History</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-light-eval-3 dark:divide-dark-eval-2">
                            @foreach ($resolvedProject as $project)
                                <tr class="transition-colors hover:bg-light-eval-2 dark:hover:bg-dark-eval-2">
                                    <td class="px-4 py-3 text-sm font-semibold text-light-text dark:text-dark-text">
                                        {{ $project->project_code ?? '-' }}
                                    </td>
                                    <td class="{{ $td }}">{{ $project->project_name ?? '-' }}</td>
                                    <td class="{{ $td }}">{{ $project->request_date ?? '-' }}</td>
                                    <td class="{{ $td }}">{{ $project->requestor->name ?? '-' }}</td>
                                    <td class="{{ $td }}">{{ $project->priority->name ?? '-' }}</td>
                                    <td
                                        class="px-4 py-3 text-sm {{ $muted }} max-w-xs break-words whitespace-normal">
                                        {{ $project->description ?? '-' }}
                                    </td>
                                    <td class="{{ $td }}">{{ $project->start_date ?? '-' }}</td>
                                    <td class="{{ $td }}">{{ $project->end_date ?? '-' }}</td>
                                    <td class="px-4 py-3">
                                        <button onclick="showProjectModal({{ $project->id }})"
                                            class="text-blue-600 dark:text-blue-400 hover:underline text-sm font-medium">
                                            Lihat Detail
                                        </button>
                                    </td>
                                    
                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>
            </div>

            {{-- ================= PENDING ================= --}}
            <div x-show="tab==='pending'" x-cloak class="{{ $card }} p-4 sm:p-5">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <div class="text-base sm:text-lg font-semibold text-light-text dark:text-dark-text">
                            Project Pending
                        </div>
                        <div class="text-xs mt-1 {{ $muted2 }}">{{ $tabs['pending']['subtitle'] }}</div>
                    </div>
                    <span class="{{ $badgeBase }} {{ $tabs['pending']['badge'] }}">Pending</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="datatable w-full text-light-text dark:text-dark-text">
                        <thead class="{{ $thead }}">
                            <tr>
                                <th class="{{ $th }}">Project Code</th>
                                <th class="{{ $th }}">Project Name</th>
                                <th class="{{ $th }}">Request Date</th>
                                <th class="{{ $th }}">Requestor</th>
                                <th class="{{ $th }}">Priority</th>
                                <th class="{{ $th }}">Progress %</th>
                                <th class="{{ $th }}">Progress Date</th>
                                <th class="{{ $th }}">Description</th>
                                <th class="{{ $th }}">Start Date</th>
                                <th class="{{ $th }}">End Date</th>
                                <th class="{{ $th }}">History</th>
                                <th class="{{ $th }} text-center">Action</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-light-eval-3 dark:divide-dark-eval-2">
                            @foreach ($pendingProject as $project)
                                <tr class="transition-colors hover:bg-light-eval-2 dark:hover:bg-dark-eval-2">
                                    <td class="px-4 py-3 text-sm font-semibold text-light-text dark:text-dark-text">
                                        {{ $project->project_code ?? '-' }}
                                    </td>
                                    <td class="{{ $td }}">{{ $project->project_name ?? '-' }}</td>
                                    <td class="{{ $td }}">{{ $project->request_date ?? '-' }}</td>
                                    <td class="{{ $td }}">{{ $project->requestor->name ?? '-' }}</td>
                                    <td class="{{ $td }}">{{ $project->priority->name ?? '-' }}</td>
                                    <td class="{{ $td }}">{{ $project->progress_percent ?? '-' }}%</td>
                                    <td class="{{ $td }}">{{ $project->progress_date ?? '-' }}</td>
                                    <td
                                        class="px-4 py-3 text-sm {{ $muted }} max-w-xs break-words whitespace-normal">
                                        {{ $project->description ?? '-' }}
                                    </td>
                                    <td class="{{ $td }}">{{ $project->start_date ?? '-' }}</td>
                                    <td class="{{ $td }}">{{ $project->end_date ?? '-' }}</td>
                                    <td class="px-4 py-3">
                                        <button onclick="showProjectModal({{ $project->id }})"
                                            class="text-blue-600 dark:text-blue-400 hover:underline text-sm font-medium">
                                            Lihat Detail
                                        </button>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <button onclick="openContinueModal({{ $project->id }})"
                                            class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-blue-600 hover:bg-blue-700 text-white transition-colors">
                                            Continue
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>
            </div>

            {{-- ================= VOID ================= --}}
            <div x-show="tab==='void'" x-cloak class="{{ $card }} p-4 sm:p-5">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <div class="text-base sm:text-lg font-semibold text-light-text dark:text-dark-text">
                            Project Void
                        </div>
                        <div class="text-xs mt-1 {{ $muted2 }}">{{ $tabs['void']['subtitle'] }}</div>
                    </div>
                    <span class="{{ $badgeBase }} {{ $tabs['void']['badge'] }}">Void</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="datatable w-full text-light-text dark:text-dark-text">
                        <thead class="{{ $thead }}">
                            <tr>
                                <th class="{{ $th }}">Project Code</th>
                                <th class="{{ $th }}">Nama</th>
                                <th class="{{ $th }}">Request Date</th>
                                <th class="{{ $th }}">Requestor</th>
                                <th class="{{ $th }}">Priority</th>
                                <th class="{{ $th }}">Description</th>
                                <th class="{{ $th }}">Notes</th>
                                <th class="{{ $th }}">Start Date</th>
                                <th class="{{ $th }}">End Date</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-light-eval-3 dark:divide-dark-eval-2">
                            @foreach ($voidProject as $project)
                                <tr class="transition-colors hover:bg-light-eval-2 dark:hover:bg-dark-eval-2">
                                    <td class="px-4 py-3 text-sm font-semibold text-light-text dark:text-dark-text">
                                        {{ $project->project_code ?? '-' }}
                                    </td>
                                    <td class="{{ $td }}">{{ $project->project_name ?? '-' }}</td>
                                    <td class="{{ $td }}">{{ $project->request_date ?? '-' }}</td>
                                    <td class="{{ $td }}">{{ $project->requestor->name ?? '-' }}</td>
                                    <td class="{{ $td }}">{{ $project->priority->name ?? '-' }}</td>
                                    <td
                                        class="px-4 py-3 text-sm {{ $muted }} max-w-xs break-words whitespace-normal">
                                        {{ $project->description ?? '-' }}
                                    </td>
                                    <td
                                        class="px-4 py-3 text-sm {{ $muted }} max-w-xs break-words whitespace-normal">
                                        {{ $project->notes ?? '-' }}
                                    </td>
                                    <td class="{{ $td }}">{{ $project->start_date ?? '-' }}</td>
                                    <td class="{{ $td }}">{{ $project->end_date ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>
            </div>

        </div>
    </div>


    <x-modal-form id="continueModal" title="Continue Project" size="max-w-md">
                <form id="continueForm" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-sm font-semibold mb-1 text-light-text dark:text-dark-text">
                            Developer yang Continue-in <span class="text-red-500">*</span>
                        </label>
                        <select name="developer_id" required class="{{ $input }}">
                            <option value="" hidden>-- Pilih Developer --</option>
                            @foreach ($developers as $dev)
                                <option value="{{ $dev->id }}">{{ $dev->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-semibold mb-2 text-light-text dark:text-dark-text">
                            Pakai durasi manual?
                        </label>
                        <div class="flex items-center gap-4 text-sm">
                            <label class="flex items-center gap-2">
                                <input type="radio" name="use_override" value="0" checked
                                    onclick="toggleOverride(false)">
                                <span>Tidak (auto hitung)</span>
                            </label>
                            <label class="flex items-center gap-2">
                                <input type="radio" name="use_override" value="1"
                                    onclick="toggleOverride(true)">
                                <span>Ya (manual)</span>
                            </label>
                        </div>
                    </div>

                    <div class="mb-4" id="overrideBox" style="display:none;">
                        <label class="block text-sm font-semibold mb-1 text-light-text dark:text-dark-text">
                            Duration Override (menit)
                        </label>
                        <input type="number" min="0" name="duration_override" class="{{ $input }}"
                            placeholder="cth: 30">
                    </div>

                    <div class="flex justify-end gap-2">
                        <button type="button" onclick="closeModal('continueModal')"
                            class="{{ $btnGhost }}">Cancel</button>
                        <button type="submit" class="{{ $btnPrimary }}"
                            onclick="return confirm('Yakin continue project ini?')">
                            Continue
                        </button>
                    </div>
                </form>
    </x-modal-form>

    {{-- ========================================================= MODAL: DETAIL HISTORY ========================================================= --}}
    <x-modal-table name="project-detail" title="Detail Project">
        <div id="projectModalBody">
            <div class="p-6 text-center text-gray-500">
                <svg class="animate-spin h-5 w-5 mx-auto mb-2 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                    fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                        stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                </svg>
                Loading...
            </div>
        </div>
    </x-modal-table>

    {{-- ========================================================= MODAL: CREATE PROJECT ========================================================= --}}
    <x-modal-form id="projectModal" title="Create Project" size="max-w-4xl">
        <form action="{{ route('project.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <input type="hidden" name="from" value="user">

            <div class="mb-4">
                <label class="block text-sm font-semibold mb-1 text-light-text dark:text-dark-text">
                    Project Code <span class="text-red-500">*</span>
                </label>
                <input type="text" name="project_code" value="{{ $generateticket ?? '' }}" readonly
                    class="w-full px-3 py-2 rounded-lg border bg-light-eval-2 dark:bg-dark-eval-2
                           border-light-eval-3 dark:border-dark-eval-2 text-light-text dark:text-dark-text">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold mb-1 text-light-text dark:text-dark-text">
                    Project Name <span class="text-red-500">*</span>
                </label>
                <input type="text" name="project_name" required class="{{ $input }}">
            </div>

            <div class="mb-4 relative">
                <label class="block text-sm font-semibold mb-1 text-light-text dark:text-dark-text">
                    Pilih Requestor <span class="text-red-500">*</span>
                </label>
                <input type="text" id="user-search" placeholder="Cari user..." required class="{{ $input }}">
                <input type="hidden" name="requestor_id" id="user-id" required>

                <ul id="search-results"
                    class="hidden absolute z-50 w-full rounded-lg mt-2 overflow-y-auto shadow-lg max-h-40
                           bg-light-bg dark:bg-dark-eval-2 border border-light-eval-3 dark:border-dark-eval-2">
                    @foreach ($users as $user)
                        <li class="px-3 py-2 cursor-pointer border-b border-light-eval-3 dark:border-dark-eval-2 hover:bg-light-eval-2 dark:hover:bg-dark-eval-1 text-light-text dark:text-dark-text" data-id="{{ $user->id }}">{{ $user->name }}
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold mb-1 text-light-text dark:text-dark-text">
                    Priority <span class="text-red-500">*</span>
                </label>
                <select name="priority_id" required class="{{ $input }}">
                    <option value="" hidden>-- Pilih Priority --</option>
                    @foreach ($priorities as $prt)
                        <option value="{{ $prt->id }}">{{ $prt->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- optional: status hidden (tapi backend harus tetap set waiting biar aman) --}}
            <input type="hidden" name="status_id" value="{{ $stWaiting }}">

            <div class="mb-4">
                <label class="block text-sm font-semibold mb-1 text-light-text dark:text-dark-text">
                    Description <span class="text-red-500">*</span>
                </label>
                <input type="text" name="description" required class="{{ $input }}">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="mb-4">
                    <label class="block text-sm font-semibold mb-1 text-light-text dark:text-dark-text">
                        Start Date & Time <span class="text-red-500">*</span>
                    </label>
                    <input type="datetime-local" name="start_date" required class="{{ $input }}">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-semibold mb-1 text-light-text dark:text-dark-text">
                        End Date & Time <span class="text-red-500">*</span>
                    </label>
                    <input type="datetime-local" name="end_date" required class="{{ $input }}">
                </div>
            </div>

            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeModal('projectModal')"
                    class="{{ $btnGhost }}">Cancel</button>
                <button type="submit" class="{{ $btnPrimary }}">Save</button>
            </div>
        </form>
    </x-modal-form>

    {{-- ========================================================= MODAL: EDIT PROGRESS (IN PROGRESS) ========================================================= --}}
    <x-modal-form id="editProgressModal" title="Update Progress Project" size="max-w-3xl">
                <form id="editProgressForm" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block text-sm font-semibold mb-1 text-light-text dark:text-dark-text">
                            Project Code
                        </label>
                        <input type="text"
                            id="modal_project_code"
                            readonly
                            class="w-full px-3 py-2 rounded-lg border bg-light-eval-2 dark:bg-dark-eval-2
                            border-light-eval-3 dark:border-dark-eval-2 text-light-text dark:text-dark-text">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-semibold mb-1 text-light-text dark:text-dark-text">
                            Project Name
                        </label>
                        <input type="text"
                            id="modal_project_name"
                            readonly
                            class="w-full px-3 py-2 rounded-lg border bg-light-eval-2 dark:bg-dark-eval-2
                            border-light-eval-3 dark:border-dark-eval-2 text-light-text dark:text-dark-text">
                    </div>

                    {{-- Developer --}}
                    <div class="mb-4">
                        <label class="block text-sm font-semibold mb-1 text-light-text dark:text-dark-text">
                            Developer <span class="text-red-500">*</span>
                        </label>

                        <div id="selected-developers"
                            class="w-full px-3 py-2 rounded-lg border cursor-pointer flex justify-between items-center
                            bg-light-bg dark:bg-dark-eval-2 text-light-text dark:text-dark-text
                            border-light-eval-3 dark:border-dark-eval-2">

                            <span id="selected-text" class="truncate">
                                Pilih Developer...
                            </span>

                            <svg id="dropdown-icon"
                                class="w-4 h-4 transform transition-transform duration-200"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor">

                                <path stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>

                        <input type="hidden"
                            name="developer_id"
                            id="developer_id">

                        <div id="developer-dropdown"
                            class="hidden mt-2 rounded-lg shadow-lg max-h-48 overflow-y-auto p-2
                            bg-light-bg dark:bg-dark-eval-2 border border-light-eval-3 dark:border-dark-eval-2">

                            @foreach ($developers as $dev)
                                <label
                                    class="flex items-center gap-2 py-1 px-2 rounded cursor-pointer hover:bg-light-eval-2 dark:hover:bg-dark-eval-1">

                                    <input type="checkbox"
                                        value="{{ $dev->id }}"
                                        data-name="{{ $dev->name }}"
                                        class="dev-checkbox">

                                    <span class="text-sm text-light-text dark:text-dark-text">
                                        {{ $dev->name }}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Progress Date --}}
                    <div class="mb-4">
                        <label class="block text-sm font-semibold mb-1 text-light-text dark:text-dark-text">
                            Progress Date <span class="text-red-500">*</span>
                        </label>

                        <input type="datetime-local"
                            name="progress_date"
                            id="modal_progress_date"
                            required
                            class="{{ $input }}">
                    </div>

                    {{-- Progress Percent --}}
                    {{-- Progress Percent --}}
                    <div class="mb-4">
                        <label class="block text-sm font-semibold mb-1 text-light-text dark:text-dark-text">
                            Progress Percent <span class="text-red-500">*</span>
                        </label>

                        <input type="number"
                            name="progress_percent"
                            id="modal_progress_percent"
                            min="0"
                            max="100"
                            step="1"
                            required
                            class="{{ $input }}">

                        <input type="hidden" id="old_progress_percent">
                    </div>

                    {{-- Description --}}
                    <div class="mb-4">
                        <label class="block text-sm font-semibold mb-1 text-light-text dark:text-dark-text">
                            Description <span class="text-red-500">*</span>
                        </label>

                        <textarea
                            name="description"
                            id="modal_description"
                            rows="3"
                            required
                            class="{{ $input }}"></textarea>
                    </div>

                    <div class="flex justify-end gap-2">
                        <button type="button"
                            onclick="closeModal('editProgressModal')"
                            class="{{ $btnGhost }}">
                            Cancel
                        </button>

                        <button type="submit"
                            class="{{ $btnPrimary }}">
                            Save
                        </button>
                    </div>
                </form>
        </x-modal-form>        

    {{-- ========================================================= MODAL: RESOLVED PROJECT ========================================================= --}}
    <x-modal-form id="resolvedModal" title="Resolved Project" size="max-w-3xl">
        <form id="resolvedForm" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block text-sm font-semibold mb-1 text-light-text dark:text-dark-text">
                    Project Code
                </label>
                <input type="text"
                    id="resolved_project_code"
                    readonly
                    class="w-full px-3 py-2 rounded-lg border bg-light-eval-2 dark:bg-dark-eval-2
                    border-light-eval-3 dark:border-dark-eval-2 text-light-text dark:text-dark-text">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold mb-1 text-light-text dark:text-dark-text">
                    Project Name
                </label>
                <input type="text"
                    id="resolved_project_name"
                    readonly
                    class="w-full px-3 py-2 rounded-lg border bg-light-eval-2 dark:bg-dark-eval-2
                    border-light-eval-3 dark:border-dark-eval-2 text-light-text dark:text-dark-text">
            </div>

            {{-- Developer --}}
            <div class="mb-4">
                <label class="block text-sm font-semibold mb-1 text-light-text dark:text-dark-text">
                    Developer <span class="text-red-500">*</span>
                </label>

                <div id="selected-developers-resolved"
                    class="w-full px-3 py-2 rounded-lg border cursor-pointer flex justify-between items-center
                    bg-light-bg dark:bg-dark-eval-2 text-light-text dark:text-dark-text
                    border-light-eval-3 dark:border-dark-eval-2">

                    <span id="selected-text-resolved" class="truncate">
                        Pilih Developer...
                    </span>

                    <svg id="dropdown-icon-resolved"
                        class="w-4 h-4 transform transition-transform duration-200"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor">

                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M19 9l-7 7-7-7" />
                    </svg>
                </div>

                <input
                    type="hidden"
                    name="developer_id"
                    id="developer_id_resolved"
                    required>

                <div id="developer-dropdown-resolved"
                    class="hidden mt-2 rounded-lg shadow-lg max-h-48 overflow-y-auto p-2
                    bg-light-bg dark:bg-dark-eval-2 border border-light-eval-3 dark:border-dark-eval-2">

                    @foreach ($developers as $dev)
                        <label
                            class="flex items-center gap-2 py-1 px-2 rounded cursor-pointer hover:bg-light-eval-2 dark:hover:bg-dark-eval-1">

                            <input
                                type="checkbox"
                                value="{{ $dev->id }}"
                                data-name="{{ $dev->name }}"
                                class="dev-checkbox-resolved">

                            <span class="text-sm text-light-text dark:text-dark-text">
                                {{ $dev->name }}
                            </span>
                        </label>
                    @endforeach
                </div>
            </div>

            {{-- Description --}}
            <div class="mb-4">
                <label class="block text-sm font-semibold mb-1 text-light-text dark:text-dark-text">
                    Description <span class="text-red-500">*</span>
                </label>

                <textarea
                    name="description"
                    id="resolved_description"
                    rows="3"
                    required
                    class="{{ $input }}"></textarea>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold mb-2 text-light-text dark:text-dark-text">
                    Tambahkan total pending duration ke Effective End Date?
                </label>

                <div class="flex items-center gap-4 text-sm">
                    <label class="flex items-center gap-2">
                        <input
                            type="radio"
                            name="apply_pending_duration"
                            value="1"
                            checked>
                        <span>YES</span>
                    </label>

                    <label class="flex items-center gap-2">
                        <input
                            type="radio"
                            name="apply_pending_duration"
                            value="0">
                        <span>NO</span>
                    </label>
                </div>
            </div>

            <div class="flex justify-end gap-2">
                <button
                    type="button"
                    onclick="closeModal('resolvedModal')"
                    class="{{ $btnGhost }}">
                    Cancel
                </button>

                <button
                    type="submit"
                    class="inline-flex items-center justify-center px-4 py-2.5 rounded-lg text-sm font-medium
                    bg-green-600 hover:bg-green-700 text-white transition-colors shadow-sm">
                    Resolved
                </button>
            </div>
        </form>
    </x-modal-form>

    {{-- ========================================================= MODAL: PENDING ========================================================= --}}
    <x-modal-form id="pendingModal" title="Pending" size="max-w-md">
        <form id="pendingForm" method="POST">
            @csrf

            <input type="hidden" name="id_project_header" id="pending_project_id">

            <div class="mb-4">
                <label class="block text-sm font-semibold mb-1 text-light-text dark:text-dark-text">
                    Reason <span class="text-red-500">*</span>
                </label>
                <input type="text" name="reason" required class="{{ $input }}">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold mb-1 text-light-text dark:text-dark-text">
                    Developer yang Pending-in <span class="text-red-500">*</span>
                </label>
                <select name="developer_id" required class="{{ $input }}">
                    <option value="" hidden>-- Pilih Developer --</option>
                    @foreach ($developers as $dev)
                        <option value="{{ $dev->id }}">{{ $dev->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold mb-2 text-light-text dark:text-dark-text">
                    Tambahkan duration pending ke Effective End Date?
                </label>
                <div class="flex items-center gap-4 text-sm">
                    <label class="flex items-center gap-2">
                        <input type="radio" name="count_to_effective" value="1" checked>
                        <span>YES</span>
                    </label>
                    <label class="flex items-center gap-2">
                        <input type="radio" name="count_to_effective" value="0">
                        <span>NO</span>
                    </label>
                </div>
            </div>

            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeModal('pendingModal')"
                    class="{{ $btnGhost }}">Cancel</button>
                <button type="submit" class="{{ $btnPrimary }}">Save</button>
            </div>
        </form>
    </x-modal-form>

    {{-- ========================================================= MODAL: VOID ========================================================= --}}
    <x-modal-form id="voidModal" title="Void Project" size="max-w-md">
        <form id="voidForm" method="POST">
            @csrf
            <input type="hidden" name="status_id" value="{{ $stVoid }}">

            <div class="mb-4">
                <label class="block text-sm font-semibold mb-1 text-light-text dark:text-dark-text">
                    Notes <span class="text-red-500">*</span>
                </label>
                <textarea name="notes" required placeholder="Tulis alasan void..." rows="4"
                    class="w-full px-3 py-2 rounded-lg border resize-none
                           bg-light-bg dark:bg-dark-eval-2
                           border-light-eval-3 dark:border-dark-eval-2
                           text-light-text dark:text-dark-text
                           focus:ring-2 focus:ring-red-500/20 focus:border-red-500/40"></textarea>
            </div>

            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeModal('voidModal')"
                    class="{{ $btnGhost }}">Cancel</button>
                <button type="submit"
                    class="inline-flex items-center justify-center px-4 py-2.5 rounded-lg text-sm font-medium
                           bg-red-600 hover:bg-red-700 text-white transition-colors shadow-sm">
                    Save
                </button>
            </div>
        </form>
    </x-modal-form>

    {{-- ========================================================= SCRIPTS ========================================================= --}}
    <script>
        function showProjectModal(projectId) {
            window.dispatchEvent(new CustomEvent('open-modal', {
                detail: 'project-detail'
            }));

            const modalBody = document.getElementById('projectModalBody');
            modalBody.innerHTML = `
                <div class="p-6 text-center text-gray-500">
                    <svg class="animate-spin h-5 w-5 mx-auto mb-2 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                    </svg>
                    Loading...
                </div>
            `;

            fetch(`/projects/${projectId}/history`)
                .then(r => {
                    if (!r.ok) throw new Error('Gagal memuat data');
                    return r.text();
                })
                .then(html => modalBody.innerHTML = html)
                .catch(err => modalBody.innerHTML = `<div class="p-6 text-center text-red-500">⚠️ ${err.message}</div>`);
        }
    </script>
    <script>
            document.getElementById('resolvedForm').addEventListener('submit', function(e) {

            if (!this.checkValidity()) {
                e.preventDefault();
                this.reportValidity();
                return;
            }

            const developerId = document.getElementById('developer_id_resolved').value;
            const description = document.getElementById('resolved_description').value.trim();

            if (!developerId || !description) {

                e.preventDefault();

                Swal.fire({
                    icon: 'warning',
                    title: 'Form Belum Lengkap',
                    text: 'Semua field wajib diisi.'
                });

                return false;
            }
            });
        </script>
    <script>
        document.getElementById('editProgressForm').addEventListener('submit', function(e) {

        if (!this.checkValidity()) {
            e.preventDefault();
            this.reportValidity();
            return;
        }

        const developerId = document.getElementById('developer_id').value;
        const oldProgress = parseInt(document.getElementById('old_progress_percent').value || 0);
        const newProgress = parseInt(document.getElementById('modal_progress_percent').value || 0);

        if (!developerId) {

            e.preventDefault();

            Swal.fire({
                icon: 'warning',
                title: 'Form Belum Lengkap',
                text: 'Semua field wajib diisi.'
            });

            return false;
        }

        if (newProgress <= oldProgress) {

            e.preventDefault();

            Swal.fire({
                icon: 'warning',
                title: 'Progress Tidak Valid',
                text: `Progress harus lebih besar dari progress sebelumnya (${oldProgress}%).`
            });

            return false;
        }

        });
    </script>
    <script src="{{ asset('js/jquery-3.7.0.min.js') }}"></script>

    <script>
        document.addEventListener("DOMContentLoaded", () => {

            // =========================
            // DEV DROPDOWN (EDIT)
            // =========================
            $(document).on('click', '#selected-developers', function() {
                $('#developer-dropdown').toggleClass('hidden');
                $('#dropdown-icon').toggleClass('rotate-180');
            });

            $(document).on('click', function(e) {
                if (!$(e.target).closest('#selected-developers, #developer-dropdown').length) {
                    $('#developer-dropdown').addClass('hidden');
                    $('#dropdown-icon').removeClass('rotate-180');
                }
            });

            $(document).on('change', '.dev-checkbox', function() {
                $('.dev-checkbox').not(this).prop('checked', false);

                const selectedId = $(this).is(':checked') ? $(this).val() : '';
                const selectedName = $(this).is(':checked') ? ($(this).data('name') || '') : '';

                $('#selected-text').text(selectedName || 'Pilih Developer...');
                $('#developer_id').val(selectedId);
            });

            // =========================
            // DEV DROPDOWN (resolved)
            // =========================
            $(document).on('click', '#selected-developers-resolved', function() {
                $('#developer-dropdown-resolved').toggleClass('hidden');
                $('#dropdown-icon-resolved').toggleClass('rotate-180');
            });

            $(document).on('click', function(e) {
                if (!$(e.target).closest('#selected-developers-resolved, #developer-dropdown-resolved').length) {
                    $('#developer-dropdown-resolved').addClass('hidden');
                    $('#dropdown-icon-resolved').removeClass('rotate-180');
                }
            });

            $(document).on('change', '.dev-checkbox-resolved', function() {
                $('.dev-checkbox-resolved').not(this).prop('checked', false);

                const selectedId = $(this).is(':checked') ? $(this).val() : '';
                const selectedName = $(this).is(':checked') ? ($(this).data('name') || '') : '';

                $('#selected-text-resolved').text(selectedName || 'Pilih Developer...');
                $('#developer_id_resolved').val(selectedId);
            });

            // =========================
          // =========================

            // OPEN EDIT MODAL
            // =========================
            window.openEditModal = function(button) {

                const projectId = $(button).data('id');
                const projectCode = $(button).data('code');
                const projectName = $(button).data('name');
                const progress = parseInt($(button).data('progress')) || 0;
                const developerId = $(button).data('developer-id');
                const developerName = $(button).data('developer-name');

                const $form = $('#editProgressForm');
                if (!$form.length) return;

                $form[0].reset();

                $('.dev-checkbox').prop('checked', false);

                $('#dropdown-icon').removeClass('rotate-180');
                $('#developer-dropdown').addClass('hidden');

                $form.attr('action', `/project/${projectId}`);

                $('#modal_project_code').val(projectCode || '');
                $('#modal_project_name').val(projectName || '');

            // simpan progress lama
            $('#old_progress_percent').val(progress);

            // tampilkan progress lama
            $('#modal_progress_percent').val(progress);

            // tetap batasi maksimal 100
            $('#modal_progress_percent').attr('max', 100);
                // description kosong
                $('#modal_description').val('');

                if (developerId) {

                    $(`.dev-checkbox[value="${developerId}"]`)
                        .prop('checked', true);

                    $('#selected-text')
                        .text(developerName || 'Pilih Developer...');

                    $('#developer_id').val(developerId);

                } else {

                    $('#selected-text').text('Pilih Developer...');
                    $('#developer_id').val('');

                }

                const now = new Date();

                const datetime =
                    `${now.getFullYear()}-${String(now.getMonth()+1).padStart(2,'0')}-${String(now.getDate()).padStart(2,'0')}T${String(now.getHours()).padStart(2,'0')}:${String(now.getMinutes()).padStart(2,'0')}`;

                $('#modal_progress_date').val(datetime);

                openModal('editProgressModal');
            };

            // =========================
            // OPEN resolved MODAL
            // =========================
            window.openresolvedModal = function(button) {
                const projectId = $(button).data('id');
                const projectCode = $(button).data('code');
                const projectName = $(button).data('name');
                const developerId = $(button).data('developer-id');
                const developerName = $(button).data('developer-name');
                const description = $(button).data('');

                const $form = $('#resolvedForm');
                if (!$form.length) return;

                $form[0].reset();
                $('.dev-checkbox-resolved').prop('checked', false);
                $('#dropdown-icon-resolved').removeClass('rotate-180');
                $('#developer-dropdown-resolved').addClass('hidden');

                // route resolved (PUT) => sesuai routes kamu:
                // Route::put('/project/{project}/resolved', ...)->name('project.resolved');
                // (group name kamu agak dobel, tapi kita pake URL biar aman)
                $form.attr('action', `/project/${projectId}/resolved`);

                $('#resolved_project_code').val(projectCode || '');
                $('#resolved_project_name').val(projectName || '');
                $('#resolved_description').val(description ?? '');

                if (developerId) {
                    $(`.dev-checkbox-resolved[value="${developerId}"]`).prop('checked', true);
                    $('#selected-text-resolved').text(developerName || 'Pilih Developer...');
                    $('#developer_id_resolved').val(developerId);
                } else {
                    $('#selected-text-resolved').text('Pilih Developer...');
                    $('#developer_id_resolved').val('');
                }

                openModal('resolvedModal');
            };

            // =========================
            // OPEN PENDING MODAL
            // =========================
            window.openPendingModal = function(projectId) {
                const $form = $('#pendingForm');
                if (!$form.length) return;

                $('#pending_project_id').val(projectId);
                $form.attr('action', `/project/${projectId}/pending`);
                openModal('pendingModal');
            };

            // =========================
            // OPEN VOID MODAL
            // =========================
            window.openVoidModal = function(projectId) {
                const $form = $('#voidForm');
                if (!$form.length) return;

                $form[0].reset();
                const actionUrl = "{{ route('project.updateStatus', ':id') }}".replace(':id', projectId);
                $form.attr('action', actionUrl);

                openModal('voidModal');
            };

            // =========================
            // USER SEARCH (Create Modal)
            // =========================
            const $input = $('#user-search');
            const $results = $('#search-results');
            const $hidden = $('#user-id');

            if ($input.length) {
                $input.on('focus', () => $results.show());

                $input.on('input', function() {
                    const val = $(this).val().toLowerCase();
                    let anyVisible = false;

                    $results.children('li').each(function() {
                        const match = $(this).text().toLowerCase().includes(val);
                        $(this).toggle(match);
                        if (match) anyVisible = true;
                    });

                    $results.toggle(anyVisible);
                });

                $results.on('click', 'li', function() {
                    $input.val($(this).text());
                    $hidden.val($(this).data('id'));
                    $results.hide();
                });

                $(document).on('click', (e) => {
                    if (!$(e.target).closest('#user-search, #search-results').length) {
                        $results.hide();
                    }
                });
            }

            // =========================
            // DATATABLE THEME
            // =========================
            const applyDTTheme = () => {
                const dark = document.documentElement.classList.contains('dark');

                $('div.dt-search input, div.dt-length select, div.dataTables_filter input, div.dataTables_length select')
                    .each(function() {
                        $(this).removeClass().addClass(
                            `rounded-lg border px-3 py-2 text-sm transition ` +
                            (dark ?
                                'bg-gray-800 border-gray-700 text-gray-100 placeholder-gray-400' :
                                'bg-white border-gray-300 text-gray-800 placeholder-gray-400')
                        );
                    });

                $('div.dt-paging button, div.dataTables_paginate a').each(function() {
                    $(this).removeClass().addClass(
                        `px-3 py-1 border rounded-lg mx-1 text-sm font-medium transition ` +
                        (dark ?
                            'border-gray-700 text-gray-100 hover:bg-gray-700' :
                            'border-gray-300 text-gray-800 hover:bg-gray-100')
                    );
                });

                $('div.dt-info, div.dataTables_info').each(function() {
                    $(this).removeClass().addClass(dark ? 'text-gray-400 text-sm mt-2' :
                        'text-gray-600 text-sm mt-2');
                });
            };

            applyDTTheme();

            const observer = new MutationObserver(() => applyDTTheme());
            observer.observe(document.documentElement, {
                attributes: true,
                attributeFilter: ['class']
            });
        });
    </script>

    <script>
        window.openContinueModal = function(projectId) {
            const $form = $('#continueForm');
            if (!$form.length) return;

            $form[0].reset();
            $('#overrideBox').hide();
            $form.attr('action', `/project/${projectId}/continue`);
            openModal('continueModal');
        };

        window.toggleOverride = function(show) {
            if (show) $('#overrideBox').show();
            else $('#overrideBox').hide();
        };
    </script>

    <style>
        [x-cloak] {
            display: none !important;
        }

        .date-input::-webkit-calendar-picker-indicator {
            filter: invert(0);
            cursor: pointer;
        }

        .dark .date-input::-webkit-calendar-picker-indicator {
            filter: invert(1);
            cursor: pointer;
        }

        .date-input::-webkit-calendar-picker-indicator:hover {
            opacity: 0.7;
        }

        .dt-layout-row {
            display: flex !important;
            flex-wrap: wrap !important;
            gap: 12px !important;
            align-items: center !important;
            justify-content: space-between !important;
        }

        .dt-layout-cell {
            flex: 1 1 auto !important;
            min-width: 220px !important;
        }

        .dt-length select {
            min-width: 70px !important;
            border-radius: 0 !important;
        }

        .dt-search input {
            min-width: 220px !important;
        }
    </style>
</x-app-layout>
