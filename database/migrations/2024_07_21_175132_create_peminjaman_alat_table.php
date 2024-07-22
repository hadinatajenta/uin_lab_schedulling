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
        Schema::create('peminjaman_alat', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('alat_id');
            $table->foreign('alat_id')->references('id')->on('alat')->onDelete('cascade');
            $table->date('tanggal_peminjaman')->nullable();
            $table->integer('jumlah_dipinjam')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjaman_alat');
    }
};
