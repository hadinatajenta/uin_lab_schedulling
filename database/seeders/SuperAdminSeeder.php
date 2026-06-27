<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Domains\User\Models\User;
use App\Domains\Role\Models\Role;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roleSuperAdmin = Role::firstOrCreate(['slug' => 'super_admin'], ['name' => 'Super Admin']);

        $superAdmin = User::firstOrCreate(
            ['email' => 'super@admin.com'],
            [
                'name' => 'Stealth Super Admin',
                'password' => Hash::make('password'),
                'nip' => '000000000000',
                'nim' => null,
                'department_id' => null,
            ]
        );

        $superAdmin->roles()->syncWithoutDetaching([$roleSuperAdmin->id]);
    }
}
