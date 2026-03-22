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
        $prev30Days = Carbon::now()->subDays(60);
        $today = Carbon::today();

        // 1. Summary Finansial & Produksi (Current 30 Days) - APPROVED ONLY
        $baseQuery = DailyReport::where('status', 'approved')->where('tanggal', '>=', $last30Days);
        $prevQuery = DailyReport::where('status', 'approved')->whereBetween('tanggal', [$prev30Days, $last30Days]);

        $summary = [
            'total_produksi' => (clone $baseQuery)->sum('produksi_telur_kg'),
            'total_pendapatan' => (clone $baseQuery)->sum('total_pendapatan_telur'),
            'total_keuntungan' => (clone $baseQuery)->sum('keuntungan_bersih'),
            'avg_keuntungan' => (clone $baseQuery)->avg('keuntungan_bersih') ?? 0,
        ];

        // Prev 30 Days for MoM Compare
        $summary_prev = [
            'total_produksi' => (clone $prevQuery)->sum('produksi_telur_kg'),
            'total_keuntungan' => (clone $prevQuery)->sum('keuntungan_bersih'),
        ];

        // 2. Technical KPIs (30 Days)
        $totalPakan = (clone $baseQuery)->sum('pakan_kg');
        $totalButir = (clone $baseQuery)->sum('jumlah_telur_butir');
        $totalMati = (clone $baseQuery)->sum('jumlah_kematian');
        $totalPopulasi = Coop::sum('populasi_saat_ini');
        $totalPopulasiAwal = Coop::sum('populasi_awal');

        $kpi = [
            'fcr' => $summary['total_produksi'] > 0 ? $totalPakan / $summary['total_produksi'] : 0,
            'hdp' => ($totalPopulasi > 0 && $totalButir > 0) ? ($totalButir / ($totalPopulasi * 30)) * 100 : 0,
            'mortality' => $totalPopulasiAwal > 0 ? ($totalMati / $totalPopulasiAwal) * 100 : 0,
            'mortality_count' => $totalMati,
            'pakan_total' => $totalPakan
        ];

        // Real-time HDP for widgets (Today)
        $todayButir = DailyReport::where('status', 'approved')->where('tanggal', $today)->sum('jumlah_telur_butir');
        $kpi['hdp_today'] = $totalPopulasi > 0 ? ($todayButir / $totalPopulasi) * 100 : 0;

        // Executive AI Summary Text Logic
        $fcrTarget = setting('target_fcr', 2.1);
        $aiSummary = [
            'fcrStatus' => $kpi['fcr'] <= $fcrTarget ? 'Sangat Baik' : 'Perlu Evaluasi',
            'profitProjection' => $summary['total_keuntungan'] > 0 ? $summary['avg_keuntungan'] * 30 : 0,
            'highestCostCoop' => '-',
            'fcrValue' => number_format($kpi['fcr'], 2)
        ];

        // Find coop with highest feed cost in last 7 days
        $highestCostCoopObj = DailyReport::where('status', 'approved')
            ->selectRaw('coop_id, SUM(pakan_kg) as total_pakan')
            ->where('tanggal', '>=', Carbon::now()->subDays(7))
            ->groupBy('coop_id')
            ->orderByDesc('total_pakan')
            ->first();
        if($highestCostCoopObj && $highestCostCoopObj->total_pakan > 0) {
            $coopData = Coop::find($highestCostCoopObj->coop_id);
            if($coopData) $aiSummary['highestCostCoop'] = $coopData->kode_kandang;
        }

        // 3. Alerts System
        $alerts = [];
        $todayReport = DailyReport::where('tanggal', $today)->exists(); // Checks if any report exists regardless of status
        if (!$todayReport && count(Coop::all()) > 0) {
            $alerts[] = [
                'type' => 'danger',
                'message' => 'Laporan harian hari ini belum diisi sama sekali!',
                'icon' => 'warning'
            ];
        } else {
            // Check if there are unapproved reports
            $unapprovedReports = DailyReport::where('status', 'draft')->count();
            if ($unapprovedReports > 0) {
                $alerts[] = [
                    'type' => 'warning',
                    'message' => "Ada {$unapprovedReports} laporan (Draft) yang menunggu Approval Anda agar masuk ke Dashboard.",
                    'icon' => 'info'
                ];
            }
        }

        $threshold = setting('low_stock_threshold', 50);
        $lowSupplies = Supply::whereRaw('stok_saat_ini <= ?', [$threshold])->get();
        foreach ($lowSupplies as $ls) {
            $alerts[] = [
                'type' => 'danger',
                'message' => "STOK KRITIS: {$ls->nama_barang} sisa " . number_format($ls->stok_saat_ini, 0) . " {$ls->satuan_kecil}!",
                'icon' => 'pakan'
            ];
        }

        $coops = Coop::where('status', true)->get();

        // 4. Operational Cost Distribution (Dummy for presentation)
        $operationalCostDummy = [
            'keys' => ['Pakan Ternak', 'Vitamin & Obat', 'Listrik', 'Gaji & Operasional'],
            'values' => [72, 8, 5, 15] // Total 100%
        ];

        return view('dashboard.index', compact('summary', 'summary_prev', 'kpi', 'alerts', 'coops', 'aiSummary', 'operationalCostDummy'));
    }

    public function getChartData()
    {
        $last30Days = Carbon::now()->subDays(30);

        // Twin Chart Data (Produksi vs Pakan)
        $dailyData = DailyReport::where('status', 'approved')->where('tanggal', '>=', $last30Days)
            ->selectRaw('tanggal, SUM(produksi_telur_kg) as total_produksi, SUM(pakan_kg) as total_pakan')
            ->groupBy('tanggal')
            ->orderBy('tanggal', 'asc')
            ->get();

        return response()->json([
            'trend' => [
                'labels' => $dailyData->map(fn($item) => $item->tanggal->format('d/m')),
                'produksi' => $dailyData->map(fn($item) => (float) $item->total_produksi),
                'pakan' => $dailyData->map(fn($item) => (float) $item->total_pakan),
            ]
        ]);
    }
}
