<?php

namespace App\Http\Controllers;

use App\Models\Supply;
use App\Models\SupplyTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SupplyController extends Controller
{
    protected $stockService;

    public function __construct(\App\Services\StockService $stockService)
    {
        $this->stockService = $stockService;
    }

    public function index()
    {
        $supplies = Supply::latest()->paginate(10);
        return view('supplies.index', compact('supplies'));
    }

    public function create()
    {
        return view('supplies.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_barang' => 'required|string|max:255',
            'kategori' => 'required|in:Pakan,Obat',
            'satuan_besar' => 'required|string|max:50',
            'satuan_kecil' => 'required|string|max:50',
            'konversi' => 'required|numeric|min:1',
            'stok_minimal' => 'required|numeric|min:0',
        ]);
        $validated['stok_saat_ini'] = 0;
        $validated['harga_terakhir'] = 0;

        Supply::create($validated);

        return redirect()->route('supplies.index')
            ->with('success', 'Master stok berhasil ditambahkan.');
    }

    public function edit(Supply $supply)
    {
        return view('supplies.edit', compact('supply'));
    }

    public function update(Request $request, Supply $supply)
    {
        $validated = $request->validate([
            'nama_barang' => 'required|string|max:255',
            'kategori' => 'required|in:Pakan,Obat',
            'satuan_besar' => 'required|string|max:50',
            'satuan_kecil' => 'required|string|max:50',
            'konversi' => 'required|numeric|min:1',
            'stok_minimal' => 'required|numeric|min:0',
        ]);

        $supply->update($validated);

        return redirect()->route('supplies.index')
            ->with('success', 'Master stok berhasil diperbarui.');
    }

    public function destroy(Supply $supply)
    {
        if ($supply->dailyReports()->count() > 0) {
            return redirect()->route('supplies.index')
                ->with('error', 'Barang tidak bisa dihapus karena sudah digunakan dalam laporan.');
        }

        $supply->delete();

        return redirect()->route('supplies.index')
            ->with('success', 'Master stok berhasil dihapus.');
    }

    /**
     * Show transaction history
     */
    public function transactions()
    {
        $transactions = SupplyTransaction::with('supply')->latest()->paginate(20);
        return view('supplies.transactions', compact('transactions'));
    }

    /**
     * Record Stock In (Purchases)
     */
    public function stockIn(Request $request, Supply $supply)
    {
        $validated = $request->validate([
            'jumlah_besar' => 'required|numeric|min:0',
            'total_harga' => 'required|numeric|min:0',
            'tanggal' => 'required|date',
            'expired_at' => 'nullable|date',
            'keterangan' => 'nullable|string',
        ]);

        try {
            $this->stockService->addStock(
                $supply->id,
                $validated['jumlah_besar'],
                $validated['total_harga'],
                $validated['total_harga'],
                $validated['expired_at'] ?? null,
                $validated['keterangan'] ?? 'Stok Masuk (Pembelian)'
            );

            return redirect()->back()->with('success', "Stok {$supply->nama_barang} berhasil ditambahkan.");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Record Stock Out (Manual Adjustment)
     */
    public function stockOut(Request $request, Supply $supply)
    {
        $validated = $request->validate([
            'jumlah' => 'required|numeric|min:0.01',
            'tanggal' => 'required|date',
            'keterangan' => 'required|string',
        ]);

        try {
            $this->stockService->reduceStock(
                $supply->id,
                $validated['jumlah'],
                $validated['keterangan'],
                $validated['tanggal']
            );

            return redirect()->back()->with('success', "Pemakaian {$supply->nama_barang} berhasil dicatat.");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
