<?php

namespace App\Domains\AboutLab\Controllers;

use App\Http\Controllers\Controller;
use App\Domains\AboutLab\Repositories\AboutLabRepositoryInterface;
use App\Domains\AboutLab\Services\AboutLabService;
use Illuminate\Http\Request;

class AboutLabController extends Controller
{
    protected AboutLabRepositoryInterface $aboutLabRepository;
    protected AboutLabService $aboutLabService;

    public function __construct(AboutLabRepositoryInterface $aboutLabRepository, AboutLabService $aboutLabService)
    {
        $this->aboutLabRepository = $aboutLabRepository;
        $this->aboutLabService = $aboutLabService;
    }

    public function aboutLabView()
    {
        $aboutLab = $this->aboutLabRepository->getFirst();
        if (!$aboutLab) {
            return abort(404, 'About Lab information not found');
        }
        return view('about-lab.show', compact('aboutLab'));
    }

    public function editInfoView(Request $request)
    {
        $aboutlab = $this->aboutLabRepository->getFirst();
        return view('about-lab.edit', compact('aboutlab'));
    }

    public function editInfo(Request $request)
    {
        $aboutLab = $this->aboutLabRepository->getFirst();

        $request->validate([
            'sop' => 'nullable',
            'stuktur' => 'nullable'
        ]);

        if ($aboutLab) {
            $this->aboutLabService->updateAboutLab(
                $aboutLab,
                $request->input('sop'),
                $request->file('stuktur')
            );
            
            return redirect()->route('tentangLab')->with('success', 'Berhasil memperbarui informasi LAB');
        }
        
        return redirect()->back()->with('error', 'Gagal Memperbarui Informasi LAB!');
    }
}
