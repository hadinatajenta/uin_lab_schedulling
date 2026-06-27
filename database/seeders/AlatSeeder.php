<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Domains\Equipment\Models\Equipment;
use Faker\Factory as Faker;
use Carbon\Carbon;

class AlatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // 1. Buat 50 data Alat
        $namaAlatOptions = ['Mikroskop Cahaya', 'Autoklaf', 'Inkubator', 'Oven Laboratorium', 'Timbangan Analitik', 'Centrifuge', 'Spektrofotometer', 'pH Meter', 'Mikropipet', 'Water Bath', 'Laminar Air Flow', 'Hot Plate Stirrer', 'Vortex Mixer', 'Desikator', 'Lemari Asam'];
        $kondisiOptions = ['Baik', 'Rusak Ringan', 'Rusak Berat'];

        for ($i = 0; $i < 50; $i++) {
            Alat::create([
                'nama_alat' => $faker->randomElement($namaAlatOptions) . ' Model ' . $faker->bothify('??-###'),
                'jenis_alat' => 'Alat',
                'deskripsi' => $faker->sentence(10),
                'spesifikasi' => 'Merek: ' . $faker->company . ', Tegangan: 220V, Dimensi: ' . $faker->numberBetween(10, 50) . 'x' . $faker->numberBetween(10, 50) . ' cm',
                'kondisi' => $faker->randomElement($kondisiOptions),
                'gambar' => null, // Optional
                'jumlah_satuan' => $faker->numberBetween(1, 20),
                'jumlah_ml' => null,
                'cara_penggunaan' => $faker->paragraph(3),
                'link_youtube' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', // Dummy link
                'tanggal_pembelian' => Carbon::now()->subDays($faker->numberBetween(100, 1000))->toDateString(),
                'tanggal_expired' => null, // Alat umumnya tidak punya expired
            ]);
        }

        // 2. Buat 50 data Bahan
        $namaBahanOptions = ['Alkohol 70%', 'Aquadest', 'NaCl 0.9%', 'Asam Sulfat (H2SO4)', 'Natrium Hidroksida (NaOH)', 'Glukosa', 'Agar Nutrient', 'Etanol 96%', 'Formalin', 'Iodium', 'Aseton', 'Buffer pH 7', 'Reagen Benedict', 'Metilen Biru', 'Asam Klorida (HCl)'];

        for ($i = 0; $i < 50; $i++) {
            Alat::create([
                'nama_alat' => $faker->randomElement($namaBahanOptions) . ' ' . $faker->lexify('?????'),
                'jenis_alat' => 'Bahan',
                'deskripsi' => 'Bahan kimia cair/padat untuk keperluan praktikum.',
                'spesifikasi' => 'Konsentrasi: ' . $faker->randomElement(['Pro Analis (PA)', 'Teknis', '10%', '1 N', '0.1 M']) . ', Produsen: ' . $faker->company,
                'kondisi' => 'Baik', // Bahan biasa diasumsikan baik kecuali expired
                'gambar' => null,
                'jumlah_satuan' => null, // Bahan dihitung dalam ml atau gram (kita set ml)
                'jumlah_ml' => $faker->numberBetween(100, 5000),
                'cara_penggunaan' => 'Gunakan sesuai petunjuk MSDS.',
                'link_youtube' => null,
                'tanggal_pembelian' => Carbon::now()->subDays($faker->numberBetween(10, 200))->toDateString(),
                'tanggal_expired' => Carbon::now()->addDays($faker->numberBetween(30, 700))->toDateString(),
            ]);
        }
    }
}
