<?php

namespace App\Http\Controllers\API;

use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TicketReportController extends Controller
{
    public function ticketsByCategory(Request $request)
    {
        try {
            $request->validate([
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date|after_or_equal:start_date',
            ]);

            $query = Ticket::select('problem_category_id', DB::raw('count(*) as total'))
                ->with('problemCategory:id,problem_category_name');

            if ($request->has('start_date') && $request->start_date) {
                $startDate = Carbon::parse($request->start_date)->startOfDay();
                $query->where('created_at', '>=', $startDate);
            }

            if ($request->has('end_date') && $request->end_date) {
                $endDate = Carbon::parse($request->end_date)->endOfDay();
                $query->where('created_at', '<=', $endDate);
            }

            $tickets = $query->groupBy('problem_category_id')->get();

            $data = $tickets->map(function ($ticket) {
                return [
                    'category' => $ticket->problemCategory?->problem_category_name ?? 'Unknown',
                    'total' => $ticket->total
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $data,
                'filters' => [
                    'start_date' => $request->start_date,
                    'end_date' => $request->end_date
                ]
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


            $year = $request->year ?? Carbon::now()->year;

            $tickets = Ticket::select(
                DB::raw('MONTH(end_date) as month'),
                DB::raw('COUNT(*) as total')
            )
                ->whereYear('end_date', $year)
                ->whereNotNull('end_date')
                ->whereIn('status_id', [3,5])
                ->groupBy(DB::raw('MONTH(end_date)'))
                ->orderBy('month')
                ->get();

            $monthlyData = collect(range(1, 12))->mapWithKeys(function ($month) {
                return [$month => 0];
            });

            foreach ($tickets as $ticket) {
                $monthlyData[$ticket->month] = $ticket->total;
            }

            $data = $monthlyData->map(function ($total, $month) use ($year) {
                $monthName = Carbon::create($year, $month, 1)->format('F');
                return [
                    'month' => $monthName,
                    'month_number' => $month,
                    'total' => $total
                ];
            })->values();

            return response()->json([
                'success' => true,
                'data' => $data,
                'filters' => [
                    'year' => $year
                ]
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

            $query = Ticket::done();

            if ($request->start_date) {
                $startDate = Carbon::parse($request->start_date)->startOfDay();
                $query->where('end_date', '>=', $startDate);
            }
            if ($request->end_date) {
                $endDate = Carbon::parse($request->end_date)->endOfDay();
                $query->where('end_date', '<=', $endDate);
            }

            $avgResolutionTime = $query->avg('time_spent');
            $avgResolutionTime = $avgResolutionTime ? round($avgResolutionTime / 60, 2) : 0;

            $fullResolutionTime = $query->sum('time_spent');
            $fullResolutionTime = $fullResolutionTime ? round($fullResolutionTime / 60, 2) : 0;

            $totalCompleted = $query->count();
            $metSLA = (clone $query)->where('time_spent', '<=', 8 * 60)->count();
            $slaPercentage = $totalCompleted > 0
                ? round(($metSLA / $totalCompleted) * 100, 2)
                : 0;

            return response()->json([
                'success' => true,
                'data' => [
                    'fullResolutionTime' => $fullResolutionTime,
                    'avgResolutionTime'  => $avgResolutionTime,
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
        $year = $request->input('year', now()->year);

        $tickets = Ticket::selectRaw('support_id, MONTH(end_date) as month, COUNT(*) as total')
            ->whereYear('end_date', $year)
            ->done()
            ->groupBy('support_id', 'month')
            ->get();

        $months = range(1, 12);
        $supports = User::whereHas('role', fn($q) => $q->where('id', 1))->get();

        $datasets = [];
        foreach ($supports as $support) {
            $data = [];
            foreach ($months as $m) {
                $count = $tickets->where('support_id', $support->id)
                    ->where('month', $m)
                    ->sum('total');
                $data[] = $count;
            }
            $datasets[] = [
                'label' => $support->name,
                'data' => $data,
                'backgroundColor' => 'rgba(' . rand(0, 255) . ',' . rand(0, 255) . ',' . rand(0, 255) . ', 0.6)',
            ];
        }

        return response()->json([
            'success' => true,
            'data' => [
                'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                'datasets' => $datasets
            ]
        ]);
    }

    public function chartTimeSpentByDev(Request $request)
    {
        $year = $request->input('year', now()->year);

        $tickets = Ticket::selectRaw('support_id, MONTH(end_date) as month, SUM(time_spent) as total_minutes')
            ->whereYear('end_date', $year)
            ->done()
            ->groupBy('support_id', 'month')
            ->get();

        $months = range(1, 12);
        $supports = User::whereHas('role', fn($q) => $q->where('id', 1))->get();

        $datasets = [];
        foreach ($supports as $support) {
            $data = [];
            foreach ($months as $m) {
                $minutes = $tickets->where('support_id', $support->id)
                    ->where('month', $m)
                    ->sum('total_minutes');
                $data[] = round($minutes / 60, 2); // convert to hours
            }
            $datasets[] = [
                'label' => $support->name,
                'data' => $data,
                'backgroundColor' => 'rgba(' . rand(0, 255) . ',' . rand(0, 255) . ',' . rand(0, 255) . ', 0.6)',
            ];
        }

        return response()->json([
            'success' => true,
            'data' => [
                'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                'datasets' => $datasets
            ]
        ]);
    }

    public function ticketsBySupport(Request $request)
    {
        $date = $request->query('date'); // contoh: 2025-10-28

        $ticketsAzi = Ticket::where('support_id', 2)
            ->whereIn('status_id', [3,5])
            ->when($date, fn($q) => $q->whereDate('end_date', $date))
            ->select('ticket_code', 'problem', 'solution')
            ->get();

        $ticketsApri = Ticket::where('support_id', 3)
            ->whereIn('status_id', [3,5])
            ->when($date, fn($q) => $q->whereDate('end_date', $date))
            ->select('ticket_code', 'problem', 'solution')
            ->get();

        $ticketsBayu = Ticket::where('support_id', 4)
            ->whereIn('status_id', [3,5])
            ->when($date, fn($q) => $q->whereDate('end_date', $date))
            ->select('ticket_code', 'problem', 'solution')
            ->get();

        $ticketsFatih = Ticket::where('support_id', 5)
           ->whereIn('status_id', [3,5])
            ->when($date, fn($q) => $q->whereDate('end_date', $date))
            ->select('ticket_code', 'problem', 'solution')
            ->get();

        return response()->json([
            'data' => [
                'ticketsAzi' => $ticketsAzi,
                'ticketsApri' => $ticketsApri,
                'ticketsBayu' => $ticketsBayu,
                'ticketsFatih' => $ticketsFatih,
            ],
            'filter' => [
                'date' => $date,
            ],
        ]);
    }

 public function export(Request $request): \Symfony\Component\HttpFoundation\StreamedResponse
{
    $startDate = $request->query('start_date');
    $endDate   = $request->query('end_date');

    // Ambil semua tiket berdasarkan tanggal dibuat
    $tickets = Ticket::with([
            'user.department', // ✅ ambil user + department
            'support',
            'status',
            'assets',
            'problemCategory'
        ])
        ->whereBetween('created_at', [$startDate, $endDate])
        ->orderBy('created_at', 'asc')
        ->get([
            'ticket_code',
            'user_id',
            'support_id',
            'problem_category_id',
            'assets_id',
            'status_id',
            'problem',
            'solution',
            'notes',
            'start_date',
            'end_date',
            'time_spent',
            'is_late',
            'created_at',
        ]);

    $filename = "Data Ticket {$startDate} - {$endDate}.csv";

    $headers = [
        "Content-Type"        => "text/csv",
        "Content-Disposition" => "attachment; filename={$filename}",
        "Pragma"              => "no-cache",
        "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
        "Expires"             => "0",
    ];

    // Kolom CSV
    $columns = [
        'Ticket Code',
        'Requestor Name',
        'Requestor Division',
        'Support Name',
        'Category Problem',
        'Assets',
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

    // Stream CSV ke browser
    $callback = function () use ($tickets, $columns) {
        $file = fopen('php://output', 'w');
        fputcsv($file, $columns);

        foreach ($tickets as $ticket) {
            fputcsv($file, [
                $ticket->ticket_code,
                $ticket->user->name ?? '-',
                $ticket->user->department->department_name ?? '-',
                $ticket->support->name ?? '-',
                $ticket->problemCategory->problem_category_name ?? '-',
                $ticket->assets->assets_name ?? '-',
                $ticket->problem,
                $ticket->solution,
                $ticket->notes,
                $ticket->status->status_name ?? '-',
                $ticket->start_date,
                $ticket->end_date,
                $ticket->time_spent,
                $ticket->is_late ? 'Yes' : 'No',
                $ticket->created_at,
            ]);
        }

        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
}


    public function preview(Request $request)
    {
        $startDate = $request->query('start_date');
        $endDate   = $request->query('end_date');

        if (!$startDate || !$endDate) {
            return response()->json(['error' => 'Tanggal mulai dan akhir wajib diisi'], 400);
        }

        $tickets = Ticket::with(['user', 'support', 'status'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'asc')
            ->limit(50) // biar ringan
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
                'time_spent',
                'is_late',
                'created_at',
            ])
            ->map(function ($t) {
                return [
                    'ticket_code' => $t->ticket_code,
                    'requestor_name' => optional($t->user)->name ?? '-',
                    'support_name' => optional($t->support)->name ?? '-',
                    'problem' => $t->problem ?? '-',
                    'solution' => $t->solution ?? '-',
                    'notes' => $t->notes ?? '-',
                    'status_name' => optional($t->status)->status_name ?? '-',
                    'start_date' => $t->start_date ? $t->start_date->format('Y-m-d H:i') : '-',
                    'end_date' => $t->end_date ? $t->end_date->format('Y-m-d H:i') : '-',
                    'time_spent' => $t->time_spent ?? 0,
                    'is_late' => $t->is_late ? 'Yes' : 'No',
                    'created_at' => $t->created_at ? $t->created_at->format('Y-m-d H:i') : '-',
                ];
            });

        return response()->json(['data' => $tickets]);
    }
}
