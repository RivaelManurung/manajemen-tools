<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Biarkan kode mencari ID yang benar
        $adminJobId = DB::table('job_titles')->where('name', 'Administrator')->value('id');
        $mechanicJobId = DB::table('job_titles')->where('name', 'Mechanic')->value('id');

        $dept1 = DB::table('departments')->where('name', 'M361')->value('id');
        $dept2 = DB::table('departments')->where('name', 'M361A')->value('id');

        DB::table('users')->insert([
            [
                'fullname'       => 'Admin Utama',
                'username'       => 'admin',
                'email'          => 'admin@example.com',
                'password'       => Hash::make('password'),
                'peran'          => 'admin',
                'job_title_id'   => $adminJobId,    
                'department_id'  => $dept1,         
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'fullname'       => 'Agus Mekanik',
                'username'       => 'mekanik',
                'email'          => 'mekanik@example.com',
                'password'       => Hash::make('password'),
                'peran'          => 'user',
                'job_title_id'   => $mechanicJobId, 
                'department_id'  => $dept2,        
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
        ]);
    }
}
