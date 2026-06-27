<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Domains\User\Models\User;
use App\Models\Role;
use App\Models\Department;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $password = Hash::make('password');

        // Pastikan role sudah ada
        $roleAdminLab = Role::firstOrCreate(['slug' => 'admin_lab'], ['name' => 'Admin Lab']);
        $roleDosen = Role::firstOrCreate(['slug' => 'lecturer'], ['name' => 'Dosen']);
        $roleMahasiswa = Role::firstOrCreate(['slug' => 'student'], ['name' => 'Mahasiswa']);

        // Ambil jurusan yang ada
        $departments = Department::inRandomOrder()->get();
        if ($departments->isEmpty()) {
            $this->command->warn('Tabel departments kosong. Jalankan php artisan db:seed --class=DepartmentSeeder terlebih dahulu.');
            return;
        }

        // 1. Buat 5 data Admin Lab dengan jurusan
        for ($i = 0; $i < 5; $i++) {
            $department = $departments[$i % $departments->count()];
            $admin = User::create([
                'name' => 'Admin Lab ' . $faker->firstName,
                'email' => 'adminlab' . ($i + 1) . '@example.com',
                'password' => $password,
                'phone_number' => $faker->phoneNumber,
                'department_id' => $department->id,
                'is_active' => true,
            ]);
            $admin->roles()->attach($roleAdminLab->id);
        }

        // 2. Buat 25 data Dosen
        $dosenList = [];
        for ($i = 0; $i < 25; $i++) {
            $department = $departments[$i % $departments->count()];

            $dosen = User::create([
                'name' => 'Dr. ' . $faker->firstName . ' ' . $faker->lastName . ', M.Si.',
                'email' => 'dosen' . ($i + 1) . '@example.com',
                'password' => $password,
                'phone_number' => $faker->phoneNumber,
                'nip' => $faker->numerify('19##########'),
                'department_id' => $department->id,
                'is_active' => true,
            ]);
            $dosen->roles()->attach($roleDosen->id);
            $dosenList[] = $dosen;
        }

        // 3. Buat 25 data Mahasiswa PER Dosen
        foreach ($dosenList as $index => $supervisor) {
            for ($j = 0; $j < 25; $j++) {
                $mahasiswa = User::create([
                    'name' => $faker->name,
                    'email' => 'mahasiswa' . ($index + 1) . '_' . ($j + 1) . '@example.com',
                    'password' => $password,
                    'phone_number' => $faker->phoneNumber,
                    'nim' => $faker->numerify('20##########'),
                    'department_id' => $supervisor->department_id, // samakan dengan jurusan dosen
                    'entry_year' => $faker->numberBetween(2020, 2024),
                    'supervisor_id' => $supervisor->id,
                    'is_active' => true,
                ]);
                $mahasiswa->roles()->attach($roleMahasiswa->id);
            }
        }
    }
}
