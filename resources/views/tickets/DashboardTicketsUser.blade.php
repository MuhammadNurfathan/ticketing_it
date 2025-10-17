<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard Tickets User') }}
        </h2>
    </x-slot>

    <div class="p-6 space-y-6">

        @if (session('error'))
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const isDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

                    Swal.fire({
                        icon: 'warning',
                        title: 'Tidak bisa membuat tiket baru!',
                        text: '{{ session('error') }}',
                        confirmButtonColor: isDark ? '#3b82f6' : '#2563eb',
                        background: isDark ? '#1f2937' : '#ffffff', // dark:bg-gray-800 / light:white
                        color: isDark ? '#f3f4f6' : '#111827', // dark:text-gray-100 / light:text-gray-900
                        iconColor: isDark ? '#facc15' : '#eab308', // warna icon warning
                        customClass: {
                            popup: 'rounded-2xl shadow-lg',
                            confirmButton: 'font-semibold px-4 py-2 rounded'
                        }
                    });
                });
            </script>
        @endif



        {{-- BUTTON TAMBAH TICKET --}}
        @if (!$hasDoneTicket)
            <a href="{{ route('DashboardTicketsUser.createUser') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white dark:text-white px-4 py-2 rounded inline-block transition-colors">
                Tambah Ticket
            </a>
        @endif

        {{-- MY TICKET SECTION --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 border border-gray-200 dark:border-gray-700 mt-4">
            <h3
                class="font-semibold text-lg mb-2 border-b border-gray-300 dark:border-gray-600 pb-1 text-gray-800 dark:text-gray-200">
                My Tickets
            </h3>
            <div class="flex gap-4 text-sm mb-4 text-gray-600 dark:text-gray-400">
                <div>Total: {{ $myTicket->count() }}</div>
            </div>

            {{-- DESKTOP VIEW (TABLE) --}}
            <div class="hidden md:block overflow-x-auto">
                <table class="datatable min-w-full border border-gray-300 dark:border-gray-600 text-sm">
                    <thead class="bg-gray-100 dark:bg-gray-700 text-left text-gray-700 dark:text-gray-200">
                        <tr>
                            <th class="border border-gray-300 dark:border-gray-600 p-2">Ticket Code</th>
                            <th class="border border-gray-300 dark:border-gray-600 p-2">Nama</th>
                            <th class="border border-gray-300 dark:border-gray-600 p-2">Kategori</th>
                            <th class="border border-gray-300 dark:border-gray-600 p-2">Masalah</th>
                            <th class="border border-gray-300 dark:border-gray-600 p-2">Status</th>
                            <th class="border border-gray-300 dark:border-gray-600 p-2">Tanggal Req</th>
                            <th class="border border-gray-300 dark:border-gray-600 p-2">Feedback</th>
                            <th class="border border-gray-300 dark:border-gray-600 p-2">Image</th>
                            <th class="border border-gray-300 dark:border-gray-600 p-2 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-200">
                        @foreach ($myTicket as $ticket)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="border border-gray-300 dark:border-gray-600 p-2">{{ $ticket->ticket_code }}
                                </td>
                                <td class="border border-gray-300 dark:border-gray-600 p-2">
                                    {{ $ticket->user->name ?? '-' }}</td>
                                <td class="border border-gray-300 dark:border-gray-600 p-2">
                                    {{ $ticket->problemCategory?->problem_category_name ?? '-' }}</td>
                                <td class="border border-gray-300 dark:border-gray-600 p-2">{{ $ticket->problem }}</td>
                                <td class="border border-gray-300 dark:border-gray-600 p-2">
                                    {{ $ticket->status->status_name }}</td>
                                <td class="border border-gray-300 dark:border-gray-600 p-2">
                                    {{ $ticket->request_date?->format('Y-m-d') }}</td>
                                <td class="border border-gray-300 dark:border-gray-600 p-2">
                                    {{ $ticket->feedback->description ?? '-' }}</td>
                                <td class="border border-gray-300 dark:border-gray-600 p-2">
                                    @if ($ticket->image)
                                        <a href="{{ asset('storage/' . $ticket->image) }}" target="_blank"
                                            class="text-blue-600 dark:text-blue-400 underline">Lihat File</a>
                                    @else
                                        <span class="text-gray-500 dark:text-gray-400 text-sm italic">No media</span>
                                    @endif
                                </td>
                                <td class="border border-gray-300 dark:border-gray-600 p-2 text-center">
                                    @if ($ticket->status_id == 3)
                                        <a href="{{ route('feedback.form', $ticket->id) }}"
                                            class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-xs inline-block transition-colors">
                                            Feedback
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

            {{-- MOBILE VIEW (CARDS) --}}
            <div class="md:hidden space-y-4">
                @foreach ($myTicket as $ticket)
                    <div
                        class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 shadow border border-gray-200 dark:border-gray-600">
                        {{-- Header Card --}}
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <div class="text-xs font-semibold text-blue-600 dark:text-blue-400">
                                    {{ $ticket->ticket_code }}
                                </div>
                                <div class="text-sm font-medium text-gray-800 dark:text-gray-200 mt-1">
                                    {{ $ticket->user->name ?? '-' }}
                                </div>
                            </div>
                            <span
                                class="bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 text-xs px-2 py-1 rounded">
                                {{ $ticket->status->status_name }}
                            </span>
                        </div>

                        {{-- Content Card --}}
                        <div class="space-y-2 text-sm">
                            <div class="flex">
                                <span class="text-gray-500 dark:text-gray-400 w-24 flex-shrink-0">Kategori:</span>
                                <span class="text-gray-800 dark:text-gray-200 font-medium">
                                    {{ $ticket->problemCategory?->problem_category_name ?? '-' }}
                                </span>
                            </div>
                            <div class="flex">
                                <span class="text-gray-500 dark:text-gray-400 w-24 flex-shrink-0">Masalah:</span>
                                <span class="text-gray-800 dark:text-gray-200">{{ $ticket->problem }}</span>
                            </div>
                            <div class="flex">
                                <span class="text-gray-500 dark:text-gray-400 w-24 flex-shrink-0">Tanggal:</span>
                                <span
                                    class="text-gray-800 dark:text-gray-200">{{ $ticket->request_date?->format('Y-m-d') }}</span>
                            </div>
                            <div class="flex">
                                <span class="text-gray-500 dark:text-gray-400 w-24 flex-shrink-0">Feedback:</span>
                                <span
                                    class="text-gray-800 dark:text-gray-200">{{ $ticket->feedback->description ?? '-' }}</span>
                            </div>
                            <div class="flex">
                                <span class="text-gray-500 dark:text-gray-400 w-24 flex-shrink-0">Image:</span>
                                @if ($ticket->image)
                                    <a href="{{ asset('storage/' . $ticket->image) }}" target="_blank"
                                        class="text-blue-600 dark:text-blue-400 underline">Lihat File</a>
                                @else
                                    <span class="text-gray-500 dark:text-gray-400 text-sm italic">No media</span>
                                @endif
                            </div>
                        </div>

                        {{-- Action Button --}}
                        @if ($ticket->status_id == 3)
                            <div class="mt-4 pt-3 border-t border-gray-200 dark:border-gray-600">
                                <a href="{{ route('feedback.form', $ticket->id) }}"
                                    class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded text-sm inline-block transition-colors w-full text-center">
                                    Berikan Feedback
                                </a>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

    </div>

    {{-- MODAL FORM --}}
    <div id="ticketModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 w-96 max-w-full mx-4 relative">
            <button id="closeModal"
                class="absolute top-2 right-2 text-gray-500 dark:text-gray-300 hover:text-gray-700 dark:hover:text-white text-2xl">&times;</button>
            <h3 class="font-bold text-lg mb-4 text-gray-800 dark:text-gray-200">Update Ticket</h3>
            <form method="POST" id="ticketForm">
                @csrf
                <input type="hidden" name="ticket_id" id="modalTicketId">
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1 text-gray-800 dark:text-gray-200">Time Spent
                        (hours)</label>
                    <input type="number" step="0.01" name="time_spent" id="timeSpentInput"
                        class="w-full border border-gray-300 dark:border-gray-600 rounded p-2 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200"
                        required>
                </div>
                <button type="submit"
                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded w-full transition-colors">Submit</button>
            </form>
        </div>
    </div>

    {{-- CDN --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

    {{-- DATATABLE - Only for desktop --}}
    <script>
        $(document).ready(function() {
            // Only initialize DataTable on desktop view
            if (window.innerWidth >= 768) {
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
                        paginate: {
                            next: "›",
                            previous: "‹"
                        },
                    },
                    dom: '<"flex flex-wrap justify-between items-center mb-4"<"flex gap-2"l><"flex gap-2"f>>t<"flex flex-wrap justify-between items-center mt-4"<"text-sm"i><"flex gap-2"p>>',
                });
            }
        });

        // MODAL JS
        document.addEventListener('DOMContentLoaded', () => {
            const modal = document.getElementById('ticketModal');
            const closeBtn = document.getElementById('closeModal');
            const ticketIdInput = document.getElementById('modalTicketId');

            document.querySelectorAll('.openModalBtn').forEach(btn => {
                btn.addEventListener('click', () => {
                    ticketIdInput.value = btn.dataset.ticketId;
                    modal.classList.remove('hidden');
                });
            });

            closeBtn.addEventListener('click', () => modal.classList.add('hidden'));
            modal.addEventListener('click', (e) => {
                if (e.target === modal) modal.classList.add('hidden');
            });
        });
    </script>
</x-app-layout>
