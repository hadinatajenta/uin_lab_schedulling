@extends('layouts.app')

@section('title', 'Jadwalkan Praktikum')

@section('content')
    <div class="px-2 pb-12 max-w-4xl mx-auto" x-data="{
        waktuMulai: '{{ old('waktu_mulai', '08:00') }}',
        waktuSelesai: '{{ old('waktu_selesai', '10:00') }}',
        isSubmitting: false,
        get isTimeValid() {
            if(!this.waktuMulai || !this.waktuSelesai) return true;
            return this.waktuMulai < this.waktuSelesai;
        }
    }">
        {{-- Header --}}
        <div class="mb-8">
            <a href="{{ route('lab') }}"
                class="inline-flex items-center gap-2 text-sm font-semibold text-zinc-500 hover:text-[rgb(var(--color-primary))] transition-colors mb-4 group">
                <div class="w-8 h-8 rounded-full bg-zinc-100 flex items-center justify-center group-hover:bg-[rgb(var(--color-primary)_/_0.1)] transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                    </svg>
                </div>
                Kembali ke Daftar Jadwal
            </a>
            <h1 class="text-3xl font-black tracking-tight text-zinc-900">Jadwalkan Praktikum</h1>
            <p class="text-sm text-zinc-500 mt-2 max-w-xl leading-relaxed">
                Lengkapi formulir di bawah ini untuk memesan ruangan laboratorium. Pastikan jadwal tidak bertabrakan dengan pengguna lain.
            </p>
        </div>

        {{-- Interactive Global Error Toast --}}
        @if($errors->any())
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 6000)" 
                class="fixed bottom-6 right-6 z-50 p-4 rounded-2xl bg-rose-50 border border-rose-200 shadow-xl flex items-start gap-3 max-w-sm"
                x-transition:enter="transition ease-out duration-300 transform"
                x-transition:enter-start="opacity-0 translate-y-8 scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                x-transition:leave="transition ease-in duration-200 transform"
                x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                x-transition:leave-end="opacity-0 translate-y-8 scale-95">
                
                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-white shadow-sm flex items-center justify-center text-rose-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <h4 class="text-sm font-bold text-rose-900">Terdapat Kesalahan</h4>
                    <p class="text-xs font-medium text-rose-600 mt-1 leading-relaxed">Mohon periksa kembali isian formulir Anda. Beberapa data tidak valid atau belum diisi.</p>
                </div>
                <button @click="show = false" type="button" class="text-rose-400 hover:text-rose-700 bg-rose-100/50 hover:bg-rose-100 rounded-full p-1 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        @endif

        <form action="{{ route('addJadwal') }}" method="POST" @submit="isSubmitting = true" class="space-y-6">
            @csrf

            {{-- STEP 1: Detail Mata Kuliah --}}
            <div class="ui-surface border border-zinc-200/80 rounded-3xl shadow-sm overflow-hidden group">
                <div class="px-6 py-5 border-b border-zinc-100 bg-zinc-50/50 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-[rgb(var(--color-primary))] text-white flex items-center justify-center font-bold text-sm shadow-sm">
                        1
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-zinc-900">Detail Praktikum</h3>
                        <p class="text-xs font-medium text-zinc-500">Informasi spesifik mata kuliah dan kelas.</p>
                    </div>
                </div>
                
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Mata Kuliah --}}
                    <div class="md:col-span-2">
                        <label for="mata_kuliah" class="block text-xs font-bold text-zinc-700 mb-2 uppercase tracking-wider">
                            Mata Kuliah <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="mata_kuliah" id="mata_kuliah" value="{{ old('mata_kuliah') }}"
                            placeholder="Contoh: Praktikum Biologi Dasar"
                            class="block w-full h-12 px-4 text-sm font-medium text-zinc-800 bg-zinc-50 border {{ $errors->has('mata_kuliah') ? 'border-rose-300 focus:border-rose-500 focus:ring-rose-500/20' : 'border-zinc-200 focus:border-[rgb(var(--color-primary))] focus:ring-[rgb(var(--color-primary)_/_0.2)]' }} rounded-xl focus:outline-none focus:ring-2 focus:bg-white shadow-sm transition-all placeholder:text-zinc-400"
                            required>
                        @error('mata_kuliah')
                            <p class="text-rose-500 text-[11px] font-bold mt-2 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Submateri --}}
                    <div>
                        <label for="submateri" class="block text-xs font-bold text-zinc-700 mb-2 uppercase tracking-wider">
                            Submateri / Topik <span class="text-zinc-400 font-normal normal-case">(Opsional)</span>
                        </label>
                        <input type="text" name="submateri" id="submateri" value="{{ old('submateri') }}"
                            placeholder="Contoh: Pengamatan Struktur Sel"
                            class="block w-full h-12 px-4 text-sm font-medium text-zinc-800 bg-zinc-50 border {{ $errors->has('submateri') ? 'border-rose-300 focus:border-rose-500 focus:ring-rose-500/20' : 'border-zinc-200 focus:border-[rgb(var(--color-primary))] focus:ring-[rgb(var(--color-primary)_/_0.2)]' }} rounded-xl focus:outline-none focus:ring-2 focus:bg-white shadow-sm transition-all placeholder:text-zinc-400">
                        @error('submateri')
                            <p class="text-rose-500 text-[11px] font-bold mt-2 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Dosen Pengampu --}}
                    <div>
                        <label for="dosen_id" class="block text-xs font-bold text-zinc-700 mb-2 uppercase tracking-wider">
                            Dosen Pengampu <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <select id="dosen_id" name="dosen_id"
                                class="block w-full h-12 pl-4 pr-10 text-sm font-medium text-zinc-800 bg-zinc-50 border {{ $errors->has('dosen_id') ? 'border-rose-300 focus:border-rose-500 focus:ring-rose-500/20' : 'border-zinc-200 focus:border-[rgb(var(--color-primary))] focus:ring-[rgb(var(--color-primary)_/_0.2)]' }} rounded-xl focus:outline-none focus:ring-2 focus:bg-white shadow-sm transition-all appearance-none cursor-pointer"
                                required>
                                <option value="" disabled selected>Pilih dosen...</option>
                                @foreach ($user as $dosen)
                                    <option value="{{ $dosen->id }}" {{ old('dosen_id') == $dosen->id ? 'selected' : '' }}>
                                        {{ $dosen->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-zinc-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path></svg>
                            </div>
                        </div>
                        @error('dosen_id')
                            <p class="text-rose-500 text-[11px] font-bold mt-2 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Kelas & Semester --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="kelas" class="block text-xs font-bold text-zinc-700 mb-2 uppercase tracking-wider">
                                Kelas <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="kelas" id="kelas" value="{{ old('kelas') }}"
                                placeholder="Cth: A"
                                class="block w-full h-12 px-4 text-sm font-medium text-zinc-800 bg-zinc-50 border {{ $errors->has('kelas') ? 'border-rose-300 focus:border-rose-500 focus:ring-rose-500/20' : 'border-zinc-200 focus:border-[rgb(var(--color-primary))] focus:ring-[rgb(var(--color-primary)_/_0.2)]' }} rounded-xl focus:outline-none focus:ring-2 focus:bg-white shadow-sm transition-all placeholder:text-zinc-400"
                                required>
                            @error('kelas')
                                <p class="text-rose-500 text-[11px] font-bold mt-2 flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                        <div>
                            <label for="semester" class="block text-xs font-bold text-zinc-700 mb-2 uppercase tracking-wider">
                                Semester <span class="text-rose-500">*</span>
                            </label>
                            <input type="number" name="semester" id="semester" value="{{ old('semester') }}"
                                placeholder="Cth: 3"
                                class="block w-full h-12 px-4 text-sm font-medium text-zinc-800 bg-zinc-50 border {{ $errors->has('semester') ? 'border-rose-300 focus:border-rose-500 focus:ring-rose-500/20' : 'border-zinc-200 focus:border-[rgb(var(--color-primary))] focus:ring-[rgb(var(--color-primary)_/_0.2)]' }} rounded-xl focus:outline-none focus:ring-2 focus:bg-white shadow-sm transition-all placeholder:text-zinc-400"
                                required min="1" max="14">
                            @error('semester')
                                <p class="text-rose-500 text-[11px] font-bold mt-2 flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- STEP 2: Pilih Ruangan --}}
            <div class="ui-surface border border-zinc-200/80 rounded-3xl shadow-sm overflow-hidden group">
                <div class="px-6 py-5 border-b border-zinc-100 bg-zinc-50/50 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-[rgb(var(--color-primary))] text-white flex items-center justify-center font-bold text-sm shadow-sm">
                        2
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-zinc-900">Pilih Ruangan Laboratorium</h3>
                        <p class="text-xs font-medium text-zinc-500">Tentukan ruangan mana yang akan digunakan.</p>
                    </div>
                </div>
                
                <div class="p-6">
                    @error('ruangan_id')
                        <div class="mb-4 p-3 rounded-xl bg-rose-50 border border-rose-200 flex items-center gap-2">
                            <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <p class="text-[12px] font-bold text-rose-700">{{ $message }}</p>
                        </div>
                    @enderror

                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                        @forelse ($ruangans ?? [] as $ruang)
                            <label class="relative flex flex-col p-5 border-2 rounded-2xl cursor-pointer transition-all duration-200
                                has-[:checked]:border-[rgb(var(--color-primary))] has-[:checked]:bg-[rgb(var(--color-primary)_/_0.03)] has-[:checked]:shadow-md
                                hover:border-[rgb(var(--color-primary-soft))] border-zinc-200 bg-white group">
                                <input type="radio" name="ruangan_id" value="{{ $ruang->id }}" class="sr-only" {{ old('ruangan_id') == $ruang->id ? 'checked' : '' }} required>
                                
                                <div class="flex items-center justify-between mb-3">
                                    <div class="w-12 h-12 rounded-xl bg-zinc-100 flex items-center justify-center text-zinc-500 group-hover:text-[rgb(var(--color-primary))] group-hover:bg-[rgb(var(--color-primary)_/_0.1)] group-has-[:checked]:bg-[rgb(var(--color-primary))] group-has-[:checked]:text-white transition-colors">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                    </div>
                                    <div class="w-6 h-6 rounded-full border-2 border-zinc-300 group-has-[:checked]:border-[rgb(var(--color-primary))] group-has-[:checked]:bg-[rgb(var(--color-primary))] flex items-center justify-center transition-colors">
                                        <div class="w-2.5 h-2.5 rounded-full bg-white opacity-0 group-has-[:checked]:opacity-100 transition-opacity"></div>
                                    </div>
                                </div>
                                <h4 class="text-sm font-extrabold text-zinc-900 group-has-[:checked]:text-[rgb(var(--color-primary))] transition-colors">{{ $ruang->nama_ruangan ?? 'Ruangan ' . $ruang->id }}</h4>
                                <p class="text-[11px] font-bold uppercase tracking-wider text-zinc-400 mt-1.5 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                    Kapasitas: {{ $ruang->kapasitas ?? '30' }} Orang
                                </p>
                            </label>
                        @empty
                            <div class="col-span-full p-8 border-2 border-dashed border-zinc-300 rounded-2xl text-center bg-zinc-50">
                                <div class="w-16 h-16 rounded-full bg-zinc-200 text-zinc-400 flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                                </div>
                                <h4 class="text-sm font-bold text-zinc-900">Ruangan Tidak Tersedia</h4>
                                <p class="text-xs text-zinc-500 mt-1">Sistem belum memiliki data ruangan laboratorium.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- STEP 3: Waktu Pelaksanaan --}}
            <div class="ui-surface border border-zinc-200/80 rounded-3xl shadow-sm overflow-hidden group">
                <div class="px-6 py-5 border-b border-zinc-100 bg-zinc-50/50 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-[rgb(var(--color-primary))] text-white flex items-center justify-center font-bold text-sm shadow-sm">
                        3
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-zinc-900">Waktu Pelaksanaan</h3>
                        <p class="text-xs font-medium text-zinc-500">Tentukan tanggal dan jam penggunaan.</p>
                    </div>
                </div>
                
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        {{-- Tanggal --}}
                        <div>
                            <label for="tanggal_jadwal" class="block text-xs font-bold text-zinc-700 mb-2 uppercase tracking-wider">
                                Tanggal <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                                    <svg class="w-5 h-5 text-[rgb(var(--color-primary))] opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <input datepicker datepicker-autohide datepicker-min-date="today" type="text" name="tanggal_jadwal" id="tanggal_jadwal"
                                    value="{{ old('tanggal_jadwal') }}" placeholder="Pilih tanggal" datepicker-format="yyyy-mm-dd"
                                    min="{{ now()->format('Y-m-d') }}"
                                    class="block w-full h-12 pl-12 pr-4 text-sm font-bold text-zinc-800 bg-zinc-50 border {{ $errors->has('tanggal_jadwal') ? 'border-rose-300 focus:border-rose-500 focus:ring-rose-500/20' : 'border-zinc-200 focus:border-[rgb(var(--color-primary))] focus:ring-[rgb(var(--color-primary)_/_0.2)]' }} rounded-xl focus:outline-none focus:ring-2 focus:bg-white shadow-sm transition-all placeholder:text-zinc-400 placeholder:font-medium"
                                    required autocomplete="off">
                            </div>
                            @error('tanggal_jadwal')
                                <p class="text-rose-500 text-[11px] font-bold mt-2 flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Waktu Mulai --}}
                        <div>
                            <label for="waktu_mulai" class="block text-xs font-bold text-zinc-700 mb-2 uppercase tracking-wider">
                                Waktu Mulai <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                                    <svg class="w-5 h-5 text-[rgb(var(--color-primary))] opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <input type="time" id="waktu_mulai" name="waktu_mulai" x-model="waktuMulai"
                                    class="block w-full h-12 pl-12 pr-4 text-sm font-bold text-zinc-800 bg-zinc-50 border {{ $errors->has('waktu_mulai') ? 'border-rose-300 focus:border-rose-500 focus:ring-rose-500/20' : 'border-zinc-200 focus:border-[rgb(var(--color-primary))] focus:ring-[rgb(var(--color-primary)_/_0.2)]' }} rounded-xl focus:outline-none focus:ring-2 focus:bg-white shadow-sm transition-all"
                                    min="06:00" max="23:59" required>
                            </div>
                            @error('waktu_mulai')
                                <p class="text-rose-500 text-[11px] font-bold mt-2 flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Waktu Selesai --}}
                        <div>
                            <label for="waktu_selesai" class="block text-xs font-bold text-zinc-700 mb-2 uppercase tracking-wider">
                                Waktu Selesai <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                                    <svg class="w-5 h-5 text-[rgb(var(--color-primary))] opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <input type="time" id="waktu_selesai" name="waktu_selesai" x-model="waktuSelesai"
                                    class="block w-full h-12 pl-12 pr-4 text-sm font-bold text-zinc-800 bg-zinc-50 border {{ $errors->has('waktu_selesai') ? 'border-rose-300 focus:border-rose-500 focus:ring-rose-500/20' : 'border-zinc-200 focus:border-[rgb(var(--color-primary))] focus:ring-[rgb(var(--color-primary)_/_0.2)]' }} rounded-xl focus:outline-none focus:ring-2 focus:bg-white shadow-sm transition-all"
                                    min="06:00" max="23:59" required>
                            </div>
                            
                            {{-- Real-time validation message --}}
                            <div x-show="!isTimeValid" x-transition class="mt-2 p-2 rounded-lg bg-rose-50 border border-rose-100 flex items-start gap-2">
                                <svg class="w-4 h-4 text-rose-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <p class="text-[11px] font-bold text-rose-600 leading-tight">Waktu selesai harus lebih besar dari waktu mulai.</p>
                            </div>

                            @error('waktu_selesai')
                                <p class="text-rose-500 text-[11px] font-bold mt-2 flex items-center gap-1.5" x-show="isTimeValid">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex flex-col-reverse sm:flex-row items-stretch sm:items-center justify-end gap-4 mt-8">
                <a href="{{ route('lab') }}"
                    class="inline-flex items-center justify-center h-12 px-6 text-sm font-bold text-zinc-600 bg-white border border-zinc-200/80 rounded-xl hover:bg-zinc-50 hover:text-zinc-900 transition-colors shadow-sm">
                    Batalkan
                </a>
                <button type="submit"
                    :disabled="!isTimeValid || isSubmitting"
                    class="inline-flex items-center justify-center h-12 px-8 text-sm font-bold text-white bg-[rgb(var(--color-primary))] hover:opacity-90 rounded-xl transition-all shadow-md shadow-[rgb(var(--color-primary))_/_0.25] focus:outline-none focus:ring-4 focus:ring-[rgb(var(--color-primary)_/_0.15)] disabled:opacity-50 disabled:cursor-not-allowed group">
                    
                    <span x-show="!isSubmitting" class="flex items-center">
                        <svg class="w-5 h-5 mr-2 -ml-1 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                        </svg>
                        Konfirmasi & Simpan Jadwal
                    </span>
                    
                    <span x-show="isSubmitting" class="flex items-center" style="display: none;">
                        <svg class="w-5 h-5 mr-2 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Memproses...
                    </span>
                </button>
            </div>
        </form>
    </div>

    {{-- Script needed for datepicker logic --}}
    @push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/datepicker.min.js"></script>
    @endpush
@endsection
