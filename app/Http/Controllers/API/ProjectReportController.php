<?php

namespace App\Http\Controllers\api;
use App\Http\Controllers\Controller;
use App\Models\ProjectHeader;
use Illuminate\Http\Request;

class ProjectReportController extends Controller
{
    public function ProjectQueue(){
        $ProjectQueue = ProjectHeader::with(['priority', 'requestor', 'developer', 'status'])->where('status_id', 1)->get()->all();
        return response()->json([
            'data' => [
                'ProjectQueue' => $ProjectQueue
            ],
        ]);
    }

        public function gantChart()
    {
        // Ambil semua project dengan kolom yang dibutuhkan
        $projects = ProjectHeader::select(
            'id',
            'project_name',
            'start_date',
            'end_date',
            'effective_end_date',
            'progress_percent'
        )->get();

        // Ubah data agar sesuai format yang dibutuhkan Gantt Chart
        $data = $projects->map(function ($p) {
            $start = $p->start_date;
            $end = $p->effective_end_date ?? $p->end_date;

            return [
                'id' => $p->id,
                'name' => $p->project_name,
                'start' => $start,
                'end' => $end,
                'progress' => intval($p->progress_percent),
            ];
        });

        return response()->json($data);
    }
}
