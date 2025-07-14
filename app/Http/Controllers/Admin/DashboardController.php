<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Peralatan;
use App\Models\User;
use App\Models\LogPeminjaman;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Menghitung statistik untuk card
        $totalPeralatan = Peralatan::count();
        $peralatanTersedia = Peralatan::where('status', 'tersedia')->count();
        $peralatanDipinjam = Peralatan::where('status', 'dipinjam')->count();
        $totalPengguna = User::count();

        // Mengambil 5 riwayat peminjaman terbaru
        $logTerbaru = LogPeminjaman::with(['peralatan', 'mekanik', 'storeman'])
            ->latest('waktu_pinjam')
            ->take(5)
            ->get();

        // Mengirim semua data ke view
        return view('admin.dashboard', compact(
            'totalPeralatan',
            'peralatanTersedia',
            'peralatanDipinjam',
            'totalPengguna',
            'logTerbaru'
        ));
    }
}
