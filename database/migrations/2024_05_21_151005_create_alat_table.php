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
        Schema::create('alat', function (Blueprint $table) {
            $table->id();
            $table->string('nama_alat')->nullable();
            $table->string('jenis_alat')->nullable();
            $table->longText('deskripsi')->nullable();
            $table->longText('spesifikasi')->nullable();
            $table->string('kondisi')->nullable();
            $table->string('gambar')->nullable();
            $table->integer('jumlah_satuan')->nullable();
            $table->integer('jumlah_ml')->nullable();
            $table->longText('cara_penggunaan')->nullable();
            $table->string('link_youtube')->nullable();
            $table->date('tanggal_pembelian')->nullable();
            $table->date('tanggal_expired')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alat');
    }
};
