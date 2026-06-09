<?php

namespace App\Http\Controllers\API;

use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TicketReportController extends Controller
{
    /**
     * Ambil status IDs yang dianggap "selesai" untuk context ticket.
     */
    private function doneStatusIds(): array
    {
        return Status::where('context', 'ticket')
            ->whereIn('type', ['done', 'feedback'])
            ->pluck('id')
            ->map(fn($v) => (int) $v)
            ->values()
            ->all();
    }

    /**
     * Apply filter done status ke query.
     */
    private function applyDoneFilter($query, array $doneIds, string $prefix = ''): void
    {
        $col = $prefix ? "{$prefix}.status_id" : 'status_id';
        if (!empty($doneIds)) {
            $query->whereIn($col, $doneIds);
        } elseif (method_exists(Ticket::class, 'scopeDone')) {
            $query->done();
        }
    }

    /**
     * Apply filter tanggal (end_date) ke query.
     */
    private function applyDateFilter($query, ?string $startDate, ?string $endDate, string $col = 'end_date'): void
    {
        if ($startDate) {
            $query->where($col, '>=', Carbon::parse($startDate)->startOfDay());
        }
        if ($endDate) {
            $query->where($col, '<=', Carbon::parse($endDate)->endOfDay());
        }
    }

    /**
     * Konversi menit ke format "X jam Y menit".
     */
    private function formatMinutes(int|float $minutes): string
    {
        $minutes = (int) round($minutes);
        $h = intdiv($minutes, 60);
        $m = $minutes % 60;

        if ($h <= 0) return "{$minutes} menit";
        if ($m <= 0) return "{$h} jam";
        return "{$h} jam {$m} menit";
    }

    // ─────────────────────────────────────────────────────────────
    // Endpoints
    // ─────────────────────────────────────────────────────────────

    public function ticketsByCategory(Request $request)
    {
        try {
            $request->validate([
                'start_date' => 'nullable|date',
                'end_date'   => 'nullable|date|after_or_equal:start_date',
            ]);

            $doneIds = $this->doneStatusIds();

            $query = Ticket::query()
                ->select('category_id', DB::raw('COUNT(*) as total'))
                ->with('category:id,name')
                ->whereNotNull('end_date');

            $this->applyDoneFilter($query, $doneIds);
            $this->applyDateFilter($query, $request->start_date, $request->end_date);

            $data = $query->groupBy('category_id')->get()
                ->map(fn($row) => [
                    'category' => optional($row->category)->name ?? 'Unknown',
                    'total'    => (int) $row->total,
                ]);

            return response()->json([
                'success' => true,
                'data'    => $data,
                'filters' => [
                    'start_date' => $request->start_date,
                    'end_date'   => $request->end_date,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function ticketsDonePerMonth(Request $request)
    {
        try {
            $request->validate([
                'year' => 'nullable|integer|min:2020|max:2100',
            ]);

            $year    = (int) ($request->year ?? now()->year);
            $doneIds = $this->doneStatusIds();

            $query = Ticket::query()
                ->select(DB::raw('MONTH(end_date) as month'), DB::raw('COUNT(*) as total'))
                ->whereNotNull('end_date')
                ->whereYear('end_date', $year);

            $this->applyDoneFilter($query, $doneIds);

            $tickets = $query->groupBy(DB::raw('MONTH(end_date)'))->orderBy('month')->get();

            $monthlyData = collect(range(1, 12))->mapWithKeys(fn($m) => [$m => 0]);
            foreach ($tickets as $t) {
                $monthlyData[(int) $t->month] = (int) $t->total;
            }

            $data = $monthlyData->map(fn($total, $month) => [
                'month'        => Carbon::create($year, $month, 1)->format('F'),
                'month_number' => (int) $month,
                'total'        => (int) $total,
            ])->values();

            return response()->json([
                'success' => true,
                'data'    => $data,
                'filters' => ['year' => $year],
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function statistik(Request $request)
    {
        try {
            $request->validate([
                'start_date' => 'nullable|date',
                'end_date'   => 'nullable|date|after_or_equal:start_date',
            ]);

            $doneIds = $this->doneStatusIds();

            $query = Ticket::query()->whereNotNull('end_date');
            $this->applyDoneFilter($query, $doneIds);
            $this->applyDateFilter($query, $request->start_date, $request->end_date);

            $avgMinutes  = (float) ($query->avg('time_spent_minutes') ?: 0);
            $fullMinutes = (int) ($query->sum('time_spent_minutes') ?: 0);

            $totalCompleted = (int) $query->count();
            $metSLA         = (int) (clone $query)->where('time_spent_minutes', '<=', 8 * 60)->count();
            $slaPercentage  = $totalCompleted > 0 ? round(($metSLA / $totalCompleted) * 100, 2) : 0;

            return response()->json([
                'success' => true,
                'data'    => [
                    'fullResolutionTime' => $this->formatMinutes($fullMinutes),
                    'avgResolutionTime'  => $this->formatMinutes($avgMinutes),
                    'slaPercentage'      => $slaPercentage,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function chartTicketsByDev(Request $request)
    {
        $year    = (int) $request->input('year', now()->year);
        $doneIds = $this->doneStatusIds();

        $query = Ticket::query()
            ->selectRaw('support_id, MONTH(end_date) as month, COUNT(*) as total')
            ->whereNotNull('end_date')
            ->whereYear('end_date', $year)
            ->groupBy('support_id', DB::raw('MONTH(end_date)'));

        $this->applyDoneFilter($query, $doneIds);

        $tickets  = $query->get();
        $supports = User::where('role_id', 1)->select('id', 'name')->get();
        $months   = range(1, 12);

        $datasets = $supports->map(fn($support) => [
            'label' => $support->name,
            'data'  => collect($months)->map(
                fn($m) => (int) $tickets->where('support_id', $support->id)->where('month', $m)->sum('total')
            )->values()->all(),
        ])->values()->all();

        return response()->json([
            'success' => true,
            'data'    => [
                'labels'   => ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                'datasets' => $datasets,
            ],
        ]);
    }

    public function chartTimeSpentByDev(Request $request)
    {
        $year    = (int) $request->input('year', now()->year);
        $doneIds = $this->doneStatusIds();

        // ✅ Hitung SUM time_spent_minutes per support_id per bulan
        // Tidak ada JOIN ke tabel lain → tidak ada risiko baris dobel
        $query = Ticket::query()
            ->selectRaw('support_id, MONTH(end_date) as month, SUM(time_spent_minutes) as total_minutes')
            ->whereNotNull('end_date')
            ->whereNotNull('support_id')
            ->whereYear('end_date', $year)
            ->groupBy('support_id', DB::raw('MONTH(end_date)'));

        $this->applyDoneFilter($query, $doneIds);

        $tickets  = $query->get();
        $supports = User::where('role_id', 1)->select('id', 'name')->get();
        $months   = range(1, 12);

        $datasets = $supports->map(fn($support) => [
            'label' => $support->name,
            'data'  => collect($months)->map(
                fn($m) => (int) $tickets->where('support_id', $support->id)->where('month', $m)->sum('total_minutes')
            )->values()->all(),
        ])->values()->all();

        return response()->json([
            'success' => true,
            'data'    => [
                'labels'   => ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                'datasets' => $datasets,
            ],
        ]);
    }

    public function chartTimeSpentByDepartment(Request $request)
    {
        $year    = (int) $request->input('year', now()->year);
        $doneIds = $this->doneStatusIds();

        // ✅ FIX: Subquery dulu untuk hindari baris dobel akibat JOIN multi-tabel.
        //
        // Pola lama:  tickets JOIN users JOIN departments → groupBy dept + month
        // Masalahnya: kalau ada relasi 1-to-many di tengah (misal user punya banyak dept,
        //             atau eager load lain), bisa muncul baris dobel dan SUM jadi kembung.
        //
        // Pola baru:  tickets groupBy(user_id, month) dulu → hasilnya di-join ke users
        //             → baru groupBy(dept_id, month).
        //             Dengan begini setiap ticket hanya dihitung 1x.

        $subQuery = Ticket::query()
            ->selectRaw('user_id, MONTH(end_date) as month, SUM(time_spent_minutes) as total_minutes')
            ->whereNotNull('end_date')
            ->whereNotNull('user_id')
            ->whereYear('end_date', $year);

        $this->applyDoneFilter($subQuery, $doneIds);

        $subQuery->groupBy('user_id', DB::raw('MONTH(end_date)'));

        // Wrap sebagai subquery lalu join ke users untuk dapat department_id
        $rows = DB::table(DB::raw("({$subQuery->toSql()}) as t"))
            ->mergeBindings($subQuery->getQuery())
            ->join('users', 't.user_id', '=', 'users.id')
            ->selectRaw('users.department_id, t.month, SUM(t.total_minutes) as total_minutes')
            ->whereNotNull('users.department_id')
            ->groupBy('users.department_id', 't.month')
            ->get();

        $departments = Department::with('location')->get();
        $months      = range(1, 12);

        $datasets = $departments->map(function ($dept) use ($rows, $months) {
            return [
                'label' => $dept->name . ($dept->location ? ' - ' . $dept->location->name : ''),
                'data'  => collect($months)->map(
                    fn($m) => (int) $rows->where('department_id', $dept->id)->where('month', $m)->sum('total_minutes')
                )->values()->all(),
            ];
        })->values()->all();

        return response()->json([
            'success' => true,
            'data'    => [
                'labels'   => ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                'datasets' => $datasets,
            ],
        ]);
    }

    public function ticketsBySupport(Request $request)
    {
        $date        = $request->query('date', now()->toDateString());
        $doneStatusIds = Status::where('context', 'ticket')
            ->where('type', 'done')
            ->pluck('id')
            ->all();

        $supports = User::where('role_id', 1)
            ->select('id', 'name')
            ->with([
                'handledTickets' => function ($q) use ($date, $doneStatusIds) {
                    $q->select('id', 'ticket_code', 'problem', 'solution', 'support_id', 'status_id', 'end_date')
                      ->with(['status:id,name,type,context', 'feedback:id,ticket_id,created_at'])
                      ->where(function ($qq) use ($doneStatusIds) {
                          if (!empty($doneStatusIds)) {
                              $qq->whereIn('status_id', $doneStatusIds);
                          } else {
                              $qq->whereHas('status', fn($s) => $s->where('context', 'ticket')->where('type', 'done'));
                          }
                          $qq->orWhereHas('feedback');
                      })
                      ->where(function ($qd) use ($date, $doneStatusIds) {
                          $qd->where(function ($a) use ($date, $doneStatusIds) {
                              $a->whereNotNull('end_date')->whereDate('end_date', $date);
                              if (!empty($doneStatusIds)) {
                                  $a->whereIn('status_id', $doneStatusIds);
                              } else {
                                  $a->whereHas('status', fn($s) => $s->where('context', 'ticket')->where('type', 'done'));
                              }
                          })
                          ->orWhere(fn($b) => $b->whereHas('feedback', fn($f) => $f->whereDate('created_at', $date)));
                      });
                },
            ])
            ->get();

        $result = $supports->map(fn($support) => [
            'support_id'   => $support->id,
            'support_name' => $support->name,
            'tickets'      => $support->handledTickets->map(fn($t) => [
                'ticket_code'   => $t->ticket_code,
                'problem'       => $t->problem,
                'solution'      => $t->solution,
                'status'        => optional($t->status)->name ?? '-',
                'end_date'      => $t->end_date ? $t->end_date->format('Y-m-d H:i') : '-',
                'feedback_date' => optional($t->feedback)->created_at?->format('Y-m-d H:i') ?? '-',
            ])->values(),
        ]);

        return response()->json([
            'data'   => $result,
            'filter' => ['date' => $date],
        ]);
    }

    public function export(Request $request): StreamedResponse
    {
        $startQ = $request->query('start_date');
        $endQ   = $request->query('end_date');

        if (!$startQ || !$endQ) abort(400, 'Tanggal wajib diisi');

        $startDate = Carbon::parse($startQ)->startOfDay();
        $endDate   = Carbon::parse($endQ)->endOfDay();

        $tickets = Ticket::with(['user.department', 'support', 'status', 'assets', 'category'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'asc')
            ->get([
                'ticket_code', 'user_id', 'support_id', 'category_id', 'assets_id', 'status_id',
                'problem', 'solution', 'notes', 'start_date', 'end_date',
                'time_spent_minutes', 'is_late', 'created_at',
            ]);

        $filename = "Data_Ticket_{$startDate->format('Ymd')}_{$endDate->format('Ymd')}.csv";

        $columns = [
            'Ticket Code', 'Requestor Name', 'Requestor Division', 'Support Name',
            'Category', 'Asset', 'Problem', 'Solution', 'Notes', 'Status',
            'Start Date', 'End Date', 'Time Spent (Minutes)', 'Is Late', 'Created At',
        ];

        $callback = function () use ($tickets, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($tickets as $t) {
                fputcsv($file, [
                    $t->ticket_code,
                    optional($t->user)->name ?? '-',
                    optional(optional($t->user)->department)->name ?? '-',
                    optional($t->support)->name ?? '-',
                    optional($t->category)->name ?? '-',
                    optional($t->assets)->name ?? '-',
                    $t->problem ?? '-',
                    $t->solution ?? '-',
                    $t->notes ?? '-',
                    optional($t->status)->name ?? '-',
                    $t->start_date ? Carbon::parse($t->start_date)->format('Y-m-d H:i') : '-',
                    $t->end_date ? Carbon::parse($t->end_date)->format('Y-m-d H:i') : '-',
                    (int) ($t->time_spent_minutes ?? 0),
                    $t->is_late ? 'Yes' : 'No',
                    $t->created_at ? $t->created_at->format('Y-m-d H:i') : '-',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ]);
    }

    public function preview(Request $request)
    {
        $startQ = $request->query('start_date');
        $endQ   = $request->query('end_date');

        if (!$startQ || !$endQ) {
            return response()->json(['error' => 'Tanggal mulai dan akhir wajib diisi'], 400);
        }

        $startDate = Carbon::parse($startQ)->startOfDay();
        $endDate   = Carbon::parse($endQ)->endOfDay();

        $tickets = Ticket::with(['user', 'support', 'status'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'asc')
            ->limit(50)
            ->get([
                'ticket_code', 'user_id', 'support_id', 'problem', 'solution',
                'notes', 'status_id', 'start_date', 'end_date',
                'time_spent_minutes', 'is_late', 'created_at',
            ])
            ->map(fn($t) => [
                'ticket_code'        => $t->ticket_code,
                'requestor_name'     => optional($t->user)->name ?? '-',
                'support_name'       => optional($t->support)->name ?? '-',
                'problem'            => $t->problem ?? '-',
                'solution'           => $t->solution ?? '-',
                'notes'              => $t->notes ?? '-',
                'status'             => optional($t->status)->name ?? '-',
                'start_date'         => $t->start_date ? Carbon::parse($t->start_date)->format('Y-m-d H:i') : '-',
                'end_date'           => $t->end_date ? Carbon::parse($t->end_date)->format('Y-m-d H:i') : '-',
                'time_spent_minutes' => (int) ($t->time_spent_minutes ?? 0),
                'is_late'            => $t->is_late ? 'Yes' : 'No',
                'created_at'         => $t->created_at ? $t->created_at->format('Y-m-d H:i') : '-',
            ]);

        return response()->json(['data' => $tickets]);
    }
}