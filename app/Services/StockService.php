<?php

namespace App\Services;

use App\Models\Supply;
use App\Models\SupplyTransaction;
use Illuminate\Support\Facades\DB;

class StockService
{
    /**
     * Add stock (Purchase)
     */
    public function addStock($supplyId, $cantidadBesar, $totalHarga, $expiredAt = null, $keterangan = 'Stok Masuk')
    {
        return DB::transaction(function () use ($supplyId, $cantidadBesar, $totalHarga, $expiredAt, $keterangan) {
            $supply = Supply::findOrFail($supplyId);

            // Convert to small unit
            $jumlahKecil = $cantidadBesar * $supply->konversi;

            // Calculate price per small unit
            $hargaSatuan = $jumlahKecil > 0 ? $totalHarga / $jumlahKecil : 0;

            // Update Supply
            $supply->stok_saat_ini += $jumlahKecil;
            $supply->harga_terakhir = $hargaSatuan;
            $supply->save();

            // Create Transaction Record
            SupplyTransaction::create([
                'supply_id' => $supply->id,
                'tipe' => 'Masuk',
                'jumlah' => $jumlahKecil,
                'harga_satuan' => $hargaSatuan,
                'total_harga' => $totalHarga,
                'tanggal' => now(),
                'expired_at' => $expiredAt,
                'keterangan' => $keterangan,
            ]);

            return $supply;
        });
    }

    /**
     * Reduce stock (Usage)
     */
    public function reduceStock($supplyId, $jumlahKecil, $keterangan, $tanggal)
    {
        return DB::transaction(function () use ($supplyId, $jumlahKecil, $keterangan, $tanggal) {
            $supply = Supply::findOrFail($supplyId);

            if ($supply->stok_saat_ini < $jumlahKecil) {
                throw new \Exception("Stok tidak mencukupi. Saat ini: {$supply->stok_saat_ini} {$supply->satuan_kecil}");
            }

            // Update Supply
            $supply->stok_saat_ini -= $jumlahKecil;
            $supply->save();

            // Create Transaction Record
            SupplyTransaction::create([
                'supply_id' => $supply->id,
                'tipe' => 'Keluar',
                'jumlah' => $jumlahKecil,
                'tanggal' => $tanggal,
                'keterangan' => $keterangan,
            ]);

            return $supply;
        });
    }
}
