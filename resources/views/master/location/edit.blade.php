<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Location') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    @if ($errors->any())
                        <div class="bg-red-100 dark:bg-red-200 border border-red-400 dark:border-red-600 text-red-700 dark:text-red-800 px-4 py-3 rounded relative mb-4" role="alert">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('locations.update', $location) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nama Location</label>
                            <input type="text" name="location_name" value="{{ old('location_name', $location->location_name) }}" 
                                class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm px-3 py-2 
                                text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-700 
                                focus:outline-none focus:ring-blue-500 focus:border-blue-500" 
                                required>
                        </div>

                        <div class="flex items-center gap-3">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 dark:hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                                Update
                            </button>
                            <a href="{{ route('locations.index') }}" class="bg-gray-500 hover:bg-gray-700 dark:bg-gray-600 dark:hover:bg-gray-500 text-white font-bold py-2 px-4 rounded">
                                Kembali
                            </a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
