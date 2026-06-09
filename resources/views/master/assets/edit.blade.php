<x-app-layout>

    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100">
                Edit Asset
            </h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Update data asset yang sudah ada
            </p>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="w-full mx-auto px-4 sm:px-6 lg:px-8">

            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-lg rounded-xl">

                <div class="p-6">

                    {{-- ERROR --}}
                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('assets.update', $asset) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        {{-- KODE --}}
                        <div class="mb-6">
                            <label class="block text-sm font-medium mb-2">Kode Asset</label>
                            <input type="text" name="code"
                                value="{{ old('code', $asset->code) }}"
                                class="w-full rounded-lg border border-gray-300 dark:border-gray-600
                                bg-gray-50 dark:bg-gray-700
                                focus:bg-white dark:focus:bg-gray-800
                                focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                                transition duration-200"
                                placeholder="Contoh: AST-001" required>
                        </div>

                        {{-- NAMA --}}
                        <div class="mb-6">
                            <label class="block text-sm font-medium mb-2">Nama Asset</label>
                            <input type="text" name="name"
                                value="{{ old('name', $asset->name) }}"
                                class="w-full rounded-lg border border-gray-300 dark:border-gray-600
                                bg-gray-50 dark:bg-gray-700
                                focus:bg-white dark:focus:bg-gray-800
                                focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                                transition duration-200"
                                placeholder="Contoh: Laptop Lenovo ThinkPad" required>
                        </div>

                        {{-- KATEGORI --}}
                        <div class="mb-6">
                            <label class="block text-sm font-medium mb-2">Kategori</label>
                            <input type="text" name="category"
                                value="{{ old('category', $asset->category) }}"
                                class="w-full rounded-lg border border-gray-300 dark:border-gray-600
                                bg-gray-50 dark:bg-gray-700
                                focus:bg-white dark:focus:bg-gray-800
                                focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                                transition duration-200"
                                placeholder="Contoh: Laptop / PC" required>
                        </div>

                        {{-- STATUS --}}
                        <div class="mb-6">
                            <label class="block text-sm font-medium mb-2">Status</label>
                            <select name="status"
                                class="w-full rounded-lg border border-gray-300 dark:border-gray-600
                                bg-gray-50 dark:bg-gray-700
                                focus:bg-white dark:focus:bg-gray-800
                                focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                                transition duration-200"
                                required>
                                <option value="Available" {{ old('status', $asset->status) == 'Available' ? 'selected' : '' }}>
                                    Available
                                </option>
                                <option value="Checked Out" {{ old('status', $asset->status) == 'Checked Out' ? 'selected' : '' }}>
                                    Checked Out
                                </option>
                            </select>
                        </div>


                        {{-- BUTTON --}}
                        <div class="flex justify-between items-center pt-4 border-t border-gray-200 dark:border-gray-700">

                            <a href="{{ route('assets.index') }}"
                               class="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300
                               dark:bg-gray-700 dark:hover:bg-gray-600
                               text-gray-800 dark:text-gray-200 transition">
                                Kembali
                            </a>

                            <button type="submit"
                                    class="px-6 py-2 rounded-lg bg-blue-600 hover:bg-blue-700
                                    text-white font-medium shadow-sm transition">
                                Update
                            </button>

                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>

</x-app-layout>