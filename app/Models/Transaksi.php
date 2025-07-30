<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne; // Tambahkan ini

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksis';

    protected $fillable = [
        'kode_transaksi',
        'tipe',
        'user_id',
        'storeman_id',
        'peminjaman_id',
        'tanggal_transaksi',
        'catatan',
    ];

    public function details(): HasMany
    {
        return $this->hasMany(TransaksiDetail::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function storeman(): BelongsTo
    {
        return $this->belongsTo(Storeman::class);
    }

    /**
     * PENAMBAHAN: Relasi untuk mengecek apakah sebuah peminjaman sudah memiliki transaksi pengembalian.
     * Satu peminjaman memiliki satu pengembalian.
     */
    public function pengembalian(): HasOne
    {
        return $this->hasOne(Transaksi::class, 'peminjaman_id');
    }

    /**
     * PENAMBAHAN: Relasi untuk melihat dari mana sebuah pengembalian berasal (opsional).
     * Satu pengembalian milik satu peminjaman.
     */
    public function peminjamanAsal(): BelongsTo
    {
        return $this->belongsTo(Transaksi::class, 'peminjaman_id');
    }
}
