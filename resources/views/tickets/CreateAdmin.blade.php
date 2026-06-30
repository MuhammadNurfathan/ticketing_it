<x-app-layout>
    <x-slot name="header">
        <x-slot name="header">
            <div>
                <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100">
                    Buat Ticket
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Tambahkan ticket baru untuk melaporkan masalah
                </p>
            </div>
        </x-slot>
    </x-slot>

    <div class="py-4">
        <div class="w-full mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-lg rounded-xl">

                <div class="p-6">


                    {{-- Error Alert --}}
                    @if ($errors->any())
                        <div
                            class="bg-red-100 dark:bg-red-900 border border-red-400 dark:border-red-600 text-red-700 dark:text-red-200 px-4 py-3 rounded mb-4">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Form Ticket --}}
                    <form action="{{ route('DashboardTicketsAdmin.store') }}" method="POST"
                        enctype="multipart/form-data" id="ticket-form">
                        @csrf
                        <input type="hidden" name="from" value="admin">



                        {{-- User --}}
                        <div class="mb-4 relative">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Choose Requestor <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="user-search" placeholder="Cari user..." required
                                value="{{ old('user_search') }}"
                                class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <input type="hidden" name="user_id" id="user-id" value="{{ old('user_id') }}" required>
                            <ul id="search-results"
                                class="hidden absolute z-50 w-full border border-gray-300 dark:border-gray-600 rounded-md mt-1 overflow-y-auto bg-white dark:bg-gray-800 shadow-lg max-h-32">
                                @foreach ($users as $user)
                                    <li class="px-3 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer border-b border-gray-100 dark:border-gray-700 text-gray-900 dark:text-gray-100"
                                        data-id="{{ $user->id }}">{{ $user->name }}</li>
                                @endforeach
                            </ul>
                        </div>



                        {{-- IT Support --}}
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                IT Support <span class="text-red-500">*</span>
                            </label>
                            <select name="support_id" required
                                class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="" hidden>-- Choose IT Support --</option>
                                @foreach ($supports as $sup)
                                    <option value="{{ $sup->id }}"
                                        {{ old('support_id') == $sup->id ? 'selected' : '' }}>
                                        {{ $sup->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Problem Category --}}
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Category <span class="text-red-500">*</span>
                            </label>
                            <select name="category_id" required
                                class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="" hidden>-- Choose Category --</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}"
                                        {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        {{-- Assets --}}
                        <div class="mb-4 relative">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Choose Assets
                            </label>
                            <input type="text" id="assets-search" placeholder="Cari assets..."
                                value="{{ old('assets_search') }}"
                                class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <input type="hidden" name="asset_id" id="assets-id" value="{{ old('asset_id') }}">
                            <ul id="assets-results"
                                class="hidden absolute z-50 w-full left-0 border border-gray-300 dark:border-gray-600 rounded-md mt-1 overflow-y-auto bg-white dark:bg-gray-800 shadow-lg max-h-32">
                                @foreach ($assets as $ass)
                                    <li class="px-3 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer border-b border-gray-100 dark:border-gray-700 text-gray-900 dark:text-gray-100" data-id="{{ $ass->id }}">
                                        {{ $ass->name }} - {{ $ass->code }}</li>
                                @endforeach
                            </ul>
                        </div>


                        {{-- Problem --}}
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Problem <span class="text-red-500">*</span>
                            </label>
                            <textarea name="problem" placeholder="Masukkan Kendala..." required rows="3"
                                class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-blue-500 focus:border-blue-500">{{ old('problem') }}</textarea>
                        </div>

                        {{-- Priority --}}
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Priority <span class="text-red-500">*</span>
                            </label>
                            <select name="priority_id" required
                                class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="" hidden>-- Choose Priority --</option>
                                @foreach ($priorities as $prt)
                                    <option value="{{ $prt->id }}"
                                        {{ old('priority_id') == $prt->id ? 'selected' : '' }}>
                                        {{ $prt->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Status --}}
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <select name="status_id" id="status-select" required
                                class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="1" hidden>Waiting</option>
                                @foreach ($statuses as $stat)
                                    <option value="{{ $stat->id }}" data-name="{{ strtolower($stat->name) }}">
                                        {{ $stat->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Date Range --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-end mb-4">
                            {{-- Start Date --}}
                            <div id="start-date-container" class="hidden">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    <svg class="w-4 h-4 inline-block mr-1 text-gray-600 dark:text-gray-300"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    Start Date & Time <span class="text-red-500">*</span>
                                </label>
                                <input type="datetime-local" id="start_datetime" name="start_date"
                                    value="{{ old('start_date') }}"
                                    class="date-input w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm px-3 py-2 
                   bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 
                   focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            {{-- End Date --}}
                            <div id="end-date-container" class="hidden">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    <svg class="w-4 h-4 inline-block mr-1 text-gray-600 dark:text-gray-300"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    End Date & Time <span class="text-red-500">*</span>
                                </label>
                                <input type="datetime-local" id="end_datetime" name="end_date"
                                    value="{{ old('end_date') }}"
                                    class="date-input w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm px-3 py-2 
                   bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 
                   focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>


                        {{-- Time Spent --}}
                        <div id="time-spent-container" class="mb-4 hidden">
                            <div class="flex items-center justify-between mb-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Time Spent (Menit) <span class="text-red-500">*</span>
                                </label>
                                <label class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                    <input type="checkbox" id="manual_time" class="mr-2 rounded focus:ring-blue-500">
                                    Manual Input
                                </label>
                            </div>
                            <input type="number" id="time_spent_minutes" name="time_spent_minutes" readonly min="1"
                                class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm px-3 py-2 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        {{-- Solution - Hanya muncul jika status In Progress atau Done --}}
                        <div id="solution-container" class="mb-4 hidden">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Solusi
                            </label>
                            <textarea name="solution" id="solution-field" placeholder="Masukkan Solusi..." rows="3"
                                class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-blue-500 focus:border-blue-500"></textarea>
                        </div>

                        {{-- Notes - Muncul jika manual time atau status Void --}}
                        <div id="notes-container" class="mb-4 hidden">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Notes <span class="text-red-500" id="notes-required">*</span>
                            </label>
                            <textarea name="notes" id="notes" rows="3" placeholder="Masukkan catatan..."
                                class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-blue-500 focus:border-blue-500"></textarea>
                        </div>

                        {{-- Upload Media --}}
                        <div class="mb-6">
                            <label for="media"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Upload Gambar / Video (Max 5 mb)
                            </label>
                            <input type="file" name="image" id="media" accept=".jpg,.jpeg,.png,.mp4"
                                class="block w-full text-sm text-gray-900 dark:text-gray-100 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-gray-700 dark:file:text-gray-200 dark:hover:file:bg-gray-600">
                            <div id="preview-container" class="mt-4"></div>
                        </div>

                        {{-- Submit Button --}}
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md transition-colors duration-200">
                            Simpan
                        </button>
                    </form>

                </div>
            </div>
        </div>
    </div>

    {{-- jQuery (tetap dipakai untuk search dropdown) --}}
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {

            // =========================
            // 🔥 DATA TABLE (SAFE INIT)
            // =========================
            let dataTableInstance = null;

            function initDataTable() {
                const table = document.querySelector(".datatable");
                if (!table) return;

                // kalau sudah pernah init → destroy dulu
                if ($.fn.dataTable.isDataTable(table)) {
                    $(table).DataTable().destroy();
                }

                dataTableInstance = new DataTable(table);
            }

            if (window.innerWidth >= 1024) {
                initDataTable();
            }

            // =========================
            // 🔥 STATUS FIELD LOGIC
            // =========================
            const statusSelect = document.getElementById("status-select");
            if (statusSelect) {

                const startContainer = document.getElementById("start-date-container");
                const endContainer = document.getElementById("end-date-container");
                const timeContainer = document.getElementById("time-spent-container");
                const solutionContainer = document.getElementById("solution-container");
                const notesContainer = document.getElementById("notes-container");

                const startInput = document.getElementById("start_datetime");
                const endInput = document.getElementById("end_datetime");
                const timeInput = document.getElementById("time_spent_minutes");
                const solutionField = document.getElementById("solution-field");
                const notesField = document.getElementById("notes");
                const manualCheckbox = document.getElementById("manual_time");

                // restore old status
                @if (old('status_id'))
                    statusSelect.value = "{{ old('status_id') }}";
                    statusSelect.dispatchEvent(new Event('change'));
                @endif

                function resetFields() {
                    startContainer?.classList.add("hidden");
                    endContainer?.classList.add("hidden");
                    timeContainer?.classList.add("hidden");
                    solutionContainer?.classList.add("hidden");
                    notesContainer?.classList.add("hidden");

                    startInput?.removeAttribute("required");
                    endInput?.removeAttribute("required");
                    timeInput?.removeAttribute("required");
                    solutionField?.removeAttribute("required");
                    notesField?.removeAttribute("required");

                    if (startInput) startInput.value = "";
                    if (endInput) endInput.value = "";
                    if (timeInput) timeInput.value = "";
                }

                statusSelect.addEventListener("change", function() {
                    const status = this.options[this.selectedIndex].dataset.name;

                    resetFields();

                    if (status === "in progress") {
                        startContainer?.classList.remove("hidden");
                        startInput?.setAttribute("required", "required");
                    }

                    if (status === "done") {
                        startContainer?.classList.remove("hidden");
                        endContainer?.classList.remove("hidden");
                        timeContainer?.classList.remove("hidden");
                        solutionContainer?.classList.remove("hidden");

                        startInput?.setAttribute("required", "required");
                        endInput?.setAttribute("required", "required");
                        timeInput?.setAttribute("required", "required");
                        solutionField?.setAttribute("required", "required");
                    }
                });

                // =========================
                // TIME CALC
                // =========================
                function hitungTimeSpent() {
                    if (manualCheckbox?.checked) return;

                    const start = new Date(startInput?.value);
                    const end = new Date(endInput?.value);

                    if (!isNaN(start) && !isNaN(end) && end > start) {
                        timeInput.value = Math.floor((end - start) / 60000);
                    } else {
                        timeInput.value = "";
                    }
                }

                startInput?.addEventListener("change", hitungTimeSpent);
                endInput?.addEventListener("change", hitungTimeSpent);

                // =========================
                // MANUAL TIME
                // =========================
                manualCheckbox?.addEventListener("change", function() {

                    if (this.checked) {
                        timeInput.removeAttribute("readonly");
                        timeInput.classList.remove("bg-gray-100", "dark:bg-gray-700");
                        timeInput.classList.add("bg-gray-50", "dark:bg-gray-800");

                        notesContainer?.classList.remove("hidden");
                        notesField?.setAttribute("required", "required");
                    } else {
                        timeInput.setAttribute("readonly", true);
                        timeInput.classList.remove("bg-gray-50", "dark:bg-gray-800");
                        timeInput.classList.add("bg-gray-100", "dark:bg-gray-700");

                        hitungTimeSpent();

                        notesContainer?.classList.add("hidden");
                        notesField?.removeAttribute("required");
                    }
                });

                // =========================
                // FORM VALIDATION
                // =========================
                const form = document.getElementById("ticket-form");
                form?.addEventListener("submit", function(e) {
                    const userId = document.getElementById("user-id");
                    if (!userId?.value) {
                        e.preventDefault();
                        alert("⚠️ Pilih Requestor terlebih dahulu!");
                        document.getElementById("user-search")?.focus();
                    }
                });
            }

            // =========================
            // 🔥 USER SEARCH (JQUERY)
            // =========================
            $(function() {
                const $input = $('#user-search');
                const $results = $('#search-results');
                const $hidden = $('#user-id');

                if (!$input.length) return;

                $input.on('focus', () => $results.show());

                $input.on('input', function() {
                    const val = $(this).val().toLowerCase();
                    let visible = false;

                    $results.children('li').each(function() {
                        const match = $(this).text().toLowerCase().includes(val);
                        $(this).toggle(match);
                        if (match) visible = true;
                    });

                    $results.toggle(visible);
                });

                $results.on('click', 'li', function() {
                    $input.val($(this).text());
                    $hidden.val($(this).data('id'));
                    $results.hide();
                });

                $(document).on('click', (e) => {
                    if (!$(e.target).closest('#user-search, #search-results').length) {
                        $results.hide();
                    }
                });
            });

            // =========================
            // 🔥 ASSETS SEARCH (JQUERY)
            // =========================
            $(function() {
                const $input = $('#assets-search');
                const $results = $('#assets-results');
                const $hidden = $('#assets-id');

                if (!$input.length) return;

                $input.on('focus', () => $results.removeClass('hidden'));

                $input.on('input', function() {
                    const val = $(this).val().toLowerCase();
                    let hasVisible = false;

                    $results.children('li').each(function() {
                        const match = $(this).text().toLowerCase().includes(val);
                        $(this).toggleClass('hidden', !match);
                        if (match) hasVisible = true;
                    });

                    $results.toggleClass('hidden', !hasVisible);
                });

                $results.on('click', 'li', function() {
                    $input.val($(this).text());
                    $hidden.val($(this).data('id'));
                    $results.addClass('hidden');
                });

                $(document).on('click', (e) => {
                    if (!$(e.target).closest('#assets-search, #assets-results').length) {
                        $results.addClass('hidden');
                    }
                });
            });

            // =========================
            // 🔥 MEDIA PREVIEW
            // =========================
            const mediaInput = document.getElementById("media");
            const previewContainer = document.getElementById("preview-container");

            mediaInput?.addEventListener("change", function(e) {
                if (!previewContainer) return;

                previewContainer.innerHTML = "";

                const file = e.target.files[0];
                if (!file) return;

                const MAX_SIZE = 10 * 1024 * 1024;

                if (file.size > MAX_SIZE) {
                    alert("❌ File terlalu besar! Maksimal 10MB");
                    mediaInput.value = "";
                    return;
                }

                const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'video/mp4'];

                if (!validTypes.includes(file.type)) {
                    alert("❌ Format tidak valid!");
                    mediaInput.value = "";
                    return;
                }

                const preview = document.createElement("div");
                preview.className = "relative inline-block";

                if (file.type.startsWith("image/")) {
                    const img = document.createElement("img");
                    img.src = URL.createObjectURL(file);
                    img.className = "h-40 rounded border";
                    preview.appendChild(img);
                } else {
                    const video = document.createElement("video");
                    video.src = URL.createObjectURL(file);
                    video.controls = true;
                    video.className = "h-40 rounded border";
                    preview.appendChild(video);
                }

                const btn = document.createElement("button");
                btn.type = "button";
                btn.innerHTML = "✖";
                btn.className = "absolute top-0 right-0 bg-red-500 text-white px-2 rounded";

                btn.onclick = () => {
                    mediaInput.value = "";
                    previewContainer.innerHTML = "";
                };

                preview.appendChild(btn);
                previewContainer.appendChild(preview);
            });

        });
    </script>

    {{-- STYLE FIX --}}
    <style>
        .date-input::-webkit-calendar-picker-indicator {
            filter: invert(0);
            cursor: pointer;
        }

        .dark .date-input::-webkit-calendar-picker-indicator {
            filter: invert(1);
        }

        .date-input::-webkit-calendar-picker-indicator:hover {
            opacity: 0.7;
        }

        #search-results::-webkit-scrollbar,
        #assets-results::-webkit-scrollbar {
            width: 8px;
        }

        #search-results::-webkit-scrollbar-track,
        #assets-results::-webkit-scrollbar-track {
            background: #374151;
        }

        #search-results::-webkit-scrollbar-thumb,
        #assets-results::-webkit-scrollbar-thumb {
            background: #6b7280;
            border-radius: 4px;
        }

        #search-results::-webkit-scrollbar-thumb:hover,
        #assets-results::-webkit-scrollbar-thumb:hover {
            background: #9ca3af;
        }
    </style>

</x-app-layout>
