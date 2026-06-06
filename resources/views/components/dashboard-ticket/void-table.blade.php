
<div x-show="tab==='void'"
    x-cloak
    class="{{ $tableWrap }} p-4 sm:p-5">

    <div class="flex items-center justify-between mb-4">

        <div>
            <div class="{{ $sectionTitle }}">
                Tickets Void
            </div>

            <div class="text-xs mt-1 {{ $muted2 }}">
                {{ $tabs['void']['subtitle'] }}
            </div>
        </div>

        <span class="{{ $badgeBase }} {{ $tabs['void']['badge'] }}">
            Void
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
                    <th class="{{ $th }}">Assignee</th>
                    <th class="{{ $th }}">Category</th>
                    <th class="{{ $th }}">Problem</th>
                    <th class="{{ $th }}">Request Date</th>
                    <th class="{{ $th }}">Image</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-light-eval-3 dark:divide-dark-eval-2">

                @foreach ($tickets as $ticket)

                    <tr class="hover:bg-light-eval-2 dark:hover:bg-dark-eval-2 transition-colors">

                        <td class="px-4 py-3 text-sm font-semibold text-light-text dark:text-dark-text">
                            {{ $ticket->ticket_code ?? '-' }}
                        </td>

                        <td class="{{ $td }}">
                            {{ $ticket->user->name ?? '-' }}
                        </td>

                        <td class="{{ $td }}">
                            {{ $ticket->user?->department?->name ?? '-' }}
                        </td>

                        <td class="{{ $td }}">
                            {{ $ticket->user?->department?->location?->name ?? '-' }}
                        </td>

                        <td class="{{ $td }}">
                            {{ $ticket->support?->name ?? '-' }}
                        </td>

                        <td class="{{ $td }}">
                            {{ $ticket->category?->name ?? '-' }}
                        </td>

                        <td class="px-4 py-3 text-sm {{ $muted }} max-w-xs break-words">
                            {{ $ticket->problem ?? '-' }}
                        </td>

                        <td class="{{ $td }}">
                            {{ $ticket->request_date?->format('Y-m-d') ?? '-' }}
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

                    </tr>

                @endforeach

            </tbody>

        </table>

    </div>

</div>

