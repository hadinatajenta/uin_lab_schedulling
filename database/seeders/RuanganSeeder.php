<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Domains\Room\Models\Ruangan;

class RuanganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ruangans = [
            ['nama_ruangan' => 'Lab Biologi Dasar', 'ketersediaan' => true, 'kapasitas' => 40, 'fasilitas' => 'Mikroskop, Proyektor'],
            ['nama_ruangan' => 'Lab Mikrobiologi', 'ketersediaan' => true, 'kapasitas' => 30, 'fasilitas' => 'Inkubator, Autoklaf, Mikroskop'],
            ['nama_ruangan' => 'Lab Botani', 'ketersediaan' => true, 'kapasitas' => 35, 'fasilitas' => 'Herbarium, Proyektor'],
            ['nama_ruangan' => 'Lab Ekologi', 'ketersediaan' => true, 'kapasitas' => 30, 'fasilitas' => 'Alat Sampling, Proyektor'],
        ];

        foreach ($ruangans as $r) {
            Ruangan::create($r);
        }
    }
}
