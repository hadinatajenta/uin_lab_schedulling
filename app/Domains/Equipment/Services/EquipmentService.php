<?php

namespace App\Domains\Equipment\Services;

use App\Domains\Equipment\Models\Equipment;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\WebpEncoder;

class EquipmentService
{
    public function createEquipment(array $data, ?array $images): Equipment
    {
        $equipment = new Equipment();
        $this->fillEquipmentData($equipment, $data);
        
        if ($images) {
            $equipment->gambar = $this->processImages($images);
        }

        $equipment->save();
        return $equipment;
    }

    public function updateEquipment(Equipment $equipment, array $data, ?array $images): Equipment
    {
        $this->fillEquipmentData($equipment, $data);

        if ($images) {
            $this->deleteImages($equipment->gambar);
            $equipment->gambar = $this->processImages($images);
        }

        $equipment->save();
        return $equipment;
    }

    public function deleteEquipment(Equipment $equipment): void
    {
        $this->deleteImages($equipment->gambar);
        $equipment->delete();
    }

    private function fillEquipmentData(Equipment $equipment, array $data): void
    {
        $equipment->nama_alat = $data['nama_alat'];
        $equipment->jenis_alat = $data['jenis_alat'];
        $equipment->deskripsi = $data['deskripsi'] ?? null;
        $equipment->spesifikasi = $data['spesifikasi'] ?? null;
        $equipment->kondisi = $data['kondisi'] ?? null;
        $equipment->cara_penggunaan = $data['cara_penggunaan'] ?? null;
        $equipment->link_youtube = $data['link_youtube'] ?? null;
        $equipment->tanggal_pembelian = $data['tanggal_pembelian'] ?? null;

        if ($equipment->jenis_alat == 'Alat') {
            $equipment->jumlah_satuan = $data['jumlah_satuan'] ?? null;
            $equipment->jumlah_ml = null;
            $equipment->tanggal_expired = null;
        } elseif ($equipment->jenis_alat == 'Bahan') {
            $equipment->jumlah_ml = $data['jumlah_ml'] ?? null;
            $equipment->jumlah_satuan = null;
            $equipment->tanggal_expired = $data['tanggal_expired'] ?? null;
        }
    }

    private function processImages(array $images): array
    {
        $manager = new ImageManager(new Driver());
        $paths = [];
        
        foreach ($images as $image) {
            $imageName = time() . '_' . uniqid() . '.webp';
            
            $img = $manager->decode($image);
            $img->scaleDown(1600, 1600);
            $encoded = $img->encode(new WebpEncoder(80));
            
            Storage::disk('public')->put('images/' . $imageName, (string) $encoded);
            
            $paths[] = 'images/' . $imageName;
        }
        
        return $paths;
    }

    private function deleteImages($gambar): void
    {
        if (empty($gambar)) {
            return;
        }

        if (is_array($gambar)) {
            foreach ($gambar as $path) {
                Storage::disk('public')->delete($path);
            }
        } elseif (is_string($gambar)) {
            Storage::disk('public')->delete($gambar);
        }
    }
}
