<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

trait LogsActivity
{
    public static function bootLogsActivity()
    {
        static::created(function ($model) {
            $model->logActivity('created');
        });

        static::updated(function ($model) {
            $model->logActivity('updated');
        });

        static::deleted(function ($model) {
            $model->logActivity('deleted');
        });
    }

    protected function logActivity($action)
    {
        // Don't log if running in console (e.g. seeders) unless we want to
        if (app()->runningInConsole() && !app()->runningUnitTests()) {
            return;
        }

        $oldValues = [];
        $newValues = [];

        if ($action === 'updated') {
            $newValues = $this->getDirty();
            $oldValues = array_intersect_key($this->getOriginal(), $newValues);
        } elseif ($action === 'created') {
            $newValues = $this->getAttributes();
        } elseif ($action === 'deleted') {
            $oldValues = $this->getAttributes();
        }

        $description = "{$action} " . class_basename($this);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'subject_type' => get_class($this),
            'subject_id' => $this->id,
            'description' => ucfirst($description),
            'old_values' => empty($oldValues) ? null : $oldValues,
            'new_values' => empty($newValues) ? null : $newValues,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
