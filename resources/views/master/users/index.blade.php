<x-app-layout>

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-800 dark:text-white">
                    Users
                </h2>

                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Kelola user, email, dan role akses
                </p>
            </div>

            <a href="{{ route('users.create') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition">
                Tambah
            </a>
        </div>
    </x-slot>

    <div class="p-6">

        <x-alert />

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4">

            <div class="overflow-x-auto">

                <table class="min-w-full text-sm whitespace-nowrap">

                    <thead class="bg-gray-100 dark:bg-gray-700 uppercase text-xs tracking-wider">
                        <tr>
                            <th class="p-3 text-left">No</th>
                            <th class="p-3 text-left">Username</th>
                            <th class="p-3 text-left">Email</th>
                            <th class="p-3 text-left">Phone</th>
                            <th class="p-3 text-left">Posisi</th>
                            <th class="p-3 text-left">Status</th>
                            <th class="p-3 text-left">Department</th>
                            <th class="p-3 text-left">Location</th>
                            <th class="p-3 text-left">Role</th>
                            <th class="p-3 text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($users as $index => $user)

                            <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                <td class="p-3 text-gray-700 dark:text-gray-200">{{ $index + 1 }}</td>
                                <td class="p-3 text-gray-700 dark:text-gray-200">{{ $user->username }}</td>
                                <td class="p-3 text-gray-700 dark:text-gray-200">{{ $user->email }}</td>
                                <td class="p-3 text-gray-700 dark:text-gray-200">{{ $user->phone ?? '-' }}</td>
                                <td class="p-3 text-gray-700 dark:text-gray-200">{{ $user->job_position ?? '-' }}</td>
                                <td class="p-3">
                                    @if($user->status === 'Aktif')
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-700">
                                            Aktif
                                        </span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-700">
                                            Non Aktif
                                        </span>
                                    @endif
                                </td>
                                <td class="p-3 text-gray-700 dark:text-gray-200">{{ $user->department->name ?? '-' }}</td>
                                <td class="p-3 text-gray-700 dark:text-gray-200">{{ $user->department->location->name ?? '-' }}</td>
                                <td class="p-3 text-gray-700 dark:text-gray-200">{{ $user->role->name ?? '-' }}</td>
                                <td class="p-3">
                                    <div class="flex justify-center gap-2">
                                        <a href="{{ route('users.edit', $user) }}"
                                            class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded-lg transition">
                                            Edit
                                        </a>
                                        <form method="POST"
                                            action="{{ route('users.destroy', $user) }}"
                                            class="delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded-lg transition">
                                                Delete
                                            </button>
                                        </form>

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="10" class="text-center py-6 text-gray-500 dark:text-gray-400"> Belum ada data user.</td></tr>
                        @endforelse
                    </tbody>

                </table>

            </div>

        </div>

    </div>

    <x-delete-alert />

</x-app-layout>