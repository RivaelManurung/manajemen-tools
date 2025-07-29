<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StoremanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('storemen')->insert([
            [
                'nama' => 'Agus Storeman',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Siti Storeman',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}