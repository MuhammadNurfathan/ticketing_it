<x-app-layout>

    {{-- HEADER --}}
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-light-text dark:text-dark-text leading-tight">
                    Project Tracking
                </h2>
                <p class="text-xs mt-1 text-light-text-secondary dark:text-dark-text-secondary">
                    Export project + monitoring daily developer progress
                </p>
            </div>
        </div>
    </x-slot>

    @php
        $page = 'min-h-screen bg-light-bg dark:bg-dark-bg';
        $wrap = 'w-full px-4 sm:px-6 lg:px-8 py-6 space-y-6';

        $card = "rounded-2xl border shadow-sm overflow-hidden
                 bg-light-eval-1 dark:bg-dark-eval-1
                 border-light-eval-3 dark:border-dark-eval-2";

        $cardHead = "p-4 sm:p-6 border-b
                     border-light-eval-3 dark:border-dark-eval-2";

        $title = 'text-lg font-semibold text-light-text dark:text-dark-text';
        $sub = 'text-sm text-light-text-secondary dark:text-dark-text-secondary mt-1';

        $label = 'block text-sm font-medium mb-1 text-light-text-secondary dark:text-dark-text-secondary';

        $input = "w-full rounded-lg border px-3 py-2 text-sm
                  bg-light-bg dark:bg-dark-eval-2
                  text-light-text dark:text-dark-text
                  border-light-eval-3 dark:border-dark-eval-2
                  placeholder:text-light-text-muted dark:placeholder:text-dark-text-secondary
                  focus:ring-2 focus:ring-blue-500/25 focus:border-blue-500/40";

        $btn = "inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg text-sm font-semibold
                bg-blue-600 hover:bg-blue-700 text-white transition-colors shadow-sm";

        $btnGhost = "inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg text-sm font-semibold
                     bg-light-eval-2 dark:bg-dark-eval-2
                     text-light-text-secondary dark:text-dark-text-secondary
                     hover:bg-light-eval-3 dark:hover:bg-dark-eval-3 transition-colors";

        $thead = "bg-light-eval-2 dark:bg-dark-eval-2 border-b
                  border-light-eval-3 dark:border-dark-eval-2";

        $th = "px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider
               text-light-text-secondary dark:text-dark-text-secondary";
    @endphp

    <div class="{{ $page }}">
        <div class="{{ $wrap }}">

            {{-- ===================== DOWNLOAD DATA PROJECT ===================== --}}
            <div class="{{ $card }}">
                <div class="{{ $cardHead }}">
                    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
                        <div>
                            <h2 class="{{ $title }}">Download Data Project</h2>
                            <p class="{{ $sub }}">Pilih periode, preview, lalu download</p>
                        </div>

                        <div
                            class="hidden sm:flex items-center gap-2 text-xs text-light-text-muted dark:text-dark-text-secondary">
                            <span
                                class="px-2 py-1 rounded-lg border border-light-eval-3 dark:border-dark-eval-2
                                        bg-light-bg dark:bg-dark-eval-2">
                                Tips: preset → cepat
                            </span>
                        </div>
                    </div>
                </div>

                <div class="p-4 sm:p-6">
                    <form class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
                        <div>
                            <label class="{{ $label }}">Tanggal Mulai</label>
                            <input type="date" id="start_date" class="{{ $input }} date-input">
                        </div>

                        <div>
                            <label class="{{ $label }}">Tanggal Akhir</label>
                            <input type="date" id="end_date" class="{{ $input }} date-input">
                        </div>

                        <div>
                            <label class="{{ $label }}">Preset Tanggal</label>
                            <select id="datePreset" class="{{ $input }}">
                                <option value="">Custom</option>
                                <option value="today">Hari Ini</option>
                                <option value="last_week">Last Week</option>
                                <option value="this_month" selected>This Month</option>
                                <option value="last_month">Last Month</option>
                                <option value="this_year">This Year</option>
                                <option value="last_year">Last Year</option>
                            </select>

                        </div>

                        <div class="flex gap-3">
                            <button type="button" id="previewBtn" class="{{ $btn }} w-full">
                                Preview
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- ===================== MODAL PREVIEW (SAME AS BEFORE) ===================== --}}
            <div id="previewModal" class="hidden fixed inset-0 z-50 bg-black/50 backdrop-blur-sm">
                <div class="min-h-full flex items-center justify-center p-4">
                    <div id="previewModalPanel"
                        class="{{ $card }} w-full max-w-6xl transform scale-95 opacity-0 transition duration-200">

                        <div class="{{ $cardHead }} flex items-center justify-between gap-3">
                            <div>
                                <h3 class="text-lg font-semibold text-light-text dark:text-dark-text">Preview Data
                                    Project</h3>
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
                                {{-- LOADING OVERLAY (SAMA) --}}
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
                                        <thead class="{{ $thead }} sticky top-0 z-10">
                                            <tr>
                                                @foreach (['Project Code', 'Project Name', 'Requestor', 'Priority', 'Project Status', 'Developer', 'Detail Status', 'Progress Date', 'Progress %', 'Memo', 'Start Date', 'End Date', 'Late', 'Notes'] as $head)
                                                    <th class="{{ $th }} whitespace-nowrap">
                                                        {{ $head }}</th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody id="previewModalBody"
                                            class="divide-y divide-light-eval-3 dark:divide-dark-eval-2
                                                   bg-light-eval-1 dark:bg-dark-eval-1">
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="mt-6 flex flex-col sm:flex-row justify-end gap-3">
                                <button id="closePreviewBtn" class="{{ $btnGhost }}">Tutup</button>
                                <button id="confirmDownload" class="{{ $btn }}">💾 Download</button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- ===================== PROJECT BY DEVELOPER (SAME STYLE) ===================== --}}
            <div class="{{ $card }}">
                <div class="{{ $cardHead }}">
                    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
                        <div>
                            <h2 class="{{ $title }}">Project by Developer (Per Hari)</h2>
                            <p class="{{ $sub }}">Filter tanggal untuk lihat progress harian</p>
                        </div>

                        <div class="flex items-center gap-3">
                            <input type="date" id="projectDate"
                                class="{{ $input }} date-input min-w-[180px]">
                            <button id="projectFilterBtn" class="{{ $btn }}">Filter</button>
                        </div>
                    </div>
                </div>

                <div class="p-4 sm:p-6">
                    {{-- LOADING OVERLAY CONTAINER (SAMA STYLE) --}}
                    <div id="projectsLoadingWrap"
                        class="hidden rounded-2xl border p-6 text-center
                                bg-light-eval-2 dark:bg-dark-eval-2
                                border-light-eval-3 dark:border-dark-eval-2">
                        <div class="flex flex-col items-center gap-3">
                            <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-blue-600"></div>
                            <p class="text-sm text-light-text-secondary dark:text-dark-text-secondary">Loading...</p>
                        </div>
                    </div>

                    <div id="projectsContainer"
                        class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-4">
                    </div>
                </div>
            </div>

        </div>
    </div>

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

        .mini-scroll::-webkit-scrollbar {
            width: 8px;
        }

        .mini-scroll::-webkit-scrollbar-thumb {
            border-radius: 999px;
        }

        .mini-scroll::-webkit-scrollbar-track {
            background: transparent;
        }
    </style>

    <script>
        // ===== helpers (same pattern) =====
        const $ = (id) => document.getElementById(id);
        const show = (id) => $(id).classList.remove('hidden');
        const hide = (id) => $(id).classList.add('hidden');

        function openPreviewModal() {
            const modal = $('previewModal');
            const panel = $('previewModalPanel');
            modal.classList.remove('hidden');
            requestAnimationFrame(() => {
                panel.classList.remove('opacity-0', 'scale-95');
                panel.classList.add('opacity-100', 'scale-100');
            });
        }

        function closePreviewModal() {
            const modal = $('previewModal');
            const panel = $('previewModalPanel');
            panel.classList.remove('opacity-100', 'scale-100');
            panel.classList.add('opacity-0', 'scale-95');
            setTimeout(() => modal.classList.add('hidden'), 180);
        }
    </script>

    <script>
        (() => {
            const preset = document.getElementById('datePreset');
            const startInp = document.getElementById('start_date');
            const endInp = document.getElementById('end_date');

            if (!preset || !startInp || !endInp) return;

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
                        return; // custom
                }

                startInp.value = formatDate(start);
                endInp.value = formatDate(end);
            }

            // apply default preset saat load (misalnya This Month selected)
            if (preset.value) applyPreset(preset.value);

            preset.addEventListener('change', () => {
                if (preset.value) applyPreset(preset.value);
            });

            // kalau user ubah manual date, set preset jadi custom
            [startInp, endInp].forEach(inp => {
                inp.addEventListener('change', () => {
                    preset.value = '';
                });
            });
        })();
    </script>


    <script>
        // ===== Projects by Developer (FAST + SAME DESIGN) =====
        function badgeStatus(status = '') {
            const s = (status || '').toLowerCase();
            if (s.includes('done')) return 'bg-green-600/10 text-green-700 dark:bg-green-400/10 dark:text-green-300';
            if (s.includes('progress')) return 'bg-blue-600/10 text-blue-700 dark:bg-blue-400/10 dark:text-blue-300';
            if (s.includes('wait')) return 'bg-yellow-500/15 text-yellow-700 dark:bg-yellow-400/10 dark:text-yellow-300';
            if (s.includes('void')) return 'bg-red-600/10 text-red-700 dark:bg-red-400/10 dark:text-red-300';
            return 'bg-light-eval-2 text-light-text-secondary dark:bg-dark-eval-2 dark:text-dark-text-secondary';
        }

        async function loadProjects() {
            const date = $('projectDate').value;
            const url = date ? `/api/projects-by-developer?date=${date}` : `/api/projects-by-developer`;

            const container = $('projectsContainer');
            const loading = $('projectsLoadingWrap');

            container.innerHTML = '';
            show('projectsLoadingWrap');

            try {
                const res = await fetch(url);
                const json = await res.json();
                const developers = json.data || [];

                hide('projectsLoadingWrap');

                if (!developers.length) {
                    container.innerHTML = `
                        <div class="col-span-full rounded-2xl border p-6 text-center text-sm italic
                                    bg-light-eval-2 dark:bg-dark-eval-2
                                    border-light-eval-3 dark:border-dark-eval-2
                                    text-light-text-muted dark:text-dark-text-secondary">
                            Tidak ada data project
                        </div>`;
                    return;
                }

                const frag = document.createDocumentFragment();

                developers.forEach(dev => {
                    const wrap = document.createElement('div');
                    wrap.className = `
                        rounded-2xl border shadow-sm overflow-hidden
                        bg-light-eval-2 dark:bg-dark-eval-2
                        border-light-eval-3 dark:border-dark-eval-2
                    `;

                    const total = dev.projects?.length ?? 0;

                    // header
                    const header = document.createElement('div');
                    header.className = `
                        p-4 border-b
                        border-light-eval-3 dark:border-dark-eval-2
                        bg-light-eval-1 dark:bg-dark-eval-1
                    `;
                    header.innerHTML = `
                        <div class="flex items-center justify-between gap-3">
                            <div class="min-w-0">
                                <div class="font-bold text-light-text dark:text-dark-text truncate">
                                    ${dev.developer_name ?? '-'}
                                </div>
                                <div class="text-xs mt-0.5 text-light-text-muted dark:text-dark-text-secondary">
                                    ${total} project
                                </div>
                            </div>
                            <span class="text-xs px-2 py-1 rounded-lg bg-blue-600/10 text-blue-700 dark:bg-blue-400/10 dark:text-blue-300">
                                👨‍💻
                            </span>
                        </div>
                    `;

                    // body list
                    const body = document.createElement('div');
                    body.className = 'p-4';

                    const list = document.createElement('div');
                    list.className = 'mini-scroll max-h-[320px] overflow-y-auto space-y-3 pr-1';

                    if (total) {
                        dev.projects.forEach(p => {
                            const item = document.createElement('div');
                            item.className = `
                                rounded-xl border p-3
                                bg-light-eval-1 dark:bg-dark-eval-1
                                border-light-eval-3 dark:border-dark-eval-2
                            `;
                            item.innerHTML = `
                                <div class="flex items-start justify-between gap-3">
                                    <div class="min-w-0">
                                        <div class="text-sm font-semibold text-light-text dark:text-dark-text truncate">
                                            ${p.project_name ?? '-'}
                                        </div>
                                        <div class="text-xs mt-0.5 text-light-text-muted dark:text-dark-text-secondary">
                                            Code: <span class="font-medium">${p.project_code ?? '-'}</span>
                                        </div>
                                    </div>
                                    <span class="shrink-0 inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold ${badgeStatus(p.status)}">
                                        ${p.status ?? '-'}
                                    </span>
                                </div>

                                <div class="mt-2 grid grid-cols-2 gap-2 text-xs text-light-text-secondary dark:text-dark-text-secondary">
                                    <div class="rounded-lg border p-2 bg-light-bg dark:bg-dark-eval-2 border-light-eval-3 dark:border-dark-eval-2">
                                        <div class="font-semibold">Progress</div>
                                        <div class="mt-0.5">
                                            <span class="font-bold text-blue-700 dark:text-blue-300">${p.progress ?? 0}%</span>
                                        </div>
                                    </div>
                                    <div class="rounded-lg border p-2 bg-light-bg dark:bg-dark-eval-2 border-light-eval-3 dark:border-dark-eval-2">
                                        <div class="font-semibold">Date</div>
                                        <div class="mt-0.5">${p.progress_date ?? '-'}</div>
                                    </div>
                                </div>

                                <div class="mt-2 text-xs text-light-text-secondary dark:text-dark-text-secondary break-words">
                                    <span class="font-semibold">Memo:</span> ${p.description || '-'}
                                </div>
                            `;
                            list.appendChild(item);
                        });
                    } else {
                        const empty = document.createElement('div');
                        empty.className = `
                            rounded-xl border p-4 text-center text-sm italic
                            bg-light-bg dark:bg-dark-eval-1
                            border-light-eval-3 dark:border-dark-eval-2
                            text-light-text-muted dark:text-dark-text-secondary
                        `;
                        empty.textContent = 'No Data Available';
                        list.appendChild(empty);
                    }

                    body.appendChild(list);

                    wrap.appendChild(header);
                    wrap.appendChild(body);

                    frag.appendChild(wrap);
                });

                container.innerHTML = '';
                container.appendChild(frag);

            } catch (err) {
                console.error(err);
                hide('projectsLoadingWrap');
                container.innerHTML = `
                    <div class="col-span-full rounded-2xl border p-6 text-center text-sm
                                bg-light-eval-2 dark:bg-dark-eval-2
                                border-light-eval-3 dark:border-dark-eval-2
                                text-red-600 dark:text-red-400">
                        Gagal memuat data project
                    </div>`;
            }
        }

        $('projectFilterBtn').addEventListener('click', loadProjects);
        loadProjects();
    </script>

    <script>
        // ===== Modal Preview (FAST + SAME LOADING) =====
        $('previewBtn').addEventListener('click', async () => {
            const start = $('start_date').value;
            const end = $('end_date').value;

            if (!start || !end) {
                alert('Isi tanggal mulai dan akhir terlebih dahulu');
                return;
            }
            if (start > end) {
                alert('Tanggal mulai tidak boleh lebih besar dari tanggal akhir');
                return;
            }

            $('previewRangeText').textContent = `${start} → ${end}`;

            // UI state
            $('previewModalBody').innerHTML = '';
            show('previewLoading');
            openPreviewModal();

            try {
                const res = await fetch(`/api/projects/preview?start_date=${start}&end_date=${end}`);
                const json = await res.json();

                hide('previewLoading');

                const data = json.data || [];
                if (!data.length) {
                    $('previewModalBody').innerHTML = `
                        <tr>
                            <td colspan="14" class="px-4 py-10 text-center text-sm italic
                                text-light-text-muted dark:text-dark-text-secondary">
                                Tidak ada data untuk periode ini
                            </td>
                        </tr>`;
                    return;
                }

                const frag = document.createDocumentFragment();

                data.forEach(p => {
                    const tr = document.createElement('tr');
                    tr.className = 'transition-colors hover:bg-light-eval-2 dark:hover:bg-dark-eval-2';
                    tr.innerHTML = `
                        <td class="px-4 py-3 text-sm text-light-text-secondary dark:text-dark-text-secondary whitespace-nowrap">${p.project_code ?? '-'}</td>
                        <td class="px-4 py-3 text-sm font-semibold text-light-text dark:text-dark-text">${p.project_name ?? '-'}</td>
                        <td class="px-4 py-3 text-sm text-light-text-secondary dark:text-dark-text-secondary">${p.requestor ?? '-'}</td>
                        <td class="px-4 py-3 text-sm text-light-text-secondary dark:text-dark-text-secondary">${p.priority ?? '-'}</td>
                        <td class="px-4 py-3 text-sm text-light-text-secondary dark:text-dark-text-secondary">${p.project_status ?? '-'}</td>
                        <td class="px-4 py-3 text-sm text-light-text-secondary dark:text-dark-text-secondary">${p.developer_name ?? '-'}</td>
                        <td class="px-4 py-3 text-sm text-light-text-secondary dark:text-dark-text-secondary">${p.detail_status ?? '-'}</td>
                        <td class="px-4 py-3 text-sm text-light-text-secondary dark:text-dark-text-secondary whitespace-nowrap">${p.progress_date ?? '-'}</td>
                        <td class="px-4 py-3 text-center text-sm font-bold text-blue-700 dark:text-blue-300 whitespace-nowrap">${p.progress ?? 0}%</td>
                        <td class="px-4 py-3 text-sm text-light-text-secondary dark:text-dark-text-secondary break-words">${p.description ?? '-'}</td>
                        <td class="px-4 py-3 text-sm text-light-text-secondary dark:text-dark-text-secondary whitespace-nowrap">${p.start_date ?? '-'}</td>
                        <td class="px-4 py-3 text-sm text-light-text-secondary dark:text-dark-text-secondary whitespace-nowrap">${p.end_date ?? '-'}</td>
                        <td class="px-4 py-3 text-sm text-light-text-secondary dark:text-dark-text-secondary whitespace-nowrap">${p.is_late ?? '-'}</td>
                        <td class="px-4 py-3 text-sm text-light-text-secondary dark:text-dark-text-secondary break-words">${p.notes ?? '-'}</td>
                    `;
                    frag.appendChild(tr);
                });

                $('previewModalBody').appendChild(frag);

            } catch (err) {
                console.error(err);
                hide('previewLoading');
                $('previewModalBody').innerHTML = `
                    <tr>
                        <td colspan="14" class="px-4 py-10 text-center text-sm text-red-600 dark:text-red-400">
                            Gagal memuat preview
                        </td>
                    </tr>`;
            }
        });

        $('closePreviewX').addEventListener('click', closePreviewModal);
        $('closePreviewBtn').addEventListener('click', closePreviewModal);
        $('previewModal').addEventListener('click', (e) => {
            if (e.target.id === 'previewModal') closePreviewModal();
        });

        $('confirmDownload').addEventListener('click', () => {
            const start = $('start_date').value;
            const end = $('end_date').value;
            if (!start || !end) return;

            window.location.href = `/api/projects/export?start_date=${start}&end_date=${end}`;
            closePreviewModal();
        });
    </script>

</x-app-layout>
