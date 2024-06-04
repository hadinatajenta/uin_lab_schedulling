<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jaslab extends Model
{
    use HasFactory;

    protected $table= 'jaslab';
    protected $fillable =[
        'role',
        'warna'
    ];
}
