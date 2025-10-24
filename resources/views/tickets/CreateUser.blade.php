<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tambah Ticket') }}
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
                    <form action="{{ route('DashboardTicketsAdmin.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        ` <input type="hidden" name="from" value="user">
                        {{-- Ticket Code --}}
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Ticket Code <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="ticket_code" value="{{ $generateticket }}" readonly
                                class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm px-3 py-2 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                        </div>

                        {{-- User (otomatis sesuai login, readonly) --}}
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                User
                            </label>
                            <input type="text" value="{{ Auth::user()->name }}" readonly
                                class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm px-3 py-2 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                            <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                        </div>

                        {{-- USER --}}
                        <div class="mb-4 hidden">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                User
                            </label>
                            <input type="text" readonly
                                class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm px-3 py-2 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                            <input type="hidden" name="status_id" value="1">
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
                                    <option value="{{ $cat->id }}"
                                        {{ old('problem_category_id') == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->problem_category_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Problem --}}
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Problem <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="problem" placeholder="Masukkan Kendala..."
                                class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                        </div>



                        {{-- Upload Media (1 file saja) --}}
                        <div class="mb-6">
                            <label for="media" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Upload Gambar / Video (Max 10 MB)
                            </label>
                            <input type="file" name="image" id="media" accept="image/*,video/*" capture="environment"
                                class="mt-1 block w-full text-sm text-gray-900 dark:text-gray-100 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700  hover:file:bg-blue-100 dark:file:bg-gray-700 dark:file:text-gray-200  dark:hover:file:bg-gray-600">
                            <div id="preview-container"
                                class="mt-4 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3"></div>
                        </div>

                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">Simpan Ticket
                        </button>

                    </form>

                    {{-- jQuery --}}
                    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

                    {{-- TRIGGER FIELD BY STATUS --}}
                    <script>
                        document.addEventListener("DOMContentLoaded", function() {
                            const statusSelect = document.getElementById("status-select");
                            const startContainer = document.getElementById("start-date-container");
                            const endContainer = document.getElementById("end-date-container");
                            const timeContainer = document.getElementById("time-spent-container");
                            const startInput = document.getElementById("start_datetime");
                            const endInput = document.getElementById("end_datetime");
                            const timeInput = document.getElementById("time_spent");
                            const manualCheckbox = document.getElementById("manual_time");

                            // === STATUS TRIGGER ===
                            statusSelect.addEventListener("change", function() {
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
                            manualCheckbox.addEventListener("change", function() {
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
                            const maxSize = 5 * 1024 * 1024;
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

                    {{-- IMAGE --}}
                    <script>
                        document.addEventListener("DOMContentLoaded", function() {
                            const input = document.getElementById("media");
                            const previewContainer = document.getElementById("preview-container");
                            let selectedFile = null; // cuma 1 file

                            input.addEventListener("change", function(e) {
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
                            form.addEventListener("submit", function(e) {
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
