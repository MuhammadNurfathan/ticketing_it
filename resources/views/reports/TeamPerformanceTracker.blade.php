<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-xl text-light-text dark:text-dark-text">
                {{ __('Executive Ticket Insight') }}
            </h2>
            <p class="text-xs mt-1 text-light-text-secondary dark:text-dark-text-secondary">
                Download data + insight per developer & support
            </p>
        </div>
    </x-slot>

    @php
        $page = 'min-h-screen bg-light-bg dark:bg-dark-bg';
        $wrap = 'w-full px-4 sm:px-6 lg:px-8 py-6 space-y-6';

        $card = "rounded-2xl border shadow-sm overflow-hidden
                 bg-light-eval-1 dark:bg-dark-eval-1
                 border-light-eval-3 dark:border-dark-eval-2";

        $head = "p-4 sm:p-6 border-b
                 border-light-eval-3 dark:border-dark-eval-2";

        $title = 'text-lg font-semibold text-light-text dark:text-dark-text';
        $sub = 'text-sm text-light-text-secondary dark:text-dark-text-secondary mt-1';

        $label = 'block text-sm font-medium mb-1 text-light-text-secondary dark:text-dark-text-secondary';

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

        $tableHead = "bg-light-eval-2 dark:bg-dark-eval-2 border-b
                      border-light-eval-3 dark:border-dark-eval-2";

        $th =
            'px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-light-text-secondary dark:text-dark-text-secondary';
        $td = 'px-4 py-3 text-sm text-light-text dark:text-dark-text';
    @endphp

    <div class="{{ $page }}">
        <div class="{{ $wrap }}">

            {{-- ================= DOWNLOAD DATA ================= --}}
            <div class="{{ $card }}">
                <div class="{{ $head }}">
                    <h2 class="{{ $title }}">Download Data Tickets</h2>
                    <p class="{{ $sub }}">Preview dulu biar yakin, lalu download CSV</p>
                </div>

                <div class="p-4 sm:p-6">

                    <form id="exportForm" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
                        <div>
                            <label for="start_date" class="{{ $label }}">Tanggal Mulai</label>
                            <input type="date" id="start_date" name="start_date"
                                class="{{ $input }} date-input">
                        </div>

                        <div>
                            <label for="end_date" class="{{ $label }}">Tanggal Akhir</label>
                            <input type="date" id="end_date" name="end_date"
                                class="{{ $input }} date-input">
                        </div>

                        <div>
                            <label for="datePreset" class="{{ $label }}">Preset</label>
                            <select id="datePreset" class="{{ $input }}">
                                <option value="today" selected>Today</option>
                                <option value="this_month" selected>This Month</option>
                                <option value="last_week">Last Week</option>
                                <option value="last_month">Last Month</option>
                                <option value="this_year">This Year</option>
                                <option value="last_year">Last Year</option>
                                <option value="">Custom</option>
                            </select>
                        </div>

                        <div>
                            <button type="button" id="previewBtn" class="{{ $btn }} w-full">
                                Preview
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- ================= MODAL PREVIEW ================= --}}
            <div id="previewModal" class="hidden fixed inset-0 z-50 bg-black/50 backdrop-blur-sm">
                <div class="min-h-full flex items-center justify-center p-4">
                    <div id="previewModalPanel"
                        class="{{ $card }} w-full max-w-6xl transform scale-95 opacity-0 transition duration-200">
                        <div class="{{ $head }} flex items-center justify-between gap-3">
                            <div>
                                <h3 class="text-lg font-semibold text-light-text dark:text-dark-text">Preview Data
                                    Tickets</h3>
                                <p id="previewRangeText"
                                    class="text-xs mt-1 text-light-text-secondary dark:text-dark-text-secondary">
                                    -
                                </p>
                            </div>

                            <button id="closePreviewX"
                                class="h-10 w-10 rounded-lg grid place-items-center
                                       bg-light-eval-2 dark:bg-dark-eval-2
                                       hover:bg-light-eval-3 dark:hover:bg-dark-eval-3
                                       text-light-text dark:text-dark-text transition-colors">
                                ✕
                            </button>
                        </div>

                        <div class="p-4 sm:p-6">
                            <div class="relative">
                                <div id="previewLoading"
                                    class="hidden absolute inset-0 z-10 rounded-xl
                                           bg-light-eval-1/80 dark:bg-dark-eval-1/80 backdrop-blur-sm
                                           flex items-center justify-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-blue-600">
                                        </div>
                                        <p class="text-sm text-light-text-secondary dark:text-dark-text-secondary">
                                            Loading...</p>
                                    </div>
                                </div>

                                <div
                                    class="overflow-x-auto max-h-[60vh] rounded-xl border border-light-eval-3 dark:border-dark-eval-2">
                                    <table class="min-w-full text-sm">
                                        <thead class="{{ $tableHead }} sticky top-0 z-10">
                                            <tr>
                                                <th class="{{ $th }}">Ticket Code</th>
                                                <th class="{{ $th }}">Requestor</th>
                                                <th class="{{ $th }}">Support</th>
                                                <th class="{{ $th }}">Problem</th>
                                                <th class="{{ $th }}">Status</th>
                                                <th class="{{ $th }}">Start</th>
                                                <th class="{{ $th }}">End</th>
                                                <th class="{{ $th }}">Time (min)</th>
                                                <th class="{{ $th }}">Late</th>
                                                <th class="{{ $th }}">Created</th>
                                            </tr>
                                        </thead>
                                        <tbody id="previewTableBody"
                                            class="divide-y divide-light-eval-3 dark:divide-dark-eval-2
                                                   bg-light-eval-1 dark:bg-dark-eval-1">
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="mt-6 flex flex-col sm:flex-row justify-end gap-3">
                                <button id="closePreview" class="{{ $btnGhost }}">
                                    Tutup
                                </button>
                                <button id="confirmDownload" class="{{ $btn }}">
                                    💾 Download CSV
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ================= BAR CHART: TICKETS / DEV ================= --}}
            <div class="{{ $card }}">
                <div class="{{ $head }}">
                    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
                        <div>
                            <h3 class="{{ $title }}">👨‍💻 Ticket Done per Developer</h3>
                            <p class="{{ $sub }}">Jumlah ticket selesai per developer (per tahun)</p>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:items-end">
                            <div>
                                <label class="{{ $label }}">Pilih Tahun</label>
                                <select id="bar_year" class="{{ $input }}"></select>
                            </div>
                            <button id="filterBarBtn" class="{{ $btn }}">Filter</button>
                            <button id="resetBarBtn" class="{{ $btnGhost }}">Reset</button>
                        </div>
                    </div>
                </div>

                <div class="p-4 sm:p-6">
                    <div class="relative min-h-[380px]">
                        <div id="barLoadingIndicator"
                            class="absolute inset-0 hidden items-center justify-center bg-light-eval-1/80 dark:bg-dark-eval-1/80 backdrop-blur-sm z-10">
                            <div class="flex flex-col items-center gap-3">
                                <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-blue-600"></div>
                                <p class="text-sm text-light-text-secondary dark:text-dark-text-secondary">Load Data...
                                </p>
                            </div>
                        </div>

                        <div id="barNoDataMessage" class="absolute inset-0 hidden items-center justify-center z-10">
                            <p class="text-sm italic text-light-text-muted dark:text-dark-text-secondary">No Data
                                Available</p>
                        </div>

                        <div id="barChartContainer" class="w-full">
                            <canvas id="myBarChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ================= BAR CHART: TIME / DEV ================= --}}
            <div class="{{ $card }}">
                <div class="{{ $head }}">
                    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
                        <div>
                            <h3 class="{{ $title }}">⏱️ Total Time Spent per Developer</h3>
                            <p class="{{ $sub }}">Akumulasi menit pengerjaan (per tahun)</p>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:items-end">
                            <div>
                                <label class="{{ $label }}">Pilih Tahun</label>
                                <select id="time_year" class="{{ $input }}"></select>
                            </div>
                            <button id="filterTimeBtn" class="{{ $btn }}">Filter</button>
                            <button id="resetTimeBtn" class="{{ $btnGhost }}">Reset</button>
                        </div>
                    </div>
                </div>

                <div class="p-4 sm:p-6">
                    <div class="relative min-h-[380px]">
                        <div id="timeLoadingIndicator"
                            class="absolute inset-0 hidden items-center justify-center bg-light-eval-1/80 dark:bg-dark-eval-1/80 backdrop-blur-sm z-10">
                            <div class="flex flex-col items-center gap-3">
                                <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-blue-600"></div>
                                <p class="text-sm text-light-text-secondary dark:text-dark-text-secondary">Load Data...
                                </p>
                            </div>
                        </div>

                        <div id="timeNoDataMessage" class="absolute inset-0 hidden items-center justify-center z-10">
                            <p class="text-sm italic text-light-text-muted dark:text-dark-text-secondary">No Data
                                Available</p>
                        </div>

                        <div id="timeChartContainer" class="w-full">
                            <canvas id="timeSpentChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ================= TICKETS BY SUPPORT ================= --}}
            <div class="{{ $card }}">
                <div class="{{ $head }}">
                    <h2 class="{{ $title }}">Tickets by Support (Per Hari)</h2>
                    <p class="{{ $sub }}">Ringkasan ticket per support, per tanggal</p>
                </div>

                <div class="p-4 sm:p-6">
                    <div class="flex flex-col sm:flex-row gap-3 mb-6">
                        <input type="date" id="date" class="{{ $input }} date-input flex-1">
                        <button id="filterBtn" class="{{ $btn }}">Filter</button>
                    </div>

                    <div id="ticketsContainer" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4"></div>
                </div>
            </div>

        </div>
    </div>

    {{-- ================= STYLE ================= --}}
    <style>
        .date-input::-webkit-calendar-picker-indicator {
            filter: invert(0);
            cursor: pointer;
            opacity: .85;
        }

        .dark .date-input::-webkit-calendar-picker-indicator {
            filter: invert(1);
            opacity: .85;
        }

        .date-input::-webkit-calendar-picker-indicator:hover {
            opacity: 1;
        }

        canvas {
            max-height: 420px;
        }
    </style>

    {{-- ================= JS (FASTER) ================= --}}
    <script>
        const $ = (id) => document.getElementById(id);
        const show = (id) => $(id).classList.remove('hidden');
        const hide = (id) => $(id).classList.add('hidden');
        const flexShow = (id) => {
            $(id).classList.remove('hidden');
            $(id).classList.add('flex');
        };
        const flexHide = (id) => {
            $(id).classList.add('hidden');
            $(id).classList.remove('flex');
        };

        // ====== Modal helpers ======
        function openPreviewModal() {
            const modal = $('previewModal');
            const panel = $('previewModalPanel');
            modal.classList.remove('hidden');
            requestAnimationFrame(() => {
                panel.classList.remove('opacity-0');
                panel.classList.remove('scale-95');
                panel.classList.add('opacity-100');
                panel.classList.add('scale-100');
            });
        }

        function closePreviewModal() {
            const modal = $('previewModal');
            const panel = $('previewModalPanel');
            panel.classList.remove('opacity-100');
            panel.classList.remove('scale-100');
            panel.classList.add('opacity-0');
            panel.classList.add('scale-95');
            setTimeout(() => modal.classList.add('hidden'), 180);
        }


        // ====== Preview (FAST render) ======
        $('previewBtn').addEventListener('click', async () => {
            const start = $('start_date').value;
            const end = $('end_date').value;

            if (!start || !end) {
                alert('Isi tanggal mulai dan tanggal akhir terlebih dahulu.');
                return;
            }
            if (start > end) {
                alert('Tanggal mulai tidak boleh lebih besar dari tanggal akhir.');
                return;
            }

            $('previewRangeText').textContent = `${start} → ${end}`;

            // UI state
            show('previewLoading');
            $('previewTableBody').innerHTML = '';
            openPreviewModal();

            try {
                const res = await fetch(`/api/tickets/preview?start_date=${start}&end_date=${end}`);
                const result = await res.json();

                hide('previewLoading');

                const data = result.data || [];
                if (!data.length) {
                    $('previewTableBody').innerHTML = `
                        <tr>
                            <td colspan="10" class="px-4 py-10 text-center text-sm italic text-light-text-muted dark:text-dark-text-secondary">
                                Tidak ada data di range ini
                            </td>
                        </tr>`;
                    return;
                }

                const frag = document.createDocumentFragment();
                data.forEach(t => {
                    const tr = document.createElement('tr');
                    tr.className = 'transition-colors hover:bg-light-eval-2 dark:hover:bg-dark-eval-2';
                    tr.innerHTML = `
                        <td class="px-4 py-3 text-sm text-light-text dark:text-dark-text">${t.ticket_code ?? '-'}</td>
                        <td class="px-4 py-3 text-sm text-light-text dark:text-dark-text">${t.requestor_name ?? '-'}</td>
                        <td class="px-4 py-3 text-sm text-light-text dark:text-dark-text">${t.support_name ?? '-'}</td>
                        <td class="px-4 py-3 text-sm text-light-text dark:text-dark-text">${t.problem ?? '-'}</td>
                        <td class="px-4 py-3 text-sm text-light-text dark:text-dark-text">${t.status_name ?? '-'}</td>
                        <td class="px-4 py-3 text-sm text-light-text dark:text-dark-text">${t.start_date ?? '-'}</td>
                        <td class="px-4 py-3 text-sm text-light-text dark:text-dark-text">${t.end_date ?? '-'}</td>
                        <td class="px-4 py-3 text-sm text-center text-light-text dark:text-dark-text">${t.time_spent ?? '-'}</td>
                        <td class="px-4 py-3 text-sm text-center text-light-text dark:text-dark-text">${t.is_late ?? '-'}</td>
                        <td class="px-4 py-3 text-sm text-light-text dark:text-dark-text">${t.created_at ?? '-'}</td>
                    `;
                    frag.appendChild(tr);
                });
                $('previewTableBody').appendChild(frag);

            } catch (err) {
                console.error(err);
                hide('previewLoading');
                $('previewTableBody').innerHTML = `
                    <tr>
                        <td colspan="10" class="px-4 py-10 text-center text-sm text-red-500">
                            Gagal memuat preview
                        </td>
                    </tr>`;
            }
        });

        $('closePreview').addEventListener('click', closePreviewModal);
        $('closePreviewX').addEventListener('click', closePreviewModal);
        $('previewModal').addEventListener('click', (e) => {
            if (e.target.id === 'previewModal') closePreviewModal();
        });

        $('confirmDownload').addEventListener('click', () => {
            const start = $('start_date').value;
            const end = $('end_date').value;
            if (!start || !end) return;
            window.location.href = `/api/tickets/export?start_date=${start}&end_date=${end}`;
            closePreviewModal();
        });
    </script>

    <script>
        (() => {
            const startInp = document.getElementById('start_date');
            const endInp = document.getElementById('end_date');
            const preset = document.getElementById('datePreset');

            if (!startInp || !endInp || !preset) return;

            const pad2 = (n) => String(n).padStart(2, '0');
            const formatDate = (d) => `${d.getFullYear()}-${pad2(d.getMonth() + 1)}-${pad2(d.getDate())}`;

            const startOfWeekMonday = (date) => {
                const d = new Date(date);
                const day = d.getDay(); // 0=Sun..6=Sat
                const diff = (day === 0 ? -6 : 1 - day);
                d.setDate(d.getDate() + diff);
                d.setHours(0, 0, 0, 0);
                return d;
            };

            const endOfWeekSunday = (date) => {
                const s = startOfWeekMonday(date);
                const e = new Date(s);
                e.setDate(s.getDate() + 6);
                e.setHours(0, 0, 0, 0);
                return e;
            };

            function applyPreset(type) {
                const today = new Date();
                let start, end;

                switch (type) {
                    case 'today': {
                        start = new Date(today);
                        end = new Date(today);
                        break;
                    }

                    case 'last_week': {
                        const lastWeekRef = new Date(today);
                        lastWeekRef.setDate(today.getDate() - 7);
                        start = startOfWeekMonday(lastWeekRef);
                        end = endOfWeekSunday(lastWeekRef);
                        break;
                    }

                    case 'this_month': {
                        start = new Date(today.getFullYear(), today.getMonth(), 1);
                        end = new Date(today.getFullYear(), today.getMonth() + 1, 0);
                        break;
                    }

                    case 'last_month': {
                        start = new Date(today.getFullYear(), today.getMonth() - 1, 1);
                        end = new Date(today.getFullYear(), today.getMonth(), 0);
                        break;
                    }

                    case 'this_year': {
                        start = new Date(today.getFullYear(), 0, 1);
                        end = new Date(today.getFullYear(), 11, 31);
                        break;
                    }

                    case 'last_year': {
                        start = new Date(today.getFullYear() - 1, 0, 1);
                        end = new Date(today.getFullYear() - 1, 11, 31);
                        break;
                    }

                    default:
                        return; // Custom
                }

                startInp.value = formatDate(start);
                endInp.value = formatDate(end);
            }

            preset.addEventListener('change', () => {
                if (preset.value) applyPreset(preset.value);
            });

            [startInp, endInp].forEach(inp => {
                inp.addEventListener('change', () => {
                    preset.value = '';
                });
            });
        })();
    </script>




    {{-- ================= Tickets by Support (FAST) ================= --}}
    <script>
        async function loadTickets() {
            const date = $('date').value;
            const url = date ? `/api/tickets-by-support?date=${date}` : `/api/tickets-by-support`;

            const container = $('ticketsContainer');
            container.innerHTML = `
                <div class="col-span-full text-center text-sm italic text-light-text-muted dark:text-dark-text-secondary py-8">
                    Loading...
                </div>
            `;

            try {
                const res = await fetch(url);
                const json = await res.json();
                const supports = json.data || [];

                if (!supports.length) {
                    container.innerHTML = `
                        <div class="col-span-full text-center text-sm italic text-light-text-muted dark:text-dark-text-secondary py-8">
                            Tidak ada data support
                        </div>`;
                    return;
                }

                const frag = document.createDocumentFragment();

                supports.forEach(support => {
                    const card = document.createElement('div');
                    card.className = `
                        rounded-2xl border p-4 shadow-sm
                        bg-light-eval-2 dark:bg-dark-eval-2
                        border-light-eval-3 dark:border-dark-eval-2
                    `;

                    const tickets = support.tickets || [];
                    const ticketHtml = tickets.length ?
                        tickets.map(t => `
                            <div class="rounded-xl border p-3
                                        bg-light-eval-1 dark:bg-dark-eval-1
                                        border-light-eval-3 dark:border-dark-eval-2">
                                <div class="text-sm text-light-text dark:text-dark-text">
                                    <span class="font-semibold">Kode:</span> ${t.ticket_code ?? '-'}
                                </div>
                                <div class="text-sm text-light-text-secondary dark:text-dark-text-secondary mt-1">
                                    <span class="font-semibold">Problem:</span> ${t.problem ?? '-'}
                                </div>
                                <div class="text-sm text-light-text-secondary dark:text-dark-text-secondary mt-1">
                                    <span class="font-semibold">Solution:</span> ${t.solution ?? '-'}
                                </div>
                            </div>
                        `).join('') :
                        `<p class="text-center text-sm italic text-light-text-muted dark:text-dark-text-secondary">No Data Available</p>`;

                    card.innerHTML = `
                        <div class="flex items-center justify-between mb-3">
                            <div class="font-bold text-light-text dark:text-dark-text">${support.support_name ?? '-'}</div>
                            <span class="text-xs px-2 py-1 rounded-lg bg-blue-600/10 text-blue-700 dark:bg-blue-400/10 dark:text-blue-300">
                                ${tickets.length} ticket
                            </span>
                        </div>
                        <div class="space-y-2 max-h-[320px] overflow-y-auto pr-1">
                            ${ticketHtml}
                        </div>
                    `;
                    frag.appendChild(card);
                });

                container.innerHTML = '';
                container.appendChild(frag);

            } catch (err) {
                console.error(err);
                container.innerHTML = `
                    <div class="col-span-full text-center text-red-500 py-8">
                        Gagal memuat tiket
                    </div>`;
            }
        }

        $('filterBtn').addEventListener('click', loadTickets);
        loadTickets();
    </script>

    {{-- ================= Charts (Bar + Time) - faster + no refetch on dark toggle ================= --}}
    <script type="module">
        const $ = (id) => document.getElementById(id);
        const show = (id) => $(id).classList.remove('hidden');
        const hide = (id) => $(id).classList.add('hidden');
        const flexShow = (id) => {
            $(id).classList.remove('hidden');
            $(id).classList.add('flex');
        };
        const flexHide = (id) => {
            $(id).classList.add('hidden');
            $(id).classList.remove('flex');
        };

        const NEUTRAL = '#6B7280';
        const COLORS = ['#3b82f6', '#ef4444', '#10b981', '#f59e0b', '#8b5cf6', '#ec4899', '#06b6d4', '#84cc16', '#f97316',
            '#6366f1'
        ];

        let barChart = null;
        let timeChart = null;

        function fillYears(selectId) {
            const now = new Date().getFullYear();
            const s = $(selectId);
            s.innerHTML = '';
            for (let y = now; y >= now - 5; y--) {
                const o = document.createElement('option');
                o.value = y;
                o.textContent = y;
                if (y === now) o.selected = true;
                s.appendChild(o);
            }
            return now;
        }

        function baseOptions(extra = {}) {
            return {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            color: NEUTRAL,
                            font: {
                                size: 12
                            },
                            padding: 15
                        }
                    },
                    tooltip: {
                        borderColor: NEUTRAL,
                        borderWidth: 1
                    }
                },
                scales: {
                    x: {
                        grid: {
                            color: NEUTRAL
                        },
                        ticks: {
                            color: NEUTRAL,
                            font: {
                                size: 11
                            }
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: NEUTRAL
                        },
                        ticks: {
                            color: NEUTRAL,
                            font: {
                                size: 11
                            }
                        }
                    }
                },
                ...extra
            };
        }

        async function loadChart({
            url,
            canvasId,
            containerId,
            loadingId,
            noDataId,
            instance,
            buildDatasets,
            options
        }) {
            flexShow(loadingId);
            hide(containerId);
            flexHide(noDataId);

            if (instance) instance.destroy();

            try {
                const res = await fetch(url);
                const json = await res.json();

                flexHide(loadingId);

                const raw = json?.data;
                if (!json?.success || !raw?.labels || !raw?.datasets?.length) {
                    flexShow(noDataId);
                    return null;
                }

                // if all zero
                const allZero = raw.datasets.every(ds => (ds.data || []).every(v => (v || 0) === 0));
                if (allZero) {
                    flexShow(noDataId);
                    return null;
                }

                show(containerId);

                const datasets = buildDatasets(raw.datasets);

                return new Chart($(canvasId), {
                    type: 'bar',
                    data: {
                        labels: raw.labels,
                        datasets
                    },
                    options: options
                });

            } catch (e) {
                console.error(e);
                flexHide(loadingId);
                flexShow(noDataId);
                return null;
            }
        }

        async function loadBar(year) {
            barChart = await loadChart({
                url: `/api/chart-tickets-by-dev?year=${year}`,
                canvasId: 'myBarChart',
                containerId: 'barChartContainer',
                loadingId: 'barLoadingIndicator',
                noDataId: 'barNoDataMessage',
                instance: barChart,
                buildDatasets: (datasets) => datasets.map((ds, i) => ({
                    ...ds,
                    backgroundColor: COLORS[i % COLORS.length],
                    borderColor: COLORS[i % COLORS.length],
                    borderWidth: 2
                })),
                options: baseOptions()
            });
        }

        async function loadTime(year) {
            timeChart = await loadChart({
                url: `/api/chart-time-spent-by-dev?year=${year}`,
                canvasId: 'timeSpentChart',
                containerId: 'timeChartContainer',
                loadingId: 'timeLoadingIndicator',
                noDataId: 'timeNoDataMessage',
                instance: timeChart,
                buildDatasets: (datasets) => datasets.map((ds, i) => {
                    const color = (ds.label === 'Total Time Spent') ? NEUTRAL : COLORS[i % COLORS
                        .length];
                    return {
                        ...ds,
                        backgroundColor: color,
                        borderColor: color,
                        borderWidth: 2
                    };
                }),
                options: baseOptions({
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: (ctx) => {
                                    const m = ctx.raw || 0;
                                    const h = Math.floor(m / 60);
                                    const mm = m % 60;
                                    return `${ctx.dataset.label}: ${h} jam ${mm} menit`;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: NEUTRAL
                            },
                            ticks: {
                                color: NEUTRAL,
                                callback: (v) => {
                                    const h = Math.floor(v / 60);
                                    const mm = v % 60;
                                    return `${h}j ${mm}m`;
                                }
                            }
                        },
                        x: {
                            grid: {
                                color: NEUTRAL
                            },
                            ticks: {
                                color: NEUTRAL
                            }
                        }
                    }
                })
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            const currentYear = fillYears('bar_year');
            fillYears('time_year');

            const barYear = $('bar_year');
            const timeYear = $('time_year');

            $('filterBarBtn').addEventListener('click', () => loadBar(barYear.value));
            $('resetBarBtn').addEventListener('click', () => {
                barYear.value = currentYear;
                loadBar(currentYear);
            });

            $('filterTimeBtn').addEventListener('click', () => loadTime(timeYear.value));
            $('resetTimeBtn').addEventListener('click', () => {
                timeYear.value = currentYear;
                loadTime(currentYear);
            });

            loadBar(currentYear);
            loadTime(currentYear);

            // dark toggle: update only (no refetch)
            new MutationObserver(() => {
                if (barChart) barChart.update();
                if (timeChart) timeChart.update();
            }).observe(document.documentElement, {
                attributes: true,
                attributeFilter: ['class']
            });
        });
    </script>

</x-app-layout>
