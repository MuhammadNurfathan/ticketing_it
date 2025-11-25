<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-dark-eval-1 dark:text-light-eval-1 leading-tight">

                {{ 'Kelola Department' }}
            </h2>
            <a href="{{ route('departments.create') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow">
                Tambah Department
            </a>
        </div>
    </x-slot>

    <div class="p-6 space-y-6">

        {{-- Alert Success --}}
        @if (session('success'))
            <div class="bg-green-800 border border-green-700 text-green-200 px-4 py-3 rounded relative mb-4"
                role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        {{-- Alert Error --}}
        @if (session('error'))
            <div class="bg-red-800 border border-red-700 text-red-200 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <div
            class="bg-light-eval-1 dark:bg-dark-eval-1 rounded-lg shadow-md p-4 border border-gray-200 dark:border-gray-700">
            <h3
                class="font-semibold text-lg mb-2 border-b border-gray-300 dark:border-gray-600 pb-1 text-light-text dark:text-dark-text">
                List Department
            </h3>

            <div class="overflow-x-auto">
                <table class="datatable min-w-full border border-gray-300 dark:border-gray-600 text-sm">
                    <thead class="bg-light-eval-2 dark:bg-dark-eval-2 text-left">
                        <tr>
                            <th
                                class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                No</th>
                            <th
                                class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                Nama Department</th>
                            <th
                                class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-dark-eval-1">
                        @forelse ($departments as $index => $department)
                            <tr class="hover:bg-light-eval-2 dark:hover:bg-dark-eval-2">
                                <td
                                    class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                    {{ $index + 1 }}
                                </td>
                                <td
                                    class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                    {{ $department->department_name }}
                                </td>
                                <td
                                    class="border border-gray-300 dark:border-gray-600 p-2 text-light-text dark:text-dark-text">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('departments.edit', $department) }}"
                                            class="border border-gray-300 dark:border-gray-600 px-3 py-1 text-light-text dark:text-dark-text rounded hover:bg-gray-100 dark:hover:bg-gray-800">
                                            Edit
                                        </a>
                                        <form action="{{ route('departments.destroy', $department) }}" method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus department ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="px-3 py-1 text-red-400 hover:text-red-300 border border-gray-300 dark:border-gray-600 rounded">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-2 py-2 text-center text-gray-500">
                                    Tidak ada data Department
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    new DataTable(".datatable", {
        responsive: true,
        pageLength: 10,
        layout: {
            topStart: "pageLength",   // ✔️ fitur resmi v2
            topEnd: "search",
            bottomStart: "info",
            bottomEnd: "paging"
        }
    });
});
</script>

 
</x-app-layout>
