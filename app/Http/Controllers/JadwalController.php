<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Domains\User\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class JadwalController extends Controller
{
    public function jadwalView(Request $request)
    {
        $now = Carbon::now();
        $today = now()->toDateString();

        // Metrics
        $totalToday = Jadwal::where('tanggal_jadwal', $today)->count();
        $totalLabs = \App\Models\Ruangan::count();
        $activeLabsToday = Jadwal::where('tanggal_jadwal', $today)->distinct('ruangan_id')->count('ruangan_id');
        $availableLabs = max(0, $totalLabs - $activeLabsToday);

        $keyword = $request->input('keyword');
        $dateFilter = $request->input('date');
        $statusFilter = $request->input('status');
        $perPage = $request->input('per_page', 10);

        $query = Jadwal::with('dosen');

        if (!empty($keyword)) {
            $query->where(function ($q) use ($keyword) {
                $q->where('mata_kuliah', 'LIKE', "%{$keyword}%")
                    ->orWhere('kelas', 'LIKE', "%{$keyword}%")
                    ->orWhereHas('dosen', function ($q2) use ($keyword) {
                        $q2->where('name', 'LIKE', "%{$keyword}%");
                    });
            });
        }

        if (!empty($dateFilter)) {
            $query->where('tanggal_jadwal', $dateFilter);
        }

        $conflicts = \Illuminate\Support\Facades\DB::table('jadwal as a')
            ->join('jadwal as b', function ($join) {
                $join->on('a.tanggal_jadwal', '=', 'b.tanggal_jadwal')
                    ->on('a.ruangan_id', '=', 'b.ruangan_id')
                    ->whereRaw('a.id != b.id')
                    ->whereRaw('a.waktu_mulai < b.waktu_selesai')
                    ->whereRaw('a.waktu_selesai > b.waktu_mulai');
            })
            ->pluck('a.id')
            ->unique()
            ->toArray();

        $totalConflicts = count($conflicts);

        if (!empty($statusFilter)) {
            if ($statusFilter === 'konflik') {
                $query->whereIn('id', $conflicts);
            } else {
                $query->where('status', $statusFilter);
            }
        }

        $schedule = $query->orderBy('tanggal_jadwal', 'desc')
            ->orderBy('waktu_mulai', 'asc')
            ->paginate($perPage)
            ->withQueryString();

        if ($request->ajax()) {
            return view('schedules.partials.table', compact('schedule', 'conflicts'))->render();
        }

        return view('schedules.index', compact('schedule', 'totalToday', 'availableLabs', 'totalConflicts', 'conflicts'));
    }

    public function addJadwalView()
    {
        $user = User::whereHas('roles', function ($q) {
            $q->where('slug', 'lecturer');
        })->get();
        $ruangans = \App\Models\Ruangan::all();
        return view('schedules.create', compact('user', 'ruangans'));
    }

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
