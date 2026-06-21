@extends('layouts.app')

@section('title', 'Edit Jadwal')

@section('content')
    <div class="px-2 pb-8">
        {{-- Header with Back Button --}}
        <div class="mb-6">
            <a href="{{ route('lab') }}"
                class="inline-flex items-center gap-2 text-sm font-semibold text-zinc-500 hover:text-zinc-700 transition-colors mb-4">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali ke Jadwal
            </a>
            <h1 class="text-2xl font-bold tracking-tight text-zinc-900">Edit Jadwal</h1>
            <p class="text-sm font-medium text-zinc-500 mt-1">Perbarui informasi jadwal pemakaian laboratorium.</p>
        </div>

        {{-- Validation Errors handle by global Toast --}}

        {{-- Form Card --}}
        <div class="bg-white border border-zinc-200/80 rounded-2xl shadow-sm overflow-hidden">
            <form action="{{ route('editJadwal', $jadwal->id) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Section: Informasi Mata Kuliah --}}
                <div class="px-5 md:px-8 py-6 border-b border-zinc-100">
                    <h3 class="text-sm font-bold text-zinc-900 mb-1">Informasi Mata Kuliah</h3>
                    <p class="text-xs font-medium text-zinc-400 mb-5">Detail mata kuliah yang dijadwalkan.</p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        {{-- Mata Kuliah --}}
                        <div>
                            <label for="mata_kuliah" class="block text-xs font-bold text-zinc-700 mb-1.5">
                                Mata Kuliah <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="mata_kuliah" id="mata_kuliah"
                                value="{{ old('mata_kuliah', $jadwal->mata_kuliah) }}"
                                placeholder="cth. Biologi Dasar"
                                class="block w-full h-12 md:h-10 px-3.5 text-sm md:text-xs font-medium text-zinc-800 border border-zinc-200 rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 shadow-sm transition-colors placeholder:text-zinc-400"
                                required>
                        </div>

                        {{-- Dosen Pengampu --}}
                        <div>
                            <label for="dosen" class="block text-xs font-bold text-zinc-700 mb-1.5">
                                Dosen Pengampu <span class="text-rose-500">*</span>
                            </label>
                            <select id="dosen_id" name="dosen_id"
                                class="block w-full h-12 md:h-10 px-3.5 text-sm md:text-xs font-medium text-zinc-800 border border-zinc-200 rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 shadow-sm transition-colors appearance-none"
                                required>
                                <option value="" disabled>Pilih dosen pengampu</option>
                                @foreach ($user as $dosen)
                                    <option value="{{ $dosen->id }}"
                                        {{ old('dosen_id', $jadwal->dosen_id) == $dosen->id ? 'selected' : '' }}>
                                        {{ $dosen->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Kelas & Semester --}}
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="kelas" class="block text-xs font-bold text-zinc-700 mb-1.5">
                                    Kelas <span class="text-rose-500">*</span>
                                </label>
                                <input type="text" name="kelas" id="kelas"
                                    value="{{ old('kelas', $jadwal->kelas) }}"
                                    placeholder="cth. A"
                                    class="block w-full h-12 md:h-10 px-3.5 text-sm md:text-xs font-medium text-zinc-800 border border-zinc-200 rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 shadow-sm transition-colors placeholder:text-zinc-400"
                                    required>
                            </div>
                            <div>
                                <label for="semester" class="block text-xs font-bold text-zinc-700 mb-1.5">
                                    Semester <span class="text-rose-500">*</span>
                                </label>
                                <input type="text" name="semester" id="semester"
                                    value="{{ old('semester', $jadwal->semester) }}"
                                    placeholder="cth. 3"
                                    class="block w-full h-12 md:h-10 px-3.5 text-sm md:text-xs font-medium text-zinc-800 border border-zinc-200 rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 shadow-sm transition-colors placeholder:text-zinc-400"
                                    required>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Section: Waktu Pelaksanaan --}}
                <div class="px-5 md:px-8 py-6 border-b border-zinc-100">
                    <h3 class="text-sm font-bold text-zinc-900 mb-1">Waktu Pelaksanaan</h3>
                    <p class="text-xs font-medium text-zinc-400 mb-5">Atur tanggal dan jam pelaksanaan praktikum.</p>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        {{-- Tanggal --}}
                        <div>
                            <label for="tanggal_jadwal" class="block text-xs font-bold text-zinc-700 mb-1.5">
                                Tanggal <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none">
                                    <svg class="w-4 h-4 text-zinc-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                    </svg>
                                </div>
                                <input datepicker datepicker-autohide datepicker-min-date="today" type="text" name="tanggal_jadwal" id="tanggal_jadwal"
                                    value="{{ old('tanggal_jadwal', $jadwal->tanggal_jadwal) }}" placeholder="Pilih tanggal" datepicker-format="yyyy-mm-dd"
                                    min="{{ now()->format('Y-m-d') }}"
                                    class="block w-full h-12 md:h-10 pl-10 pr-3.5 text-sm md:text-xs font-medium text-zinc-800 border border-zinc-200 rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 shadow-sm transition-colors placeholder:text-zinc-400"
                                    required>
                            </div>
                        </div>

                        {{-- Waktu Mulai --}}
                        <div>
                            <label for="waktu_mulai" class="block text-xs font-bold text-zinc-700 mb-1.5">
                                Waktu Mulai <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3.5 pointer-events-none">
                                    <svg class="w-4 h-4 text-zinc-400" fill="currentColor" viewBox="0 0 24 24">
                                        <path fill-rule="evenodd" d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4a1 1 0 1 0-2 0v4a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414L13 11.586V8Z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <input type="time" id="waktu_mulai" name="waktu_mulai"
                                    value="{{ old('waktu_mulai', $jadwal->waktu_mulai) }}"
                                    class="block w-full h-12 md:h-10 px-3.5 text-sm md:text-xs font-medium text-zinc-800 border border-zinc-200 rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 shadow-sm transition-colors"
                                    min="06:00" max="23:59" required>
                            </div>
                        </div>

                        {{-- Waktu Selesai --}}
                        <div>
                            <label for="waktu_selesai" class="block text-xs font-bold text-zinc-700 mb-1.5">
                                Waktu Selesai <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3.5 pointer-events-none">
                                    <svg class="w-4 h-4 text-zinc-400" fill="currentColor" viewBox="0 0 24 24">
                                        <path fill-rule="evenodd" d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4a1 1 0 1 0-2 0v4a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414L13 11.586V8Z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <input type="time" id="waktu_selesai" name="waktu_selesai"
                                    value="{{ old('waktu_selesai', $jadwal->waktu_selesai) }}"
                                    class="block w-full h-12 md:h-10 px-3.5 text-sm md:text-xs font-medium text-zinc-800 border border-zinc-200 rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 shadow-sm transition-colors"
                                    min="06:00" max="23:59" required>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Form Actions --}}
                <div class="px-5 md:px-8 py-5 bg-zinc-50/50 flex flex-col-reverse sm:flex-row items-stretch sm:items-center justify-end gap-3">
                    <a href="{{ route('lab') }}"
                        class="inline-flex items-center justify-center h-11 md:h-10 px-5 text-sm md:text-xs font-semibold text-zinc-700 bg-white border border-zinc-200 rounded-xl hover:bg-zinc-50 transition-colors shadow-sm">
                        Batal
                    </a>
                    <button type="submit"
                        class="inline-flex items-center justify-center h-11 md:h-10 px-6 text-sm md:text-xs font-semibold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Perbarui Jadwal
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
