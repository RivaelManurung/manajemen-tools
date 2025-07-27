<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransaksiDetail extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terhubung dengan model.
     *
     * @var string
     */
    protected $table = 'transaksi_details';

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'transaksi_id',
        'peralatan_id',
        'jumlah',
    ];

    /**
     * Mendefinisikan relasi "milik" ke Transaksi.
     * Detail ini adalah bagian dari satu master transaksi.
     */
    public function transaksi(): BelongsTo
    {
        return $this->belongsTo(Transaksi::class);
    }

    /**
     * Mendefinisikan relasi "milik" ke Peralatan.
     * Detail ini merujuk pada satu jenis peralatan.
     */
    public function peralatan(): BelongsTo
    {
        return $this->belongsTo(Peralatan::class);
    }
}
