<?php

namespace Database\Seeders;

use App\Domains\User\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            DepartmentSeeder::class,
            UserSeeder::class,
            RuanganSeeder::class,
            JadwalSeeder::class,
            AboutLabSeeder::class,
            WasteSeeder::class,
            SuperAdminSeeder::class,
        ]);
    }
}
