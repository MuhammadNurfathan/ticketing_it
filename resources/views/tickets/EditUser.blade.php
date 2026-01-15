<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Ticket') }}
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

                    {{-- Form Edit Ticket --}}
                    <form action="{{ route('DashboardTicketsUser.update', $ticket->id) }}" method="POST" enctype="multipart/form-data" id="edit-form">
                        @csrf
                        @method('PUT')

                        {{-- Ticket Code (READ ONLY) --}}
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Ticket Code
                            </label>
                            <input type="text" value="{{ $ticket->ticket_code }}" readonly
                                class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm px-3 py-2 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100 cursor-not-allowed">
                        </div>

                        {{-- Nama Pembuat (EDITABLE) --}}
                        <div class="mb-4">
                            <label for="nama_pembuat" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Nama Pembuat <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nama_pembuat" id="nama_pembuat" 
                                value="{{ old('nama_pembuat', $ticket->nama_pembuat) }}" 
                                required
                                class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Masukkan nama pembuat">
                            @error('nama_pembuat')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Category (EDITABLE) --}}
                        <div class="mb-4">
                            <label for="problem_category_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Category <span class="text-red-500">*</span>
                            </label>
                            <select name="problem_category_id" id="problem_category_id" required
                                class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="" disabled {{ old('problem_category_id', $ticket->problem_category_id) ? '' : 'selected' }}>
                                    -- Pilih Category --
                                </option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}"
                                        {{ old('problem_category_id', $ticket->problem_category_id) == $cat->id ? 'selected' : '' }}
                                        class="bg-white text-gray-900 dark:bg-gray-700 dark:text-gray-100">
                                        {{ $cat->problem_category_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('problem_category_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Problem (EDITABLE) --}}
                        <div class="mb-4">
                            <label for="problem" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Problem <span class="text-red-500">*</span>
                            </label>
                            <textarea name="problem" id="problem" rows="4" required
                                class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Jelaskan masalah yang dihadapi">{{ old('problem', $ticket->problem) }}</textarea>
                            @error('problem')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Buttons --}}
                        <div class="flex gap-2">
                            <button type="submit"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md transition-colors duration-200 shadow-sm">
                                Update Ticket
                            </button>
                            <button type="button" onclick="history.back()"
                                class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md transition-colors duration-200 shadow-sm">
                                Back
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>