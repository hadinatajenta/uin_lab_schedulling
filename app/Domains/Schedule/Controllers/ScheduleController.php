<?php

namespace App\Domains\Schedule\Controllers;

use App\Http\Controllers\Controller;
use App\Domains\Schedule\Repositories\ScheduleRepositoryInterface;
use App\Domains\Schedule\Services\ScheduleService;
use App\Domains\User\Models\User;
use App\Domains\Room\Models\Ruangan;
use App\Http\Requests\JadwalRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class ScheduleController extends Controller
{
    protected ScheduleRepositoryInterface $scheduleRepository;
    protected ScheduleService $scheduleService;

    public function __construct(ScheduleRepositoryInterface $scheduleRepository, ScheduleService $scheduleService)
    {
        $this->scheduleRepository = $scheduleRepository;
        $this->scheduleService = $scheduleService;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['keyword', 'date', 'status']);
        $perPage = $request->input('per_page', 10);
        
        $today = Carbon::now()->toDateString();
        
        $schedule = $this->scheduleRepository->getPaginatedSchedules($filters, $perPage);
        $metrics = $this->scheduleRepository->getMetrics($today);
        
        $totalToday = $metrics['totalToday'];
        $availableLabs = $metrics['availableLabs'];
        $totalConflicts = $metrics['totalConflicts'];
        $conflicts = $metrics['conflicts'];

        if ($request->ajax()) {
            return view('schedules.partials.table', compact('schedule', 'conflicts'))->render();
        }

        return view('schedules.index', compact('schedule', 'totalToday', 'availableLabs', 'totalConflicts', 'conflicts'));
    }

    public function create()
    {
        $user = User::whereHas('roles', function ($q) {
            $q->where('slug', 'lecturer');
        })->get();
        $ruangans = Ruangan::all();
        
        return view('schedules.create', compact('user', 'ruangans'));
    }

    public function store(JadwalRequest $request)
    {
        $this->scheduleService->createSchedule($request->validated());

        return redirect()->route('lab')->with('success', 'Berhasil menambah Jadwal!');
    }

    public function edit(int $id)
    {
        $jadwal = $this->scheduleRepository->findById($id);
        $user = User::whereHas('roles', function ($q) {
            $q->where('slug', 'lecturer');
        })->get();
        
        return view('schedules.edit', compact('jadwal', 'user'));
    }

    public function update(JadwalRequest $request, int $id)
    {
        try {
            $schedule = $this->scheduleRepository->findById($id);
            $this->scheduleService->updateSchedule($schedule, $request->validated());
            
            return redirect()->route('lab')->with('success', 'Berhasil perbarui Jadwal!');
        } catch (\Exception $e) {
            return redirect()->route('lab')->with('error', 'Jadwal tidak ditemukan atau gagal diperbarui!');
        }
    }

    public function destroy(int $id)
    {
        try {
            $schedule = $this->scheduleRepository->findById($id);
            $this->scheduleService->deleteSchedule($schedule);
            
            return redirect()->back()->with('success', 'Berhasil menghapus jadwal');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus jadwal');
        }
    }

    public function cancel(int $id)
    {
        try {
            $schedule = $this->scheduleRepository->findById($id);
            $isAdmin = Auth::user()->jabatan == 'admin lab';
            
            $this->scheduleService->cancelSchedule($schedule, Auth::id(), $isAdmin);
            
            return redirect()->back()->with('success', 'Jadwal berhasil dibatalkan.');
        } catch (ValidationException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function completeEarly(int $id)
    {
        try {
            $schedule = $this->scheduleRepository->findById($id);
            $isAdmin = Auth::user()->jabatan == 'admin lab';
            
            $this->scheduleService->completeEarly($schedule, Auth::id(), $isAdmin);
            
            return redirect()->back()->with('success', 'Jadwal berhasil diselesaikan.');
        } catch (ValidationException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
