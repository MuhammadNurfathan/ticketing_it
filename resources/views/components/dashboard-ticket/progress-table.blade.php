
<div x-show="tab==='in_progress'"
    x-cloak
    class="{{ $tableWrap }} p-4 sm:p-5">

    <div class="flex items-center justify-between mb-4">

        <div>
            <div class="{{ $sectionTitle }}">
                Tickets In Progress
            </div>

            <div class="text-xs mt-1 {{ $muted2 }}">
                {{ $tabs['in_progress']['subtitle'] }}
            </div>
        </div>

        <span class="{{ $badgeBase }} {{ $tabs['in_progress']['badge'] }}">
            In Progress
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
                    <th class="{{ $th }}">Start Date</th>
                    <th class="{{ $th }}">End Date</th>
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
                            {{ $ticket->problem }}
                        </td>

                        <td class="{{ $td }}">
                            {{ $ticket->request_date?->format('Y-m-d') ?? '-' }}
                        </td>

                        <td class="{{ $td }}">
                            {{ $ticket->start_date?->format('Y-m-d H:i:s') ?? '-' }}
                        </td>

                        <td class="{{ $td }}">
                            {{ $ticket->end_date?->format('Y-m-d H:i:s') ?? '-' }}
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

                                <td class="px-4 py-3 text-center">

                                    <button type="button"
                                        class="doneBtn inline-flex items-center justify-center px-3 py-1.5 rounded-lg text-xs font-semibold
                                        bg-green-600 hover:bg-green-700 text-white transition-colors"
                                        data-start="{{ $ticket->start_date?->format('Y-m-d H:i:s') }}">

                                        Done
                                    </button>

                                    {{-- MODAL --}}
                                    <div class="timeSpentModal fixed inset-0 z-50 hidden items-center justify-center
                                        bg-black/40 dark:bg-black/60 backdrop-blur-sm">

                                        <div class="modalContent w-[420px]
                                            {{ $card }} p-6
                                            transform scale-95 opacity-0 transition-all duration-200">

                                            <h3 class="text-lg font-semibold mb-4 text-light-text dark:text-dark-text">
                                                Time Spent & Solution
                                            </h3>

                                            <div class="mb-4">

                                                <div class="flex items-center justify-between gap-3">

                                                    <label class="block text-sm font-medium {{ $muted }}">
                                                        Time Spent (Menit)
                                                    </label>

                                                    <label class="flex items-center text-sm {{ $muted }}">
                                                        <input type="checkbox"
                                                            class="manualCheckbox mr-2">
                                                        Manual Input
                                                    </label>

                                                </div>

                                                <input type="number"
                                                    class="timeInput w-full mt-2 px-3 py-2 rounded-lg border
                                                    bg-light-eval-2 dark:bg-dark-eval-2
                                                    border-light-eval-3 dark:border-dark-eval-2
                                                    text-light-text dark:text-dark-text"
                                                    readonly>

                                            </div>

                                            <div class="mb-4 hidden notesContainer">

                                                <label class="block text-sm font-medium {{ $muted }} mb-2">
                                                    Notes
                                                    <span class="text-red-500">*</span>
                                                </label>

                                                <textarea
                                                    class="notesInput w-full px-3 py-2 rounded-lg border
                                                    bg-light-bg dark:bg-dark-eval-2
                                                    border-light-eval-3 dark:border-dark-eval-2
                                                    text-light-text dark:text-dark-text"
                                                    rows="2"></textarea>

                                            </div>

                                            <div class="mb-5">

                                                <label class="block text-sm font-medium {{ $muted }} mb-2">
                                                    Solution
                                                    <span class="text-red-500">*</span>
                                                </label>

                                                <textarea
                                                    class="solutionInput w-full px-3 py-2 rounded-lg border
                                                    bg-light-bg dark:bg-dark-eval-2
                                                    border-light-eval-3 dark:border-dark-eval-2
                                                    text-light-text dark:text-dark-text"
                                                    rows="3">{{ $ticket->solution ?? '' }}</textarea>

                                            </div>

                                            <div class="flex justify-end gap-2">

                                                <button type="button"
                                                    class="cancelBtn {{ $btnGhost }}">
                                                    Cancel
                                                </button>

                                                <button type="button"
                                                    class="saveBtn inline-flex items-center justify-center px-4 py-2 rounded-lg text-sm font-medium
                                                    bg-green-600 hover:bg-green-700 text-white transition-colors">

                                                    Save
                                                </button>

                                            </div>

                                        </div>
                                    </div>

                                    <form class="doneForm"
                                        action="{{ route('DashboardTicketsAdmin.updateStatusDone', $ticket->id) }}"
                                        method="POST">

                                        @csrf

                                        <input type="hidden"
                                            name="status_id"
                                            value="3">

                                        <input type="hidden"
                                            name="time_spent_minutes"
                                            class="hiddenTimeSpent">

                                        <input type="hidden"
                                            name="solution"
                                            class="hiddenSolution">

                                        <input type="hidden"
                                            name="notes"
                                            class="hiddenNotes">

                                    </form>

                                </td>

                            @endif
                        @endauth

                    </tr>

                @endforeach

            </tbody>

        </table>

    </div>

</div>
