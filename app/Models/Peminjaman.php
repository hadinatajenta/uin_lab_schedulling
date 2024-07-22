<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjaman_alat';
    protected $fillable = [
        'alat_id',
        'tanggal_peminjaman',
        'jumlah_dipinjam',
    ];
}
