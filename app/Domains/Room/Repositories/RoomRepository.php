<?php

namespace App\Domains\Room\Repositories;

use App\Domains\Room\Models\Ruangan;
use App\Domains\Schedule\Models\Schedule;
use Illuminate\Pagination\LengthAwarePaginator;

class RoomRepository implements RoomRepositoryInterface
{
    public function getPaginatedRooms(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        $query = Ruangan::query()->with(['pic', 'facilities', 'maintenances' => function($q) {
            $q->whereIn('status', ['scheduled', 'in_progress']);
        }]);

        if (!empty($filters['search'])) {
            $query->where(function($q) use ($filters) {
                $q->where('nama_ruangan', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('room_code', 'like', '%' . $filters['search'] . '%');
            });
        }

        return $query->paginate($perPage);
    }

    public function findById(int $id, array $relations = []): ?Ruangan
    {
        return Ruangan::with($relations)->findOrFail($id);
    }

    public function create(array $data): Ruangan
    {
        return Ruangan::create($data);
    }

    public function update(Ruangan $room, array $data): Ruangan
    {
        $room->update($data);
        return $room;
    }

    public function delete(Ruangan $room): void
    {
        $room->delete();
    }

    public function hasActiveSchedules(Ruangan $room): bool
    {
        return $room->schedules()->where('tanggal_jadwal', '>=', now()->toDateString())->whereIn('status', ['dijadwalkan', 'berlangsung'])->exists();
    }

    public function transferSchedules(Ruangan $fromRoom, Ruangan $toRoom): void
    {
        $fromRoom->schedules()
            ->where('tanggal_jadwal', '>=', now()->toDateString())
            ->whereIn('status', ['dijadwalkan', 'berlangsung', 'ditunda_darurat'])
            ->update([
                'ruangan_id' => $toRoom->id,
                'status' => 'dijadwalkan' // Assuming transferred schedules resume normal status
            ]);
    }

    public function getAvailableRoomsOn(string $date)
    {
        // Get all rooms that don't have maintenance on the given date and are active
        return Ruangan::where('is_active', true)
            ->whereDoesntHave('maintenances', function ($query) use ($date) {
                $query->whereIn('status', ['scheduled', 'in_progress'])
                      ->where('start_date', '<=', $date)
                      ->where(function($q) use ($date) {
                          $q->where('end_date', '>=', $date)
                            ->orWhereNull('end_date');
                      });
            })->get();
    }
}
