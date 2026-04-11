    @php
        $page = 'min-h-screen';
        $wrap = 'w-full px-4 sm:px-6 lg:px-8 py-2 space-y-6';

        $card = 'rounded-2xl border bg-white dark:bg-gray-800
                 border-gray-200 dark:border-gray-700 shadow-sm';

        $thead = 'bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-700';

        $th = 'px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider
               text-gray-500 dark:text-gray-400';

        $tr = 'hover:bg-gray-50 dark:hover:bg-gray-700 transition';

        $td = 'px-4 py-3 text-sm text-gray-700 dark:text-gray-200';

        $btnPrimary = 'inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold
                       bg-blue-600 hover:bg-blue-700 text-white shadow-sm transition';

        $btnSecondary = 'px-3 py-1.5 text-xs font-semibold rounded-lg
                         border border-gray-300 dark:border-gray-600
                         text-gray-700 dark:text-gray-200
                         bg-white dark:bg-gray-700
                         hover:bg-gray-100 dark:hover:bg-gray-600 transition';

        $btnDanger = 'px-3 py-1.5 text-xs font-semibold rounded-lg
                      border border-red-300 dark:border-red-700
                      text-red-600 dark:text-red-400
                      bg-white dark:bg-gray-700
                      hover:bg-red-100 dark:hover:bg-red-900 transition';

        $alertSuccess = 'rounded-xl border px-4 py-3
                         bg-green-100 text-green-700
                         dark:bg-green-900 dark:text-green-300';

        $alertError = 'rounded-xl border px-4 py-3
                       bg-red-100 text-red-700
                       dark:bg-red-900 dark:text-red-300';

        $pill = 'inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold';
    @endphp

<x-app-layout>

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100">
                    Kelola Assets
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Kelola data asset, status, lokasi, dan user peminjam
                </p>
            </div>

            <a href="{{ route('assets.create') }}"
               class="{{ $btnPrimary }}">
                <span>＋</span>
                <span>Tambah</span>
            </a>
        </div>
    </x-slot>

    <div class="{{ $page }}">
        <div class="{{ $wrap }}">

            {{-- ALERT SUCCESS --}}
            @if (session('success'))
                <div class="rounded-xl border px-4 py-3 bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300">
                    {{ session('success') }}
                </div>
            @endif

            {{-- ALERT ERROR --}}
            @if (session('error'))
                <div class="rounded-xl border px-4 py-3 bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300">
                    {{ session('error') }}
                </div>
            @endif

            {{-- CARD --}}
            <div class="{{ $card }}">

                <x-datatable-wrapper>

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

                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">

                            @foreach ($assets as $index => $a)

                                @php
                                    $status = strtolower($a->status ?? '');

                                    if (str_contains($status, 'available')) {
                                        $statusBadge = 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300';
                                    } elseif (str_contains($status, 'use') || str_contains($status, 'borrow') || str_contains($status, 'in')) {
                                        $statusBadge = 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300';
                                    } elseif (str_contains($status, 'broken') || str_contains($status, 'damage')) {
                                        $statusBadge = 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300';
                                    } else {
                                        $statusBadge = 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300';
                                    }
                                @endphp

                                <tr class="{{ $tr }}">

                                    <td class="{{ $td }} text-center font-semibold">
                                        {{ $index + 1 }}
                                    </td>

                                    <td class="{{ $td }}">
                                        {{ $a->assets_code ?? '-' }}
                                    </td>

                                    <td class="{{ $td }}">
                                        <div class="font-medium">
                                            {{ $a->assets_name ?? '-' }}
                                        </div>
                                    </td>

                                    <td class="{{ $td }}">
                                        {{ $a->check_out_to ?? '-' }}
                                    </td>

                                    <td class="{{ $td }}">
                                        {{ $a->category ?? '-' }}
                                    </td>

                                    <td class="{{ $td }}">
                                        <span class="{{ $pill }} {{ $statusBadge }}">
                                            {{ $a->status ?? '-' }}
                                        </span>
                                    </td>

                                    <td class="{{ $td }}">
                                        {{ $a->location ?? '-' }}
                                    </td>

                                    <td class="{{ $td }}">
                                        <div class="flex justify-start gap-2">

                                            <a href="{{ route('assets.edit', $a) }}"
                                               class="{{ $btnSecondary }}">
                                                Edit
                                            </a>

                                            <form action="{{ route('assets.destroy', $a) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('Yakin ingin menghapus assets ini?');">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" class="{{ $btnDanger }}">
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


</x-app-layout>