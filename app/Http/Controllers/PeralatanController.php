<?php

namespace App\Http\Controllers;

use App\Models\Peralatan;
use Illuminate\Http\Request;

class PeralatanController extends Controller
{
    // Menampilkan daftar semua peralatan
    public function index()
    {
        $peralatan = Peralatan::paginate(10);
        return view('admin.peralatan.index', compact('peralatan'));
    }

    // Menampilkan form untuk membuat peralatan baru
    public function create()
    {
        return view('admin.peralatan.create');
    }

    // Menyimpan peralatan baru ke database
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'kode' => 'required|string|unique:peralatan,kode',
        ]);

        Peralatan::create($request->all());

        return redirect()->route('peralatan.index')->with('success', 'Peralatan berhasil ditambahkan.');
    }

    // Menampilkan form untuk mengedit peralatan
    public function edit(Peralatan $peralatan)
    {
        return view('admin.peralatan.edit', compact('peralatan'));
    }

    // Mengupdate data peralatan di database
    public function update(Request $request, Peralatan $peralatan)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'kode' => 'required|string|unique:peralatan,kode,' . $peralatan->id,
            'status' => 'required|in:tersedia,dipinjam',
        ]);

        $peralatan->update($request->all());

        return redirect()->route('peralatan.index')->with('success', 'Data peralatan berhasil diperbarui.');
    }

    // Menghapus peralatan dari database
    public function destroy(Peralatan $peralatan)
    {
        // Tambahkan validasi agar tidak bisa menghapus tool yang sedang dipinjam
        if ($peralatan->status === 'dipinjam') {
             return redirect()->route('peralatan.index')->withErrors('Tidak bisa menghapus peralatan yang sedang dipinjam.');
        }

        $peralatan->delete();

        return redirect()->route('peralatan.index')->with('success', 'Peralatan berhasil dihapus.');
    }
}