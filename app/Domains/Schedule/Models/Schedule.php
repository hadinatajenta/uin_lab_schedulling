<?php

namespace App\Domains\Schedule\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Domains\User\Models\User;
use App\Domains\Waste\Models\WasteLog;

use App\Traits\LogsActivity;

class Schedule extends Model
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

    public function room()
    {
        return $this->belongsTo(\App\Domains\Room\Models\Ruangan::class, 'ruangan_id');
    }
}
