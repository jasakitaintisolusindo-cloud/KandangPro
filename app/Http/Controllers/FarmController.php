<?php

namespace App\Http\Controllers;

use App\Models\Farm;
use Illuminate\Http\Request;

class FarmController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Farm::withCount('coops');

        if ($request->filled('search')) {
            $query->where('nama', 'LIKE', '%' . $request->search . '%')
                ->orWhere('lokasi', 'LIKE', '%' . $request->search . '%');
        }

        $farms = $query->latest()->paginate(10);

        return view('farms.index', compact('farms'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'lokasi' => 'required|string|max:255',
        ]);

        Farm::create($validated);

        return redirect()->route('farms.index')
            ->with('success', 'Peternakan berhasil ditambahkan.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Farm $farm)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'lokasi' => 'required|string|max:255',
        ]);

        $farm->update($validated);

        return redirect()->route('farms.index')
            ->with('success', 'Data peternakan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Farm $farm)
    {
        if ($farm->coops()->count() > 0) {
            return redirect()->route('farms.index')
                ->with('error', 'Peternakan tidak bisa dihapus karena masih memiliki kandang.');
        }

        $farm->delete();

        return redirect()->route('farms.index')
            ->with('success', 'Peternakan berhasil dihapus.');
    }
}
