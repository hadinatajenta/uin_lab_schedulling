<?php

namespace App\Domains\ActivityLog\Repositories;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface ActivityLogRepositoryInterface
{
    /**
     * Get paginated and filtered activity logs.
     *
     * @param array $filters
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getFilteredLogs(array $filters, int $perPage = 10): LengthAwarePaginator;

    /**
     * Get all unique action types for dropdown filter.
     *
     * @return Collection
     */
    public function getDistinctActions(): Collection;
}
