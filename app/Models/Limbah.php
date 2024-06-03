<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Limbah extends Model
{
    use HasFactory;

    protected $table ='limbah';
    protected $fillable =[
        'nama_limbah',
        'bahan',
        'cara_penggunaan',
        'materi',
        'cara_pengolahan',
    ];
}
