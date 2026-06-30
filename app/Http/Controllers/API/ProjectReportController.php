<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ProjectDetail;
use App\Models\ProjectHeader;
use App\Models\Status;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ProjectReportController extends Controller
{
    public function ProjectQueue(Request $request)
    {
        $year = (int) $request->query('year', now()->year);

        // ✅ status waiting (project)
        $waitingId = Status::where('context', 'project')->where('type', 'waiting')->value('id');

        $ProjectQueue = ProjectHeader::with(['priority', 'requestor', 'developer', 'status'])
            ->whereYear('start_date', $year)
            ->when($waitingId, fn($q) => $q->where('status_id', $waitingId))
            ->get();

        return response()->json([
            'data' => [
                'ProjectQueue' => $ProjectQueue
            ],
        ]);
    }

    public function gantChart(Request $request)
    {
        $year = (int) $request->query('year', now()->year);

        $startOfYear = Carbon::create($year, 1, 1)->startOfDay();
        $endOfYear   = Carbon::create($year, 12, 31)->endOfDay();

        $projects = ProjectHeader::with('status') // ✅ biar status->name bisa dipakai
            ->select(
                'id',
                'project_name',
                'start_date',
                'end_date',
                'effective_end_date',
                'progress_percent',
                'status_id',
                'is_late',
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
            $start = Carbon::parse($p->start_date);
            $end   = Carbon::parse($p->effective_end_date ?? $p->end_date);

            if ($start->lt($startOfYear)) $start = $startOfYear->copy();
            if ($end->gt($endOfYear))     $end   = $endOfYear->copy();

            $statusName = $p->status->name ?? 'Unknown';

            return [
                'id'          => $p->id,
                'name'        => $p->project_name,
                'start'       => $start->format('Y-m-d'),
                'end'         => $end->format('Y-m-d'),
                'year'        => $start->format('Y'),
                'month_start' => $start->format('m'),
                'month_end'   => $end->format('m'),
                'day_start'   => $start->format('d'),
                'day_end'     => $end->format('d'),
                'progress'    => (int) $p->progress_percent,
                'status_id'   => $p->status_id,
                'status_name' => $statusName,
                'is_late'     => $statusName === 'Done'
                    ? ((int)$p->is_late === 1 ? 'Late' : 'On Time')
                    : '',
            ];
        });

        return response()->json($data);
    }

    public function summary(Request $request)
    {
        $year = (int) $request->query('year', now()->year);
        return response()->json(ProjectHeader::summary($year));
    }

    public function projectByDeveloper(Request $request)
    {
        $date = $request->query('date', now()->toDateString());

        // 1) ambil semua developer_id unik dari project_details
        $developers = ProjectDetail::query()
            ->whereNotNull('developer_id')
            ->distinct()
            ->pluck('developer_id');

        // 2) ambil detail pada tanggal tsb + join relasi yang bener
        $detailsByDev = ProjectDetail::with([
                'header:id,project_code,project_name',
                'status:id,name',
                'developer:id,name',
            ])
            ->whereNotNull('progress_date')
            ->whereDate('progress_date', $date)
            ->get()
            ->groupBy('developer_id');

        // 3) map hasilnya
        $result = $developers->map(function ($devId) use ($detailsByDev) {
            $items = $detailsByDev->get($devId, collect());

            return [
                'developer_id'   => $devId,
                'developer_name' => optional($items->first()?->developer)->name ?? null,
                'projects' => $items->map(function ($d) {
                    return [
                        'project_code'  => optional($d->header)->project_code ?? '-',
                        'project_name'  => optional($d->header)->project_name ?? '-',
                        'description'   => $d->description ?? '-', // ✅ ganti memo -> description
                        'progress'      => (int) ($d->progress_percent ?? 0),
                        'status'        => optional($d->status)->name ?? '-', // ✅ status_name -> name
                        'progress_date' => $d->progress_date ? $d->progress_date->format('Y-m-d') : '-',
                    ];
                })->values(),
            ];
        });

        return response()->json([
            'data' => $result->values(),
            'filter' => [
                'date' => $date,
            ],
        ]);
    }

    public function exportProject(Request $request): StreamedResponse
    {
        $startQ = $request->query('start_date');
        $endQ   = $request->query('end_date');

        if (!$startQ || !$endQ) abort(400, 'Tanggal wajib diisi');

        $startDate = Carbon::parse($startQ)->startOfDay();
        $endDate   = Carbon::parse($endQ)->endOfDay();

        $projects = ProjectDetail::with([
                'header.requestor',
                'header.priority',
                'header.status',
                'status',
                'developer',
            ])
            ->whereNotNull('progress_date')
            ->whereBetween('progress_date', [$startDate, $endDate])
            ->orderBy('progress_date', 'asc')
            ->get();

        $filename = "Project_Report_{$startDate->format('Ymd')}_{$endDate->format('Ymd')}.csv";

        $headers = [
            "Content-Type"        => "text/csv",
            "Content-Disposition" => "attachment; filename={$filename}",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate",
            "Expires"             => "0",
        ];

        $columns = [
            'Project Code',
            'Project Name',
            'Requestor',
            'Priority',
            'Project Status',
            'Developer Name',
            'Detail Status',
            'Progress Date',
            'Progress (%)',
            'Description',
            'Start Date',
            'End Date',
            'Is Late',
            'Notes',
        ];

        $callback = function () use ($projects, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($projects as $p) {
                fputcsv($file, [
                    optional($p->header)->project_code ?? '-',
                    optional($p->header)->project_name ?? '-',
                    optional(optional($p->header)->requestor)->name ?? '-',
                    optional(optional($p->header)->priority)->name ?? '-',
                    optional(optional($p->header)->status)->name ?? '-',
                    optional($p->developer)->name ?? '-',      // ✅ developer_name -> relasi users
                    optional($p->status)->name ?? '-',         // ✅ status_name -> name
                    $p->progress_date ? $p->progress_date->format('Y-m-d') : '-',
                    (int) ($p->progress_percent ?? 0),
                    $p->description ?? '-',              
                    optional($p->header)->start_date ? $p->header->start_date->format('Y-m-d') : '-',
                    optional($p->header)->end_date ? $p->header->end_date->format('Y-m-d') : '-',
                    optional($p->header)->is_late ? 'Yes' : 'No',
                    optional($p->header)->notes ?? '-',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function previewProject(Request $request)
    {
        $startQ = $request->query('start_date');
        $endQ   = $request->query('end_date');

        if (!$startQ || !$endQ) {
            return response()->json(['error' => 'Tanggal mulai dan akhir wajib diisi'], 400);
        }

        $startDate = Carbon::parse($startQ)->startOfDay();
        $endDate   = Carbon::parse($endQ)->endOfDay();

        $projects = ProjectDetail::with([
                'header.requestor',
                'header.priority',
                'header.status',
                'status',
                'developer',
            ])
            ->whereNotNull('progress_date')
            ->whereBetween('progress_date', [$startDate, $endDate])
            ->orderBy('progress_date', 'asc')
            ->limit(50)
            ->get()
            ->map(function ($p) {
                return [
                    'project_code'   => optional($p->header)->project_code ?? '-',
                    'project_name'   => optional($p->header)->project_name ?? '-',
                    'requestor'      => optional(optional($p->header)->requestor)->name ?? '-',
                    'priority'       => optional(optional($p->header)->priority)->name ?? '-',
                    'project_status' => optional(optional($p->header)->status)->name ?? '-',
                    'developer_name' => optional($p->developer)->name ?? '-',
                    'detail_status'  => optional($p->status)->name ?? '-',
                    'progress_date'  => $p->progress_date ? $p->progress_date->format('Y-m-d') : '-',
                    'progress'       => (int) ($p->progress_percent ?? 0),
                    'description'    => $p->description ?? '-',
                    'start_date'     => optional($p->header)->start_date ? $p->header->start_date->format('Y-m-d') : '-',
                    'end_date'       => optional($p->header)->end_date ? $p->header->end_date->format('Y-m-d') : '-',
                    'is_late'        => optional($p->header)->is_late ? 'Yes' : 'No',
                    'notes'          => optional($p->header)->notes ?? '-',
                ];
            });

        return response()->json(['data' => $projects]);
    }
}