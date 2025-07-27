<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transaksi extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terhubung dengan model.
     *
     * @var string
     */
    protected $table = 'transaksis';

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'kode_transaksi',
        'tipe',
        'user_id',
        'tanggal_transaksi',
        'catatan',
    ];

    /**
     * Mendefinisikan relasi "satu ke banyak" ke TransaksiDetail.
     * Satu transaksi memiliki banyak detail barang.
     */
    public function details(): HasMany
    {
        return $this->hasMany(TransaksiDetail::class);
    }

    /**
     * Mendefinisikan relasi "milik" ke User.
     * Satu transaksi dilakukan oleh satu user.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    /**
     * Mendefinisikan relasi "milik" ke Storeman.
     * Satu transaksi dikelola oleh satu storeman.
     */
    public function storeman(): BelongsTo
    {   
        return $this->belongsTo(Storeman::class);
    }
}
