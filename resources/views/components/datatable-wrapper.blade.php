@props([
    'title' => null,
    'subtitle' => null,
    'count' => null,
])

<div
    class="rounded-2xl border shadow-sm overflow-hidden
           bg-light-eval-1 dark:bg-dark-eval-1
           border-light-eval-3 dark:border-dark-eval-2">

    {{-- HEADER --}}
    @if ($title)
        <div class="p-4 sm:p-6 border-b border-light-eval-3 dark:border-dark-eval-2">
            <div class="flex items-end justify-between gap-4">
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
                        class="hidden sm:inline-flex px-2.5 py-1 rounded-lg text-xs font-semibold
                               border bg-light-bg dark:bg-dark-eval-2
                               border-light-eval-3 dark:border-dark-eval-2
                               text-light-text-secondary dark:text-dark-text-secondary">
                        Total: {{ $count }}
                    </span>
                @endif
            </div>
        </div>
    @endif

    {{-- TABLE --}}
    <div class="overflow-x-auto rounded-2xl px-3 pt-4 pb-3">
        {{ $slot }}
    </div>
</div>
