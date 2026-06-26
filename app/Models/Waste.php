<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Waste extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'kode_limbah',
        'nama_limbah',
        'kategori',
        'sifat_bahaya',
        'gambar_panduan',
        'cara_penanganan',
        'prosedur_darurat',
    ];

    protected $casts = [
        'sifat_bahaya' => 'array',
    ];

    public function logs()
    {
        return $this->hasMany(WasteLog::class);
    }
}
