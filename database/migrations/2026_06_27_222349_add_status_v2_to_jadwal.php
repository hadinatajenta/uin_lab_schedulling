<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('jadwal', function (Blueprint $table) {
            $table->string('status_v2')->default('dijadwalkan')->after('status');
        });

        // Copy data
        \Illuminate\Support\Facades\DB::statement("UPDATE jadwal SET status_v2 = status");

        Schema::table('jadwal', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('jadwal', function (Blueprint $table) {
            $table->renameColumn('status_v2', 'status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jadwal', function (Blueprint $table) {
            $table->string('status_v2')->default('dijadwalkan')->after('status');
        });

        \Illuminate\Support\Facades\DB::statement("UPDATE jadwal SET status_v2 = status");

        Schema::table('jadwal', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('jadwal', function (Blueprint $table) {
            $table->renameColumn('status_v2', 'status');
        });
    }
};
