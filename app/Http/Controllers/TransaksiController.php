<?php

namespace App\Http\Controllers;

use App\Models\Peralatan;
use App\Models\Storeman;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class TransaksiController extends Controller
{
    /**
     * Menampilkan riwayat SEMUA transaksi dengan filter.
     */
    public function index(Request $request)
    {
        $transaksiQuery = Transaksi::with(['user', 'storeman'])->latest('tanggal_transaksi');

        $this->applyDateFilters($transaksiQuery, $request);

        $transaksis = $transaksiQuery->paginate(15)->withQueryString();

        return view('admin.transaksi.index', compact('transaksis'));
    }

    /**
     * Menampilkan form untuk membuat transaksi baru oleh Admin.
     */
    public function create()
    {
        $peralatanTersedia = Peralatan::orderBy('nama')->get()->map(function ($item) {
            $item->stok_tersedia = $item->stokTersedia();
            return $item;
        })->filter(function ($item) {
            return $item->stok_tersedia > 0;
        });

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
     * Menyimpan transaksi baru yang dibuat oleh Admin.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tipe' => 'required|in:peminjaman,pengembalian',
            'user_id' => 'required|exists:users,id',
            'storeman_id' => 'required|exists:storemen,id',
            'items' => 'required|array|min:1',
            'items.*.peralatan_id' => 'required|exists:peralatan,id',
            'items.*.jumlah' => 'required|integer|min:1',
            'catatan' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();
        try {
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
                    'kondisi' => $request->tipe == 'pengembalian' ? ($item['kondisi'] ?? 'baik') : null,
                ]);
            }

            DB::commit();
            return redirect()->route('admin.transaksi.index')->with('success', 'Transaksi ' . $request->tipe . ' berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Mengambil data detail transaksi untuk ditampilkan di modal.
     */
    public function show(Transaksi $transaksi)
    {
        $transaksi->load(['user', 'storeman', 'details.peralatan']);
        return response()->json($transaksi);
    }
    
    /**
     * Mengekspor data transaksi yang difilter ke PDF.
     */
    public function exportPDF(Request $request)
    {
        $transaksiQuery = Transaksi::with(['user', 'storeman', 'details.peralatan'])->latest('tanggal_transaksi');
        $this->applyDateFilters($transaksiQuery, $request);
        $transaksis = $transaksiQuery->get();
        $tanggalCetak = Carbon::now()->format('d M Y');

        $pdf = Pdf::loadView('admin.transaksi.pdf', compact('transaksis', 'tanggalCetak'));
        $pdf->setPaper('a4', 'landscape');
        return $pdf->download('laporan-transaksi-' . time() . '.pdf');
    }

    /**
     * Method private untuk menerapkan filter tanggal.
     */
    private function applyDateFilters($query, Request $request)
    {
        if ($request->filled('filter')) {
            switch ($request->filter) {
                case 'harian': $query->whereDate('tanggal_transaksi', Carbon::today()); break;
                case 'mingguan': $query->whereBetween('tanggal_transaksi', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]); break;
                case 'bulanan': $query->whereMonth('tanggal_transaksi', Carbon::now()->month); break;
            }
        } elseif ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal_transaksi', [$request->start_date, $request->end_date]);
        }
    }
}