<x-app-layout>

    {{-- HEADER --}}
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Monitoring Project Queue
            </h2>
        </div>
    </x-slot>

    {{-- MAIN CONTENT --}}
    <div class="py-6 space-y-8">
        {{-- SECTION: PROJECT QUEUE TABLE --}}
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6 transition-colors">
                <div class="flex flex-col sm:flex-row justify-between items-center mb-6 p-4 rounded-lg">
                    <!-- Title -->
                    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-4 sm:mb-0">
                        Daftar Project Queue
                    </h3>

                    <!-- Filter Section -->
                    <div class="flex flex-wrap items-center gap-3">
                        <!-- Filter Tahun -->
                        <div class="flex items-center gap-2">
                            <select id="filter-year"
                                class="border border-gray-300 dark:border-gray-700 rounded-md p-2 bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 w-36">
                                <option value="">Pilih Tahun</option>
                            </select>
                        </div>

                        <!-- Tombol Tampilkan -->
                        <button id="filterBtn"
                            class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700  transition-all duration-200 shadow-sm">
                            <span>🔍</span>
                            <span>Tampilkan</span>
                        </button>


                    </div>
                </div>


                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left border border-gray-200 dark:border-gray-700 rounded-lg">
                        <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200">
                            <tr>
                                <th class="px-4 py-2 border-b dark:border-gray-600">No</th>
                                <th class="px-4 py-2 border-b dark:border-gray-600">Project Code</th>
                                <th class="px-4 py-2 border-b dark:border-gray-600">Project Name</th>
                                <th class="px-4 py-2 border-b dark:border-gray-600">Priority</th>
                                <th class="px-4 py-2 border-b dark:border-gray-600">Requestor</th>
                                <th class="px-4 py-2 border-b dark:border-gray-600">Description</th>
                                <th class="px-4 py-2 border-b dark:border-gray-600">Created At</th>
                            </tr>
                        </thead>
                        <tbody id="projectQueueTableBody" class="divide-y divide-gray-100 dark:divide-gray-700">
                            <tr>
                                <td colspan="7" class="text-center text-gray-500 dark:text-gray-400 py-4 italic">
                                    Loading data...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- SECTION: GANTT CHART --}}
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6 transition-colors">
                <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-4">
                    Progress Timeline (Gantt Chart)
                </h3>
                <div id="gantt"></div>
            </div>
        </div>

        {{-- SECTION: PROJECT SUMMARY --}}
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6 transition-colors duration-300">
                <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-4">
                    Project Summary
                </h3>

                <div id="summary-container" class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                    <!-- Active Projects -->
                    <div
                        class="p-5 rounded-xl border border-gray-200 dark:border-gray-700 bg-blue-50 dark:bg-blue-900/30 flex flex-col items-center shadow-sm hover:shadow-md transition">
                        <h4 class="text-base font-semibold text-gray-700 dark:text-gray-200 mb-2">
                            All Project
                        </h4>
                        <p id="all-projects"
                            class="text-4xl font-bold text-blue-700 dark:text-blue-300 tracking-tight">
                            -
                        </p>
                    </div>
                    <!-- Active Projects -->
                    <div
                        class="p-5 rounded-xl border border-gray-200 dark:border-gray-700 bg-blue-50 dark:bg-blue-900/30 flex flex-col items-center shadow-sm hover:shadow-md transition">
                        <h4 class="text-base font-semibold text-gray-700 dark:text-gray-200 mb-2">
                            Waiting Projects
                        </h4>
                        <p id="waiting-projects"
                            class="text-4xl font-bold text-blue-700 dark:text-blue-300 tracking-tight">
                            -
                        </p>
                    </div>
                    <!-- Active Projects -->
                    <div
                        class="p-5 rounded-xl border border-gray-200 dark:border-gray-700 bg-blue-50 dark:bg-blue-900/30 flex flex-col items-center shadow-sm hover:shadow-md transition">
                        <h4 class="text-base font-semibold text-gray-700 dark:text-gray-200 mb-2">
                            Void Projects
                        </h4>
                        <p id="void-projects"
                            class="text-4xl font-bold text-blue-700 dark:text-blue-300 tracking-tight">
                            -
                        </p>
                    </div>
                    <!-- Active Projects -->
                    <div
                        class="p-5 rounded-xl border border-gray-200 dark:border-gray-700 bg-blue-50 dark:bg-blue-900/30 flex flex-col items-center shadow-sm hover:shadow-md transition">
                        <h4 class="text-base font-semibold text-gray-700 dark:text-gray-200 mb-2">
                            Active Projects
                        </h4>
                        <p id="active-projects"
                            class="text-4xl font-bold text-blue-700 dark:text-blue-300 tracking-tight">
                            -
                        </p>
                    </div>

                    <!-- Closed Projects -->
                    <div
                        class="p-5 rounded-xl border border-gray-200 dark:border-gray-700 bg-green-50 dark:bg-green-900/30 flex flex-col items-center shadow-sm hover:shadow-md transition">
                        <h4 class="text-base font-semibold text-gray-700 dark:text-gray-200 mb-2">
                            Closed Projects
                        </h4>
                        <p id="closed-projects"
                            class="text-4xl font-bold text-green-700 dark:text-green-300 tracking-tight">
                            -
                        </p>
                    </div>

                    <!-- SLA -->
                    <div
                        class="p-5 rounded-xl border border-gray-200 dark:border-gray-700 bg-yellow-50 dark:bg-yellow-900/30 flex flex-col items-center shadow-sm hover:shadow-md transition">
                        <h4 class="text-base font-semibold text-gray-700 dark:text-gray-200 mb-2">
                            SLA (%)
                        </h4>
                        <p id="sla-value"
                            class="text-4xl font-bold text-yellow-700 dark:text-yellow-300 tracking-tight">
                            -
                        </p>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <style>
        #gantt {
            min-height: 450px;
            background: #f9fafb;
            border-radius: 8px;
            border: 1px solid #d1d5db;
            transition: background-color 0.3s, border-color 0.3s;
        }

        .dark #gantt {
            background: #1f2937;
            border-color: #374151;
        }

        .popup-content {
            padding: 6px;
            font-size: 0.85rem;
        }

        /* Animasi icon saat refresh */
        .spin {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        /* Gantt chart dark mode adjustments */
        .dark .gantt .grid-background {
            fill: #1f2937;
        }

        .dark .gantt .grid-header {
            fill: #374151;
        }

        .dark .gantt text {
            fill: #ffffff !important;
            font-weight: bold;
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
    </style>
    <!-- Day.js harus di atas sebelum script main -->
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
                    document.getElementById('waiting-projects').textContent = data.active ?? '-';
                    document.getElementById('active-projects').textContent = data.active ?? '-';
                    document.getElementById('closed-projects').textContent = data.closed ?? '-';
                    document.getElementById('sla-value').textContent = data.sla ? `${data.sla}%` : '-';
                } catch (err) {
                    console.error(err);
                    document.getElementById('all-projects').textContent = '-';
                    document.getElementById('void-projects').textContent = '-';
                    document.getElementById('waiting-projects').textContent = '-';
                    document.getElementById('active-projects').textContent = '-';
                    document.getElementById('closed-projects').textContent = '-';
                    document.getElementById('sla-value').textContent = '-';
                }
            }

            // === Load Project Queue ===
            async function loadProjectQueue(year) {
                const tbody = document.getElementById('projectQueueTableBody');
                tbody.innerHTML =
                    `<tr><td colspan="7" class="text-center text-gray-500 dark:text-gray-400 py-4 italic">Loading data...</td></tr>`;

                try {
                    const res = await fetch(`/api/ProjectQueue?year=${year}`);
                    const json = await res.json();
                    const data = json.data?.ProjectQueue || [];

                    if (data.length === 0) {
                        tbody.innerHTML =
                            `<tr><td colspan="7" class="text-center text-gray-400 dark:text-gray-500 py-4 italic">Tidak ada project queue.</td></tr>`;
                        return;
                    }

                    tbody.innerHTML = '';
                    data.forEach((item, index) => {
                        tbody.insertAdjacentHTML('beforeend', `
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                        <td class="px-4 py-2 text-gray-900 dark:text-gray-100">${index + 1}</td>
                        <td class="px-4 py-2 text-gray-900 dark:text-gray-100">${item.project_code ?? '-'}</td>
                        <td class="px-4 py-2 text-gray-900 dark:text-gray-100">${item.project_name ?? '-'}</td>
                        <td class="px-4 py-2 text-gray-900 dark:text-gray-100">${item.priority?.priority_name ?? '-'}</td>
                        <td class="px-4 py-2 text-gray-900 dark:text-gray-100">${item.requestor?.name ?? '-'}</td>
                        <td class="px-4 py-2 text-gray-900 dark:text-gray-100">${item.description ?? '-'}</td>
                        <td class="px-4 py-2 text-gray-900 dark:text-gray-100">${new Date(item.created_at).toLocaleString('id-ID')}</td>
                    </tr>
                `);
                    });
                } catch (error) {
                    console.error(error);
                    tbody.innerHTML =
                        `<tr><td colspan="7" class="text-center text-red-500 dark:text-red-400 py-4">Gagal memuat data</td></tr>`;
                }
            }

            // === Load Gantt Chart ===
            async function loadGanttChart(year) {
                const ganttContainer = document.getElementById('gantt');
                ganttContainer.innerHTML =
                    `<p class="text-gray-500 dark:text-gray-400 italic text-center mt-4">Loading Gantt chart...</p>`;

                try {
                    const res = await fetch(`/api/ProjectMonitorGraph?year=${year}`);
                    if (!res.ok) throw new Error("Gagal mengambil data Gantt Chart");
                    const projects = await res.json();

                    if (!projects.length) {
                        ganttContainer.innerHTML =
                            `<p class="text-gray-400 dark:text-gray-500 italic text-center mt-4">Tidak ada data proyek untuk ditampilkan.</p>`;
                        return;
                    }

                    ganttContainer.innerHTML = '';

                    const tasks = projects.map(p => {
                        // Cutoff tanggal ke tahun yang dipilih
                        const start = new Date(year, Number(p.month_start) - 1, Number(p
                            .day_start));
                        const end = new Date(year, Number(p.month_end) - 1, Number(p.day_end));

                        // Format YYYY-MM-DD
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
                    <div class='popup-content text-sm p-2'>
                        <strong>${task.name}</strong><br>
                        ${formatDDMMMYYYY(task.start)} → ${formatDDMMMYYYY(task.end)}<br>
                        Progress: <b>${task.progress}%</b><br>
                        Status: ${task.status_name}
                    </div>
                `;
                        }
                    });

                } catch (err) {
                    console.error("❌ Error load Gantt:", err);
                    ganttContainer.innerHTML =
                        `<p class="text-red-500 dark:text-red-400 italic text-center mt-4">Gagal memuat data Gantt Chart.</p>`;
                }
            }

        });
    </script>


</x-app-layout>
