<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Division;
use Illuminate\Http\Request;

class DivisionController extends Controller
{
    public function index()
    {
        $divisions = Division::withCount('users')->orderBy('name')->get();
        return view('divisions.index', compact('divisions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:divisions,name',
        ], [
            'name.required' => 'Nama divisi wajib diisi.',
            'name.max' => 'Nama divisi maksimal 255 karakter.',
            'name.unique' => 'Nama divisi sudah ada.',
        ]);

        Division::create(['name' => $request->name]);

        return redirect()->route('divisions.index')
            ->with('success', 'Divisi berhasil ditambahkan.');
    }

    public function update(Request $request, Division $division)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:divisions,name,' . $division->id,
        ], [
            'name.required' => 'Nama divisi wajib diisi.',
            'name.max' => 'Nama divisi maksimal 255 karakter.',
            'name.unique' => 'Nama divisi sudah ada.',
        ]);

        $division->update(['name' => $request->name]);

        return redirect()->route('divisions.index')
            ->with('success', 'Divisi berhasil diperbarui.');
    }

    public function destroy(Division $division)
    {
        if ($division->users()->count() > 0) {
            return redirect()->route('divisions.index')
                ->with('error', 'Divisi tidak dapat dihapus karena masih memiliki user.');
        }

        $division->delete();

        return redirect()->route('divisions.index')
            ->with('success', 'Divisi berhasil dihapus.');
    }
}
