@extends('layouts.app')

@section('title', 'Jadwalkan Praktikum')

@section('content')
    <div class="px-4 py-8 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <x-ui.page-header title="Jadwalkan Praktikum"
            description="Lengkapi formulir di bawah ini untuk memesan ruangan laboratorium. Pastikan jadwal tidak bertabrakan dengan pengguna lain." />

        <x-ui.toast />

        <form action="{{ route('addJadwal') }}" method="POST" x-data="{
                waktuMulai: '{{ old('waktu_mulai', '08:00') }}',
                waktuSelesai: '{{ old('waktu_selesai', '10:00') }}',
                isSubmitting: false,
                get isTimeValid() {
                    if(!this.waktuMulai || !this.waktuSelesai) return true;
                    return this.waktuMulai < this.waktuSelesai;
                }
            }" @submit="isSubmitting = true" class="space-y-8">
            @csrf

            <!-- SECTION 1: Detail Praktikum -->
            <div class="grid grid-cols-1 gap-x-8 gap-y-10 md:grid-cols-3 bg-surface p-6 sm:p-8 rounded-2xl shadow-sm border border-default/60">
                <div>
                    <h2 class="text-base font-semibold leading-7 text-foreground">Detail Praktikum</h2>
                    <p class="mt-1 text-sm leading-6 text-foreground-muted">Informasi spesifik mata kuliah, kelas, dan dosen pengampu.</p>
                </div>

                <div class="grid max-w-2xl grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-6 md:col-span-2">
                    <div class="col-span-full">
                        <x-input-label for="mata_kuliah" value="Mata Kuliah" class="mb-1" />
                        <x-ui.input id="mata_kuliah" name="mata_kuliah" type="text" :value="old('mata_kuliah')" required
                            placeholder="Contoh: Praktikum Biologi Dasar" class="!bg-surface-muted !border-default focus:!bg-surface focus:!border-primary" />
                        <x-input-error :messages="$errors->get('mata_kuliah')" class="mt-2 text-danger" />
                    </div>

                    <div class="col-span-full">
                        <x-input-label for="submateri" value="Submateri / Topik (Opsional)" class="mb-1" />
                        <x-ui.input id="submateri" name="submateri" type="text" :value="old('submateri')"
                            placeholder="Contoh: Pengamatan Struktur Sel" class="!bg-surface-muted !border-default focus:!bg-surface focus:!border-primary" />
                        <x-input-error :messages="$errors->get('submateri')" class="mt-2 text-danger" />
                    </div>

                    <div class="col-span-full sm:col-span-4">
                        <x-input-label for="dosen_id" value="Dosen Pengampu" class="mb-1" />
                        <select id="dosen_id" name="dosen_id"
                            class="block w-full h-11 md:h-10 text-sm md:text-xs font-medium rounded-xl text-foreground border-default bg-surface-muted focus:bg-surface focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary shadow-sm transition-colors"
                            required>
                            <option value="" disabled selected>Pilih dosen...</option>
                            @foreach ($user as $dosen)
                                <option value="{{ $dosen->id }}" {{ old('dosen_id') == $dosen->id ? 'selected' : '' }}>
                                    {{ $dosen->name }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('dosen_id')" class="mt-2 text-danger" />
                    </div>

                    <div class="col-span-3 sm:col-span-1">
                        <x-input-label for="kelas" value="Kelas" class="mb-1" />
                        <x-ui.input id="kelas" name="kelas" type="text" :value="old('kelas')" required
                            placeholder="Cth: A" class="!bg-surface-muted !border-default focus:!bg-surface focus:!border-primary" />
                        <x-input-error :messages="$errors->get('kelas')" class="mt-2 text-danger" />
                    </div>

                    <div class="col-span-3 sm:col-span-1">
                        <x-input-label for="semester" value="Semester" class="mb-1" />
                        <x-ui.input id="semester" name="semester" type="number" :value="old('semester')" required min="1"
                            max="14" placeholder="Cth: 3" class="!bg-surface-muted !border-default focus:!bg-surface focus:!border-primary" />
                        <x-input-error :messages="$errors->get('semester')" class="mt-2 text-danger" />
                    </div>
                </div>
            </div>

            <!-- SECTION 2: Ruangan -->
            <div class="grid grid-cols-1 gap-x-8 gap-y-10 md:grid-cols-3 bg-surface p-6 sm:p-8 rounded-2xl shadow-sm border border-default/60">
                <div>
                    <h2 class="text-base font-semibold leading-7 text-foreground">Pemilihan Ruangan</h2>
                    <p class="mt-1 text-sm leading-6 text-foreground-muted">Tentukan ruangan laboratorium yang akan digunakan untuk
                        praktikum ini.</p>
                </div>

                <div class="max-w-2xl md:col-span-2">
                    @error('ruangan_id')
                        <div class="mb-4 p-3 rounded-lg bg-danger-soft text-sm text-danger font-medium">
                            {{ $message }}
                        </div>
                    @enderror

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @forelse ($ruangans ?? [] as $ruang)
                            <label
                                class="relative flex flex-col p-4 border border-default bg-surface rounded-xl cursor-pointer hover:border-primary has-[:checked]:border-primary has-[:checked]:bg-primary-soft/20 has-[:checked]:ring-1 has-[:checked]:ring-primary transition-all group">
                                <input type="radio" name="ruangan_id" value="{{ $ruang->id }}" class="sr-only" {{ old('ruangan_id') == $ruang->id ? 'checked' : '' }} required>
                                <div class="flex items-center justify-between mb-2">
                                    <span
                                        class="material-symbols-rounded text-foreground-muted group-has-[:checked]:text-primary">meeting_room</span>
                                    <div
                                        class="w-4 h-4 rounded-full border border-default group-has-[:checked]:border-primary flex items-center justify-center bg-surface">
                                        <div
                                            class="w-2 h-2 rounded-full bg-primary hidden group-has-[:checked]:block">
                                        </div>
                                    </div>
                                </div>
                                <h4 class="text-sm font-semibold text-foreground">
                                    {{ $ruang->nama_ruangan ?? 'Ruangan ' . $ruang->id }}
                                </h4>
                                <p class="text-xs text-foreground-muted mt-1">Kapasitas: {{ $ruang->kapasitas ?? '30' }} Orang</p>
                            </label>
                        @empty
                            <div class="col-span-full p-6 border border-dashed border-default bg-surface-muted rounded-xl text-center">
                                <span class="material-symbols-rounded text-foreground-muted text-3xl">meeting_room</span>
                                <h4 class="text-sm font-medium text-foreground mt-2">Ruangan Tidak Tersedia</h4>
                                <p class="text-xs text-foreground-muted mt-1">Sistem belum memiliki data ruangan laboratorium.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- SECTION 3: Waktu Pelaksanaan -->
            <div class="grid grid-cols-1 gap-x-8 gap-y-10 md:grid-cols-3 bg-surface p-6 sm:p-8 rounded-2xl shadow-sm border border-default/60">
                <div>
                    <h2 class="text-base font-semibold leading-7 text-foreground">Waktu Pelaksanaan</h2>
                    <p class="mt-1 text-sm leading-6 text-foreground-muted">Tentukan tanggal dan jam (mulai hingga selesai).</p>
                </div>

                <div class="grid max-w-2xl grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-6 md:col-span-2">
                    <div class="col-span-full sm:col-span-3">
                        <x-input-label for="tanggal_jadwal" value="Tanggal" class="mb-1" />
                        <x-ui.date-picker id="tanggal_jadwal" name="tanggal_jadwal" :value="old('tanggal_jadwal')" required placeholder="Pilih tanggal" />
                        <!-- Error dipesan khusus agar tidak dirender dari backend sesuai instruksi -->
                    </div>
                    <div class="hidden sm:block sm:col-span-3"></div> <!-- Spacer -->

                    <div class="col-span-3 sm:col-span-2">
                        <x-input-label for="waktu_mulai" value="Waktu Mulai" class="mb-1" />
                        <x-ui.input id="waktu_mulai" name="waktu_mulai" type="time" x-model="waktuMulai" required
                            min="06:00" max="23:59" class="!bg-surface-muted !border-default focus:!bg-surface focus:!border-primary" />
                        <x-input-error :messages="$errors->get('waktu_mulai')" class="mt-2 text-danger" />
                    </div>

                    <div class="col-span-3 sm:col-span-2">
                        <x-input-label for="waktu_selesai" value="Waktu Selesai" class="mb-1" />
                        <x-ui.input id="waktu_selesai" name="waktu_selesai" type="time" x-model="waktuSelesai" required
                            min="06:00" max="23:59" class="!bg-surface-muted !border-default focus:!bg-surface focus:!border-primary" />

                        <div x-show="!isTimeValid" x-transition class="mt-2 text-[11px] font-medium text-danger bg-danger-soft p-1.5 rounded-md border border-danger/20">
                            Waktu selesai harus lebih besar.
                        </div>
                        <x-input-error x-show="isTimeValid" :messages="$errors->get('waktu_selesai')" class="mt-2 text-danger" />
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end gap-x-4 pt-4">
                <a href="{{ route('lab') }}"
                    class="text-sm font-semibold leading-6 text-foreground hover:text-foreground-muted">Batalkan</a>
                <x-primary-button type="submit" x-bind:disabled="!isTimeValid || isSubmitting" class="bg-primary hover:opacity-90 text-primary-foreground focus:ring-primary/50">
                    <span x-show="!isSubmitting">Simpan Jadwal</span>
                    <span x-show="isSubmitting" style="display: none;" class="flex items-center gap-2">
                        <svg class="animate-spin h-4 w-4 text-primary-foreground" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                        Menyimpan...
                    </span>
                </x-primary-button>
            </div>
        </form>
    </div>
@endsection