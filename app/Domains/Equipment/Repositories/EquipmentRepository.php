<?php

namespace App\Domains\Equipment\Repositories;

use App\Domains\Equipment\Models\Equipment;
use Illuminate\Pagination\LengthAwarePaginator;

class EquipmentRepository implements EquipmentRepositoryInterface
{
    public function getPaginatedEquipments(array $filters, int $perPage = 10): LengthAwarePaginator
    {
        $query = Equipment::query();

        if (!empty($filters['cari'])) {
            $query->where('nama_alat', 'LIKE', "%{$filters['cari']}%");
        }
        if (!empty($filters['jenis_alat'])) {
            $query->where('jenis_alat', $filters['jenis_alat']);
        }
        
        return $query->paginate($perPage)->withQueryString();
    }

    public function findById(int $id): Equipment
    {
        return Equipment::findOrFail($id);
    }

    public function countByJenis(string $jenis): int
    {
        return Equipment::where('jenis_alat', $jenis)->count();
    }

    public function countAll(): int
    {
        return Equipment::count();
    }
}
