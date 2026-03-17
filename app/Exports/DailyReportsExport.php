<?php

namespace App\Exports;

use App\Models\DailyReport;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class DailyReportsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $reports;

    public function __construct($reports)
    {
        $this->reports = $reports;
    }

    public function collection()
    {
        $collection = $this->reports;

        // Add Total Row
        if ($collection->count() > 0) {
            $totalPendapatan = $collection->sum('total_pendapatan_telur');
            $totalBiayaPakan = $collection->sum('total_biaya_pakan');
            $totalBiayaLain = $collection->sum('biaya_lain_lain');
            $totalKeuntungan = $collection->sum('keuntungan_bersih');

            $collection->push((object) [
                'tanggal' => null,
                'coop' => (object) ['kode_kandang' => 'TOTAL'],
                'produksi_telur_kg' => null,
                'harga_telur_per_kg' => null,
                'total_pendapatan_telur' => $totalPendapatan,
                'total_biaya_pakan' => $totalBiayaPakan,
                'biaya_lain_lain' => $totalBiayaLain,
                'keuntungan_bersih' => $totalKeuntungan,
                'is_total' => true
            ]);
        }

        return $collection;
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Kandang',
            'Produksi (kg)',
            'Harga/kg',
            'Total Pendapatan',
            'Biaya Pakan',
            'Biaya Lain-lain',
            'Keuntungan Bersih',
        ];
    }

    public function map($report): array
    {
        if (isset($report->is_total)) {
            return [
                'TOTAL',
                '',
                '',
                '',
                number_format($report->total_pendapatan_telur, 0, ',', '.'),
                number_format($report->total_biaya_pakan, 0, ',', '.'),
                number_format($report->biaya_lain_lain, 0, ',', '.'),
                number_format($report->keuntungan_bersih, 0, ',', '.'),
            ];
        }

        return [
            $report->tanggal->format('d/m/Y'),
            $report->coop->kode_kandang . ' - ' . $report->coop->farm->nama_peternakan,
            number_format($report->produksi_telur_kg, 2, ',', '.'),
            number_format($report->harga_telur_per_kg, 0, ',', '.'),
            number_format($report->total_pendapatan_telur, 0, ',', '.'),
            number_format($report->total_biaya_pakan, 0, ',', '.'),
            number_format($report->biaya_lain_lain, 0, ',', '.'),
            number_format($report->keuntungan_bersih, 0, ',', '.'),
        ];
    }
}
