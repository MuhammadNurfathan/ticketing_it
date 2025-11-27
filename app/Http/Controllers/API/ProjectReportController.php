<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\ProjectHeader;
use Illuminate\Http\Request;

class ProjectReportController extends Controller
{
    public function ProjectQueue(Request $request){
        $year = $request->query('year', now()->year);

        $ProjectQueue = ProjectHeader::with(['priority', 'requestor', 'developer', 'status'])
            ->whereYear('start_date', $year)
            ->where('status_id', 1)
            ->get();

        return response()->json([
            'data' => [
                'ProjectQueue' => $ProjectQueue
            ],
        ]);
    }

    public function gantChart(Request $request){
        $year = $request->query('year', now()->year);

        $startOfYear = "{$year}-01-01";
        $endOfYear = "{$year}-12-31";

        $projects = ProjectHeader::select(
            'id',
            'project_name',
            'start_date',
            'end_date',
            'effective_end_date',
            'progress_percent',
            'status_id'
        )
            ->where(function ($query) use ($year) {
                $query->whereYear('start_date', '<=', $year)
                    ->where(function ($sub) use ($year) {
                        $sub->whereYear('effective_end_date', '>=', $year)
                            ->orWhere(function ($q2) use ($year) {
                                $q2->whereNull('effective_end_date')
                                    ->whereYear('end_date', '>=', $year);
                            });
                    });
            })
            ->get();

        $data = $projects->map(function ($p) use ($startOfYear, $endOfYear) {
            $start = $p->start_date;
            $end = $p->effective_end_date ?? $p->end_date;

            if ($start < $startOfYear) {
                $start = $startOfYear;
            }
            if ($end > $endOfYear) {
                $end = $endOfYear;
            }

            return [
                'id' => $p->id,
                'name' => $p->project_name,
                'start' => \Carbon\Carbon::parse($start)->format('Y-m-d'),
                'end' => \Carbon\Carbon::parse($end)->format('Y-m-d'),
                'year' => \Carbon\Carbon::parse($start)->format('Y'),
                'month_start' => \Carbon\Carbon::parse($start)->format('m'),
                'month_end' => \Carbon\Carbon::parse($end)->format('m'),
                'day_start' => \Carbon\Carbon::parse($start)->format('d'),
                'day_end' => \Carbon\Carbon::parse($end)->format('d'),
                'progress' => intval($p->progress_percent),
                'status_id' => $p->status_id,
                'status_name' => $p->status->status_name ?? 'Unknown',
            ];
        });

        return response()->json($data);
    }

    public function summary(Request $request){
        $year = $request->query('year', now()->year);
        $summary = ProjectHeader::summary($year);
        return response()->json($summary);
    }
}
