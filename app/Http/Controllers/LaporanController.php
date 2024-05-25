<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Alat;
use App\Models\Jadwal;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function laporanView()
    {
        $alat = Alat::all();
        $user = User::all();

        $jadwals = Jadwal::all();

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
        return view('admin.laporan', compact('alat', 'user', 'jadwals', 'totalFormatted'));

    }


}
