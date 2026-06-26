<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::with('user')->latest();

        // 1. Filter by User
        $selectedUser = null;
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
            $selectedUser = User::find($request->user_id);
        }

        // 2. Filter by Action Type
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // 3. Keyword Search (Description or IP)
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function($q) use ($keyword) {
                $q->where('description', 'LIKE', "%{$keyword}%")
                  ->orWhere('ip_address', 'LIKE', "%{$keyword}%");
            });
        }

        // 4. Date Filters
        if ($request->filled('date_start')) {
            $query->whereDate('created_at', '>=', $request->date_start);
        }

        if ($request->filled('date_end')) {
            $query->whereDate('created_at', '<=', $request->date_end);
        }

        // 5. Dynamic Pagination
        $perPage = $request->input('per_page', 10);
        $logs = $query->paginate($perPage)->withQueryString();
        
        $users = User::orderBy('name')->get(['id', 'name', 'email', 'jabatan']);

        // Collect available actions for the filter dropdown
        $actions = ActivityLog::select('action')->distinct()->pluck('action');

        return view('activity-logs.index', compact('logs', 'users', 'selectedUser', 'actions', 'perPage'));
    }
}
