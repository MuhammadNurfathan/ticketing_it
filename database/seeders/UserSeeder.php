<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'Administrator',
                'department_id' => 1,
                'role_id' => 1,
                'email' => 'admin@example.com',
                'phone' => '081234567890',
                'job_position' => 'System Administrator',
                'password' => Hash::make('password'), // password default
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Manager User',
                'department_id' => 2,
                'role_id' => 2,
                'email' => 'manager@example.com',
                'phone' => '082233445566',
                'job_position' => 'Finance Manager',
                'password' => Hash::make('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Staff User',
                'department_id' => 3,
                'role_id' => 3,
                'email' => 'staff@example.com',
                'phone' => '083344556677',
                'job_position' => 'IT Support',
                'password' => Hash::make('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
