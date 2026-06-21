<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\LogsActivity;

class Limbah extends Model
{
    use HasFactory, LogsActivity;

    protected $table ='limbah';
    protected $fillable =[
        'nama_limbah',
        'bahan',
        'cara_penggunaan',
        'materi',
        'cara_pengolahan',
    ];
}
