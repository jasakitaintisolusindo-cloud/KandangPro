<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Farm;
use App\Models\Coop;
use App\Models\DailyReport;

class FarmSeeder extends Seeder
{
    public function run(): void
    {
        // Create Farm
        $farm = Farm::create([
            'nama_peternakan' => 'Peternakan Jasfarm Utama',
            'lokasi' => 'Jl. Raya Peternakan No. 123, Bogor',
        ]);

        // Create Coops
        $coop1 = Coop::create([
            'farm_id' => $farm->id,
            'kode_kandang' => 'K-001',
            'populasi_awal' => 1000,
            'status' => true,
        ]);

        $coop2 = Coop::create([
            'farm_id' => $farm->id,
            'kode_kandang' => 'K-002',
            'populasi_awal' => 1500,
            'status' => true,
        ]);

        // Create Sample Daily Reports for last 7 days
        for ($i = 6; $i >= 0; $i--) {
            $tanggal = now()->subDays($i);

            // Report for Coop 1
            $produksi1 = rand(80, 120);
            $hargaTelur1 = 25000;
            $pakan1 = rand(50, 70);
            $hargaPakan1 = 8000;
            $biayaLain1 = rand(10000, 50000);

            DailyReport::create([
                'coop_id' => $coop1->id,
                'tanggal' => $tanggal,
                'produksi_telur_kg' => $produksi1,
                'harga_telur_per_kg' => $hargaTelur1,
                'pakan_kg' => $pakan1,
                'harga_pakan_per_kg' => $hargaPakan1,
                'biaya_lain_lain' => $biayaLain1,
                'keterangan' => 'Data sample untuk kandang ' . $coop1->kode_kandang,
                'total_pendapatan_telur' => $produksi1 * $hargaTelur1,
                'total_biaya_pakan' => $pakan1 * $hargaPakan1,
                'keuntungan_bersih' => ($produksi1 * $hargaTelur1) - (($pakan1 * $hargaPakan1) + $biayaLain1),
            ]);

            // Report for Coop 2
            $produksi2 = rand(120, 160);
            $hargaTelur2 = 25000;
            $pakan2 = rand(70, 90);
            $hargaPakan2 = 8000;
            $biayaLain2 = rand(15000, 60000);

            DailyReport::create([
                'coop_id' => $coop2->id,
                'tanggal' => $tanggal,
                'produksi_telur_kg' => $produksi2,
                'harga_telur_per_kg' => $hargaTelur2,
                'pakan_kg' => $pakan2,
                'harga_pakan_per_kg' => $hargaPakan2,
                'biaya_lain_lain' => $biayaLain2,
                'keterangan' => 'Data sample untuk kandang ' . $coop2->kode_kandang,
                'total_pendapatan_telur' => $produksi2 * $hargaTelur2,
                'total_biaya_pakan' => $pakan2 * $hargaPakan2,
                'keuntungan_bersih' => ($produksi2 * $hargaTelur2) - (($pakan2 * $hargaPakan2) + $biayaLain2),
            ]);
        }
    }
}
