<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 leading-tight">
            {{ __('Dashboard Tickets User') }}
        </h2>
    </x-slot>
    
    <div class="p-6 space-y-6 ">

        {{-- BUTTON TAMBAH TICKET --}}
        @if (!$hasDoneTicket)
            <a href="{{ route('DashboardTicketsUser.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded inline-block">
                Tambah Ticket
            </a>
        @endif

        {{-- TABLE MY TICKET --}}
        <div class="bg-light-eval-1 dark:bg-dark-eval-1 rounded-lg shadow-md p-4 border border-gray-200 dark:border-gray-700 mt-4">
            <h3 class="font-semibold text-lg mb-2 border-b border-gray-300 dark:border-gray-600 pb-1 text-light-text dark:text-dark-text">
                My Tickets
            </h3>
            <div class="flex gap-4 text-sm mb-4 text-light-text-secondary dark:text-dark-text-secondary">
                <div>Total: {{ $myTicket->count() }}</div>
            </div>
            <div class="overflow-x-auto">
                <table class="datatable min-w-full border border-gray-300 dark:border-gray-600 text-sm">
                    <thead class="bg-light-eval-2 dark:bg-dark-eval-2 text-left">
                        <tr>
                            <th class="border border-gray-300 dark:border-gray-600 p-2">Ticket Code</th>
                            <th class="border border-gray-300 dark:border-gray-600 p-2">Nama</th>
                            <th class="border border-gray-300 dark:border-gray-600 p-2">Kategori</th>
                            <th class="border border-gray-300 dark:border-gray-600 p-2">Masalah</th>
                            <th class="border border-gray-300 dark:border-gray-600 p-2">Status</th>
                            <th class="border border-gray-300 dark:border-gray-600 p-2">Tanggal Req</th>
                            <th class="border border-gray-300 dark:border-gray-600 p-2">Image</th>
                            <th class="border border-gray-300 dark:border-gray-600 p-2 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-dark-eval-1">
                        @foreach ($myTicket as $ticket)
                            <tr class="hover:bg-light-eval-2 dark:hover:bg-dark-eval-2">
                                <td class="border border-gray-300 dark:border-gray-600 p-2">{{ $ticket->ticket_code }}</td>
                                <td class="border border-gray-300 dark:border-gray-600 p-2">{{ $ticket->user->name ?? '-' }}</td>
                                <td class="border border-gray-300 dark:border-gray-600 p-2">{{ $ticket->problemCategory?->problem_category_name ?? '-' }}</td>
                                <td class="border border-gray-300 dark:border-gray-600 p-2">{{ $ticket->problem }}</td>
                                <td class="border border-gray-300 dark:border-gray-600 p-2">{{ $ticket->status->status_name }}</td>
                                <td class="border border-gray-300 dark:border-gray-600 p-2">{{ $ticket->request_date?->format('Y-m-d') }}</td>
                                <td class="border border-gray-300 dark:border-gray-600 p-2">
                                    @if ($ticket->image)
                                        <a href="{{ asset('storage/' . $ticket->image) }}" target="_blank" class="text-blue-600 underline">Lihat File</a>
                                    @else
                                        <span class="text-gray-500 text-sm italic">No media</span>
                                    @endif
                                </td>
                                @if ($ticket->status_id == 3)
                                    <td class="border border-gray-300 dark:border-gray-600 p-2 text-center">
                                        <a href="{{ route('DashboardTicketsAdmin.edit', $ticket->id) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-xs inline-block">
                                            Feedback
                                        </a>
                                    </td>
                                @else
                                    <td class="border border-gray-300 dark:border-gray-600 p-2 text-center">-</td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    {{-- MODAL FORM (DI BAWAH APP LAYOUT) --}}
    <div id="ticketModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white dark:bg-dark-eval-1 rounded-lg shadow-lg p-6 w-96 relative">
            <button id="closeModal" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700">&times;</button>
            <h3 class="font-bold text-lg mb-4 text-light-text dark:text-dark-text">Update Ticket</h3>
            <form method="POST" id="ticketForm">
                @csrf
                <input type="hidden" name="ticket_id" id="modalTicketId">
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1 text-light-text dark:text-dark-text">Time Spent (hours)</label>
                    <input type="number" step="0.01" name="time_spent" id="timeSpentInput" class="w-full border border-gray-300 dark:border-gray-600 rounded p-2 bg-white dark:bg-dark-eval-2 text-light-text dark:text-dark-text" required>
                </div>
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded w-full">Submit</button>
            </form>
        </div>
    </div>

    {{-- CDN --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

    {{-- DATATABLE --}}
    <script>
        $(document).ready(function() {
            $('.datatable').DataTable({
                responsive: true,
                paging: true,
                searching: true,
                ordering: true,
                info: true,
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Cari data...",
                    emptyTable: "Tidak ada data tersedia di tabel ini",
                    paginate: { next: "›", previous: "‹" },
                },
                dom: '<"flex flex-wrap justify-between items-center mb-4"<"flex gap-2"l><"flex gap-2"f>>t<"flex flex-wrap justify-between items-center mt-4"<"text-sm"i><"flex gap-2"p>>',
            });
        });

        // MODAL JS
        document.addEventListener('DOMContentLoaded', () => {
            const modal = document.getElementById('ticketModal');
            const closeBtn = document.getElementById('closeModal');
            const ticketIdInput = document.getElementById('modalTicketId');

            // Jika ada tombol action ingin buka modal, tambahkan class "openModalBtn" pada tombol
            document.querySelectorAll('.openModalBtn').forEach(btn => {
                btn.addEventListener('click', () => {
                    ticketIdInput.value = btn.dataset.ticketId;
                    modal.classList.remove('hidden');
                });
            });

            closeBtn.addEventListener('click', () => modal.classList.add('hidden'));
            modal.addEventListener('click', (e) => { if(e.target === modal) modal.classList.add('hidden'); });
        });
    </script>

</x-app-layout>
