<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // UMUM
            ['key' => 'farm_name', 'value' => 'Jasfarm Kediri', 'label' => 'Nama Peternakan', 'group' => 'Umum', 'type' => 'text'],
            ['key' => 'farm_address', 'value' => 'Jl. Raya Kandangan No. 10, Kediri', 'label' => 'Alamat Peternakan', 'group' => 'Umum', 'type' => 'textarea'],
            ['key' => 'currency', 'value' => 'IDR', 'label' => 'Mata Uang', 'group' => 'Umum', 'type' => 'text'],

            // TARGET PERFORMA
            ['key' => 'target_fcr', 'value' => '2.1', 'label' => 'Target FCR Ideal', 'group' => 'Target Performa', 'type' => 'number'],
            ['key' => 'target_hdp', 'value' => '85.0', 'label' => 'Target HDP Harian (%)', 'group' => 'Target Performa', 'type' => 'number'],
            ['key' => 'low_stock_threshold', 'value' => '50.0', 'label' => 'Batas Stok Kritis (Kg)', 'group' => 'Target Performa', 'type' => 'number'],

            // INTEGRASI
            ['key' => 'cctv_url', 'value' => 'http://stream.jasfarm.com/hls/live.m3u8', 'label' => 'URL Stream CCTV (HLS)', 'group' => 'Integrasi', 'type' => 'text'],
            ['key' => 'api_bridging_token', 'value' => 'JF-KEY-9920112', 'label' => 'API Bridging Token', 'group' => 'Integrasi', 'type' => 'text'],
            ['key' => 'whatsapp_report', 'value' => '081234567890', 'label' => 'Nomor WhatsApp Laporan', 'group' => 'Integrasi', 'type' => 'text'],
        ];

        foreach ($settings as $s) {
            Setting::updateOrCreate(['key' => $s['key']], $s);
        }
    }
}
