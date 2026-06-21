@extends('layouts.app')

@section('title', 'Pengaturan Akun')

@section('content')
<div class="px-2 pb-8 min-h-[calc(100vh-12rem)] flex flex-col">
    {{-- Header Section --}}
    <x-ui.page-header title="Pengaturan Akun" description="Kelola informasi pribadi dan keamanan akun Anda." />

    {{-- Alert Messages --}}
    @if(session('success'))
        <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-100 flex items-start gap-3">
            <div class="mt-0.5 w-6 h-6 rounded-full bg-emerald-100 flex items-center justify-center shrink-0">
                <svg class="w-3.5 h-3.5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
            </div>
            <div>
                <h4 class="text-sm font-bold text-emerald-800">Berhasil!</h4>
                <p class="text-xs text-emerald-600 font-medium mt-0.5">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if(session('info'))
        <div class="mb-6 p-4 rounded-xl bg-blue-50 border border-blue-100 flex items-start gap-3">
            <div class="mt-0.5 w-6 h-6 rounded-full bg-blue-100 flex items-center justify-center shrink-0">
                <svg class="w-3.5 h-3.5 text-blue-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" /></svg>
            </div>
            <div>
                <p class="text-xs text-blue-700 font-medium mt-0.5">{{ session('info') }}</p>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8">
        {{-- Kolom Kiri: Info Readonly --}}
        <div class="md:col-span-1 space-y-6">
            <div class="bg-white border border-zinc-200 shadow-sm rounded-2xl p-5 lg:p-6">
                <h3 class="text-base font-bold text-zinc-800 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
                    Informasi Terkunci
                </h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-zinc-500 mb-1.5 uppercase tracking-wide">Nama Lengkap</label>
                        <div class="px-4 py-3 bg-zinc-50 border border-zinc-200 rounded-xl text-sm font-semibold text-zinc-700 flex items-center gap-2">
                            <svg class="w-4 h-4 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            {{ $user->name }}
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-zinc-500 mb-1.5 uppercase tracking-wide">NIM / NIP</label>
                        <div class="px-4 py-3 bg-zinc-50 border border-zinc-200 rounded-xl text-sm font-semibold text-zinc-700 flex items-center gap-2">
                            <svg class="w-4 h-4 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            {{ $user->nim ?? '-' }}
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-zinc-500 mb-1.5 uppercase tracking-wide">Jabatan / Role</label>
                        <div class="px-4 py-3 bg-zinc-50 border border-zinc-200 rounded-xl text-sm font-semibold text-zinc-700 flex items-center gap-2">
                            <svg class="w-4 h-4 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            {{ $user->jabatan }}
                        </div>
                    </div>
                    
                    <p class="text-[11px] text-zinc-500 font-medium leading-relaxed mt-2 bg-indigo-50/50 p-3 rounded-lg border border-indigo-100">
                        <span class="font-bold text-indigo-700">Catatan:</span> Informasi di atas dikunci oleh sistem. Silakan hubungi Administrator jika terdapat kesalahan data diri.
                    </p>
                </div>
            </div>
        </div>

        {{-- Kolom Kanan: Form Update --}}
        <div class="md:col-span-2 space-y-6">
            <div class="bg-white border border-zinc-200 shadow-sm rounded-2xl p-5 lg:p-6">
                <h3 class="text-base font-bold text-zinc-800 mb-1 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    Ubah Data Akun
                </h3>
                <p class="text-xs text-zinc-500 font-medium mb-6">Ubah email dan password untuk keperluan login Anda.</p>
                
                <form action="{{ route('profile.settings.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="space-y-5">
                        {{-- Email --}}
                        <div>
                            <label for="email" class="block text-xs font-bold text-zinc-700 mb-1.5">Alamat Email</label>
                            <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                                class="w-full h-10 px-3 text-sm font-medium bg-white border border-zinc-300 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-colors
                                @error('email') border-rose-300 focus:border-rose-500 focus:ring-rose-500/20 @enderror">
                            @error('email')
                                <p class="mt-1.5 text-xs font-semibold text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Password --}}
                        <div>
                            <label for="password" class="block text-xs font-bold text-zinc-700 mb-1.5">Password Baru</label>
                            <input type="password" id="password" name="password" placeholder="Kosongkan jika tidak ingin mengubah password"
                                class="w-full h-10 px-3 text-sm font-medium bg-white border border-zinc-300 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-colors
                                @error('password') border-rose-300 focus:border-rose-500 focus:ring-rose-500/20 @enderror">
                            @error('password')
                                <p class="mt-1.5 text-xs font-semibold text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="pt-4 flex items-center justify-end border-t border-zinc-100">
                            <button type="submit" class="h-10 px-5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
