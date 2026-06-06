<div class="grid grid-cols-2 md:grid-cols-4 gap-4">

    @foreach ($tabs as $key => $t)

        <button type="button"
            @click="tab='{{ $key }}'"
            class="group text-left rounded-2xl border shadow-sm p-5 transition-all duration-200
                   bg-light-eval-1 dark:bg-dark-eval-1
                   border-light-eval-3 dark:border-dark-eval-2
                   hover:-translate-y-0.5 hover:shadow-md focus:outline-none"
            :class="tab === '{{ $key }}'
                ? 'ring-2 ring-blue-500/25 dark:ring-blue-400/20 border-blue-500/30'
                : ''">

            <div class="h-1.5 w-full rounded-full {{ $t['accent'] }}"
                :class="tab === '{{ $key }}'
                    ? 'opacity-100'
                    : 'opacity-50'">
            </div>

            <div class="mt-4">

                <div class="text-sm font-semibold text-light-text dark:text-dark-text">
                    {{ $t['label'] }}
                </div>

                <div class="text-xs mt-0.5 {{ $muted2 }}">
                    Click to filter
                </div>

            </div>

            <div class="mt-4 text-3xl font-bold tracking-tight text-light-text dark:text-dark-text">
                {{ $t['count'] }}
            </div>

        </button>

    @endforeach

</div>
