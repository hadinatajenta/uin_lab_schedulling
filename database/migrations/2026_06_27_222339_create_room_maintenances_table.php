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
        Schema::create('room_maintenances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ruangan_id')->constrained('ruangan')->cascadeOnDelete();
            $table->enum('type', [
                'sterilisasi',          // Sterilisasi rutin
                'kalibrasi_alat',       // Kalibrasi alat di dalam ruangan
                'renovasi',             // Perbaikan fisik ruangan
                'fumigasi',             // Pengendalian hama
                'perbaikan_fasilitas',  // AC rusak, proyektor error, dll
                'kebakaran',
                'banjir',
                'bencana_alam',
                'lainnya',              // Fleksibel
            ]);
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('start_date');
            $table->date('end_date')->nullable(); // Nullable for emergencies
            $table->enum('status', [
                'scheduled',      // Dijadwalkan tapi belum mulai
                'in_progress',    // Sedang berjalan
                'completed',      // Selesai
                'cancelled',      // Dibatalkan
            ])->default('scheduled');
            $table->boolean('is_emergency')->default(false);
            $table->enum('schedule_action', [
                'none',              // Tidak otomatis lakukan apa-apa pada jadwal
                'auto_suspend',      // Jadwal mendatang di-suspend otomatis (status: ditunda_darurat)
                'auto_cancel',       // Jadwal mendatang dibatalkan otomatis (status: dibatalkan_darurat)
            ])->default('none');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_maintenances');
    }
};
