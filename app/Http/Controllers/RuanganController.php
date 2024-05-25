<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Ruangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class RuanganController extends Controller
{

    public function updateRuangan(Request $request)
    {
        $ruangan = Ruangan::first();
        return view('lab', compact('ruangan'));
    }
}
