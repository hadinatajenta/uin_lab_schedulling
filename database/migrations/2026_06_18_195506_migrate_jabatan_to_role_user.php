<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Seed Roles if not exist
        $rolesData = [
            ['name' => 'Admin Lab', 'slug' => 'admin_lab'],
            ['name' => 'Dosen', 'slug' => 'lecturer'],
            ['name' => 'Mahasiswa', 'slug' => 'student'],
            ['name' => 'Asisten Dosen', 'slug' => 'assistant'],
        ];

        foreach ($rolesData as $roleData) {
            DB::table('roles')->updateOrInsert(
                ['slug' => $roleData['slug']],
                ['name' => $roleData['name'], 'created_at' => now(), 'updated_at' => now()]
            );
        }

        $roles = DB::table('roles')->get()->keyBy('slug');

        // 2. Map existing jabatan to role_user
        if (Schema::hasColumn('users', 'jabatan')) {
            $users = DB::table('users')->whereNotNull('jabatan')->get();

            foreach ($users as $user) {
                $jabatan = strtolower(trim($user->jabatan));
                $roleId = null;

                if ($jabatan === 'admin lab') {
                    $roleId = $roles['admin_lab']->id;
                    // Auto-assign Dosen role to Admin Lab
                    DB::table('role_user')->updateOrInsert([
                        'user_id' => $user->id,
                        'role_id' => $roles['lecturer']->id,
                    ], [
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                } elseif ($jabatan === 'dosen') {
                    $roleId = $roles['lecturer']->id;
                } elseif ($jabatan === 'asisten dosen') {
                    $roleId = $roles['assistant']->id;
                    // Auto-assign Mahasiswa role to Asisten Dosen
                    DB::table('role_user')->updateOrInsert([
                        'user_id' => $user->id,
                        'role_id' => $roles['student']->id,
                    ], [
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                } elseif ($jabatan === 'mahasiswa') {
                    $roleId = $roles['student']->id;
                }

                if ($roleId) {
                    DB::table('role_user')->updateOrInsert([
                        'user_id' => $user->id,
                        'role_id' => $roleId,
                    ], [
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            // 3. Drop jabatan column safely
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('jabatan');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasColumn('users', 'jabatan')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('jabatan')->nullable();
            });

            // Very basic rollback, data loss possible
            $roleUsers = DB::table('role_user')->get();
            $roles = DB::table('roles')->get()->keyBy('id');
            
            foreach ($roleUsers as $ru) {
                $role = $roles[$ru->role_id];
                $jabatanStr = 'Mahasiswa';
                if ($role->slug === 'admin_lab') $jabatanStr = 'admin lab';
                elseif ($role->slug === 'lecturer') $jabatanStr = 'dosen';
                elseif ($role->slug === 'assistant') $jabatanStr = 'asisten dosen';

                DB::table('users')->where('id', $ru->user_id)->update(['jabatan' => $jabatanStr]);
            }
        }
    }
};
