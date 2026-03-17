<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supply extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_barang',
        'kategori',
        'satuan_besar',
        'satuan_kecil',
        'konversi',
        'stok_saat_ini',
        'stok_minimal',
        'harga_terakhir',
    ];

    protected $casts = [
        'konversi' => 'decimal:2',
        'stok_saat_ini' => 'decimal:2',
        'stok_minimal' => 'decimal:2',
        'harga_terakhir' => 'decimal:2',
    ];

    public function transactions()
    {
        return $this->hasMany(SupplyTransaction::class);
    }

    public function dailyReports()
    {
        return $this->hasMany(DailyReport::class);
    }

    /**
     * Get stock in large unit (e.g., Sak)
     */
    public function getStokBesarAttribute()
    {
        if ($this->konversi <= 0)
            return 0;
        return floor($this->stok_saat_ini / $this->konversi);
    }

    /**
     * Get stock remainder in small unit (e.g., Kg)
     */
    public function getStokSisaKecilAttribute()
    {
        if ($this->konversi <= 0)
            return $this->stok_saat_ini;
        return fmod((float) $this->stok_saat_ini, (float) $this->konversi);
    }

    /**
     * Formatted stock string (e.g., "10 Sak 5 Kg")
     */
    public function getStokFormattedAttribute()
    {
        $besar = $this->stok_besar;
        $kecil = $this->stok_sisa_kecil;

        $result = [];
        if ($besar > 0)
            $result[] = "{$besar} {$this->satuan_besar}";
        if ($kecil > 0 || empty($result))
            $result[] = "{$kecil} {$this->satuan_kecil}";

        return implode(' ', $result);
    }
}
