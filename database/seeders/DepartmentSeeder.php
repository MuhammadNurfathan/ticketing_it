<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Department;
class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Department::insert([
            [
                'location_id' => 1,
                'name' => 'Teknologi Informasi',
            ],
            [
                'location_id' => 1,
                'name' => 'Finance',
            ],
            [
                'location_id' => 1,
                'name' => 'Product',
            ],
            [
                'location_id' => 1,
                'name' => 'Legal',
            ],
            [
                'location_id' => 1,
                'name' => 'HCGA',
            ],
            [
                'location_id' => 2,
                'name' => 'Marketing',
            ],
            [
                'location_id' => 3,
                'name' => 'Store',
            ],
        ]);
    }
}
