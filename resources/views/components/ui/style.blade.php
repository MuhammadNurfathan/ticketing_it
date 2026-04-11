@props([])

@php
    $page = 'min-h-screen';

    $wrap = 'w-full px-4 sm:px-6 lg:px-8 py-6 space-y-6';

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

{{ $slot }}