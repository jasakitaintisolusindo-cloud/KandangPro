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
        Schema::create('supply_transactions', function (Blueprint $create) {
            $create->id();
            $create->foreignId('supply_id')->constrained()->onDelete('cascade');
            $create->enum('tipe', ['Masuk', 'Keluar']);
            $create->decimal('jumlah', 15, 2);
            $create->decimal('harga_satuan', 15, 2)->nullable()->comment('Harga per satuan kecil saat transaksi');
            $create->decimal('total_harga', 15, 2)->nullable();
            $create->date('tanggal');
            $create->date('expired_at')->nullable()->comment('Untuk metode FIFO');
            $create->string('keterangan')->nullable();
            $create->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supply_transactions');
    }
};
