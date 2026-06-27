<?php

namespace App\Domains\User\Repositories;

use App\Domains\User\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

class UserRepository implements UserRepositoryInterface
{
    public function getPaginatedUsers(array $filters, int $perPage = 10): LengthAwarePaginator
    {
        $query = User::query();

        if (!empty($filters['keyword'])) {
            $keyword = $filters['keyword'];
            $query->where(function ($sub) use ($keyword) {
                $sub->where('name', 'LIKE', "%{$keyword}%")
                    ->orWhere('email', 'LIKE', "%{$keyword}%")
                    ->orWhere('nim', 'LIKE', "%{$keyword}%")
                    ->orWhere('nip', 'LIKE', "%{$keyword}%");
            });
        }

        if (!empty($filters['name'])) $query->where('name', 'LIKE', "%{$filters['name']}%");
        if (!empty($filters['email'])) $query->where('email', 'LIKE', "%{$filters['email']}%");
        if (!empty($filters['nim'])) $query->where('nim', 'LIKE', "%{$filters['nim']}%");
        if (!empty($filters['nip'])) $query->where('nip', 'LIKE', "%{$filters['nip']}%");
        
        if (!empty($filters['department_id'])) {
            $query->where('department_id', $filters['department_id']);
        } elseif (!empty($filters['faculty'])) {
            $faculty = $filters['faculty'];
            $query->whereHas('department', function ($q) use ($faculty) {
                $q->where('faculty', $faculty);
            });
        }

        if (!empty($filters['roleSlug'])) {
            $roleSlug = $filters['roleSlug'];
            $mappedSlug = match (strtolower(trim($roleSlug))) {
                'admin lab', 'admin_lab' => 'admin_lab',
                'dosen', 'lecturer' => 'lecturer',
                'asisten dosen', 'assistant' => 'assistant',
                'mahasiswa', 'student' => 'student',
                default => $roleSlug
            };

            $query->whereHas('roles', function ($qRole) use ($mappedSlug) {
                $qRole->where('slug', $mappedSlug);
            });
        }

        // Fast Pagination (Deferred Join)
        $paginator = $query->select('users.id')->latest()->paginate($perPage)->withQueryString();

        if ($paginator->isNotEmpty()) {
            $userIds = $paginator->pluck('id')->toArray();
            
            $usersData = User::with(['roles', 'department'])
                ->whereIn('id', $userIds)
                ->get()
                ->keyBy('id');
            
            $sortedUsers = collect($userIds)->map(fn($id) => $usersData[$id]);
            $paginator->setCollection($sortedUsers);
        }

        return $paginator;
    }

    public function findById(int $id, array $relations = []): User
    {
        return User::with($relations)->findOrFail($id);
    }
}
