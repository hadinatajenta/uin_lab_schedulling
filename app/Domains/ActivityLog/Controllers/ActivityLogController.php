<?php

namespace App\Domains\ActivityLog\Controllers;

use App\Http\Controllers\Controller;
use App\Domains\ActivityLog\Repositories\ActivityLogRepositoryInterface;
use App\Domains\User\Models\User;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    protected ActivityLogRepositoryInterface $activityLogRepository;

    public function __construct(ActivityLogRepositoryInterface $activityLogRepository)
    {
        $this->activityLogRepository = $activityLogRepository;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['user_id', 'action', 'keyword', 'date_start', 'date_end']);
        $perPage = $request->input('per_page', 10);
        
        $logs = $this->activityLogRepository->getFilteredLogs($filters, $perPage);
        
        $selectedUser = null;
        if (!empty($filters['user_id'])) {
            $selectedUser = User::find($filters['user_id']);
        }

        $users = User::orderBy('name')->get(['id', 'name', 'email', 'jabatan']);
        $actions = $this->activityLogRepository->getDistinctActions();

        return view('activity-logs.index', compact('logs', 'users', 'selectedUser', 'actions', 'perPage'));
    }
}
