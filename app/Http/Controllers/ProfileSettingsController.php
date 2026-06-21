<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileSettingsController extends Controller
{
    public function index()
    {
        return view('admin.profile-settings', [
            'user' => Auth::user(),
        ]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6',
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan oleh pengguna lain.',
            'password.min' => 'Password minimal 6 karakter.',
        ]);

        $changes = [];

        if ($user->email !== $request->email) {
            $changes[] = "mengubah email dari {$user->email} menjadi {$request->email}";
            $user->email = $request->email;
        }

        if ($request->filled('password')) {
            $changes[] = "mengubah password";
            $user->password = Hash::make($request->password);
        }

        if (!empty($changes)) {
            $user->save();

            // Log activity
            ActivityLog::create([
                'user_id' => $user->id,
                'action' => 'update_profile',
                'subject_type' => get_class($user),
                'subject_id' => $user->id,
                'description' => 'Pengguna ' . implode(' dan ', $changes),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return redirect()->back()->with('success', 'Pengaturan profil berhasil diperbarui.');
        }

        return redirect()->back()->with('info', 'Tidak ada perubahan yang dilakukan.');
    }
}
