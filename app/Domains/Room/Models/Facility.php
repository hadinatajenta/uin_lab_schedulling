<?php

namespace App\Domains\Room\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'icon'];

    public function rooms()
    {
        return $this->belongsToMany(Ruangan::class, 'room_facilities')
            ->withPivot('quantity', 'condition')
            ->withTimestamps();
    }
}
