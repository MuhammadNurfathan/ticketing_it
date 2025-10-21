<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProjectHeaderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('project_header')->insert([
            [
                'id' => 1,
                'project_code' => 'PRJ001',
                'project_name' => 'Sistem Monitoring Pending',
                'request_date' => Carbon::now()->subDays(10),
                'requestor_id' => 1,
                'dev_id' => 2,
                'status_id' => 2, // in progress
                'priority_id' => 1,
                'progress_percent' => 70,
                'description' => 'Membangun sistem untuk mencatat dan memonitor pending project.',
                'notes' => 'Akan dilanjutkan setelah feedback user.',
                'start_date' => Carbon::now()->subDays(9),
                'end_date' => Carbon::now()->addDays(3),
                'actual_start_date' => Carbon::now()->subDays(9),
                'actual_end_date' => null,
                'total_pending_days' => 2,
                'effective_end_date' => Carbon::now()->addDays(5),
                'is_late' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'project_code' => 'PRJ002',
                'project_name' => 'Sistem Approval Internal',
                'request_date' => Carbon::now()->subDays(15),
                'requestor_id' => 1,
                'dev_id' => 2,
                'status_id' => 4, // done
                'priority_id' => 2,
                'progress_percent' => 100,
                'description' => 'Membuat sistem persetujuan otomatis untuk dokumen internal.',
                'notes' => 'Sudah diimplementasikan.',
                'start_date' => Carbon::now()->subDays(14),
                'end_date' => Carbon::now()->subDays(5),
                'actual_start_date' => Carbon::now()->subDays(14),
                'actual_end_date' => Carbon::now()->subDays(5),
                'total_pending_days' => 0,
                'effective_end_date' => Carbon::now()->subDays(5),
                'is_late' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'project_code' => 'PRJ003',
                'project_name' => 'Dashboard Kinerja Developer',
                'request_date' => Carbon::now()->subDays(20),
                'requestor_id' => 1,
                'dev_id' => 2,
                'status_id' => 2, // in progress
                'priority_id' => 3,
                'progress_percent' => 60,
                'description' => 'Dashboard untuk melihat performa developer per project.',
                'notes' => 'Sering tertunda karena revisi data.',
                'start_date' => Carbon::now()->subDays(19),
                'end_date' => Carbon::now()->subDays(2),
                'actual_start_date' => Carbon::now()->subDays(19),
                'actual_end_date' => null,
                'total_pending_days' => 5,
                'effective_end_date' => Carbon::now()->addDays(3),
                'is_late' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
