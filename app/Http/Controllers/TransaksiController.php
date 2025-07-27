<?php

namespace App\Http\Controllers;

use App\Models\Peralatan;
use App\Models\Storeman;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class TransaksiController extends Controller
{
    /**
     * Menampilkan form utama untuk membuat transaksi (peminjaman & pengembalian).
     */
    public function create()
    {
        // Data untuk tab Peminjaman
        $peralatanTersedia = Peralatan::orderBy('nama')->get()->map(function ($item) {
            $item->stok_tersedia = $item->stokTersedia();
            return $item;
        })->filter(function ($item) {
            return $item->stok_tersedia > 0;
        });

        // Data untuk tab Pengembalian & dropdown peminjam
        $semuaPeralatan = Peralatan::orderBy('nama')->get();
        $semuaUser = User::where('peran', 'user')->orderBy('fullname')->get();
        $daftarStoreman = Storeman::orderBy('nama')->get();

        return view('admin.transaksi.create', [
            'peralatanTersedia' => $peralatanTersedia,
            'semuaPeralatan' => $semuaPeralatan,
            'semuaUser' => $semuaUser,
            'daftarStoreman' => $daftarStoreman,
        ]);
    }

    /**
     * Menyimpan transaksi baru (baik peminjaman maupun pengembalian).
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'tipe' => 'required|in:peminjaman,pengembalian',
            'user_id' => 'required|exists:users,id',
            'storeman_id' => 'required|exists:storemen,id',
            'items' => 'required|array|min:1',
            'items.*.peralatan_id' => 'required|exists:peralatan,id',
            'items.*.jumlah' => 'required|integer|min:1',
            'catatan' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // Validasi stok hanya untuk transaksi PEMINJAMAN
            if ($request->tipe == 'peminjaman') {
                foreach ($request->items as $item) {
                    $peralatan = Peralatan::find($item['peralatan_id']);
                    if ($item['jumlah'] > $peralatan->stokTersedia()) {
                        DB::rollBack();
                        return back()->withInput()->with('error', "Stok untuk '{$peralatan->nama}' tidak mencukupi. Sisa: {$peralatan->stokTersedia()}");
                    }
                }
            }

            $transaksi = Transaksi::create([
                'kode_transaksi' => strtoupper($request->tipe) . '-' . time(),
                'tipe' => $request->tipe,
                'user_id' => $request->user_id,
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
            return redirect()->route('transaksi.index')->with('success', 'Transaksi ' . $request->tipe . ' berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // ... method index() untuk riwayat ...
}
