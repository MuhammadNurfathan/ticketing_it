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
                        📈 Ticket Selesai Per Tahun
                    </h3>

                    {{-- Year Filter --}}
                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3 mb-6">
                        <div class="flex items-center gap-2 w-full sm:w-auto">
                            <label for="line_year"
                                class="text-sm font-medium text-gray-700 dark:text-gray-300 whitespace-nowrap">
                                Pilih Tahun
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
                            <p class="mt-4 text-gray-600 dark:text-gray-400">Memuat data...</p>
                        </div>

                        <div id="lineNoDataMessage"
                            class="absolute inset-0 flex justify-center items-center text-gray-500 dark:text-gray-400 hidden z-10">
                            Tidak ada data untuk ditampilkan
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
                        📊 Distribusi Ticket Berdasarkan Kategori
                    </h3>

                    {{-- Pie Chart Filter --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Tanggal Mulai
                            </label>
                            <input type="date" id="pie_start_date"
                                class="block w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-dark-eval-2 text-gray-900 dark:text-white shadow-sm focus:border-gray-400 focus:ring-gray-400 dark:focus:ring-gray-500 text-sm px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Tanggal Akhir
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
                <div class="p-4 sm:p-6 border-t border-gray-200 dark:border-gray-700">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div
                            class="bg-gray-50 dark:bg-dark-eval-2 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
                            <h4
                                class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-2">
                                Full Resolution Time
                            </h4>
                            <p id="fullResolutionTime" class="text-2xl font-bold text-gray-900 dark:text-white">-</p>
                        </div>
                        <div
                            class="bg-gray-50 dark:bg-dark-eval-2 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
                            <h4
                                class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-2">
                                Average Resolution Time
                            </h4>
                            <p id="avgResolutionTime" class="text-2xl font-bold text-gray-900 dark:text-white">-</p>
                        </div>
                        <div
                            class="bg-gray-50 dark:bg-dark-eval-2 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
                            <h4
                                class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-2">
                                SLA Percentage
                            </h4>
                            <p id="slaPercentage" class="text-2xl font-bold text-gray-900 dark:text-white">-</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Chart.js CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    <script>
    // ============ HELPER: Detect Dark Mode ============
    function isDarkMode() {
        return document.documentElement.classList.contains('dark');
    }

    // Satu warna netral yang dipilih: #999999
    const NEUTRAL_COLOR = '#999999';

    // DIUBAH: Menggunakan satu warna netral untuk teks di kedua mode
    function getTextColor() {
        return NEUTRAL_COLOR;
    }

    // DIUBAH: Menggunakan satu warna netral untuk grid di kedua mode
    function getGridColor() {
        return NEUTRAL_COLOR;
    }

    // Warna Background Card (tetap dinamis agar digunakan sebagai border chart)
    function getBgColor() {
        // Ini adalah warna background container/card, yang penting untuk border chart di Dark Mode
        return isDarkMode() ? '#1f2937' : '#ffffff'; 
    }


    // ============ CHART DEFAULTS ============
    // Chart.js defaults kini akan selalu menggunakan #999999
    Chart.defaults.color = getTextColor();
    Chart.defaults.borderColor = getGridColor();

    // ============ STATS ============
    async function loadStats(startDate = null, endDate = null) {
        // ... (Fungsi loadStats tetap sama)
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
        } catch (err) {
            console.error('Error loading stats:', err);
            document.getElementById('fullResolutionTime').innerText = '-';
            document.getElementById('avgResolutionTime').innerText = '-';
            document.getElementById('slaPercentage').innerText = '-';
        }
    }

    // ============ PIE & LINE CHART ============
    document.addEventListener('DOMContentLoaded', function() {
        const pieStart = document.getElementById('pie_start_date');
        const pieEnd = document.getElementById('pie_end_date');
        const lineYear = document.getElementById('line_year');

        // Default values
        const today = new Date();
        const thirtyDaysAgo = new Date(today);
        thirtyDaysAgo.setDate(today.getDate() - 30);
        pieEnd.valueAsDate = today;
        pieStart.valueAsDate = thirtyDaysAgo;
        lineYear.value = today.getFullYear();

        // Initial load
        loadPieChart();
        loadLineChart();
        loadStats(pieStart.value, pieEnd.value);

        // Event listeners
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
            lineYear.value = today.getFullYear();
            loadLineChart();
        });

        // Listen to dark mode changes
        const observer = new MutationObserver(() => {
            // Perbarui default dengan warna NEUTRAL_COLOR yang baru
            Chart.defaults.color = getTextColor();
            Chart.defaults.borderColor = getGridColor();
            if (pieChart) loadPieChart();
            if (lineChart) loadLineChart();
        });
        observer.observe(document.documentElement, { 
            attributes: true, 
            attributeFilter: ['class'] 
        });
    });

    let pieChart, lineChart;

    // ============ PIE CHART ============
    async function loadPieChart() {
        const start = document.getElementById('pie_start_date').value;
        const end = document.getElementById('pie_end_date').value;
        const container = document.getElementById('pieChartContainer');
        const noData = document.getElementById('pieNoDataMessage');
        const loading = document.getElementById('pieLoadingIndicator');
        const canvas = document.getElementById('ticketCategoryChart');

        if(start && end && start > end) { 
            alert('Tanggal mulai tidak boleh lebih besar dari tanggal akhir!'); 
            return; 
        }

        loading.classList.remove('hidden');
        container.classList.add('hidden');
        noData.classList.add('hidden');
        
        if(pieChart) {
            pieChart.destroy();
            pieChart = null;
        }

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
                
                const labels = data.data.map(item => item.category || 'Unknown');
                const values = data.data.map(item => item.total || 0);
                
                // Generate colors
                const colors = generateColors(data.data.length);
                
                pieChart = new Chart(canvas, {
                    type: 'pie',
                    data: {
                        labels: labels,
                        datasets: [{
                            data: values,
                            backgroundColor: colors,
                            // MENGGUNAKAN getBgColor() sebagai border agar slice terlihat jelas di kedua mode
                            borderColor: getBgColor(), 
                            borderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    color: getTextColor(), // Menggunakan #999999
                                    font: {
                                        size: 12
                                    },
                                    padding: 15
                                }
                            },
                            tooltip: {
                                // Background tooltip tetap dinamis
                                backgroundColor: isDarkMode() ? '#ffffff' : '#374151',
                                titleColor: getTextColor(), // Menggunakan #999999
                                bodyColor: getTextColor(), // Menggunakan #999999
                                borderColor: getGridColor(), // Menggunakan #999999
                                borderWidth: 1
                            }
                        }
                    }
                });
            } else {
                noData.classList.remove('hidden');
            }
        } catch (err) {
            console.error('Error loading pie chart:', err);
            loading.classList.add('hidden');
            noData.classList.remove('hidden');
        }
    }

    // ============ LINE CHART ============
    async function loadLineChart() {
        const year = document.getElementById('line_year').value || new Date().getFullYear();
        const container = document.getElementById('lineChartContainer');
        const noData = document.getElementById('lineNoDataMessage');
        const loading = document.getElementById('lineLoadingIndicator');
        const canvas = document.getElementById('ticketsDoneChart');

        loading.classList.remove('hidden');
        container.classList.add('hidden');
        noData.classList.add('hidden');
        
        if(lineChart) {
            lineChart.destroy();
            lineChart = null;
        }

        try {
            const res = await fetch(`/api/reports/tickets-done-per-month?year=${year}`);
            const data = await res.json();
            loading.classList.add('hidden');
            
            if(data.data && data.data.length) {
                container.classList.remove('hidden');
                
                const labels = data.data.map(item => item.month || '');
                const values = data.data.map(item => item.total || 0);
                
                lineChart = new Chart(canvas, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Ticket Selesai',
                            // Warna garis dan background tetap dinamis
                            borderColor: isDarkMode() ? '#3b82f6' : '#60a5fa',
                            backgroundColor: isDarkMode() ? 'rgba(59, 130, 246, 0.1)' : 'rgba(96, 165, 250, 0.1)',
                            data: values,
                            borderWidth: 2,
                            fill: true,
                            tension: 0.4,
                            pointRadius: 4,
                            pointBackgroundColor: isDarkMode() ? '#3b82f6' : '#60a5fa',
                            // Border titik menggunakan warna background card
                            pointBorderColor: getBgColor(),
                            pointBorderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top',
                                labels: {
                                    color: getTextColor(), // Menggunakan #999999
                                    font: {
                                        size: 12
                                    },
                                    padding: 15
                                }
                            },
                            tooltip: {
                                // Background tooltip tetap dinamis
                                backgroundColor: isDarkMode() ? '#ffffff' : '#374151',
                                titleColor: getTextColor(), // Menggunakan #999999
                                bodyColor: getTextColor(), // Menggunakan #999999
                                borderColor: getGridColor(), // Menggunakan #999999
                                borderWidth: 1
                            }
                        },
                        scales: {
                            x: {
                                grid: {
                                    color: getGridColor(), // Menggunakan #999999
                                    drawBorder: false
                                },
                                ticks: {
                                    color: getTextColor(), // Menggunakan #999999
                                    font: {
                                        size: 11
                                    }
                                }
                            },
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: getGridColor(), // Menggunakan #999999
                                    drawBorder: false
                                },
                                ticks: {
                                    color: getTextColor(), // Menggunakan #999999
                                    font: {
                                        size: 11
                                    },
                                    stepSize: 1
                                }
                            }
                        }
                    }
                });
            } else {
                noData.classList.remove('hidden');
            }
        } catch (err) {
            console.error('Error loading line chart:', err);
            loading.classList.add('hidden');
            noData.classList.remove('hidden');
        }
    }

    // ============ GENERATE COLORS ============
    function generateColors(count) {
        const colors = [
            '#3b82f6', '#ef4444', '#10b981', '#f59e0b', '#8b5cf6',
            '#ec4899', '#06b6d4', '#84cc16', '#f97316', '#6366f1'
        ];
        
        const result = [];
        for(let i = 0; i < count; i++) {
            result.push(colors[i % colors.length]);
        }
        return result;
    }
</script>

    <style>
        /* Date input styling for dark mode */
        input[type="date"]::-webkit-calendar-picker-indicator {
            filter: invert(0);
        }

        .dark input[type="date"]::-webkit-calendar-picker-indicator {
            filter: invert(1);
        }

        /* Number input styling */
        input[type="number"]::-webkit-inner-spin-button,
        input[type="number"]::-webkit-outer-spin-button {
            opacity: 1;
        }

        /* Canvas styling */
        canvas {
            max-height: 400px;
        }
    </style>
</x-app-layout>
