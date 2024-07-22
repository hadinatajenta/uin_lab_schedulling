<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PeminjamanConttroller extends Controller
{
    public function laporanPeminjaman()
    {
        $currentMonth = Carbon::now()->month;
        $lastMonth = Carbon::now()->subMonth()->month;

        $currentMonthTotal = Peminjaman::whereMonth('tanggal_peminjaman', $currentMonth)
            ->sum('jumlah_dipinjam');

        $lastMonthTotal = Peminjaman::whereMonth('tanggal_peminjaman', $lastMonth)
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
        $totalBahan = Peminjaman::sum('jumlah_dipinjam');

        return view('peminjaman', compact('currentMonthTotal', 'formattedPercentage', 'percentageClass', 'comparisonText', 'totalBahan'));
    }
    public function pinjamAlat($id)
    {
        $pinjam = DB::table('alat')->where('id', $id)->first();
        return view('pinjam', compact('pinjam'));
    }

    public function ajukanPeminjaman(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $pinjam = new Peminjaman();
            $pinjam->alat_id = $id;
            $pinjam->tanggal_peminjaman = $request->input('tanggal_peminjaman');
            $pinjam->jumlah_dipinjam = $request->input('jumlah_dipinjam');

            if ($pinjam->jumlah_dipinjam < $pinjam->jumlah_satuan || $pinjam->jumlah_ml) {
                return redirect()->back()->with('error', 'Jumlah peminjaman tidak dapat melebihi jumlah alat/bahan');
            }
            $pinjam->save();
            DB::commit();
            return redirect()->route('laporanView')->with('success', 'Berhasil ajukan peminjaman');
        } catch (\Throwable $th) {
            DB::rollBack();
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
