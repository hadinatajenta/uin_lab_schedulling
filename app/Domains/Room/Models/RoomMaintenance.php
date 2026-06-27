<?php

namespace App\Domains\Room\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Domains\User\Models\User;

class RoomMaintenance extends Model
{
    use HasFactory;

    protected $fillable = [
        'ruangan_id',
        'type',
        'title',
        'description',
        'start_date',
        'end_date',
        'status',
        'is_emergency',
        'schedule_action',
        'created_by',
        'notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_emergency' => 'boolean',
    ];

    public function room()
    {
        return $this->belongsTo(Ruangan::class, 'ruangan_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
