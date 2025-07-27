<?php

namespace App\Http\Controllers;

use App\Models\Peralatan;
use App\Models\Storeman;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class PeminjamanController extends Controller
{

    /**
     * Menampilkan daftar peminjaman yang telah diajukan oleh user.
     */ public function index(): View
    {
        // PERBAIKI: Ganti nama variabel dari $peminjaman menjadi $riwayatTransaksi
        $riwayatTransaksi = Transaksi::where('user_id', Auth::id()) // Query juga disesuaikan untuk mengambil semua tipe transaksi user
            ->with(['storeman', 'details.peralatan'])
            ->latest('tanggal_transaksi')
            ->paginate(10);

        // Kirim variabel dengan nama yang sudah benar
        return view('user.peminjaman.index', compact('riwayatTransaksi'));
    }
    /**
     * Menampilkan form sederhana untuk peminjaman.
     */
    public function create(): View
    {
        $peralatanTersedia = Peralatan::orderBy('nama')->get()->map(function ($item) {
            $item->stok_tersedia = $item->stokTersedia();
            return $item;
        })->filter(function ($item) {
            return $item->stok_tersedia > 0;
        });

        $daftarStoreman = Storeman::orderBy('nama')->get();

        // Mengarahkan ke view yang benar dan sederhana
        return view('user.peminjaman.create', [
            'daftarPeralatan' => $peralatanTersedia,
            'daftarStoreman' => $daftarStoreman,
        ]);
    }

    /**
     * Menyimpan transaksi PEMINJAMAN dari user.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'storeman_id' => 'required|exists:storemen,id',
            'items' => 'required|array|min:1',
            'items.*.peralatan_id' => 'required|exists:peralatan,id',
            'items.*.jumlah' => 'required|integer|min:1',
            'catatan' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            foreach ($request->items as $item) {
                $peralatan = Peralatan::find($item['peralatan_id']);
                if ($item['jumlah'] > $peralatan->stokTersedia()) {
                    DB::rollBack();
                    return back()->withInput()->with('error', "Stok untuk '{$peralatan->nama}' tidak mencukupi. Sisa: {$peralatan->stokTersedia()}");
                }
            }

            $transaksi = Transaksi::create([
                'kode_transaksi' => 'PINJAM-' . time(),
                'tipe' => 'peminjaman',
                'user_id' => Auth::id(), // PENTING: User ID diambil otomatis dari yang login
                'storeman_id' => $request->storeman_id,
                'tanggal_transaksi' => now(),
                'catatan' => $request->catatan,
            ]);

            foreach ($request->items as $item) {
                $transaksi->details()->create([
                    'peralatan_id' => $item['peralatan_id'],
                    'jumlah' => $item['jumlah'],
                ]);
            }

            DB::commit();
            return redirect()->route('peminjaman.index')->with('success', 'Peminjaman berhasil diajukan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat memproses peminjaman.');
        }
    }

    // ... method index() dan kembalikan() ...
}
