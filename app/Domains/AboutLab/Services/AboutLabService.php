<?php

namespace App\Domains\AboutLab\Services;

use App\Domains\AboutLab\Models\AboutLab;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class AboutLabService
{
    public function updateAboutLab(AboutLab $aboutLab, ?string $sop, ?UploadedFile $stukturImage): AboutLab
    {
        $aboutLab->sop = $sop;

        if ($stukturImage) {
            $imageName = time() . '.' . $stukturImage->getClientOriginalExtension();
            $path = $stukturImage->storeAs('images', $imageName, 'public');
            
            // Optional: delete old image if necessary
            // if ($aboutLab->stuktur && Storage::disk('public')->exists($aboutLab->stuktur)) {
            //     Storage::disk('public')->delete($aboutLab->stuktur);
            // }
            
            $aboutLab->stuktur = $path;
        }

        $aboutLab->save();
        return $aboutLab;
    }
}
