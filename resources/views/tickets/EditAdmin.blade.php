<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Ticket') }}
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

                    {{-- Form Edit Ticket --}}
                    <form action="{{ route('DashboardTicketsAdmin.update', $ticket->id) }}" method="POST"
                        enctype="multipart/form-data" id="edit-form">
                        @csrf
                        @method('PUT')

                        {{-- Ticket Code (READ ONLY) --}}
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Ticket Code
                            </label>
                            <input type="text" name="ticket_code" value="{{ $ticket->ticket_code }}" readonly
                                class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm px-3 py-2 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100 cursor-not-allowed">
                        </div>

                        {{-- Requestor Name (READ ONLY) --}}
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Requestor Name
                            </label>
                            <input type="text" value="{{ $ticket->user->name ?? 'N/A' }}" readonly
                                class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm px-3 py-2 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100 cursor-not-allowed">
                            <input type="hidden" name="user_id" value="{{ $ticket->user_id }}">
                        </div>

                        {{-- IT Support (MANDATORY) --}}
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                IT Support <span class="text-red-500">*</span>
                            </label>
                            <select name="support_id" required
                                class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="" hidden>-- Pilih IT Support --</option>
                                @foreach ($developers as $dev)
                                    <option value="{{ $dev->id }}"
                                        {{ old('support_id', $ticket->support_id) == $dev->id ? 'selected' : '' }}>
                                        {{ $dev->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Category (READ ONLY) --}}
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Category Problem
                            </label>
                            <input type="text" value="{{ $ticket->problemCategory->problem_category_name ?? 'N/A' }}"
                                readonly
                                class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm px-3 py-2 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100 cursor-not-allowed">
                            <input type="hidden" name="problem_category_id"
                                value="{{ $ticket->problem_category_id }}">
                        </div>


                        @php
                            $selectedAsset = null;

                            // Ambil asset dari ticket yang sedang diedit
                            if (isset($ticket) && $ticket->assets_id) {
                                $selectedAsset = $assets->firstWhere('id', $ticket->assets_id);
                            }

                            // Override dengan old() kalau validasi gagal
                            if (old('assets_id')) {
                                $selectedAsset = $assets->firstWhere('id', old('assets_id'));
                            }

                            $selectedText = $selectedAsset
                                ? $selectedAsset->assets_name .
                                    ' - ' .
                                    $selectedAsset->assets_code .
                                    ' - ' .
                                    $selectedAsset->check_out_to
                                : '';
                        @endphp

                        {{-- Assets --}}
                        <div class="mb-4 relative">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Choose Assets
                            </label>
                            <input type="text" id="assets-search" placeholder="Cari assets..."
                                value="{{ $selectedText }}"
                                class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-blue-500 focus:border-blue-500" />

                            <input type="hidden" name="assets_id" id="assets-id" value="{{ old('assets_id') }}">
                            <ul id="assets-results"
                                class="hidden absolute z-50 w-full left-0 border border-gray-300 dark:border-gray-600 rounded-md mt-1 overflow-y-auto bg-white dark:bg-gray-800 shadow-lg max-h-32">
                                @foreach ($assets as $ass)
                                    <li class="px-3 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer border-b border-gray-100 dark:border-gray-700 text-gray-900 dark:text-gray-100"
                                        data-id="{{ $ass->id }}">{{ $ass->assets_name }} -
                                        {{ $ass->assets_code }} - {{ $ass->check_out_to }}</li>
                                @endforeach
                            </ul>
                        </div>



                        {{-- Problem (READ ONLY) --}}
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Problem
                            </label>
                            <textarea readonly rows="3"
                                class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm px-3 py-2 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100 cursor-not-allowed">{{ $ticket->problem }}</textarea>
                            <input type="hidden" name="problem" value="{{ $ticket->problem }}">
                        </div>

                        {{-- Priority (MANDATORY) --}}
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Priority <span class="text-red-500">*</span>
                            </label>
                            <select name="priority_id" required
                                class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="" hidden>-- Pilih Priority --</option>
                                @foreach ($priorities as $prt)
                                    <option value="{{ $prt->id }}"
                                        {{ old('priority_id', $ticket->priority_id) == $prt->id ? 'selected' : '' }}>
                                        {{ $prt->priority_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Status (MANDATORY) --}}
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <select name="status_id" id="status-select" required
                                class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="" hidden>-- Pilih Status --</option>
                                @foreach ($statuses as $stat)
                                    @if (!in_array($stat->id, [4, 5, 6]))
                                        <option value="{{ $stat->id }}"
                                            data-name="{{ strtolower($stat->status_name) }}"
                                            {{ old('status_id', $ticket->status_id) == $stat->id ? 'selected' : '' }}>
                                            {{ $stat->status_name }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <div class="flex flex-col md:flex-row gap-4 mb-4">
                            {{-- Start Date --}}
                            <div id="start-date-container"
                                class="w-full md:w-1/2 {{ in_array(old('status_id', $ticket->status_id), [2, 3]) ? '' : 'hidden' }}">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Start Date & Time <span class="text-red-500">*</span>
                                </label>
                                <input type="datetime-local" id="start_datetime" name="start_date"
                                    value="{{ old('start_date', $ticket->start_date ? $ticket->start_date->format('Y-m-d\TH:i') : '') }}"
                                    class="date-input w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm px-3 py-2 
                   bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 
                   focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            {{-- End Date --}}
                            <div id="end-date-container"
                                class="w-full md:w-1/2 {{ old('status_id', $ticket->status_id) == 3 ? '' : 'hidden' }}">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    End Date & Time <span class="text-red-500">*</span>
                                </label>
                                <input type="datetime-local" id="end_datetime" name="end_date"
                                    value="{{ old('end_date', $ticket->end_date ? $ticket->end_date->format('Y-m-d\TH:i') : '') }}"
                                    class="date-input w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm px-3 py-2 
                   bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 
                   focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>


                        {{-- Time Spent (Conditional - MANDATORY untuk Done) --}}
                        <div id="time-spent-container"
                            class="mb-4 {{ old('status_id', $ticket->status_id) == 3 ? '' : 'hidden' }}">
                            <div class="flex items-center justify-between mb-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Time Spent (Menit) <span class="text-red-500">*</span>
                                </label>
                                <label class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                    <input type="checkbox" id="manual_time" class="mr-2 rounded focus:ring-blue-500">
                                    Manual Input
                                </label>
                            </div>
                            <input type="number" id="time_spent" name="time_spent"
                                value="{{ old('time_spent', $ticket->time_spent) }}" readonly min="1"
                                placeholder="Masukkan Waktu Pengerjaan (Menit)"
                                class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm px-3 py-2 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        {{-- Notes (Muncul jika manual time) --}}
                        <div id="notes-container" class="mb-4 hidden">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Notes <span class="text-red-500">*</span>
                            </label>
                            <textarea name="notes" id="notes" rows="3" placeholder="Masukkan catatan..."
                                class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-blue-500 focus:border-blue-500">{{ old('notes', $ticket->notes ?? '') }}</textarea>
                        </div>

                        {{-- Solution (Conditional - MANDATORY untuk In Progress/Done) --}}
                        <div id="solution-container"
                            class="mb-4 {{ in_array(old('status_id', $ticket->status_id), [2, 3]) ? '' : 'hidden' }}">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Solution
                            </label>
                            <textarea name="solution" id="solution" rows="3" placeholder="Masukkan solusi penyelesaian..."
                                minlength="10"
                                class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-blue-500 focus:border-blue-500">{{ old('solution', $ticket->solution ?? '') }}</textarea>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Minimal 10 karakter</p>
                        </div>

                        {{-- File Preview (READ ONLY - Tidak bisa diubah) --}}
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Current File
                            </label>

                            @if ($ticket->image)
                                <div
                                    class="border border-gray-300 dark:border-gray-600 rounded-lg p-3 bg-gray-50 dark:bg-gray-900">
                                    @php
                                        $extension = pathinfo($ticket->image, PATHINFO_EXTENSION);
                                        $filename = basename($ticket->image);
                                    @endphp

                                    @if (in_array($extension, ['jpg', 'jpeg', 'png']))
                                        <img src="{{ route('ticket.file', $filename) }}" alt="Ticket Image"
                                            class="max-w-md h-40 object-cover rounded border border-gray-300 dark:border-gray-600">
                                    @elseif ($extension == 'mp4')
                                        <video controls
                                            class="max-w-md h-40 rounded border border-gray-300 dark:border-gray-600">
                                            <source src="{{ route('ticket.file', $filename) }}" type="video/mp4">
                                        </video>
                                    @endif
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">ðŸ“Ž {{ $filename }}
                                    </p>
                                </div>
                            @else
                                <p class="text-sm text-gray-500 dark:text-gray-400">Tidak ada file yang diupload</p>
                            @endif

                            <input type="hidden" name="image" value="{{ $ticket->image }}">
                        </div>

                        {{-- Buttons --}}
                        <div class="flex gap-2">
                            <button type="submit"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md transition-colors duration-200">
                                Update Ticket
                            </button>
                            <button type="button" onclick="history.back()"
                                class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md transition-colors duration-200">
                                Back
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
    {{-- Assets Search --}}
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script>
        $(function() {
            const $input = $('#assets-search');
            const $results = $('#assets-results');
            const $hidden = $('#assets-id');

            // Tampilkan list saat focus
            $input.on('focus', () => $results.removeClass('hidden'));

            // Filter list saat ketik
            $input.on('input', function() {
                const val = $(this).val().toLowerCase();
                let hasVisible = false;

                $results.children('li').each(function() {
                    const text = $(this).text().toLowerCase();
                    const match = text.includes(val);
                    $(this).toggleClass('hidden', !match);
                    if (match) hasVisible = true;
                });

                $results.toggleClass('hidden', !hasVisible);
            });

            // Pilih item
            $results.on('click', 'li', function() {
                $input.val($(this).text());
                $hidden.val($(this).data('id'));
                $results.addClass('hidden');
            });

            // Klik di luar â†’ sembunyikan list
            $(document).on('click', function(e) {
                if (!$(e.target).closest('#assets-search, #assets-results').length) {
                    $results.addClass('hidden');
                }
            });
        });
    </script>

    <script>
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
            const solutionField = document.getElementById("solution");
            const notesField = document.getElementById("notes");
            const manualCheckbox = document.getElementById("manual_time");

            // Restore old status jika ada error
            @if (old('status_id'))
                statusSelect.value = "{{ old('status_id') }}";
                const event = new Event('change');
                statusSelect.dispatchEvent(event);
            @endif

            // === STATUS CHANGE HANDLER ===
            statusSelect.addEventListener("change", function() {
                const statusId = parseInt(this.value);

                // Sembunyikan semua field dulu
                startContainer.classList.add("hidden");
                endContainer.classList.add("hidden");
                timeContainer.classList.add("hidden");
                solutionContainer.classList.add("hidden");

                // Hapus required semua dulu
                startInput.removeAttribute("required");
                endInput.removeAttribute("required");
                timeInput.removeAttribute("required");
                solutionField.removeAttribute("required");

                // Field sesuai status
                if (statusId === 2) { // IN PROGRESS
                    startContainer.classList.remove("hidden");
                    startInput.setAttribute("required", "required");
                } else if (statusId === 3) { // DONE
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

            // === Update minimum end date ===
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
            updateEndDateMin();

            // === Auto calculate time spent ===
            function hitungTimeSpent() {
                if (manualCheckbox.checked) return; // skip kalau manual
                const start = new Date(startInput.value);
                const end = new Date(endInput.value);
                if (!isNaN(start) && !isNaN(end) && end > start) {
                    timeInput.value = Math.floor((end - start) / 60000); // menit
                } else {
                    timeInput.value = "";
                }
            }

            startInput.addEventListener('change', hitungTimeSpent);
            startInput.addEventListener('input', hitungTimeSpent);
            endInput.addEventListener('change', hitungTimeSpent);
            endInput.addEventListener('input', hitungTimeSpent);

            // Hitung saat page load jika ada nilai
            hitungTimeSpent();

            // === Manual input toggle ===
            // Manual time checkbox
            manualCheckbox.addEventListener("change", function() {
                if (this.checked) {
                    timeInput.removeAttribute("readonly");

                    // Ganti kelas bg sesuai tema tapi tetap editable
                    timeInput.classList.remove("bg-gray-100", "dark:bg-gray-700");
                    timeInput.classList.add("bg-gray-50", "dark:bg-gray-800");

                    notesContainer.classList.remove("hidden");
                    notesField.setAttribute("required", "required");
                } else {
                    timeInput.setAttribute("readonly", true);

                    // Kembalikan bg default readonly
                    timeInput.classList.remove("bg-gray-50", "dark:bg-gray-800");
                    timeInput.classList.add("bg-gray-100", "dark:bg-gray-700");

                    hitungTimeSpent();
                    notesContainer.classList.add("hidden");
                    notesField.removeAttribute("required");
                }
            });

        });
    </script>

    <style>
        /* Styling untuk date input di light mode */
        .date-input::-webkit-calendar-picker-indicator {
            filter: invert(0);
            cursor: pointer;
        }

        /* Styling untuk date input di dark mode */
        .dark .date-input::-webkit-calendar-picker-indicator {
            filter: invert(1);
            cursor: pointer;
        }

        /* Opsional: ubah opacity saat hover */
        .date-input::-webkit-calendar-picker-indicator:hover {
            opacity: 0.7;
        }
    </style>
</x-app-layout>
