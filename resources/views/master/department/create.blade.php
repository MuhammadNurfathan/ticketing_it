<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100">
                Tambah Department
            </h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Tambahkan department baru ke dalam sistem
            </p>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="w-full mx-auto px-4 sm:px-6 lg:px-8">

            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-lg rounded-xl">

                <div class="p-6">
                    <form action="{{ route('departments.store') }}" method="POST">
                        @csrf

                        {{-- Nama Department --}}
                        <div class="mb-6">
                            <label class="block text-sm font-medium mb-2">
                                Nama Department
                            </label>
                            <input type="text" name="name"
                                   value="{{ old('name') }}"
                                   class="w-full rounded-lg border border-gray-300 dark:border-gray-600 
                                   bg-gray-50 dark:bg-gray-700 
                                   focus:bg-white dark:focus:bg-gray-800
                                   focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                                   transition duration-200
                                   @error('name') border-red-500 @enderror"
                                   placeholder="Contoh: Jakarta">

                            @error('name')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Location --}}
                        <div class="mb-6">
                            <label class="block text-sm font-medium mb-2">
                                Pilih Location
                            </label>
                            <select name="location_id"
                                    class="w-full rounded-lg border border-gray-300 dark:border-gray-600 
                                    bg-gray-50 dark:bg-gray-700 
                                    focus:bg-white dark:focus:bg-gray-800
                                    focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                                    transition duration-200
                                    @error('location_id') border-red-500 @enderror">

                                <option value="">-- Pilih Location --</option>

                                @foreach ($locations as $location)
                                    <option value="{{ $location->id }}" {{ old('location_id') == $location->id ? 'selected' : '' }}>
                                        {{ $location->name }}
                                    </option>
                                @endforeach
                            </select>

                            @error('location_id')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Button --}}
                        <div class="flex justify-between items-center pt-4 border-t border-gray-200 dark:border-gray-700">
                            <a href="{{ route('departments.index') }}"
                               class="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300 
                               dark:bg-gray-700 dark:hover:bg-gray-600 
                               text-gray-800 dark:text-gray-200 transition">
                                Kembali
                            </a>

                            <button type="submit"
                                    class="px-6 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 
                                    text-white font-medium shadow-sm transition">
                                Simpan
                            </button>
                        </div>

                    </form>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>x