<?php

// Pastikan namespace controller Anda benar
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Peralatan;
use App\Models\User;
use App\Models\Transaksi; // Menggunakan model Transaksi Anda
use Illuminate\View\View;
use Illuminate\Support\Facades\DB; // <-- TAMBAHKAN IMPORT INI
use Carbon\Carbon;                  // <-- TAMBAHKAN IMPORT INI

class DashboardController extends Controller
{
    public function index(): View
    {
        // --- LOGIKA ANDA UNTUK KARTU STATISTIK (SAYA PERTAHANKAN) ---
        $totalUnitPeralatan = Peralatan::sum('stok_total');

        $totalDipinjam = Transaksi::where('tipe', 'peminjaman')->withSum('details', 'jumlah')->get()->sum('details_sum_jumlah');
        $totalDikembalikan = Transaksi::where('tipe', 'pengembalian')->withSum('details', 'jumlah')->get()->sum('details_sum_jumlah');
        $stokDipinjam = $totalDipinjam - $totalDikembalikan;

        $stokTersedia = $totalUnitPeralatan - $stokDipinjam;

        $totalPengguna = User::count();

        $transaksiTerbaru = Transaksi::with(['user', 'storeman', 'details.peralatan'])
            ->latest('tanggal_transaksi')
            ->take(5)
            ->get();

        // --- LOGIKA BARU UNTUK DATA GRAFIK (SAYA TAMBAHKAN) ---
        $endDate = Carbon::now();
        $startDate = Carbon::now()->subDays(6);

        // Menggunakan model 'Transaksi' sesuai kode Anda
        $transaksiByDay = Transaksi::select(
                DB::raw('DATE(tanggal_transaksi) as tanggal'),
                DB::raw("SUM(CASE WHEN tipe = 'peminjaman' THEN 1 ELSE 0 END) as total_peminjaman"),
                DB::raw("SUM(CASE WHEN tipe = 'pengembalian' THEN 1 ELSE 0 END) as total_pengembalian")
            )
            ->whereBetween('tanggal_transaksi', [$startDate, $endDate])
            ->groupBy('tanggal')
            ->orderBy('tanggal', 'asc')
            ->get();

        // Format data untuk ApexCharts
        $chartLabels = [];
        $dataPeminjaman = [];
        $dataPengembalian = [];

        // Inisialisasi data untuk 7 hari agar grafik tetap lengkap
        for ($i = 0; $i < 7; $i++) {
            $date = $startDate->copy()->addDays($i);
            $chartLabels[] = $date->format('d M');
            $dataPeminjaman[$date->format('Y-m-d')] = 0;
            $dataPengembalian[$date->format('Y-m-d')] = 0;
        }

        foreach ($transaksiByDay as $data) {
            $dataPeminjaman[$data->tanggal] = $data->total_peminjaman;
            $dataPengembalian[$data->tanggal] = $data->total_pengembalian;
        }

        // --- MENGIRIM SEMUA DATA (STATISTIK + GRAFIK) KE VIEW ---
        return view('admin.dashboard', compact(
            'totalUnitPeralatan',
            'stokTersedia',
            'stokDipinjam',
            'totalPengguna',
            'transaksiTerbaru',
            'chartLabels',
            'dataPeminjaman',
            'dataPengembalian'
        ));
    }
}