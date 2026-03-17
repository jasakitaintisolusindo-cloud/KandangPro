<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('supplies', function (Blueprint $create) {
            $create->id();
            $create->string('nama_barang');
            $create->enum('kategori', ['Pakan', 'Obat']);
            $create->string('satuan_besar')->comment('Contoh: Sak, Box');
            $create->string('satuan_kecil')->comment('Contoh: Kg, Botol');
            $create->decimal('konversi', 10, 2);
            $create->decimal('stok_saat_ini', 15, 2);
            $create->decimal('stok_minimal', 15, 2);
            $create->decimal('harga_terakhir', 15, 2)->nullable()->comment('Harga beli terakhir per satuan kecil');
            $create->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplies');
    }
};
