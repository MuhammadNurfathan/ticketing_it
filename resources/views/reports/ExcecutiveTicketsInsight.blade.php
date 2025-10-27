<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Executive Ticket Insight') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- LINE CHART SECTION --}}
            <div
                class="bg-light-eval-1 dark:bg-dark-eval-1 rounded-lg shadow-md border border-gray-200 dark:border-gray-700">
                <div class="p-6 flex flex-col gap-6">

                    {{-- Title --}}
                    <h3 class="text-xl font-bold text-gray-800 dark:text-gray-200 mb-6">
                        📈 Ticket Selesai Per Bulan
                    </h3>



                    {{-- Custom Year Input --}}
                    <div class="flex flex-col md:flex-row items-center gap-3 w-full max-w-md">
                        {{-- Label + Input --}}
                        <div class="flex flex-col md:flex-row items-center gap-2 w-full md:w-1/3">
                            <label for="line_year" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                Pilih Tahun
                            </label>
                            <input type="number" id="line_year" placeholder="2025" min="2020" max="2100"
                                value="2025"
                                class="block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        {{-- Buttons --}}
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

                {{-- Chart Section --}}
                <div class="relative flex justify-center items-center min-h-[400px]">

                    {{-- Loading --}}
                    <div id="lineLoadingIndicator"
                        class="absolute inset-0 flex flex-col justify-center items-center bg-white/80 dark:bg-gray-900/70 rounded-lg hidden">
                        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
                        <p class="mt-4 text-gray-600 dark:text-gray-400">Memuat data...</p>
                    </div>

                    {{-- No Data --}}
                    <div id="lineNoDataMessage"
                        class="absolute inset-0 flex justify-center items-center text-gray-500 dark:text-gray-400 hidden">
                        Tidak ada data untuk ditampilkan
                    </div>

                    {{-- Chart Canvas --}}
                    <div id="lineChartContainer" class="w-full max-w-6xl">
                        <canvas id="ticketsDoneChart"></canvas>
                    </div>
                </div>

            </div>

            {{-- PIE CHART SECTION --}}
            <div
                class="bg-light-eval-1 dark:bg-dark-eval-1 rounded-lg shadow-md border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <h3 class="text-xl font-bold mb-6 text-gray-800 dark:text-gray-200">
                        📊 Distribusi Ticket Berdasarkan Kategori
                    </h3>

                    {{-- Filter Pie Chart --}}
                    <div class="mb-6">
                        <h4 class="text-sm font-semibold mb-3 text-gray-700 dark:text-gray-300">Filter Pie Chart</h4>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Tanggal Mulai
                                </label>
                                <input type="date" id="pie_start_date"
                                    class="block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Tanggal Akhir
                                </label>
                                <input type="date" id="pie_end_date"
                                    class="block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div class="flex items-end gap-2">
                                <button id="filterPieBtn"
                                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-md w-full transition-colors">
                                    Filter
                                </button>
                                <button id="resetPieBtn"
                                    class="bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded-md w-full transition-colors">
                                    Reset
                                </button>
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

                <div class="p-6 flex flex-col gap-6">
                    <div class="stats-cards flex gap-4">
                        <div class="card p-4 bg-white dark:bg-dark-eval-3 rounded-lg shadow w-full">
                            <h4 class="font-semibold text-gray-700 dark:text-gray-300">Full Resolution Time</h4>
                            <p id="fullResolutionTime" class="text-xl font-bold text-gray-900 dark:text-gray-100">-</p>
                        </div>
                        <div class="card p-4 bg-white  dark:bg-dark-eval-3 rounded-lg shadow w-full">
                            <h4 class="font-semibold text-gray-700 dark:text-gray-300">Average Resolution Time</h4>
                            <p id="avgResolutionTime" class="text-xl font-bold text-gray-900 dark:text-gray-100">-</p>
                        </div>
                        <div class="card p-4 bg-white dark:bg-dark-eval-3 rounded-lg shadow w-full">
                            <h4 class="font-semibold text-gray-700 dark:text-gray-300">SLA Percentage</h4>
                            <p id="slaPercentage" class="text-xl font-bold text-gray-900 dark:text-gray-100">-</p>
                        </div>
                    </div>
                </div>
            </div>
        
        <div
            class="bg-light-eval-1 dark:bg-dark-eval-1 rounded-lg shadow-md border border-gray-200 dark:border-gray-700">
            <div class="p-6">
                <div class="chart-container" style="max-width: 600px;">
                    <canvas id="myBarChart"></canvas>
                </div>
            </div>
        </div>
        <div
            class="bg-light-eval-1 dark:bg-dark-eval-1 rounded-lg shadow-md border border-gray-200 dark:border-gray-700">
            <div class="p-6">
                <div class="chart-container" style="max-width: 600px;">
                    <canvas id="timeSpentChart"></canvas>
                </div>
            </div>
        </div>
        </div>
    </div>

<canvas id="myBarChart"></canvas>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('myBarChart').getContext('2d');

    fetch('/api/chart-tickets-by-Dev')
      .then(res => res.json())
    .then(res => {
        const ctx = document.getElementById('myBarChart').getContext('2d');
        window.initBarChart(ctx, res.labels, res.datasets);
    });
});
</script>



