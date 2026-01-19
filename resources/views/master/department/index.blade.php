<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl leading-tight text-light-text dark:text-dark-text">
                    {{ __('Kelola Department') }}
                </h2>
                <p class="text-xs mt-1 text-light-text-secondary dark:text-dark-text-secondary">
                    Kelola daftar department dan aksi cepat
                </p>
            </div>

            <a href="{{ route('departments.create') }}"
                class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold
                      bg-blue-600 hover:bg-blue-700 text-white transition-colors shadow-sm">
                <span class="text-base">＋</span>
                <span>Tambah Department</span>
            </a>
        </div>
    </x-slot>

    @php
        $page = 'min-h-screen bg-light-bg dark:bg-dark-bg';
        $wrap = 'w-full px-4 sm:px-6 lg:px-8 py-6 space-y-6';

        $card = "rounded-2xl border shadow-sm overflow-hidden
                 bg-light-eval-1 dark:bg-dark-eval-1
                 border-light-eval-3 dark:border-dark-eval-2";

        $thead = "bg-light-eval-2 dark:bg-dark-eval-2 border-b
                  border-light-eval-3 dark:border-dark-eval-2";

        $th = "px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap
               text-light-text-secondary dark:text-dark-text-secondary";

        $td = 'px-4 py-3 text-sm text-light-text-secondary dark:text-dark-text-secondary';
    @endphp

    <div class="{{ $page }}">
        <div class="{{ $wrap }}">

            {{-- Alert Success --}}
            @if (session('success'))
                <div
                    class="rounded-xl border px-4 py-3 bg-green-600/10 dark:bg-green-400/10 border-green-600/20 dark:border-green-400/20">
                    <div class="text-sm font-medium text-green-700 dark:text-green-300">
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            {{-- Alert Error --}}
            @if (session('error'))
                <div
                    class="rounded-xl border px-4 py-3 bg-red-600/10 dark:bg-red-400/10 border-red-600/20 dark:border-red-400/20">
                    <div class="text-sm font-medium text-red-700 dark:text-red-300">
                        {{ session('error') }}
                    </div>
                </div>
            @endif

            <div class="{{ $card }}">
                <x-datatable-wrapper title="List Department" subtitle="Daftar department & aksi cepat"
                    :count="$departments->count()">
                    <table class="datatable w-full min-w-[600px] text-sm">
                        <thead class="{{ $thead }}">
                            <tr>
                                <th class="{{ $th }} w-14 text-center">No</th>
                                <th class="{{ $th }}">Nama Department</th>
                                <th class="{{ $th }} text-right">Aksi</th>
                            </tr>
                        </thead>

                        <tbody
                            class="divide-y divide-light-eval-3 dark:divide-dark-eval-2 bg-light-bg dark:bg-dark-eval-2">
                            @forelse ($departments as $index => $department)
                                <tr class="transition-colors hover:bg-light-eval-2 dark:hover:bg-dark-eval-1">
                                    <td
                                        class="px-4 py-3 text-center text-sm font-semibold text-light-text dark:text-dark-text">
                                        {{ $index + 1 }}
                                    </td>

                                    <td class="px-4 py-3">
                                        <div class="text-sm font-semibold text-light-text dark:text-dark-text">
                                            {{ $department->department_name ?? '-' }}
                                        </div>
                                    </td>

                                    <td class="px-4 py-3">
                                        <div class="flex justify-end gap-2">
                                            <a href="{{ route('departments.edit', $department) }}"
                                                class="inline-flex items-center justify-center px-3 py-1.5 rounded-lg text-xs font-semibold
                                                       border border-light-eval-3 dark:border-dark-eval-2
                                                       bg-light-bg dark:bg-dark-eval-1
                                                       text-light-text-secondary dark:text-dark-text-secondary
                                                       hover:bg-light-eval-2 dark:hover:bg-dark-eval-2 transition-colors">
                                                Edit
                                            </a>

                                            <form action="{{ route('departments.destroy', $department) }}"
                                                method="POST"
                                                onsubmit="return confirm('Yakin ingin menghapus department ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="inline-flex items-center justify-center px-3 py-1.5 rounded-lg text-xs font-semibold
                                                           border border-red-600/25 dark:border-red-400/25
                                                           text-red-600 dark:text-red-300
                                                           hover:bg-red-600/10 dark:hover:bg-red-400/10 transition-colors">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3"
                                        class="py-10 text-center text-sm italic text-light-text-muted dark:text-dark-text-secondary">
                                        Tidak ada data Department
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </x-datatable-wrapper>
            </div>

        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            new DataTable(".datatable", {
                responsive: false,
                pageLength: 10,
                layout: {
                    topStart: "pageLength",
                    topEnd: "search",
                    bottomStart: "info",
                    bottomEnd: "paging"
                }
            });
        });
    </script>
</x-app-layout>
