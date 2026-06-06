<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::insert([
            [
                'name'          => 'Nurfathan',
                'username'      => 'nurfathan',
                'department_id' => 1,
                'role_id'       => 1,
                'email'         => 'muhammadnurfathan07@gmail.com',
                'password'      => Hash::make('password'),
                'phone'         => '+6288298955023',
                'job_position'  => 'IT Support',
                'status'        => 'Aktif',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],

            [
                'name'          => 'Azi',
                'username'      => 'azi',
                'department_id' => 1,
                'role_id'       => 2,
                'email'         => 'azi@gmail.com',
                'password'      => Hash::make('password'),
                'phone'         => '+62883533232',
                'job_position'  => 'Manager IT',
                'status'        => 'Aktif',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
        ]);
    }
}