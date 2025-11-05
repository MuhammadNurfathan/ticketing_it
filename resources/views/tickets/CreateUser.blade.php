<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tambah Ticket') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-light-eval-1 dark:bg-dark-eval-1 rounded-lg shadow-md p-4 border border-gray-200 dark:border-gray-700">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    {{-- Error Alert --}}
                    @if ($errors->any())
                        <div class="bg-red-100 dark:bg-red-900 border border-red-400 dark:border-red-600 text-red-700 dark:text-red-200 px-4 py-3 rounded mb-4">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Form Ticket --}}
                    <form action="{{ route('DashboardTicketsAdmin.store') }}" method="POST" enctype="multipart/form-data" id="ticket-form">
                        @csrf
                        <input type="hidden" name="from" value="user">
                        <input type="hidden" name="status_id" value="1">
                        
                        {{-- Ticket Code --}}
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Ticket Code <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="ticket_code" value="{{ $generateticket }}" readonly required
                                class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm px-3 py-2 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                        </div>

                        {{-- User (otomatis sesuai login, readonly) --}}
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                User <span class="text-red-500">*</span>
                            </label>
                            <input type="text" value="{{ Auth::user()->name }}" readonly
                                class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm px-3 py-2 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                            <input type="hidden" name="user_id" value="{{ Auth::user()->id }}" required>
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
                                    <option value="{{ $cat->id }}" {{ old('problem_category_id') == $cat->id ? 'selected' : '' }}>
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
                            <textarea name="problem" placeholder="Enter Problem..." 
                                class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-blue-500 focus:border-blue-500"></textarea>
                        </div>

                        {{-- Upload Media (OPTIONAL - Max 5 MB) --}}
                        <div class="mb-6">
                            <label for="media" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Upload Image / Video (Max 5 MB)
                            </label>
                            <input type="file" name="image" id="media" accept=".jpg,.jpeg,.png,.mp4" capture="environment"
                                class="block w-full text-sm text-gray-900 dark:text-gray-100 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-gray-700 dark:file:text-gray-200 dark:hover:file:bg-gray-600">
                            <div id="preview-container" class="mt-4"></div>
                        </div>

                        {{-- Buttons --}}
                        <div class="flex gap-2">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md transition-colors duration-200">
                                Save
                            </button>
                            <button type="button" onclick="history.back()" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md transition-colors duration-200">
                                Back
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    {{-- Scripts --}}
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

    {{-- Media Preview & Validation (Max 5MB, OPTIONAL) --}}
    <script>
        const mediaInput = document.getElementById("media");
        const previewContainer = document.getElementById("preview-container");
        const MAX_SIZE = 5 * 1024 * 1024; // 5MB

        mediaInput.addEventListener("change", function(e) {
            previewContainer.innerHTML = "";
            
            const file = e.target.files[0];
            if (!file) return;

            // Validasi ukuran
            if (file.size > MAX_SIZE) {
                alert("❌ File terlalu besar! Maksimal 5MB");
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

        // Form validation sebelum submit
        document.getElementById('ticket-form').addEventListener('submit', function(e) {
            const problem = document.querySelector('textarea[name="problem"]').value.trim();

        });
    </script>

</x-app-layout>