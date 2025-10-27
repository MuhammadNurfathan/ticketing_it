<?php

namespace App\Http\Controllers\API;

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
                ->where('status_id', 3)
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


public function chartTicketsByDev()
{
    // Ambil semua user dengan role_id = 1 (developer)
    $developers = User::where('role_id', 1)->get();

    // Buat daftar 6 bulan terakhir, format 'Y-m'
    $months = collect(range(0, 5))
        ->map(function($i) {
            return now()->subMonths($i)->format('Y-m');
        })
        ->reverse();

    // Ubah ke format bulan untuk label chart, misal 'Oct 2025'
    $labels = $months->map(function($month) {
        return \Carbon\Carbon::parse($month.'-01')->format('M Y');
    })->values()->toArray();

    $datasets = [];

    // Loop tiap developer
    foreach ($developers as $user) {
        $data = [];

        // Loop tiap bulan
        foreach ($months as $month) {
            [$year, $mon] = explode('-', $month);

            // Hitung tiket yang sudah Done atau Feedback
            $ticketCount = Ticket::where('support_id', $user->id)
                ->done() // scope Done sudah termasuk Feedback
                ->whereYear('request_date', $year)
                ->whereMonth('request_date', $mon)
                ->count();

            $data[] = $ticketCount;
        }

        // Tambahkan ke datasets
        $datasets[] = [
            'label' => $user->name, // nama developer
            'data' => $data,        // jumlah tiket per bulan
            'backgroundColor' => 'rgba('.rand(0,255).','.rand(0,255).','.rand(0,255).',0.2)',
            'borderColor' => 'rgb('.rand(0,255).','.rand(0,255).','.rand(0,255).')',
            'borderWidth' => 1
        ];
    }

    // Kembalikan response JSON untuk Chart.js
    return response()->json([
        'labels' => $labels,   // horizontal: bulan
        'datasets' => $datasets // tiap developer satu dataset
    ]);
}


public function chartTimeSpentByDev()
{
    $developers = User::where('role_id', 1)->get();

    $months = collect(range(0, 5))
        ->map(fn($i) => now()->subMonths($i)->format('Y-m'))
        ->reverse();

    $labels = $months->map(fn($m) => \Carbon\Carbon::parse($m.'-01')->format('M Y'))->values()->toArray();

    $datasets = [];

    foreach ($developers as $user) {
        $data = [];
        foreach ($months as $month) {
            [$year, $mon] = explode('-', $month);

            $totalTime = Ticket::where('support_id', $user->id)
                ->done() // Done atau Feedback
                ->whereYear('request_date', $year)
                ->whereMonth('request_date', $mon)
                ->sum('time_spent');

            $data[] = $totalTime;
        }

        $datasets[] = [
            'label' => $user->name,
            'data' => $data,
            'backgroundColor' => 'rgba('.rand(0,255).','.rand(0,255).','.rand(0,255).',0.2)',
            'borderColor' => 'rgb('.rand(0,255).','.rand(0,255).','.rand(0,255).')',
            'borderWidth' => 1
        ];
    }

    return response()->json([
        'labels' => $labels,
        'datasets' => $datasets
    ]);
}



}
