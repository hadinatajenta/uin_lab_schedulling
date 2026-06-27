<?php

namespace App\Domains\Borrowing\Repositories;

use App\Domains\Borrowing\Models\Borrowing;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class BorrowingRepository implements BorrowingRepositoryInterface
{
    public function getPaginatedBorrowings(array $filters, int $perPage = 10): LengthAwarePaginator
    {
        $query = Borrowing::query();

        // Implement filters here if needed
        
        return $query->paginate($perPage)->withQueryString();
    }

    public function findById(int $id): Borrowing
    {
        return Borrowing::findOrFail($id);
    }

    public function getActiveLoansForEquipment(int $alatId): Collection
    {
        return Borrowing::where('alat_id', $alatId)->get();
    }
}
