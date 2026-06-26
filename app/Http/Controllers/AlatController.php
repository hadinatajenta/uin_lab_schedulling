<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Alat;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\WebpEncoder;
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
        
        // Dynamic Pagination (withQueryString preserves filters in URLs)
        $alat = $query->paginate($perPage)->withQueryString();

        return view('equipments.index', compact('alat', 'bahanPadatCount', 'bahanCairCount', 'all'));
    }

    // view tambah alat
    public function tambahAlatView()
    {
        return view('equipments.create');
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
            'gambar' => 'nullable|array|max:5',
            'gambar.*' => 'image|max:2048', // max 2MB per gambar as requested
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

        // handle gambar multi upload & resize
        if ($request->hasFile('gambar')) {
            $manager = new ImageManager(new Driver());
            $paths = [];
            foreach ($request->file('gambar') as $image) {
                $imageName = time() . '_' . uniqid() . '.webp';
                
                // Read image (Intervention Image v4 uses decode)
                $img = $manager->decode($image);
                
                // Scale down to max 1600px width/height maintaining aspect ratio
                $img->scaleDown(1600, 1600);
                
                // Encode to webp with 80% quality
                $encoded = $img->encode(new WebpEncoder(80));
                
                // Save to storage
                Storage::disk('public')->put('images/' . $imageName, (string) $encoded);
                
                $paths[] = 'images/' . $imageName;
            }
            $alat->gambar = $paths;
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
                return redirect()->back()->withInput()->with('toast', [
                    'type' => 'error',
                    'message' => 'Tanggal pembelian tidak boleh melebihi tanggal expired.'
                ]);
            }
        }

        $alat->save();
        return redirect()->route('alat')->with('toast', [
            'type' => 'success',
            'message' => 'Berhasil menambahkan data baru!'
        ]);
    }

    // delete alat
    public function deleteAlat($id)
    {
        // Cari objek Alat berdasarkan ID
        $alat = Alat::find($id);

        // Jika objek Alat tidak ditemukan
        if (!$alat) {
            return redirect()->route('alat')->with('toast', [
                'type' => 'error',
                'message' => 'Data Alat tidak ditemukan!'
            ]);
        }

        // Hapus gambar jika ada
        if (!empty($alat->gambar) && is_array($alat->gambar)) {
            foreach ($alat->gambar as $path) {
                Storage::disk('public')->delete($path);
            }
        } elseif (!empty($alat->gambar) && is_string($alat->gambar)) {
            Storage::disk('public')->delete($alat->gambar);
        }

        // Hapus objek Alat
        $alat->delete();

        // Redirect dengan pesan sukses
        return redirect()->route('alat')->with('toast', [
            'type' => 'success',
            'message' => 'Berhasil menghapus data Alat!'
        ]);
    }

    public function alat($id)
    {
        $alat = Alat::find($id);
        return view('equipments.show', compact('alat'));
    }

    public function editAlat($id)
    {
        $edit = Alat::find($id);
        return view('equipments.edit', compact('edit'));
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
            'gambar' => 'nullable|array|max:5',
            'gambar.*' => 'image|max:2048', // max 2MB per gambar
            'jumlah_satuan' => 'required_if:jenis_alat,Alat|nullable|numeric|min:0',
            'jumlah_ml' => 'required_if:jenis_alat,Bahan|nullable|numeric|min:0',
            'cara_penggunaan' => 'nullable|string',
            'link_youtube' => 'nullable|string',
            'tanggal_pembelian' => 'nullable|date',
            'tanggal_expired' => 'nullable|date',
        ], $messages);

        $alat = Alat::find($id);

        if (!$alat) {
            return redirect()->back()->with('toast', [
                'type' => 'error',
                'message' => 'Data Alat tidak ditemukan.'
            ]);
        }

        // Update data Alat
        $alat->nama_alat = $request->input('nama_alat');
        $alat->jenis_alat = $request->input('jenis_alat');
        $alat->deskripsi = $request->input('deskripsi');
        $alat->spesifikasi = $request->input('spesifikasi');
        $alat->kondisi = $request->input('kondisi');

        // Handle gambar disini (timpa seluruh gambar lama jika ada unggahan baru)
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama
            if (!empty($alat->gambar) && is_array($alat->gambar)) {
                foreach ($alat->gambar as $oldPath) {
                    Storage::disk('public')->delete($oldPath);
                }
            } elseif (!empty($alat->gambar) && is_string($alat->gambar)) {
                Storage::disk('public')->delete($alat->gambar);
            }

            // Simpan gambar baru
            $manager = new ImageManager(new Driver());
            $paths = [];
            foreach ($request->file('gambar') as $image) {
                $imageName = time() . '_' . uniqid() . '.webp';
                
                $img = $manager->decode($image);
                $img->scaleDown(1600, 1600);
                $encoded = $img->encode(new WebpEncoder(80));
                
                Storage::disk('public')->put('images/' . $imageName, (string) $encoded);
                
                $paths[] = 'images/' . $imageName;
            }
            $alat->gambar = $paths;
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
                return redirect()->back()->withInput()->with('toast', [
                    'type' => 'error',
                    'message' => 'Tanggal pembelian tidak boleh melebihi tanggal expired.'
                ]);
            }
        }

        $alat->save();
        return redirect()->route('alat')->with('toast', [
            'type' => 'success',
            'message' => 'Berhasil memperbarui data!'
        ]);
    }


}
