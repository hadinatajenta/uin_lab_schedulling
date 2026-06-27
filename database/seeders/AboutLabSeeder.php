<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Domains\AboutLab\Models\AboutLab;

class AboutLabSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AboutLab::create([
            'sop' => 'SOP Standar Laboratorium UIN Raden Intan...',
            'stuktur' => 'Struktur Organisasi Laboratorium UIN Raden Intan...',
        ]);
    }
}
