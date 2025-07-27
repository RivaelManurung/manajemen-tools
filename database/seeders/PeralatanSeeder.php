<?php

namespace Database\Seeders;

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
                'stok_total' => 15, 
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Obeng Minus Kecil',
                'kode' => 'OBG-002',
                'stok_total' => 20,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Kunci Inggris 10mm',
                'kode' => 'KCI-001',
                'stok_total' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Palu Konde',
                'kode' => 'PLU-001',
                'stok_total' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Tang Buaya',
                'kode' => 'TNG-001',
                'stok_total' => 8,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
