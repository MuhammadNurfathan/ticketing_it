@props([
    'title' => null,
    'subtitle' => null,
    'count' => null,
])

<div
    class="rounded-lg border shadow-sm overflow-hidden
           bg-light-eval-1 dark:bg-dark-eval-1
           border-light-eval-3 dark:border-dark-eval-2">

    {{-- HEADER --}}
    @if ($title)
        <div class="p-4 sm:p-6 border-b
                    border-light-eval-3 dark:border-dark-eval-2
                    bg-light-eval-2 dark:bg-dark-eval-2">

            <div class="flex items-center justify-between gap-4">

                <div>
                    <h3 class="text-lg font-semibold text-light-text dark:text-dark-text">
                        {{ $title }}
                    </h3>

                    @if ($subtitle)
                        <p class="text-sm mt-1 text-light-text-secondary dark:text-dark-text-secondary">
                            {{ $subtitle }}
                        </p>
                    @endif
                </div>

                @if (!is_null($count))
                    <span
                        class="hidden sm:inline-flex px-3 py-1 rounded-lg text-xs font-semibold
                               bg-light-bg dark:bg-dark-eval-3
                               border border-light-eval-3 dark:border-dark-eval-2
                               text-light-text-secondary dark:text-dark-text-secondary">
                        {{ $count }} data
                    </span>
                @endif

            </div>
        </div>
    @endif

    {{-- CONTENT --}}
    <div class="p-4">
    <div class="overflow-x-auto">
        {{ $slot }}
    </div>
</div>
</div>