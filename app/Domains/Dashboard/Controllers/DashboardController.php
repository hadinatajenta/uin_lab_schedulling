<?php

namespace App\Domains\Dashboard\Controllers;

use App\Http\Controllers\Controller;
use App\Domains\Dashboard\Services\DashboardService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected DashboardService $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function index(Request $request)
    {
        $metrics = $this->dashboardService->getMetrics();
        $activities = $this->dashboardService->getRecentActivities();
        $upcomingSchedules = $this->dashboardService->getUpcomingSchedules();
        $insights = $this->dashboardService->getActionableInsights();

        $totalPengguna = $metrics['totalPengguna'];
        $alatTersedia = $metrics['alatTersedia'];
        $bahanTersedia = $metrics['bahanTersedia'];
        $peminjamanAktif = $metrics['peminjamanAktif'];
        $jadwalHariIni = $metrics['jadwalHariIni'];
        $peminjamanHariIni = $metrics['peminjamanHariIni'];
        $pengembalianHariIni = $metrics['pengembalianHariIni'];
        $menungguPersetujuan = $metrics['menungguPersetujuan'];

        return view('dashboard.index', compact(
            'totalPengguna',
            'alatTersedia',
            'bahanTersedia',
            'peminjamanAktif',
            'activities',
            'jadwalHariIni',
            'peminjamanHariIni',
            'pengembalianHariIni',
            'menungguPersetujuan',
            'upcomingSchedules',
            'insights'
        ));
    }
}
