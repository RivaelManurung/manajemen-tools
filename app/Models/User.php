<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'fullname', // <-- DIUBAH DARI 'name'
        'username',
        'email',
        'password',
        'job_title_id',
        'department_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    // app/Models/User.php

    // Relasi ke JobTitle
    public function jobTitle()
    {
        return $this->belongsTo(JobTitle::class);
    }

    // Relasi ke Department
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
    public function peralatanYangSedangDipinjam()
    {
        // Mengambil total peminjaman per item
        $peminjaman = DB::table('transaksis')
            ->join('transaksi_details', 'transaksis.id', '=', 'transaksi_details.transaksi_id')
            ->where('transaksis.user_id', $this->id)
            ->where('transaksis.tipe', 'peminjaman')
            ->groupBy('transaksi_details.peralatan_id')
            ->select('peralatan_id', DB::raw('SUM(jumlah) as total_dipinjam'));

        // Mengambil total pengembalian per item
        $pengembalian = DB::table('transaksis')
            ->join('transaksi_details', 'transaksis.id', '=', 'transaksi_details.transaksi_id')
            ->where('transaksis.user_id', $this->id)
            ->where('transaksis.tipe', 'pengembalian')
            ->groupBy('transaksi_details.peralatan_id')
            ->select('peralatan_id', DB::raw('SUM(jumlah) as total_dikembalikan'));

        // Menggabungkan keduanya untuk mendapatkan sisa pinjaman
        return DB::table('peralatan')
            ->leftJoinSub($peminjaman, 'peminjaman', function ($join) {
                $join->on('peralatan.id', '=', 'peminjaman.peralatan_id');
            })
            ->leftJoinSub($pengembalian, 'pengembalian', function ($join) {
                $join->on('peralatan.id', '=', 'pengembalian.peralatan_id');
            })
            ->select(
                'peralatan.id as peralatan_id',
                'peralatan.nama as nama_peralatan',
                DB::raw('IFNULL(peminjaman.total_dipinjam, 0) - IFNULL(pengembalian.total_dikembalikan, 0) as jumlah_dipinjam')
            )
            ->where(DB::raw('IFNULL(peminjaman.total_dipinjam, 0) - IFNULL(pengembalian.total_dikembalikan, 0)'), '>', 0)
            ->get();
    }
}
