<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutLab extends Model
{
    use HasFactory;

    protected $table = 'about_lab';
    protected $fillable = [
        'sop',
        'stuktur'
    ];
}
