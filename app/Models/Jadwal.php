<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    use HasFactory;

    protected $table = 'jadwal';
    protected $fillable = [
        'ruangan_id',
        'mata_kuliah',
        'submateri',
        'tanggal_jadwal',
        'waktu_mulai',
        'waktu_selesai',
        'status',
        'dosen',
        'kelas',
        'semester',
    ];
}
