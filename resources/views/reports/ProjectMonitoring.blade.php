<x-app-layout>

    {{-- HEADER --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Monitoring Project Queue
        </h2>
    </x-slot>

    {{-- MAIN CONTENT --}}
    <div class="py-6 space-y-8">
        {{-- SECTION: PROJECT QUEUE TABLE --}}
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200">
                        Daftar Project Queue
                    </h3>
                    <button id="refreshBtn"
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition flex items-center gap-2">
                        <span id="refreshIcon" class="transition-transform duration-300">🔄</span> Refresh
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left border border-gray-200 dark:border-gray-700 rounded-lg">
                        <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200">
                            <tr>
                                <th class="px-4 py-2 border-b">No</th>
                                <th class="px-4 py-2 border-b">Project Code</th>
                                <th class="px-4 py-2 border-b">Project Name</th>
                                <th class="px-4 py-2 border-b">Priority</th>
                                <th class="px-4 py-2 border-b">Requestor</th>
                                <th class="px-4 py-2 border-b">Description</th>
                                <th class="px-4 py-2 border-b">Created At</th>
                            </tr>
                        </thead>
                        <tbody id="projectQueueTableBody" class="divide-y divide-gray-100 dark:divide-gray-700">
                            <tr>
                                <td colspan="6" class="text-center text-gray-500 py-4 italic">
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
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-4">
                    Progress Timeline (Gantt Chart)
                </h3>
                <div id="gantt"></div>
            </div>
        </div>
    </div>

    {{-- Frappe Gantt --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/frappe-gantt@0.6.1/dist/frappe-gantt.css">
    <script src="https://cdn.jsdelivr.net/npm/frappe-gantt@0.6.1/dist/frappe-gantt.min.js"></script>

    <style>
        #gantt {
            min-height: 450px;
            background: #f9fafb;
            border-radius: 8px;
            border: 1px solid #d1d5db;
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
    </style>

    {{-- SCRIPTS --}}
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            loadProjectQueue();
            loadGanttChart();

            const refreshBtn = document.getElementById('refreshBtn');
            const refreshIcon = document.getElementById('refreshIcon');

            refreshBtn.addEventListener('click', async () => {
                refreshIcon.classList.add('spin');
                await Promise.all([loadProjectQueue(), loadGanttChart()]);
                setTimeout(() => refreshIcon.classList.remove('spin'), 300);
            });
        });

        // === PROJECT QUEUE ===
        async function loadProjectQueue() {
            const tbody = document.getElementById('projectQueueTableBody');
            tbody.innerHTML =
                `<tr><td colspan="6" class="text-center text-gray-500 py-4 italic">Loading data...</td></tr>`;

            try {
                const res = await fetch('/api/ProjectQueue');
                const json = await res.json();
                const data = json.data?.ProjectQueue || [];

                if (data.length === 0) {
                    tbody.innerHTML =
                        `<tr><td colspan="6" class="text-center text-gray-400 py-4 italic">Tidak ada project queue.</td></tr>`;
                    return;
                }

                tbody.innerHTML = '';
                data.forEach((item, index) => {
                    tbody.insertAdjacentHTML('beforeend', `
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            <td class="px-4 py-2">${index + 1}</td>
                            <td class="px-4 py-2">${item.project_code ?? '-'}</td>
                            <td class="px-4 py-2">${item.project_name ?? '-'}</td>
                            <td class="px-4 py-2">${item.priority?.priority_name ?? '-'}</td>
                            <td class="px-4 py-2">${item.requestor?.name ?? '-'}</td>
                            <td class="px-4 py-2">${item.description ?? '-'}</td>
                            <td class="px-4 py-2">${new Date(item.created_at).toLocaleString('id-ID')}</td>
                        </tr>
                    `);
                });

            } catch (error) {
                console.error(error);
                tbody.innerHTML =
                    `<tr><td colspan="6" class="text-center text-red-500 py-4">Gagal memuat data</td></tr>`;
            }
        }

        // === GANTT CHART ===
        async function loadGanttChart() {
            const ganttContainer = document.getElementById('gantt');
            ganttContainer.innerHTML = `<p class="text-gray-500 italic text-center mt-4">Loading Gantt chart...</p>`;

            try {
                const res = await fetch('/api/ProjectMonitorGraph');
                if (!res.ok) throw new Error("Gagal mengambil data Gantt Chart");

                const projects = await res.json();
                console.log("📊 Data Gantt:", projects);

                if (!projects.length) {
                    ganttContainer.innerHTML = `<p class="text-gray-400 italic text-center mt-4">Tidak ada data proyek untuk ditampilkan.</p>`;
                    return;
                }

                ganttContainer.innerHTML = '';

                const tasks = projects.map(p => ({
                    id: p.id,
                    name: p.name,
                    start: p.start,
                    end: p.end,
                    progress: p.progress,
                    custom_class: p.progress >= 80 ? "bar-success" : "bar-progress"
                }));

                new Gantt("#gantt", tasks, {
                    view_mode: "Week",
                    date_format: "YYYY-MM-DD",
                    custom_popup_html: task => `
                        <div class='popup-content'>
                            <strong>${task.name}</strong><br>
                            ${task.start} → ${task.end}<br>
                            Progress: <b>${task.progress}%</b>
                        </div>
                    `
                });

            } catch (err) {
                console.error("❌ Error load Gantt:", err);
                ganttContainer.innerHTML =
                    `<p class="text-red-500 italic text-center mt-4">Gagal memuat data Gantt Chart.</p>`;
            }
        }
    </script>

</x-app-layout>
