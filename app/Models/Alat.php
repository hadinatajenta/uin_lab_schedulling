<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\LogsActivity;

class Alat extends Model
{
    use HasFactory, LogsActivity;
    protected $table = 'alat';
    
    protected $casts = [
        'gambar' => 'array',
    ];

    protected $fillable = [
        'nama_alat',
        'jenis_alat',
        'deskripsi',
        'spesifikasi',
        'kondisi',
        'gambar',
        'jumlah_satuan',
        'jumlah_ml',
        'cara_penggunaan',
        'link_youtube',
        'tanggal_pembelian',
        'tanggal_expired',
    ];
}
