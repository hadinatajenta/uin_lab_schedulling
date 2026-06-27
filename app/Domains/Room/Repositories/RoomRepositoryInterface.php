<?php

namespace App\Domains\Room\Repositories;

use App\Domains\Room\Models\Ruangan;
use Illuminate\Pagination\LengthAwarePaginator;

interface RoomRepositoryInterface
{
    public function getPaginatedRooms(array $filters = [], int $perPage = 10): LengthAwarePaginator;
    public function findById(int $id, array $relations = []): ?Ruangan;
    public function create(array $data): Ruangan;
    public function update(Ruangan $room, array $data): Ruangan;
    public function delete(Ruangan $room): void;
    
    // Domain Specific
    public function hasActiveSchedules(Ruangan $room): bool;
    public function transferSchedules(Ruangan $fromRoom, Ruangan $toRoom): void;
    public function getAvailableRoomsOn(string $date);
}
