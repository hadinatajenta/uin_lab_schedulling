<?php

namespace App\Domains\Room\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Domains\User\Models\User;
use App\Domains\Schedule\Models\Schedule;

class Ruangan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'ruangan';

    protected $fillable = [
        'room_code',
        'nama_ruangan',
        'description',
        'building',
        'floor',
        'kapasitas',
        'photo',
        'is_active',
        'pic_user_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function pic()
    {
        return $this->belongsTo(User::class, 'pic_user_id');
    }

    public function facilities()
    {
        return $this->belongsToMany(Facility::class, 'room_facilities')
            ->withPivot('quantity', 'condition')
            ->withTimestamps();
    }

    public function maintenances()
    {
        return $this->hasMany(RoomMaintenance::class, 'ruangan_id');
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'ruangan_id');
    }

    public function isAvailable(): bool
    {
        if (!$this->is_active) return false;

        return !$this->maintenances()
            ->whereIn('status', ['scheduled', 'in_progress'])
            ->where('start_date', '<=', now())
            ->where(function ($query) {
                $query->where('end_date', '>=', now())
                      ->orWhereNull('end_date'); // Handle emergency
            })
            ->exists();
    }

    public function isAvailableOn(string $date): bool
    {
        if (!$this->is_active) return false;

        return !$this->maintenances()
            ->whereIn('status', ['scheduled', 'in_progress'])
            ->where('start_date', '<=', $date)
            ->where(function ($query) use ($date) {
                $query->where('end_date', '>=', $date)
                      ->orWhereNull('end_date'); // Handle emergency
            })
            ->exists();
    }
}
