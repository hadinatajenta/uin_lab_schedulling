<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;
use Illuminate\Support\Str;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'Fakultas Tarbiyah dan Keguruan' => [
                'Pendidikan Agama Islam',
                'Pendidikan Bahasa Arab',
                'Manajemen Pendidikan Islam',
                'Pendidikan Bahasa Inggris',
                'Pendidikan Biologi',
                'Pendidikan Fisika',
                'Bimbingan dan Konseling',
                'Pendidikan Matematika',
                'Pendidikan Anak Usia Dini',
                'Pendidikan Guru Madrasah Ibtidaiyah',
            ],
            'Fakultas Ushuluddin dan Studi Agama' => [
                'Aqidah dan Filsafat Islam',
                'Ilmu Alquran dan Tafsir',
                'Pemikiran Politik Islam',
                'Sosiologi Agama',
                'Studi Agama-Agama',
                'Ilmu Hadist',
            ],
            'Fakultas Syariah' => [
                'Hukum Keluarga (Ahwal Syakhshiyah)',
                'Hukum Tata Negara',
                'Hukum Ekonomi Syariah',
            ],
            'Fakultas Dakwah dan Ilmu Komunikasi' => [
                'Komunikasi dan Penyiaran Islam',
                'Pengembangan Masyarakat Islam',
                'Manajemen Dakwah',
            ],
            'Fakultas Ekonomi dan Bisnis Islam' => [
                'Akuntansi Syariah',
                'Perbankan Syariah',
                'Ekonomi Syariah',
                'Manajemen Bisnis Syariah',
            ],
            'Fakultas Adab' => [
                'Ilmu Perpustakaan dan Informasi Islam',
                'Sejarah Peradaban Islam',
            ],
            'Fakultas Psikologi Islam' => [
                'Psikologi Islam',
                'Tasawuf dan Psikoterapi',
                'Bimbingan dan Konseling Islam',
            ],
            'Fakultas Sains dan Teknologi' => [
                'Biologi',
                'Sistem Informasi',
                'Sains Data',
                'Kimia',
            ],
        ];

        foreach ($data as $faculty => $departments) {
            foreach ($departments as $dept) {
                Department::updateOrCreate(
                    ['name' => $dept],
                    [
                        'code' => Str::slug($dept),
                        'faculty' => $faculty
                    ]
                );
            }
        }
    }
}
