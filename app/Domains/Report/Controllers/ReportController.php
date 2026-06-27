<?php

namespace App\Domains\Report\Controllers;

use App\Http\Controllers\Controller;
use App\Domains\Equipment\Models\Equipment;
use App\Domains\Schedule\Models\Schedule;
use App\Domains\User\Models\User;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function laporanView()
    {
        $alat = Equipment::all();
        $user = User::all();

        $jadwals = Schedule::all();
        $alatRusakHabis = Equipment::where('kondisi', 'Rusak')->orWhere('kondisi', 'Habis')->get();

        $totalWaktu = Carbon::createFromTime(0, 0, 0);

        // Iterasi setiap jadwal
        foreach ($jadwals as $jadwal) {
            // Ambil waktu mulai dan waktu selesai
            $waktuMulai = Carbon::parse($jadwal->waktu_mulai);
            $waktuSelesai = Carbon::parse($jadwal->waktu_selesai);

            // Hitung selisih waktu dan tambahkan ke total waktu
            $selisihWaktu = $waktuMulai->diff($waktuSelesai);
            $totalWaktu->addSeconds($selisihWaktu->s)
                ->addMinutes($selisihWaktu->i)
                ->addHours($selisihWaktu->h);
        }

        // Format total waktu sebagai HH:MM:SS
        $totalFormatted = $totalWaktu->format('H:i:s');
        return view('reports.index', compact('alat', 'user', 'jadwals', 'totalFormatted', 'alatRusakHabis'));

    }


}
