<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Farm extends Model
{
    protected $fillable = [
        'nama',
        'lokasi',
        'telepon',
    ];

    public function coops()
    {
        return $this->hasMany(Coop::class);
    }
}
