<?php

namespace App\Http\Controllers;

use App\Models\Coop;
use App\Models\Farm;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CoopController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Coop::with('farm');

        // Fitur Pencarian Cepat berdasarkan Kode Kandang
        if ($request->filled('search')) {
            $query->where('kode_kandang', 'LIKE', '%' . $request->search . '%');
        }

        $coops = $query->latest()->paginate(10);

        // Ringkasan Populasi (Kandang Aktif)
        $summary = [
            'total_active_coops' => Coop::where('status', true)->count(),
            'total_capacity' => Coop::where('status', true)->sum('populasi_awal'),
            'total_current_population' => Coop::where('status', true)->sum('populasi_saat_ini'),
        ];

        return view('coops.index', compact('coops', 'summary'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Coop $coop)
    {
        return view('coops.show', compact('coop'));
    }

    // Methods create and edit removed as they are handled by modals in index
    public function edit(Coop $coop)
    {
        return redirect()->route('coops.index')
            ->with('edit_coop_data', $coop->toJson());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'farm_id' => 'required|exists:farms,id',
            'kode_kandang' => 'required|string|max:50|unique:coops,kode_kandang',
            'populasi_awal' => 'required|integer|min:1',
            'status' => 'required|boolean',
            'cctv_url' => 'nullable|url',
        ]);

        // Default populasi_saat_ini = populasi_awal sesuai kebutuhan Master
        $validated['populasi_saat_ini'] = $validated['populasi_awal'];
        $validated['status'] = $request->boolean('status');
        // cctv_url automatically included in $validated because it's in the validation rules

        Coop::create($validated);

        return redirect()->route('coops.index')
            ->with('success', 'Master kandang berhasil ditambahkan.');
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Coop $coop)
    {
        $validated = $request->validate([
            'farm_id' => 'required|exists:farms,id',
            'kode_kandang' => [
                'required',
                'string',
                'max:50',
                Rule::unique('coops')->ignore($coop->id),
            ],
            'populasi_awal' => 'required|integer|min:1',
            // 'populasi_saat_ini' validation removed as it is not editable here
            'stok_pakan_kg' => 'nullable|numeric|min:0',
            'target_hdp' => 'nullable|numeric|min:0|max:100',
            'status' => 'required|boolean',
            'cctv_url' => 'nullable|url',
        ]);

        $validated['status'] = $request->boolean('status');

        $coop->update($validated);

        return redirect()->route('coops.index')
            ->with('success', 'Data kandang berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Coop $coop)
    {
        // Cek apakah ada laporan harian terkait
        if ($coop->dailyReports()->count() > 0) {
            return redirect()->route('coops.index')
                ->with('error', 'Kandang tidak bisa dihapus karena memiliki data laporan harian.');
        }

        $coop->delete();

        return redirect()->route('coops.index')
            ->with('success', 'Master kandang berhasil dihapus.');
    }

    /**
     * Save snapshot from CCTV stream
     */
    public function saveSnapshot(Request $request, Coop $coop)
    {
        $request->validate([
            'image' => 'required|string', // Base64 image
        ]);

        $imageBody = substr($request->image, strpos($request->image, ',') + 1);
        $imageData = base64_decode($imageBody);

        $filename = 'snapshot_' . $coop->kode_kandang . '_' . date('Ymd_His') . '.jpg';
        $path = 'snapshots/' . $filename;

        Storage::disk('public')->put($path, $imageData);

        return response()->json([
            'success' => true,
            'message' => 'Snapshot berhasil disimpan.',
            'url' => Storage::url($path)
        ]);
    }
}
