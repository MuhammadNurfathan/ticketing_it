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
     * Helper: ambil status done IDs untuk context ticket
     * (lebih aman daripada hardcode [3,5])
     */
    private function doneStatusIds(): array
    {
        // Kalau lu punya 1 done: type='done'
        // Kalau lu punya 2 done: misalnya type in ('done','closed') -> tambah di sini
        return Status::where('context', 'ticket')
            ->whereIn('type', ['done'])
            ->pluck('id')
            ->map(fn($v) => (int)$v)
            ->values()
            ->all();
    }

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
                ->with('category:id,name') // ✅ pastikan relasi Ticket::category() dan kolom categories.name
                ->whereNotNull('end_date');

            // filter done
            if (!empty($doneIds)) {
                $query->whereIn('status_id', $doneIds);
            } elseif (method_exists(Ticket::class, 'scopeDone')) {
                $query->done();
            }

            if ($request->start_date) {
                $start = Carbon::parse($request->start_date)->startOfDay();
                $query->where('end_date', '>=', $start);
            }
            if ($request->end_date) {
                $end = Carbon::parse($request->end_date)->endOfDay();
                $query->where('end_date', '<=', $end);
            }

            $tickets = $query->groupBy('category_id')->get();

            $data = $tickets->map(function ($row) {
                return [
                    'category' => optional($row->category)->name ?? 'Unknown',
                    'total'    => (int) $row->total,
                ];
            });

            return response()->json([
                'success' => true,
                'data'    => $data,
                'filters' => [
                    'start_date' => $request->start_date,
                    'end_date'   => $request->end_date,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function ticketsDonePerMonth(Request $request)
    {
        try {
            $request->validate([
                'year' => 'nullable|integer|min:2020|max:2100',
            ]);

            $year = (int) ($request->year ?? now()->year);
            $doneIds = $this->doneStatusIds();

            $q = Ticket::query()
                ->select(
                    DB::raw('MONTH(end_date) as month'),
                    DB::raw('COUNT(*) as total')
                )
                ->whereNotNull('end_date')
                ->whereYear('end_date', $year);

            if (!empty($doneIds)) {
                $q->whereIn('status_id', $doneIds);
            } elseif (method_exists(Ticket::class, 'scopeDone')) {
                $q->done();
            }

            $tickets = $q->groupBy(DB::raw('MONTH(end_date)'))
                ->orderBy('month')
                ->get();

            $monthlyData = collect(range(1, 12))->mapWithKeys(fn($m) => [$m => 0]);

            foreach ($tickets as $t) {
                $monthlyData[(int)$t->month] = (int)$t->total;
            }

            $data = $monthlyData->map(function ($total, $month) use ($year) {
                return [
                    'month'        => Carbon::create($year, $month, 1)->format('F'),
                    'month_number' => (int) $month,
                    'total'        => (int) $total,
                ];
            })->values();

            return response()->json([
                'success' => true,
                'data'    => $data,
                'filters' => ['year' => $year],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
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

            if (!empty($doneIds)) {
                $query->whereIn('status_id', $doneIds);
            } elseif (method_exists(Ticket::class, 'scopeDone')) {
                $query->done();
            }

            if ($request->start_date) {
                $startDate = Carbon::parse($request->start_date)->startOfDay();
                $query->where('end_date', '>=', $startDate);
            }

            if ($request->end_date) {
                $endDate = Carbon::parse($request->end_date)->endOfDay();
                $query->where('end_date', '<=', $endDate);
            }

            $avgMinutes  = (float) ($query->avg('time_spent_minutes') ?: 0);
            $fullMinutes = (int) ($query->sum('time_spent_minutes') ?: 0);

            $totalCompleted = (int) $query->count();
            $metSLA = (int) (clone $query)->where('time_spent_minutes', '<=', 8 * 60)->count();
            $slaPercentage = $totalCompleted > 0 ? round(($metSLA / $totalCompleted) * 100, 2) : 0;

            $convertToHourMinute = function ($minutes) {
                $minutes = (int) round($minutes);
                $h = intdiv($minutes, 60);
                $m = $minutes % 60;

                if ($h <= 0) return "{$minutes} menit";
                if ($m <= 0) return "{$h} jam";
                return "{$h} jam {$m} menit";
            };

            return response()->json([
                'success' => true,
                'data' => [
                    'fullResolutionTime' => $convertToHourMinute($fullMinutes),
                    'avgResolutionTime'  => $convertToHourMinute($avgMinutes),
                    'slaPercentage'      => $slaPercentage,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function chartTicketsByDev(Request $request)
    {
        $year = (int) $request->input('year', now()->year);
        $doneIds = $this->doneStatusIds();

        $q = Ticket::query()
            ->selectRaw('support_id, MONTH(end_date) as month, COUNT(*) as total')
            ->whereNotNull('end_date')
            ->whereYear('end_date', $year)
            ->groupBy('support_id', 'month');

        if (!empty($doneIds)) {
            $q->whereIn('status_id', $doneIds);
        } elseif (method_exists(Ticket::class, 'scopeDone')) {
            $q->done();
        }

        $tickets = $q->get();

        $months = range(1, 12);

        // ✅ developer/support ambil role_id=1 sesuai project lu
        $supports = User::query()
            ->where('role_id', 1)
            ->select('id', 'name')
            ->get();

        $datasets = [];
        foreach ($supports as $support) {
            $data = [];
            foreach ($months as $m) {
                $count = (int) $tickets->where('support_id', $support->id)
                    ->where('month', $m)
                    ->sum('total');
                $data[] = $count;
            }

            $datasets[] = [
                'label' => $support->name,
                'data'  => $data,
            ];
        }

        return response()->json([
            'success' => true,
            'data' => [
                'labels'   => ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'],
                'datasets' => $datasets
            ]
        ]);
    }

    public function chartTimeSpentByDev(Request $request)
    {
        $year = (int) $request->input('year', now()->year);
        $doneIds = $this->doneStatusIds();

        $q = Ticket::query()
            ->selectRaw('support_id, MONTH(end_date) as month, SUM(time_spent_minutes) as total_minutes')
            ->whereNotNull('end_date')
            ->whereYear('end_date', $year)
            ->groupBy('support_id', 'month');

        if (!empty($doneIds)) {
            $q->whereIn('status_id', $doneIds);
        } elseif (method_exists(Ticket::class, 'scopeDone')) {
            $q->done();
        }

        $tickets = $q->get();

        $months = range(1, 12);
        $supports = User::query()
            ->where('role_id', 1)
            ->select('id', 'name')
            ->get();

        $datasets = [];
        foreach ($supports as $support) {
            $data = [];
            foreach ($months as $m) {
                $minutes = (int) $tickets->where('support_id', $support->id)
                    ->where('month', $m)
                    ->sum('total_minutes');
                $data[] = $minutes;
            }
            $datasets[] = [
                'label' => $support->name,
                'data'  => $data,
            ];
        }

        return response()->json([
            'success' => true,
            'data' => [
                'labels'   => ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'],
                'datasets' => $datasets
            ]
        ]);
    }

    public function chartTimeSpentByDepartment(Request $request)
    {
        $year = (int) $request->input('year', now()->year);
        $doneIds = $this->doneStatusIds();

        $q = Ticket::query()
            ->selectRaw('
                departments.id as department_id,
                MONTH(tickets.end_date) as month,
                COALESCE(SUM(tickets.time_spent_minutes), 0) as total_minutes
            ')
            ->join('users', 'tickets.user_id', '=', 'users.id')
            ->join('departments', 'users.department_id', '=', 'departments.id')
            ->whereNotNull('tickets.end_date')
            ->whereYear('tickets.end_date', $year)
            ->groupBy('departments.id', 'month');

        if (!empty($doneIds)) {
            $q->whereIn('tickets.status_id', $doneIds);
        } elseif (method_exists(Ticket::class, 'scopeDone')) {
            $q->done();
        }

        $tickets = $q->get();
        $months = range(1, 12);

        $departments = Department::with('location')->get();

        $datasets = [];
        foreach ($departments as $department) {
            $data = [];
            foreach ($months as $m) {
                $minutes = (int) $tickets
                    ->where('department_id', $department->id)
                    ->where('month', $m)
                    ->sum('total_minutes');

                $data[] = $minutes;
            }

            $datasets[] = [
                'label' => $department->name
    . ($department->location ? ' - ' . $department->location->name : ''),
                'data' => $data,
            ];
        }

        return response()->json([
            'success' => true,
            'data' => [
                'labels'   => ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'],
                'datasets' => $datasets
            ]
        ]);
    }

    public function ticketsBySupport(Request $request)
{
    $date = $request->query('date', now()->toDateString());

    // Ambil id status DONE untuk context ticket (lebih aman dari hardcode)
    $doneStatusIds = \App\Models\Status::where('context', 'ticket')
        ->where('type', 'done')
        ->pluck('id')
        ->all();

    $supports = \App\Models\User::query()
        ->where('role_id', 1)
        ->select('id', 'name')
        ->with([
            // ✅ PAKAI handledTickets (support_id), bukan tickets (user_id)
            'handledTickets' => function ($q) use ($date, $doneStatusIds) {
                $q->select('id', 'ticket_code', 'problem', 'solution', 'support_id', 'status_id', 'end_date')
                  ->with(['status:id,name,type,context', 'feedback:id,ticket_id,created_at'])

                  // ✅ kondisi: (DONE) OR (punya feedback)
                  ->where(function ($qq) use ($doneStatusIds) {
                      // DONE
                      if (!empty($doneStatusIds)) {
                          $qq->whereIn('status_id', $doneStatusIds);
                      } else {
                          // fallback kalau doneStatusIds kosong
                          $qq->whereHas('status', fn($s) => $s->where('context','ticket')->where('type','done'));
                      }

                      // OR punya feedback
                      $qq->orWhereHas('feedback');
                  })

                  // ✅ filter tanggal:
                  // - DONE pakai end_date
                  // - feedback-only pakai feedback.created_at
                  ->where(function ($qd) use ($date, $doneStatusIds) {
                      // DONE part by end_date
                      $qd->where(function ($a) use ($date, $doneStatusIds) {
                          $a->whereNotNull('end_date')
                            ->whereDate('end_date', $date);

                          if (!empty($doneStatusIds)) {
                              $a->whereIn('status_id', $doneStatusIds);
                          } else {
                              $a->whereHas('status', fn($s) => $s->where('context','ticket')->where('type','done'));
                          }
                      })

                      // feedback-only part by feedback date
                      ->orWhere(function ($b) use ($date, $doneStatusIds) {
                          // yang ini khusus ticket yg punya feedback
                          $b->whereHas('feedback', fn($f) => $f->whereDate('created_at', $date));

                          // optional: kalau lu mau pastiin yang ini bukan done (biar gak double logic), boleh:
                          // if (!empty($doneStatusIds)) $b->whereNotIn('status_id', $doneStatusIds);
                      });
                  });
            }
        ])
        ->get();

    $result = $supports->map(function ($support) {
        return [
            'support_id'   => $support->id,
            'support_name' => $support->name,
            'tickets'      => $support->handledTickets->map(function ($t) {
                return [
                    'ticket_code'    => $t->ticket_code,
                    'problem'        => $t->problem,
                    'solution'       => $t->solution,
                    'status'         => optional($t->status)->name ?? '-',
                    'end_date'       => $t->end_date ? $t->end_date->format('Y-m-d H:i') : '-',
                    'feedback_date'  => optional($t->feedback)->created_at?->format('Y-m-d H:i') ?? '-',
                ];
            })->values(),
        ];
    });

    return response()->json([
        'data' => $result,
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

        // ✅ report lebih masuk akal pakai created_at range
        // kalau lu maunya DONE range, ganti created_at -> end_date
        $tickets = Ticket::with([
                'user.department',
                'support',
                'status',
                'assets',
                'category',
            ])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'asc')
            ->get([
                'ticket_code',
                'user_id',
                'support_id',
                'category_id',
                'assets_id',
                'status_id',
                'problem',
                'solution',
                'notes',
                'start_date',
                'end_date',
                'time_spent_minutes',
                'is_late',
                'created_at',
            ]);

        $filename = "Data_Ticket_{$startDate->format('Ymd')}_{$endDate->format('Ymd')}.csv";

        $headers = [
            "Content-Type"        => "text/csv",
            "Content-Disposition" => "attachment; filename={$filename}",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0",
        ];

        $columns = [
            'Ticket Code',
            'Requestor Name',
            'Requestor Division',
            'Support Name',
            'Category',
            'Asset',
            'Problem',
            'Solution',
            'Notes',
            'Status',
            'Start Date',
            'End Date',
            'Time Spent (Minutes)',
            'Is Late',
            'Created At',
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
                    optional($t->category)->name ?? '-',     // ✅ problemCategory -> category, name
                    optional($t->assets)->name ?? '-',       // ✅ assets_name -> name
                    $t->problem ?? '-',
                    $t->solution ?? '-',
                    $t->notes ?? '-',
                    optional($t->status)->name ?? '-',       // ✅ status_name -> name
                    $t->start_date ? Carbon::parse($t->start_date)->format('Y-m-d H:i') : '-',
                    $t->end_date ? Carbon::parse($t->end_date)->format('Y-m-d H:i') : '-',
                    (int) ($t->time_spent_minutes ?? 0),
                    $t->is_late ? 'Yes' : 'No',
                    $t->created_at ? $t->created_at->format('Y-m-d H:i') : '-',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
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
                'ticket_code',
                'user_id',
                'support_id',
                'problem',
                'solution',
                'notes',
                'status_id',
                'start_date',
                'end_date',
                'time_spent_minutes',
                'is_late',
                'created_at',
            ])
            ->map(function ($t) {
                return [
                    'ticket_code'     => $t->ticket_code,
                    'requestor_name'  => optional($t->user)->name ?? '-',
                    'support_name'    => optional($t->support)->name ?? '-',
                    'problem'         => $t->problem ?? '-',
                    'solution'        => $t->solution ?? '-',
                    'notes'           => $t->notes ?? '-',
                    'status'          => optional($t->status)->name ?? '-', // ✅ status_name -> name
                    'start_date'      => $t->start_date ? Carbon::parse($t->start_date)->format('Y-m-d H:i') : '-',
                    'end_date'        => $t->end_date ? Carbon::parse($t->end_date)->format('Y-m-d H:i') : '-',
                    'time_spent_minutes'      => (int) ($t->time_spent_minutes ?? 0),
                    'is_late'         => $t->is_late ? 'Yes' : 'No',
                    'created_at'      => $t->created_at ? $t->created_at->format('Y-m-d H:i') : '-',
                ];
            });

        return response()->json(['data' => $tickets]);
    }
}