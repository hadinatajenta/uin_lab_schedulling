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
            $table->foreignId('dosen_id')->nullable()->constrained('users')->onDelete('cascade');
        });

        // Map existing dosen strings to user ids
        $jadwals = \App\Models\Jadwal::all();
        foreach ($jadwals as $jadwal) {
            $user = \App\Models\User::where('name', $jadwal->dosen)->first();
            if ($user) {
                $jadwal->dosen_id = $user->id;
                $jadwal->saveQuietly();
            }
        }

        Schema::table('jadwal', function (Blueprint $table) {
            $table->dropColumn('dosen');
        });
    }

    public function down(): void
    {
        Schema::table('jadwal', function (Blueprint $table) {
            $table->string('dosen')->nullable();
        });

        $jadwals = \App\Models\Jadwal::all();
        foreach ($jadwals as $jadwal) {
            $user = \App\Models\User::find($jadwal->dosen_id);
            if ($user) {
                $jadwal->dosen = $user->name;
                $jadwal->saveQuietly();
            }
        }

        Schema::table('jadwal', function (Blueprint $table) {
            $table->dropForeign(['dosen_id']);
            $table->dropColumn('dosen_id');
        });
    }
};
