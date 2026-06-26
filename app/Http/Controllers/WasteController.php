<?php

namespace App\Http\Controllers;

use App\Models\Waste;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class WasteController extends Controller
{
    public function index(Request $request)
    {
        $query = Waste::query();

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function($q) use ($keyword) {
                $q->where('nama_limbah', 'like', "%{$keyword}%")
                  ->orWhere('kode_limbah', 'like', "%{$keyword}%");
            });
        }

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        if ($request->filled('bahaya')) {
            $query->whereJsonContains('sifat_bahaya', $request->bahaya);
        }

        $wastes = $query->latest()->paginate(10)->withQueryString();
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

        if ($request->hasFile('gambar_panduan')) {
            $path = $request->file('gambar_panduan')->store('wastes', 'public');
            $validated['gambar_panduan'] = $path;
        }

        Waste::create($validated);

        return redirect()->route('wastes.index')->with('success', 'Katalog limbah berhasil ditambahkan!');
    }

    public function show($id)
    {
        $waste = Waste::with(['logs.schedule', 'logs' => function($q) {
            $q->latest();
        }])->findOrFail($id);

        return view('wastes.show', compact('waste'));
    }

    public function edit($id)
    {
        $waste = Waste::findOrFail($id);
        return view('wastes.form', ['mode' => 'edit', 'waste' => $waste]);
    }

    public function update(Request $request, $id)
    {
        $waste = Waste::findOrFail($id);

        $validated = $request->validate([
            'kode_limbah' => 'required|unique:wastes,kode_limbah,'.$waste->id,
            'nama_limbah' => 'required',
            'kategori' => 'required|in:Padat,Cair,Gas,Infeksius',
            'sifat_bahaya' => 'nullable|array',
            'cara_penanganan' => 'nullable',
            'prosedur_darurat' => 'nullable',
            'gambar_panduan' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('gambar_panduan')) {
            if ($waste->gambar_panduan && Storage::disk('public')->exists($waste->gambar_panduan)) {
                Storage::disk('public')->delete($waste->gambar_panduan);
            }
            $path = $request->file('gambar_panduan')->store('wastes', 'public');
            $validated['gambar_panduan'] = $path;
        }

        $waste->update($validated);

        return redirect()->route('wastes.index')->with('success', 'Katalog limbah berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $waste = Waste::findOrFail($id);
        
        if ($waste->gambar_panduan && Storage::disk('public')->exists($waste->gambar_panduan)) {
            Storage::disk('public')->delete($waste->gambar_panduan);
        }
        
        $waste->delete();

        return redirect()->route('wastes.index')->with('success', 'Katalog limbah berhasil dihapus!');
    }
}
