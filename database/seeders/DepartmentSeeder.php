<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('departments')->insert([
            ['location_id' => 1, 'department_name' => 'Human Resources', 'created_at' => now(), 'updated_at' => now()],
            ['location_id' => 1, 'department_name' => 'Finance', 'created_at' => now(), 'updated_at' => now()],
            ['location_id' => 2, 'department_name' => 'IT Support', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
