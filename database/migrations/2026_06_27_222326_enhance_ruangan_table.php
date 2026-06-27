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
        Schema::table('ruangan', function (Blueprint $table) {
            $table->string('room_code')->unique()->after('id')->nullable(); // nullable temporarily for existing rows
            $table->text('description')->nullable()->after('nama_ruangan');
            $table->string('building')->nullable()->after('description');
            $table->string('floor')->nullable()->after('building');
            $table->string('photo')->nullable()->after('kapasitas');
            $table->foreignId('pic_user_id')->nullable()->constrained('users')->nullOnDelete()->after('photo');
            $table->softDeletes();
            $table->renameColumn('ketersediaan', 'is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ruangan', function (Blueprint $table) {
            $table->renameColumn('is_active', 'ketersediaan');
            $table->dropSoftDeletes();
            $table->dropForeign(['pic_user_id']);
            $table->dropColumn(['room_code', 'description', 'building', 'floor', 'photo', 'pic_user_id']);
        });
    }
};
