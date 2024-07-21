<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Alat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;


class AlatController extends Controller
{
    // halaman alat
    public function alatView(Request $request)
    {
        $bahanPadat = Alat::where('jenis_alat', 'Alat')->get();
        $bahanCair = Alat::where('jenis_alat', 'Bahan')->get();
        $keyword = $request->input('cari');
        if ($keyword) {
            $alat = Alat::where('nama_alat', 'LIKE', "%{$keyword}%")->get();
        } else {
            $alat = Alat::paginate(20);
        }
        $all = $alat->count();
        return view('alat', compact('alat', 'bahanPadat', 'bahanCair', 'all'));
    }

    // view tambah alat
    public function tambahAlatView()
    {
        return view('tambahAlat');
    }
    // function tambah alat
    public function addAlat(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama_alat' => 'required',
            'jenis_alat' => 'nullable',
            'deskripsi' => 'nullable',
            'spesifikasi' => 'nullable',
            'kondisi' => 'nullable',
            'gambar' => 'nullable',
            'jumlah_satuan' => 'nullable',
            'jumlah_ml' => 'nullable',
            'cara_penggunaan' => 'nullable',
            'link_youtube' => 'nullable',
            'tanggal_pembelian' => 'nullable',
            'tanggal_expired' => 'nullable',
        ]);


        // Buat objek Alat baru
        $alat = new Alat();
        $alat->nama_alat = $request->input('nama_alat');
        $alat->jenis_alat = $request->input('jenis_alat');
        $alat->deskripsi = $request->input('deskripsi');
        $alat->spesifikasi = $request->input('spesifikasi');
        $alat->kondisi = $request->input('kondisi');

        // handle gambar disini
        if ($request->hasFile('gambar')) {
            $image = $request->file('gambar');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('images', $imageName, 'public');
            $alat->gambar = $path;
        }

        $alat->cara_penggunaan = $request->input('cara_penggunaan');
        $alat->link_youtube = $request->input('link_youtube');

        $tanggal_pembelian = $request->input('tanggal_pembelian');
        $alat->tanggal_pembelian = $tanggal_pembelian;

        if ($alat->jenis_alat == 'Alat') {
            $alat->jumlah_satuan = $request->input('jumlah_satuan');
        } elseif ($alat->jenis_alat == 'Bahan') {
            $alat->jumlah_ml = $request->input('jumlah_ml');
            $tanggal_expired = $request->input('tanggal_expired');
            $alat->tanggal_expired = $tanggal_expired;

            if (strtotime($tanggal_pembelian) > strtotime($tanggal_expired)) {
                return redirect()->back()->with('error', 'Tanggal pembelian tidak boleh melebihi tanggal expired.');
            }
        }

        if ($alat) {
            $alat->save();
            return redirect()->route('alat')->with('success', 'Berhasil menambahkan data Alat baru!');
        }
    }

    // delete alat
    public function deleteAlat($id)
    {
        // Cari objek Alat berdasarkan ID
        $alat = Alat::find($id);

        // Jika objek Alat tidak ditemukan
        if (!$alat) {
            return redirect()->route('alat')->with('error', 'Data Alat tidak ditemukan!');
        }

        // Hapus gambar jika ada
        if ($alat->gambar) {
            Storage::disk('public')->delete($alat->gambar);
        }

        // Hapus objek Alat
        $alat->delete();

        // Redirect dengan pesan sukses
        return redirect()->route('alat')->with('success', 'Berhasil menghapus data Alat!');
    }

    public function alat($id)
    {
        $alat = Alat::find($id);
        return view('admin.detailAlat', compact('alat'));
    }

    public function editAlat($id)
    {
        $edit = Alat::find($id);
        return view('admin.EditAlat', compact('edit'));
    }

    public function updateAlat(Request $request, $id)
    {
        $request->validate([
            'nama_alat' => 'required',
            'jenis_alat' => 'nullable',
            'deskripsi' => 'nullable',
            'spesifikasi' => 'nullable',
            'kondisi' => 'nullable',
            'gambar' => 'nullable',
            'jumlah_satuan' => 'nullable',
            'jumlah_ml' => 'nullable',
            'cara_penggunaan' => 'nullable',
            'link_youtube' => 'nullable',
            'tanggal_pembelian' => 'nullable',
            'tanggal_expired' => 'nullable',
        ]);

        $alat = Alat::find($id);

        if (!$alat) {
            return redirect()->back()->with('error', 'Data Alat tidak ditemukan.');
        }

        // Update data Alat
        $alat->nama_alat = $request->input('nama_alat');
        $alat->jenis_alat = $request->input('jenis_alat');
        $alat->deskripsi = $request->input('deskripsi');
        $alat->spesifikasi = $request->input('spesifikasi');
        $alat->kondisi = $request->input('kondisi');

        // Handle gambar disini
        if ($request->hasFile('gambar')) {
            $image = $request->file('gambar');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('images', $imageName, 'public');
            $alat->gambar = $path;
        }

        $alat->cara_penggunaan = $request->input('cara_penggunaan');
        $alat->link_youtube = $request->input('link_youtube');

        $tanggal_pembelian = $request->input('tanggal_pembelian');
        $alat->tanggal_pembelian = $tanggal_pembelian;

        if ($alat->jenis_alat == 'Alat') {
            $alat->jumlah_satuan = $request->input('jumlah_satuan');
        } elseif ($alat->jenis_alat == 'Bahan') {
            $alat->jumlah_ml = $request->input('jumlah_ml');
            $tanggal_expired = $request->input('tanggal_expired');
            $alat->tanggal_expired = $tanggal_expired;

            if (strtotime($tanggal_pembelian) > strtotime($tanggal_expired)) {
                return redirect()->back()->with('error', 'Tanggal pembelian tidak boleh melebihi tanggal expired.');
            }
        }

        if ($alat) {
            $alat->save();
            return redirect()->route('alat')->with('success', 'Berhasil memperbarui data Alat!');
        }
    }


}
