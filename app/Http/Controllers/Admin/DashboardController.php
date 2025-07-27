<?php

// Pastikan namespace controller Anda benar
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Peralatan;
use App\Models\User;
use App\Models\Transaksi; // Menggunakan model Transaksi
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        // --- PERBAIKAN: Logika Kalkulasi Statistik ---

        // Menghitung total unit dari semua jenis peralatan
        $totalUnitPeralatan = Peralatan::sum('stok_total');

        // Menghitung jumlah unit yang sedang dipinjam
        $totalDipinjam = Transaksi::where('tipe', 'peminjaman')->withSum('details', 'jumlah')->get()->sum('details_sum_jumlah');
        $totalDikembalikan = Transaksi::where('tipe', 'pengembalian')->withSum('details', 'jumlah')->get()->sum('details_sum_jumlah');
        $stokDipinjam = $totalDipinjam - $totalDikembalikan;

        // Menghitung stok yang tersedia
        $stokTersedia = $totalUnitPeralatan - $stokDipinjam;

        // Menghitung total pengguna
        $totalPengguna = User::count();

        // --- PERBAIKAN: Mengambil 5 riwayat transaksi terbaru ---
        $transaksiTerbaru = Transaksi::with(['user', 'storeman', 'details.peralatan'])
            ->latest('tanggal_transaksi')
            ->take(5)
            ->get();

        // Mengirim semua data baru ke view
        return view('admin.dashboard', compact(
            'totalUnitPeralatan',
            'stokTersedia',
            'stokDipinjam',
            'totalPengguna',
            'transaksiTerbaru'
        ));
    }
}