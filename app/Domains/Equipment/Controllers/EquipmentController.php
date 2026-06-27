<?php

namespace App\Domains\Equipment\Controllers;

use App\Http\Controllers\Controller;
use App\Domains\Equipment\Repositories\EquipmentRepositoryInterface;
use App\Domains\Equipment\Services\EquipmentService;
use Illuminate\Http\Request;

class EquipmentController extends Controller
{
    protected EquipmentRepositoryInterface $equipmentRepository;
    protected EquipmentService $equipmentService;

    public function __construct(EquipmentRepositoryInterface $equipmentRepository, EquipmentService $equipmentService)
    {
        $this->equipmentRepository = $equipmentRepository;
        $this->equipmentService = $equipmentService;
    }

    public function index(Request $request)
    {
        $bahanPadatCount = $this->equipmentRepository->countByJenis('Alat');
        $bahanCairCount = $this->equipmentRepository->countByJenis('Bahan');
        $all = $this->equipmentRepository->countAll();

        $filters = $request->only(['cari', 'jenis_alat']);
        $perPage = $request->input('per_page', 10);
        
        $alat = $this->equipmentRepository->getPaginatedEquipments($filters, $perPage);

        return view('equipments.index', compact('alat', 'bahanPadatCount', 'bahanCairCount', 'all'));
    }

    public function create()
    {
        return view('equipments.create');
    }

    public function store(Request $request)
    {
        $validatedData = $this->validateEquipment($request);

        $tanggal_pembelian = $request->input('tanggal_pembelian');
        $tanggal_expired = $request->input('tanggal_expired');
        $jenis_alat = $request->input('jenis_alat');

        if ($jenis_alat == 'Bahan' && $tanggal_pembelian && $tanggal_expired && (strtotime($tanggal_pembelian) > strtotime($tanggal_expired))) {
            return redirect()->back()->withInput()->with('toast', [
                'type' => 'error',
                'message' => 'Tanggal pembelian tidak boleh melebihi tanggal expired.'
            ]);
        }

        $this->equipmentService->createEquipment(
            $request->except('gambar'),
            $request->file('gambar')
        );

        return redirect()->route('alat')->with('toast', [
            'type' => 'success',
            'message' => 'Berhasil menambahkan data baru!'
        ]);
    }

    public function show(int $id)
    {
        $alat = $this->equipmentRepository->findById($id);
        return view('equipments.show', compact('alat'));
    }

    public function edit(int $id)
    {
        $edit = $this->equipmentRepository->findById($id);
        return view('equipments.edit', compact('edit'));
    }

    public function update(Request $request, int $id)
    {
        $validatedData = $this->validateEquipment($request);

        $tanggal_pembelian = $request->input('tanggal_pembelian');
        $tanggal_expired = $request->input('tanggal_expired');
        $jenis_alat = $request->input('jenis_alat');

        if ($jenis_alat == 'Bahan' && $tanggal_pembelian && $tanggal_expired && (strtotime($tanggal_pembelian) > strtotime($tanggal_expired))) {
            return redirect()->back()->withInput()->with('toast', [
                'type' => 'error',
                'message' => 'Tanggal pembelian tidak boleh melebihi tanggal expired.'
            ]);
        }

        try {
            $equipment = $this->equipmentRepository->findById($id);
            $this->equipmentService->updateEquipment(
                $equipment,
                $request->except('gambar'),
                $request->file('gambar')
            );
            
            return redirect()->route('alat')->with('toast', [
                'type' => 'success',
                'message' => 'Berhasil memperbarui data!'
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('toast', [
                'type' => 'error',
                'message' => 'Data Alat tidak ditemukan atau gagal diperbarui.'
            ]);
        }
    }

    public function destroy(int $id)
    {
        try {
            $equipment = $this->equipmentRepository->findById($id);
            $this->equipmentService->deleteEquipment($equipment);
            
            return redirect()->route('alat')->with('toast', [
                'type' => 'success',
                'message' => 'Berhasil menghapus data Alat!'
            ]);
        } catch (\Exception $e) {
            return redirect()->route('alat')->with('toast', [
                'type' => 'error',
                'message' => 'Data Alat tidak ditemukan!'
            ]);
        }
    }

    private function validateEquipment(Request $request): array
    {
        $messages = [
            'nama_alat.required' => 'Nama barang wajib diisi.',
            'jenis_alat.required' => 'Kategori barang wajib dipilih.',
            'jenis_alat.in' => 'Kategori barang tidak valid.',
            'jumlah_satuan.required_if' => 'Jumlah satuan (unit) wajib diisi untuk kategori Alat.',
            'jumlah_ml.required_if' => 'Jumlah takaran (ml) wajib diisi untuk kategori Bahan.',
        ];

        return $request->validate([
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
    }
}
