<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ProjectDetail;
use App\Models\ProjectHeader;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ProjectReportController extends Controller
{
    public function ProjectQueue(Request $request)
    {
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

    public function gantChart(Request $request)
    {
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
                'is_late' => $p->status->status_name === 'Done'
                    ? ($p->is_late == 1 ? 'Late' : 'On Time')
                    : '',

            ];
        });

        return response()->json($data);
    }

    public function summary(Request $request)
    {
        $year = $request->query('year', now()->year);
        return response()->json(ProjectHeader::summary($year));
    }

public function projectByDeveloper(Request $request)
{
    $date = $request->query('date', now()->toDateString());

    // 1. Ambil SEMUA developer_name unik
    $developers = ProjectDetail::select('developer_name')
        ->whereNotNull('developer_name')
        ->where('developer_name', '!=', '')
        ->distinct()
        ->pluck('developer_name');

    // 2. Ambil project sesuai tanggal
    $projects = ProjectDetail::with([
        'header:id,project_code,project_name',
        'status:id,status_name'
    ])
        ->whereNotNull('progress_date') // ✅ Tambahin ini
        ->whereDate('progress_date', $date)
        ->get()
        ->groupBy('developer_name');

    // 3. Gabungkan → developer kosong tetap tampil
    $result = $developers->map(function ($devName) use ($projects) {
        $items = $projects->get($devName, collect());

        return [
            'developer_name' => $devName,
            'projects' => $items->map(function ($d) {
                return [
                    'project_code'  => optional($d->header)->project_code ?? '-',
                    'project_name'  => optional($d->header)->project_name ?? '-',
                    'memo'          => $d->memo ?? '-',
                    'progress'      => $d->progress_percent ?? 0,
                    'status'        => optional($d->status)->status_name ?? '-',
                    'progress_date' => $d->progress_date ? $d->progress_date->format('Y-m-d') : '-', // ✅ Fix ini
                ];
            })->values()
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
    $startDate = $request->query('start_date');
    $endDate   = $request->query('end_date');

    if (!$startDate || !$endDate) {
        abort(400, 'Tanggal wajib diisi');
    }

    $projects = ProjectDetail::with([
        'header.requestor',
        'header.priority',
        'header.status',
        'status'
    ])
    ->whereNotNull('progress_date') // ✅ Cegah null
    ->whereBetween('progress_date', [$startDate, $endDate])
    ->orderBy('progress_date', 'asc')
    ->get();

    $filename = "Project_Report_{$startDate}_{$endDate}.csv";

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
        'Progress Date',    // ✅ Kolom ke-8
        'Progress (%)',
        'Memo',
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
                optional(optional($p->header)->priority)->priority_name ?? '-',
                optional(optional($p->header)->status)->status_name ?? '-',
                $p->developer_name ?? '-',
                optional($p->status)->status_name ?? '-',
                $p->progress_date ? $p->progress_date->format('Y-m-d') : '-', // ✅ Progress date
                $p->progress_percent ?? 0,
                $p->memo ?? '-',
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
    $startDate = $request->query('start_date');
    $endDate   = $request->query('end_date');

    if (!$startDate || !$endDate) {
        return response()->json([
            'error' => 'Tanggal mulai dan akhir wajib diisi'
        ], 400);
    }

    $projects = ProjectDetail::with([
        'header.requestor',
        'header.priority',
        'header.status',
        'status'
    ])
    ->whereNotNull('progress_date') // ✅ Cegah null
    ->whereBetween('progress_date', [$startDate, $endDate])
    ->orderBy('progress_date', 'asc')
    ->limit(50)
    ->get()
    ->map(function ($p) {
        return [
            'project_code'   => optional($p->header)->project_code ?? '-',
            'project_name'   => optional($p->header)->project_name ?? '-',
            'requestor'      => optional(optional($p->header)->requestor)->name ?? '-',
            'priority'       => optional(optional($p->header)->priority)->priority_name ?? '-',
            'project_status' => optional(optional($p->header)->status)->status_name ?? '-',
            'developer_name' => $p->developer_name ?? '-',
            'detail_status'  => optional($p->status)->status_name ?? '-',
            'progress_date'  => $p->progress_date ? $p->progress_date->format('Y-m-d') : '-', // ✅ FIX: hapus duplikat
            'progress'       => $p->progress_percent ?? 0,
            'memo'           => $p->memo ?? '-',
            'start_date'     => optional($p->header)->start_date ? $p->header->start_date->format('Y-m-d') : '-',
            'end_date'       => optional($p->header)->end_date ? $p->header->end_date->format('Y-m-d') : '-',
            'is_late'        => optional($p->header)->is_late ? 'Yes' : 'No',
            'notes'          => optional($p->header)->notes ?? '-',
        ];
    });

    return response()->json([
        'data' => $projects
    ]);
}
}
