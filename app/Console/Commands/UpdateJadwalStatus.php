<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UpdateJadwalStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jadwal:update-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically update jadwal statuses based on time (dijadwalkan -> berlangsung -> selesai)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = now();
        $today = $now->toDateString();
        $currentTime = $now->format('H:i');

        // 1. dijadwalkan -> berlangsung
        $toOngoing = \App\Domains\Schedule\Models\Schedule::where('tanggal_jadwal', $today)
            ->where('status', 'dijadwalkan')
            ->where('waktu_mulai', '<=', $currentTime)
            ->where('waktu_selesai', '>', $currentTime)
            ->get();

        foreach ($toOngoing as $jadwal) {
            $jadwal->update(['status' => 'berlangsung']);
            $this->logActivity($jadwal, 'berlangsung');
        }

        // 2. berlangsung (atau dijadwalkan tapi sudah lewat) -> selesai
        $toCompleted = \App\Domains\Schedule\Models\Schedule::where('tanggal_jadwal', $today)
            ->whereIn('status', ['dijadwalkan', 'berlangsung'])
            ->where('waktu_selesai', '<=', $currentTime)
            ->get();

        foreach ($toCompleted as $jadwal) {
            $jadwal->update(['status' => 'selesai']);
            $this->logActivity($jadwal, 'selesai');
        }

        $this->info("Updated {$toOngoing->count()} to berlangsung, and {$toCompleted->count()} to selesai.");
    }

    private function logActivity($jadwal, $newStatus)
    {
        \App\Models\ActivityLog::create([
            'user_id' => null, // System
            'action' => 'updated',
            'subject_type' => get_class($jadwal),
            'subject_id' => $jadwal->id,
            'description' => "Sistem mengubah status jadwal matkul {$jadwal->mata_kuliah} menjadi {$newStatus}",
            'ip_address' => 'System',
            'user_agent' => 'Cron Job',
        ]);
    }
}
