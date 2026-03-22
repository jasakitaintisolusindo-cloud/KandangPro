<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        $settings = [
            [
                'key' => 'petugas_access_farms',
                'label' => 'Akses Master Peternakan',
                'value' => '0',
                'type' => 'boolean',
                'group' => 'Hak Akses Petugas'
            ],
            [
                'key' => 'petugas_access_coops',
                'label' => 'Akses Master Kandang',
                'value' => '0',
                'type' => 'boolean',
                'group' => 'Hak Akses Petugas'
            ],
            [
                'key' => 'petugas_access_supplies',
                'label' => 'Akses Inventaris & Stok',
                'value' => '0',
                'type' => 'boolean',
                'group' => 'Hak Akses Petugas'
            ],
            [
                'key' => 'petugas_access_financial',
                'label' => 'Akses Matriks Keuangan (Dashboard)',
                'value' => '0',
                'type' => 'boolean',
                'group' => 'Hak Akses Petugas'
            ]
        ];

        foreach ($settings as $setting) {
            DB::table('settings')->updateOrInsert(
                ['key' => $setting['key']],
                $setting
            );
        }
    }

    public function down()
    {
        DB::table('settings')->where('group', 'Hak Akses Petugas')->delete();
    }
};
