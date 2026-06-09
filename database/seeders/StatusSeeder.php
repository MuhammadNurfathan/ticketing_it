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
                'type' => 'waiting',
                'context' => 'ticket',
            ],
            [
                'name' => 'In Progress',
                'type' => 'in_progress',
                'context' => 'ticket',
            ],
            [
                'name' => 'Done',
                'type' => 'done',
                'context' => 'ticket',
            ],
            [
                'name' => 'Void',
                'type' => 'void',
                'context' => 'ticket',
            ],
            [
                'name' => 'Feedback',
                'type' => 'feedback',
                'context' => 'ticket',
            ],
            [
                'name' => 'Waiting',
                'type' => 'waiting',
                'context' => 'project',
            ],
            [
                'name' => 'In Progress',
                'type' => 'in_progress',
                'context' => 'project',
            ],
            [
                'name' => 'Resolved',
                'type' => 'resolved',
                'context' => 'project',
            ],
            [
                'name' => 'Pending',
                'type' => 'pending',
                'context' => 'project',
            ],
            [
                'name' => 'Void',
                'type' => 'void',
                'context' => 'project',
            ],
        ]);
    }
}
