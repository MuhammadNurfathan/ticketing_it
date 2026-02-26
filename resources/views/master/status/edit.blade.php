<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Status') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('status.update', $status) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-white mb-2">Nama Status</label>
                            <input type="text" name="name" value="{{ old('name', $status->name) }}" 
                                class="w-full border border-gray-300 dark:border-gray-600 dark:text-white    text-gray-800 bg-white dark:bg-gray-600 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500" 
                                placeholder="Contoh: Open, In Progress, Closed" 
                                required>
                                </div>
                                  <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-white mb-2">Type Status</label>
                            <input type="text" name="type" value="{{ old('type', $status->type) }}" 
                                class="w-full border border-gray-300 dark:border-gray-600 dark:text-white    text-gray-800 bg-white dark:bg-gray-600 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500" 
                                placeholder="Contoh: Open, In Progress, Closed" 
                                required>
                                  </div>
                                    <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-white mb-2">Context</label>
                            <select name="context" id="context" class="w-full border border-gray-300 dark:border-gray-600 dark:text-white text-gray-800 bg-white dark:bg-gray-600 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="">-- Pilih Context --</option>
                                <option value="ticket" {{ old('context', $status->context) == 'ticket' ? 'selected' : '' }}>Ticket</option>
                                <option value="project" {{ old('context', $status->context) == 'project' ? 'selected' : '' }}>Project</option>
                        </select>
                                    </div>
                        

                        <div class="flex items-center gap-3">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Update
                            </button>
                            <a href="{{ route('status.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Kembali
                            </a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>