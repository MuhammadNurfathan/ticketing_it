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

                    <form class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-1">Tanggal Mulai</label>
                            <input type="date" id="start_date"
                                class="date-input w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-dark-eval-2 px-3 py-2 text-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Tanggal Akhir</label>
                            <input type="date" id="end_date"
                                class="date-input w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-dark-eval-2 px-3 py-2 text-sm">
                        </div>

                        <div class="flex items-end">
                            <button type="button" id="previewBtn"
                                class="w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium">
                                Preview
                            </button>
                        </div>
                    </form>
                </div>
            </div>


            {{-- MODAL PREVIEW - STRUKTUR YANG BENER --}}
            <div id="previewModal"
                class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm min-h-screen w-screen">
                <div
                    class="bg-white dark:bg-dark-eval-1 rounded-2xl shadow-2xl w-11/12 max-w-6xl transform scale-95 transition-all">
                    
                    {{-- HEADER MODAL - FIXED --}}
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex justify-between items-center">
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Preview Data Project</h3>
                            <button id="closePreview" class="text-gray-500 hover:text-red-500 text-2xl font-bold transition-colors">
                                ✕
                            </button>
                        </div>
                    </div>

                    {{-- BODY MODAL - SCROLLABLE --}}
                    <div class="overflow-x-auto overflow-y-auto max-h-[60vh] border-b border-gray-200 dark:border-gray-700">
                        <table class="min-w-full text-sm">
                            <thead class="bg-gray-50 dark:bg-dark-eval-2 sticky top-0 z-10">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase">Code</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase">Project</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase">Requestor</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase">Priority</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase">Status</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase">Developer</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase">Detail</th>
                                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase">Progress</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase">Memo</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase">Date</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase">Late</th>
                                </tr>
                            </thead>
                            <tbody id="previewModalBody"
                                class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-dark-eval-1">
                            </tbody>
                        </table>
                    </div>

                    {{-- FOOTER MODAL - FIXED --}}
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

                    <div id="projectsContainer" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-6">
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {

            const previewBtn = document.getElementById('previewBtn');
            const modal = document.getElementById('previewModal');
            const body = document.getElementById('previewModalBody');
            const closeBtn = document.getElementById('closePreview');
            const closeBtnFooter = document.getElementById('closePreviewBtn');
            const downloadBtn = document.getElementById('confirmDownload');

            previewBtn.addEventListener('click', async () => {
                const start = start_date.value;
                const end = end_date.value;

                body.innerHTML = `
            <tr>
                <td colspan="11" class="text-center py-6 italic text-gray-500">
                    Loading data...
                </td>
            </tr>
        `;

                modal.classList.remove('hidden');

                const res = await fetch(`/api/projects/preview?start_date=${start}&end_date=${end}`);
                const json = await res.json();

                body.innerHTML = '';

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
                    <td class="px-4 py-3 text-center font-bold text-blue-600 dark:text-blue-400">${p.progress}%</td>
                    <td class="px-4 py-3 text-gray-900 dark:text-gray-100">${p.memo ?? '-'}</td>
                    <td class="px-4 py-3 text-gray-900 dark:text-gray-100">${p.progress_date}</td>
                    <td class="px-4 py-3 text-gray-900 dark:text-gray-100">${p.is_late}</td>
                </tr>
            `);
                });
            });

            function closeModal() {
                modal.classList.add('hidden');
            }

            closeBtn.addEventListener('click', closeModal);
            closeBtnFooter.addEventListener('click', closeModal);
            modal.addEventListener('click', e => e.target === modal && closeModal());

            downloadBtn.addEventListener('click', () => {
                window.location.href =
                    `/api/projects/export?start_date=${start_date.value}&end_date=${end_date.value}`;
            });

        });
    </script>


    <script>
        async function loadProjects() {
            const date = document.getElementById('projectDate').value;
            const url = date ?
                `/api/projects-by-developer?date=${date}` :
                `/api/projects-by-developer`;

            try {
                const res = await fetch(url);
                const json = await res.json();
                const developers = json.data || [];

                const container = document.getElementById('projectsContainer');
                let html = '';

                developers.forEach(dev => {
                    html += `
            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 bg-gray-50 dark:bg-dark-eval-2">
                <h3 class="font-bold text-center text-gray-900 dark:text-white mb-3">
                    ${dev.developer_name}
                </h3>

                <div class="space-y-2">
                    ${
                        dev.projects.length
                        ? dev.projects.map(p => `
                                                    <div class="bg-white dark:bg-dark-eval-1 p-3 border border-gray-200 dark:border-gray-700 rounded-lg">
                                                        <p class="text-sm text-gray-700 dark:text-gray-300">
                                                            <b>Project Code:</b> ${p.project_code}
                                                        </p>
                                                        <p class="text-sm text-gray-700 dark:text-gray-300">
                                                            <b>Project Name:</b> ${p.project_name}
                                                        </p>
                                                        <p class="text-sm text-gray-700 dark:text-gray-300">
                                                            <b>Memo:</b> ${p.memo ? p.memo : '-'}
                                                        </p>

                                                        <div class="flex items-center justify-between mt-2 text-sm">
                                                            <span class="text-gray-600 dark:text-gray-400">
                                                                <b>Status:</b> ${p.status}
                                                            </span>
                                                            <span class="font-semibold text-blue-600 dark:text-blue-400">
                                                                ${p.progress}%
                                                            </span>
                                                        </div>
                                                    </div>
                                                `).join('')
                        : `<p class="text-center text-gray-400 dark:text-gray-500 italic">
                                                    No Data Available
                                                   </p>`
                    }
                </div>
            </div>
            `;
                });

                container.innerHTML = html || `
            <p class="text-center col-span-full text-gray-400 italic">
                Tidak ada data project
            </p>
        `;

            } catch (err) {
                console.error(err);
                document.getElementById('projectsContainer').innerHTML =
                    '<p class="text-center text-red-500">Gagal memuat data project</p>';
            }
        }

        document
            .getElementById('projectFilterBtn')
            .addEventListener('click', loadProjects);

        loadProjects();
    </script>

</x-app-layout>