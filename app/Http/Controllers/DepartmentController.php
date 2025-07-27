<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class DepartmentController extends Controller
{
    /**
     * Menampilkan daftar semua departemen.
     */
    public function index(): View
    {
        // withCount('users') untuk menghitung jumlah user di setiap departemen secara efisien
        $departments = Department::withCount('users')->latest()->paginate(10);
        return view('admin.departemen.index', compact('departments'));
    }

    /**
     * Menyimpan departemen baru ke database.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate(['name' => 'required|string|max:255|unique:departments']);
        Department::create($request->all());
        return redirect()->route('admin.departemen.index')->with('success', 'Departemen berhasil ditambahkan.');
    }

    /**
     * Mengupdate data departemen di database.
     */
    public function update(Request $request, Department $department): RedirectResponse
    {
        $request->validate(['name' => ['required', 'string', 'max:255', Rule::unique('departments')->ignore($department->id)]]);
        $department->update($request->all());
        return redirect()->route('admin.departemen.index')->with('success', 'Data departemen berhasil diperbarui.');
    }

    /**
     * Menghapus departemen dari database.
     */
    public function destroy(Department $department): RedirectResponse
    {
        // Validasi: Jangan hapus departemen jika masih ada user di dalamnya
        if ($department->users()->count() > 0) {
            return back()->with('error', 'Departemen tidak bisa dihapus karena masih digunakan oleh user.');
        }
        $department->delete();
        return redirect()->route('admin.departemen.index')->with('success', 'Departemen berhasil dihapus.');
    }
}