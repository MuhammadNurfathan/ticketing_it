<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Ticket') }}
        </h2>
    </x-slot>

    <div class="py-12">
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
                    <form action="{{ route('DashboardTicketsAdmin.update', $ticket->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        {{-- Ticket Code --}}
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Ticket Code
                            </label>
                            <input type="text" name="ticket_code" value="{{ $ticket->ticket_code }}" readonly
                                class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm px-3 py-2 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                        </div>

                        {{-- User --}}
                        <div class="mb-4 relative">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Pilih User <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="user-search" placeholder="Cari user..."
                                value="{{ $ticket->user->name ?? '' }}"
                                class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                            <input type="hidden" name="user_id" id="user-id" value="{{ $ticket->user_id }}" required>

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
                            <select name="support_id"
                                class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                <option hidden>-- Pilih IT Support --</option>
                                @foreach ($developers as $dev)
                                    <option value="{{ $dev->id }}" {{ $ticket->support_id == $dev->id ? 'selected' : '' }}>
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
                            <select name="problem_category_id"
                                class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                <option hidden>-- Pilih Category --</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ $ticket->problem_category_id == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->problem_category_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Assets --}}
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Assets <span class="text-red-500">*</span>
                            </label>
                            <select name="assets_id"
                                class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                <option hidden>-- Pilih Assets --</option>
                                @foreach ($assets as $ass)
                                    <option value="{{ $ass->id }}" {{ $ticket->assets_id == $ass->id ? 'selected' : '' }}>
                                        {{ $ass->assets_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Problem --}}
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Problem <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="problem" value="{{ $ticket->problem }}"
                                placeholder="Masukkan Kendala..."
                                class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                        </div>

                        {{-- Solution --}}
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Solusi <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="solution" value="{{ $ticket->solution }}"
                                placeholder="Masukkan Solusi..."
                                class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                        </div>

                        {{-- Priority --}}
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Priority <span class="text-red-500">*</span>
                            </label>
                            <select name="priority_id"
                                class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                <option hidden>-- Pilih Priority --</option>
                                @foreach ($priorities as $prt)
                                    <option value="{{ $prt->id }}" {{ $ticket->priority_id == $prt->id ? 'selected' : '' }}>
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
                            <select name="status_id" id="status-select"
                                class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option hidden value="">-- Pilih Status --</option>
                                @foreach ($statuses as $stat)
                                    @if ($stat->id != 4)
                                        <option value="{{ $stat->id }}" data-name="{{ strtolower($stat->status_name) }}"
                                            {{ $ticket->status_id == $stat->id ? 'selected' : '' }}>
                                            {{ $stat->status_name }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        {{-- Start Date --}}
                        <div id="start-date-container" class="mb-4 {{ $ticket->status_id == 2 || $ticket->status_id == 3 ? '' : 'hidden' }}">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Start Date & Time
                            </label>
                            <input type="datetime-local" id="start_datetime" name="start_date"
                                value="{{ $ticket->start_date ? $ticket->start_date->format('Y-m-d\TH:i') : '' }}"
                                class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        {{-- End Date --}}
                        <div id="end-date-container" class="mb-4 {{ $ticket->status_id == 3 ? '' : 'hidden' }}">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                End Date & Time
                            </label>
                            <input type="datetime-local" id="end_datetime" name="end_date"
                                value="{{ $ticket->end_date ? $ticket->end_date->format('Y-m-d\TH:i') : '' }}"
                                class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        {{-- Time Spent --}}
                        <div id="time-spent-container" class="mb-4 {{ $ticket->status_id == 3 ? '' : 'hidden' }}">
                            <div class="flex items-center justify-between">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Time Spent (Menit)
                                </label>
                                <label class="flex items-center text-sm text-gray-600 dark:text-gray-300 mb-2">
                                    <input type="checkbox" id="manual_time" class="mr-2" {{ old('time_spent') ? 'checked' : '' }}> Manual Input
                                </label>
                            </div>
                            <input type="number" id="time_spent" name="time_spent" value="{{ $ticket->time_spent }}" readonly
                                class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm px-3 py-2 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                        </div>

                        {{-- Upload Media --}}
                        <div class="mb-6">
                            <label for="media" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Upload Gambar / Video (Max 10 MB)
                            </label>
                            <input type="file" name="image" id="media" accept=".jpg,.jpeg,.png,.mp4"
                                class="mt-1 block w-full text-sm text-gray-900 dark:text-gray-100 
                                file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 
                                file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 
                                hover:file:bg-blue-100 dark:file:bg-gray-700 dark:file:text-gray-200 
                                dark:hover:file:bg-gray-600">

                            <div id="preview-container" class="mt-4 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                                @if($ticket->image)
                                    @if(Str::endsWith($ticket->image, ['.mp4']))
                                        <video controls class="w-full h-40 object-cover rounded-lg border border-gray-300 dark:border-gray-600">
                                            <source src="{{ asset('storage/'.$ticket->image) }}">
                                        </video>
                                    @else
                                        <img src="{{ asset('storage/'.$ticket->image) }}" class="w-full h-40 object-cover rounded-lg border border-gray-300 dark:border-gray-600">
                                    @endif
                                @endif
                            </div>
                        </div>

                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">Update Ticket</button>
                    </form>

                   {{-- jQuery --}}
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const statusSelect = document.getElementById("status-select");
    const startContainer = document.getElementById("start-date-container");
    const endContainer = document.getElementById("end-date-container");
    const timeContainer = document.getElementById("time-spent-container");
    const startInput = document.getElementById("start_datetime");
    const endInput = document.getElementById("end_datetime");
    const timeInput = document.getElementById("time_spent");
    const manualCheckbox = document.getElementById("manual_time");

    // === STATUS TRIGGER ===
    statusSelect.addEventListener("change", function () {
        const selectedOption = this.options[this.selectedIndex];
        const status = selectedOption.getAttribute("data-name");

        startContainer.classList.add("hidden");
        endContainer.classList.add("hidden");
        timeContainer.classList.add("hidden");

        if (status === "in progress") {
            startContainer.classList.remove("hidden");
        } else if (status === "done") {
            startContainer.classList.remove("hidden");
            endContainer.classList.remove("hidden");
            timeContainer.classList.remove("hidden");
        }
    });

    // === AUTO CALCULATE TIME SPENT (menit) ===
    function hitungTimeSpent() {
        if (manualCheckbox.checked) return; // kalau manual, skip auto hitung

        const start = new Date(startInput.value);
        const end = new Date(endInput.value);

        if (!isNaN(start.getTime()) && !isNaN(end.getTime()) && end > start) {
            const diffMs = end - start;
            const diffMinutes = Math.floor(diffMs / (1000 * 60));
            timeInput.value = diffMinutes;
        } else {
            timeInput.value = "";
        }
    }

    startInput.addEventListener("change", hitungTimeSpent);
    endInput.addEventListener("change", hitungTimeSpent);

    // === TOGGLE MANUAL MODE ===
    manualCheckbox.addEventListener("change", function () {
        if (this.checked) {
            timeInput.removeAttribute("readonly");
            timeInput.classList.remove("bg-gray-100");
        } else {
            timeInput.setAttribute("readonly", true);
            timeInput.classList.add("bg-gray-100");
            hitungTimeSpent(); // langsung hitung ulang otomatis
        }
    });
});
</script>


    {{-- Script: User Search --}}
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
                if (!$(e.target).closest('#user-search, #search-results').length) $results.hide();
            });
        });
    </script>

    {{-- Script: Image Preview & Validation --}}
    <script>
        document.getElementById('image').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const preview = document.getElementById('preview-image');
            const maxSize = 2 * 1024 * 1024;
            const allowed = ['image/jpeg', 'image/png', 'image/jpg'];

            if (!file) return preview.classList.add('hidden');
            if (file.size > maxSize) return alert('Ukuran file maksimal 2 MB!'), e.target.value = '', preview
                .classList.add('hidden');
            if (!allowed.includes(file.type)) return alert('Format file harus JPG atau PNG!'), e.target.value = '',
                preview.classList.add('hidden');

            preview.src = URL.createObjectURL(file);
            preview.classList.remove('hidden');
        });
    </script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const input = document.getElementById("media");
    const previewContainer = document.getElementById("preview-container");
    let selectedFile = null; // cuma 1 file

    input.addEventListener("change", function (e) {
        const file = e.target.files[0];
        const maxSize = 10 * 1024 * 1024; // 10 MB
        const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'video/mp4'];

        if (!file) return;
        if (!allowedTypes.includes(file.type)) {
            alert("Format file harus JPG, PNG, atau MP4!");
            input.value = "";
            return;
        }
        if (file.size > maxSize) {
            alert("Ukuran file maksimal 10 MB!");
            input.value = "";
            return;
        }

        selectedFile = file;
        renderPreview();
    });

    function renderPreview() {
        previewContainer.innerHTML = ""; // kosongin dulu

        const previewItem = document.createElement("div");
        previewItem.className = "relative group";

        // tombol hapus
        const removeBtn = document.createElement("button");
        removeBtn.innerHTML = "❌";
        removeBtn.className =
            "absolute top-1 right-1 bg-red-600 text-white rounded-full px-1 text-xs opacity-80 hover:opacity-100";
        removeBtn.addEventListener("click", removeFile);

        // preview gambar / video
        if (selectedFile.type.startsWith("image/")) {
            const img = document.createElement("img");
            img.src = URL.createObjectURL(selectedFile);
            img.className =
                "w-full h-40 object-cover rounded-lg border border-gray-300 dark:border-gray-600";
            previewItem.appendChild(img);
        } else if (selectedFile.type === "video/mp4") {
            const video = document.createElement("video");
            video.src = URL.createObjectURL(selectedFile);
            video.controls = true;
            video.className =
                "w-full h-40 object-cover rounded-lg border border-gray-300 dark:border-gray-600";
            previewItem.appendChild(video);
        }

        previewItem.appendChild(removeBtn);
        previewContainer.appendChild(previewItem);
    }

    function removeFile() {
        selectedFile = null;
        input.value = "";
        previewContainer.innerHTML = "";
    }

    // sebelum submit, masukkan file ke input
    const form = document.querySelector("form");
    form.addEventListener("submit", function (e) {
        if (selectedFile) {
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(selectedFile);
            input.files = dataTransfer.files;
        }
    });
});
</script>


    {{-- Custom Scrollbar --}}
    <style>
        #search-results::-webkit-scrollbar {
            width: 8px;
        }

        #search-results::-webkit-scrollbar-track {
            background: #374151;
        }

        #search-results::-webkit-scrollbar-thumb {
            background: #6b7280;
            border-radius: 4px;
        }

        #search-results::-webkit-scrollbar-thumb:hover {
            background: #9ca3af;
        }
    </style>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
