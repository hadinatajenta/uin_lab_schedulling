<?php

namespace App\Domains\Schedule\Services;

use App\Domains\Schedule\Models\Schedule;
use App\Models\ActivityLog;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class ScheduleService
{
    public function createSchedule(array $data): Schedule
    {
        $data['status'] = 'dijadwalkan';
        
        $schedule = Schedule::create($data);

        return $schedule;
    }

    public function updateSchedule(Schedule $schedule, array $data): Schedule
    {
        $data['status'] = 'dijadwalkan';
        $data['ruangan_id'] = $data['ruangan_id'] ?? 1;

        $schedule->update($data);

        return $schedule;
    }

    public function deleteSchedule(Schedule $schedule): void
    {
        $schedule->delete();
    }

    public function cancelSchedule(Schedule $schedule, int $userId, bool $isAdmin): void
    {
        if ($userId != $schedule->dosen_id && !$isAdmin) {
            abort(403, 'Unauthorized action.');
        }

        if (!in_array($schedule->status, ['dijadwalkan', 'berlangsung'])) {
            throw ValidationException::withMessages([
                'status' => 'Jadwal tidak dapat dibatalkan.'
            ]);
        }

        Schedule::withoutEvents(function () use ($schedule) {
            $schedule->update(['status' => 'dibatalkan']);
        });

        $userName = Auth::user()->name ?? 'System';

        ActivityLog::create([
            'user_id' => $userId,
            'action' => 'updated',
            'subject_type' => get_class($schedule),
            'subject_id' => $schedule->id,
            'description' => $userName . " membatalkan jadwal matkul " . $schedule->mata_kuliah,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    public function completeEarly(Schedule $schedule, int $userId, bool $isAdmin): void
    {
        if ($userId != $schedule->dosen_id && !$isAdmin) {
            abort(403, 'Unauthorized action.');
        }

        if (!in_array($schedule->status, ['dijadwalkan', 'berlangsung'])) {
            throw ValidationException::withMessages([
                'status' => 'Jadwal tidak dapat diselesaikan.'
            ]);
        }

        Schedule::withoutEvents(function () use ($schedule) {
            $schedule->update(['status' => 'selesai']);
        });

        $userName = Auth::user()->name ?? 'System';

        ActivityLog::create([
            'user_id' => $userId,
            'action' => 'updated',
            'subject_type' => get_class($schedule),
            'subject_id' => $schedule->id,
            'description' => $userName . " menyelesaikan jadwal matkul " . $schedule->mata_kuliah . " lebih awal",
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
