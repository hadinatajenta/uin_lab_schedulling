<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ScheduleCreationTest extends TestCase
{
    /**
     * Test validation rules for JadwalRequest without writing to database.
     * We send an invalid request (waktu_selesai < waktu_mulai) which should be caught by JadwalRequest.
     */
    public function test_jadwal_validation_fails_when_end_time_before_start_time()
    {
        // Find a user or create a temporary one (in memory if possible, but we need to act as one)
        // To avoid affecting the real DB, we'll try to find an existing user or create a dummy and delete it.
        $user = User::first();
        $tempUser = false;
        
        if (!$user) {
            $user = User::create([
                'name' => 'Test User',
                'email' => 'test_temp_' . time() . '@example.com',
                'password' => Hash::make('password'),
            ]);
            $tempUser = true;
        }

        $response = $this->actingAs($user)->post('/admin/jadwal-baru', [
            'ruangan_id' => 1,
            'mata_kuliah' => 'Biologi Sel',
            'dosen_id' => 1,
            'kelas' => 'A',
            'semester' => 3,
            'tanggal_jadwal' => now()->addDays(2)->format('Y-m-d'),
            'waktu_mulai' => '10:00',
            'waktu_selesai' => '09:00', // Invalid: end time before start time
        ]);

        // Should return a redirect back with validation errors for waktu_selesai
        $response->assertSessionHasErrors(['waktu_selesai']);
        
        // Clean up temporary user if we created one
        if ($tempUser) {
            $user->delete();
        }
    }

    /**
     * Test required fields validation
     */
    public function test_jadwal_validation_fails_when_missing_required_fields()
    {
        $user = User::first();
        if (!$user) {
            $this->markTestSkipped('No user found in database to act as.');
        }

        $response = $this->actingAs($user)->post('/admin/jadwal-baru', [
            // Sending empty data
        ]);

        $response->assertSessionHasErrors([
            'ruangan_id', 'mata_kuliah', 'dosen_id', 'kelas', 'semester', 'tanggal_jadwal', 'waktu_mulai', 'waktu_selesai'
        ]);
    }
}
