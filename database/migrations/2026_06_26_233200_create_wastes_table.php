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
        Schema::create('wastes', function (Blueprint $table) {
            $table->id();
            $table->string('kode_limbah')->unique();
            $table->string('nama_limbah');
            $table->enum('kategori', ['Padat', 'Cair', 'Gas', 'Infeksius']);
            $table->json('sifat_bahaya')->nullable();
            $table->string('gambar_panduan')->nullable();
            $table->text('cara_penanganan')->nullable();
            $table->text('prosedur_darurat')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wastes');
    }
};
