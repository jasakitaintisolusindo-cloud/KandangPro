<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::table('daily_reports', function (Blueprint $table) {
            $table->string('foto_produksi')->nullable();
            $table->string('foto_kematian')->nullable();
            $table->enum('status', ['draft', 'approved', 'rejected'])->default('draft');
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->json('audit_logs')->nullable();
        });

        // Set existing records to approved so they remain visible on executive dashboard
        DB::table('daily_reports')->update(['status' => 'approved']);
    }

    public function down()
    {
        Schema::table('daily_reports', function (Blueprint $table) {
            $table->dropForeign(['verified_by']);
            $table->dropColumn([
                'foto_produksi', 
                'foto_kematian', 
                'status', 
                'verified_by', 
                'verified_at', 
                'audit_logs'
            ]);
        });
    }
};
