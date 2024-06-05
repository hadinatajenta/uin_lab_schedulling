<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AboutLab;

class AboutLabController extends Controller
{
    public function aboutLabView()
    {
        $aboutLab = AboutLab::first();
        if (!$aboutLab) {
            return abort(404, 'About Lab information not found');
        }
        return view('admin.AboutLab', compact('aboutLab'));
    }

    public function editInfoView(Request $request)
    {
        $aboutlab = AboutLab::first();
        return view('admin.EditAboutLab', compact('aboutlab'));
    }

    public function editInfo(Request $request)
    {
        $aboutLab = AboutLab::first();

        $request->validate([
            'sop' => 'nullable',
            'stuktur' => 'nullable'
        ]);

        if ($aboutLab) {
            $aboutLab->sop = $request->input('sop');

            if ($request->hasFile('stuktur')) {
                $image = $request->file('stuktur');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $path = $image->storeAs('images', $imageName, 'public');
                $aboutLab->stuktur = $path;
            }

            $aboutLab->save();
            return redirect()->route('tentangLab')->with('success', 'Berhasil memperbarui informasi LAB');
        }
        return redirect()->back()->with('error', 'Gagal Memperbarui Informasi LAB!');
    }
}
