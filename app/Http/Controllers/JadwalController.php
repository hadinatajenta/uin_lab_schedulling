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

        $sevenDaysAhead = now()->addDays(7)->toDateString();
        $jadwal_minggu_ini = Jadwal::whereBetween('tanggal_jadwal', [$today, $sevenDaysAhead])
            ->orderBy('tanggal_jadwal', 'asc')
            ->orderBy('waktu_mulai', 'asc')
            ->get();

        $keyword = $request->input('keyword');
        $perPage = $request->input('per_page', 10); // Dynamic per_page, default 10

        if (isset($keyword)) {
            $schedule = Jadwal::where('mata_kuliah', 'LIKE', "%{$keyword}%")->orWhere('kelas', 'LIKE', "%{$keyword}%")->paginate($perPage)->withQueryString();
        } else {
            $schedule = Jadwal::orderBy('created_at', 'desc')->paginate($perPage)->withQueryString();
        }
        return view('schedules.index', compact('jadwal', 'jadwal_besok', 'jadwal_minggu_ini', 'schedule'));
    }

    // Halaman tambah jadwal
    public function addJadwalView()
    {
        $user = User::whereHas('roles', function ($q) {
            $q->where('slug', 'lecturer');
        })->get();
        return view('schedules.create', compact('user'));
    }

    // Handle tambah jadwal
    public function addJadwal(\App\Http\Requests\JadwalRequest $request)
    {
        $validated = $request->validated();
        $validated['status'] = 'dijadwalkan';

        Jadwal::create($validated);

        return redirect()->route('lab')->with('success', 'Berhasil menambah Jadwal!');
    }

    public function updateJadwal($id)
    {
        $jadwal = Jadwal::find($id);
        $user = User::whereHas('roles', function ($q) {
            $q->where('slug', 'lecturer');
        })->get();
        return view('schedules.edit', compact('jadwal', 'user'));
    }

    public function editjadwal(\App\Http\Requests\JadwalRequest $request, $id)
    {
        $jadwal = Jadwal::find($id);
        
        if (!$jadwal) {
            return redirect()->route('lab')->with('error', 'Jadwal tidak ditemukan!');
        }

        $validated = $request->validated();
        $validated['status'] = 'dijadwalkan';
        $validated['ruangan_id'] = $validated['ruangan_id'] ?? 1;

        $jadwal->update($validated);

        return redirect()->route('lab')->with('success', 'Berhasil perbarui Jadwal!');
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

    public function cancelJadwal($id)
    {
        $jadwal = Jadwal::findOrFail($id);
        
        if (\Illuminate\Support\Facades\Auth::id() != $jadwal->dosen_id && \Illuminate\Support\Facades\Auth::user()->jabatan != 'Admin Lab') {
            abort(403, 'Unauthorized action.');
        }

        if (!in_array($jadwal->status, ['dijadwalkan', 'berlangsung'])) {
            return redirect()->back()->with('error', 'Jadwal tidak dapat dibatalkan.');
        }

        Jadwal::withoutEvents(function () use ($jadwal) {
            $jadwal->update(['status' => 'dibatalkan']);
        });

        \App\Models\ActivityLog::create([
            'user_id' => \Illuminate\Support\Facades\Auth::id(),
            'action' => 'updated',
            'subject_type' => get_class($jadwal),
            'subject_id' => $jadwal->id,
            'description' => \Illuminate\Support\Facades\Auth::user()->name . " membatalkan jadwal matkul " . $jadwal->mata_kuliah,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return redirect()->back()->with('success', 'Jadwal berhasil dibatalkan.');
    }

    public function completeEarly($id)
    {
        $jadwal = Jadwal::findOrFail($id);
        
        if (\Illuminate\Support\Facades\Auth::id() != $jadwal->dosen_id && \Illuminate\Support\Facades\Auth::user()->jabatan != 'Admin Lab') {
            abort(403, 'Unauthorized action.');
        }

        if (!in_array($jadwal->status, ['dijadwalkan', 'berlangsung'])) {
            return redirect()->back()->with('error', 'Jadwal tidak dapat diselesaikan.');
        }

        Jadwal::withoutEvents(function () use ($jadwal) {
            $jadwal->update(['status' => 'selesai']);
        });

        \App\Models\ActivityLog::create([
            'user_id' => \Illuminate\Support\Facades\Auth::id(),
            'action' => 'updated',
            'subject_type' => get_class($jadwal),
            'subject_id' => $jadwal->id,
            'description' => \Illuminate\Support\Facades\Auth::user()->name . " menyelesaikan jadwal matkul " . $jadwal->mata_kuliah . " lebih awal",
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return redirect()->back()->with('success', 'Jadwal berhasil diselesaikan.');
    }
}
