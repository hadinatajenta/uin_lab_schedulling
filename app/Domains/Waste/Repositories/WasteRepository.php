<?php

namespace App\Domains\Waste\Repositories;

use App\Domains\Waste\Models\Waste;
use Illuminate\Pagination\LengthAwarePaginator;

class WasteRepository implements WasteRepositoryInterface
{
    public function getPaginatedWastes(array $filters, int $perPage = 10): LengthAwarePaginator
    {
        $query = Waste::query();

        if (!empty($filters['keyword'])) {
            $keyword = $filters['keyword'];
            $query->where(function($q) use ($keyword) {
                $q->where('nama_limbah', 'like', "%{$keyword}%")
                  ->orWhere('kode_limbah', 'like', "%{$keyword}%");
            });
        }

        if (!empty($filters['kategori'])) {
            $query->where('kategori', $filters['kategori']);
        }

        if (!empty($filters['bahaya'])) {
            $query->whereJsonContains('sifat_bahaya', $filters['bahaya']);
        }

        return $query->latest()->paginate($perPage)->withQueryString();
    }

    public function findById(int $id): Waste
    {
        return Waste::with(['logs.schedule', 'logs' => function($q) {
            $q->latest();
        }])->findOrFail($id);
    }
}
