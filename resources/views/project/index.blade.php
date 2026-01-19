<x-app-layout>
    {{-- ========================================================= HEADER ========================================================= --}}
    <x-slot name="header">
        @auth
            @if (Auth::user()->role_id != 3)
                <div class="flex items-center justify-between gap-4">
                    <h2 class="m-0 font-semibold text-xl leading-tight text-light-text dark:text-dark-text">
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

        // ===== Stats tabs (format sesuai request lu) =====
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
            'done' => [
                'label' => 'Done',
                'count' => $stats['done'] ?? 0,
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
    @endphp

    <div class="px-4 sm:px-6 lg:px-8 py-6 space-y-6" x-data="{
        tab: 'waiting',
        dt: null,
        initDT() {
            // init sekali (DataTables v2)
            this.dt = new DataTable('.datatable', {
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
        },
        onTab() {
            // biar width table aman saat tab hidden
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

                    <div class="mt-4 flex items-start justify-between gap-3">
                        <div class="flex items-center gap-3">

                            <div>
                                <div class="text-sm font-semibold text-light-text dark:text-dark-text">
                                    {{ $t['label'] }}
                                </div>
                                <div class="text-xs mt-0.5 {{ $muted2 }}">
                                    {{ $t['subtitle'] }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 text-3xl font-bold tracking-tight text-light-text dark:text-dark-text">
                        {{ $t['count'] }}
                    </div>
                </button>
            @endforeach
        </div>

        {{-- ========================================================= TABLE AREA (ONLY ONE) ========================================================= --}}
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
                                    <td class="{{ $td }} text-center">
                                        {{ $project->priority->priority_name }}</td>
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
                                        <div class="flex justify-center items-center gap-2">
                                            <button onclick="openEditModal(this)" data-id="{{ $project->id }}"
                                                data-code="{{ $project->project_code }}"
                                                data-name="{{ $project->project_name }}"
                                                data-progress="{{ $project->progress_percent }}"
                                                data-status="{{ $project->status_id }}"
                                                class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-blue-600 hover:bg-blue-700 text-white transition-colors">
                                                Update
                                            </button>

                                            <button onclick="openPendingModal({{ $project->id }})"
                                                class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-orange-600 hover:bg-orange-700 text-white transition-colors">
                                                Pending
                                            </button>

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

            {{-- ================= WAITING (DEFAULT) ================= --}}
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
                                    <td class="{{ $td }}">{{ $project->priority->priority_name }}</td>
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
                                                <input type="hidden" name="status_id" value="2">
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

            {{-- ================= DONE ================= --}}
            <div x-show="tab==='done'" x-cloak class="{{ $card }} p-4 sm:p-5">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <div class="text-base sm:text-lg font-semibold text-light-text dark:text-dark-text">
                            Project Done
                        </div>
                        <div class="text-xs mt-1 {{ $muted2 }}">{{ $tabs['done']['subtitle'] }}</div>
                    </div>
                    <span class="{{ $badgeBase }} {{ $tabs['done']['badge'] }}">Done</span>
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
                                <th class="{{ $th }} text-center">Action</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-light-eval-3 dark:divide-dark-eval-2">
                            @foreach ($doneProject as $project)
                                <tr class="transition-colors hover:bg-light-eval-2 dark:hover:bg-dark-eval-2">
                                    <td class="px-4 py-3 text-sm font-semibold text-light-text dark:text-dark-text">
                                        {{ $project->project_code ?? '-' }}
                                    </td>
                                    <td class="{{ $td }}">{{ $project->project_name ?? '-' }}</td>
                                    <td class="{{ $td }}">{{ $project->request_date ?? '-' }}</td>
                                    <td class="{{ $td }}">{{ $project->requestor->name ?? '-' }}</td>
                                    <td class="{{ $td }}">{{ $project->priority->priority_name }}</td>
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
                                        <form action="{{ route('project.updateStatus', $project->id) }}"
                                            method="POST" class="inline">
                                            @csrf
                                            <input type="hidden" name="status_id" value="2">
                                            <button
                                                class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-red-600 hover:bg-red-700 text-white transition-colors"
                                                onclick="return confirm('Apakah Anda Yakin?')">
                                                Cancel
                                            </button>
                                        </form>
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
                                    <td class="{{ $td }}">{{ $project->priority->priority_name ?? '-' }}
                                    </td>
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
                                        <form action="{{ route('project.continueProgress', $project->id) }}"
                                            method="POST" class="inline">
                                            @csrf
                                            <input type="hidden" name="status_id" value="2">
                                            <button
                                                class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-blue-600 hover:bg-blue-700 text-white transition-colors"
                                                onclick="return confirm('Apakah Anda Yakin?')">
                                                Continue
                                            </button>
                                        </form>
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
                                    <td class="{{ $td }}">{{ $project->priority->priority_name }}</td>
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

    {{-- Modal detail tetap --}}
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
    </style>

    {{-- MODAL-MODAL kamu yang lain (pending/edit/create/void) tetap bisa dipakai apa adanya --}}
    {{-- tinggal paste modal bagian bawah dari file lama lu (ga gue ubah logic-nya) --}}

    {{-- ============================================ MODAL FORM PENDING ============================================ --}}
    <x-modal-form id="pendingModal" title="Pending" size="max-w-md">
        <form id="pendingForm" method="POST">
            @csrf
            <input type="hidden" name="id_project_header" id="pending_project_id">

            <div class="mb-4">
                <label class="block text-sm font-medium text-light-text dark:text-dark-text mb-2">
                    Reason <span class="text-red-500">*</span>
                </label>
                <input type="text" name="reason" required placeholder="Tulis alasan pending..."
                    class="w-full px-3 py-2 rounded-lg border
                           bg-light-bg dark:bg-dark-eval-2
                           border-light-eval-3 dark:border-dark-eval-2
                           text-light-text dark:text-dark-text
                           focus:ring-2 focus:ring-blue-500/25 focus:border-blue-500/40">
            </div>

            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeModal('pendingModal')"
                    class="{{ $btnGhost }}">Cancel</button>
                <button type="submit" class="{{ $btnPrimary }}">Save</button>
            </div>
        </form>
    </x-modal-form>

    {{-- ============================================ MODAL FORM VOID ============================================ --}}
    <x-modal-form id="voidModal" title="Void Project" size="max-w-md">
        <form id="voidForm" method="POST">
            @csrf
            <input type="hidden" name="status_id" value="4">

            <div class="mb-4">
                <label class="block text-sm font-medium text-light-text dark:text-dark-text mb-2">
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
</x-app-layout>
