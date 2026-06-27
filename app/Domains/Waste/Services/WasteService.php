<?php

namespace App\Domains\Waste\Services;

use App\Domains\Waste\Models\Waste;
use Illuminate\Support\Facades\Storage;

class WasteService
{
    public function createWaste(array $data, $image = null): Waste
    {
        if ($image) {
            $path = $image->store('wastes', 'public');
            $data['gambar_panduan'] = $path;
        }

        return Waste::create($data);
    }

    public function updateWaste(Waste $waste, array $data, $image = null): Waste
    {
        if ($image) {
            if ($waste->gambar_panduan && Storage::disk('public')->exists($waste->gambar_panduan)) {
                Storage::disk('public')->delete($waste->gambar_panduan);
            }
            $path = $image->store('wastes', 'public');
            $data['gambar_panduan'] = $path;
        }

        $waste->update($data);
        return $waste;
    }

    public function deleteWaste(Waste $waste): void
    {
        if ($waste->gambar_panduan && Storage::disk('public')->exists($waste->gambar_panduan)) {
            Storage::disk('public')->delete($waste->gambar_panduan);
        }
        
        $waste->delete();
    }
}
