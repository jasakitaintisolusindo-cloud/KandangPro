<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplyTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'supply_id',
        'tipe',
        'jumlah',
        'harga_satuan',
        'total_harga',
        'tanggal',
        'expired_at',
        'keterangan',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'expired_at' => 'date',
        'jumlah' => 'decimal:2',
        'harga_satuan' => 'decimal:2',
        'total_harga' => 'decimal:2',
    ];

    public function supply()
    {
        return $this->belongsTo(Supply::class);
    }
}
