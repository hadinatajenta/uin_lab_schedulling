<?php

namespace App\Domains\Schedule\Repositories;

use App\Domains\Schedule\Models\Schedule;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class ScheduleRepository implements ScheduleRepositoryInterface
{
    public function getPaginatedSchedules(array $filters, int $perPage = 10): LengthAwarePaginator
    {
        $query = Schedule::with('dosen');

        if (!empty($filters['keyword'])) {
            $keyword = $filters['keyword'];
            $query->where(function ($q) use ($keyword) {
                $q->where('mata_kuliah', 'LIKE', "%{$keyword}%")
                    ->orWhere('kelas', 'LIKE', "%{$keyword}%")
                    ->orWhereHas('dosen', function ($q2) use ($keyword) {
                        $q2->where('name', 'LIKE', "%{$keyword}%");
                    });
            });
        }

        if (!empty($filters['date'])) {
            $query->where('tanggal_jadwal', $filters['date']);
        }

        $conflicts = $this->getConflictIds();

        if (!empty($filters['status'])) {
            if ($filters['status'] === 'konflik') {
                $query->whereIn('id', $conflicts);
            } else {
                $query->where('status', $filters['status']);
            }
        }

        return $query->orderBy('tanggal_jadwal', 'desc')
            ->orderBy('waktu_mulai', 'asc')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function findById(int $id, array $relations = []): Schedule
    {
        return Schedule::with($relations)->findOrFail($id);
    }

    public function getConflictIds(): array
    {
        return DB::table('jadwal as a')
            ->join('jadwal as b', function ($join) {
                $join->on('a.tanggal_jadwal', '=', 'b.tanggal_jadwal')
                    ->on('a.ruangan_id', '=', 'b.ruangan_id')
                    ->whereRaw('a.id != b.id')
                    ->whereRaw('a.waktu_mulai < b.waktu_selesai')
                    ->whereRaw('a.waktu_selesai > b.waktu_mulai');
            })
            ->pluck('a.id')
            ->unique()
            ->toArray();
    }

    public function getMetrics(string $date): array
    {
        $totalToday = Schedule::where('tanggal_jadwal', $date)->count();
        $totalLabs = \App\Domains\Room\Models\Ruangan::count();
        $activeLabsToday = Schedule::where('tanggal_jadwal', $date)->distinct('ruangan_id')->count('ruangan_id');
        $availableLabs = max(0, $totalLabs - $activeLabsToday);
        $conflicts = $this->getConflictIds();

        return [
            'totalToday' => $totalToday,
            'availableLabs' => $availableLabs,
            'totalConflicts' => count($conflicts),
            'conflicts' => $conflicts
        ];
    }
}
