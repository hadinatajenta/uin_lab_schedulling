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
        Schema::create('limbah', function (Blueprint $table) {
            $table->id();
            $table->string('nama_limbah');
            $table->string('bahan');
            $table->text('cara_penggunaan');
            $table->text('materi');
            $table->text('cara_pengolahan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('limbah');
    }
};
