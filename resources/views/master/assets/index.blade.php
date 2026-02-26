<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl leading-tight text-light-text dark:text-dark-text">
                    {{ __('Kelola Assets') }}
                </h2>
                <p class="text-xs mt-1 text-light-text-secondary dark:text-dark-text-secondary">
                    Kelola data asset, status, lokasi, dan user peminjam
                </p>
            </div>

            <a href="{{ route('assets.create') }}"
                class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold
                       bg-blue-600 hover:bg-blue-700 text-white transition-colors shadow-sm">
                <span class="text-base">＋</span>
                <span>Tambah Assets</span>
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

        $pill = 'inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold';
    @endphp

    <div class="{{ $page }}">
        <div class="{{ $wrap }}">

            {{-- Alert Success --}}
            @if (session('success'))
                <div class="rounded-xl border px-4 py-3 bg-green-600/10 dark:bg-green-400/10 border-green-600/20 dark:border-green-400/20">
                    <div class="text-sm font-medium text-green-700 dark:text-green-300">
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            {{-- Alert Error --}}
            @if (session('error'))
                <div class="rounded-xl border px-4 py-3 bg-red-600/10 dark:bg-red-400/10 border-red-600/20 dark:border-red-400/20">
                    <div class="text-sm font-medium text-red-700 dark:text-red-300">
                        {{ session('error') }}
                    </div>
                </div>
            @endif

            <div class="{{ $card }}">
                <x-datatable-wrapper
                    title="Daftar Assets"
                    subtitle="Data asset lengkap dan aksi cepat"
                    :count="$assets->count()"
                >
                    <table class="datatable w-full min-w-[900px] text-sm">
                        <thead class="{{ $thead }}">
                            <tr>
                                <th class="{{ $th }} w-14 text-center">No</th>
                                <th class="{{ $th }}">Kode Assets</th>
                                <th class="{{ $th }}">Nama Assets</th>
                                <th class="{{ $th }}">User</th>
                                <th class="{{ $th }}">Kategori</th>
                                <th class="{{ $th }}">Status</th>
                                <th class="{{ $th }}">Lokasi</th>
                                <th class="{{ $th }} text-right">Aksi</th>
                            </tr>
                        </thead>

                        {{-- IMPORTANT:
                             kalau kosong, jangan render row @empty.
                             biarkan DataTables yang tampilkan emptyTable. --}}
                        <tbody class="divide-y divide-light-eval-3 dark:divide-dark-eval-2 bg-light-bg dark:bg-dark-eval-2">
                            @foreach ($assets as $index => $a)
                                @php
                                    $status = strtolower($a->status ?? '');

                                    if (str_contains($status, 'available')) {
                                        $statusBadge = 'bg-green-600/10 text-green-700 dark:bg-green-400/10 dark:text-green-300';
                                    } elseif (str_contains($status, 'use') || str_contains($status, 'borrow') || str_contains($status, 'in')) {
                                        $statusBadge = 'bg-blue-600/10 text-blue-700 dark:bg-blue-400/10 dark:text-blue-300';
                                    } elseif (str_contains($status, 'broken') || str_contains($status, 'damage')) {
                                        $statusBadge = 'bg-red-600/10 text-red-700 dark:bg-red-400/10 dark:text-red-300';
                                    } else {
                                        $statusBadge = 'bg-light-eval-2 text-light-text-secondary dark:bg-dark-eval-1 dark:text-dark-text-secondary';
                                    }
                                @endphp

                                <tr class="transition-colors hover:bg-light-eval-2 dark:hover:bg-dark-eval-1">
                                    <td class="px-4 py-3 text-center text-sm font-semibold text-light-text dark:text-dark-text">
                                        {{ $index + 1 }}
                                    </td>

                                    <td class="{{ $td }} whitespace-nowrap">
                                        {{ $a->assets_code ?? '-' }}
                                    </td>

                                    <td class="px-4 py-3">
                                        <div class="text-sm font-semibold text-light-text dark:text-dark-text">
                                            {{ $a->assets_name ?? '-' }}
                                        </div>
                                    </td>

                                    <td class="{{ $td }}">
                                        {{ $a->check_out_to ?? '-' }}
                                    </td>

                                    <td class="{{ $td }}">
                                        {{ $a->category ?? '-' }}
                                    </td>

                                    <td class="px-4 py-3">
                                        <span class="{{ $pill }} {{ $statusBadge }}">
                                            {{ $a->status ?? '-' }}
                                        </span>
                                    </td>

                                    <td class="{{ $td }}">
                                        {{ $a->location ?? '-' }}
                                    </td>

                                    <td class="px-4 py-3">
                                        <div class="flex justify-end gap-2">
                                            <a href="{{ route('assets.edit', $a) }}"
                                                class="inline-flex items-center justify-center px-3 py-1.5 rounded-lg text-xs font-semibold
                                                       border border-light-eval-3 dark:border-dark-eval-2
                                                       bg-light-bg dark:bg-dark-eval-1
                                                       text-light-text-secondary dark:text-dark-text-secondary
                                                       hover:bg-light-eval-2 dark:hover:bg-dark-eval-2 transition-colors">
                                                Edit
                                            </a>

                                            <form action="{{ route('assets.destroy', $a) }}" method="POST"
                                                  onsubmit="return confirm('Yakin ingin menghapus assets ini?');">
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
                            @endforeach
                        </tbody>
                    </table>
                </x-datatable-wrapper>
            </div>

        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            document.querySelectorAll(".datatable").forEach((table) => {
                new DataTable(table, {
                    responsive: false,
                    pageLength: 10,
                    layout: {
                        topStart: "pageLength",
                        topEnd: "search",
                        bottomStart: "info",
                        bottomEnd: "paging"
                    },
                    language: {
                        emptyTable: "No Data Available",
                        zeroRecords: "No Data Available"
                    }
                });
            });
        });
    </script>
</x-app-layout>