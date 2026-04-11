<x-app-layout>

    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100">
                Buat Ticket
            </h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Tambahkan ticket baru untuk melaporkan masalah
            </p>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="w-full mx-auto px-4 sm:px-6 lg:px-8">

            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-lg rounded-xl">

                <div class="p-6">

                    {{-- ERROR --}}
                    @if ($errors->any())
                        <div class="mb-6 rounded-lg border border-red-300 bg-red-50 dark:bg-red-900/20 p-4 text-red-700 dark:text-red-300">
                            <ul class="list-disc list-inside text-sm">
                                @foreach ($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('DashboardTicketsUser.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <input type="hidden" name="from" value="user">
                        <input type="hidden" name="user_id" value="{{ Auth::id() }}">

                        {{-- CATEGORY --}}
                        <div class="mb-6">
                            <label class="block text-sm font-medium mb-2">
                                Category <span class="text-red-500">*</span>
                            </label>

                            <select name="category_id" required
                                class="w-full rounded-lg border border-gray-300 dark:border-gray-600 
                                bg-gray-50 dark:bg-gray-700 
                                focus:bg-white dark:focus:bg-gray-800
                                focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                                transition duration-200">

                                <option value="">-- Pilih Category --</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}">
                                        {{ $cat->name }}
                                    </option>
                                @endforeach

                            </select>
                        </div>

                        {{-- PROBLEM --}}
                        <div class="mb-6">
                            <label class="block text-sm font-medium mb-2">
                                Problem <span class="text-red-500">*</span>
                            </label>

                            <textarea name="problem" rows="4" required
                                class="w-full rounded-lg border border-gray-300 dark:border-gray-600 
                                bg-gray-50 dark:bg-gray-700 
                                focus:bg-white dark:focus:bg-gray-800
                                focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                                transition duration-200"
                                placeholder="Jelaskan masalah..."></textarea>
                        </div>

                        {{-- IMAGE --}}
                        <div class="mb-6">
                            <label class="block text-sm font-medium mb-2">
                                Upload (optional)
                            </label>

                            <input type="file" name="image"
                                class="w-full text-sm text-gray-700 dark:text-gray-200">
                        </div>

                        {{-- BUTTON --}}
                        <div class="flex justify-between items-center pt-4 border-t border-gray-200 dark:border-gray-700">

                            <a href="{{ route('DashboardTicketsUser.index') }}"
                               class="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300
                               dark:bg-gray-700 dark:hover:bg-gray-600
                               text-gray-800 dark:text-gray-200 transition">
                                Back
                            </a>

                            <button type="submit"
                                class="px-6 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 
                                text-white font-medium shadow-sm transition">
                                Submit
                            </button>

                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>

</x-app-layout>