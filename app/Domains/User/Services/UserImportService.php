<?php

namespace App\Domains\User\Services;

use App\Domains\User\Models\User;
use App\Models\Department;
use App\Models\Role;
use App\Models\ActivityLog;
use App\Jobs\ProcessBulkUsersJob;

class UserImportService
{
    public function validateSingleRow(string $type, array $row): array
    {
        $errors = [];
        
        if (empty($row['name'])) {
            $errors['name'] = 'Nama wajib diisi.';
        }
        
        if (empty($row['email'])) {
            $errors['email'] = 'Email wajib diisi.';
        } elseif (!filter_var($row['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Format email tidak valid.';
        } elseif (User::where('email', $row['email'])->exists()) {
            $errors['email'] = 'Email sudah terdaftar.';
        }

        if (empty($row['department_code'])) {
            $errors['department_code'] = 'Jurusan wajib diisi.';
        } elseif (!Department::where('id', $row['department_code'])->exists()) {
            $errors['department_code'] = 'ID Jurusan tidak ditemukan.';
        }

        if (empty($row['role'])) {
            $errors['role'] = 'Peran wajib diisi.';
        } elseif (!Role::where('slug', $row['role'])->exists()) {
            $errors['role'] = 'Slug peran tidak valid.';
        }

        if ($type === 'staf') {
            if (empty($row['nip'])) {
                $errors['nip'] = 'NIP wajib diisi.';
            } elseif (User::where('nip', $row['nip'])->exists()) {
                $errors['nip'] = 'NIP sudah terdaftar.';
            }
        } else {
            if (empty($row['nim'])) {
                $errors['nim'] = 'NIM wajib diisi.';
            } elseif (User::where('nim', $row['nim'])->exists()) {
                $errors['nim'] = 'NIM sudah terdaftar.';
            }
        }

        return $errors;
    }

    public function processBulk(string $type, array $rows, int $userId): bool
    {
        $validRows = [];
        foreach ($rows as $row) {
            $errors = $this->validateSingleRow($type, $row);
            if (empty($errors)) {
                $validRows[] = $row;
            }
        }

        if (count($validRows) === 0) {
            return false;
        }

        ActivityLog::create([
            'user_id' => $userId,
            'action' => 'import_users',
            'description' => "Memulai proses import " . count($validRows) . " data pengguna ($type)."
        ]);

        ProcessBulkUsersJob::dispatch($validRows, $type, $userId);

        return true;
    }
}
