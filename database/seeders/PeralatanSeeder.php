<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PeralatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('peralatan')->insert([
            [
                'nama' => 'Obeng Plus Besar',
                'kode' => 'OBG-001',
                'status' => 'tersedia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Obeng Minus Kecil',
                'kode' => 'OBG-002',
                'status' => 'tersedia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Kunci Inggris 10mm',
                'kode' => 'KCI-001',
                'status' => 'tersedia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Palu Konde',
                'kode' => 'PLU-001',
                'status' => 'tersedia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Tang Buaya',
                'kode' => 'TNG-001',
                'status' => 'tersedia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}