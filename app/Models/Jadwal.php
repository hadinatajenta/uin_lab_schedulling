<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Domains\User\Models\User;

use App\Traits\LogsActivity;

class Jadwal extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'jadwal';
    protected $fillable = [
        'ruangan_id',
        'mata_kuliah',
        'submateri',
        'tanggal_jadwal',
        'waktu_mulai',
        'waktu_selesai',
        'status',
        'dosen_id',
        'kelas',
        'semester',
    ];

    public function dosen()
    {
        return $this->belongsTo(User::class, 'dosen_id');
    }

    public function wasteLogs()
    {
        return $this->hasMany(WasteLog::class, 'schedule_id', 'id');
    }
}
