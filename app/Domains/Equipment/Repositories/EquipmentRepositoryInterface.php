<?php

namespace App\Domains\Equipment\Repositories;

use App\Domains\Equipment\Models\Equipment;
use Illuminate\Pagination\LengthAwarePaginator;

interface EquipmentRepositoryInterface
{
    /**
     * Get paginated equipments with filters.
     *
     * @param array $filters
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getPaginatedEquipments(array $filters, int $perPage = 10): LengthAwarePaginator;

    /**
     * Find equipment by ID.
     *
     * @param int $id
     * @return Equipment
     */
    public function findById(int $id): Equipment;

    /**
     * Get the count of materials based on jenis_alat.
     *
     * @param string $jenis
     * @return int
     */
    public function countByJenis(string $jenis): int;

    /**
     * Get the total count of equipments.
     *
     * @return int
     */
    public function countAll(): int;
}
