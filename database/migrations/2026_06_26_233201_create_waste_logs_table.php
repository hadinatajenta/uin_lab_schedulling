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
        Schema::create('waste_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('waste_id')->constrained('wastes')->onDelete('cascade');
            $table->foreignId('schedule_id')->constrained('jadwal')->onDelete('cascade');
            $table->decimal('jumlah_volume', 8, 2);
            $table->enum('satuan', ['Liter', 'Kg', 'Gram', 'ml']);
            $table->text('catatan')->nullable();
            $table->enum('status', ['Ditampung', 'Diolah', 'Diserahkan'])->default('Ditampung');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('waste_logs');
    }
};
