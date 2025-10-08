<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('locations')->insert([
            ['location_name' => 'Jakarta', 'created_at' => now(), 'updated_at' => now()],
            ['location_name' => 'Bandung', 'created_at' => now(), 'updated_at' => now()],
            ['location_name' => 'Surabaya', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
