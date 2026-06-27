<?php

namespace App\Domains\Room\Services;

use App\Domains\Room\Repositories\RoomRepositoryInterface;
use App\Domains\Room\Models\Ruangan;
use App\Domains\Room\Models\RoomMaintenance;
use App\Domains\Schedule\Models\Schedule;
use Illuminate\Support\Facades\DB;
use App\Domains\ActivityLog\Models\ActivityLog;

class RoomService
{
    public function __construct(
        private RoomRepositoryInterface $roomRepository
    ) {}

    public function createRoom(array $data)
    {
        return DB::transaction(function () use ($data) {
            $room = $this->roomRepository->create($data);
            
            if (isset($data['facilities']) && is_array($data['facilities'])) {
                $room->facilities()->sync($this->formatFacilitiesSyncData($data['facilities']));
            }
            
            return $room;
        });
    }

    public function updateRoom(Ruangan $room, array $data)
    {
        return DB::transaction(function () use ($room, $data) {
            $room = $this->roomRepository->update($room, $data);
            
            if (isset($data['facilities']) && is_array($data['facilities'])) {
                $room->facilities()->sync($this->formatFacilitiesSyncData($data['facilities']));
            }
            
            return $room;
        });
    }

    public function deleteRoom(Ruangan $room)
    {
        // Check strategy 3-tier
        if ($this->roomRepository->hasActiveSchedules($room)) {
            throw new \Exception("Ruangan memiliki jadwal aktif. Harus dipindahkan terlebih dahulu.");
        }
        
        $this->roomRepository->delete($room);
    }
    
    public function deactivateRoom(Ruangan $room)
    {
        $this->roomRepository->update($room, ['is_active' => false]);
    }

    public function scheduleMaintenance(Ruangan $room, array $data)
    {
        return DB::transaction(function () use ($room, $data) {
            $data['ruangan_id'] = $room->id;
            $data['created_by'] = auth()->id();
            $maintenance = RoomMaintenance::create($data);
            
            if ($maintenance->is_emergency) {
                $this->handleEmergencySchedules($room, $maintenance);
                $this->roomRepository->update($room, ['is_active' => false]);
            }
            
            return $maintenance;
        });
    }

    public function completeMaintenance(RoomMaintenance $maintenance, array $data)
    {
        return DB::transaction(function () use ($maintenance, $data) {
            $maintenance->update([
                'status' => 'completed',
                'end_date' => now()->toDateString(),
                'notes' => $data['notes'] ?? null
            ]);
            
            if ($maintenance->is_emergency) {
                // Determine schedule actions for recovery
                $this->handleEmergencyRecovery($maintenance, $data['schedule_recovery'] ?? []);
            }
            
            // Auto open room if no other active maintenance
            $room = $maintenance->room;
            if (!$room->maintenances()->whereIn('status', ['scheduled', 'in_progress'])->exists()) {
                 $this->roomRepository->update($room, ['is_active' => true]);
            }
            
            return $maintenance;
        });
    }

    private function handleEmergencySchedules(Ruangan $room, RoomMaintenance $maintenance)
    {
        if ($maintenance->schedule_action === 'none') {
            return;
        }

        $schedules = $room->schedules()
            ->where('tanggal_jadwal', '>=', $maintenance->start_date)
            ->whereIn('status', ['dijadwalkan', 'berlangsung'])
            ->get();
            
        $newStatus = $maintenance->schedule_action === 'auto_suspend' ? 'ditunda_darurat' : 'dibatalkan_darurat';

        foreach ($schedules as $schedule) {
            $schedule->update(['status' => $newStatus]);
            ActivityLog::create([
                'user_id' => auth()->id() ?? 1,
                'action' => 'updated',
                'subject_type' => Schedule::class,
                'subject_id' => $schedule->id,
                'description' => "Jadwal " . ($newStatus === 'ditunda_darurat' ? 'ditunda' : 'dibatalkan') . " karena darurat ruangan.",
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        }
    }
    
    private function handleEmergencyRecovery(RoomMaintenance $maintenance, array $recoveryData)
    {
        // KELOMPOK A & B processed from controller input in $recoveryData
        // Format of $recoveryData: 
        // [
        //   'expired_schedules' => [
        //       ['id' => 1, 'action' => 'tidak_terlaksana_darurat'],
        //   ],
        //   'active_schedules' => [
        //       ['id' => 2, 'action' => 'resume', 'new_room_id' => null],
        //       ['id' => 3, 'action' => 'transfer', 'new_room_id' => 5],
        //   ]
        // ]
        
        if (isset($recoveryData['expired_schedules'])) {
            foreach ($recoveryData['expired_schedules'] as $expired) {
                $schedule = Schedule::find($expired['id']);
                if ($schedule && $schedule->status === 'ditunda_darurat') {
                    if ($expired['action'] === 'tidak_terlaksana_darurat') {
                        $schedule->update(['status' => 'tidak_terlaksana_darurat']);
                    } else if ($expired['action'] === 'delete') {
                        $schedule->delete();
                    }
                }
            }
        }
        
        if (isset($recoveryData['active_schedules'])) {
             foreach ($recoveryData['active_schedules'] as $active) {
                $schedule = Schedule::find($active['id']);
                if ($schedule && $schedule->status === 'ditunda_darurat') {
                    if ($active['action'] === 'resume') {
                        $schedule->update(['status' => 'dijadwalkan']);
                    } else if ($active['action'] === 'transfer' && !empty($active['new_room_id'])) {
                        $schedule->update([
                            'status' => 'dijadwalkan', 
                            'ruangan_id' => $active['new_room_id']
                        ]);
                    }
                }
            }
        }
    }

    private function formatFacilitiesSyncData(array $facilitiesData)
    {
        $syncData = [];
        foreach ($facilitiesData as $item) {
            $syncData[$item['id']] = [
                'quantity' => $item['quantity'] ?? 1,
                'condition' => $item['condition'] ?? 'baik',
            ];
        }
        return $syncData;
    }
}
