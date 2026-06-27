<?php

namespace App\Http\Controllers;

use App\Models\Jaslab;
use Illuminate\Http\Request;

class JaslabController extends Controller
{
    public function index()
    {
        $jaslab = Jaslab::all();
        return view('lab-suits.index', compact('jaslab'));
    }

    public function update(Request $request, $id)
    {
        $jaslab = Jaslab::findOrFail($id);
        $jaslab->warna = $request->input('warna');
        $jaslab->save();
        return redirect()->back()->with('success', 'Berhasil perbarui warna jaslab!');
    }
}
