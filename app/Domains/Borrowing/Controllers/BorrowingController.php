<?php

namespace App\Domains\Borrowing\Controllers;

use App\Http\Controllers\Controller;
use App\Domains\Borrowing\Repositories\BorrowingRepositoryInterface;
use App\Domains\Borrowing\Services\BorrowingService;
use App\Domains\Borrowing\Models\Borrowing;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BorrowingController extends Controller
{
    protected BorrowingRepositoryInterface $borrowingRepository;
    protected BorrowingService $borrowingService;

    public function __construct(BorrowingRepositoryInterface $borrowingRepository, BorrowingService $borrowingService)
    {
        $this->borrowingRepository = $borrowingRepository;
        $this->borrowingService = $borrowingService;
    }

    public function laporanPeminjaman()
    {
        $currentMonth = Carbon::now()->month;
        $lastMonth = Carbon::now()->subMonth()->month;

        $currentMonthTotal = Borrowing::whereMonth('tanggal_peminjaman', $currentMonth)
            ->sum('jumlah_dipinjam');

        $lastMonthTotal = Borrowing::whereMonth('tanggal_peminjaman', $lastMonth)
            ->sum('jumlah_dipinjam');

        // Menghitung persentase perubahan
        $percentageChange = 0;
        if ($lastMonthTotal > 0) {
            $percentageChange = (($currentMonthTotal - $lastMonthTotal) / $lastMonthTotal) * 100;
        }

        // Mengatur kelas Tailwind berdasarkan perbandingan
        $percentageClass = $percentageChange >= 0 ? 'text-green-800' : 'text-red-800';
        $comparisonText = $percentageChange >= 0 ? 'Lebih tinggi dari bulan lalu' : 'Lebih rendah dari bulan lalu';

        // Format persentase dengan 2 desimal
        $formattedPercentage = number_format(abs($percentageChange), 2);
        $totalBahan = Borrowing::sum('jumlah_dipinjam');

        return view('borrowings.index', compact('currentMonthTotal', 'formattedPercentage', 'percentageClass', 'comparisonText', 'totalBahan'));
    }

    public function pinjamAlat($id)
    {
        $pinjam = DB::table('alat')->where('id', $id)->first();
        return view('borrowings.show', compact('pinjam'));
    }

    public function ajukanPeminjaman(Request $request, $id)
    {
        try {
            $pinjam = DB::table('alat')->where('id', $id)->first();
            
            $this->borrowingService->createBorrowing(
                $id,
                $request->input('tanggal_peminjaman'),
                $request->input('jumlah_dipinjam'),
                $pinjam
            );
            
            return redirect()->route('laporanView')->with('success', 'Berhasil ajukan peminjaman');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function getChartData()
    {
        $data = DB::table('peminjaman_alat')
            ->select(DB::raw('tanggal_peminjaman, SUM(jumlah_dipinjam) AS total_peminjaman'))
            ->groupBy('tanggal_peminjaman')
            ->orderBy('tanggal_peminjaman')
            ->get();

        return response()->json($data);
    }

    public function getPieChartData()
    {
        $data = DB::table('peminjaman_alat')
            ->join('alat', 'peminjaman_alat.alat_id', '=', 'alat.id')
            ->select('alat.nama_alat', DB::raw('SUM(peminjaman_alat.jumlah_dipinjam) AS total_peminjaman'))
            ->groupBy('alat.nama_alat')
            ->get();

        return response()->json($data);
    }
}
