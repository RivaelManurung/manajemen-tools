<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('departments')->insert([
            ['name' => 'M361', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'M361A', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Subcon MMS', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Labour MMS', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'IT Department', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Warehouse', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
