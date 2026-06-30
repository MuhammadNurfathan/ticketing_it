<x-app-layout>

    {{-- HEADER --}}
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-light-text dark:text-dark-text leading-tight">
                    Monitoring Project Queue
                </h2>
                <p class="text-xs mt-1 text-light-text-secondary dark:text-dark-text-secondary">
                    Ringkasan, timeline, dan antrian project per tahun
                </p>
            </div>
        </div>
    </x-slot>

    @php
        $page = "min-h-screen bg-light-bg dark:bg-dark-bg";
        $wrap = "w-full px-4 sm:px-6 lg:px-8 py-6 space-y-6";

        $card = "rounded-2xl border shadow-sm overflow-hidden
                 bg-light-eval-1 dark:bg-dark-eval-1
                 border-light-eval-3 dark:border-dark-eval-2";

        $cardHead = "p-4 sm:p-6 border-b flex flex-col sm:flex-row justify-between sm:items-center gap-4
                     border-light-eval-3 dark:border-dark-eval-2";

        $title = "text-lg font-semibold text-light-text dark:text-dark-text";
        $sub = "text-sm text-light-text-secondary dark:text-dark-text-secondary mt-1";

        $input = "px-3 py-2 text-sm rounded-lg border
                  bg-light-bg dark:bg-dark-eval-2
                  text-light-text dark:text-dark-text
                  border-light-eval-3 dark:border-dark-eval-2
                  focus:ring-2 focus:ring-blue-500/25 focus:border-blue-500/40";

        $btn = "inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg text-sm font-semibold
                bg-blue-600 hover:bg-blue-700 text-white transition-colors shadow-sm";

        $btnGhost = "inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg text-sm font-semibold
                     border border-light-eval-3 dark:border-dark-eval-2
                     text-light-text-secondary dark:text-dark-text-secondary
                     hover:bg-light-eval-2 dark:hover:bg-dark-eval-2 transition-colors";

        $thead = "bg-light-eval-2 dark:bg-dark-eval-2 border-b
                  border-light-eval-3 dark:border-dark-eval-2";

        $th = "px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider
               text-light-text-secondary dark:text-dark-text-secondary";

        $td = "px-4 py-3 text-sm text-light-text-secondary dark:text-dark-text-secondary";
    @endphp

    {{-- MAIN CONTENT --}}
    <div class="{{ $page }}">
        <div class="{{ $wrap }}">

            {{-- ===================== SECTION: PROJECT SUMMARY ===================== --}}
            <div class="{{ $card }}">

                <div class="{{ $cardHead }}">
                    <div>
                        <h3 class="{{ $title }}">Project Summary</h3>
                        <p class="{{ $sub }}">Overview of all projects (by year)</p>
                    </div>

                    <div class="flex flex-wrap items-center gap-3">
                        <select id="filter-year" class="{{ $input }} min-w-[160px]">
                            <option value="">Pilih Tahun</option>
                        </select>

                        <button id="filterBtn" class="{{ $btn }}">
                            <span class="text-base">🔍</span>
                            <span>Tampilkan</span>
                        </button>

                        <button id="resetBtn" type="button" class="{{ $btnGhost }}">
                            Reset
                        </button>
                    </div>
                </div>

                <div class="p-4 sm:p-6">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        {{-- All Project --}}
                        <div class="rounded-2xl border p-4 sm:p-5
                                    bg-light-bg dark:bg-dark-eval-2
                                    border-light-eval-3 dark:border-dark-eval-2">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <div class="text-xs font-semibold uppercase tracking-wider
                                                text-light-text-muted dark:text-dark-text-secondary">
                                        All Project
                                    </div>
                                    <div id="all-projects" class="mt-2 text-2xl sm:text-3xl font-bold text-light-text dark:text-dark-text">
                                        -
                                    </div>
                                </div>
                               
                            </div>
                           
                        </div>

                        {{-- Waiting --}}
                        <div class="rounded-2xl border p-4 sm:p-5
                                    bg-light-bg dark:bg-dark-eval-2
                                    border-light-eval-3 dark:border-dark-eval-2">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <div class="text-xs font-semibold uppercase tracking-wider
                                                text-light-text-muted dark:text-dark-text-secondary">
                                        Waiting
                                    </div>
                                    <div id="waiting-projects" class="mt-2 text-2xl sm:text-3xl font-bold text-light-text dark:text-dark-text">
                                        -
                                    </div>
                                </div>
                              
                            </div>
                         
                        </div>

                        {{-- Active --}}
                        <div class="rounded-2xl border p-4 sm:p-5
                                    bg-light-bg dark:bg-dark-eval-2
                                    border-light-eval-3 dark:border-dark-eval-2">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <div class="text-xs font-semibold uppercase tracking-wider
                                                text-light-text-muted dark:text-dark-text-secondary">
                                        Active
                                    </div>
                                    <div id="active-projects" class="mt-2 text-2xl sm:text-3xl font-bold text-light-text dark:text-dark-text">
                                        -
                                    </div>
                                </div>
                              
                            </div>
                           
                        </div>

                        {{-- SLA --}}
                        <div class="rounded-2xl border p-4 sm:p-5
                                    bg-light-bg dark:bg-dark-eval-2
                                    border-light-eval-3 dark:border-dark-eval-2">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <div class="text-xs font-semibold uppercase tracking-wider
                                                text-light-text-muted dark:text-dark-text-secondary">
                                        SLA
                                    </div>
                                    <div id="sla-value" class="mt-2 text-2xl sm:text-3xl font-bold text-light-text dark:text-dark-text">
                                        -
                                    </div>
                                </div>
                               
                            </div>
                           
                        </div>
                    </div>
                </div>
            </div>

            {{-- ===================== SECTION: GANTT CHART ===================== --}}
            <div class="{{ $card }}">
                <div class="p-4 sm:p-6 border-b border-light-eval-3 dark:border-dark-eval-2">
                    <h3 class="{{ $title }}">Progress Timeline (Gantt Chart)</h3>
                    <p class="{{ $sub }}">Visual project timeline</p>
                </div>

                <div class="p-4 sm:p-6">
                    <div class="overflow-x-auto rounded-2xl border
                                border-light-eval-3 dark:border-dark-eval-2
                                bg-light-bg dark:bg-dark-eval-2">
                        <div id="gantt" class="min-w-[720px] w-full p-4"></div>
                    </div>
                </div>
            </div>

            {{-- ===================== SECTION: PROJECT QUEUE ===================== --}}
            <div class="{{ $card }}">
                <div class="p-4 sm:p-6 border-b border-light-eval-3 dark:border-dark-eval-2">
                    <h3 class="{{ $title }}">List of Project Queues</h3>
                    <p class="{{ $sub }}">List of all project queues</p>
                </div>

                <div id="projectQueueContainer" class="p-4 sm:p-6">
                    <div class="overflow-x-auto rounded-2xl border
                                border-light-eval-3 dark:border-dark-eval-2
                                bg-light-bg dark:bg-dark-eval-2">
                        {{-- DESKTOP TABLE --}}
                        <table class="w-full text-sm hidden md:table min-w-[760px]">
                            <thead class="{{ $thead }}">
                                <tr>
                                    <th class="{{ $th }} w-14 text-center">No</th>
                                    <th class="{{ $th }}">Project Code</th>
                                    <th class="{{ $th }}">Project Name</th>
                                    <th class="{{ $th }}">Priority</th>
                                    <th class="{{ $th }}">Requestor</th>
                                    <th class="{{ $th }}">Description</th>
                                    <th class="{{ $th }}">Start Date</th>
                                    <th class="{{ $th }}">End Date</th>
                                </tr>
                            </thead>

                            <tbody id="projectQueueTableBody" class="divide-y divide-light-eval-3 dark:divide-dark-eval-2">
                                <tr>
                                    <td colspan="8" class="py-10 text-center text-sm text-light-text-muted dark:text-dark-text-secondary italic">
                                        Loading data...
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        {{-- MOBILE CARD VIEW --}}
                        <div id="projectQueueCardBody" class="grid gap-4 md:hidden p-4"></div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Gantt assets --}}
    <link rel="stylesheet" href="{{ asset('css/frappe-gantt.css') }}">
    <script src="{{ asset('js/frappe-gantt.min.js') }}"></script>

    <style>
        /* bikin gantt kelihatan lebih halus */
        .gantt-container .bar {
            rx: 8px;
            ry: 8px;
        }
        .gantt .grid-header,
        .gantt .grid-row,
        .gantt .tick {
            stroke-opacity: .35;
        }
        .popup-wrapper {
            border-radius: 14px !important;
            overflow: hidden !important;
        }
        .popup-wrapper .title {
            font-weight: 700 !important;
        }

        /* (opsional) remove default x-cloak */
        [x-cloak] { display: none !important; }
    </style>

    <script>
        document.addEventListener("DOMContentLoaded", async () => {
            const yearSelect = document.getElementById('filter-year');
            const tbody = document.getElementById('projectQueueTableBody');
            const cardBody = document.getElementById('projectQueueCardBody');

            const filterBtn = document.getElementById('filterBtn');
            const resetBtn = document.getElementById('resetBtn');

            const currentYear = new Date().getFullYear();

            for (let y = currentYear + 2; y >= 2025; y--) {
                const opt = document.createElement('option');
                opt.value = y;
                opt.textContent = y;
                if (y === currentYear) opt.selected = true;
                yearSelect.appendChild(opt);
            }

            await loadAll(currentYear);

            filterBtn.addEventListener("click", async () => {
                await loadAll(yearSelect.value || currentYear);
            });

            resetBtn.addEventListener("click", async () => {
                yearSelect.value = currentYear;
                await loadAll(currentYear);
            });

            async function loadAll(year) {
                await Promise.all([
                    loadSummary(year),
                    loadProjectQueue(year),
                    loadGanttChart(year)
                ]);
            }

            async function loadSummary(year) {
                try {
                    const res = await fetch(`/api/SummaryProject?year=${year}`);
                    const data = await res.json();

                    document.getElementById('all-projects').textContent = data.total ?? '-';
                    document.getElementById('waiting-projects').textContent = data.waiting ?? '-';
                    document.getElementById('active-projects').textContent = data.active ?? '-';
                    document.getElementById('sla-value').textContent = data.sla ? `${data.sla}%` : '0%';
                } catch {
                    ['all-projects', 'waiting-projects', 'active-projects', 'sla-value']
                        .forEach(id => document.getElementById(id).textContent = '-');
                }
            }

            async function loadProjectQueue(year) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="8" class="py-10 text-center text-sm text-light-text-muted dark:text-dark-text-secondary italic">
                            Loading...
                        </td>
                    </tr>`;
                cardBody.innerHTML = `
                    <div class="py-6 text-center text-sm text-light-text-muted dark:text-dark-text-secondary italic">
                        Loading...
                    </div>`;

                try {
                    const res = await fetch(`/api/ProjectQueue?year=${year}`);
                    const json = await res.json();
                    const data = json.data?.ProjectQueue || [];

                    if (data.length === 0) {
                        tbody.innerHTML = `
                            <tr>
                                <td colspan="8" class="py-10 text-center text-sm text-light-text-muted dark:text-dark-text-secondary italic">
                                    No Data Available
                                </td>
                            </tr>`;
                        cardBody.innerHTML = `
                            <div class="py-6 text-center text-sm text-light-text-muted dark:text-dark-text-secondary italic">
                                No Data Available
                            </div>`;
                        return;
                    }

                    tbody.innerHTML = '';
                    cardBody.innerHTML = '';

                    data.forEach((item, index) => {
                        const pr = (item.priority?.name || '').toLowerCase();
                        const badgeClass =
                            pr === 'high'
                                ? 'bg-red-600/10 text-red-700 dark:bg-red-400/10 dark:text-red-300'
                                : pr === 'medium'
                                    ? 'bg-yellow-500/15 text-yellow-700 dark:bg-yellow-400/10 dark:text-yellow-300'
                                    : 'bg-blue-600/10 text-blue-700 dark:bg-blue-400/10 dark:text-blue-300';

                        // === TABLE VIEW ===
                        const row = `
                            <tr class="transition-colors hover:bg-light-eval-2 dark:hover:bg-dark-eval-1">
                                <td class="px-4 py-3 text-center text-sm font-semibold text-light-text dark:text-dark-text">${index + 1}</td>
                                <td class="px-4 py-3 text-sm text-light-text-secondary dark:text-dark-text-secondary">${item.project_code ?? '-'}</td>
                                <td class="px-4 py-3 text-sm font-semibold text-light-text dark:text-dark-text">${item.project_name ?? '-'}</td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold ${badgeClass}">
                                        ${item.priority?.name ?? '-'}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm text-light-text-secondary dark:text-dark-text-secondary">${item.requestor?.name ?? '-'}</td>
                                <td class="px-4 py-3 text-sm text-light-text-secondary dark:text-dark-text-secondary max-w-[420px] whitespace-normal break-words">
                                    ${item.description ?? '-'}
                                </td>
                                <td class="px-4 py-3 text-sm text-light-text-secondary dark:text-dark-text-secondary">${item.start_date ?? '-'}</td>
                                <td class="px-4 py-3 text-sm text-light-text-secondary dark:text-dark-text-secondary">${item.end_date ?? '-'}</td>
                            </tr>`;
                        tbody.insertAdjacentHTML('beforeend', row);

                        // === CARD VIEW ===
                        const card = `
                            <div class="rounded-2xl border p-4 shadow-sm
                                        bg-light-bg dark:bg-dark-eval-2
                                        border-light-eval-3 dark:border-dark-eval-2">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="min-w-0">
                                        <div class="text-sm font-semibold text-light-text dark:text-dark-text truncate">
                                            ${item.project_name ?? '-'}
                                        </div>
                                        <div class="text-xs mt-0.5 text-light-text-muted dark:text-dark-text-secondary">
                                            Code: <span class="font-medium">${item.project_code ?? '-'}</span>
                                        </div>
                                    </div>
                                    <span class="shrink-0 inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold ${badgeClass}">
                                        ${item.priority?.name ?? '-'}
                                    </span>
                                </div>

                                <div class="mt-3 text-sm text-light-text-secondary dark:text-dark-text-secondary break-words">
                                    ${item.description ?? '-'}
                                </div>

                                <div class="mt-4 grid grid-cols-2 gap-3 text-xs text-light-text-muted dark:text-dark-text-secondary">
                                    <div class="rounded-xl border p-2
                                                bg-light-eval-2 dark:bg-dark-eval-1
                                                border-light-eval-3 dark:border-dark-eval-2">
                                        <div class="font-semibold">Requestor</div>
                                        <div class="mt-0.5">${item.requestor?.name ?? '-'}</div>
                                    </div>
                                    <div class="rounded-xl border p-2
                                                bg-light-eval-2 dark:bg-dark-eval-1
                                                border-light-eval-3 dark:border-dark-eval-2">
                                        <div class="font-semibold">Timeline</div>
                                        <div class="mt-0.5">${item.start_date ?? '-'} → ${item.end_date ?? '-'}</div>
                                    </div>
                                </div>
                            </div>`;
                        cardBody.insertAdjacentHTML('beforeend', card);
                    });
                } catch {
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="8" class="py-10 text-center text-sm text-red-600 dark:text-red-400">
                                Gagal memuat data
                            </td>
                        </tr>`;
                    cardBody.innerHTML = `
                        <div class="py-6 text-center text-sm text-red-600 dark:text-red-400">
                            Gagal memuat data
                        </div>`;
                }
            }

            async function loadGanttChart(year) {
                const ganttContainer = document.getElementById('gantt');
                ganttContainer.innerHTML = `
                    <div class="py-10 text-center text-sm text-light-text-muted dark:text-dark-text-secondary italic">
                        Loading...
                    </div>`;

                try {
                    const res = await fetch(`/api/ProjectMonitorGraph?year=${year}`);
                    const projects = await res.json();

                    if (!projects.length) {
                        ganttContainer.innerHTML = `
                            <div class="py-10 text-center text-sm text-light-text-muted dark:text-dark-text-secondary italic">
                                No Data Available.
                            </div>`;
                        return;
                    }

                    ganttContainer.innerHTML = '';

                    const tasks = projects.map(p => ({
                        id: p.id,
                        name: `${p.name} - [ ${p.status_name} - ${p.progress}% ] ${p.is_late}`,
                        start: `${year}-${String(p.month_start).padStart(2, '0')}-${String(p.day_start).padStart(2, '0')}`,
                        end: `${year}-${String(p.month_end).padStart(2, '0')}-${String(p.day_end).padStart(2, '0')}`,
                        progress: p.progress,
                        status_name: p.status_name
                    }));

                    new Gantt("#gantt", tasks, {
                        view_mode: "Week",
                        date_format: "YYYY-MM-DD",
                        custom_popup_html: task => `
                            <div class='popup-content' style="padding:12px 14px;">
                                <div style="font-weight:800; margin-bottom:6px;">${task.name}</div>
                                <div style="font-size:12px; opacity:.9;">${task.start} → ${task.end}</div>
                                <div style="margin-top:8px; font-size:12px;">
                                    Progress: <b>${task.progress}%</b><br/>
                                    Status: ${task.status_name}
                                </div>
                            </div>
                        `
                    });
                } catch {
                    ganttContainer.innerHTML = `
                        <div class="py-10 text-center text-sm text-red-600 dark:text-red-400 italic">
                            Gagal memuat Gantt Chart.
                        </div>`;
                }
            }
        });
    </script>

</x-app-layout>
