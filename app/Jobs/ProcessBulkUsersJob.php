<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Models\Role;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\QueryException;

class ProcessBulkUsersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $rows;
    protected $type;
    protected $userId;

    /**
     * Create a new job instance.
     */
    public function __construct(array $rows, string $type, int $userId)
    {
        $this->rows = $rows;
        $this->type = $type;
        $this->userId = $userId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $successCount = 0;
        $failCount = 0;
        $defaultPassword = Hash::make('password123'); // Best practice: uniform default password

        foreach ($this->rows as $row) {
            try {
                DB::transaction(function () use ($row, $defaultPassword) {
                    $user = User::create([
                        'name' => $row['name'],
                        'email' => $row['email'],
                        'phone_number' => $row['phone_number'] ?? null,
                        'nip' => $this->type === 'staf' ? ($row['nip'] ?? null) : null,
                        'nim' => $this->type === 'mahasiswa' ? ($row['nim'] ?? null) : null,
                        'department_id' => $row['department_code'],
                        'entry_year' => $this->type === 'mahasiswa' ? ($row['entry_year'] ?? null) : null,
                        'password' => $defaultPassword,
                        'is_active' => true,
                        'must_change_password' => true, // Force user to change password
                        'created_by' => $this->userId,
                        'updated_by' => $this->userId,
                    ]);

                    if ($this->type === 'mahasiswa' && !empty($row['supervisor_nip'])) {
                        $supervisor = User::where('nip', $row['supervisor_nip'])->first();
                        if ($supervisor) {
                            $user->update(['supervisor_id' => $supervisor->id]);
                        }
                    }

                    $role = Role::where('slug', $row['role'])->first();
                    if ($role) {
                        $user->roles()->attach($role->id);
                    }
                });
                
                $successCount++;
            } catch (QueryException $e) {
                // If it hits a unique constraint violation (e.g. email, nim, nip exists), catch it so the job doesn't crash
                $failCount++;
            } catch (\Exception $e) {
                $failCount++;
            }
        }

        // Log hasil akhir
        $message = "Proses import selesai. Berhasil: $successCount pengguna.";
        if ($failCount > 0) {
            $message .= " Gagal: $failCount pengguna (Kemungkinan duplikat/bentrok data).";
        }

        ActivityLog::create([
            'user_id' => $this->userId,
            'action' => 'import_users_completed',
            'description' => $message
        ]);
    }
}
