<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Jadwal;
use App\Models\Ruangan;
use App\Models\User;
use Faker\Factory as Faker;
use Carbon\Carbon;

class JadwalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $ruangans = Ruangan::pluck('id')->toArray();
        $dosens = User::whereHas('roles', function($q) {
            $q->where('slug', 'lecturer');
        })->pluck('id')->toArray();

        if (empty($ruangans) || empty($dosens)) {
            $this->command->warn('Tabel ruangan atau dosen kosong. Pastikan RuanganSeeder dan UserSeeder dijalankan lebih dulu.');
            return;
        }

        $statuses = ['dijadwalkan', 'berlangsung', 'selesai', 'dibatalkan'];
        $mataKuliah = ['Biologi Umum', 'Mikrobiologi Dasar', 'Fisiologi Tumbuhan', 'Ekologi Hewan', 'Genetika', 'Biokimia', 'Bioteknologi'];
        $kelasOptions = ['A', 'B', 'C', 'D'];

        foreach ($statuses as $status) {
            for ($i = 0; $i < 15; $i++) {
                $tanggal = Carbon::now()->addDays($faker->numberBetween(-30, 30));
                
                // Jika status selesai/dibatalkan, buat tanggal di masa lalu. 
                // Jika dijadwalkan, buat di masa depan.
                // Jika berlangsung, buat hari ini.
                if ($status == 'selesai' || $status == 'dibatalkan') {
                    $tanggal = Carbon::now()->subDays($faker->numberBetween(1, 30));
                } elseif ($status == 'dijadwalkan') {
                    $tanggal = Carbon::now()->addDays($faker->numberBetween(1, 30));
                } else {
                    $tanggal = Carbon::now();
                }

                $waktuMulai = Carbon::createFromTime($faker->numberBetween(8, 14), 0, 0);
                $waktuSelesai = (clone $waktuMulai)->addHours(2);

                Jadwal::create([
                    'ruangan_id' => $faker->randomElement($ruangans),
                    'mata_kuliah' => $faker->randomElement($mataKuliah),
                    'submateri' => 'Praktikum Modul ' . $faker->numberBetween(1, 10),
                    'tanggal_jadwal' => $tanggal->toDateString(),
                    'waktu_mulai' => $waktuMulai->format('H:i:s'),
                    'waktu_selesai' => $waktuSelesai->format('H:i:s'),
                    'status' => $status,
                    'dosen_id' => $faker->randomElement($dosens),
                    'kelas' => $faker->randomElement($kelasOptions),
                    'semester' => $faker->numberBetween(1, 8),
                ]);
            }
        }
    }
}
