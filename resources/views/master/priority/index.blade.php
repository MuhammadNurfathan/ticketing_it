
<x-app-layout>

    <x-slot name="header">
        <div class="flex items-center justify-between">

            <div>
                <h2 class="text-xl font-semibold text-gray-800 dark:text-white">
                    Kelola Priority
                </h2>

                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Kelola daftar priority & aksi cepat
                </p>
            </div>

            <a href="{{ route('priority.create') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition">
                Tambah
            </a>

        </div>
    </x-slot>

    <div class="p-6">

        <x-alert />

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4">

            <table class="w-full text-sm">

                <thead class="bg-gray-100 dark:bg-gray-700">
                    <tr>
                        <th class="p-3 text-left">No</th>
                        <th class="p-3 text-left">Nama Priority</th>
                        <th class="p-3 text-left">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($priorities as $index => $priority)

                        <tr class="border-b border-gray-200 dark:border-gray-700">

                            <td class="p-3 text-gray-700 dark:text-gray-200">
                                {{ $index + 1 }}
                            </td>

                            <td class="p-3 text-gray-700 dark:text-gray-200">
                                {{ $priority->name ?? '-' }}
                            </td>

                            <td class="p-3 flex gap-2">

                                <a href="{{ route('priority.edit', $priority) }}"
                                    class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded-lg">
                                    Edit
                                </a>

                                <form method="POST"
                                    action="{{ route('priority.destroy', $priority) }}"
                                    class="delete-form">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                        class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded-lg">
                                        Delete
                                    </button>

                                </form>

                            </td>

                        </tr>

                    @endforeach
                </tbody>

            </table>

        </div>

    </div>

    <x-delete-alert />

</x-app-layout>
