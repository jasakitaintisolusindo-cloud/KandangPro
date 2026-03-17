<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coop extends Model
{
    protected $fillable = [
        'farm_id',
        'kode_kandang',
        'populasi_awal',
        'status',
        'cctv_url',
        'stok_pakan_kg',
        'target_hdp',
        'populasi_saat_ini',
    ];

    /**
     * Get the Coop's label for dropdowns.
     * Format: "K-001 - Peternakan Jasfarm Utama (Populasi: 1000)"
     */
    public function getLabelAttribute()
    {
        return "{$this->kode_kandang} - {$this->farm->nama} (Populasi: {$this->populasi_awal})";
    }

    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }

    public function dailyReports()
    {
        return $this->hasMany(DailyReport::class);
    }
}
