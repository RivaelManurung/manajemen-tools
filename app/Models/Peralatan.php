<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peralatan extends Model
{
    use HasFactory;

    // Menentukan nama tabel secara eksplisit
    protected $table = 'peralatan';

    // Kolom yang dapat diisi
    protected $fillable = ['nama', 'kode', 'stok_total'];

    /**
     * Method helper untuk menghitung stok yang tersedia saat ini.
     * Ini membuat kode di controller lebih bersih dan bisa digunakan di banyak tempat.
     * * @return int
     */
    public function stokTersedia(): int
    {
        // Menghitung total barang yang dipinjam
        $totalDipinjam = TransaksiDetail::where('peralatan_id', $this->id)
            ->whereHas('transaksi', function ($query) {
                $query->where('tipe', 'peminjaman');
            })->sum('jumlah');

        // Menghitung total barang yang dikembalikan
        $totalDikembalikan = TransaksiDetail::where('peralatan_id', $this->id)
            ->whereHas('transaksi', function ($query) {
                $query->where('tipe', 'pengembalian');
            })->sum('jumlah');

        // Rumus stok tersedia
        return $this->stok_total - ($totalDipinjam - $totalDikembalikan);
    }
}
