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
        Schema::create('daily_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coop_id')->constrained('coops')->onDelete('cascade');
            $table->date('tanggal');
            $table->decimal('produksi_telur_kg', 10, 2);
            $table->decimal('harga_telur_per_kg', 10, 2);
            $table->decimal('pakan_kg', 10, 2);
            $table->decimal('harga_pakan_per_kg', 10, 2);
            $table->decimal('biaya_lain_lain', 10, 2);
            $table->text('keterangan')->nullable();
            // Kolom untuk hasil perhitungan otomatis
            $table->decimal('total_pendapatan_telur', 10, 2);
            $table->decimal('total_biaya_pakan', 10, 2);
            $table->decimal('keuntungan_bersih', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_reports');
    }
};
