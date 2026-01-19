<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-xl text-light-text dark:text-dark-text">
                {{ __('Executive Ticket Insight') }}
            </h2>
            <p class="text-xs mt-1 text-light-text-secondary dark:text-dark-text-secondary">
                Insight summary untuk eksekutif (filter per tahun & periode)
            </p>
        </div>
    </x-slot>

    @php
        $page = "min-h-screen bg-light-bg dark:bg-dark-bg";
        $wrap = "w-full px-4 sm:px-6 lg:px-8 py-6 space-y-6";

        $card = "rounded-2xl border shadow-sm overflow-hidden
                 bg-light-eval-1 dark:bg-dark-eval-1
                 border-light-eval-3 dark:border-dark-eval-2";

        $head = "p-4 sm:p-6 border-b
                 border-light-eval-3 dark:border-dark-eval-2";

        $title = "text-lg font-semibold text-light-text dark:text-dark-text";
        $sub = "text-sm text-light-text-secondary dark:text-dark-text-secondary mt-1";

        $label = "block text-sm font-medium mb-1 text-light-text-secondary dark:text-dark-text-secondary";

        $input = "w-full rounded-lg border px-3 py-2 text-sm
                  bg-light-bg dark:bg-dark-eval-2
                  text-light-text dark:text-dark-text
                  border-light-eval-3 dark:border-dark-eval-2
                  focus:ring-2 focus:ring-blue-500/25 focus:border-blue-500/40";

        $btn = "inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg text-sm font-semibold
                bg-blue-600 hover:bg-blue-700 text-white transition-colors shadow-sm";

        $btnGhost = "inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg text-sm font-semibold
                     bg-light-eval-2 dark:bg-dark-eval-2
                     text-light-text-secondary dark:text-dark-text-secondary
                     hover:bg-light-eval-3 dark:hover:bg-dark-eval-3 transition-colors";

        $kpi = "rounded-2xl border p-4
                bg-light-eval-2 dark:bg-dark-eval-2
                border-light-eval-3 dark:border-dark-eval-2";
        $kpiTitle = "text-xs font-semibold uppercase tracking-wider text-light-text-muted dark:text-dark-text-secondary";
        $kpiValue = "mt-2 text-2xl font-bold text-light-text dark:text-dark-text";
    @endphp

    <div class="{{ $page }}">
        <div class="{{ $wrap }}">

            {{-- ================= LINE CHART ================= --}}
            <div class="{{ $card }}">
                <div class="{{ $head }}">
                    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
                        <div>
                            <h3 class="{{ $title }}">Tickets Completed (per Month)</h3>
                            <p class="{{ $sub }}">Jumlah ticket selesai per bulan</p>
                        </div>

                        {{-- Year Filter --}}
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:items-end">
                            <div>
                                <label class="{{ $label }}">Select Year</label>
                                <select id="line_year" class="{{ $input }}"></select>
                            </div>

                            <button id="filterLineBtn" class="{{ $btn }}">Filter</button>
                            <button id="resetLineBtn" class="{{ $btnGhost }}">Reset</button>
                        </div>
                    </div>
                </div>

                <div class="p-4 sm:p-6">
                    <div class="relative min-h-[380px]">
                        <div id="lineLoadingIndicator"
                            class="absolute inset-0 hidden items-center justify-center bg-light-eval-1/80 dark:bg-dark-eval-1/80 backdrop-blur-sm z-10">
                            <div class="flex flex-col items-center gap-3">
                                <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-blue-600"></div>
                                <p class="text-sm text-light-text-secondary dark:text-dark-text-secondary">Load Data...</p>
                            </div>
                        </div>

                        <div id="lineNoDataMessage"
                            class="absolute inset-0 hidden items-center justify-center z-10">
                            <p class="text-sm italic text-light-text-muted dark:text-dark-text-secondary">No Data Available</p>
                        </div>

                        <div id="lineChartContainer" class="w-full">
                            <canvas id="ticketsDoneChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ================= BAR CHART (TIME SPENT / DEPT) ================= --}}
            <div class="{{ $card }}">
                <div class="{{ $head }}">
                    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
                        <div>
                            <h3 class="{{ $title }}">Total Time Spent per Department</h3>
                            <p class="{{ $sub }}">Akumulasi menit pengerjaan per bulan (per departemen)</p>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:items-end">
                            <div>
                                <label class="{{ $label }}">Pilih Tahun</label>
                                <select id="dept_time_year" class="{{ $input }}"></select>
                            </div>

                            <button id="filterDeptTimeBtn" class="{{ $btn }}">Filter</button>
                            <button id="resetDeptTimeBtn" class="{{ $btnGhost }}">Reset</button>
                        </div>
                    </div>
                </div>

                <div class="p-4 sm:p-6">
                    <div class="relative min-h-[380px]">
                        <div id="deptTimeLoading"
                            class="absolute inset-0 hidden items-center justify-center bg-light-eval-1/80 dark:bg-dark-eval-1/80 backdrop-blur-sm z-10">
                            <div class="flex flex-col items-center gap-3">
                                <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-blue-600"></div>
                                <p class="text-sm text-light-text-secondary dark:text-dark-text-secondary">Load Data...</p>
                            </div>
                        </div>

                        <div id="deptTimeNoData"
                            class="absolute inset-0 hidden items-center justify-center z-10">
                            <p class="text-sm italic text-light-text-muted dark:text-dark-text-secondary">No Data Available</p>
                        </div>

                        <div id="deptTimeChartContainer" class="w-full">
                            <canvas id="timeSpentDepartmentChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ================= PIE CHART ================= --}}
            <div class="{{ $card }}">
                <div class="{{ $head }}">
                    <div class="flex flex-col gap-4">
                        <div>
                            <h3 class="{{ $title }}">Ticket Distribution by Category</h3>
                            <p class="{{ $sub }}">Sebaran ticket berdasarkan kategori</p>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                            <div>
                                <label class="{{ $label }}">Start Date</label>
                                <input type="date" id="pie_start_date" class="{{ $input }} date-input">
                            </div>
                            <div>
                                <label class="{{ $label }}">End Date</label>
                                <input type="date" id="pie_end_date" class="{{ $input }} date-input">
                            </div>
                            <div class="flex items-end">
                                <button id="filterPieBtn" class="{{ $btn }} w-full">Filter</button>
                            </div>
                            <div class="flex items-end">
                                <button id="resetPieBtn" class="{{ $btnGhost }} w-full">Reset</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-4 sm:p-6">
                    <div id="pieLoadingIndicator" class="hidden py-14 text-center">
                        <div class="inline-block animate-spin rounded-full h-10 w-10 border-b-2 border-blue-600"></div>
                        <p class="mt-3 text-sm text-light-text-secondary dark:text-dark-text-secondary">Load Data...</p>
                    </div>

                    <div id="pieChartContainer" class="flex justify-center items-center min-h-[380px]">
                        <div class="w-full max-w-[640px]">
                            <canvas id="ticketCategoryChart"></canvas>
                        </div>
                    </div>

                    <div id="pieNoDataMessage" class="hidden py-14 text-center">
                        <p class="text-sm italic text-light-text-muted dark:text-dark-text-secondary">No Data Available</p>
                    </div>
                </div>

                {{-- Stats --}}
                <div class="p-4 sm:p-6 border-t border-light-eval-3 dark:border-dark-eval-2">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="{{ $kpi }}">
                            <div class="{{ $kpiTitle }}">Full Resolution Time</div>
                            <div id="fullResolutionTime" class="{{ $kpiValue }}">-</div>
                        </div>
                        <div class="{{ $kpi }}">
                            <div class="{{ $kpiTitle }}">Average Resolution Time</div>
                            <div id="avgResolutionTime" class="{{ $kpiValue }}">-</div>
                        </div>
                        <div class="{{ $kpi }}">
                            <div class="{{ $kpiTitle }}">SLA Percentage</div>
                            <div id="slaPercentage" class="{{ $kpiValue }}">-</div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- ================= JS (FASTER) ================= --}}
    <script>
        // ===== Utils (fast DOM access + no heavy re-render) =====
        const $ = (id) => document.getElementById(id);
        const show = (id) => $(id).classList.remove('hidden');
        const hide = (id) => $(id).classList.add('hidden');
        const flexShow = (id) => { $(id).classList.remove('hidden'); $(id).classList.add('flex'); };
        const flexHide = (id) => { $(id).classList.add('hidden'); $(id).classList.remove('flex'); };

        function isDarkMode() {
            return document.documentElement.classList.contains('dark');
        }

        // Chart theme (netral biar konsisten)
        const NEUTRAL = '#6B7280';
        function getTextColor() { return NEUTRAL; }
        function getGridColor() { return NEUTRAL; }
        function getBgColor() { return isDarkMode() ? '#151823' : '#FFFFFF'; }

        function generateColors(count) {
            const colors = ['#3b82f6','#ef4444','#10b981','#f59e0b','#8b5cf6','#ec4899','#06b6d4','#84cc16','#f97316','#6366f1'];
            return Array.from({ length: count }, (_, i) => colors[i % colors.length]);
        }

        function fillYearSelect(selectId) {
            const currentYear = new Date().getFullYear();
            const select = $(selectId);
            select.innerHTML = '';
            for (let y = currentYear - 5; y <= currentYear + 5; y++) {
                const opt = document.createElement('option');
                opt.value = y;
                opt.textContent = y;
                if (y === currentYear) opt.selected = true;
                select.appendChild(opt);
            }
        }

        let pieChart = null;
        let lineChart = null;
        let deptTimeChart = null;

        // ===== Stats =====
        async function loadStats(startDate = null, endDate = null) {
            const fullEl = $('fullResolutionTime');
            const avgEl  = $('avgResolutionTime');
            const slaEl  = $('slaPercentage');

            let url = '/api/tickets/statistik';
            const params = new URLSearchParams();
            if (startDate) params.append('start_date', startDate);
            if (endDate) params.append('end_date', endDate);
            if (params.toString()) url += '?' + params.toString();

            try {
                const res = await fetch(url);
                const data = await res.json();
                fullEl.textContent = data.success ? data.data.fullResolutionTime : '-';
                avgEl.textContent  = data.success ? data.data.avgResolutionTime : '-';
                slaEl.textContent  = data.success ? (data.data.slaPercentage + '%') : '-';
            } catch {
                fullEl.textContent = avgEl.textContent = slaEl.textContent = '-';
            }
        }

        // ===== Pie =====
        async function loadPieChart() {
            const start = $('pie_start_date').value;
            const end = $('pie_end_date').value;

            if (start && end && start > end) {
                alert('Tanggal mulai tidak boleh lebih besar dari tanggal akhir!');
                return;
            }

            show('pieLoadingIndicator');
            hide('pieChartContainer');
            hide('pieNoDataMessage');

            if (pieChart) { pieChart.destroy(); pieChart = null; }

            let url = '/api/reports/tickets-by-category';
            const params = new URLSearchParams();
            if (start) params.append('start_date', start);
            if (end) params.append('end_date', end);
            if (params.toString()) url += '?' + params.toString();

            try {
                const res = await fetch(url);
                const data = await res.json();

                hide('pieLoadingIndicator');

                if (data.data && data.data.length > 0) {
                    show('pieChartContainer');

                    const labels = data.data.map(i => i.category || 'Unknown');
                    const values = data.data.map(i => i.total || 0);

                    // kalau semua 0 → no data
                    if (values.every(v => v === 0)) {
                        show('pieNoDataMessage');
                        hide('pieChartContainer');
                        return;
                    }

                    pieChart = new Chart($('ticketCategoryChart'), {
                        type: 'pie',
                        data: {
                            labels,
                            datasets: [{
                                data: values,
                                backgroundColor: generateColors(values.length),
                                borderColor: getBgColor(),
                                borderWidth: 2
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: { color: getTextColor(), font: { size: 12 }, padding: 15 }
                                }
                            }
                        }
                    });
                } else {
                    show('pieNoDataMessage');
                }
            } catch (err) {
                console.error(err);
                hide('pieLoadingIndicator');
                show('pieNoDataMessage');
            }
        }

        // ===== Line =====
        async function loadLineChart() {
            const year = $('line_year').value || new Date().getFullYear();

            flexShow('lineLoadingIndicator');
            hide('lineChartContainer');
            flexHide('lineNoDataMessage');

            if (lineChart) { lineChart.destroy(); lineChart = null; }

            try {
                const res = await fetch(`/api/reports/tickets-done-per-month?year=${year}`);
                const data = await res.json();

                flexHide('lineLoadingIndicator');

                if (data.data && data.data.length > 0) {
                    const labels = data.data.map(i => i.month || '');
                    const values = data.data.map(i => i.total || 0);

                    if (values.every(v => v === 0)) {
                        flexShow('lineNoDataMessage');
                        return;
                    }

                    show('lineChartContainer');

                    lineChart = new Chart($('ticketsDoneChart'), {
                        type: 'line',
                        data: {
                            labels,
                            datasets: [{
                                label: 'Ticket Selesai',
                                data: values,
                                borderWidth: 2,
                                tension: 0.4,
                                fill: true,
                                borderColor: '#3b82f6',
                                backgroundColor: 'rgba(59,130,246,0.15)',
                                pointRadius: 4,
                                pointBackgroundColor: '#3b82f6',
                                pointBorderColor: getBgColor(),
                                pointBorderWidth: 2
                            }]
                        },
                        options: {
                            responsive: true,
                            scales: {
                                x: { ticks: { color: getTextColor() }, grid: { color: getGridColor() } },
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        color: getTextColor(),
                                        callback: v => Number.isInteger(v) ? v : null
                                    },
                                    grid: { color: getGridColor() }
                                }
                            },
                            plugins: {
                                legend: { labels: { color: getTextColor() } }
                            }
                        }
                    });
                } else {
                    flexShow('lineNoDataMessage');
                }
            } catch (err) {
                console.error(err);
                flexHide('lineLoadingIndicator');
                flexShow('lineNoDataMessage');
            }
        }

        // ===== Dept Time (bar) =====
        async function loadDeptTimeChart(year) {
            flexShow('deptTimeLoading');
            hide('deptTimeChartContainer');
            flexHide('deptTimeNoData');

            if (deptTimeChart) { deptTimeChart.destroy(); deptTimeChart = null; }

            try {
                const res = await fetch(`/api/chart-time-spent-by-department?year=${year}`);
                const json = await res.json();

                flexHide('deptTimeLoading');

                if (!json.data || !json.data.datasets || json.data.datasets.length === 0) {
                    flexShow('deptTimeNoData');
                    return;
                }

                const monthLabels = json.data.labels;
                const colors = generateColors(json.data.datasets.length);

                const datasets = json.data.datasets
                    .map((ds, i) => {
                        const total = (ds.data || []).reduce((s, v) => s + (v || 0), 0);
                        if (total <= 0) return null;
                        return {
                            label: ds.label,
                            data: ds.data || [],
                            backgroundColor: colors[i],
                            borderColor: colors[i],
                            borderWidth: 1
                        };
                    })
                    .filter(Boolean);

                if (!datasets.length) {
                    flexShow('deptTimeNoData');
                    return;
                }

                show('deptTimeChartContainer');

                deptTimeChart = new Chart($('timeSpentDepartmentChart'), {
                    type: 'bar',
                    data: { labels: monthLabels, datasets },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    color: getTextColor(),
                                    callback: value => {
                                        const h = Math.floor(value / 60);
                                        const m = value % 60;
                                        return `${h}j ${m}m`;
                                    }
                                },
                                grid: { color: getGridColor() }
                            },
                            x: { ticks: { color: getTextColor() }, grid: { color: getGridColor() } }
                        },
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: { color: getTextColor(), font: { size: 12 }, padding: 15, usePointStyle: true }
                            },
                            tooltip: {
                                callbacks: {
                                    label: ctx => {
                                        const m = ctx.raw || 0;
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
                flexHide('deptTimeLoading');
                flexShow('deptTimeNoData');
            }
        }

        // ===== Init =====
        document.addEventListener('DOMContentLoaded', () => {
            // default date = last 30 days
            const today = new Date();
            const minus30 = new Date(today);
            minus30.setDate(today.getDate() - 30);

            $('pie_end_date').valueAsDate = today;
            $('pie_start_date').valueAsDate = minus30;

            // years
            fillYearSelect('line_year');
            fillYearSelect('dept_time_year');

            const currentYear = new Date().getFullYear();

            // initial load (parallel biar cepat)
            Promise.all([
                loadPieChart(),
                loadLineChart(),
                loadStats($('pie_start_date').value, $('pie_end_date').value),
                loadDeptTimeChart(currentYear),
            ]);

            // handlers
            $('filterPieBtn').addEventListener('click', () => {
                loadPieChart();
                loadStats($('pie_start_date').value, $('pie_end_date').value);
            });

            $('resetPieBtn').addEventListener('click', () => {
                $('pie_start_date').valueAsDate = minus30;
                $('pie_end_date').valueAsDate = today;
                loadPieChart();
                loadStats($('pie_start_date').value, $('pie_end_date').value);
            });

            $('filterLineBtn').addEventListener('click', loadLineChart);
            $('resetLineBtn').addEventListener('click', () => {
                $('line_year').value = currentYear;
                loadLineChart();
            });

            $('filterDeptTimeBtn').addEventListener('click', () => {
                loadDeptTimeChart($('dept_time_year').value);
            });

            $('resetDeptTimeBtn').addEventListener('click', () => {
                $('dept_time_year').value = currentYear;
                loadDeptTimeChart(currentYear);
            });

            // Dark mode: update chart colors without refetch (lebih cepat)
            const observer = new MutationObserver(() => {
                Chart.defaults.color = getTextColor();
                Chart.defaults.borderColor = getGridColor();

                if (pieChart) pieChart.update();
                if (lineChart) lineChart.update();
                if (deptTimeChart) deptTimeChart.update();
            });

            observer.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });
        });
    </script>

    <style>
        .date-input::-webkit-calendar-picker-indicator { filter: invert(0); cursor: pointer; opacity: .85; }
        .dark .date-input::-webkit-calendar-picker-indicator { filter: invert(1); opacity: .85; }
        .date-input::-webkit-calendar-picker-indicator:hover { opacity: 1; }
        canvas { max-height: 420px; }
    </style>
</x-app-layout>
