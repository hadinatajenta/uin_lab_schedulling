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
        Schema::table('alat', function (Blueprint $table) {
            $table->index('kategori');
            $table->index('status');
        });

        Schema::table('jadwal', function (Blueprint $table) {
            $table->index('tanggal');
            $table->index('dosen_id');
            $table->index('status');
        });

        Schema::table('peminjaman_alat', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('alat_id');
            $table->index('status');
        });

        Schema::table('activity_logs', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('action');
            $table->index('created_at');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('alat', function (Blueprint $table) {
            $table->dropIndex(['kategori']);
            $table->dropIndex(['status']);
        });

        Schema::table('jadwal', function (Blueprint $table) {
            $table->dropIndex(['tanggal']);
            $table->dropIndex(['dosen_id']);
            $table->dropIndex(['status']);
        });

        Schema::table('peminjaman_alat', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['alat_id']);
            $table->dropIndex(['status']);
        });

        Schema::table('activity_logs', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['action']);
            $table->dropIndex(['created_at']);
        });

    }
};
