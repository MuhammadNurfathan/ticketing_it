<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AssetsSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('assets')->insert([
            [
                'assets_code'   => 'AST-001',
                'assets_name'   => 'Laptop Lenovo ThinkPad X1',
                'image'         => 'thinkpadx1.jpg',
                'category'      => 'Laptop / PC',
                'status'        => 'Available',
                'model'         => 'X1 Carbon Gen 9',
                'check_in'      => 'Admin IT',
                'check_out'     => '-',
                'check_out_to'  => '-',
                'location'      => 'Ruang IT',
                'notes'         => 'Kondisi sangat baik',
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ],
            [
                'assets_code'   => 'AST-002',
                'assets_name'   => 'Printer Epson L3250',
                'image'         => 'epsonl3250.jpg',
                'category'      => 'Printer / Scanner',
                'status'        => 'Checked Out',
                'model'         => 'L3250',
                'check_in'      => 'Admin GA',
                'check_out'     => 'Oktiani',
                'check_out_to'  => 'Divisi Keuangan',
                'location'      => 'Ruang Keuangan',
                'notes'         => 'Butuh perawatan tinta',
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ],
            [
                'assets_code'   => 'AST-003',
                'assets_name'   => 'Proyektor BenQ MX550',
                'image'         => 'benqmx550.jpg',
                'category'      => 'Monitor / Proyektor',
                'status'        => 'Available',
                'model'         => 'MX550',
                'check_in'      => 'Admin Fasilitas',
                'check_out'     => '-',
                'check_out_to'  => '-',
                'location'      => 'Ruang Meeting',
                'notes'         => 'Tersedia untuk peminjaman',
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ],
        ]);
    }
}
