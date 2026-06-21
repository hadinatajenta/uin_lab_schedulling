<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
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

        // Ambil jurusan yang ada, kalau belum ada maka disarankan run DepartmentSeeder dulu
        // Ambil 8 jurusan untuk Dosen
        $departments = Department::inRandomOrder()->take(8)->get();
        if ($departments->isEmpty()) {
            $this->command->warn('Tabel departments kosong. Jalankan php artisan db:seed --class=DepartmentSeeder terlebih dahulu.');
            return;
        }

        // 1. Buat 15 data Admin Lab
        for ($i = 0; $i < 15; $i++) {
            $admin = User::create([
                'name' => 'Admin Lab ' . ($i + 1),
                'email' => 'adminlab' . ($i + 1) . '@example.com',
                'password' => $password,
                'phone_number' => $faker->phoneNumber,
                'is_active' => true,
            ]);
            $admin->roles()->attach($roleAdminLab->id);
        }

        // 2. Buat 15 data Dosen dari 8 Jurusan berbeda
        $dosenList = [];
        for ($i = 0; $i < 15; $i++) {
            // Assign ke salah satu dari 8 jurusan tersebut
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

        // 3. Buat 31 data Mahasiswa dari Jurusan berbeda (terhubung dengan dosen2 tersebut)
        $allDepartments = Department::all();
        for ($i = 0; $i < 31; $i++) {
            // Assign ke departemen random
            $department = $allDepartments->random();
            // Assign ke dosen pembimbing random dari daftar dosen
            $supervisor = $dosenList[array_rand($dosenList)];

            $mahasiswa = User::create([
                'name' => $faker->name,
                'email' => 'mahasiswa' . ($i + 1) . '@example.com',
                'password' => $password,
                'phone_number' => $faker->phoneNumber,
                'nim' => $faker->numerify('20##########'),
                'department_id' => $department->id,
                'entry_year' => $faker->numberBetween(2020, 2023),
                'supervisor_id' => $supervisor->id,
                'is_active' => true,
            ]);
            $mahasiswa->roles()->attach($roleMahasiswa->id);
        }
    }
}
