<?php

namespace App\Domains\Dashboard\Services;

use App\Domains\User\Models\User;
use App\Domains\Equipment\Models\Equipment;
use App\Domains\Borrowing\Models\Borrowing;
use App\Domains\Schedule\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class DashboardService
{
    public function getMetrics(): array
    {
        $today = Carbon::today();

        return [
            'totalPengguna' => User::count(),
            'alatTersedia' => Equipment::where('jenis_alat', 'Alat')->sum('jumlah_satuan'),
            'bahanTersedia' => Equipment::where('jenis_alat', 'Bahan')->count(),
            'peminjamanAktif' => Borrowing::count(),
            'jadwalHariIni' => Schedule::whereDate('tanggal_jadwal', $today)->count(),
            'peminjamanHariIni' => Borrowing::whereDate('tanggal_peminjaman', $today)->count(),
            'pengembalianHariIni' => 0,
            'menungguPersetujuan' => 0,
        ];
    }

    public function getRecentActivities(): Collection
    {
        $recentUsers = User::latest()->take(5)->get()->map(function ($item) {
            $role = 'Mahasiswa';
            if ($item->roles->isNotEmpty()) {
                $role = ucfirst($item->roles->first()->name);
            }

            return [
                'title' => 'Pengguna Baru Terdaftar',
                'description' => "{$item->name} ({$item->email}) terdaftar dengan jabatan " . $role . ".",
                'time' => $item->created_at,
                'icon' => 'users',
                'color' => 'text-emerald-600 bg-emerald-50'
            ];
        });

        $recentLoans = Borrowing::select('peminjaman_alat.*', 'alat.nama_alat')
            ->join('alat', 'peminjaman_alat.alat_id', '=', 'alat.id')
            ->orderBy('peminjaman_alat.created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($item) {
                return [
                    'title' => 'Peminjaman Alat Diajukan',
                    'description' => "Peminjaman alat {$item->nama_alat} diajukan sebanyak {$item->jumlah_dipinjam} unit.",
                    'time' => $item->created_at,
                    'icon' => 'cube',
                    'color' => 'text-emerald-600 bg-emerald-50'
                ];
            });

        $recentAlat = Equipment::latest()->take(5)->get()->map(function ($item) {
            return [
                'title' => $item->jenis_alat === 'Alat' ? 'Alat Baru Ditambahkan' : 'Bahan Baru Ditambahkan',
                'description' => "{$item->nama_alat} ditambahkan ke inventaris " . ($item->jenis_alat === 'Alat' ? 'alat' : 'bahan') . ".",
                'time' => $item->created_at,
                'icon' => 'plus-circle',
                'color' => 'text-zinc-600 bg-zinc-100'
            ];
        });

        // Combine and sort
        $activities = collect()
            ->concat($recentUsers)
            ->concat($recentLoans)
            ->concat($recentAlat)
            ->sortByDesc('time')
            ->take(6);

        // Fallback to dummy data if no activities exist
        if ($activities->isEmpty()) {
            $activities = collect([
                [
                    'title' => 'Sistem Diinisialisasi',
                    'description' => 'Sistem manajemen laboratorium UIN selesai disetup.',
                    'time' => Carbon::now()->subHours(2),
                    'icon' => 'settings',
                    'color' => 'text-zinc-600 bg-zinc-100'
                ],
            ]);
        }

        return $activities;
    }

    public function getUpcomingSchedules(): Collection
    {
        return Schedule::select('jadwal.*', 'ruangan.nama_ruangan')
            ->leftJoin('ruangan', 'jadwal.ruangan_id', '=', 'ruangan.id')
            ->whereDate('tanggal_jadwal', '>=', Carbon::today())
            ->orderBy('tanggal_jadwal', 'asc')
            ->orderBy('waktu_mulai', 'asc')
            ->take(4)
            ->get();
    }
}
