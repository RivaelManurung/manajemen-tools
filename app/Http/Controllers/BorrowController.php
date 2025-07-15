<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Peralatan;
use App\Models\LogPeminjaman;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class BorrowController extends Controller
{
    /**
     * Menampilkan halaman utama peminjaman & pengembalian.
     */
    public function index()
    {
        $mechanics = User::where('peran', 'mekanik')->get();
        $tools = Peralatan::orderBy('nama', 'asc')->get();
        
        return view('user.borrow.index', compact('mechanics', 'tools'));
    }

    /**
     * Menyimpan data peminjaman baru.
     */
    public function storeBorrow(Request $request)
    {
        $request->validate([
            'mechanic_id' => 'required|exists:users,id',
            'tool_id' => 'required|exists:peralatan,id',
        ]);

        LogPeminjaman::create([
            'mekanik_id' => $request->mechanic_id,
            'storeman_id' => Auth::id(),
            'peralatan_id' => $request->tool_id,
            'waktu_pinjam' => Carbon::now(),
        ]);

        $tool = Peralatan::find($request->tool_id);
        if ($tool->status == 'tersedia') {
            $tool->status = 'dipinjam';
            $tool->save();
        } else {
            return redirect()->route('user.borrow.index')->withErrors('Peralatan ini sedang tidak tersedia.');
        }

        return redirect()->route('user.borrow.index')->with('success', 'Peralatan berhasil dipinjam.');
    }

    /**
     * Menyimpan data pengembalian.
     */
    public function storeReturn(Request $request)
    {
        $request->validate([
            'tool_id' => 'required|exists:peralatan,id',
            'condition' => 'required|in:sangat baik,baik,rusak',
        ]);

        $log = LogPeminjaman::where('peralatan_id', $request->tool_id)
                        ->whereNull('waktu_kembali')
                        ->latest('waktu_pinjam')
                        ->firstOrFail();

        $log->update([
            'waktu_kembali' => Carbon::now(),
            'kondisi' => $request->condition,
        ]);
        
        $tool = Peralatan::find($request->tool_id);
        $tool->status = 'tersedia';
        $tool->save();

        return redirect()->route('user.borrow.index')->with('success', 'Peralatan berhasil dikembalikan.');
    }
    
    /**
     * Menampilkan halaman riwayat/log peminjaman.
     */
    public function logs()
    {
        $logs = LogPeminjaman::with(['peralatan', 'mekanik', 'storeman'])->latest('waktu_pinjam')->paginate(20);
        
        return view('admin.logs.index', compact('logs'));
    }
}