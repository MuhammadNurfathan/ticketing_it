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
                'name' => 'Yulliemty',
                'department_id' => 3,
                'role_id' => 2,
                'email' => 'yuli@piagam.id',
                'password' => Hash::make('yuli123'), // password default
                'phone' => '081234567890',
                'job_position' => 'System Administrator',
                'status' => 'Active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Azi Fauzi',
                'department_id' => 3,
                'role_id' => 1,
                'email' => 'azi@piagam.id',
                'password' => Hash::make('azi123'),
                'phone' => '082233445566',
                'job_position' => 'Finance Manager',
                'status' => 'Active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Apriyanto',
                'department_id' => 3,
                'role_id' => 1,
                'email' => 'apri@piagam.id',
                'password' => Hash::make('apri123'),
                'phone' => '083344556677',
                'job_position' => 'IT Support',
                'status' => 'Active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Bayu',
                'department_id' => 3,
                'role_id' => 1,
                'email' => 'bayu@piagam.id',
                'password' => Hash::make('bayu123'),
                'phone' => '083344556677',
                'job_position' => 'IT Support',
                'status' => 'Active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Muhammad Nurfathan Athaillah Humaedi',
                'department_id' => 3,
                'role_id' => 1,
                'email' => 'fatan@piagam.id',
                'password' => Hash::make('fatan201004'),
                'phone' => '083344556677',
                'job_position' => 'IT Support',
                'status' => 'Active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Siti Raudhatul Jannah',
                'department_id' => 1,
                'role_id' => 3,
                'email' => 'jannah@piagam.id',
                'password' => Hash::make('jannah123'),
                'phone' => '083344556677',
                'job_position' => 'Recruitment',
                'status' => 'Active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Cahyaningtyas',
                'department_id' => 1,
                'role_id' => 3,
                'email' => 'cahya@piagam.id',
                'password' => Hash::make('cahya123'),
                'phone' => '083344556677',
                'job_position' => 'Recruitment',
                'status' => 'Active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'M Fadli VDelon',
                'department_id' => 1,
                'role_id' => 3,
                'email' => 'Vdelon@piagam.id',
                'password' => Hash::make('vdelon123'),
                'phone' => '083344556677',
                'job_position' => 'General Affair',
                'status' => 'Active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
        ]);
    }
}
