<?php

namespace App\Domains\User\Repositories;

use Illuminate\Pagination\LengthAwarePaginator;
use App\Domains\User\Models\User;

interface UserRepositoryInterface
{
    /**
     * Get paginated users with filters.
     *
     * @param array $filters
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getPaginatedUsers(array $filters, int $perPage = 10): LengthAwarePaginator;

    /**
     * Find user by ID with eager loading.
     *
     * @param int $id
     * @param array $relations
     * @return User
     */
    public function findById(int $id, array $relations = []): User;
}
