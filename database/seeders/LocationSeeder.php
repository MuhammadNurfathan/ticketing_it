<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('locations')->insert([
            ['location_name' => 'Head Office', 'created_at' => now(), 'updated_at' => now()],
            ['location_name' => 'Gudang Rawabokor', 'created_at' => now(), 'updated_at' => now()],
            ['location_name' => 'Gudang Jatake 1', 'created_at' => now(), 'updated_at' => now()],
            ['location_name' => 'Gudang Jatake 2', 'created_at' => now(), 'updated_at' => now()],
            ['location_name' => 'Gudang KS-Tubun', 'created_at' => now(), 'updated_at' => now()],
            ['location_name' => 'pasar lama', 'created_at' => now(), 'updated_at' => now()],
            ['location_name' => 'Poris', 'created_at' => now(), 'updated_at' => now()],
            ['location_name' => 'Kalideres', 'created_at' => now(), 'updated_at' => now()],
            ['location_name' => 'banjar Wijaya', 'created_at' => now(), 'updated_at' => now()],
            ['location_name' => 'Talaga Bestari', 'created_at' => now(), 'updated_at' => now()],
            ['location_name' => 'Karawaci Pandan', 'created_at' => now(), 'updated_at' => now()],
            ['location_name' => 'Karawaci Borobudur', 'created_at' => now(), 'updated_at' => now()],
            ['location_name' => 'Ciledug', 'created_at' => now(), 'updated_at' => now()],
            ['location_name' => 'Kelapa Dua', 'created_at' => now(), 'updated_at' => now()],
            ['location_name' => 'Sepatan', 'created_at' => now(), 'updated_at' => now()],
            ['location_name' => 'Pamulang', 'created_at' => now(), 'updated_at' => now()],
            ['location_name' => 'Ciledug Indah', 'created_at' => now(), 'updated_at' => now()],
            ['location_name' => 'Dasana Indah', 'created_at' => now(), 'updated_at' => now()],
            ['location_name' => 'Gudang Garuda 21', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
