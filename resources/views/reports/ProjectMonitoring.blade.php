<x-app-layout>

    {{-- HEADER --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 dark:text-white">
            Monitoring Project Queue
        </h2>
    </x-slot>

    {{-- MAIN CONTENT --}}
    <div class="min-h-screen bg-light-bg dark:bg-dark-bg">
        <div class="w-full px-4 sm:px-6 lg:px-8 py-6 space-y-6">

            {{-- SECTION: PROJECT QUEUE TABLE --}}
            <div class="bg-white dark:bg-dark-eval-1 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                
                {{-- Header with Filter --}}
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 p-4 sm:p-6 border-b border-gray-200 dark:border-gray-700">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            Daftar Project Queue
                        </h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">List of all project queues</p>
                    </div>

                    {{-- Filter Section --}}
                    <div class="flex flex-wrap items-center gap-3 w-full sm:w-auto">
                        <select id="filter-year"
                            class="flex-1 sm:flex-none px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-dark-eval-2 text-gray-900 dark:text-white focus:ring-2 focus:ring-gray-400 dark:focus:ring-gray-500">
                            <option value="">Pilih Tahun</option>
                        </select>

                        <button id="filterBtn"
                            class="flex items-center justify-center gap-2 px-4 py-2 bg-blue-500 dark:bg-gray-100 text-white dark:text-gray-900 rounded-lg hover:bg-blue-800 dark:hover:bg-gray-200 transition-colors text-sm font-medium shadow-sm">
                            <span>🔍</span>
                            <span>Tampilkan</span>
                        </button>
                    </div>
                </div>

                {{-- Table --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 dark:bg-dark-eval-2 border-b border-gray-200 dark:border-gray-700">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">No</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Project Code</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Project Name</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Priority</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Requestor</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Description</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Start Date</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">End Date</th>
                            </tr>
                        </thead>
                        <tbody id="projectQueueTableBody" class="divide-y divide-gray-200 dark:divide-gray-700">
                            <tr>
                                <td colspan="7" class="text-center text-gray-500 dark:text-gray-400 py-8 italic">
                                    Loading data...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- SECTION: GANTT CHART --}}
            <div class="bg-white dark:bg-dark-eval-1 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="p-4 sm:p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Progress Timeline (Gantt Chart)
                    </h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Visual project timeline</p>
                </div>
                <div class="p-4 sm:p-6">
                    <div id="gantt"></div>
                </div>
            </div>

            {{-- SECTION: PROJECT SUMMARY --}}
            <div class="bg-white dark:bg-dark-eval-1 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="p-4 sm:p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Project Summary
                    </h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Overview of all projects</p>
                </div>

                <div class="p-3 sm:p-6">
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-9 gap-4">
                        
                        {{-- All Projects --}}
                        <div class="bg-gray-50 dark:bg-dark-eval-2 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
                            <div class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-2">All Project</div>
                            <div id="all-projects" class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">-</div>
                        </div>

                        {{-- Waiting Projects --}}
                        <div class="bg-gray-50 dark:bg-dark-eval-2 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
                            <div class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-2">Waiting</div>
                            <div id="waiting-projects" class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">-</div>
                        </div>

                        {{-- Void Projects --}}
                        <div class="bg-gray-50 dark:bg-dark-eval-2 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
                            <div class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-2">Void</div>
                            <div id="void-projects" class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">-</div>
                        </div>

                        {{-- Active Projects --}}
                        <div class="bg-gray-50 dark:bg-dark-eval-2 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
                            <div class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-2">Active</div>
                            <div id="active-projects" class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">-</div>
                        </div>

                        {{-- Pending Projects --}}
                        <div class="bg-gray-50 dark:bg-dark-eval-2 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
                            <div class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-2">Pending</div>
                            <div id="pending-projects" class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">-</div>
                        </div>

                        {{-- Closed Projects --}}
                        <div class="bg-gray-50 dark:bg-dark-eval-2 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
                            <div class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-2">Closed</div>
                            <div id="closed-projects" class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">-</div>
                        </div>

                        {{-- Closed On Time --}}
                        <div class="bg-gray-50 dark:bg-dark-eval-2 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
                            <div class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-2">On Time</div>
                            <div id="closedOnTime-projects" class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">-</div>
                        </div>

                        {{-- Closed Late --}}
                        <div class="bg-gray-50 dark:bg-dark-eval-2 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
                            <div class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-2">Late</div>
                            <div id="closedLate-projects" class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">-</div>
                        </div>

                        {{-- SLA --}}
                        <div class="bg-gray-50 dark:bg-dark-eval-2 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
                            <div class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-2">SLA</div>
                            <div id="sla-value" class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">-</div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>

  <style>
        #gantt {
            min-height: 400px;
            background: #f9fafb;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            transition: all 0.3s;
        }

        .dark #gantt {
            background: #1f2937;
            border-color: #4b5563;
        }

        .popup-content {
            padding: 8px;
            font-size: 0.875rem;
            background: white;
            border-radius: 6px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .dark .popup-content {
            background: #374151;
            color: #f9fafb;
        }

        /* Gantt chart dark mode adjustments */
        .dark .gantt .grid-background {
            fill: #1f2937;
        }

        .dark .gantt .grid-header {
            fill: #374151;
        }

        .dark .gantt text {
            fill: #f9fafb !important;
            font-weight: 500;
        }

        .dark .gantt .tick {
            stroke: #6b7280;
        }

        .dark .gantt .grid-row {
            fill: #111827;
        }

        .dark .gantt .bar {
            fill: #6b7280;
        }

        .dark .gantt .bar-progress {
            fill: #10b981;
        }

        /* Loading animation */
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .spin {
            animation: spin 1s linear infinite;
        }
    </style>
   <!-- Day.js -->
    <script src="https://cdn.jsdelivr.net/npm/dayjs@1.12.0/dayjs.min.js"></script>

    <!-- Frappe Gantt -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/frappe-gantt@0.6.1/dist/frappe-gantt.css">
    <script src="https://cdn.jsdelivr.net/npm/frappe-gantt@0.6.1/dist/frappe-gantt.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", async () => {
            const yearSelect = document.getElementById('filter-year');

            // === Inisialisasi Dropdown Tahun ===
            const currentYear = new Date().getFullYear();
            const startYear = 2020;
            const endYear = currentYear + 5;

            for (let y = endYear; y >= startYear; y--) {
                const opt = document.createElement('option');
                opt.value = y;
                opt.textContent = y;
                if (y === currentYear) opt.selected = true;
                yearSelect.appendChild(opt);
            }

            // === Load Data Awal (tahun sekarang) ===
            await loadAll(currentYear);

            // === Tombol Filter ===
            document.getElementById('filterBtn').addEventListener("click", async () => {
                const year = yearSelect.value || currentYear;
                await loadAll(year);
            });

            // === Fungsi Gabungan ===
            async function loadAll(year) {
                await Promise.all([
                    loadSummary(year),
                    loadProjectQueue(year),
                    loadGanttChart(year)
                ]);
            }

            // === Load Summary ===
            async function loadSummary(year) {
                try {
                    const res = await fetch(`{{ url('/api/SummaryProject') }}?year=${year}`);
                    if (!res.ok) throw new Error("Gagal ambil data summary");
                    const data = await res.json();
                    
                    document.getElementById('all-projects').textContent = data.total ?? '-';
                    document.getElementById('void-projects').textContent = data.void ?? '-';
                    document.getElementById('waiting-projects').textContent = data.waiting ?? '-';
                    document.getElementById('closedOnTime-projects').textContent = data.closedOnTime ?? '-';
                    document.getElementById('closedLate-projects').textContent = data.closedLate ?? '-';
                    document.getElementById('pending-projects').textContent = data.pending ?? '-';
                    document.getElementById('active-projects').textContent = data.active ?? '-';
                    document.getElementById('closed-projects').textContent = data.closed ?? '-';
                    document.getElementById('sla-value').textContent = data.sla ? `${data.sla}%` : '-';
                } catch (err) {
                    console.error('Error loading summary:', err);
                    // Set semua ke '-' jika error
                    ['all-projects', 'void-projects', 'waiting-projects', 'closedOnTime-projects', 
                     'closedLate-projects', 'pending-projects', 'active-projects', 'closed-projects', 'sla-value']
                    .forEach(id => document.getElementById(id).textContent = '-');
                }
            }

            // === Load Project Queue ===
            async function loadProjectQueue(year) {
                const tbody = document.getElementById('projectQueueTableBody');
                tbody.innerHTML = `<tr><td colspan="7" class="text-center text-gray-500 dark:text-gray-400 py-8 italic">Loading data...</td></tr>`;

                try {
                    const res = await fetch(`/api/ProjectQueue?year=${year}`);
                    const json = await res.json();
                    const data = json.data?.ProjectQueue || [];

                    if (data.length === 0) {
                        tbody.innerHTML = `<tr><td colspan="7" class="text-center text-gray-400 dark:text-gray-500 py-8 italic">Tidak ada project queue.</td></tr>`;
                        return;
                    }

                    tbody.innerHTML = '';
                    data.forEach((item, index) => {
                        const row = document.createElement('tr');
                        row.className = 'hover:bg-gray-50 dark:hover:bg-dark-eval-2 transition-colors';
                        row.innerHTML = `
                            <td class="px-4 py-3 text-gray-900 dark:text-white">${index + 1}</td>
                            <td class="px-4 py-3 text-gray-700 dark:text-gray-300">${item.project_code ?? '-'}</td>
                            <td class="px-4 py-3 text-gray-700 dark:text-gray-300">${item.project_name ?? '-'}</td>
                            <td class="px-4 py-3 text-gray-700 dark:text-gray-300">${item.priority?.priority_name ?? '-'}</td>
                            <td class="px-4 py-3 text-gray-700 dark:text-gray-300">${item.requestor?.name ?? '-'}</td>
                            <td class="px-4 py-3 text-gray-700 dark:text-gray-300">${item.description ?? '-'}</td>
                            <td class="px-4 py-3 text-gray-700 dark:text-gray-300">${item.start_date ?? '-'}</td>
                            <td class="px-4 py-3 text-gray-700 dark:text-gray-300">${item.end_date ?? '-'}</td>
                        `;
                        tbody.appendChild(row);
                    });
                } catch (error) {
                    console.error('Error loading project queue:', error);
                    tbody.innerHTML = `<tr><td colspan="7" class="text-center text-red-500 dark:text-red-400 py-8">Gagal memuat data</td></tr>`;
                }
            }

            // === Load Gantt Chart ===
            async function loadGanttChart(year) {
                const ganttContainer = document.getElementById('gantt');
                ganttContainer.innerHTML = `<p class="text-gray-500 dark:text-gray-400 italic text-center py-8">Loading Gantt chart...</p>`;

                try {
                    const res = await fetch(`/api/ProjectMonitorGraph?year=${year}`);
                    if (!res.ok) throw new Error("Gagal mengambil data Gantt Chart");
                    const projects = await res.json();

                    if (!projects.length) {
                        ganttContainer.innerHTML = `<p class="text-gray-400 dark:text-gray-500 italic text-center py-8">Tidak ada data proyek untuk ditampilkan.</p>`;
                        return;
                    }

                    ganttContainer.innerHTML = '';

                    const tasks = projects.map(p => {
                        const start = new Date(year, Number(p.month_start) - 1, Number(p.day_start));
                        const end = new Date(year, Number(p.month_end) - 1, Number(p.day_end));
                        const formatDate = d => d.toISOString().slice(0, 10);

                        return {
                            id: p.id,
                            name: `${p.name} [${p.status_name}]`,
                            start: formatDate(start),
                            end: formatDate(end),
                            progress: p.progress,
                            status_id: p.status_id,
                            status_name: p.status_name
                        };
                    });

                    new Gantt("#gantt", tasks, {
                        view_mode: "Week",
                        date_format: "YYYY-MM-DD",
                        custom_popup_html: task => {
                            const formatDDMMMYYYY = d => {
                                const date = new Date(d);
                                return date.toLocaleDateString('id-ID', {
                                    day: '2-digit',
                                    month: 'short',
                                    year: 'numeric'
                                });
                            };
                            return `
                                <div class='popup-content'>
                                    <strong>${task.name}</strong><br>
                                    ${formatDDMMMYYYY(task.start)} → ${formatDDMMMYYYY(task.end)}<br>
                                    Progress: <b>${task.progress}%</b><br>
                                    Status: ${task.status_name}
                                </div>
                            `;
                        }
                    });

                } catch (err) {
                    console.error("Error loading Gantt:", err);
                    ganttContainer.innerHTML = `<p class="text-red-500 dark:text-red-400 italic text-center py-8">Gagal memuat data Gantt Chart.</p>`;
                }
            }

        });
    </script>

</x-app-layout>