<?php

namespace App\Http\Controllers;

use App\Models\DailyReport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DailyReportsExport;
use App\Services\StockService;

class DailyReportController extends Controller
{
    protected $stockService;

    public function __construct(StockService $stockService)
    {
        $this->stockService = $stockService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = DailyReport::with('coop.farm');

        // Filter berdasarkan tanggal
        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_akhir')) {
            $query->whereBetween('tanggal', [$request->tanggal_mulai, $request->tanggal_akhir]);
        }

        // Filter berdasarkan kandang
        if ($request->filled('coop_id')) {
            $query->where('coop_id', $request->coop_id);
        }

        $reports = $query->orderBy('tanggal', 'desc')->paginate(15);
        $coops = \App\Models\Coop::with('farm')->get();

        return view('daily_reports.index', compact('reports', 'coops'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $coops = \App\Models\Coop::with('farm')->get();
        $supplies = \App\Models\Supply::where('kategori', 'Pakan')->get();
        return view('daily_reports.create', compact('coops', 'supplies'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'coop_id' => 'required|exists:coops,id',
            'supply_id' => 'required|exists:supplies,id',
            'tanggal' => 'required|date',
            'produksi_telur_kg' => 'required|numeric|min:0',
            'harga_telur_per_kg' => 'required|numeric|min:0',
            'pakan_kg' => 'required|numeric|min:0',
            'harga_pakan_per_kg' => 'required|numeric|min:0',
            'biaya_lain_lain' => 'nullable|numeric|min:0',
            'jumlah_telur_butir' => 'required|integer|min:0',
            'jumlah_kematian' => 'required|integer|min:0',
            'keterangan' => 'nullable|string',
        ]);

        // Perhitungan otomatis
        $validated['total_pendapatan_telur'] = $validated['produksi_telur_kg'] * $validated['harga_telur_per_kg'];
        $validated['total_biaya_pakan'] = $validated['pakan_kg'] * $validated['harga_pakan_per_kg'];
        $validated['keuntungan_bersih'] = $validated['total_pendapatan_telur'] -
            ($validated['total_biaya_pakan'] + ($validated['biaya_lain_lain'] ?? 0));

        try {
            \DB::transaction(function () use ($validated) {
                $coop = \App\Models\Coop::findOrFail($validated['coop_id']);

                // 1. Potong Stok via Service
                if ($validated['pakan_kg'] > 0) {
                    $this->stockService->reduceStock(
                        $validated['supply_id'],
                        $validated['pakan_kg'],
                        "Laporan Harian: {$coop->kode_kandang}",
                        $validated['tanggal']
                    );
                }

                // 2. Simpan Laporan
                DailyReport::create($validated);

                // 3. Update Populasi di Kandang
                $coop->populasi_saat_ini -= $validated['jumlah_kematian'];
                $coop->save();
            });

            return redirect()->route('daily-reports.index')
                ->with('success', 'Data laporan harian berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(DailyReport $dailyReport)
    {
        $dailyReport->load('coop.farm');
        return view('daily_reports.show', compact('dailyReport'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DailyReport $dailyReport)
    {
        $coops = \App\Models\Coop::with('farm')->get();
        $supplies = \App\Models\Supply::where('kategori', 'Pakan')->get();
        return view('daily_reports.edit', compact('dailyReport', 'coops', 'supplies'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DailyReport $dailyReport)
    {
        $validated = $request->validate([
            'coop_id' => 'required|exists:coops,id',
            'supply_id' => 'required|exists:supplies,id',
            'tanggal' => 'required|date',
            'produksi_telur_kg' => 'required|numeric|min:0',
            'harga_telur_per_kg' => 'required|numeric|min:0',
            'pakan_kg' => 'required|numeric|min:0',
            'harga_pakan_per_kg' => 'required|numeric|min:0',
            'biaya_lain_lain' => 'nullable|numeric|min:0',
            'jumlah_telur_butir' => 'required|integer|min:0',
            'jumlah_kematian' => 'required|integer|min:0',
            'keterangan' => 'nullable|string',
        ]);

        // Perhitungan otomatis
        $validated['total_pendapatan_telur'] = $validated['produksi_telur_kg'] * $validated['harga_telur_per_kg'];
        $validated['total_biaya_pakan'] = $validated['pakan_kg'] * $validated['harga_pakan_per_kg'];
        $validated['keuntungan_bersih'] = $validated['total_pendapatan_telur'] -
            ($validated['total_biaya_pakan'] + ($validated['biaya_lain_lain'] ?? 0));

        try {
            \DB::transaction(function () use ($validated, $dailyReport) {
                $oldCoop = \App\Models\Coop::findOrFail($dailyReport->coop_id);
                $newCoop = \App\Models\Coop::findOrFail($validated['coop_id']);

                // 1. Restore data lama (Populasi & Stok)
                $oldCoop->populasi_saat_ini += $dailyReport->jumlah_kematian;
                $oldCoop->save();

                if ($dailyReport->supply_id && $dailyReport->pakan_kg > 0) {
                    $this->stockService->restoreStock(
                        $dailyReport->supply_id,
                        (float) $dailyReport->pakan_kg,
                        "Koreksi Laporan (Update): {$oldCoop->kode_kandang}"
                    );
                }

                // 2. Terapkan data baru via Service
                if ($validated['pakan_kg'] > 0) {
                    $this->stockService->reduceStock(
                        $validated['supply_id'],
                        (float) $validated['pakan_kg'],
                        "Laporan Harian (Update): {$newCoop->kode_kandang}",
                        $validated['tanggal']
                    );
                }

                // 3. Update Laporan & Populasi Baru
                $dailyReport->update($validated);
                $newCoop->populasi_saat_ini -= $validated['jumlah_kematian'];
                $newCoop->save();
            });

            return redirect()->route('daily-reports.index')
                ->with('success', 'Data laporan harian berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DailyReport $dailyReport)
    {
        try {
            \DB::transaction(function () use ($dailyReport) {
                $coop = \App\Models\Coop::findOrFail($dailyReport->coop_id);

                // 1. Restore Populasi & Stok
                $coop->populasi_saat_ini += $dailyReport->jumlah_kematian;
                $coop->save();

                if ($dailyReport->supply_id && $dailyReport->pakan_kg > 0) {
                    $this->stockService->restoreStock(
                        $dailyReport->supply_id,
                        (float) $dailyReport->pakan_kg,
                        "Pembatalan Laporan: {$coop->kode_kandang}"
                    );
                }

                // 2. Hapus Laporan
                $dailyReport->delete();
            });

            return redirect()->route('daily-reports.index')
                ->with('success', 'Data laporan harian telah dihapus dan stok dikembalikan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Export to Excel
     */
    public function export(Request $request)
    {
        $query = DailyReport::with('coop.farm');

        // Filter berdasarkan tanggal
        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_akhir')) {
            $query->whereBetween('tanggal', [$request->tanggal_mulai, $request->tanggal_akhir]);
        }

        // Filter berdasarkan kandang
        if ($request->filled('coop_id')) {
            $query->where('coop_id', $request->coop_id);
        }

        $reports = $query->orderBy('tanggal', 'desc')->get();

        return Excel::download(new DailyReportsExport($reports), 'laporan-jasfarm-' . date('Ymd-His') . '.xlsx');
    }
}
