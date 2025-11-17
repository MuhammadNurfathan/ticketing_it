<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Create Ticket') }}
        </h2>
    </x-slot>

    <div
        class="py-12 bg-light-eval-1 dark:bg-dark-eval-1 rounded-lg shadow-md p-4 border border-gray-200 dark:border-gray-700">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

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
                    <form action="{{ route('DashboardTicketsAdmin.store') }}" method="POST" enctype="multipart/form-data"
                        id="ticket-form">
                        @csrf
                        <input type="hidden" name="from" value="admin">

                        {{-- Ticket Code --}}
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Ticket Code <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="ticket_code" value="{{ $generateticket }}" readonly required
                                class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm px-3 py-2 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                        </div>

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
                                @foreach ($developers as $dev)
                                    <option value="{{ $dev->id }}"
                                        {{ old('support_id') == $dev->id ? 'selected' : '' }}>
                                        {{ $dev->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Problem Category --}}
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Category <span class="text-red-500">*</span>
                            </label>
                            <select name="problem_category_id" required
                                class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="" hidden>-- Choose Category --</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}"
                                        {{ old('problem_category_id') == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->problem_category_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        {{-- Assets --}}
                        <div class="mb-4 relative">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Choose Assets <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="assets-search" placeholder="Cari assets..." required
                                value="{{ old('assets_search') }}"
                                class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <input type="hidden" name="assets_id" id="assets-id" value="{{ old('assets_id') }}"
                                required>
                            <ul id="assets-results"
                                class="hidden absolute z-50 w-full left-0 border border-gray-300 dark:border-gray-600 rounded-md mt-1 overflow-y-auto bg-white dark:bg-gray-800 shadow-lg max-h-32">
                                @foreach ($assets as $ass)
                                    <li class="px-3 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer text-border-b border-gray-100 dark:border-gray-700 text-gray-900 dark:text-gray-100"
                                        data-id="{{ $ass->id }}">{{ $ass->assets_name }} - {{ $ass->assets_code }} </li>
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
                                        {{ $prt->priority_name }}
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
                                <option value="" hidden>-- Choose Status --</option>
                                @foreach ($statuses as $stat)
                                    @if (!in_array($stat->id, [4, 5, 6]))
                                        <option value="{{ $stat->id }}"
                                            data-name="{{ strtolower($stat->status_name) }}">
                                            {{ $stat->status_name }}
                                        </option>
                                    @endif
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
                                    class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm px-3 py-2 
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
                                    class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm px-3 py-2 
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
                            <input type="number" id="time_spent" name="time_spent" readonly min="1"
                                class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm px-3 py-2 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        {{-- Solution - Hanya muncul jika status In Progress atau Done --}}
                        <div id="solution-container" class="mb-4 hidden">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Solusi <span class="text-red-500">*</span>
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

    {{-- Scripts --}}
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

    <script>
        {{-- Status Field Logic dengan Validasi --}}
        document.addEventListener("DOMContentLoaded", function() {
            const statusSelect = document.getElementById("status-select");
            const startContainer = document.getElementById("start-date-container");
            const endContainer = document.getElementById("end-date-container");
            const timeContainer = document.getElementById("time-spent-container");
            const solutionContainer = document.getElementById("solution-container");
            const notesContainer = document.getElementById("notes-container");
            const startInput = document.getElementById("start_datetime");
            const endInput = document.getElementById("end_datetime");
            const timeInput = document.getElementById("time_spent");
            const solutionField = document.getElementById("solution-field");
            const notesField = document.getElementById("notes");
            const manualCheckbox = document.getElementById("manual_time");

            // Restore old status jika ada error
            @if (old('status_id'))
                statusSelect.value = "{{ old('status_id') }}";
                // Trigger change untuk munculkan field yang sesuai
                const event = new Event('change');
                statusSelect.dispatchEvent(event);
            @endif

            statusSelect.addEventListener("change", function() {
                const status = this.options[this.selectedIndex].getAttribute("data-name");

                // Reset semua field
                startContainer.classList.add("hidden");
                endContainer.classList.add("hidden");
                timeContainer.classList.add("hidden");
                solutionContainer.classList.add("hidden");
                notesContainer.classList.add("hidden");

                // Reset required attributes
                startInput.removeAttribute("required");
                endInput.removeAttribute("required");
                timeInput.removeAttribute("required");
                solutionField.removeAttribute("required");
                notesField.removeAttribute("required");

                // Clear values
                startInput.value = "";
                endInput.value = "";
                timeInput.value = "";

                if (status === "waiting") {
                    // Waiting: Tidak perlu field tambahan
                } else if (status === "in progress") {
                    // In Progress: Start Date + Solution wajib
                    startContainer.classList.remove("hidden");
                    startInput.setAttribute("required", "required");
                } else if (status === "done") {
                    // Done: Start Date, End Date, Time Spent, Solution wajib
                    startContainer.classList.remove("hidden");
                    endContainer.classList.remove("hidden");
                    timeContainer.classList.remove("hidden");
                    solutionContainer.classList.remove("hidden");
                    startInput.setAttribute("required", "required");
                    endInput.setAttribute("required", "required");
                    timeInput.setAttribute("required", "required");
                    solutionField.setAttribute("required", "required");
                }
            });

            // Batasi end date
            function updateEndDateMin() {
                if (startInput.value) {
                    endInput.min = startInput.value;
                    if (endInput.value && endInput.value < startInput.value) {
                        endInput.value = '';
                        timeInput.value = '';
                    }
                } else {
                    endInput.removeAttribute('min');
                }
            }

            startInput.addEventListener('change', updateEndDateMin);
            startInput.addEventListener('input', updateEndDateMin);

            // Hitung time spent otomatis
            function hitungTimeSpent() {
                if (manualCheckbox.checked) return;
                const start = new Date(startInput.value);
                const end = new Date(endInput.value);
                if (!isNaN(start) && !isNaN(end) && end > start) {
                    timeInput.value = Math.floor((end - start) / 60000);
                } else {
                    timeInput.value = "";
                }
            }

            endInput.addEventListener("change", hitungTimeSpent);
            startInput.addEventListener("change", hitungTimeSpent);

            // Manual time checkbox
            manualCheckbox.addEventListener("change", function() {
                if (this.checked) {
                    timeInput.removeAttribute("readonly");
                    timeInput.classList.remove("bg-gray-100", "dark:bg-gray-700");
                    timeInput.classList.add("bg-white");
                    notesContainer.classList.remove("hidden");
                    notesField.setAttribute("required", "required");
                } else {
                    timeInput.setAttribute("readonly", true);
                    timeInput.classList.add("bg-gray-100", "dark:bg-gray-700");
                    timeInput.classList.remove("bg-white");
                    hitungTimeSpent();
                    notesContainer.classList.add("hidden");
                    notesField.removeAttribute("required");
                }
            });

            // Form validation sebelum submit
            document.getElementById('ticket-form').addEventListener('submit', function(e) {
                const userIdField = document.getElementById('user-id');
                const assetsIdField = document.getElementById('assets-id');

                if (!userIdField.value) {
                    e.preventDefault();
                    alert('⚠️ Pilih Requestor terlebih dahulu!');
                    document.getElementById('user-search').focus();
                    return false;
                }

                if (!assetsIdField.value) {
                    e.preventDefault();
                    alert('⚠️ Pilih Assets terlebih dahulu!');
                    document.getElementById('assets-search').focus();
                    return false;
                }
            });

            // Restore old values untuk search fields
            @if (old('user_id'))
                const userId = "{{ old('user_id') }}";
                const userSearch = document.getElementById('user-search');
                const userResults = document.querySelectorAll('#search-results li');
                userResults.forEach(li => {
                    if (li.getAttribute('data-id') == userId) {
                        userSearch.value = li.textContent;
                    }
                });
            @endif

            @if (old('assets_id'))
                const assetsId = "{{ old('assets_id') }}";
                const assetsSearch = document.getElementById('assets-search');
                const assetsResults = document.querySelectorAll('#assets-results li');
                assetsResults.forEach(li => {
                    if (li.getAttribute('data-id') == assetsId) {
                        assetsSearch.value = li.textContent;
                    }
                });
            @endif
        });
    </script>

    {{-- User Search --}}
    <script>
        $(function() {
            const $input = $('#user-search');
            const $results = $('#search-results');
            const $hidden = $('#user-id');

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
    </script>

    {{-- Assets Search --}}
    <script>
        $(function() {
            const $input = $('#assets-search');
            const $results = $('#assets-results');
            const $hidden = $('#assets-id');

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
                if (!$(e.target).closest('#assets-search, #assets-results').length) {
                    $results.hide();
                }
            });
        });
    </script>

    {{-- Media Preview & Validation (FIXED) --}}
    <script>
        const mediaInput = document.getElementById("media");
        const previewContainer = document.getElementById("preview-container");
        const MAX_SIZE = 10 * 1024 * 1024; // 10MB

        mediaInput.addEventListener("change", function(e) {
            previewContainer.innerHTML = "";

            const file = e.target.files[0];
            if (!file) return;

            // Validasi ukuran
            if (file.size > MAX_SIZE) {
                alert("❌ File terlalu besar! Maksimal 10MB");
                mediaInput.value = "";
                return;
            }

            // Validasi tipe
            const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'video/mp4'];
            if (!validTypes.includes(file.type)) {
                alert("❌ Format tidak valid! Hanya JPG, PNG, atau MP4");
                mediaInput.value = "";
                return;
            }

            // Tampilkan preview
            const preview = document.createElement("div");
            preview.className = "relative inline-block";

            if (file.type.startsWith('image/')) {
                const img = document.createElement("img");
                img.src = URL.createObjectURL(file);
                img.className = "h-40 rounded border border-gray-300 dark:border-gray-600";
                img.onload = () => URL.revokeObjectURL(img.src);
                preview.appendChild(img);
            } else {
                const video = document.createElement("video");
                video.src = URL.createObjectURL(file);
                video.controls = true;
                video.className = "h-40 rounded border border-gray-300 dark:border-gray-600";
                preview.appendChild(video);
            }

            // Tombol hapus
            const btnRemove = document.createElement("button");
            btnRemove.innerHTML = "✖";
            btnRemove.type = "button";
            btnRemove.className = "absolute top-0 right-0 bg-red-500 text-white px-2 rounded-full hover:bg-red-600";
            btnRemove.onclick = function() {
                mediaInput.value = "";
                previewContainer.innerHTML = "";
            };

            preview.appendChild(btnRemove);
            previewContainer.appendChild(preview);
        });
    </script>

    {{-- Custom Scrollbar Styling --}}
    <style>
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
