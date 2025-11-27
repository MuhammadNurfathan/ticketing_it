<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 dark:text-white">
            {{ __('Executive Ticket Insight') }}
        </h2>
    </x-slot>

    <div class="min-h-screen bg-light-bg dark:bg-dark-bg">
        <div class="w-full px-4 sm:px-6 lg:px-8 py-6 space-y-6">

            {{-- DOWNLOAD DATA SECTION --}}
            <div
                class="bg-white dark:bg-dark-eval-1 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="p-4 sm:p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Download Data Tickets</h2>

                    <form id="exportForm" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div>
                            <label for="start_date"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"> Tanggal Mulai
                            </label>
                            <input type="date" id="start_date" name="start_date"
                                class="block w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-dark-eval-2 text-gray-900 dark:text-white shadow-sm focus:ring-2 focus:ring-gray-400 dark:focus:ring-gray-500 text-sm px-3 py-2">
                        </div>

                        <div>
                            <label for="end_date"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"> Tanggal Akhir
                            </label>
                            <input type="date" id="end_date" name="end_date"
                                class="block w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-dark-eval-2 text-gray-900 dark:text-white shadow-sm focus:ring-2 focus:ring-gray-400 dark:focus:ring-gray-500 text-sm px-3 py-2">
                        </div>

                        <div class="flex items-end">
                            <button type="button" id="previewBtn"
                                class="w-full px-4 py-2 bg-gray-900 dark:bg-gray-100 hover:bg-gray-800 dark:hover:bg-gray-200 text-white dark:text-gray-900 rounded-lg shadow font-medium text-sm transition-colors">
                                👁️ Preview
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- MODAL PREVIEW --}}
            <div id="previewModal"
                class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
                <div
                    class="bg-white dark:bg-dark-eval-1 rounded-2xl shadow-2xl w-11/12 max-w-6xl p-6 transform scale-95 transition-all">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Preview Data Tickets</h3>

                    <div class="overflow-x-auto max-h-[60vh] border border-gray-200 dark:border-gray-700 rounded-lg">
                        <table class="min-w-full text-sm">
                            <thead class="bg-gray-50 dark:bg-dark-eval-2 sticky top-0">
                                <tr>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase">
                                        Ticket Code</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase">
                                        Requestor</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase">
                                        Support</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase">
                                        Problem</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase">
                                        Status</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase">
                                        Start</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase">
                                        End</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase">
                                        Time (min)</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase">
                                        Late</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase">
                                        Created</th>
                                </tr>
                            </thead>
                            <tbody id="previewTableBody"
                                class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-dark-eval-1">
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6 flex justify-end gap-3">
                        <button id="closePreview"
                            class="px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-dark-eval-2 transition-colors text-sm font-medium">
                            Tutup
                        </button>
                        <button id="confirmDownload"
                            class="px-4 py-2 bg-gray-900 dark:bg-gray-100 hover:bg-gray-800 dark:hover:bg-gray-200 text-white dark:text-gray-900 rounded-lg transition-colors text-sm font-medium">
                            💾 Download CSV
                        </button>
                    </div>
                </div>
            </div>

            {{-- BAR CHART - Tickets per Developer --}}
            <div
                class="bg-white dark:bg-dark-eval-1 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="p-4 sm:p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">
                        👨‍💻 Jumlah Ticket Selesai per Developer
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"> Pilih Tahun
                            </label>
                            <select id="bar_year"
                                class="block w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-dark-eval-2 text-gray-900 dark:text-white shadow-sm focus:ring-2 focus:ring-gray-400 dark:focus:ring-gray-500 text-sm px-3 py-2">
                            </select>
                        </div>
                        <div class="flex items-end">
                            <button id="filterBarBtn"
                                class="w-full bg-gray-900 dark:bg-gray-100 hover:bg-gray-800 dark:hover:bg-gray-200 text-white dark:text-gray-900 font-medium py-2 px-4 rounded-lg text-sm transition-colors">
                                Filter
                            </button>
                        </div>
                        <div class="flex items-end">
                            <button id="resetBarBtn"
                                class="w-full bg-gray-500 dark:bg-gray-600 hover:bg-gray-600 dark:hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-lg text-sm transition-colors">
                                Reset
                            </button>
                        </div>
                    </div>

                    <div class="relative flex justify-center items-center min-h-[400px]">
                        <div id="barLoadingIndicator"
                            class="absolute inset-0 flex flex-col justify-center items-center bg-white/90 dark:bg-dark-eval-1/90 rounded-lg hidden z-10">
                            <div
                                class="animate-spin rounded-full h-12 w-12 border-b-2 border-gray-900 dark:border-gray-100">
                            </div>
                            <p class="mt-4 text-gray-600 dark:text-gray-400">Memuat data...</p>
                        </div>
                        <div id="barNoDataMessage"
                            class="absolute inset-0 flex justify-center items-center text-gray-500 dark:text-gray-400 hidden z-10">
                            Tidak ada data untuk ditampilkan
                        </div>
                        <div id="barChartContainer" class="w-full p-4">
                            <canvas id="myBarChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            {{-- BAR CHART - Time Spent per Developer --}}
            <div
                class="bg-white dark:bg-dark-eval-1 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="p-4 sm:p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">
                        ⏱️ Total Time Spent per Developer
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"> Pilih Tahun
                            </label>
                            <select id="time_year"
                                class="block w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-dark-eval-2 text-gray-900 dark:text-white shadow-sm focus:ring-2 focus:ring-gray-400 dark:focus:ring-gray-500 text-sm px-3 py-2">
                            </select>
                        </div>
                        <div class="flex items-end">
                            <button id="filterTimeBtn"
                                class="w-full bg-gray-900 dark:bg-gray-100 hover:bg-gray-800 dark:hover:bg-gray-200 text-white dark:text-gray-900 font-medium py-2 px-4 rounded-lg text-sm transition-colors">
                                Filter
                            </button>
                        </div>
                        <div class="flex items-end">
                            <button id="resetTimeBtn"
                                class="w-full bg-gray-500 dark:bg-gray-600 hover:bg-gray-600 dark:hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-lg text-sm transition-colors">
                                Reset
                            </button>
                        </div>
                    </div>

                    <div class="relative flex justify-center items-center min-h-[400px]">
                        <div id="timeLoadingIndicator"
                            class="absolute inset-0 flex flex-col justify-center items-center bg-white/90 dark:bg-dark-eval-1/90 rounded-lg hidden z-10">
                            <div
                                class="animate-spin rounded-full h-12 w-12 border-b-2 border-gray-900 dark:border-gray-100">
                            </div>
                            <p class="mt-4 text-gray-600 dark:text-gray-400">Memuat data...</p>
                        </div>
                        <div id="timeNoDataMessage"
                            class="absolute inset-0 flex justify-center items-center text-gray-500 dark:text-gray-400 hidden z-10">
                            Tidak ada data untuk ditampilkan
                        </div>
                        <div id="timeChartContainer" class="w-full p-4">
                            <canvas id="timeSpentChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            {{-- TICKETS BY SUPPORT --}}
            <div
                class="bg-white dark:bg-dark-eval-1 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="p-4 sm:p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">
                        Tickets by Support (Per Hari)
                    </h2>

                    <div class="flex flex-col sm:flex-row gap-3 mb-6">
                        <input type="date" id="date"
                            class="flex-1 rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-dark-eval-2 text-gray-900 dark:text-white shadow-sm focus:ring-2 focus:ring-gray-400 dark:focus:ring-gray-500 text-sm px-3 py-2">
                        <button id="filterBtn"
                            class="px-6 py-2 bg-gray-900 dark:bg-gray-100 hover:bg-gray-800 dark:hover:bg-gray-200 text-white dark:text-gray-900 rounded-lg font-medium text-sm transition-colors">
                            Filter
                        </button>
                    </div>

                    <div id="ticketsContainer" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4"></div>
                </div>
            </div>

        </div>
    </div>

    {{-- Preview Modal Script --}}
    <script>
        document.getElementById('previewBtn').addEventListener('click', async () => {
            const start = document.getElementById('start_date').value;
            const end = document.getElementById('end_date').value;

            if (!start || !end) {
                alert('Isi tanggal mulai dan tanggal akhir terlebih dahulu.');
                return;
            }

            try {
                const res = await fetch(`/api/tickets/preview?start_date=${start}&end_date=${end}`);
                const result = await res.json();

                if (!result.data || result.data.length === 0) {
                    alert('Tidak ada data pada rentang tanggal tersebut.');
                    return;
                }

                const tbody = document.getElementById('previewTableBody');
                tbody.innerHTML = '';

                result.data.forEach(t => {
                    const row = document.createElement('tr');
                    row.className = 'hover:bg-gray-50 dark:hover:bg-dark-eval-2 transition-colors';
                    row.innerHTML = `
                <td class="px-4 py-3 text-gray-700 dark:text-gray-300">${t.ticket_code}</td>
                <td class="px-4 py-3 text-gray-700 dark:text-gray-300">${t.requestor_name}</td>
                <td class="px-4 py-3 text-gray-700 dark:text-gray-300">${t.support_name}</td>
                <td class="px-4 py-3 text-gray-700 dark:text-gray-300">${t.problem}</td>
                <td class="px-4 py-3 text-gray-700 dark:text-gray-300">${t.status}</td>
                <td class="px-4 py-3 text-gray-700 dark:text-gray-300">${t.start_date}</td>
                <td class="px-4 py-3 text-gray-700 dark:text-gray-300">${t.end_date}</td>
                <td class="px-4 py-3 text-center text-gray-700 dark:text-gray-300">${t.time_spent}</td>
                <td class="px-4 py-3 text-center text-gray-700 dark:text-gray-300">${t.is_late}</td>
                <td class="px-4 py-3 text-gray-700 dark:text-gray-300">${t.created_at}</td>
            `;
                    tbody.appendChild(row);
                });

                const modal = document.getElementById('previewModal');
                modal.classList.remove('hidden');
                setTimeout(() => modal.querySelector('div').classList.replace('scale-95', 'scale-100'), 10);

            } catch (err) {
                console.error(err);
                alert('Gagal memuat preview.');
            }
        });

        document.getElementById('closePreview').addEventListener('click', () => {
            const modal = document.getElementById('previewModal');
            modal.querySelector('div').classList.replace('scale-100', 'scale-95');
            setTimeout(() => modal.classList.add('hidden'), 200);
        });

        document.getElementById('confirmDownload').addEventListener('click', () => {
            const start = document.getElementById('start_date').value;
            const end = document.getElementById('end_date').value;
            window.location.href = `/api/tickets/export?start_date=${start}&end_date=${end}`;
            document.getElementById('previewModal').classList.add('hidden');
        });
    </script>

    {{-- Tickets by Support Script --}}
    <script>
        async function loadTickets() {
            const date = document.getElementById('date').value;
            const url = date ? `/api/tickets-by-support?date=${date}` : `/api/tickets-by-support`;

            try {
                const res = await fetch(url);
                const json = await res.json();
                const data = json.data || {};

                const supports = {
                    ticketsAzi: 'Azi',
                    ticketsApri: 'Apri',
                    ticketsBayu: 'Bayu',
                    ticketsFatih: 'Fatih'
                };

                const container = document.getElementById('ticketsContainer');
                let html = '';

                for (const key in supports) {
                    const tickets = data[key] || [];
                    html += `
                <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 bg-gray-50 dark:bg-dark-eval-2">
                    <h3 class="font-bold text-center text-gray-900 dark:text-white mb-3">${supports[key]}</h3>
                    <div class="space-y-2">
                        ${
                            tickets.length
                                ? tickets.map(t => `
                                            <div class="bg-white dark:bg-dark-eval-1 p-3 border border-gray-200 dark:border-gray-700 rounded-lg">
                                                <p class="text-sm text-gray-700 dark:text-gray-300"><b>Kode:</b> ${t.ticket_code}</p>
                                                <p class="text-sm text-gray-700 dark:text-gray-300"><b>Problem:</b> ${t.problem ?? '-'}</p>
                                                <p class="text-sm text-gray-700 dark:text-gray-300"><b>Solution:</b> ${t.solution ?? '-'}</p>
                                            </div>
                                        `).join('')
                                : '<p class="text-center text-gray-400 dark:text-gray-500 italic">Tidak ada tiket</p>'
                        }
                    </div>
                </div>
            `;
                }

                container.innerHTML = html;

            } catch (err) {
                console.error('Error loading tickets:', err);
                document.getElementById('ticketsContainer').innerHTML =
                    '<p class="text-center text-red-500">Gagal memuat tiket</p>';
            }
        }

        // Event listener
        document.getElementById('filterBtn').addEventListener('click', loadTickets);

        // Load awal
        loadTickets();
    </script>

    {{-- Bar & Time Charts Script --}}
    <script type="module">
        const NEUTRAL_COLOR = '#999999';
        let barChart, timeChart;

        function isDarkMode() {
            return document.documentElement.classList.contains('dark');
        }

        function getTextColor() {
            return NEUTRAL_COLOR;
        }

        function getGridColor() {
            return NEUTRAL_COLOR;
        }

        function getBgColor() {
            return isDarkMode() ? '#1f2937' : '#ffffff';
        }

        function getBarColors() {
            return ['#3b82f6', '#ef4444', '#10b981', '#f59e0b', '#8b5cf6', '#ec4899', '#06b6d4', '#84cc16'];
        }

        function getBarChartOptions() {
            return {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            color: getTextColor(),
                            font: {
                                size: 12
                            },
                            padding: 15
                        }
                    },
                    tooltip: {
                        backgroundColor: isDarkMode() ? '#374151' : '#fff',
                        titleColor: getTextColor(),
                        bodyColor: getTextColor(),
                        borderColor: getGridColor(),
                        borderWidth: 1
                    }
                },
                scales: {
                    x: {
                        grid: {
                            color: getGridColor(),
                            drawBorder: false
                        },
                        ticks: {
                            color: getTextColor(),
                            font: {
                                size: 11
                            }
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: getGridColor(),
                            drawBorder: false
                        },
                        ticks: {
                            color: getTextColor(),
                            font: {
                                size: 11
                            },
                            stepSize: 1
                        }
                    }
                }
            };
        }

        async function loadBarChart(year) {
            const loading = document.getElementById('barLoadingIndicator');
            const container = document.getElementById('barChartContainer');
            const noData = document.getElementById('barNoDataMessage');
            const canvas = document.getElementById('myBarChart');

            loading.classList.remove('hidden');
            container.classList.add('hidden');
            noData.classList.add('hidden');
            if (barChart) {
                barChart.destroy();
                barChart = null;
            }

            try {
                const res = await fetch(`/api/chart-tickets-by-dev?year=${year}`).then(r => r.json());
                loading.classList.add('hidden');
                if (res.success && res.data.datasets.length > 0) {
                    container.classList.remove('hidden');
                    barChart = new Chart(canvas, {
                        type: 'bar',
                        data: {
                            labels: res.data.labels,
                            datasets: res.data.datasets.map((ds, i) => ({
                                ...ds,
                                backgroundColor: getBarColors()[i % getBarColors().length],
                                borderColor: getBgColor(),
                                borderWidth: 1
                            }))
                        },
                        options: getBarChartOptions()
                    });
                } else {
                    noData.classList.remove('hidden');
                }
            } catch (e) {
                console.error(e);
                loading.classList.add('hidden');
                noData.classList.remove('hidden');
            }
        }

        async function loadTimeChart(year) {
            const loading = document.getElementById('timeLoadingIndicator');
            const container = document.getElementById('timeChartContainer');
            const noData = document.getElementById('timeNoDataMessage');
            const canvas = document.getElementById('timeSpentChart');

            loading.classList.remove('hidden');
            container.classList.add('hidden');
            noData.classList.add('hidden');
            if (timeChart) {
                timeChart.destroy();
                timeChart = null;
            }

            try {
                const res = await fetch(`/api/chart-time-spent-by-dev?year=${year}`).then(r => r.json());
                loading.classList.add('hidden');
                if (res.success && res.data.datasets.length > 0) {
                    container.classList.remove('hidden');
                    timeChart = new Chart(canvas, {
                        type: 'bar',
                        data: {
                            labels: res.data.labels,
                            datasets: res.data.datasets.map((ds, i) => ({
                                ...ds,
                                backgroundColor: getBarColors()[i % getBarColors().length],
                                borderColor: getBgColor(),
                                borderWidth: 1
                            }))
                        },
                        options: getBarChartOptions()
                    });
                } else {
                    noData.classList.remove('hidden');
                }
            } catch (e) {
                console.error(e);
                loading.classList.add('hidden');
                noData.classList.remove('hidden');
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            const currentYear = new Date().getFullYear();
            const barYear = document.getElementById('bar_year');
            const timeYear = document.getElementById('time_year');

            for (let y = currentYear; y >= currentYear - 5; y--) {
                barYear.innerHTML += `<option value="${y}">${y}</option>`;
                timeYear.innerHTML += `<option value="${y}">${y}</option>`;
            }

            document.getElementById('filterBarBtn').addEventListener('click', () => loadBarChart(barYear.value));
            document.getElementById('resetBarBtn').addEventListener('click', () => {
                barYear.value = currentYear;
                loadBarChart(currentYear);
            });
            document.getElementById('filterTimeBtn').addEventListener('click', () => loadTimeChart(timeYear.value));
            document.getElementById('resetTimeBtn').addEventListener('click', () => {
                timeYear.value = currentYear;
                loadTimeChart(currentYear);
            });

            loadBarChart(currentYear);
            loadTimeChart(currentYear);

            new MutationObserver(() => {
                if (barChart) loadBarChart(barYear.value);
                if (timeChart) loadTimeChart(timeYear.value);
            }).observe(document.documentElement, {
                attributes: true,
                attributeFilter: ['class']
            });
        });
    </script>

</x-app-layout>
