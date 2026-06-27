<?php

namespace App\Domains\Borrowing\Repositories;

use App\Domains\Borrowing\Models\Borrowing;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface BorrowingRepositoryInterface
{
    /**
     * Get paginated borrowings with filters.
     *
     * @param array $filters
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getPaginatedBorrowings(array $filters, int $perPage = 10): LengthAwarePaginator;

    /**
     * Find a borrowing by ID.
     *
     * @param int $id
     * @return Borrowing
     */
    public function findById(int $id): Borrowing;

    /**
     * Get recent active loans for a specific equipment.
     *
     * @param int $alatId
     * @return Collection
     */
    public function getActiveLoansForEquipment(int $alatId): Collection;
}
