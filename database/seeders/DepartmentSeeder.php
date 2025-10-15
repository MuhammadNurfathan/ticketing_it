<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('departments')->insert([
            ['location_id' => 1, 'department_name' => 'HCGA', 'created_at' => now(), 'updated_at' => now()],
            ['location_id' => 1, 'department_name' => 'Finance', 'created_at' => now(), 'updated_at' => now()],
            ['location_id' => 1, 'department_name' => 'IT Support', 'created_at' => now(), 'updated_at' => now()],
            ['location_id' => 1, 'department_name' => 'Legal', 'created_at' => now(), 'updated_at' => now()],
            ['location_id' => 1, 'department_name' => 'Product', 'created_at' => now(), 'updated_at' => now()],
            ['location_id' => 1, 'department_name' => 'Marketing', 'created_at' => now(), 'updated_at' => now()],
            ['location_id' => 1, 'department_name' => 'Board Of Director', 'created_at' => now(), 'updated_at' => now()],
            ['location_id' => 1, 'department_name' => 'Sales B2B', 'created_at' => now(), 'updated_at' => now()],
            ['location_id' => 2, 'department_name' => 'Sales GT', 'created_at' => now(), 'updated_at' => now()],
            ['location_id' => 5, 'department_name' => 'store', 'created_at' => now(), 'updated_at' => now()],
            ['location_id' => 6, 'department_name' => 'Store', 'created_at' => now(), 'updated_at' => now()],
            ['location_id' => 7, 'department_name' => 'Store', 'created_at' => now(), 'updated_at' => now()],
            ['location_id' => 8, 'department_name' => 'Store', 'created_at' => now(), 'updated_at' => now()],
            ['location_id' => 9, 'department_name' => 'Store', 'created_at' => now(), 'updated_at' => now()],
            ['location_id' => 10, 'department_name' => 'Store', 'created_at' => now(), 'updated_at' => now()],
            ['location_id' => 11, 'department_name' => 'Store', 'created_at' => now(), 'updated_at' => now()],
            ['location_id' => 12, 'department_name' => 'Store', 'created_at' => now(), 'updated_at' => now()],
            ['location_id' => 13, 'department_name' => 'Store', 'created_at' => now(), 'updated_at' => now()],
            ['location_id' => 14, 'department_name' => 'Store', 'created_at' => now(), 'updated_at' => now()],
            ['location_id' => 15,'department_name' => 'Store', 'created_at' => now(), 'updated_at' => now()],
            ['location_id' => 16, 'department_name' => 'Store', 'created_at' => now(), 'updated_at' => now()],
            ['location_id' => 17, 'department_name' => 'Store', 'created_at' => now(), 'updated_at' => now()],
            ['location_id' => 18, 'department_name' => 'Store', 'created_at' => now(), 'updated_at' => now()],
            ['location_id' => 19, 'department_name' => 'Suppl Chain', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
