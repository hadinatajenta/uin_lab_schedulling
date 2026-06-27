<?php

namespace App\Domains\Borrowing\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\LogsActivity;

class Borrowing extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'peminjaman_alat';
    protected $fillable = [
        'alat_id',
        'tanggal_peminjaman',
        'jumlah_dipinjam',
    ];
}
