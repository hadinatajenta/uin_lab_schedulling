<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Limbah;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LimbahController extends Controller
{
    public function limbahView()
    {
        $limbah = Limbah::all();
        return view('admin.limbah',compact('limbah'));
    }
    // tambah  limbah view
    public function tambahLimbahView()
    {
        return view('admin.tambahLimbah');
    }
    // create limbah
     public function create(Request $request)
    {
        // Validasi input
        $validatedData = $request->validate([
            'nama_limbah' => 'required',
            'bahan' => 'nullable',
            'cara_penggunaan' => 'nullable',
            'materi' => 'nullable',
            'cara_pengolahan' => 'nullable',
        ]);

        // Membuat record baru
        $limbah = Limbah::create([
            'nama_limbah' => $validatedData['nama_limbah'],
            'bahan' => $validatedData['bahan'],
            'cara_penggunaan' => $validatedData['cara_penggunaan'],
            'materi' => $validatedData['materi'],
            'cara_pengolahan' => $validatedData['cara_pengolahan'],
        ]);

        // Mengembalikan respon sukses atau error
        if ($limbah) {
            return redirect()->route('limbah')->with('success','Berhasil Menambahkan Limbah!');
        } else {
            return redirect()->back()->with('error','Gagal menambah limbah');
        }
    }
    // ddetail limbah 
    public function detailLimbah($id){
        $findLimbah = Limbah::find($id);

        if($findLimbah){
            return view('admin.detailLimbah',compact('findLimbah'));
        }else{
            return redirect()->back()->with('error','Data limbah tidak ditemukan');
        }
    }

    public function hapusLimbah($id)
    {
        $limbah = Limbah::find($id);

        if($limbah){
            $limbah->delete();
            return redirect()->back()->with('success','Berhasil hapus informasi limbah');
        }else{
            return redirect()->back()->with('error','Gagal hapus informasi limbah');
        }
    }
}
