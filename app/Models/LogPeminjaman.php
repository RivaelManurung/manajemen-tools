<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LogPeminjaman extends Model
{
    use HasFactory;

    protected $table = 'log_peminjaman';

    protected $fillable = [
        'peralatan_id',
        'mekanik_id',
        'storeman_id',
        'waktu_pinjam',
        'waktu_kembali',
        'kondisi',
    ];

    // Mengubah kolom waktu menjadi objek Carbon (untuk mempermudah format)
    protected $casts = [
        'waktu_pinjam' => 'datetime',
        'waktu_kembali' => 'datetime',
    ];

    /**
     * Mendefinisikan relasi: Setiap log PASTI memiliki satu peralatan.
     */
    public function peralatan(): BelongsTo
    {
        return $this->belongsTo(Peralatan::class, 'peralatan_id');
    }

    /**
     * Mendefinisikan relasi: Setiap log PASTI memiliki satu mekanik.
     */
    public function mekanik(): BelongsTo
    {
        return $this->belongsTo(User::class, 'mekanik_id');
    }

    /**
     * Mendefinisikan relasi: Setiap log PASTI memiliki satu storeman.
     */
    public function storeman(): BelongsTo
    {
        return $this->belongsTo(User::class, 'storeman_id');
    }
}