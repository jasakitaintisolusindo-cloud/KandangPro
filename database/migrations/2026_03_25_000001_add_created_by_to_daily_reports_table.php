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
            $table->foreignId('created_by')->nullable()->after('status')->constrained('users')->onDelete('set null');
        });
        
        // As a fallback for existing records, we can set them to the first user or leave as null.
        $firstUserId = DB::table('users')->orderBy('id')->value('id');
        if ($firstUserId) {
            DB::table('daily_reports')->whereNull('created_by')->update(['created_by' => $firstUserId]);
        }
    }

    public function down()
    {
        Schema::table('daily_reports', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropColumn('created_by');
        });
    }
};
