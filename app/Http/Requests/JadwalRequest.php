<?php

namespace App\Http\Requests;

use App\Domains\Schedule\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class JadwalRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Asumsikan otorisasi sudah dihandle oleh middleware route
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'ruangan_id' => ['required', 'integer'],
            'mata_kuliah' => ['required', 'string', 'max:255'],
            'dosen_id' => ['required', 'integer', 'exists:users,id'],
            'kelas' => ['required', 'string', 'max:50'],
            'semester' => ['required', 'integer', 'min:1', 'max:14'],
            'tanggal_jadwal' => [
                'required', 
                'date', 
                'date_format:Y-m-d',
                'after_or_equal:today'
            ],
            'waktu_mulai' => [
                'required', 
                'date_format:H:i',
                function ($attribute, $value, $fail) {
                    // Validasi Jam Operasional Minimum (07:00)
                    if ($value < '07:00') {
                        $fail('Waktu mulai tidak boleh kurang dari jam 07:00.');
                    }

                    // Validasi Waktu Hari Ini (Jika tanggal = hari ini, jam tidak boleh lewat)
                    if ($this->tanggal_jadwal === Carbon::today()->format('Y-m-d')) {
                        if ($value < Carbon::now()->format('H:i')) {
                            $fail('Waktu mulai tidak boleh menggunakan jam yang sudah lewat pada hari ini.');
                        }
                    }
                }
            ],
            'waktu_selesai' => [
                'required', 
                'date_format:H:i', 
                'after:waktu_mulai',
                function ($attribute, $value, $fail) {
                    // Validasi Jam Operasional Maksimum (17:00)
                    if ($value > '17:00') {
                        $fail('Waktu selesai tidak boleh lebih dari jam 17:00.');
                    }

                    // Logika pencegahan tabrakan ruangan & dosen ganda
                    $this->validateOverlapping($fail);
                }
            ],
            'submateri' => ['nullable', 'string', 'max:255'],
        ];
    }

    /**
     * Custom method to handle complex overlapping checks.
     */
    protected function validateOverlapping($fail)
    {
        $ruanganId = $this->input('ruangan_id', 1); // Default ke 1 jika tidak ada
        $tanggal = $this->input('tanggal_jadwal');
        $waktuMulai = $this->input('waktu_mulai');
        $waktuSelesai = $this->input('waktu_selesai');
        $dosenId = $this->input('dosen_id');
        
        // Ignore the current schedule if we are editing
        $jadwalId = $this->route('id') ?: null;

        if (!$tanggal || !$waktuMulai || !$waktuSelesai) {
            return; // Skip if basic fields are missing (already caught by basic rules)
        }

        // Query untuk mengecek irisan waktu
        // Rumus Irisan Waktu: (mulai_baru < selesai_lama) AND (selesai_baru > mulai_lama)
        $conflicts = Schedule::where('tanggal_jadwal', $tanggal)
            ->when($jadwalId, function($query) use ($jadwalId) {
                return $query->where('id', '!=', $jadwalId);
            })
            ->where(function ($query) use ($waktuMulai, $waktuSelesai) {
                $query->where('waktu_mulai', '<', $waktuSelesai)
                      ->where('waktu_selesai', '>', $waktuMulai);
            })
            ->get();

        foreach ($conflicts as $conflict) {
            // 1. Cek Tabrakan Ruangan
            if ($conflict->ruangan_id == $ruanganId) {
                $fail("Jadwal bertabrakan dengan jadwal existing di Ruangan {$ruanganId} pada {$conflict->waktu_mulai}-{$conflict->waktu_selesai} ({$conflict->mata_kuliah}).");
                return; // Stop checking further if already failed
            }

            // 2. Cek Dosen Ganda (Dosen mengajar di ruangan lain di waktu bersamaan)
            if ($conflict->dosen_id == $dosenId) {
                $fail("Dosen terkait sudah memiliki jadwal mengajar pada jam {$conflict->waktu_mulai}-{$conflict->waktu_selesai} di Ruangan {$conflict->ruangan_id}.");
                return; // Stop checking further
            }
        }
    }

    /**
     * Custom message for validation rules.
     */
    public function messages(): array
    {
        return [
            'semester.min' => 'Semester minimal adalah 1.',
            'semester.max' => 'Semester maksimal adalah 14.',
            'waktu_selesai.after' => 'Waktu selesai harus lebih lambat daripada waktu mulai.',
            'tanggal_jadwal.after_or_equal' => 'Tanggal jadwal tidak boleh hari yang sudah lewat.',
        ];
    }
}
