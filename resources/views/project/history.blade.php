@php
    $card = "rounded-2xl border shadow-sm
             bg-light-eval-1 dark:bg-dark-eval-1
             border-light-eval-3 dark:border-dark-eval-2";

    $thead = "bg-light-eval-2 dark:bg-dark-eval-2 border-b
              border-light-eval-3 dark:border-dark-eval-2";

    $muted = "text-light-text-secondary dark:text-dark-text-secondary";
    $muted2 = "text-light-text-muted dark:text-dark-text-secondary";

    $th = "px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider $muted";
    $td = "px-4 py-3 text-sm $muted";

    $pillBase = "inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold";

    $fmt = fn($dt, $format = 'd M Y H:i') => $dt ? \Carbon\Carbon::parse($dt)->format($format) : '-';
@endphp

{{-- ========================= DETAIL PROJECT ========================= --}}
<div class="{{ $card }} p-4 sm:p-5 mb-6">
    <div class="flex items-start justify-between gap-4 mb-4">
        <div>
            <div class="flex items-center gap-3">
                <span class="h-2.5 w-2.5 rounded-full bg-blue-600"></span>
                <h3 class="text-base sm:text-lg font-semibold text-light-text dark:text-dark-text">
                    Detail Project
                </h3>
            </div>
            <div class="text-xs mt-1 {{ $muted2 }}">
                Riwayat progress developer & status project
            </div>
        </div>

        <span class="{{ $pillBase }} bg-blue-600/10 text-blue-700 dark:bg-blue-400/10 dark:text-blue-300">
            Progress Log
        </span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-light-text dark:text-dark-text">
            <thead class="{{ $thead }}">
                <tr>
                    <th class="{{ $th }} w-14 text-center">No</th>
                    <th class="{{ $th }}">Developer</th>
                    <th class="{{ $th }}">Progress Date</th>
                    <th class="{{ $th }}">Status</th>
                    <th class="{{ $th }} text-center">Progress</th>
                    <th class="{{ $th }}">Memo</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-light-eval-3 dark:divide-dark-eval-2">
                @forelse ($details as $index => $d)
                    @php
                        // ✅ status name sinkron: pakai field "name"
                        $statusName = $d->status->name ?? '-';
                        $type = strtolower($d->status->type ?? '');

                        $statusBadge = match ($type) {
                            'waiting' => 'bg-yellow-500/15 text-yellow-700 dark:bg-yellow-400/10 dark:text-yellow-300',
                            'in_progress' => 'bg-blue-600/10 text-blue-700 dark:bg-blue-400/10 dark:text-blue-300',
                            'done' => 'bg-green-600/10 text-green-700 dark:bg-green-400/10 dark:text-green-300',
                            'void' => 'bg-red-600/10 text-red-700 dark:bg-red-400/10 dark:text-red-300',
                            'pending' => 'bg-orange-600/10 text-orange-700 dark:bg-orange-400/10 dark:text-orange-300',
                            default => 'bg-light-eval-2 text-light-text dark:bg-dark-eval-2 dark:text-dark-text',
                        };

                        $pct = (int) ($d->progress_percent ?? 0);

                        // ✅ developer sinkron: ambil dari relasi developer (developer_id)
                        $devName = $d->developer->name ?? '-';
                    @endphp

                    <tr class="transition-colors hover:bg-light-eval-2 dark:hover:bg-dark-eval-2">
                        <td class="px-4 py-3 text-center text-sm font-semibold text-light-text dark:text-dark-text">
                            {{ $index + 1 }}
                        </td>

                        <td class="{{ $td }}">
                            <div class="font-medium text-light-text dark:text-dark-text">
                                {{ $devName }}
                            </div>
                            @if (!empty($d->developer_id))
                                <div class="text-xs {{ $muted2 }}">ID: {{ $d->developer_id }}</div>
                            @endif
                        </td>

                        <td class="{{ $td }}">
                            {{ $fmt($d->progress_date) }}
                        </td>

                        <td class="{{ $td }}">
                            <span class="{{ $pillBase }} {{ $statusBadge }}">
                                {{ $statusName }}
                            </span>
                        </td>

                        <td class="px-4 py-3 text-center">
                            <div class="flex flex-col items-center gap-1">
                                <span class="text-sm font-semibold text-light-text dark:text-dark-text">
                                    {{ $pct }}%
                                </span>

                                <div class="w-28 h-2 rounded-full bg-light-eval-3 dark:bg-dark-eval-2 overflow-hidden">
                                    <div class="h-full bg-blue-600 rounded-full"
                                        style="width: {{ max(0, min(100, $pct)) }}%"></div>
                                </div>
                            </div>
                        </td>

                        <td class="px-4 py-3 text-sm {{ $muted }} max-w-xl break-words whitespace-normal">
                            {{ $d->memo ?? '-' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-10 text-center text-sm {{ $muted2 }}">
                            Tidak ada data progress project
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ========================= PENDING TASKS ========================= --}}
<div class="{{ $card }} p-4 sm:p-5">
    <div class="flex items-start justify-between gap-4 mb-4">
        <div>
            <div class="flex items-center gap-3">
                <span class="h-2.5 w-2.5 rounded-full bg-orange-600"></span>
                <h3 class="text-base sm:text-lg font-semibold text-light-text dark:text-dark-text">
                    Pending Tasks
                </h3>
            </div>
            <div class="text-xs mt-1 {{ $muted2 }}">
                Log pending beserta durasi & alasan
            </div>
        </div>

        <span class="{{ $pillBase }} bg-orange-600/10 text-orange-700 dark:bg-orange-400/10 dark:text-orange-300">
            Pending Log
        </span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-light-text dark:text-dark-text">
            <thead class="{{ $thead }}">
                <tr>
                    <th class="{{ $th }} w-14 text-center">No</th>
                    <th class="{{ $th }}">Pending Start</th>
                    <th class="{{ $th }}">Pending End</th>
                    <th class="{{ $th }}">Reason</th>
                    <th class="{{ $th }} text-center">Duration</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-light-eval-3 dark:divide-dark-eval-2">
                @forelse ($pendings as $index => $p)
                    @php
                        $duration = (int) ($p->duration_minutes ?? 0);
                    @endphp

                    <tr class="transition-colors hover:bg-light-eval-2 dark:hover:bg-dark-eval-2">
                        <td class="px-4 py-3 text-center text-sm font-semibold text-light-text dark:text-dark-text">
                            {{ $index + 1 }}
                        </td>

                        <td class="{{ $td }}">{{ $fmt($p->pending_start) }}</td>
                        <td class="{{ $td }}">{{ $fmt($p->pending_end) }}</td>

                        <td class="px-4 py-3 text-sm {{ $muted }} max-w-xl break-words whitespace-normal">
                            {{ $p->reason ?? '-' }}
                        </td>

                        <td class="px-4 py-3 text-center">
                            <span class="{{ $pillBase }} bg-orange-600/10 text-orange-700 dark:bg-orange-400/10 dark:text-orange-300">
                                {{ $duration }} min
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-10 text-center text-sm {{ $muted2 }}">
                            Tidak ada pending tasks
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>