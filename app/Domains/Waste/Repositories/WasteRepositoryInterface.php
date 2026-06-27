<?php

namespace App\Domains\Waste\Repositories;

use App\Domains\Waste\Models\Waste;
use Illuminate\Pagination\LengthAwarePaginator;

interface WasteRepositoryInterface
{
    /**
     * Get paginated wastes with filters.
     *
     * @param array $filters
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getPaginatedWastes(array $filters, int $perPage = 10): LengthAwarePaginator;

    /**
     * Find a waste by ID.
     *
     * @param int $id
     * @return Waste
     */
    public function findById(int $id): Waste;
}
