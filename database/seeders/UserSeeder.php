<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            // 1. Akun Admin
            [
                'name' => 'Admin Utama',
                'username' => 'admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'), // password = password
                'peran' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // 2. Akun Storeman
            [
                'name' => 'Budi Storeman',
                'username' => 'storeman',
                'email' => 'storeman@example.com',
                'password' => Hash::make('password'),
                'peran' => 'storeman',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // 3. Akun Mekanik
            [
                'name' => 'Agus Mekanik',
                'username' => 'mekanik',
                'email' => 'mekanik@example.com',
                'password' => Hash::make('password'),
                'peran' => 'mekanik',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}