<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
            Buat Ticket
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-3xl mx-auto px-6">

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow border p-6">

                {{-- ERROR --}}
                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-100 border border-red-400 rounded text-red-700">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('DashboardTicketsAdmin.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <input type="hidden" name="from" value="user">
                    <input type="hidden" name="user_id" value="{{ Auth::id() }}">

                    {{-- CATEGORY --}}
                    <div class="mb-5">
                        <label class="block mb-2 text-sm font-medium">
                            Category <span class="text-red-500">*</span>
                        </label>
                        <select name="category_id" required
                            class="w-full rounded border px-3 py-2 dark:bg-gray-700">
                            <option hidden>-- Pilih Category --</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}">
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- PROBLEM --}}
                    <div class="mb-5">
                        <label class="block mb-2 text-sm font-medium">
                            Problem <span class="text-red-500">*</span>
                        </label>
                        <textarea name="problem" rows="4" required
                            class="w-full rounded border px-3 py-2 dark:bg-gray-700"
                            placeholder="Jelaskan masalah..."></textarea>
                    </div>

                    {{-- IMAGE --}}
                    <div class="mb-6">
                        <label class="block mb-2 text-sm font-medium">
                            Upload (optional)
                        </label>
                        <input type="file" name="image"
                            class="block w-full text-sm">
                    </div>

                    {{-- BUTTON --}}
                    <div class="flex gap-3">
                        <button type="submit"
                            class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded">
                            Submit
                        </button>

                        <a href="{{ route('DashboardTicketsUser.index') }}"
                            class="px-5 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded">
                            Back
                        </a>
                    </div>

                </form>
            </div>

        </div>
    </div>
</x-app-layout>