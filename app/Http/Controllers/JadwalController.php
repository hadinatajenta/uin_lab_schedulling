<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class JadwalController extends Controller
{
    // Halaman jadwal
    public function jadwalView(Request $request)
    {
        $now = Carbon::now();
        $today = now()->toDateString();
        $jadwal = Jadwal::where('tanggal_jadwal', $today)->orderBy('waktu_mulai', 'asc')->get();
        $tomorrow = now()->addDay()->toDateString();
        $jadwal_besok = Jadwal::where('tanggal_jadwal', $tomorrow)->orderBy('waktu_mulai', 'asc')->get();

        $keyword = $request->input('keyword');
        if (isset($keyword)) {
            $schedule = Jadwal::where('mata_kuliah', 'LIKE', "%{$keyword}%")->orWhere('kelas', 'LIKE', "%{$keyword}%")->paginate(15);
        } else {
            $schedule = Jadwal::orderBy('created_at', 'desc')->paginate(15);
        }
        return view('lab', compact('jadwal', 'jadwal_besok', 'schedule'));
    }

    // Halaman tambah jadwal
    public function addJadwalView()
    {
        $user = User::where('jabatan', 'dosen')->get();
        return view('admin.addJadwal', compact('user'));
    }

    // Handle tambah jadwal
    public function addJadwal(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mata_kuliah' => ['required'],
            'tanggal_jadwal' => ['required', 'date', 'after_or_equal:today'],
            'waktu_mulai' => ['required', 'date_format:H:i'],
            'waktu_selesai' => ['required', 'date_format:H:i', 'after:waktu_mulai'],
            'dosen' => ['required'],
            'submateri' => ['nullable'],
            'kelas' => ['required'],
            'semester' => ['required'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $jadwal = new Jadwal();
        $jadwal->ruangan_id = $request->input('ruangan_id');
        $jadwal->tanggal_jadwal = $request->input('tanggal_jadwal');
        $jadwal->mata_kuliah = $request->input('mata_kuliah');
        $jadwal->submateri = $request->input('submateri');
        $jadwal->waktu_mulai = $request->input('waktu_mulai');
        $jadwal->waktu_selesai = $request->input('waktu_selesai');
        $status = trim("dijadwalkan");
        $jadwal->status = $status;
        $jadwal->dosen = $request->input('dosen');
        $jadwal->kelas = $request->input('kelas');
        $jadwal->semester = $request->input('semester');
        $jadwal->save();

        return redirect()->route('lab')->with('success', 'Berhasil menambah Jadwal!');

    }

    public function updateJadwal($id)
    {
        $jadwal = Jadwal::find($id);
        $user = User::where('jabatan', 'dosen')->get();
        return view('admin.editJadwal', compact('jadwal', 'user'));
    }
    // Halaman update jadwal
    public function editjadwal(Request $request, $id)
    {
        $jadwal = Jadwal::find($id);
        if ($jadwal) {
            $validator = Validator::make($request->all(), [
                'mata_kuliah' => ['required'],
                'tanggal_jadwal' => ['required', 'date', 'after_or_equal:today'],
                'waktu_mulai' => ['required', 'date_format:H:i'],
                'waktu_selesai' => ['required', 'date_format:H:i', 'after:waktu_mulai'],
                'dosen' => ['required'],
                'kelas' => ['required'],
                'semester' => ['required'],
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $jadwal->ruangan_id = 1;
            $jadwal->tanggal_jadwal = $request->input('tanggal_jadwal');
            $jadwal->mata_kuliah = $request->input('mata_kuliah');
            $jadwal->waktu_mulai = $request->input('waktu_mulai');
            $jadwal->waktu_selesai = $request->input('waktu_selesai');
            $status = trim("dijadwalkan");
            $jadwal->status = $status;
            $jadwal->dosen = $request->input('dosen');
            $jadwal->kelas = $request->input('kelas');
            $jadwal->semester = $request->input('semester');
            $jadwal->save();

            return redirect()->route('lab')->with('success', 'Berhasil perbarui Jadwal!');
        } else {
            return redirect()->route('lab')->with('error', 'Gagal perbarui Jadwal!');
        }

    }
    // Handle function update jadwal

    // Hapus jadwal
    public function hapusJadwal($id)
    {
        $jadwal = Jadwal::find($id);
        if ($jadwal) {
            $jadwal->delete();
            return redirect()->back()->with('success', 'Berhasil menghapus jadwal');
        } else {
            return redirect()->back()->with('error', 'Berhasil menghapus jadwal');
        }
    }
}
