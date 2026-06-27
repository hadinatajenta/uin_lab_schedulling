<?php

namespace App\Domains\Room\Controllers;

use App\Http\Controllers\Controller;
use App\Domains\Room\Repositories\RoomRepositoryInterface;
use App\Domains\Room\Services\RoomService;
use App\Domains\Room\Models\Ruangan;
use App\Domains\Room\Models\RoomMaintenance;
use App\Domains\Room\Models\Facility;
use App\Domains\User\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RoomController extends Controller
{
    public function __construct(
        private RoomRepositoryInterface $roomRepository,
        private RoomService $roomService
    ) {}

    public function index(Request $request)
    {
        $rooms = $this->roomRepository->getPaginatedRooms(['search' => $request->search]);
        return view('rooms.index', compact('rooms'));
    }

    public function create()
    {
        $facilities = Facility::all();
        $users = User::all();
        return view('rooms.create', compact('facilities', 'users'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'room_code' => 'required|unique:ruangan',
            'nama_ruangan' => 'required|string|max:255',
            'description' => 'nullable|string',
            'building' => 'nullable|string|max:255',
            'floor' => 'nullable|string|max:255',
            'kapasitas' => 'required|integer|min:1',
            'is_active' => 'boolean',
            'pic_user_id' => 'nullable|exists:users,id',
            'facilities' => 'nullable|array',
            'facilities.*.id' => 'exists:facilities,id',
            'facilities.*.quantity' => 'integer|min:1',
            'facilities.*.condition' => 'in:baik,rusak_ringan,rusak_berat',
            'photo' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('rooms', 'public');
        }

        $this->roomService->createRoom($data);
        return redirect()->route('rooms.index')->with('success', 'Ruangan berhasil ditambahkan');
    }

    public function show($id)
    {
        $room = $this->roomRepository->findById($id, ['facilities', 'maintenances' => function($q) {
            $q->orderBy('start_date', 'desc');
        }, 'pic', 'schedules' => function($q) {
            $q->where('tanggal_jadwal', '>=', now()->toDateString())->orderBy('tanggal_jadwal');
        }]);
        
        return view('rooms.show', compact('room'));
    }

    public function edit($id)
    {
        $room = $this->roomRepository->findById($id, ['facilities']);
        $facilities = Facility::all();
        $users = User::all();
        return view('rooms.edit', compact('room', 'facilities', 'users'));
    }

    public function update(Request $request, $id)
    {
        $room = $this->roomRepository->findById($id);
        
        $data = $request->validate([
            'room_code' => 'required|unique:ruangan,room_code,'.$room->id,
            'nama_ruangan' => 'required|string|max:255',
            'description' => 'nullable|string',
            'building' => 'nullable|string|max:255',
            'floor' => 'nullable|string|max:255',
            'kapasitas' => 'required|integer|min:1',
            'is_active' => 'boolean',
            'pic_user_id' => 'nullable|exists:users,id',
            'facilities' => 'nullable|array',
            'photo' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('photo')) {
            if ($room->photo) Storage::disk('public')->delete($room->photo);
            $data['photo'] = $request->file('photo')->store('rooms', 'public');
        }

        $this->roomService->updateRoom($room, $data);
        return redirect()->route('rooms.index')->with('success', 'Ruangan berhasil diperbarui');
    }

    public function destroy($id)
    {
        $room = $this->roomRepository->findById($id);
        
        try {
            $this->roomService->deleteRoom($room);
            return redirect()->route('rooms.index')->with('success', 'Ruangan berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
    
    public function deactivate($id)
    {
        $room = $this->roomRepository->findById($id);
        $this->roomService->deactivateRoom($room);
        return redirect()->route('rooms.index')->with('success', 'Ruangan dinonaktifkan.');
    }
    
    public function transferSchedules(Request $request, $id)
    {
        $room = $this->roomRepository->findById($id);
        $toRoom = $this->roomRepository->findById($request->to_room_id);
        
        $this->roomRepository->transferSchedules($room, $toRoom);
        return redirect()->back()->with('success', 'Jadwal berhasil dipindahkan.');
    }

    public function scheduleMaintenance(Request $request, $id)
    {
        $room = $this->roomRepository->findById($id);
        
        $data = $request->validate([
            'type' => 'required|string',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_emergency' => 'boolean',
            'schedule_action' => 'required|in:none,auto_suspend,auto_cancel'
        ]);

        $this->roomService->scheduleMaintenance($room, $data);
        
        $msg = $data['is_emergency'] ? 'Kondisi darurat berhasil dilaporkan.' : 'Maintenance berhasil dijadwalkan.';
        return redirect()->back()->with('success', $msg);
    }

    public function completeMaintenance(Request $request, $id, $maintenanceId)
    {
        $maintenance = RoomMaintenance::findOrFail($maintenanceId);
        
        $data = $request->validate([
            'notes' => 'nullable|string',
            'schedule_recovery' => 'nullable|array' // Handles emergency recovery
        ]);

        $this->roomService->completeMaintenance($maintenance, $data);
        return redirect()->back()->with('success', 'Tindakan selesai dicatat.');
    }
    
    public function cancelMaintenance($id, $maintenanceId)
    {
        $maintenance = RoomMaintenance::findOrFail($maintenanceId);
        $maintenance->update(['status' => 'cancelled']);
        
        $room = $maintenance->room;
        if (!$room->maintenances()->whereIn('status', ['scheduled', 'in_progress'])->exists()) {
             $room->update(['is_active' => true]);
        }
        
        return redirect()->back()->with('success', 'Maintenance dibatalkan.');
    }
}
