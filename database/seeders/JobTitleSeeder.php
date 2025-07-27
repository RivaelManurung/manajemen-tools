<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JobTitleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('job_titles')->insert([
            // Data sesuai dengan daftar yang Anda berikan
            ['name' => 'Administrator', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Supervisor', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Foreman', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Mechanic', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Tyre', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Service', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Welder', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Electrical', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
