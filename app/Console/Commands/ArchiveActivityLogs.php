<?php

namespace App\Console\Commands;

use App\Models\ActivityLog;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ArchiveActivityLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'activitylog:archive {--months=6 : Number of months to keep in database}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export old activity logs to JSON and delete them from the database to save space';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $months = $this->option('months');
        $cutoffDate = Carbon::now()->subMonths($months);

        $this->info("Looking for logs older than {$cutoffDate->format('Y-m-d H:i:s')}");

        $oldLogs = ActivityLog::where('created_at', '<', $cutoffDate)->get();

        if ($oldLogs->isEmpty()) {
            $this->info("No logs older than {$months} months found. Nothing to do.");
            return 0;
        }

        $count = $oldLogs->count();
        $this->info("Found {$count} logs to archive.");

        // Create export format
        $exportData = $oldLogs->map(function ($log) {
            return [
                'id' => $log->id,
                'user_id' => $log->user_id,
                'action' => $log->action,
                'subject_type' => $log->subject_type,
                'subject_id' => $log->subject_id,
                'description' => $log->description,
                'old_values' => $log->old_values,
                'new_values' => $log->new_values,
                'ip_address' => $log->ip_address,
                'user_agent' => $log->user_agent,
                'created_at' => $log->created_at->toIso8601String(),
            ];
        })->toJson(JSON_PRETTY_PRINT);

        // Define file path
        $filename = 'activity_logs_archive_' . now()->format('Y_m_d_His') . '.json';
        $path = "archives/logs/{$filename}";

        // Save to storage (local by default, in storage/app/archives/logs/)
        Storage::put($path, $exportData);

        $this->info("Exported logs to storage/app/{$path}");

        // Delete from database
        $deleted = ActivityLog::where('created_at', '<', $cutoffDate)->delete();

        $this->info("Successfully deleted {$deleted} old logs from the database.");
        
        return 0;
    }
}
