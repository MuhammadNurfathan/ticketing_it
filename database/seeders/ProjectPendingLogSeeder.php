<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProjectPendingLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('project_pending_log')->insert([
            // Pending Project 1
            [
                'project_header_id' => 1,
                'pending_start' => Carbon::now()->subDays(6),
                'pending_end' => Carbon::now()->subDays(5),
                'pending_duration' => 1,
                'reason' => 'Menunggu klarifikasi kebutuhan.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'project_header_id' => 1,
                'pending_start' => Carbon::now()->subDays(3),
                'pending_end' => Carbon::now()->subDays(2),
                'pending_duration' => 1,
                'reason' => 'Server dev down.',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Project 3
            [
                'project_header_id' => 3,
                'pending_start' => Carbon::now()->subDays(15),
                'pending_end' => Carbon::now()->subDays(14),
                'pending_duration' => 1,
                'reason' => 'Menunggu akses database HR.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'project_header_id' => 3,
                'pending_start' => Carbon::now()->subDays(8),
                'pending_end' => Carbon::now()->subDays(5),
                'pending_duration' => 3,
                'reason' => 'Data kinerja tidak sinkron.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'project_header_id' => 3,
                'pending_start' => Carbon::now()->subDays(2),
                'pending_end' => null,
                'pending_duration' => 1,
                'reason' => 'Menunggu revisi dari tim QA.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
