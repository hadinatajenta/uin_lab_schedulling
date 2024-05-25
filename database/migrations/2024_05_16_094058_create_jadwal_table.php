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
        Schema::create('jadwal', function (Blueprint $table) {
            $table->id();
            $table->integer('ruangan_id');
            $table->string('mata_kuliah');
            $table->date('tanggal_jadwal');
            $table->time('waktu_mulai');
            $table->time('waktu_selesai');
            $table->enum('status', ['selesai', 'diganti', 'dibatalkan']);
            $table->string('dosen');
            $table->string('kelas');
            $table->string('semester');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal');
    }
};
