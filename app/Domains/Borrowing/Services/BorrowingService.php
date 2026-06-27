<?php

namespace App\Domains\Borrowing\Services;

use App\Domains\Borrowing\Models\Borrowing;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class BorrowingService
{
    public function createBorrowing(int $alatId, string $tanggalPeminjaman, float $jumlahDipinjam, $pinjam): Borrowing
    {
        try {
            DB::beginTransaction();
            
            $borrowing = new Borrowing();
            $borrowing->alat_id = $alatId;
            $borrowing->tanggal_peminjaman = $tanggalPeminjaman;
            $borrowing->jumlah_dipinjam = $jumlahDipinjam;

            if ($borrowing->jumlah_dipinjam < $pinjam->jumlah_satuan || $borrowing->jumlah_ml) {
                throw ValidationException::withMessages([
                    'jumlah_dipinjam' => 'Jumlah peminjaman tidak dapat melebihi jumlah alat/bahan'
                ]);
            }
            
            $borrowing->save();
            
            DB::commit();
            return $borrowing;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
