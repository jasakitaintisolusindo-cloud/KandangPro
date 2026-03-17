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
        Schema::table('coops', function (Blueprint $table) {
            if (!Schema::hasColumn('coops', 'stok_pakan_kg')) {
                $table->decimal('stok_pakan_kg', 10, 2)->nullable();
            }
            if (!Schema::hasColumn('coops', 'target_hdp')) {
                $table->decimal('target_hdp', 5, 2)->nullable();
            }
            if (!Schema::hasColumn('coops', 'populasi_saat_ini')) {
                $table->integer('populasi_saat_ini')->nullable();
            }
        });

        // Initialize populasi_saat_ini from populasi_awal for existing records
        \DB::table('coops')->update(['populasi_saat_ini' => \DB::raw('populasi_awal')]);

        Schema::table('daily_reports', function (Blueprint $table) {
            if (!Schema::hasColumn('daily_reports', 'jumlah_telur_butir')) {
                $table->integer('jumlah_telur_butir')->default(0);
            }
            if (!Schema::hasColumn('daily_reports', 'jumlah_kematian')) {
                $table->integer('jumlah_kematian')->default(0);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('coops', function (Blueprint $table) {
            $table->dropColumn(['stok_pakan_kg', 'target_hdp', 'populasi_saat_ini']);
        });

        Schema::table('daily_reports', function (Blueprint $table) {
            $table->dropColumn(['jumlah_telur_butir', 'jumlah_kematian']);
        });
    }
};
