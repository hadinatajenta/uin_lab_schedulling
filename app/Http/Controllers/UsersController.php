<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Jaslab;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class UsersController extends Controller
{
    public function usersView(Request $request)
    {
        $keyword = $request->input('keyword');
        if (isset($keyword)) {
            $users = User::where('name', 'LIKE', "%{$keyword}%")
                ->orWhere('email', 'LIKE', "%{$keyword}%")->paginate(10);
        } else {
            $users = User::all();
        }
        $jumlahUser = User::all();
        $dosen = User::where('jabatan', 'dosen')->get();
        $admin = User::where('jabatan', 'admin lab')->get();
        $mahasiswa = User::where('jabatan', 'Mahasiswa')->get();
        $jaslab = Jaslab::all();
        return view('admin.users', compact('users', 'jumlahUser', 'dosen', 'admin', 'jaslab', 'mahasiswa'));
    }

    public function addUser(Request $request)
    {
        $request->validate([
            'name' => ['required'],
            'email' => ['email', 'required', 'unique:users,email'],
            'password' => ['required']
        ]);

        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->jabatan = 'Mahasiswa';
        $user->password = Hash::make($request->input('password'));

        if ($user) {
            $user->save();
            return redirect()->back()->with('success', 'Berhasil tambah pengguna!');
        } else {
            return redirect()->back()->with('error', 'Gagal Menambah Pengguna!');

        }
    }

    public function usersUpdate(Request $request, $id)
    {
        $users = User::find($id);

        $request->validate([
            'name' => ['required']
        ]);

        if ($users) {
            $users->name = $request->input('name');
            $users->jabatan = $request->input('jabatan');
            $users->save();
            return redirect()->back()->with('success', 'Berhasil perbarui data pengguna!');
        } else {
            return redirect()->back()->with('error', 'Pengguna tidak ditemukan!');

        }
    }

    public function deleteUser($id)
    {
        $user = User::find($id);
        if ($user) {
            $user->delete();
            return redirect()->back()->with('success', 'Berhasil hapus pengguna!');
        } else {
            return redirect()->back()->with('error', ['Gagal hapus pengguna!', $user->getMessage()]);
        }
    }

    public function ubahJaslab(Request $request, $id)
    {
        $jaslab = Jaslab::find($id);
        if ($jaslab) {
            $jaslab->warna = $request->input('warna');
            $jaslab->save();
            return redirect()->back()->with('success', 'Berhasil perbarui warna jaslab!');
        } else {
            return redirect()->back()->with('error', 'Informasi jaslab tidak ditemukan!');
        }
    }
}
