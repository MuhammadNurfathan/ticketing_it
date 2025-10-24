{{-- DETAIL PROJECT --}}
<div
    class="bg-light-eval-1 dark:bg-dark-eval-1 rounded-lg shadow-md p-4 border border-gray-200 dark:border-gray-700 mb-4">
    <h3 class="inline bg-blue-500 px-2 py-1 font-semibold text-lg mb-4 text-white rounded">
        Detail Project
    </h3>

    {{-- Detail project --}}
    <div class="overflow-x-auto mt-4">
        <table class="min-w-full border text-sm">
            <thead class="bg-gray-100 dark:bg-gray-800">
                <tr>
                    <th class="px-4 py-2 border text-center">No</th>
                    <th class="px-4 py-2 border">Developer Name</th>
                    <th class="px-4 py-2 border">Progress Date</th>
                    <th class="px-4 py-2 border">Status</th>
                    <th class="px-4 py-2 border text-center">Progress Percent</th>
                    <th class="px-4 py-2 border">Memo</th>
                </tr>
                </thead>
                <tbody>
                    @forelse ($details as $index => $d)
                        <tr class="border-b">
                            <td class="px-4 py-2 border text-center">{{ $index + 1 }}</td>
                            <td class="px-4 py-2 border">{{ $d->developer_name ?? '-' }}</td>
                            <td class="px-4 py-2 border">{{ $d->progress_date ?? '-' }}</td>
                            <td class="px-4 py-2 border">{{ $d->status->status_name ?? '-' }}</td>
                            <td class="px-4 py-2 border text-center">{{ $d->progress_percent ?? 0 }}%</td>
                            <td class="px-4 py-2 border">{{ $d->memo ?? '-' }}</td>
                        </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-gray-500 py-4">Tidak ada data progress project</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- PENDING TASKS --}}
<div
    class="bg-light-eval-1 dark:bg-dark-eval-1 rounded-lg shadow-md p-4 border border-gray-200 dark:border-gray-700">
    <h3 class="inline bg-orange-500 px-2 py-1 font-semibold text-lg mb-2 text-white rounded">
        Pending Tasks
    </h3>

    {{-- Tabel pending --}}
    <div class="overflow-x-auto py-4">
        <table class="min-w-full border text-sm">
            <thead class="bg-gray-100 dark:bg-gray-800">
                <tr>
                    <th class="px-4 py-2 border text-center">No</th>
                    <th class="px-4 py-2 border">Pending Start</th>
                    <th class="px-4 py-2 border">Pending End</th>
                    <th class="px-4 py-2 border">Reason</th>
                    <th class="px-4 py-2 border text-center">Duration (Minutes)</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pendings as $index => $p)
                    <tr class="border-b">
                        <td class="px-4 py-2 border text-center">{{ $index + 1 }}</td>
                        <td class="px-4 py-2 border">{{ $p->pending_start ? \Carbon\Carbon::parse($p->pending_start)->format('d M Y H:i') : '-' }}</td>
                        <td class="px-4 py-2 border">{{ $p->pending_end ? \Carbon\Carbon::parse($p->pending_end)->format('d M Y H:i') : '-' }}</td>
                        <td class="px-4 py-2 border">{{ $p->reason ?? '-' }}</td>
                        <td class="px-4 py-2 border text-center">{{ $p->duration_minutes ?? 0 }} Minutes</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-gray-500 py-4">Tidak ada pending tasks</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
