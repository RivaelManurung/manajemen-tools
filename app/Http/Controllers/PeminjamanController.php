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
     * Menampilkan riwayat transaksi PRIBADI milik user.
     */
    public function index(): View
    {
        $riwayatTransaksi = Transaksi::where('user_id', Auth::id())
            ->with(['storeman', 'details.peralatan', 'pengembalian'])
            ->latest('tanggal_transaksi')
            ->paginate(10);

        return view('user.peminjaman.index', compact('riwayatTransaksi'));
    }

    /**
     * Menampilkan form untuk peminjaman & pengembalian.
     */
    public function create(): View
    {
        $peralatanTersedia = Peralatan::orderBy('nama')->get()->map(function ($item) {
            $item->stok_tersedia = $item->stokTersedia();
            return $item;
        })->filter(function ($item) {
            return $item->stok_tersedia > 0;
        });

        $peralatanDipinjam = Auth::user()->peralatanYangSedangDipinjam();
        $daftarStoreman = Storeman::orderBy('nama')->get();

        return view('user.peminjaman.create', [
            'peralatanTersedia' => $peralatanTersedia,
            'peralatanDipinjam' => $peralatanDipinjam,
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
            'catatan' => 'nullable|string|max:255',
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
                'user_id' => Auth::id(),
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
            return redirect()->route('user.peminjaman.index')->with('success', 'Peminjaman berhasil diajukan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Membuat transaksi PENGEMBALIAN.
     */
    public function kembalikan(Request $request, Transaksi $peminjaman): RedirectResponse
    {
        $request->validate(['storeman_id' => 'required|exists:storemen,id']);

        if ($peminjaman->user_id !== Auth::id() || $peminjaman->tipe !== 'peminjaman' || $peminjaman->pengembalian) {
            return redirect()->route('peminjaman.index')->with('error', 'Transaksi tidak valid untuk dikembalikan.');
        }

        DB::beginTransaction();
        try {
            $pengembalian = Transaksi::create([
                'kode_transaksi'      => 'KEMBALI-' . time(),
                'tipe'                => 'pengembalian',
                'user_id'             => Auth::id(),
                'storeman_id'         => $request->storeman_id,
                'tanggal_transaksi'   => now(),
                'peminjaman_id'       => $peminjaman->id,
            ]);

            foreach ($peminjaman->details as $detail) {
                $pengembalian->details()->create([
                    'peralatan_id' => $detail->peralatan_id,
                    'jumlah' => $detail->jumlah,
                    'kondisi' => 'baik',
                ]);
            }

            DB::commit();
            return redirect()->route('user.peminjaman.index')->with('success', 'Pengembalian berhasil dicatat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
