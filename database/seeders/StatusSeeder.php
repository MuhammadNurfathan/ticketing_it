<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('status')->insert([
            [
                'id'=>1,'status_name' => 'Pending'
            ],
            [
                'id'=>2,'status_name' => 'In Progress'
            ],
            [
                'id'=>3,'status_name' => 'Done'
            ]
        ]);
    }
}
