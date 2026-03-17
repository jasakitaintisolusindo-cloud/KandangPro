<?php

namespace App\Http\Controllers;

use App\Models\DailyReport;
use App\Models\Coop;
use App\Models\Supply;
use App\Models\SupplyTransaction;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $last30Days = Carbon::now()->subDays(30);
        $today = Carbon::today();

        // 1. Summary Finansial & Produksi 30 Hari Terakhir
        $summary = [
            'total_produksi' => DailyReport::where('tanggal', '>=', $last30Days)->sum('produksi_telur_kg'),
            'total_pendapatan' => DailyReport::where('tanggal', '>=', $last30Days)->sum('total_pendapatan_telur'),
            'total_keuntungan' => DailyReport::where('tanggal', '>=', $last30Days)->sum('keuntungan_bersih'),
            'avg_keuntungan' => DailyReport::where('tanggal', '>=', $last30Days)->avg('keuntungan_bersih') ?? 0,
        ];

        // 2. Technical KPIs (30 Days)
        $totalPakan = DailyReport::where('tanggal', '>=', $last30Days)->sum('pakan_kg');
        $totalButir = DailyReport::where('tanggal', '>=', $last30Days)->sum('jumlah_telur_butir');
        $totalMati = DailyReport::where('tanggal', '>=', $last30Days)->sum('jumlah_kematian');
        $totalPopulasi = Coop::sum('populasi_saat_ini');

        $kpi = [
            'fcr' => $summary['total_produksi'] > 0 ? $totalPakan / $summary['total_produksi'] : 0,
            'hdp' => ($totalPopulasi > 0 && $totalButir > 0) ? ($totalButir / ($totalPopulasi * 30)) * 100 : 0,
            'mortality' => $totalMati,
        ];

        // Real-time HDP for widgets (Today)
        $todayButir = DailyReport::where('tanggal', $today)->sum('jumlah_telur_butir');
        $kpi['hdp_today'] = $totalPopulasi > 0 ? ($todayButir / $totalPopulasi) * 100 : 0;

        // 3. Alerts System
        $alerts = [];
        $todayReport = DailyReport::where('tanggal', $today)->exists();
        if (!$todayReport) {
            $alerts[] = [
                'type' => 'warning',
                'message' => 'Laporan harian hari ini belum diisi!',
                'icon' => 'warning'
            ];
        }

        // Alert Stok Kritis (Gudang)
        $threshold = setting('low_stock_threshold', 50);
        $lowSupplies = Supply::whereRaw('stok_saat_ini <= ?', [$threshold])->get();
        foreach ($lowSupplies as $ls) {
            $alerts[] = [
                'type' => 'danger',
                'message' => "STOK KRITIS: {$ls->nama_barang} sisa " . number_format($ls->stok_saat_ini, 0) . " {$ls->satuan_kecil}!",
                'icon' => 'pakan'
            ];
        }

        // Anomali Produksi (Turun > 5% vs Rata-rata 7 Hari)
        $coops = Coop::where('status', true)->get();
        foreach ($coops as $coop) {
            $avgProduction = DailyReport::where('coop_id', $coop->id)
                ->where('tanggal', '>=', Carbon::now()->subDays(7))
                ->avg('produksi_telur_kg') ?? 0;

            $todayProd = DailyReport::where('coop_id', $coop->id)
                ->where('tanggal', $today)
                ->value('produksi_telur_kg') ?? 0;

            if ($avgProduction > 0 && $todayProd > 0 && $todayProd < ($avgProduction * 0.95)) {
                $alerts[] = [
                    'type' => 'warning',
                    'message' => "Produksi Kandang {$coop->kode_kandang} turun > 5% dibanding rata-rata seminggu.",
                    'icon' => 'warning'
                ];
            }
        }

        // 4. Supply Summary (Pakan) untuk Dashboard Widget
        $supplySummary = Supply::where('kategori', 'Pakan')->get()->map(function ($s) {
            $avgUsage = SupplyTransaction::where('supply_id', $s->id)
                ->where('tipe', 'Keluar')
                ->where('tanggal', '>=', Carbon::now()->subDays(7))
                ->avg('jumlah') ?? 0;

            $daysLeft = $avgUsage > 0 ? floor($s->stok_saat_ini / $avgUsage) : 999;

            return [
                'name' => $s->nama_barang,
                'stock' => $s->stok_saat_ini,
                'unit' => $s->satuan_kecil,
                'days_left' => $daysLeft,
                'status' => $s->stok_saat_ini <= $s->stok_minimal ? 'low' : 'ok'
            ];
        });

        return view('dashboard.index', compact('summary', 'kpi', 'alerts', 'coops', 'supplySummary'));
    }

    public function getChartData()
    {
        $last30Days = Carbon::now()->subDays(30);

        // Data untuk Line Chart (Trend Keuntungan 30 Hari)
        $dailyData = DailyReport::where('tanggal', '>=', $last30Days)
            ->selectRaw('tanggal, SUM(keuntungan_bersih) as total_keuntungan, SUM(produksi_telur_kg) as total_produksi')
            ->groupBy('tanggal')
            ->orderBy('tanggal', 'asc')
            ->get();

        // Data untuk Bar Chart (Keuntungan per Kandang 30 Hari)
        $coopData = DailyReport::where('tanggal', '>=', $last30Days)
            ->with('coop')
            ->selectRaw('coop_id, SUM(keuntungan_bersih) as total_keuntungan, AVG(produksi_telur_kg) as avg_produksi')
            ->groupBy('coop_id')
            ->get()
            ->map(function ($item) {
                return [
                    'label' => $item->coop->kode_kandang ?? 'Unknown',
                    'value' => (float) $item->total_keuntungan,
                    'target' => (float) ($item->coop->target_hdp ?? 0),
                    'realisasi' => ($item->coop && $item->coop->populasi_awal > 0) ? ($item->avg_produksi / $item->coop->populasi_awal) * 100 : 0
                ];
            });

        return response()->json([
            'trend' => [
                'labels' => $dailyData->map(fn($item) => $item->tanggal->format('d/m')),
                'values' => $dailyData->map(fn($item) => (float) $item->total_keuntungan),
            ],
            'coops' => [
                'labels' => $coopData->pluck('label'),
                'values' => $coopData->pluck('value'),
                'realisasi' => $coopData->pluck('realisasi'),
                'target' => $coopData->pluck('target'),
            ]
        ]);
    }
}
