<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl leading-tight text-light-text dark:text-dark-text">
                {{ __('List Feedback') }}
            </h2>
        </div>
    </x-slot>

    @php
        $card = "rounded-2xl border shadow-sm
                 bg-light-eval-1 dark:bg-dark-eval-1
                 border-light-eval-3 dark:border-dark-eval-2";

        $muted = "text-light-text-secondary dark:text-dark-text-secondary";
        $muted2 = "text-light-text-muted dark:text-dark-text-secondary";

        $thead = "bg-light-eval-2 dark:bg-dark-eval-2 border-b
                  border-light-eval-3 dark:border-dark-eval-2";

        $th = "px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider $muted";
        $td = "px-4 py-3 text-sm $muted";
    @endphp

    <div class="px-4 sm:px-6 lg:px-8 py-6 space-y-6">

        {{-- Alert Success --}}
        @if (session('success'))
            <div class="rounded-xl border px-4 py-3 {{ $card }} border-green-600/25">
                <div class="text-sm font-medium text-green-700 dark:text-green-300">
                    {{ session('success') }}
                </div>
            </div>
        @endif

        {{-- Alert Error --}}
        @if (session('error'))
            <div class="rounded-xl border px-4 py-3 {{ $card }} border-red-600/25">
                <div class="text-sm font-medium text-red-700 dark:text-red-300">
                    {{ session('error') }}
                </div>
            </div>
        @endif

        {{-- Table Card --}}
        <div class="{{ $card }} p-4 sm:p-5">
            {{-- Top bar --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
                <div>
                    <div class="text-sm font-semibold text-light-text dark:text-dark-text">
                        Summary
                    </div>
                    <div class="text-xs mt-0.5 {{ $muted2 }}">
                        Total feedback & rating average
                    </div>
                </div>

                <div class="flex items-center gap-2">
                  <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-semibold
             bg-blue-600/10 dark:bg-blue-400/10 text-blue-700 dark:text-blue-300">
    Avg Speed: {{ $avgSpeed }} ⭐
</span>
<span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-semibold
             bg-blue-600/10 dark:bg-blue-400/10 text-blue-700 dark:text-blue-300">
    Avg Waiting: {{ $avgWaiting }} ⭐
</span>
<span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-semibold
             bg-blue-600/10 dark:bg-blue-400/10 text-blue-700 dark:text-blue-300">
    Avg Solution: {{ $avgSolution }} ⭐
</span>
<span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-semibold
             bg-green-600/10 dark:bg-green-400/10 text-green-700 dark:text-green-300">
    Overall: {{ $avgOverall }} ⭐
</span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="datatable w-full text-light-text dark:text-dark-text">
                    <thead class="{{ $thead }}">
                        <tr>
                            <th class="{{ $th }}">Ticket</th>
                            <th class="{{ $th }}">Requestor</th>
                            <th class="{{ $th }}">IT Support</th>
                            <th class="{{ $th }}">Speed Rating</th>
                            <th class="{{ $th }}">Waiting Rating</th>
                            <th class="{{ $th }}">Solution Rating</th>
                            <th class="{{ $th }}">Comment</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-light-eval-3 dark:divide-dark-eval-2">
                        @foreach ($feedback as $f)
                            <tr class="transition-colors hover:bg-light-eval-2 dark:hover:bg-dark-eval-2">
                                <td class="px-4 py-3">
                                    <div class="text-sm font-semibold text-light-text dark:text-dark-text">
                                        {{ $f->ticket->ticket_code ?? '-' }}
                                    </div>
                                </td>

                                <td class="{{ $td }}">
                                    {{ $f->ticket->user->username ?? '-' }}
                                </td>

                                <td class="{{ $td }}">
                                    {{ $f->ticket->support->name ?? '-' }}
                                </td>
                                <td class="{{ $td }}">
                                    {{ $f->speed_rating ?? '-' }}
                                </td>
                                <td class="{{ $td }}">
                                    {{ $f->waiting_rating ?? '-' }}
                                </td>
                                <td class="{{ $td }}">
                                    {{ $f->solution_rating ?? '-' }}
                                </td>

                                <td class="px-4 py-3 text-sm {{ $muted }} max-w-xl break-words whitespace-normal">
                                    {{ $f->comment ?? '-' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
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
