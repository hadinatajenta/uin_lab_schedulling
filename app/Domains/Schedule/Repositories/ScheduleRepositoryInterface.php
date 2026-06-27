<?php

namespace App\Domains\Schedule\Repositories;

use App\Domains\Schedule\Models\Schedule;
use Illuminate\Pagination\LengthAwarePaginator;

interface ScheduleRepositoryInterface
{
    /**
     * Get paginated schedules with filters.
     *
     * @param array $filters
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getPaginatedSchedules(array $filters, int $perPage = 10): LengthAwarePaginator;

    /**
     * Find schedule by ID.
     *
     * @param int $id
     * @param array $relations
     * @return Schedule
     */
    public function findById(int $id, array $relations = []): Schedule;

    /**
     * Get conflict IDs.
     *
     * @return array
     */
    public function getConflictIds(): array;

    /**
     * Get schedule metrics for a specific date.
     *
     * @param string $date
     * @return array
     */
    public function getMetrics(string $date): array;
}
