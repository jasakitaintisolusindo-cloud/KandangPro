<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyReport extends Model
{
    protected $fillable = [
        'coop_id',
        'supply_id',
        'tanggal',
        'produksi_telur_kg',
        'jumlah_telur_butir',
        'harga_telur_per_kg',
        'pakan_kg',
        'harga_pakan_per_kg',
        'biaya_lain_lain',
        'jumlah_kematian',
        'keterangan',
        'total_pendapatan_telur',
        'total_biaya_pakan',
        'keuntungan_bersih',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'produksi_telur_kg' => 'decimal:2',
        'harga_telur_per_kg' => 'decimal:2',
        'pakan_kg' => 'decimal:2',
        'harga_pakan_per_kg' => 'decimal:2',
        'biaya_lain_lain' => 'decimal:2',
        'total_pendapatan_telur' => 'decimal:2',
        'total_biaya_pakan' => 'decimal:2',
        'keuntungan_bersih' => 'decimal:2',
    ];

    public function coop()
    {
        return $this->belongsTo(Coop::class);
    }

    public function supply()
    {
        return $this->belongsTo(Supply::class);
    }
}
