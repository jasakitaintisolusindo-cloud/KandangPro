<?php

namespace App\Http\Controllers;

use App\Models\DailyReport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DailyReportsExport;
use App\Services\StockService;
use Illuminate\Support\Facades\Storage;

class DailyReportController extends Controller
{
    protected $stockService;

    public function __construct(StockService $stockService)
    {
        $this->stockService = $stockService;
    }

    public function index(Request $request)
    {
        $query = DailyReport::with(['coop.farm', 'verifiedBy', 'creator']);

        if (auth()->check() && auth()->user()->isPetugas()) {
            $query->where('created_by', auth()->id());
        }

        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_akhir')) {
            $query->whereBetween('tanggal', [$request->tanggal_mulai, $request->tanggal_akhir]);
        }
        if ($request->filled('coop_id')) {
            $query->where('coop_id', $request->coop_id);
        }

        $reports = $query->orderBy('tanggal', 'desc')->paginate(15);
        $coops = \App\Models\Coop::with('farm')->get();

        return view('daily_reports.index', compact('reports', 'coops'));
    }

    public function create()
    {
        $coops = \App\Models\Coop::with('farm')->get();
        $supplies = \App\Models\Supply::where('kategori', 'Pakan')->get();
        return view('daily_reports.create', compact('coops', 'supplies'));
    }

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
            'foto_produksi' => 'nullable|image|max:5120',
            'foto_kematian' => 'nullable|image|max:5120',
        ]);

        if ($request->hasFile('foto_produksi')) {
            $validated['foto_produksi'] = $request->file('foto_produksi')->store('evidence/produksi', 'public');
        }
        if ($request->hasFile('foto_kematian')) {
            $validated['foto_kematian'] = $request->file('foto_kematian')->store('evidence/kematian', 'public');
        }

        $validated['total_pendapatan_telur'] = $validated['produksi_telur_kg'] * $validated['harga_telur_per_kg'];
        $validated['total_biaya_pakan'] = $validated['pakan_kg'] * $validated['harga_pakan_per_kg'];
        $validated['keuntungan_bersih'] = $validated['total_pendapatan_telur'] -
            ($validated['total_biaya_pakan'] + ($validated['biaya_lain_lain'] ?? 0));

        try {
            \DB::transaction(function () use ($validated) {
                $coop = \App\Models\Coop::findOrFail($validated['coop_id']);

                if ($validated['pakan_kg'] > 0) {
                    $this->stockService->reduceStock(
                        $validated['supply_id'],
                        $validated['pakan_kg'],
                        "Laporan Harian (Draft): {$coop->kode_kandang}",
                        $validated['tanggal']
                    );
                }

                $validated['created_by'] = auth()->id();
                DailyReport::create($validated);
                $coop->populasi_saat_ini -= $validated['jumlah_kematian'];
                $coop->save();
            });

            return redirect()->route('daily-reports.index')
                ->with('success', 'Data laporan harian ditambahkan (Status: DRAFT / Menunggu Approval).');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function show(DailyReport $dailyReport)
    {
        $dailyReport->load(['coop.farm', 'verifiedBy']);
        return view('daily_reports.show', compact('dailyReport'));
    }

    public function edit(DailyReport $dailyReport)
    {
        if ($dailyReport->status === 'approved') {
            return redirect()->route('daily-reports.index')->with('error', 'Laporan yang sudah di-Approve tidak bisa diedit.');
        }
        $coops = \App\Models\Coop::with('farm')->get();
        $supplies = \App\Models\Supply::where('kategori', 'Pakan')->get();
        return view('daily_reports.edit', compact('dailyReport', 'coops', 'supplies'));
    }

    public function update(Request $request, DailyReport $dailyReport)
    {
        if ($dailyReport->status === 'approved') {
            return redirect()->route('daily-reports.index')->with('error', 'Laporan yang sudah di-Approve tidak bisa diedit.');
        }
        
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
            'foto_produksi' => 'nullable|image|max:5120',
            'foto_kematian' => 'nullable|image|max:5120',
        ]);

        if ($request->hasFile('foto_produksi')) {
            if ($dailyReport->foto_produksi) Storage::disk('public')->delete($dailyReport->foto_produksi);
            $validated['foto_produksi'] = $request->file('foto_produksi')->store('evidence/produksi', 'public');
        }
        
        if ($request->hasFile('foto_kematian')) {
            if ($dailyReport->foto_kematian) Storage::disk('public')->delete($dailyReport->foto_kematian);
            $validated['foto_kematian'] = $request->file('foto_kematian')->store('evidence/kematian', 'public');
        }

        // Audit Logging
        $logs = $dailyReport->audit_logs ?? [];
        $logs[] = [
            'waktu' => now()->toDateTimeString(),
            'user' => auth()->user()->name ?? 'System',
            'aksi' => 'Mengubah Data Laporan'
        ];
        $validated['audit_logs'] = $logs;

        // Auto invalidate approval
        $validated['status'] = 'draft';
        $validated['verified_by'] = null;
        $validated['verified_at'] = null;

        $validated['total_pendapatan_telur'] = $validated['produksi_telur_kg'] * $validated['harga_telur_per_kg'];
        $validated['total_biaya_pakan'] = $validated['pakan_kg'] * $validated['harga_pakan_per_kg'];
        $validated['keuntungan_bersih'] = $validated['total_pendapatan_telur'] -
            ($validated['total_biaya_pakan'] + ($validated['biaya_lain_lain'] ?? 0));

        try {
            \DB::transaction(function () use ($validated, $dailyReport) {
                $oldCoop = \App\Models\Coop::findOrFail($dailyReport->coop_id);
                $newCoop = \App\Models\Coop::findOrFail($validated['coop_id']);

                $oldCoop->populasi_saat_ini += $dailyReport->jumlah_kematian;
                $oldCoop->save();

                if ($dailyReport->supply_id && $dailyReport->pakan_kg > 0) {
                    $this->stockService->restoreStock(
                        $dailyReport->supply_id,
                        (float) $dailyReport->pakan_kg,
                        "Koreksi Laporan: {$oldCoop->kode_kandang}"
                    );
                }

                if ($validated['pakan_kg'] > 0) {
                    $this->stockService->reduceStock(
                        $validated['supply_id'],
                        (float) $validated['pakan_kg'],
                        "Laporan Harian (Direvisi): {$newCoop->kode_kandang}",
                        $validated['tanggal']
                    );
                }

                $dailyReport->update($validated);
                $newCoop->populasi_saat_ini -= $validated['jumlah_kematian'];
                $newCoop->save();
            });

            return redirect()->route('daily-reports.index')
                ->with('success', 'Data laporan berhasil diubah. Status dikembalikan ke Draft (Butuh Approval ulang).');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function destroy(DailyReport $dailyReport)
    {
        if ($dailyReport->status === 'approved') {
            return redirect()->route('daily-reports.index')->with('error', 'Laporan yang sudah di-Approve tidak bisa dihapus.');
        }
        try {
            \DB::transaction(function () use ($dailyReport) {
                $coop = \App\Models\Coop::findOrFail($dailyReport->coop_id);
                $coop->populasi_saat_ini += $dailyReport->jumlah_kematian;
                $coop->save();

                if ($dailyReport->supply_id && $dailyReport->pakan_kg > 0) {
                    $this->stockService->restoreStock(
                        $dailyReport->supply_id,
                        (float) $dailyReport->pakan_kg,
                        "Pembatalan Laporan: {$coop->kode_kandang}"
                    );
                }

                if ($dailyReport->foto_produksi) Storage::disk('public')->delete($dailyReport->foto_produksi);
                if ($dailyReport->foto_kematian) Storage::disk('public')->delete($dailyReport->foto_kematian);
                
                $dailyReport->delete();
            });

            return redirect()->route('daily-reports.index')
                ->with('success', 'Data laporan dan bukti fisik dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function approve(DailyReport $dailyReport)
    {
        if (auth()->user()->isPetugas()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki hak akses untuk menyetujui laporan.');
        }

        $dailyReport->update([
            'status' => 'approved',
            'rejection_note' => null,
            'verified_by' => auth()->id(),
            'verified_at' => now()
        ]);

        return redirect()->back()->with('success', 'Laporan berhasil di-Approve! Pendapatan & Biaya kini masuk ke hitungan Executive Dashboard.');
    }

    public function reject(Request $request, DailyReport $dailyReport)
    {
        if (auth()->user()->isPetugas()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki hak akses untuk menolak laporan.');
        }

        $request->validate([
            'rejection_note' => 'required|string|max:1000'
        ]);

        $dailyReport->update([
            'status' => 'rejected',
            'rejection_note' => $request->rejection_note,
            'verified_by' => auth()->id(),
            'verified_at' => now()
        ]);

        return redirect()->back()->with('success', 'Laporan berhasil ditolak dan dikembalikan ke petugas dengan catatan.');
    }

    public function export(Request $request)
    {
        if (auth()->user()->isPetugas()) {
            return redirect()->back()->with('error', 'Hanya Manager yang memiliki hak akses untuk ekspor data.');
        }

        $query = DailyReport::with('coop.farm');

        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_akhir')) {
            $query->whereBetween('tanggal', [$request->tanggal_mulai, $request->tanggal_akhir]);
        }
        if ($request->filled('coop_id')) {
            $query->where('coop_id', $request->coop_id);
        }

        $reports = $query->orderBy('tanggal', 'desc')->get();
        return Excel::download(new DailyReportsExport($reports), 'laporan-jasfarm-' . date('Ymd-His') . '.xlsx');
    }
}
