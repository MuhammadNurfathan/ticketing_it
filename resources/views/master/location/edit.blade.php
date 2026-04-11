<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100">
                Edit Location
            </h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Update data location yang sudah ada
            </p>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="w-full mx-auto px-4 sm:px-6 lg:px-8">

            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-lg rounded-xl">

                <div class="p-6">

                    <form action="{{ route('locations.update', $location) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- ERROR (SAMA STYLE DEPARTMENT) --}}
                        @if ($errors->any())
                            <div class="mb-6">
                                <div class="text-sm text-red-500">
                                    <ul class="list-disc list-inside">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif

                        {{-- NAME --}}
                        <div class="mb-6">
                            <label class="block text-sm font-medium mb-2">
                                Nama Location
                            </label>

                            <input type="text" name="name"
                                value="{{ old('name', $location->name) }}"
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

                        {{-- BUTTON (SAMA PERSIS DEPARTMENT STYLE) --}}
                        <div class="flex justify-between items-center pt-4 border-t border-gray-200 dark:border-gray-700">

                            <a href="{{ route('locations.index') }}"
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