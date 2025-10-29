<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Executive Ticket Insight') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <div
                class="bg-light-eval-1 dark:bg-dark-eval-1 rounded-lg shadow-md border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow">
                        <h2 class="text-xl font-semibold mb-6">Download Data Tickets</h2>

                        <form id="exportForm" class="flex flex-wrap items-end gap-4">
                            <div class="flex flex-col w-1/3 min-w-[220px]">
                                <label for="start_date" class="text-sm font-medium mb-1">Tanggal Mulai</label>
                                <input type="date" id="start_date" name="start_date"
                                    class="border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200">
                            </div>

                            <div class="flex flex-col w-1/3 min-w-[220px]">
                                <label for="end_date" class="text-sm font-medium mb-1">Tanggal Akhir</label>
                                <input type="date" id="end_date" name="end_date"
                                    class="border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200">
                            </div>

                            <div class="flex items-center pt-5 gap-3">
                                <button type="button" id="previewBtn"
                                    class="px-6 py-2 bg-green-600 text-white rounded-lg shadow hover:bg-green-700 transition">
                                    <i class="fas fa-eye mr-2"></i> Preview
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>


            <!-- Modal Preview -->
            <div id="previewModal"
                class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm transition-all duration-300">
                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-11/12 max-w-5xl p-6 relative transform scale-95 transition-transform duration-300">
                    <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-100 mb-4">Preview Data Tickets</h3>

                    <div class="overflow-x-auto max-h-[60vh] border border-gray-200 dark:border-gray-700 rounded-lg">
                        <table class="min-w-full text-sm text-gray-700 dark:text-gray-300">
                            <thead class="bg-gray-100 dark:bg-gray-700 sticky top-0">
                                <tr>
                                    <th class="p-2 text-left">Ticket Code</th>
                                    <th class="p-2 text-left">Requestor</th>
                                    <th class="p-2 text-left">Support</th>
                                    <th class="p-2 text-left">Problem</th>
                                    <th class="p-2 text-left">Status</th>
                                    <th class="p-2 text-left">Start</th>
                                    <th class="p-2 text-left">End</th>
                                    <th class="p-2 text-left">Time (min)</th>
                                    <th class="p-2 text-left">Late</th>
                                    <th class="p-2 text-left">Created</th>
                                </tr>
                            </thead>
                            <tbody id="previewTableBody" class="divide-y divide-gray-200 dark:divide-gray-600">
                                <!-- Data isi lewat JS -->
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6 flex justify-end gap-3">
                        <button id="closePreview"
                            class="px-4 py-2 rounded-lg border border-gray-400 text-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700 transition">
                            Tutup
                        </button>
                        <button id="confirmDownload"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                            <i class="fas fa-download mr-2"></i> Download CSV
                        </button>
                    </div>
                </div>
            </div>

            {{-- BAR CHART - Tickets per Developer --}}
            <div
                class="bg-light-eval-1 dark:bg-dark-eval-1 rounded-lg shadow-md border border-gray-200 dark:border-gray-700">
                <div class="p-6 flex flex-col gap-6">
                    <h3 class="text-xl font-bold text-gray-800 dark:text-gray-200">
                        👨‍💻 Jumlah Ticket Selesai per Developer
                    </h3>

                    {{-- Filter Tahun --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Pilih Tahun
                            </label>
                            <select id="bar_year"
                                class="block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </select>
                        </div>
                        <div class="flex items-end gap-2">
                            <button id="filterBarBtn"
                                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-md w-full transition-colors">
                                FIlter
                            </button>
                            <button id="resetBarBtn"
                                class="bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded-md w-full transition-colors">
                                Reset
                            </button>
                        </div>
                    </div>
                </div>

                <div class="relative flex justify-center items-center min-h-[400px]">
                    <div id="barLoadingIndicator"
                        class="absolute inset-0 flex flex-col justify-center items-center bg-white/80 dark:bg-gray-900/70 rounded-lg hidden">
                        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
                        <p class="mt-4 text-gray-600 dark:text-gray-400">Memuat data...</p>
                    </div>
                    <div id="barNoDataMessage"
                        class="absolute inset-0 flex justify-center items-center text-gray-500 dark:text-gray-400 hidden">
                        Tidak ada data untuk ditampilkan
                    </div>
                    <div id="barChartContainer" class="w-full max-w-6xl">
                        <canvas id="myBarChart"></canvas>
                    </div>
                </div>
            </div>

            {{-- BAR CHART - Time Spent per Developer --}}
            <div
                class="bg-light-eval-1 dark:bg-dark-eval-1 rounded-lg shadow-md border border-gray-200 dark:border-gray-700">
                <div class="p-6 flex flex-col gap-6">
                    <h3 class="text-xl font-bold text-gray-800 dark:text-gray-200">
                        ⏱️ Total Time Spent per Developer
                    </h3>

                    {{-- Filter Tahun --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Pilih Tahun
                            </label>
                            <select id="time_year"
                                class="block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </select>
                        </div>
                        <div class="flex items-end gap-2">
                            <button id="filterTimeBtn"
                                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-md w-full transition-colors">
                                Filter
                            </button>
                            <button id="resetTimeBtn"
                                class="bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded-md w-full transition-colors">
                                Reset
                            </button>
                        </div>
                    </div>
                </div>

                <div class="relative flex justify-center items-center min-h-[400px]">
                    <div id="timeLoadingIndicator"
                        class="absolute inset-0 flex flex-col justify-center items-center bg-white/80 dark:bg-gray-900/70 rounded-lg hidden">
                        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
                        <p class="mt-4 text-gray-600 dark:text-gray-400">Memuat data...</p>
                    </div>
                    <div id="timeNoDataMessage"
                        class="absolute inset-0 flex justify-center items-center text-gray-500 dark:text-gray-400 hidden">
                        Tidak ada data untuk ditampilkan
                    </div>
                    <div id="timeChartContainer" class="w-full max-w-6xl">
                        <canvas id="timeSpentChart"></canvas>
                    </div>
                </div>
            </div>


            <div
                class="bg-light-eval-1 dark:bg-dark-eval-1 rounded-lg shadow-md border border-gray-200 dark:border-gray-700">
                <div class="p-6 flex flex-col gap-6">
                    <div class="p-6 bg-white rounded-lg shadow">
                        <h2 class="text-xl font-bold mb-4">Tickets by Support (Per Hari)</h2>

                        <div class="mb-4 flex gap-2">
                            <input type="date" id="date" class="border px-2 py-1 rounded">
                            <button id="filterBtn" class="bg-blue-600 text-white px-3 py-1 rounded">Filter</button>
                        </div>

                        <div id="ticketsContainer" class="grid grid-cols-1 md:grid-cols-4 gap-4"></div>
                    </div>


                </div>
            </div>

        </div>
    </div>

    {{-- Script --}}
    <script>
        document.getElementById('previewBtn').addEventListener('click', async function() {
            const start = document.getElementById('start_date').value;
            const end = document.getElementById('end_date').value;

            if (!start || !end) {
                alert('Isi tanggal mulai dan tanggal akhir terlebih dahulu.');
                return;
            }

            const url = `/api/tickets/preview?start_date=${start}&end_date=${end}`;
            const res = await fetch(url);
            const result = await res.json();

            if (!result.data || result.data.length === 0) {
                alert('Tidak ada data pada rentang tanggal tersebut.');
                return;
            }

            const tbody = document.getElementById('previewTableBody');
            tbody.innerHTML = '';
            result.data.forEach(t => {
                const row = `
                <tr>
                    <td class="p-2">${t.ticket_code}</td>
                    <td class="p-2">${t.requestor_name}</td>
                    <td class="p-2">${t.support_name}</td>
                    <td class="p-2">${t.problem}</td>
                    <td class="p-2">${t.status}</td>
                    <td class="p-2">${t.start_date}</td>
                    <td class="p-2">${t.end_date}</td>
                    <td class="p-2 text-center">${t.time_spent}</td>
                    <td class="p-2 text-center">${t.is_late}</td>
                    <td class="p-2">${t.created_at}</td>
                </tr>`;
                tbody.insertAdjacentHTML('beforeend', row);
            });

            const modal = document.getElementById('previewModal');
            modal.classList.remove('hidden');
            setTimeout(() => modal.querySelector('div').classList.replace('scale-95', 'scale-100'), 10);
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

    <script>
        document.getElementById('filterBtn').addEventListener('click', loadTickets);

        async function loadTickets() {
            const date = document.getElementById('date').value;
            const url = date ? `/api/tickets-by-support?date=${date}` : `/api/tickets-by-support`;
            const res = await fetch(url);
            const json = await res.json();
            const data = json.data;

            const supports = {
                ticketsAzi: 'Azi',
                ticketsApri: 'Apri',
                ticketsBayu: 'Bayu',
                ticketsFatih: 'Fatih',
            };

            let html = '';
            for (const key in supports) {
                const tickets = data[key];
                html += `
            <div class="border rounded-lg p-3 shadow-sm bg-gray-50">
                <h3 class="font-bold text-center text-blue-700 mb-3">${supports[key]}</h3>
                <div class="space-y-2">
                    ${
                        tickets.length
                            ? tickets.map(t => `
                                                            <div class="bg-white p-2 border rounded-md">
                                                                <p class="text-sm text-gray-700"><b>Kode:</b> ${t.ticket_code}</p>
                                                                <p class="text-sm text-gray-700"><b>Problem:</b> ${t.problem ?? '-'}</p>
                                                                <p class="text-sm text-gray-700"><b>Solution:</b> ${t.solution ?? '-'}</p>
                                                            </div>
                                                        `).join('')
                            : '<p class="text-center text-gray-400 italic">Tidak ada tiket</p>'
                    }
                </div>
            </div>
        `;
            }

            document.getElementById('ticketsContainer').innerHTML = html;
        }

        loadTickets(); // Load pertama kali
    </script>

    <script>
        let barChart, timeChart;

        document.addEventListener('DOMContentLoaded', function() {
            const currentYear = new Date().getFullYear();
            const barYear = document.getElementById('bar_year');
            const timeYear = document.getElementById('time_year');

            // isi dropdown tahun (misal 5 tahun terakhir)
            for (let y = currentYear; y >= currentYear - 5; y--) {
                barYear.innerHTML += `<option value="${y}">${y}</option>`;
                timeYear.innerHTML += `<option value="${y}">${y}</option>`;
            }

            const barFilter = document.getElementById('filterBarBtn');
            const barReset = document.getElementById('resetBarBtn');
            const timeFilter = document.getElementById('filterTimeBtn');
            const timeReset = document.getElementById('resetTimeBtn');

            const barLoading = document.getElementById('barLoadingIndicator');
            const barContainer = document.getElementById('barChartContainer');
            const barNoData = document.getElementById('barNoDataMessage');

            const timeLoading = document.getElementById('timeLoadingIndicator');
            const timeContainer = document.getElementById('timeChartContainer');
            const timeNoData = document.getElementById('timeNoDataMessage');

            loadBarChart(currentYear);
            loadTimeChart(currentYear);

            barFilter.addEventListener('click', () => loadBarChart(barYear.value));
            barReset.addEventListener('click', () => {
                barYear.value = currentYear;
                loadBarChart(currentYear);
            });

            timeFilter.addEventListener('click', () => loadTimeChart(timeYear.value));
            timeReset.addEventListener('click', () => {
                timeYear.value = currentYear;
                loadTimeChart(currentYear);
            });

            // Fungsi BAR CHART - Tickets per Dev
            function loadBarChart(year) {
                let apiUrl = `/api/chart-tickets-by-dev?year=${year}`;
                barLoading.classList.remove('hidden');
                barContainer.classList.add('hidden');
                barNoData.classList.add('hidden');
                if (barChart) barChart.destroy();

                fetch(apiUrl)
                    .then(res => res.json())
                    .then(res => {
                        barLoading.classList.add('hidden');
                        if (res.success && res.data.datasets.length > 0) {
                            barContainer.classList.remove('hidden');
                            const ctx = document.getElementById('myBarChart').getContext('2d');
                            barChart = window.initBarChart(ctx, res.data.labels, res.data.datasets);
                        } else {
                            barNoData.classList.remove('hidden');
                        }
                    })
                    .catch(() => {
                        barLoading.classList.add('hidden');
                        barNoData.classList.remove('hidden');
                    });
            }

            // Fungsi BAR CHART - Time Spent per Dev
            function loadTimeChart(year) {
                let apiUrl = `/api/chart-time-spent-by-dev?year=${year}`;
                timeLoading.classList.remove('hidden');
                timeContainer.classList.add('hidden');
                timeNoData.classList.add('hidden');
                if (timeChart) timeChart.destroy();

                fetch(apiUrl)
                    .then(res => res.json())
                    .then(res => {
                        timeLoading.classList.add('hidden');
                        if (res.success && res.data.datasets.length > 0) {
                            timeContainer.classList.remove('hidden');
                            const ctx = document.getElementById('timeSpentChart').getContext('2d');
                            timeChart = window.initBarChart(ctx, res.data.labels, res.data.datasets);
                        } else {
                            timeNoData.classList.remove('hidden');
                        }
                    })
                    .catch(() => {
                        timeLoading.classList.add('hidden');
                        timeNoData.classList.remove('hidden');
                    });
            }
        });
    </script>

</x-app-layout>
