<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Status;
class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Status::insert([
            [
                'name' => 'Waiting',
                'context' => 'ticket',
            ],
            [
                'name' => 'In Progress',
                'context' => 'ticket',
            ],
            [
                'name' => 'Done',
                'context' => 'ticket',
            ],
            [
                'name' => 'Void',
                'context' => 'ticket',
            ],
            [
                'name' => 'Feedback',
                'context' => 'ticket',
            ],
            [
                'name' => 'Waiting',
                'context' => 'project',
            ],
            [
                'name' => 'In Progress',
                'context' => 'project',
            ],
            [
                'name' => 'Resolved',
                'context' => 'project',
            ],
            [
                'name' => 'Pending',
                'context' => 'project',
            ],
        ]);
    }
}
