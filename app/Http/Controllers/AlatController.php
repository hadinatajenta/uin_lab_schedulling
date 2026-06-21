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
        // Optimasi Count (tanpa memuat data ke RAM)
        $bahanPadatCount = Alat::where('jenis_alat', 'Alat')->count();
        $bahanCairCount = Alat::where('jenis_alat', 'Bahan')->count();
        $all = Alat::count();

        $keyword = $request->input('cari');
        $jenisAlat = $request->input('jenis_alat');
        $perPage = $request->input('per_page', 10); // Dynamic per page, default 10

        $query = Alat::query();

        if ($keyword) {
            $query->where('nama_alat', 'LIKE', "%{$keyword}%");
        }
        if ($jenisAlat) {
            $query->where('jenis_alat', $jenisAlat);
        }
        
        // Dynamic Pagination
        $alat = $query->paginate($perPage);

        return view('alat', compact('alat', 'bahanPadatCount', 'bahanCairCount', 'all'));
    }

    // view tambah alat
    public function tambahAlatView()
    {
        return view('tambahAlat');
    }
    // function tambah alat
    public function addAlat(Request $request)
    {
        $messages = [
            'nama_alat.required' => 'Nama barang wajib diisi.',
            'jenis_alat.required' => 'Kategori barang wajib dipilih.',
            'jenis_alat.in' => 'Kategori barang tidak valid.',
            'jumlah_satuan.required_if' => 'Jumlah satuan (unit) wajib diisi untuk kategori Alat.',
            'jumlah_ml.required_if' => 'Jumlah takaran (ml) wajib diisi untuk kategori Bahan.',
        ];

        // Validasi input
        $request->validate([
            'nama_alat' => 'required|string|max:255',
            'jenis_alat' => 'required|in:Alat,Bahan',
            'deskripsi' => 'nullable|string',
            'spesifikasi' => 'nullable|string',
            'kondisi' => 'nullable|string',
            'gambar' => 'nullable|image|max:5120',
            'jumlah_satuan' => 'required_if:jenis_alat,Alat|nullable|numeric|min:0',
            'jumlah_ml' => 'required_if:jenis_alat,Bahan|nullable|numeric|min:0',
            'cara_penggunaan' => 'nullable|string',
            'link_youtube' => 'nullable|string',
            'tanggal_pembelian' => 'nullable|date',
            'tanggal_expired' => 'nullable|date',
        ], $messages);

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

        // Sanitasi Data berdasarkan Jenis Alat
        if ($alat->jenis_alat == 'Alat') {
            $alat->jumlah_satuan = $request->input('jumlah_satuan');
            $alat->jumlah_ml = null;
            $alat->tanggal_expired = null; // Alat tidak expired
        } elseif ($alat->jenis_alat == 'Bahan') {
            $alat->jumlah_ml = $request->input('jumlah_ml');
            $alat->jumlah_satuan = null;
            $tanggal_expired = $request->input('tanggal_expired');
            $alat->tanggal_expired = $tanggal_expired;

            if ($tanggal_pembelian && $tanggal_expired && (strtotime($tanggal_pembelian) > strtotime($tanggal_expired))) {
                return redirect()->back()->withInput()->with('error', 'Tanggal pembelian tidak boleh melebihi tanggal expired.');
            }
        }

        $alat->save();
        return redirect()->route('alat')->with('success', 'Berhasil menambahkan data baru!');
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
        $messages = [
            'nama_alat.required' => 'Nama barang wajib diisi.',
            'jenis_alat.required' => 'Kategori barang wajib dipilih.',
            'jenis_alat.in' => 'Kategori barang tidak valid.',
            'jumlah_satuan.required_if' => 'Jumlah satuan (unit) wajib diisi untuk kategori Alat.',
            'jumlah_ml.required_if' => 'Jumlah takaran (ml) wajib diisi untuk kategori Bahan.',
        ];

        $request->validate([
            'nama_alat' => 'required|string|max:255',
            'jenis_alat' => 'required|in:Alat,Bahan',
            'deskripsi' => 'nullable|string',
            'spesifikasi' => 'nullable|string',
            'kondisi' => 'nullable|string',
            'gambar' => 'nullable|image|max:5120',
            'jumlah_satuan' => 'required_if:jenis_alat,Alat|nullable|numeric|min:0',
            'jumlah_ml' => 'required_if:jenis_alat,Bahan|nullable|numeric|min:0',
            'cara_penggunaan' => 'nullable|string',
            'link_youtube' => 'nullable|string',
            'tanggal_pembelian' => 'nullable|date',
            'tanggal_expired' => 'nullable|date',
        ], $messages);

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

        // Sanitasi Data berdasarkan Jenis Alat
        if ($alat->jenis_alat == 'Alat') {
            $alat->jumlah_satuan = $request->input('jumlah_satuan');
            $alat->jumlah_ml = null;
            $alat->tanggal_expired = null;
        } elseif ($alat->jenis_alat == 'Bahan') {
            $alat->jumlah_ml = $request->input('jumlah_ml');
            $alat->jumlah_satuan = null;
            $tanggal_expired = $request->input('tanggal_expired');
            $alat->tanggal_expired = $tanggal_expired;

            if ($tanggal_pembelian && $tanggal_expired && (strtotime($tanggal_pembelian) > strtotime($tanggal_expired))) {
                return redirect()->back()->withInput()->with('error', 'Tanggal pembelian tidak boleh melebihi tanggal expired.');
            }
        }

        $alat->save();
        return redirect()->route('alat')->with('success', 'Berhasil memperbarui data!');
    }


}
