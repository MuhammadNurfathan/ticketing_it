<x-app-layout>

    {{-- HEADER --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 dark:text-white text-center sm:text-left">
            Monitoring Project Queue
        </h2>
    </x-slot>

    {{-- MAIN CONTENT --}}
    <div class="min-h-screen bg-light-bg dark:bg-dark-bg">
        <div class="w-full px-4 sm:px-6 lg:px-8 py-6 space-y-6">

            {{-- SECTION: PROJECT SUMMARY --}}
            <div
                class="bg-white dark:bg-dark-eval-1 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">

                <div
                    class="p-4 sm:p-6 border-b border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row justify-between sm:items-center gap-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white text-center sm:text-left">
                            Project Summary
                        </h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1 text-center sm:text-left">
                            Overview of all projects
                        </p>
                    </div>

                    <div class="flex flex-row flex-wrap items-center gap-3">
                        <select id="filter-year"
                            class="px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-dark-eval-2 text-gray-900 dark:text-white focus:ring-2 focus:ring-gray-400 dark:focus:ring-gray-500">
                            <option value="">Pilih Tahun</option>
                        </select>

                        <button id="filterBtn"
                            class="flex items-center justify-center gap-2 px-4 py-2 bg-blue-500 dark:bg-gray-100 text-white dark:text-gray-900 rounded-lg hover:bg-blue-800 dark:hover:bg-gray-200 transition-colors text-sm font-medium shadow-sm">
                            <span>🔍</span>
                            <span>Tampilkan</span>
                        </button>
                    </div>
                </div>



                <div class="p-3 sm:p-6">
                    <div
                        class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-4 gap-4 place-items-stretch">
                        {{-- All Project --}}
                        <div
                            class="flex flex-col justify-center items-center text-center bg-gray-50 dark:bg-dark-eval-2 rounded-xl p-4 border border-gray-200 dark:border-gray-700 h-full">
                            <div
                                class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-2">
                                All Project
                            </div>
                            <div id="all-projects" class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">
                                -</div>
                        </div>

                        {{-- Waiting --}}
                        <div
                            class="flex flex-col justify-center items-center text-center bg-gray-50 dark:bg-dark-eval-2 rounded-xl p-4 border border-gray-200 dark:border-gray-700 h-full">
                            <div
                                class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-2">
                                Waiting
                            </div>
                            <div id="waiting-projects"
                                class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">-</div>
                        </div>

                        {{-- Active --}}
                        <div
                            class="flex flex-col justify-center items-center text-center bg-gray-50 dark:bg-dark-eval-2 rounded-xl p-4 border border-gray-200 dark:border-gray-700 h-full">
                            <div
                                class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-2">
                                Active
                            </div>
                            <div id="active-projects"
                                class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">-</div>
                        </div>

                        {{-- SLA --}}
                        <div
                            class="flex flex-col justify-center items-center text-center bg-gray-50 dark:bg-dark-eval-2 rounded-xl p-4 border border-gray-200 dark:border-gray-700 h-full">
                            <div
                                class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-2">
                                SLA
                            </div>
                            <div id="sla-value" class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">-
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- SECTION: GANTT CHART --}}
            <div
                class="bg-white dark:bg-dark-eval-1 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="p-4 sm:p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white text-center sm:text-left">
                        Progress Timeline (Gantt Chart)
                    </h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1 text-center sm:text-left">
                        Visual project timeline
                    </p>
                </div>
                <div class="p-4 sm:p-6">
                    <div class="overflow-x-auto">
                        <div id="gantt" class="min-w-[600px] w-full h-auto"></div>
                    </div>
                </div>

            </div>

            {{-- SECTION: PROJECT QUEUE --}}
            <div
                class="bg-white dark:bg-dark-eval-1 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div
                    class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 p-4 sm:p-6 border-b border-gray-200 dark:border-gray-700">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">List of Project Queues</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">List of all project queues</p>
                    </div>

                </div>

                {{-- Table & Card Responsive --}}
                <div id="projectQueueContainer" class="overflow-x-auto p-4">
                    <table class="w-full text-sm hidden md:table min-w-[600px]">
                        <thead class="bg-gray-50 dark:bg-dark-eval-2 border-b border-gray-200 dark:border-gray-700">
                            <tr>
                                @foreach (['No', 'Project Code', 'Project Name', 'Priority', 'Requestor', 'Description', 'Start Date', 'End Date'] as $head)
                                    <th
                                        class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                        {{ $head }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody id="projectQueueTableBody" class="divide-y divide-gray-200 dark:divide-gray-700">
                            <tr>
                                <td colspan="8" class="text-center text-gray-500 dark:text-gray-400 py-8 italic">
                                    Loading data...</td>
                            </tr>
                        </tbody>
                    </table>

                    {{-- MOBILE CARD VIEW --}}
                    <div id="projectQueueCardBody" class="grid gap-4 md:hidden"></div>
                </div>
            </div>
        </div>
    </div>


    <link rel="stylesheet" href="{{ asset('css/frappe-gantt.css') }}">
    <script src="{{ asset('js/frappe-gantt.min.js') }}"></script>

    <script>
        document.addEventListener("DOMContentLoaded", async () => {
            const yearSelect = document.getElementById('filter-year');
            const tbody = document.getElementById('projectQueueTableBody');
            const cardBody = document.getElementById('projectQueueCardBody');

            const currentYear = new Date().getFullYear();
            for (let y = currentYear + 2; y >= 2025; y--) {
                const opt = document.createElement('option');
                opt.value = y;
                opt.textContent = y;
                if (y === currentYear) opt.selected = true;
                yearSelect.appendChild(opt);
            }

            await loadAll(currentYear);
            document.getElementById('filterBtn').addEventListener("click", async () => {
                await loadAll(yearSelect.value || currentYear);
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
                    const res = await fetch(`{{ url('/api/SummaryProject') }}?year=${year}`);
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
                tbody.innerHTML =
                    `<tr><td colspan="8" class="text-center text-gray-500 dark:text-gray-400 py-8 italic">Loading...</td></tr>`;
                cardBody.innerHTML =
                    `<div class="text-center text-gray-500 dark:text-gray-400 py-4 italic">Loading...</div>`;

                try {
                    const res = await fetch(`/api/ProjectQueue?year=${year}`);
                    const json = await res.json();
                    const data = json.data?.ProjectQueue || [];

                    if (data.length === 0) {
                        tbody.innerHTML =
                            `<tr><td colspan="8" class="text-center py-8 text-gray-400 italic">No Data Available</td></tr>`;
                        cardBody.innerHTML =
                            `<div class="text-center py-4 text-gray-400 italic">No Data Available</div>`;
                        return;
                    }

                    tbody.innerHTML = '';
                    cardBody.innerHTML = '';

                    data.forEach((item, index) => {
                        // === TABLE VIEW ===
                        const row = `
                            <tr class="hover:bg-gray-50 dark:hover:bg-dark-eval-2 transition">
                                <td class="px-4 py-3">${index + 1}</td>
                                <td class="px-4 py-3">${item.project_code ?? '-'}</td>
                                <td class="px-4 py-3">${item.project_name ?? '-'}</td>
                                <td class="px-4 py-3">${item.priority?.priority_name ?? '-'}</td>
                                <td class="px-4 py-3">${item.requestor?.name ?? '-'}</td>
                                <td class="px-4 py-3">${item.description ?? '-'}</td>
                                <td class="px-4 py-3">${item.start_date ?? '-'}</td>
                                <td class="px-4 py-3">${item.end_date ?? '-'}</td>
                            </tr>`;
                        tbody.insertAdjacentHTML('beforeend', row);

                        // === CARD VIEW ===
                        const card = `
                            <div class="project-card">
                                <div class="flex justify-between items-center mb-2">
                                    <h4 class="font-semibold text-gray-900 dark:text-white">${item.project_name ?? '-'}</h4>
                                    <span class="text-xs px-2 py-1 rounded-full ${item.priority?.priority_name === 'High' ? 'bg-red-100 text-red-600' : 'bg-blue-100 text-blue-600'}">${item.priority?.priority_name ?? '-'}</span>
                                </div>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">${item.description ?? '-'}</p>
                                <div class="text-xs text-gray-500 dark:text-gray-400 space-y-1">
                                    <div><strong>Code:</strong> ${item.project_code ?? '-'}</div>
                                    <div><strong>Requestor:</strong> ${item.requestor?.name ?? '-'}</div>
                                    <div><strong>Start:</strong> ${item.start_date ?? '-'}</div>
                                    <div><strong>End:</strong> ${item.end_date ?? '-'}</div>
                                </div>
                            </div>`;
                        cardBody.insertAdjacentHTML('beforeend', card);
                    });
                } catch {
                    tbody.innerHTML =
                        `<tr><td colspan="8" class="text-center text-red-500 py-8">Gagal memuat data</td></tr>`;
                    cardBody.innerHTML =
                        `<div class="text-center text-red-500 py-4">Gagal memuat data</div>`;
                }
            }

            async function loadGanttChart(year) {
                const ganttContainer = document.getElementById('gantt');
                ganttContainer.innerHTML =
                    `<p class="text-center text-gray-400 py-8 italic">Loading...</p>`;

                try {
                    const res = await fetch(`/api/ProjectMonitorGraph?year=${year}`);
                    const projects = await res.json();
                    if (!projects.length) {
                        ganttContainer.innerHTML =
                            `<p class="text-center text-gray-500 py-8 italic">No Data Available.</p>`;
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
                            <div class='popup-content'>
                                <strong>${task.name}</strong><br>
                                ${task.start} → ${task.end}<br>
                                Progress: <b>${task.progress}%</b><br>
                                Status: ${task.status_name}
                            </div>
                        `
                    });
                } catch {
                    ganttContainer.innerHTML =
                        `<p class="text-center text-red-500 py-8 italic">Gagal memuat Gantt Chart.</p>`;
                }
            }
        });
    </script>

</x-app-layout>
