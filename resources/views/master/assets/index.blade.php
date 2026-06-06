<x-app-layout>

    <x-slot name="header">
        <div class="flex items-center justify-between">

            <div>
                <h2 class="text-xl font-semibold text-gray-800 dark:text-white">
                    Kelola Assets
                </h2>

                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Kelola data asset, status, lokasi, dan user peminjam
                </p>
            </div>

            <a href="{{ route('assets.create') }}"
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
                        <th class="p-3 text-left">Kode Assets</th>
                        <th class="p-3 text-left">Nama Assets</th>
                        <th class="p-3 text-left">User</th>
                        <th class="p-3 text-left">Kategori</th>
                        <th class="p-3 text-left">Status</th>
                        <th class="p-3 text-left">Lokasi</th>
                        <th class="p-3 text-left">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($assets as $index => $a)

                        <tr class="border-b border-gray-200 dark:border-gray-700">

                            <td class="p-3 text-gray-700 dark:text-gray-200">
                                {{ $index + 1 }}
                            </td>

                            <td class="p-3 text-gray-700 dark:text-gray-200">
                                {{ $a->assets_code ?? '-' }}
                            </td>

                            <td class="p-3 text-gray-700 dark:text-gray-200">
                                {{ $a->assets_name ?? '-' }}
                            </td>

                            <td class="p-3 text-gray-700 dark:text-gray-200">
                                {{ $a->check_out_to ?? '-' }}
                            </td>

                            <td class="p-3 text-gray-700 dark:text-gray-200">
                                {{ $a->category ?? '-' }}
                            </td>

                            <td class="p-3">
                                <span class="bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300 px-3 py-1 rounded-lg text-xs">
                                    {{ $a->status ?? '-' }}
                                </span>
                            </td>

                            <td class="p-3 text-gray-700 dark:text-gray-200">
                                {{ $a->location ?? '-' }}
                            </td>

                            <td class="p-3 flex gap-2">

                                <a href="{{ route('assets.edit', $a) }}"
                                    class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded-lg">
                                    Edit
                                </a>

                                <form method="POST"
                                    action="{{ route('assets.destroy', $a) }}"
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
