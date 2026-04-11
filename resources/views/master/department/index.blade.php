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
                 hover:bg-gray-100 dark:hover:bg-gray-600
                 transition';

    $btnDanger = 'px-3 py-1.5 text-xs font-semibold rounded-lg
              border border-red-300 dark:border-red-700
              text-red-600 dark:text-red-400
              bg-white dark:bg-gray-700
              hover:bg-red-100 dark:hover:bg-red-900
              transition';

    $alertSuccess = 'rounded-xl border px-4 py-3
                     bg-green-100 text-green-700
                     dark:bg-green-900 dark:text-green-300';

    $alertError = 'rounded-xl border px-4 py-3
                   bg-red-100 text-red-700
                   dark:bg-red-900 dark:text-red-300';
@endphp

<x-app-layout>

    {{-- HEADER (CUMA SATU DI SINI) --}}
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100">
                    Department
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Kelola data department
                </p>
            </div>

            <a href="{{ route('departments.create') }}" class="{{ $btnPrimary }}">
                <span>＋</span>
                <span>Tambah</span>
            </a>
        </div>
    </x-slot>

    <div class="{{ $page }}">
        <div class="{{ $wrap }}">

            {{-- ALERT --}}
            @if (session('success'))
                <div class="{{ $alertSuccess }}">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="{{ $alertError }}">
                    {{ session('error') }}
                </div>
            @endif

            {{-- CARD --}}
            <div class="{{ $card }}">



                <x-datatable-wrapper>

                    <table class="datatable w-full text-sm">

                        <thead class="{{ $thead }}">
                            <tr>
                                <th class="{{ $th }} text-center w-14">No</th>
                                <th class="{{ $th }}">Nama Department</th>
                                <th class="{{ $th }} text-right">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($departments as $index => $department)
                                <tr class="{{ $tr }}">

                                    <td class="{{ $td }} text-center font-semibold">
                                        {{ $index + 1 }}
                                    </td>

                                    <td class="{{ $td }}">
                                        <div class="font-medium">
                                            {{ $department->name }}
                                        </div>
                                    </td>

                                    <td class="{{ $td }}">
                                        <div class="flex justify-start gap-2">

                                            <a href="{{ route('departments.edit', $department) }}"
                                                class="{{ $btnSecondary }}">
                                                Edit
                                            </a>

                                            <form method="POST"
                                                action="{{ route('departments.destroy', $department) }}"
                                                onsubmit="return confirm('Yakin hapus data ini?')">
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
