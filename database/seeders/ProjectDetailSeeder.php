<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProjectDetailSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil semua data project header
        $projects = DB::table('project_header')->select('id')->get();

        foreach ($projects as $project) {
            $baseDate = Carbon::now()->subDays(rand(20, 40));
            $startPercent = 50;
            $endPercent = 100;

            for ($p = $startPercent; $p <= $endPercent; $p += 5) {
                // logika status_id berdasar progress
                if ($p < 50) {
                    $statusId = 1; // Waiting
                } elseif ($p < 100) {
                    $statusId = 2; // In Progress
                } else {
                    $statusId = 3; // Done
                }

                DB::table('project_detail')->insert([
                    'project_header_id' => $project->id,
                    'progress_date' => $baseDate->copy()->addDays(($p - $startPercent) / 5),
                    'memo' => "Progress project mencapai {$p}%",
                    'status_id' => $statusId, // ✅ kolom sudah disesuaikan
                    'progress_percent' => $p,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