<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('timeSpentChart').getContext('2d');

    fetch('/api/chart-time-spent-by-dev')
        .then(res => res.json())
        .then(res => {
            const ctx = document.getElementById('timeSpentChart').getContext('2d');
            window.initBarChart(ctx, res.labels, res.datasets);
        });
});
</script>






    @vite(['resources/js/app.js'])


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            fetch('/api/tickets/statistik') // endpoint API
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('fullResolutionTime').innerText = data.data.fullResolutionTime +
                            ' jam';
                        document.getElementById('avgResolutionTime').innerText = data.data.avgResolutionTime +
                            ' jam';
                        document.getElementById('slaPercentage').innerText = data.data.slaPercentage + ' %';
                    } else {
                        console.error('API Error', data);
                    }
                })
                .catch(err => console.error('Fetch Error:', err));
        });
    </script>
    <script>
        let pieChart;
        let lineChart;

        document.addEventListener('DOMContentLoaded', function() {
            const pieStartDate = document.getElementById('pie_start_date');
            const pieEndDate = document.getElementById('pie_end_date');
            const filterPieBtn = document.getElementById('filterPieBtn');
            const resetPieBtn = document.getElementById('resetPieBtn');
            const pieLoading = document.getElementById('pieLoadingIndicator');
            const pieContainer = document.getElementById('pieChartContainer');
            const pieNoData = document.getElementById('pieNoDataMessage');

            const lineYear = document.getElementById('line_year');
            const filterLineBtn = document.getElementById('filterLineBtn');
            const resetLineBtn = document.getElementById('resetLineBtn');
            const lineLoading = document.getElementById('lineLoadingIndicator');
            const lineContainer = document.getElementById('lineChartContainer');
            const lineNoData = document.getElementById('lineNoDataMessage');

            const statsFull = document.getElementById('fullResolutionTime');
            const statsAvg = document.getElementById('avgResolutionTime');
            const statsSLA = document.getElementById('slaPercentage');

            // Default Pie Chart 30 hari
            const today = new Date();
            const thirtyDaysAgo = new Date(today);
            thirtyDaysAgo.setDate(today.getDate() - 30);
            pieEndDate.valueAsDate = today;
            pieStartDate.valueAsDate = thirtyDaysAgo;

            loadPieChart();
            loadLineChart();
            loadStats();

            // ---------------- PIE CHART ----------------
            filterPieBtn.addEventListener('click', () => {
                loadPieChart();
                loadStats();
            });

            resetPieBtn.addEventListener('click', () => {
                pieStartDate.value = '';
                pieEndDate.value = '';
                loadPieChart();
                loadStats();
            });

            function loadPieChart() {
                const startDate = pieStartDate.value;
                const endDate = pieEndDate.value;

                if (startDate && endDate && startDate > endDate) {
                    alert('Tanggal mulai tidak boleh lebih besar dari tanggal akhir!');
                    return;
                }

                let apiUrl = '/api/reports/tickets-by-category';
                const params = new URLSearchParams();
                if (startDate) params.append('start_date', startDate);
                if (endDate) params.append('end_date', endDate);
                if (params.toString()) apiUrl += '?' + params.toString();

                pieLoading.classList.remove('hidden');
                pieContainer.classList.add('hidden');
                pieNoData.classList.add('hidden');

                if (pieChart) pieChart.destroy();

                fetch(apiUrl)
                    .then(res => res.json())
                    .then(res => {
                        pieLoading.classList.add('hidden');
                        if (res.data && res.data.length > 0) {
                            pieContainer.classList.remove('hidden');
                            pieChart = new window.PieChart('ticketCategoryChart', {
                                title: 'Distribusi Ticket Berdasarkan Kategori',
                                labelKey: 'category',
                                valueKey: 'total'
                            });
                            pieChart.render(res.data);
                        } else {
                            pieNoData.classList.remove('hidden');
                        }
                    })
                    .catch(() => {
                        pieLoading.classList.add('hidden');
                        pieNoData.classList.remove('hidden');
                    });
            }

            // ---------------- LINE CHART ----------------
            filterLineBtn.addEventListener('click', loadLineChart);
            resetLineBtn.addEventListener('click', () => {
                lineYear.value = new Date().getFullYear();
                loadLineChart();
            });

            function loadLineChart() {
                const year = lineYear.value || new Date().getFullYear();
                const apiUrl = `/api/reports/tickets-done-per-month?year=${year}`;

                lineLoading.classList.remove('hidden');
                lineContainer.classList.add('hidden');
                lineNoData.classList.add('hidden');

                if (lineChart) lineChart.destroy();

                fetch(apiUrl)
                    .then(res => res.json())
                    .then(res => {
                        lineLoading.classList.add('hidden');
                        if (res.data && res.data.length > 0) {
                            lineContainer.classList.remove('hidden');
                            lineChart = new window.LineChart('ticketsDoneChart', {
                                title: `Ticket Selesai Per Bulan - ${year}`,
                                datasetLabel: 'Ticket Selesai',
                                labelKey: 'month',
                                valueKey: 'total'
                            });
                            lineChart.render(res.data);
                        } else {
                            lineNoData.classList.remove('hidden');
                        }
                    })
                    .catch(() => {
                        lineLoading.classList.add('hidden');
                        lineNoData.classList.remove('hidden');
                    });
            }

            // ---------------- STATS ----------------
            function loadStats() {
                let apiUrl = '/api/tickets/statistik';
                const params = new URLSearchParams();
                if (pieStartDate.value) params.append('start_date', pieStartDate.value);
                if (pieEndDate.value) params.append('end_date', pieEndDate.value);
                if (params.toString()) apiUrl += '?' + params.toString();

                fetch(apiUrl)
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            statsFull.innerText = data.data.fullResolutionTime + ' jam';
                            statsAvg.innerText = data.data.avgResolutionTime + ' jam';
                            statsSLA.innerText = data.data.slaPercentage + ' %';
                        } else {
                            statsFull.innerText = '-';
                            statsAvg.innerText = '-';
                            statsSLA.innerText = '-';
                        }
                    })
                    .catch(() => {
                        statsFull.innerText = '-';
                        statsAvg.innerText = '-';
                        statsSLA.innerText = '-';
                    });
            }
        });
    </script>

</x-app-layout>
