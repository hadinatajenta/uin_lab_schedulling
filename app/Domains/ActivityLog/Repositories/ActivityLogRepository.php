<?php

namespace App\Domains\ActivityLog\Repositories;

use App\Domains\ActivityLog\Models\ActivityLog;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ActivityLogRepository implements ActivityLogRepositoryInterface
{
    public function getFilteredLogs(array $filters, int $perPage = 10): LengthAwarePaginator
    {
        $query = ActivityLog::with('user')->latest();

        if (!empty($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        if (!empty($filters['action'])) {
            $query->where('action', $filters['action']);
        }

        if (!empty($filters['keyword'])) {
            $keyword = $filters['keyword'];
            $query->where(function($q) use ($keyword) {
                $q->where('description', 'LIKE', "%{$keyword}%")
                  ->orWhere('ip_address', 'LIKE', "%{$keyword}%");
            });
        }

        if (!empty($filters['date_start'])) {
            $query->whereDate('created_at', '>=', $filters['date_start']);
        }

        if (!empty($filters['date_end'])) {
            $query->whereDate('created_at', '<=', $filters['date_end']);
        }

        return $query->paginate($perPage)->withQueryString();
    }

    public function getDistinctActions(): Collection
    {
        return ActivityLog::select('action')->distinct()->pluck('action');
    }
}
