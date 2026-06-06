<div x-show="tab === 'waiting'"
    x-cloak
    class="{{ $tableWrap }} p-4 sm:p-5">

    <div class="flex items-center justify-between mb-4">

        <div>
            <div class="{{ $sectionTitle }}">
                Waiting Tickets
            </div>

            <div class="text-xs mt-1 {{ $muted2 }}">
                {{ $tabs['waiting']['subtitle'] }}
            </div>
        </div>

        <span class="{{ $badgeBase }} {{ $tabs['waiting']['badge'] }}">
            Waiting
        </span>

    </div>

    <div class="overflow-x-auto">

        <table class="datatable w-full text-light-text dark:text-dark-text">

            <thead class="{{ $thead }}">
                <tr>
                    <th class="{{ $th }}">Ticket Code</th>
                    <th class="{{ $th }}">Requestor</th>
                    <th class="{{ $th }}">Department</th>
                    <th class="{{ $th }}">Location</th>
                    <th class="{{ $th }}">Category</th>
                    <th class="{{ $th }}">Problem</th>
                    <th class="{{ $th }}">Request Date</th>
                    <th class="{{ $th }}">Image</th>

                    @auth
                        @if (Auth::user()->role_id != 3)
                            <th class="{{ $th }} text-center">
                                Action
                            </th>
                        @endif
                    @endauth
                </tr>
            </thead>

            <tbody class="divide-y divide-light-eval-3 dark:divide-dark-eval-2">

                @foreach ($tickets as $ticket)

                    <tr class="hover:bg-light-eval-2 dark:hover:bg-dark-eval-2 transition-colors">

                        <td class="px-4 py-3 text-sm font-semibold text-light-text dark:text-dark-text">
                            {{ $ticket->ticket_code }}
                        </td>

                        <td class="{{ $td }}">
                            {{ $ticket->user?->name ?? '-' }}
                        </td>

                        <td class="{{ $td }}">
                            {{ $ticket->user?->department?->name ?? '-' }}
                        </td>

                        <td class="{{ $td }}">
                            {{ $ticket->user?->department?->location?->name ?? '-' }}
                        </td>

                        <td class="{{ $td }}">
                            {{ $ticket->category?->name ?? '-' }}
                        </td>

                        <td class="px-4 py-3 text-sm {{ $muted }} max-w-xs break-words">
                            {{ $ticket->problem }}
                        </td>

                        <td class="{{ $td }}">
                            {{ $ticket->request_date?->format('Y-m-d H:i:s') ?? '-' }}
                        </td>
                        <td class="{{ $td }}">
                            @if ($ticket->image)
                                <a href="{{ asset('storage/' . $ticket->image) }}" target="_blank" class="w-16 h-16 object-cover">
                                    <img src="{{ asset('storage/' . $ticket->image) }}" alt="Ticket Image" class="w-16 h-16 object-cover">
                                </a>
                            @else
                                -
                            @endif
                        </td>

                        @auth
                            @if (Auth::user()->role_id != 3)

                                <td class="px-4 py-3 text-center"
                                    x-data="{ open: false }">

                                    {{-- VOID --}}
                                    <button
                                        @click="open = true"
                                        class="inline-flex items-center justify-center px-3 py-1.5 rounded-lg text-xs font-semibold
                                            bg-light-eval-3 hover:bg-light-eval-4
                                            dark:bg-dark-eval-2 dark:hover:bg-dark-eval-3
                                            text-light-text dark:text-dark-text transition-colors">

                                        Void
                                    </button>

                                    {{-- EXECUTION --}}
                                    <a href="{{ route('DashboardTicketsAdmin.edit', $ticket->id) }}"
                                        class="inline-flex items-center justify-center px-3 py-1.5 rounded-lg text-xs font-semibold
                                            bg-blue-600 hover:bg-blue-700 text-white transition-colors ml-1">

                                        Execution
                                    </a>

                                    {{-- MODAL --}}
                                    <div x-show="open"
                                        x-cloak
                                        class="fixed inset-0 flex items-center justify-center
                                            bg-black/50 dark:bg-black/70 backdrop-blur-sm z-50">

                                        <div class="{{ $card }} w-[420px] p-6">

                                            <h2 class="text-lg font-semibold mb-3 text-light-text dark:text-dark-text">
                                                Masukkan Catatan Void
                                            </h2>

                                            <form action="{{ route('DashboardTicketsAdmin.updateStatus', $ticket->id) }}"
                                                method="POST">

                                                @csrf

                                                <input type="hidden"
                                                    name="status_id"
                                                    value="4">

                                                <textarea
                                                    name="notes"
                                                    rows="3"
                                                    class="w-full px-3 py-2 rounded-lg border
                                                        bg-light-bg dark:bg-dark-eval-2
                                                        border-light-eval-3 dark:border-dark-eval-2
                                                        text-light-text dark:text-dark-text"
                                                    placeholder="Enter Notes..."
                                                    required></textarea>

                                                <div class="flex justify-end mt-4 gap-2">

                                                    <button type="button"
                                                        @click="open = false"
                                                        class="{{ $btnGhost }}">
                                                        Cancel
                                                    </button>

                                                    <button type="submit"
                                                        class="inline-flex items-center justify-center px-4 py-2 rounded-lg text-sm font-medium
                                                            bg-red-600 hover:bg-red-700 text-white transition-colors">
                                                        Save
                                                    </button>

                                                </div>

                                            </form>

                                        </div>

                                    </div>

                                </td>

                            @endif
                        @endauth

                    </tr>

                @endforeach

            </tbody>

        </table>

    </div>

</div>

