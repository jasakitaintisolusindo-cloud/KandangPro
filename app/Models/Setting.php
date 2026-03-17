<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value', 'label', 'group', 'type', 'options'];

    protected static function booted()
    {
        static::updated(function ($setting) {
            Cache::forget('setting_' . $setting->key);
            Cache::forget('all_settings');
        });
    }
}
