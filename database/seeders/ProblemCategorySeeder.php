<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class ProblemCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('problem_categories')->insert([
            ['problem_category_name' => 'SoftWare', 'created_at' => now(), 'updated_at' => now()],
            ['problem_category_name' => 'HardWare', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
