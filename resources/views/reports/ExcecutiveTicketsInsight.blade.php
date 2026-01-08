<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 dark:text-white">
            {{ __('Executive Ticket Insight') }}
        </h2>
    </x-slot>

    <div class="min-h-screen bg-light-bg dark:bg-dark-bg">
        <div class="w-full px-4 sm:px-6 lg:px-8 py-6 space-y-6">

            {{-- LINE CHART SECTION --}}
            <div
                class="bg-white dark:bg-dark-eval-1 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="p-4 sm:p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                        Tickets Completed this year
                    </h3>

                    {{-- Year Filter --}}
                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3 mb-6">
                        <div class="flex items-center gap-2 w-full sm:w-auto">
                            <label for="line_year"
                                class="text-sm font-medium text-gray-700 dark:text-gray-300 whitespace-nowrap">
                                Select Year
                            </label>
                            <input type="number" id="line_year" placeholder="2025" min="2020" max="2100"
                                class="block w-full sm:w-32 rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-dark-eval-2 text-gray-900 dark:text-white shadow-sm focus:border-gray-400 focus:ring-gray-400 dark:focus:ring-gray-500 text-sm px-3 py-2">
                        </div>
                        <div class="flex gap-2 w-full sm:w-auto">
                            <button id="filterLineBtn"
                                class="bg-gray-900 dark:bg-gray-100 hover:bg-gray-800 dark:hover:bg-gray-200 text-white dark:text-gray-900 font-medium py-2 px-4 rounded-lg flex-1 sm:flex-none text-sm transition-colors">
                                Filter
                            </button>
                            <button id="resetLineBtn"
                                class="bg-gray-500 dark:bg-gray-600 hover:bg-gray-600 dark:hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-lg flex-1 sm:flex-none text-sm transition-colors">
                                Reset
                            </button>
                        </div>
                    </div>

                    {{-- Chart Container --}}
                    <div class="relative flex justify-center items-center min-h-[400px]">
                        <div id="lineLoadingIndicator"
                            class="absolute inset-0 flex flex-col justify-center items-center bg-white/90 dark:bg-dark-eval-1/90 rounded-lg hidden z-10">
                            <div
                                class="animate-spin rounded-full h-12 w-12 border-b-2 border-gray-900 dark:border-gray-100">
                            </div>
                            <p class="mt-4 text-gray-600 dark:text-gray-400">Load Data...</p>
                        </div>

                        <div id="lineNoDataMessage"
                            class="absolute inset-0 flex justify-center items-center text-gray-500 dark:text-gray-400 hidden z-10">
                            No Data Available
                        </div>

                        <div id="lineChartContainer" class="w-full p-4">
                            <canvas id="ticketsDoneChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            {{-- PIE CHART SECTION --}}
            <div
                class="bg-white dark:bg-dark-eval-1 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="p-4 sm:p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                        Ticket Distribution Based on Category
                    </h3>

                    {{-- Pie Chart Filter --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Start Date
                            </label>
                            <input type="date" id="pie_start_date"
                                class="block w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-dark-eval-2 text-gray-900 dark:text-white shadow-sm focus:border-gray-400 focus:ring-gray-400 dark:focus:ring-gray-500 text-sm px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                End Date
                            </label>
                            <input type="date" id="pie_end_date"
                                class="block w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-dark-eval-2 text-gray-900 dark:text-white shadow-sm focus:border-gray-400 focus:ring-gray-400 dark:focus:ring-gray-500 text-sm px-3 py-2">
                        </div>
                        <div class="flex items-end">
                            <button id="filterPieBtn"
                                class="bg-gray-900 dark:bg-gray-100 hover:bg-gray-800 dark:hover:bg-gray-200 text-white dark:text-gray-900 font-medium py-2 px-4 rounded-lg w-full text-sm transition-colors">
                                Filter
                            </button>
                        </div>
                        <div class="flex items-end">
                            <button id="resetPieBtn"
                                class="bg-gray-500 dark:bg-gray-600 hover:bg-gray-600 dark:hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-lg w-full text-sm transition-colors">
                                Reset
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Pie Chart Canvas --}}
                <div class="p-4 sm:p-6">
                    <div id="pieLoadingIndicator" class="text-center py-12 hidden">
                        <div
                            class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-gray-900 dark:border-gray-100">
                        </div>
                        <p class="mt-4 text-gray-600 dark:text-gray-400">Load Data...</p>
                    </div>

                    <div id="pieChartContainer" class="flex justify-center items-center" style="min-height: 400px;">
                        <div style="max-width: 600px; width: 100%;">
                            <canvas id="ticketCategoryChart"></canvas>
                        </div>
                    </div>

                    <div id="pieNoDataMessage" class="text-center py-12 hidden">
                        <p class="text-gray-500 dark:text-gray-400">No Data Available</p>
                    </div>
                </div>

                {{-- BAR CHART - Time Spent per Department --}}
                <div
                    class="bg-white dark:bg-dark-eval-1 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="p-4 sm:p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">
                            Total Time Spent per Department
                        </h3>

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 mb-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Pilih Tahun
                                </label>
                                <select id="dept_time_year"
                                    class="block w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-dark-eval-2 text-gray-900 dark:text-white shadow-sm focus:ring-2 focus:ring-gray-400 text-sm px-3 py-2">
                                </select>
                            </div>

                            <div class="flex items-end">
                                <button id="filterDeptTimeBtn"
                                    class="w-full bg-gray-900 dark:bg-gray-100 hover:bg-gray-800 text-white dark:text-gray-900 font-medium py-2 px-4 rounded-lg text-sm">
                                    Filter
                                </button>
                            </div>

                            <div class="flex items-end">
                                <button id="resetDeptTimeBtn"
                                    class="w-full bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-lg text-sm">
                                    Reset
                                </button>
                            </div>
                        </div>

                        <div class="relative flex justify-center items-center min-h-[400px]">
                            <div id="deptTimeLoading"
                                class="absolute inset-0 flex flex-col justify-center items-center bg-white/90 rounded-lg hidden z-10">
                                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-gray-900"></div>
                                <p class="mt-4 text-gray-600">Load Data...</p>
                            </div>

                            <div id="deptTimeNoData"
                                class="absolute inset-0 flex justify-center items-center text-gray-500 hidden z-10">
                                No Data Available
                            </div>

                            <div id="deptTimeChartContainer" class="w-full p-4">
                                <canvas id="timeSpentDepartmentChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>


                {{-- Stats --}}
                <div class="p-4 sm:p-6 border-t border-gray-200 dark:border-gray-700">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div
                            class="bg-gray-50 dark:bg-dark-eval-2 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
                            <h4
                                class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-2">
                                Full Resolution Time</h4>
                            <p id="fullResolutionTime" class="text-2xl font-bold text-gray-900 dark:text-white">-</p>
                        </div>
                        <div
                            class="bg-gray-50 dark:bg-dark-eval-2 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
                            <h4
                                class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-2">
                                Average Resolution Time</h4>
                            <p id="avgResolutionTime" class="text-2xl font-bold text-gray-900 dark:text-white">-</p>
                        </div>
                        <div
                            class="bg-gray-50 dark:bg-dark-eval-2 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
                            <h4
                                class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-2">
                                SLA Percentage</h4>
                            <p id="slaPercentage" class="text-2xl font-bold text-gray-900 dark:text-white">-</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>


    <script>
        let deptTimeChart = null;

        async function loadDeptTimeChart(year) {
            const canvas = document.getElementById('timeSpentDepartmentChart');
            const container = document.getElementById('deptTimeChartContainer');
            const loading = document.getElementById('deptTimeLoading');
            const noData = document.getElementById('deptTimeNoData');

            loading.classList.remove('hidden');
            container.classList.add('hidden');
            noData.classList.add('hidden');

            if (deptTimeChart) {
                deptTimeChart.destroy();
                deptTimeChart = null;
            }

            try {
                const res = await fetch(`/api/chart-time-spent-by-department?year=${year}`);
                const json = await res.json();

                loading.classList.add('hidden');

                // API returns { success, data: { labels: [...], datasets: [...] } }
                if (!json.data || !json.data.datasets || json.data.datasets.length === 0) {
                    noData.classList.remove('hidden');
                    return;
                }

                // Create datasets - one per department with monthly data
                const datasets = [];
                const colors = generateColors(json.data.datasets.length);
                const monthLabels = json.data.labels; // ['Jan', 'Feb', ..., 'Des']

                json.data.datasets.forEach((ds, index) => {
                    const total = (ds.data && ds.data.length > 0) 
                        ? ds.data.reduce((sum, val) => sum + (val || 0), 0) 
                        : 0;
                    
                    if (total > 0) {
                        datasets.push({
                            label: ds.label,
                            data: ds.data || [],
                            backgroundColor: colors[index],
                            borderColor: colors[index],
                            borderWidth: 1,
                            tension: 0.1
                        });
                    }
                });

                // 🚨 PENTING: cek apakah semua nilai = 0
                if (datasets.length === 0) {
                    noData.classList.remove('hidden');
                    return;
                }

                container.classList.remove('hidden');

                deptTimeChart = new Chart(canvas, {
                    type: 'bar',
                    data: {
                        labels: monthLabels,
                        datasets: datasets
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: value => {
                                        const h = Math.floor(value / 60);
                                        const m = value % 60;
                                        return `${h}j ${m}m`;
                                    }
                                }
                            },
                            x: {
                                ticks: {
                                    color: getTextColor()
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    color: getTextColor(),
                                    font: {
                                        size: 12
                                    },
                                    padding: 15,
                                    usePointStyle: true
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: ctx => {
                                        const m = ctx.raw;
                                        const h = Math.floor(m / 60);
                                        const mm = m % 60;
                                        return `${ctx.dataset.label}: ${h} jam ${mm} menit`;
                                    }
                                }
                            }
                        }
                    }
                });

            } catch (err) {
                console.error(err);
                loading.classList.add('hidden');
                noData.classList.remove('hidden');
            }
        }
    </script>
    <script>
        const NEUTRAL_COLOR = '#6B7280';

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

        let pieChart, lineChart;

        async function loadStats(startDate = null, endDate = null) {
            const fullEl = document.getElementById('fullResolutionTime');
            const avgEl = document.getElementById('avgResolutionTime');
            const slaEl = document.getElementById('slaPercentage');
            let url = '/api/tickets/statistik';

            const params = new URLSearchParams();
            if (startDate) params.append('start_date', startDate);
            if (endDate) params.append('end_date', endDate);
            if (params.toString()) url += '?' + params.toString();

            try {
                const res = await fetch(url);
                const data = await res.json();

                fullEl.innerText = data.success ? data.data.fullResolutionTime : '-';
                avgEl.innerText = data.success ? data.data.avgResolutionTime : '-';
                slaEl.innerText = data.success ? data.data.slaPercentage + '%' : '-';
            } catch (err) {
                console.error(err);
                fullEl.innerText = '-';
                avgEl.innerText = '-';
                slaEl.innerText = '-';
            }
        }

        function generateColors(count) {
            const colors = [
                '#3b82f6', '#ef4444', '#10b981', '#f59e0b',
                '#8b5cf6', '#ec4899', '#06b6d4', '#84cc16',
                '#f97316', '#6366f1'
            ];
            return Array.from({
                length: count
            }, (_, i) => colors[i % colors.length]);
        }

        // =========================
        //        PIE CHART
        // =========================
        async function loadPieChart() {
            const start = document.getElementById('pie_start_date').value;
            const end = document.getElementById('pie_end_date').value;

            const container = document.getElementById('pieChartContainer');
            const noData = document.getElementById('pieNoDataMessage');
            const loading = document.getElementById('pieLoadingIndicator');
            const canvas = document.getElementById('ticketCategoryChart');

            if (start && end && start > end) {
                alert('Tanggal mulai tidak boleh lebih besar dari tanggal akhir!');
                return;
            }

            loading.classList.remove('hidden');
            container.classList.add('hidden');
            noData.classList.add('hidden');

            if (pieChart) pieChart.destroy();

            let url = '/api/reports/tickets-by-category';
            const params = new URLSearchParams();
            if (start) params.append('start_date', start);
            if (end) params.append('end_date', end);
            if (params.toString()) url += '?' + params.toString();

            try {
                const res = await fetch(url);
                const data = await res.json();

                loading.classList.add('hidden');

                if (data.data && data.data.length > 0) {
                    container.classList.remove('hidden');

                    const labels = data.data.map(i => i.category || 'Unknown');
                    const values = data.data.map(i => i.total || 0);
                    const colors = generateColors(data.data.length);

                    pieChart = new Chart(canvas, {
                        type: 'pie',
                        data: {
                            labels,
                            datasets: [{
                                data: values,
                                backgroundColor: colors,
                                borderColor: getBgColor(),
                                borderWidth: 2
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: {
                                        color: getTextColor(),
                                        font: {
                                            size: 12
                                        },
                                        padding: 15
                                    }
                                }
                            }
                        }
                    });

                } else {
                    noData.classList.remove('hidden');
                }

            } catch (err) {
                console.error(err);
                loading.classList.add('hidden');
                noData.classList.remove('hidden');
            }
        }

        // =========================
        //        LINE CHART
        // =========================
        async function loadLineChart() {
            const year = document.getElementById('line_year').value || new Date().getFullYear();

            const container = document.getElementById('lineChartContainer');
            const noData = document.getElementById('lineNoDataMessage');
            const loading = document.getElementById('lineLoadingIndicator');
            const canvas = document.getElementById('ticketsDoneChart');

            loading.classList.remove('hidden');
            container.classList.add('hidden');
            noData.classList.add('hidden');

            if (lineChart) lineChart.destroy();

            try {
                const res = await fetch(`/api/reports/tickets-done-per-month?year=${year}`);
                const data = await res.json();

                loading.classList.add('hidden');

                if (data.data && data.data.length > 0) {

                    const labels = data.data.map(i => i.month || '');
                    const values = data.data.map(i => i.total || 0);

                    // 🚨 NEW: jika semua total = 0 → jangan tampilkan chart
                    const allZero = values.every(v => v === 0);
                    if (allZero) {
                        container.classList.add('hidden');
                        noData.classList.remove('hidden');
                        return;
                    }

                    noData.classList.add('hidden');
                    container.classList.remove('hidden');

                    lineChart = new Chart(canvas, {
                        type: 'line',
                        data: {
                            labels,
                            datasets: [{
                                label: 'Ticket Selesai',
                                data: values,
                                borderWidth: 2,
                                tension: 0.4,
                                fill: true,
                                borderColor: isDarkMode() ? '#3b82f6' : '#60a5fa',
                                backgroundColor: isDarkMode() ?
                                    'rgba(59,130,246,0.15)' :
                                    'rgba(96,165,250,0.15)',
                                pointRadius: 4,
                                pointBackgroundColor: isDarkMode() ? '#3b82f6' : '#60a5fa',
                                pointBorderColor: getBgColor(),
                                pointBorderWidth: 2
                            }]
                        },
                        options: {
                            responsive: true,
                            scales: {
                                x: {
                                    ticks: {
                                        color: getTextColor()
                                    },
                                    grid: {
                                        color: '#6B7280'
                                    }
                                },
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        color: getTextColor(),
                                        callback: value => Number.isInteger(value) ? value : null,
                                        precision: 0
                                    },
                                    grid: {
                                        color: '#6B7280'
                                    }
                                }
                            }
                        }
                    });

                } else {
                    container.classList.add('hidden');
                    noData.classList.remove('hidden');
                }


            } catch (err) {
                console.error(err);
                loading.classList.add('hidden');
                container.classList.add('hidden');
                noData.classList.remove('hidden');
            }
        }



        // =========================
        //     ON PAGE LOAD
        // =========================

        function populateYearDropdown(selectElementId) {
            const currentYear = new Date().getFullYear();
            const select = document.getElementById(selectElementId);
            
            for (let year = currentYear - 5; year <= currentYear + 5; year++) {
                const option = document.createElement('option');
                option.value = year;
                option.textContent = year;
                if (year === currentYear) option.selected = true;
                select.appendChild(option);
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            const pieStart = document.getElementById('pie_start_date');
            const pieEnd = document.getElementById('pie_end_date');
            const lineYear = document.getElementById('line_year');
            const deptTimeYear = document.getElementById('dept_time_year');

            const today = new Date();
            const minus30 = new Date(today);
            minus30.setDate(today.getDate() - 30);
            const currentYear = today.getFullYear();

            pieEnd.valueAsDate = today;
            pieStart.valueAsDate = minus30;
            lineYear.value = currentYear;

            // Populate year dropdowns
            populateYearDropdown('line_year');
            populateYearDropdown('dept_time_year');

            loadPieChart();
            loadLineChart();
            loadStats(pieStart.value, pieEnd.value);
            loadDeptTimeChart(currentYear);

            document.getElementById('filterPieBtn').addEventListener('click', () => {
                loadPieChart();
                loadStats(pieStart.value, pieEnd.value);
            });

            document.getElementById('resetPieBtn').addEventListener('click', () => {
                pieStart.valueAsDate = minus30;
                pieEnd.valueAsDate = today;
                loadPieChart();
                loadStats(pieStart.value, pieEnd.value);
            });

            document.getElementById('filterLineBtn').addEventListener('click', loadLineChart);
            document.getElementById('resetLineBtn').addEventListener('click', () => {
                lineYear.value = currentYear;
                loadLineChart();
            });

            document.getElementById('filterDeptTimeBtn').addEventListener('click', () => {
                const year = deptTimeYear.value;
                loadDeptTimeChart(year);
            });

            document.getElementById('resetDeptTimeBtn').addEventListener('click', () => {
                deptTimeYear.value = currentYear;
                loadDeptTimeChart(currentYear);
            });

            const observer = new MutationObserver(() => {
                Chart.defaults.color = getTextColor();
                Chart.defaults.borderColor = getGridColor();

                if (pieChart) loadPieChart();
                if (lineChart) loadLineChart();
                if (deptTimeChart) loadDeptTimeChart(deptTimeYear.value);
            });

            observer.observe(document.documentElement, {
                attributes: true,
                attributeFilter: ['class']
            });
        });
    </script>


    <style>
        input[type="date"]::-webkit-calendar-picker-indicator {
            filter: invert(0);
        }

        .dark input[type="date"]::-webkit-calendar-picker-indicator {
            filter: invert(1);
        }

        input[type="number"]::-webkit-inner-spin-button,
        input[type="number"]::-webkit-outer-spin-button {
            opacity: 1;
        }

        canvas {
            max-height: 400px;
        }
    </style>

</x-app-layout>
