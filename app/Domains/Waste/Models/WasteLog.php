<?php

namespace App\Domains\Waste\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WasteLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'waste_id',
        'schedule_id',
        'jumlah_volume',
        'satuan',
        'catatan',
        'status',
    ];

    public function waste()
    {
        return $this->belongsTo(Waste::class);
    }

    public function schedule()
    {
        return $this->belongsTo(Jadwal::class, 'schedule_id', 'id');
    }
}
