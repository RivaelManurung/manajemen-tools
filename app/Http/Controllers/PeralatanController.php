<?php

namespace App\Http\Controllers;

use App\Models\Peralatan;
use App\Models\TransaksiDetail;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Validation\Rule;

class PeralatanController extends Controller
{
    /**
     * Menampilkan daftar peralatan beserta informasi stok terkini.
     */
    public function index(): View
    {
        // Mengambil data dengan paginasi
        $semuaPeralatan = Peralatan::latest()->paginate(10);

        // PENYEMPURNAAN: Menghitung dan menambahkan info stok ke setiap item
        // Ini penting agar view bisa menampilkan data "Dipinjam" dan "Tersedia"
        $semuaPeralatan->getCollection()->transform(function ($item) {
            $stokTersedia = $item->stokTersedia();
            $item->stok_dipinjam = $item->stok_total - $stokTersedia;
            $item->stok_tersedia = $stokTersedia;
            return $item;
        });

        return view('admin.peralatan.index', ['peralatan' => $semuaPeralatan]);
    }

    /**
     * Menampilkan form untuk membuat peralatan baru.
     */
    public function create(): View
    {
        // Asumsi path view sudah benar
        return view('admin.peralatan.create');
    }

    /**
     * Menyimpan peralatan baru ke database.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'kode' => 'required|string|unique:peralatan,kode',
            'stok_total' => 'required|integer|min:0',
        ]);

        Peralatan::create($request->all());

        return redirect()->route('peralatan.index')->with('success', 'Peralatan berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit peralatan.
     */
    public function edit(Peralatan $peralatan): View
    {
        return view('admin.peralatan.edit', compact('peralatan'));
    }

    /**
     * Mengupdate data peralatan di database.
     */
    public function update(Request $request, Peralatan $peralatan): RedirectResponse
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'kode' => ['required', 'string', Rule::unique('peralatan')->ignore($peralatan->id)],
            'stok_total' => 'required|integer|min:0',
        ]);

        $peralatan->update($request->all());

        return redirect()->route('admin.peralatan.index')->with('success', 'Data peralatan berhasil diperbarui.');
    }

    /**
     * Menghapus peralatan dari database.
     */
    public function destroy(Peralatan $peralatan): RedirectResponse
    {
        // Logika pengecekan menggunakan method helper dari model
        if ($peralatan->stokTersedia() < $peralatan->stok_total) {
            return redirect()->route('admin.peralatan.index')->with('error', 'Tidak bisa menghapus peralatan yang sedang dipinjam.');
        }

        $peralatan->delete();

        return redirect()->route('admin.peralatan.index')->with('success', 'Peralatan berhasil dihapus.');
    }
}
