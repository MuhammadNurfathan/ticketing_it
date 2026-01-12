<x-app-layout>

    {{-- HEADER --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 dark:text-white text-center sm:text-left">
            Project Tracking
        </h2>
    </x-slot>

    {{-- MAIN CONTENT --}}
    <div class="min-h-screen bg-light-bg dark:bg-dark-bg">
        <div class="w-full px-4 sm:px-6 lg:px-8 py-6 space-y-6">

            {{-- DOWNLOAD DATA PROJECT --}}
            <div
                class="bg-white dark:bg-dark-eval-1 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="p-4 sm:p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">
                        Download Data Project
                    </h2>

                    <form class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
                        {{-- TANGGAL MULAI --}}
                        <div>
                            <label class="block text-sm font-medium mb-1">Tanggal Mulai</label>
                            <input type="date" id="start_date"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 
                           bg-white dark:bg-dark-eval-2 px-3 py-2 text-sm">
                        </div>

                        {{-- TANGGAL AKHIR --}}
                        <div>
                            <label class="block text-sm font-medium mb-1">Tanggal Akhir</label>
                            <input type="date" id="end_date"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 
                           bg-white dark:bg-dark-eval-2 px-3 py-2 text-sm">
                        </div>

                        {{-- PRESET --}}
                        <div>
                            <label class="block text-sm font-medium mb-1">Preset Tanggal</label>
                            <select id="datePreset"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 
                           bg-white dark:bg-dark-eval-2 px-3 py-2 text-sm">
                                <option value="this_month" selected>Bulan Ini</option>
                                <option value="today">Hari Ini</option>
                                <option value="last_7_days">7 Hari Terakhir</option>
                                <option value="">Custom</option>
                            </select>
                        </div>


                        {{-- PREVIEW --}}
                        <div>
                            <button type="button" id="previewBtn"
                                class="w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 
                           text-white rounded-lg text-sm font-medium">
                                Preview
                            </button>
                        </div>

                    </form>
                </div>
            </div>

            {{-- MODAL PREVIEW --}}
            <div id="previewModal"
                class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
                <div
                    class="bg-white dark:bg-dark-eval-1 rounded-2xl shadow-2xl w-11/12 max-w-6xl transform scale-95 transition-all">

                    {{-- HEADER MODAL --}}
                    <div
                        class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Preview Data Project</h3>
                        <button id="closePreview"
                            class="text-gray-500 hover:text-red-500 text-2xl font-bold transition-colors">✕</button>
                    </div>

                    {{-- BODY MODAL --}}
                    <div
                        class="overflow-x-auto overflow-y-auto max-h-[60vh] border-b border-gray-200 dark:border-gray-700">
                        <table class="min-w-full text-sm">
                            <thead class="bg-gray-50 dark:bg-dark-eval-2 sticky top-0 z-10">
                                <tr>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase">
                                        Project Code</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase">
                                        Project Name</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase">
                                        Requestor</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase">
                                        Priority</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase">
                                        Status</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase">
                                        Developer</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase">
                                        Status</th>
                                    <th
                                        class="px-4 py-3 text-center text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase">
                                        Progress Date</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase">
                                        Progress Percent</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase">
                                        Deskripsi</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase">
                                        Start Date</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase">
                                        End Date</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase">
                                        Late </th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase">
                                        Notes </th>
                                </tr>
                            </thead>
                            <tbody id="previewModalBody"
                                class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-dark-eval-1">
                            </tbody>
                        </table>
                    </div>

                    {{-- FOOTER MODAL --}}
                    <div class="px-6 py-4 flex justify-end gap-3">
                        <button id="closePreviewBtn"
                            class="px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-dark-eval-2 transition-colors text-sm font-medium">
                            Tutup
                        </button>
                        <button id="confirmDownload"
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors text-sm font-medium">
                            💾 Download
                        </button>
                    </div>

                </div>
            </div>

            {{-- PROJECT BY DEVELOPER --}}
            <div
                class="bg-white dark:bg-dark-eval-1 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="p-4 sm:p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">
                        Project by Developer (Per Hari)
                    </h2>

                    <div class="flex flex-col sm:flex-row gap-3 mb-6">
                        <input type="date" id="projectDate"
                            class="flex-1 rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-dark-eval-2 text-gray-900 dark:text-white shadow-sm focus:ring-2 focus:ring-blue-400 text-sm px-3 py-2">
                        <button id="projectFilterBtn"
                            class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium text-sm transition-colors">
                            Filter
                        </button>
                    </div>

                    <div id="projectsContainer"
                        class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-6"></div>
                </div>
            </div>

        </div>
    </div>

    <script>
        const preset = document.getElementById('datePreset');
        const startInp = document.getElementById('start_date');
        const endInp = document.getElementById('end_date');

        function formatDateLocal(date) {
            const y = date.getFullYear();
            const m = String(date.getMonth() + 1).padStart(2, '0');
            const d = String(date.getDate()).padStart(2, '0');
            return `${y}-${m}-${d}`;
        }


        function applyPreset(type) {
            const today = new Date();
            let start, end;

            switch (type) {
                case 'today':
                    start = end = today;
                    break;

                case 'this_month':
                    start = new Date(today.getFullYear(), today.getMonth(), 1);
                    end = new Date(today.getFullYear(), today.getMonth() + 1, 0);
                    break;


                case 'last_7_days':
                    start = new Date();
                    start.setDate(today.getDate() - 6);
                    end = today;
                    break;

                case 'next_7_days':
                    start = today;
                    end = new Date();
                    end.setDate(today.getDate() + 6);
                    break;

                default:
                    return; // custom
            }

            startInp.value = formatDateLocal(start);
            endInp.value = formatDateLocal(end);
        }

        // default load → Bulan Ini
        applyPreset(preset.value);

        // change preset
        preset.addEventListener('change', () => {
            if (preset.value) {
                applyPreset(preset.value);
            }
        });

        // kalau user edit manual → set ke Custom
        [startInp, endInp].forEach(input => {
            input.addEventListener('change', () => {
                preset.value = '';
            });
        });
    </script>


    <script>
        // Load Projects by Developer
        async function loadProjects() {
            const date = document.getElementById('projectDate').value;
            const url = date ? `/api/projects-by-developer?date=${date}` : `/api/projects-by-developer`;

            try {
                const res = await fetch(url);
                const json = await res.json();
                const developers = json.data || [];
                const container = document.getElementById('projectsContainer');
                let html = '';

                developers.forEach(dev => {
                    html += `
            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 bg-gray-50 dark:bg-dark-eval-2 flex flex-col">
                <h3 class="font-bold text-center text-gray-900 dark:text-white mb-3">${dev.developer_name}</h3>

                <div class="flex-1 max-h-[300px] overflow-y-auto space-y-2 pr-2 scrollbar-thin scrollbar-thumb-blue-500 scrollbar-track-gray-200 dark:scrollbar-thumb-blue-400 dark:scrollbar-track-dark-eval-2">
                    ${
                        dev.projects.length
                        ? dev.projects.map(p => `
                                        <div class="bg-white dark:bg-dark-eval-1 p-3 border border-gray-200 dark:border-gray-700 rounded-lg">
                                            <div class="space-y-1.5">
                                                <p class="text-sm text-gray-700 dark:text-gray-300">
                                                    <span class="font-semibold">Project Code:</span> ${p.project_code}
                                                </p>
                                                <p class="text-sm text-gray-700 dark:text-gray-300">
                                                    <span class="font-semibold">Project Name:</span> ${p.project_name}
                                                </p>
                                                <p class="text-sm text-gray-700 dark:text-gray-300">
                                                    <span class="font-semibold">Status:</span> 
                                                    <span class="text-blue-600 dark:text-blue-400">${p.status}</span>
                                                </p>
                                                <p class="text-sm text-gray-700 dark:text-gray-300">
                                                    <span class="font-semibold">Progress:</span> 
                                                    <span class="font-bold text-blue-600 dark:text-blue-400">${p.progress}%</span>
                                                </p>
                                                <p class="text-sm text-gray-700 dark:text-gray-300">
                                                    <span class="font-semibold">Progress Date:</span> 
                                                    <span class="text-gray-600 dark:text-gray-400">${p.progress_date}</span>
                                                </p>
                                                <p class="text-sm text-gray-700 dark:text-gray-300">
                                                    <span class="font-semibold">Memo:</span> ${p.memo || '-'}
                                                </p>
                                            </div>
                                        </div>
                                    `).join('')
                        : `<p class="text-center text-gray-400 dark:text-gray-500 italic">No Data Available</p>`
                    }
                </div>
            </div>
            `;
                });

                container.innerHTML = html ||
                    `<p class="text-center col-span-full text-gray-400 italic">Tidak ada data project</p>`;

            } catch (err) {
                console.error(err);
                document.getElementById('projectsContainer').innerHTML =
                    '<p class="text-center text-red-500">Gagal memuat data project</p>';
            }
        }

        document.getElementById('projectFilterBtn').addEventListener('click', loadProjects);
        loadProjects();

        // Modal Preview Script
        document.addEventListener('DOMContentLoaded', () => {
            const previewBtn = document.getElementById('previewBtn');
            const modal = document.getElementById('previewModal');
            const body = document.getElementById('previewModalBody');
            const closeBtn = document.getElementById('closePreview');
            const closeBtnFooter = document.getElementById('closePreviewBtn');
            const downloadBtn = document.getElementById('confirmDownload');

            function closeModal() {
                modal.classList.add('hidden');
            }

            previewBtn.addEventListener('click', async () => {
                const start = start_date.value;
                const end = end_date.value;

                if (!start || !end) {
                    alert('Isi tanggal mulai dan akhir terlebih dahulu');
                    return;
                }

                body.innerHTML =
                    `<tr><td colspan="14" class="text-center py-6 italic text-gray-500">Loading data...</td></tr>`;
                modal.classList.remove('hidden');

                try {
                    const res = await fetch(
                        `/api/projects/preview?start_date=${start}&end_date=${end}`);
                    const json = await res.json();
                    body.innerHTML = '';

                    if (!json.data || json.data.length === 0) {
                        body.innerHTML =
                            `<tr><td colspan="14" class="text-center py-6 italic text-gray-500">Tidak ada data untuk periode ini</td></tr>`;
                        return;
                    }

                    json.data.forEach(p => {
                        body.insertAdjacentHTML('beforeend', `
                    <tr class="hover:bg-gray-50 dark:hover:bg-dark-eval-2">
                        <td class="px-4 py-3 text-gray-900 dark:text-gray-100">${p.project_code}</td>
                        <td class="px-4 py-3 text-gray-900 dark:text-gray-100">${p.project_name}</td>
                        <td class="px-4 py-3 text-gray-900 dark:text-gray-100">${p.requestor}</td>
                        <td class="px-4 py-3 text-gray-900 dark:text-gray-100">${p.priority}</td>
                        <td class="px-4 py-3 text-gray-900 dark:text-gray-100">${p.project_status}</td>
                        <td class="px-4 py-3 text-gray-900 dark:text-gray-100">${p.developer_name}</td>
                        <td class="px-4 py-3 text-gray-900 dark:text-gray-100">${p.detail_status}</td>
                        <td class="px-4 py-3 text-gray-900 dark:text-gray-100">${p.progress_date}</td>
                        <td class="px-4 py-3 text-center font-bold text-blue-600 dark:text-blue-400">${p.progress}%</td>
                        <td class="px-4 py-3 text-gray-900 dark:text-gray-100">${p.memo}</td>
                        <td class="px-4 py-3 text-gray-900 dark:text-gray-100">${p.start_date}</td>
                        <td class="px-4 py-3 text-gray-900 dark:text-gray-100">${p.end_date}</td>
                        <td class="px-4 py-3 text-gray-900 dark:text-gray-100">${p.is_late}</td>
                        <td class="px-4 py-3 text-gray-900 dark:text-gray-100">${p.notes}</td>
                    </tr>
                `);
                    });
                } catch (err) {
                    console.error(err);
                    body.innerHTML =
                        `<tr><td colspan="14" class="text-center py-6 text-red-500">Error: ${err.message}</td></tr>`;
                }
            });

            closeBtn.addEventListener('click', closeModal);
            closeBtnFooter.addEventListener('click', closeModal);
            modal.addEventListener('click', e => e.target === modal && closeModal());

            downloadBtn.addEventListener('click', () => {
                const start = start_date.value;
                const end = end_date.value;

                if (!start || !end) {
                    alert('Isi tanggal mulai dan akhir terlebih dahulu');
                    return;
                }

                window.location.href = `/api/projects/export?start_date=${start}&end_date=${end}`;
                closeModal();
            });
        });
    </script>
</x-app-layout>
