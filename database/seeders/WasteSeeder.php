<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Waste;
use App\Models\WasteLog;
use App\Models\Jadwal;
use Faker\Factory as Faker;

class WasteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // Lists for generating realistic sounding lab wastes
        $prefixes = ['Sisa larutan', 'Pelarut bekas', 'Limbah padat', 'Residu', 'Ekstrak', 'Endapan', 'Reagen sisa', 'Campuran kimia', 'Bahan', 'Limbah cair'];
        $chemicals = ['Asam Asetat', 'Etanol', 'Kloroform', 'Natrium Hidroksida', 'Timbal(II) Nitrat', 'Aseton', 'Formalin', 'Benzena', 'Fenol', 'Amonia', 'Merkuri', 'Asam Sulfat', 'Metanol', 'Diklorometana', 'Toluena', 'Asam Klorida', 'Kalsium Karbonat', 'Perak Nitrat', 'Kalium Permanganat', 'Zink Sulfat'];
        $suffixes = ['Terkontaminasi', 'Kadaluarsa', 'Sisa Praktikum Kimia Dasar', 'Bekas Analisis', 'Campuran', 'Konsentrasi Tinggi', 'Sisa Uji Ekstraksi', 'Bekas Kalibrasi', 'Sisa Reaksi'];
        
        $kategoriList = ['Padat', 'Cair', 'Gas', 'Infeksius'];
        
        $sifatBahayaList = [
            'Beracun', 'Korosif', 'Mudah Terbakar', 'Mudah Meledak', 
            'Iritan', 'Karsinogenik', 'Berbahaya bagi Lingkungan', 'Oksidator'
        ];

        $jadwals = Jadwal::all();
        $statuses = ['Ditampung', 'Diolah', 'Diserahkan'];

        $wastes = [];

        for ($i = 1; $i <= 100; $i++) {
            $kategori = $kategoriList[array_rand($kategoriList)];
            
            // Bias the names based on category
            if ($kategori === 'Cair') {
                $prefix = $faker->randomElement(['Sisa larutan', 'Pelarut bekas', 'Limbah cair', 'Campuran kimia']);
            } elseif ($kategori === 'Padat' || $kategori === 'Infeksius') {
                $prefix = $faker->randomElement(['Limbah padat', 'Residu', 'Endapan', 'Bahan', 'Pecahan Kaca']);
            } else {
                $prefix = $faker->randomElement(['Gas sisa', 'Uap beracun', 'Residu aerosol']);
            }

            $namaLimbah = $prefix . ' ' . $faker->randomElement($chemicals) . ' ' . $faker->randomElement($suffixes);

            // Generate 1-3 random sifat bahaya
            $sifatBahaya = $faker->randomElements($sifatBahayaList, rand(1, 3));

            $waste = Waste::create([
                'kode_limbah' => 'LMB-' . strtoupper($faker->bothify('??###')) . '-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'nama_limbah' => $namaLimbah,
                'kategori' => $kategori,
                'sifat_bahaya' => $sifatBahaya,
                'gambar_panduan' => null,
                'cara_penanganan' => $faker->sentence(10) . ' ' . $faker->sentence(8),
                'prosedur_darurat' => $faker->sentence(12),
            ]);

            $wastes[] = $waste;

            // Generate logs for this waste
            if ($jadwals->count() > 0) {
                $logCount = rand(1, 5); // 1 to 5 logs per waste
                for ($j = 0; $j < $logCount; $j++) {
                    $randomJadwal = $jadwals->random();
                    
                    if ($kategori === 'Cair' || $kategori === 'Gas') {
                        $satuan = $faker->randomElement(['Liter', 'ml']);
                        $jumlahVolume = $satuan === 'ml' ? rand(50, 1000) : rand(1, 15) + (rand(0, 9) / 10);
                    } else {
                        $satuan = $faker->randomElement(['Kg', 'Gram']);
                        $jumlahVolume = $satuan === 'Gram' ? rand(10, 500) : rand(1, 10) + (rand(0, 9) / 10);
                    }

                    WasteLog::create([
                        'waste_id' => $waste->id,
                        'schedule_id' => $randomJadwal->id,
                        'jumlah_volume' => $jumlahVolume,
                        'satuan' => $satuan,
                        'catatan' => $faker->sentence(6),
                        'status' => $statuses[array_rand($statuses)],
                        'created_at' => $faker->dateTimeBetween('-1 year', 'now'),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }
}
