<?php

namespace App\Domains\Waste\Controllers;

use App\Http\Controllers\Controller;
use App\Domains\Waste\Repositories\WasteRepositoryInterface;
use App\Domains\Waste\Services\WasteService;
use Illuminate\Http\Request;

class WasteController extends Controller
{
    protected WasteRepositoryInterface $wasteRepository;
    protected WasteService $wasteService;

    public function __construct(WasteRepositoryInterface $wasteRepository, WasteService $wasteService)
    {
        $this->wasteRepository = $wasteRepository;
        $this->wasteService = $wasteService;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['keyword', 'kategori', 'bahaya']);
        $wastes = $this->wasteRepository->getPaginatedWastes($filters);

        return view('wastes.index', compact('wastes'));
    }

    public function create()
    {
        return view('wastes.form', ['mode' => 'create']);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_limbah' => 'required|unique:wastes,kode_limbah',
            'nama_limbah' => 'required',
            'kategori' => 'required|in:Padat,Cair,Gas,Infeksius',
            'sifat_bahaya' => 'nullable|array',
            'cara_penanganan' => 'nullable',
            'prosedur_darurat' => 'nullable',
            'gambar_panduan' => 'nullable|image|max:2048'
        ]);

        $this->wasteService->createWaste($validated, $request->file('gambar_panduan'));

        return redirect()->route('wastes.index')->with('success', 'Katalog limbah berhasil ditambahkan!');
    }

    public function show(int $id)
    {
        $waste = $this->wasteRepository->findById($id);
        return view('wastes.show', compact('waste'));
    }

    public function edit(int $id)
    {
        $waste = $this->wasteRepository->findById($id);
        return view('wastes.form', ['mode' => 'edit', 'waste' => $waste]);
    }

    public function update(Request $request, int $id)
    {
        $waste = $this->wasteRepository->findById($id);

        $validated = $request->validate([
            'kode_limbah' => 'required|unique:wastes,kode_limbah,'.$waste->id,
            'nama_limbah' => 'required',
            'kategori' => 'required|in:Padat,Cair,Gas,Infeksius',
            'sifat_bahaya' => 'nullable|array',
            'cara_penanganan' => 'nullable',
            'prosedur_darurat' => 'nullable',
            'gambar_panduan' => 'nullable|image|max:2048'
        ]);

        $this->wasteService->updateWaste($waste, $validated, $request->file('gambar_panduan'));

        return redirect()->route('wastes.index')->with('success', 'Katalog limbah berhasil diperbarui!');
    }

    public function destroy(int $id)
    {
        $waste = $this->wasteRepository->findById($id);
        $this->wasteService->deleteWaste($waste);

        return redirect()->route('wastes.index')->with('success', 'Katalog limbah berhasil dihapus!');
    }
}
