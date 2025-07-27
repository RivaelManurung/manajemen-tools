<?php

namespace App\Http\Controllers;

use App\Models\Storeman;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StoremanController extends Controller
{
    public function index()
    {
        $storemen = Storeman::latest()->paginate(10);
        return view('admin.storemen.index', compact('storemen'));
    }

    public function create()
    {
        return view('admin.storemen.create');
    }

    public function store(Request $request)
    {
        $request->validate(['nama' => 'required|string|max:255|unique:storemen']);
        Storeman::create($request->all());
        return redirect()->route('storemen.index')->with('success', 'Storeman berhasil ditambahkan.');
    }

    public function edit(Storeman $storeman)
    {
        return view('admin.storemen.edit', compact('storeman'));
    }

    public function update(Request $request, Storeman $storeman)
    {
        $request->validate(['nama' => ['required', 'string', 'max:255', Rule::unique('storemen')->ignore($storeman->id)]]);
        $storeman->update($request->all());
        return redirect()->route('storemen.index')->with('success', 'Data storeman berhasil diperbarui.');
    }

    public function destroy(Storeman $storeman)
    {
        // Anda bisa menambahkan validasi jika storeman pernah bertransaksi
        $storeman->delete();
        return redirect()->route('storemen.index')->with('success', 'Storeman berhasil dihapus.');
    }
}
