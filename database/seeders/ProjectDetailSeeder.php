<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProjectDetailSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('project_detail')->insert([
            // Project 1
            [
                'project_header_id' => 1,
                'progress_date' => Carbon::now()->subDays(8),
                'status_id' => 2,
                'progress_percent' => 20,
                'memo' => 'Dokumentasi requirement selesai.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'project_header_id' => 1,
                'progress_date' => Carbon::now()->subDays(5),
                'status_id' => 2,
                'progress_percent' => 50,
                'memo' => 'Fitur utama sudah jalan.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'project_header_id' => 1,
                'progress_date' => Carbon::now()->subDays(2),
                'status_id' => 2,
                'progress_percent' => 70,
                'memo' => 'Menunggu user feedback.',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Project 2
            [
                'project_header_id' => 2,
                'progress_date' => Carbon::now()->subDays(13),
                'status_id' => 2,
                'progress_percent' => 20,
                'memo' => 'Draft dokumen persetujuan dibuat.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'project_header_id' => 2,
                'progress_date' => Carbon::now()->subDays(10),
                'status_id' => 2,
                'progress_percent' => 70,
                'memo' => 'Workflow otomatis selesai.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'project_header_id' => 2,
                'progress_date' => Carbon::now()->subDays(5),
                'status_id' => 3,
                'progress_percent' => 100,
                'memo' => 'Sudah diimplementasikan dan dites.',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Project 3
            [
                'project_header_id' => 3,
                'progress_date' => Carbon::now()->subDays(18),
                'status_id' => 2,
                'progress_percent' => 20,
                'memo' => 'Identifikasi data kinerja developer.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'project_header_id' => 3,
                'progress_date' => Carbon::now()->subDays(10),
                'status_id' => 2,
                'progress_percent' => 50,
                'memo' => 'Tampilan utama selesai.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'project_header_id' => 3,
                'progress_date' => Carbon::now()->subDays(4),
                'status_id' => 2,
                'progress_percent' => 60,
                'memo' => 'Data sumber berubah.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
