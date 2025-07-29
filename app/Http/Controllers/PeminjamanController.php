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
     * METHOD INDEX: Menampilkan HANYA riwayat transaksi user.
     */
    public function index(): View
    {
        $riwayatTransaksi = Transaksi::where('user_id', Auth::id())
            ->with(['storeman', 'details.peralatan'])
            ->latest('tanggal_transaksi')
            ->paginate(10);

        // Mengarahkan ke view index, BUKAN create
        return view('user.peminjaman.index', compact('riwayatTransaksi'));
    }

    /**
     * METHOD CREATE: Menyiapkan data dan menampilkan FORM peminjaman/pengembalian.
     */
    public function create(): View
    {
        // Data untuk tab Peminjaman (alat yang stoknya ada)
        $peralatanTersedia = Peralatan::orderBy('nama')->get()->map(function ($item) {
            $item->stok_tersedia = $item->stokTersedia();
            return $item;
        })->filter(function ($item) {
            return $item->stok_tersedia > 0;
        });

        // Data untuk tab Pengembalian (alat yang sedang dipinjam oleh user)
        $peralatanDipinjam = Auth::user()->peralatanYangSedangDipinjam();

        // Data untuk dropdown Storeman
        $daftarStoreman = Storeman::orderBy('nama')->get();

        return view('user.peminjaman.create', [
            'peralatanTersedia' => $peralatanTersedia,
            'peralatanDipinjam' => $peralatanDipinjam,
            'daftarStoreman' => $daftarStoreman,
        ]);
    }


    /**
     * Method untuk menyimpan transaksi PENGEMBALIAN.
     */
    public function kembalikan(Request $request): RedirectResponse
    {
        $request->validate([
            'storeman_id' => 'required|exists:storemen,id',
            'items' => 'required|array|min:1',
            'items.*.peralatan_id' => 'required|exists:peralatan,id',
            'items.*.jumlah' => 'required|integer|min:1',
        ]);

        // Validasi tambahan: pastikan user tidak mengembalikan lebih dari yang dipinjam
        $peralatanDipinjam = Auth::user()->peralatanYangSedangDipinjam()->keyBy('peralatan_id');
        foreach ($request->items as $item) {
            if (!$peralatanDipinjam->has($item['peralatan_id']) || $item['jumlah'] > $peralatanDipinjam[$item['peralatan_id']]->jumlah_dipinjam) {
                $namaPeralatan = Peralatan::find($item['peralatan_id'])->nama;
                return back()->withInput()->with('error', "Jumlah pengembalian untuk '{$namaPeralatan}' melebihi jumlah yang sedang dipinjam.");
            }
        }

        // Buat transaksi baru dengan tipe 'pengembalian'
        $transaksi = Transaksi::create([
            'kode_transaksi' => 'KEMBALI-' . time(),
            'tipe' => 'pengembalian',
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

        return redirect()->route('peminjaman.index')->with('success', 'Pengembalian berhasil dicatat.');
    }


    /**
     * METHOD STORE: Menyimpan data peminjaman baru.
     * (Tidak ada perubahan di sini, sudah benar)
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'storeman_id' => 'required|exists:storemen,id',
            'items' => 'required|array|min:1',
            'items.*.peralatan_id' => 'required|exists:peralatan,id',
            'items.*.jumlah' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            // Cek ketersediaan stok sebelum transaksi
            foreach ($request->items as $item) {
                $peralatan = Peralatan::find($item['peralatan_id']);
                if ($item['jumlah'] > $peralatan->stokTersedia()) {
                    DB::rollBack();
                    return back()->withInput()->with('error', "Stok untuk '{$peralatan->nama}' tidak mencukupi. Sisa: {$peralatan->stokTersedia()}");
                }
            }

            // Buat transaksi utama
            $transaksi = Transaksi::create([
                'kode_transaksi' => 'PINJAM-' . time(),
                'tipe' => 'peminjaman',
                'user_id' => Auth::id(),
                'storeman_id' => $request->storeman_id,
                'tanggal_transaksi' => now(),
            ]);

            // Buat detail transaksi
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
}
