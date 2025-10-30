<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Executive Ticket Insight') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- LINE CHART SECTION --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md border border-gray-200 dark:border-gray-700 transition-colors">
                <div class="p-6 flex flex-col gap-6">

                    {{-- Title --}}
                    <h3 class="text-xl font-bold text-gray-800 dark:text-gray-200 mb-6">
                        📈 Ticket Selesai Per Bulan
                    </h3>

                    {{-- Year Filter --}}
                    <div class="flex flex-col md:flex-row items-center gap-3 w-full max-w-md">
                        <div class="flex flex-col md:flex-row items-center gap-2 w-full md:w-1/3">
                            <label for="line_year" class="text-sm font-medium text-gray-700 dark:text-gray-300">Pilih Tahun</label>
                            <input type="number" id="line_year" placeholder="2025" min="2020" max="2100"
                                value="2025"
                                class="block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors">
                        </div>
                        <div class="flex gap-2 w-full md:w-2/3">
                            <button id="filterLineBtn"
                                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-md w-full transition-colors">
                                Filter
                            </button>
                            <button id="resetLineBtn"
                                class="bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded-md w-full transition-colors">
                                Reset
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Chart Container --}}
                <div class="relative flex justify-center items-center min-h-[400px]">
                    <div id="lineLoadingIndicator" class="absolute inset-0 flex flex-col justify-center items-center bg-white/80 dark:bg-gray-900/70 rounded-lg hidden transition-colors">
                        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
                        <p class="mt-4 text-gray-600 dark:text-gray-400">Memuat data...</p>
                    </div>

                    <div id="lineNoDataMessage" class="absolute inset-0 flex justify-center items-center text-gray-500 dark:text-gray-400 hidden">
                        Tidak ada data untuk ditampilkan
                    </div>

                    <div id="lineChartContainer" class="w-full max-w-6xl">
                        <canvas id="ticketsDoneChart"></canvas>
                    </div>
                </div>
            </div>

            {{-- PIE CHART SECTION --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md border border-gray-200 dark:border-gray-700 transition-colors">
                <div class="p-6">
                    <h3 class="text-xl font-bold mb-6 text-gray-800 dark:text-gray-200">📊 Distribusi Ticket Berdasarkan Kategori</h3>

                    {{-- Pie Chart Filter --}}
                    <div class="mb-6">
                        <h4 class="text-sm font-semibold mb-3 text-gray-700 dark:text-gray-300">Filter Pie Chart</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tanggal Mulai</label>
                                <input type="date" id="pie_start_date" 
                                    class="block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tanggal Akhir</label>
                                <input type="date" id="pie_end_date" 
                                    class="block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors">
                            </div>
                            <div class="flex items-end gap-2">
                                <button id="filterPieBtn" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-md w-full transition-colors">Filter</button>
                                <button id="resetPieBtn" class="bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded-md w-full transition-colors">Reset</button>
                            </div>
                        </div>
                    </div>

                    {{-- Pie Chart Canvas --}}
                    <div id="pieLoadingIndicator" class="text-center py-12 hidden">
                        <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
                        <p class="mt-4 text-gray-600 dark:text-gray-400">Memuat data...</p>
                    </div>

                    <div id="pieChartContainer" class="flex justify-center items-center" style="min-height: 400px;">
                        <div style="max-width: 600px; width: 100%;">
                            <canvas id="ticketCategoryChart"></canvas>
                        </div>
                    </div>

                    <div id="pieNoDataMessage" class="text-center py-12 hidden">
                        <p class="text-gray-500 dark:text-gray-400">Tidak ada data untuk ditampilkan</p>
                    </div>
                </div>

                {{-- Stats --}}
                <div class="p-6 flex flex-col gap-6">
                    <div class="stats-cards flex gap-4">
                        <div class="card p-4 bg-white dark:bg-gray-700 rounded-lg shadow w-full transition-colors">
                            <h4 class="font-semibold text-gray-700 dark:text-gray-300">Full Resolution Time</h4>
                            <p id="fullResolutionTime" class="text-xl font-bold text-gray-900 dark:text-gray-100">-</p>
                        </div>
                        <div class="card p-4 bg-white dark:bg-gray-700 rounded-lg shadow w-full transition-colors">
                            <h4 class="font-semibold text-gray-700 dark:text-gray-300">Average Resolution Time</h4>
                            <p id="avgResolutionTime" class="text-xl font-bold text-gray-900 dark:text-gray-100">-</p>
                        </div>
                        <div class="card p-4 bg-white dark:bg-gray-700 rounded-lg shadow w-full transition-colors">
                            <h4 class="font-semibold text-gray-700 dark:text-gray-300">SLA Percentage</h4>
                            <p id="slaPercentage" class="text-xl font-bold text-gray-900 dark:text-gray-100">-</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @vite(['resources/js/app.js'])

    <script>
        // ---------------- STATS ----------------
        async function loadStats(startDate = null, endDate = null) {
            let apiUrl = '/api/tickets/statistik';
            const params = new URLSearchParams();
            if(startDate) params.append('start_date', startDate);
            if(endDate) params.append('end_date', endDate);
            if(params.toString()) apiUrl += '?' + params.toString();

            try {
                const res = await fetch(apiUrl);
                const data = await res.json();
                document.getElementById('fullResolutionTime').innerText = data.success ? data.data.fullResolutionTime + ' jam' : '-';
                document.getElementById('avgResolutionTime').innerText = data.success ? data.data.avgResolutionTime + ' jam' : '-';
                document.getElementById('slaPercentage').innerText = data.success ? data.data.slaPercentage + ' %' : '-';
            } catch {
                document.getElementById('fullResolutionTime').innerText = '-';
                document.getElementById('avgResolutionTime').innerText = '-';
                document.getElementById('slaPercentage').innerText = '-';
            }
        }

        // ---------------- PIE & LINE CHART ----------------
        document.addEventListener('DOMContentLoaded', function() {
            const pieStart = document.getElementById('pie_start_date');
            const pieEnd = document.getElementById('pie_end_date');

            // Default 30 hari
            const today = new Date();
            const thirtyDaysAgo = new Date(today);
            thirtyDaysAgo.setDate(today.getDate() - 30);
            pieEnd.valueAsDate = today;
            pieStart.valueAsDate = thirtyDaysAgo;

            loadPieChart();
            loadLineChart();
            loadStats(pieStart.value, pieEnd.value);

            document.getElementById('filterPieBtn').addEventListener('click', () => {
                loadPieChart();
                loadStats(pieStart.value, pieEnd.value);
            });
            document.getElementById('resetPieBtn').addEventListener('click', () => {
                pieStart.valueAsDate = thirtyDaysAgo;
                pieEnd.valueAsDate = today;
                loadPieChart();
                loadStats(pieStart.value, pieEnd.value);
            });

            document.getElementById('filterLineBtn').addEventListener('click', loadLineChart);
            document.getElementById('resetLineBtn').addEventListener('click', () => {
                document.getElementById('line_year').value = new Date().getFullYear();
                loadLineChart();
            });
        });

        let pieChart, lineChart;

        async function loadPieChart() {
            const start = document.getElementById('pie_start_date').value;
            const end = document.getElementById('pie_end_date').value;
            const container = document.getElementById('pieChartContainer');
            const noData = document.getElementById('pieNoDataMessage');
            const loading = document.getElementById('pieLoadingIndicator');

            if(start && end && start > end) { alert('Tanggal mulai tidak boleh lebih besar dari tanggal akhir!'); return; }

            loading.classList.remove('hidden');
            container.classList.add('hidden');
            noData.classList.add('hidden');
            if(pieChart) pieChart.destroy();

            let url = '/api/reports/tickets-by-category';
            const params = new URLSearchParams();
            if(start) params.append('start_date', start);
            if(end) params.append('end_date', end);
            if(params.toString()) url += '?' + params.toString();

            try {
                const res = await fetch(url);
                const data = await res.json();
                loading.classList.add('hidden');
                if(data.data && data.data.length) {
                    container.classList.remove('hidden');
                    pieChart = new window.PieChart('ticketCategoryChart', {
                        title: 'Distribusi Ticket Berdasarkan Kategori',
                        labelKey: 'category',
                        valueKey: 'total',
                        darkMode: document.documentElement.classList.contains('dark')
                    });
                    pieChart.render(data.data);
                } else noData.classList.remove('hidden');
            } catch {
                loading.classList.add('hidden');
                noData.classList.remove('hidden');
            }
        }

        async function loadLineChart() {
            const year = document.getElementById('line_year').value || new Date().getFullYear();
            const container = document.getElementById('lineChartContainer');
            const noData = document.getElementById('lineNoDataMessage');
            const loading = document.getElementById('lineLoadingIndicator');

            loading.classList.remove('hidden');
            container.classList.add('hidden');
            noData.classList.add('hidden');
            if(lineChart) lineChart.destroy();

            try {
                const res = await fetch(`/api/reports/tickets-done-per-month?year=${year}`);
                const data = await res.json();
                loading.classList.add('hidden');
                if(data.data && data.data.length) {
                    container.classList.remove('hidden');
                    lineChart = new window.LineChart('ticketsDoneChart', {
                        title: `Ticket Selesai Per Bulan - ${year}`,
                        datasetLabel: 'Ticket Selesai',
                        labelKey: 'month',
                        valueKey: 'total',
                        darkMode: document.documentElement.classList.contains('dark')
                    });
                    lineChart.render(data.data);
                } else noData.classList.remove('hidden');
            } catch {
                loading.classList.add('hidden');
                noData.classList.remove('hidden');
            }
        }
    </script>
</x-app-layout>
