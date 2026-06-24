@extends('layouts.app')

@section('title', 'Import Pengguna')

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/xlsx/dist/xlsx.full.min.js"></script>
@endsection

@section('content')
    <div class="px-4 pb-8 min-h-[calc(100vh-12rem)] flex flex-col space-y-6" x-data="importUsers()">
        <x-ui.page-header title="Import Users (Bulk)" description="Upload Excel file to add multiple users at once.">
            <a href="{{ route('users.index') }}" class="inline-flex items-center text-sm font-semibold text-zinc-600 hover:text-[rgb(var(--color-primary))] transition-colors">
                <x-atoms.icon name="arrow-left" class="w-4 h-4 mr-1.5" />
                Kembali ke Daftar
            </a>
        </x-ui.page-header>

        {{-- Error Banner --}}
        <template x-if="errorMessage">
            <div class="p-4 bg-rose-50 border border-rose-200 rounded-xl text-sm font-semibold text-rose-600 flex items-center">
                <x-atoms.icon name="exclamation-circle" class="w-5 h-5 mr-2" />
                <span x-text="errorMessage"></span>
            </div>
        </template>

        {{-- Step 1: Upload --}}
        <div x-show="step === 1" class="ui-surface border border-zinc-200/80 rounded-3xl p-6 shadow-sm">
            <div class="max-w-xl mx-auto space-y-8 py-4">
                
                <div class="space-y-4">
                    <h3 class="text-base font-bold text-zinc-800">1. Pilih Tipe Data</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <label class="relative flex cursor-pointer rounded-2xl border ui-surface p-4 shadow-sm focus:outline-none"
                            :class="importType === 'staf' ? 'border-[rgb(var(--color-primary))] ring-1 ring-[rgb(var(--color-primary))]' : 'border-zinc-200 hover:bg-zinc-50'">
                            <input type="radio" x-model="importType" value="staf" class="sr-only">
                            <span class="flex flex-1">
                                <span class="flex flex-col">
                                    <span class="block text-sm font-bold text-zinc-900">Staf Akademik</span>
                                    <span class="mt-1 flex items-center text-xs text-zinc-500 font-medium">Admin Lab & Dosen</span>
                                </span>
                            </span>
                            <x-atoms.icon name="check-circle" class="h-5 w-5 text-[rgb(var(--color-primary))]" x-show="importType === 'staf'" />
                        </label>

                        <label class="relative flex cursor-pointer rounded-2xl border ui-surface p-4 shadow-sm focus:outline-none"
                            :class="importType === 'mahasiswa' ? 'border-[rgb(var(--color-primary))] ring-1 ring-[rgb(var(--color-primary))]' : 'border-zinc-200 hover:bg-zinc-50'">
                            <input type="radio" x-model="importType" value="mahasiswa" class="sr-only">
                            <span class="flex flex-1">
                                <span class="flex flex-col">
                                    <span class="block text-sm font-bold text-zinc-900">Mahasiswa</span>
                                    <span class="mt-1 flex items-center text-xs text-zinc-500 font-medium">Asisten & Mahasiswa</span>
                                </span>
                            </span>
                            <x-atoms.icon name="check-circle" class="h-5 w-5 text-[rgb(var(--color-primary))]" x-show="importType === 'mahasiswa'" />
                        </label>
                    </div>
                    <div class="flex items-center justify-between mt-2">
                        <p class="text-xs text-zinc-500 font-medium">Download template Excel/CSV untuk menyesuaikan data.</p>
                        <a :href="importType === 'staf' ? '/templates/template-staf.csv' : '/templates/template-mahasiswa.csv'"
                           class="inline-flex items-center text-xs font-bold text-[rgb(var(--color-primary))] hover:text-[rgb(var(--color-primary))] ui-primary-soft px-3 py-1.5 rounded-lg transition-colors">
                            <x-atoms.icon name="arrow-down-tray" class="w-3.5 h-3.5 mr-1" />
                            Download Template
                        </a>
                    </div>
                </div>

                <div class="space-y-4">
                    <h3 class="text-base font-bold text-zinc-800">2. Unggah File</h3>
                    <div class="mt-2 flex justify-center rounded-2xl border border-dashed border-zinc-300 px-6 py-12 transition-colors"
                         :class="isDragging ? 'border-[rgb(var(--color-primary))] ui-primary-soft' : 'hover:border-zinc-400 bg-zinc-50/50'"
                         @dragover.prevent="isDragging = true"
                         @dragleave.prevent="isDragging = false"
                         @drop.prevent="handleDrop">
                        <div class="text-center">
                            <x-atoms.icon name="document-plus" class="mx-auto h-10 w-10 text-zinc-400" />
                            <div class="mt-4 flex text-sm leading-6 text-zinc-600 justify-center">
                                <label for="file-upload" class="relative cursor-pointer rounded-md bg-transparent font-semibold text-[rgb(var(--color-primary))] hover:text-[rgb(var(--color-primary))] focus-within:outline-none focus-within:ring-2 focus-within:ring-[rgb(var(--color-primary))] focus-within:ring-offset-2">
                                    <span>Pilih file</span>
                                    <input id="file-upload" type="file" accept=".xlsx, .csv" class="sr-only" @change="handleFileSelect">
                                </label>
                                <p class="pl-1 font-medium">atau drag and drop</p>
                            </div>
                            <p class="text-xs leading-5 text-zinc-500 font-medium mt-1">Excel (.xlsx) atau CSV hingga 10MB</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- Step 2 & 3: Preview Table --}}
        <div x-show="step > 1" class="ui-surface border border-zinc-200/80 rounded-3xl p-6 shadow-sm" x-cloak>
            
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
                <div>
                    <h3 class="text-lg font-bold text-zinc-800">Preview Data</h3>
                    <p class="text-sm font-medium text-zinc-500 mt-1">File: <span class="text-zinc-900 font-bold" x-text="fileName"></span> (<span x-text="rows.length"></span> baris)</p>
                </div>

                <div class="flex items-center gap-3">
                    <button type="button" @click="step = 1; file = null; rows = []" class="px-4 py-2 text-sm font-semibold text-zinc-600 hover:bg-zinc-100 rounded-xl transition-colors">
                        Batal
                    </button>
                    <button type="button" @click="submitBulk" :disabled="!allValid || isSubmitting"
                        class="px-5 py-2 inline-flex items-center text-sm font-semibold text-white rounded-xl transition-all shadow-sm ui-primary shadow-[rgb(var(--color-primary))_/_0.1] focus:outline-none focus:ring-2 focus:ring-[rgb(var(--color-primary))] focus:ring-offset-2"
                        :class="(!allValid || isSubmitting) ? 'bg-[rgb(var(--color-primary))] opacity-50 cursor-not-allowed' : 'ui-primary hover:opacity-90'">
                        <span x-show="isSubmitting" class="material-symbols-rounded animate-spin text-[18px] mr-2">progress_activity</span>
                        <span x-text="isSubmitting ? 'Memproses...' : 'Proses & Simpan Data'"></span>
                    </button>
                </div>
            </div>

            {{-- Info Banner if Invalid --}}
            <template x-if="invalidCount > 0 && !isProcessing">
                <div class="mb-6 p-4 bg-amber-50 border border-amber-200 rounded-xl flex items-start">
                    <x-atoms.icon name="exclamation-triangle" class="w-5 h-5 mr-3 text-amber-500 shrink-0 mt-0.5" />
                    <div>
                        <h4 class="text-sm font-bold text-amber-800">Perhatian: Ada data yang tidak valid</h4>
                        <p class="text-xs font-medium text-amber-700 mt-1">Terdapat <span x-text="invalidCount"></span> baris yang memiliki masalah. Harap perbaiki langsung pada tabel di bawah (klik icon Edit) atau hapus baris yang bermasalah agar tombol Simpan dapat ditekan.</p>
                    </div>
                </div>
            </template>

            {{-- Table --}}
            <div class="relative overflow-x-auto rounded-2xl border border-zinc-200">
                <table class="w-full text-left text-sm text-zinc-600">
                    <thead class="ui-primary-soft text-[rgb(var(--color-primary))] text-xs font-bold uppercase border-b border-zinc-200">
                        <tr>
                            <th scope="col" class="px-4 py-3 w-16 text-center">No</th>
                            <th scope="col" class="px-4 py-3 min-w-[200px]">Nama Lengkap</th>
                            <th scope="col" class="px-4 py-3 min-w-[200px]">Email</th>
                            <template x-if="importType === 'staf'">
                                <th scope="col" class="px-4 py-3 min-w-[150px]">NIP</th>
                            </template>
                            <template x-if="importType === 'mahasiswa'">
                                <th scope="col" class="px-4 py-3 min-w-[150px]">NIM</th>
                            </template>
                            <th scope="col" class="px-4 py-3 min-w-[120px]">Kode Jurusan</th>
                            <th scope="col" class="px-4 py-3 min-w-[150px]">Peran</th>
                            <th scope="col" class="px-4 py-3 w-24 text-center">Status</th>
                            <th scope="col" class="px-4 py-3 w-28 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-if="isProcessing">
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center">
                                    <span class="material-symbols-rounded animate-spin text-[32px] text-[rgb(var(--color-primary))] mb-2">progress_activity</span>
                                    <p class="text-sm font-medium text-zinc-500">Sedang memvalidasi data...</p>
                                </td>
                            </tr>
                        </template>

                        <template x-if="!isProcessing && rows.length === 0">
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center">
                                    <p class="text-sm font-medium text-zinc-500">Tidak ada data ditemukan dalam file.</p>
                                </td>
                            </tr>
                        </template>

                        <template x-for="(row, index) in rows" :key="row.id">
                            <tr class="ui-surface border-b border-zinc-100 transition-colors" :class="{'bg-rose-50/30': !row.isValid, 'ui-primary-soft': row.isValid}">
                                <td class="px-4 py-3 text-center text-xs font-semibold text-zinc-500" x-text="index + 1"></td>
                                
                                {{-- Nama --}}
                                <td class="px-4 py-3">
                                    <template x-if="!row.editing">
                                        <div>
                                            <span class="text-sm font-medium text-zinc-900" x-text="row.name"></span>
                                            <template x-if="row.errors.name">
                                                <p class="text-xs font-bold text-rose-500 mt-0.5" x-text="row.errors.name"></p>
                                            </template>
                                        </div>
                                    </template>
                                    <template x-if="row.editing">
                                        <input type="text" x-model="row.name" class="block w-full text-xs font-medium border-zinc-300 rounded-lg focus:ring-[rgb(var(--color-primary))] focus:border-[rgb(var(--color-primary))] py-1.5 px-2">
                                    </template>
                                </td>

                                {{-- Email --}}
                                <td class="px-4 py-3">
                                    <template x-if="!row.editing">
                                        <div>
                                            <span class="text-sm font-medium text-zinc-600" x-text="row.email"></span>
                                            <template x-if="row.errors.email">
                                                <p class="text-xs font-bold text-rose-500 mt-0.5" x-text="row.errors.email"></p>
                                            </template>
                                        </div>
                                    </template>
                                    <template x-if="row.editing">
                                        <input type="email" x-model="row.email" class="block w-full text-xs font-medium border-zinc-300 rounded-lg focus:ring-[rgb(var(--color-primary))] focus:border-[rgb(var(--color-primary))] py-1.5 px-2">
                                    </template>
                                </td>

                                {{-- NIP (Staf) --}}
                                <template x-if="importType === 'staf'">
                                    <td class="px-4 py-3">
                                        <template x-if="!row.editing">
                                            <div>
                                                <span class="text-sm font-medium text-zinc-600" x-text="row.nip"></span>
                                                <template x-if="row.errors.nip">
                                                    <p class="text-xs font-bold text-rose-500 mt-0.5" x-text="row.errors.nip"></p>
                                                </template>
                                            </div>
                                        </template>
                                        <template x-if="row.editing">
                                            <input type="text" x-model="row.nip" class="block w-full text-xs font-medium border-zinc-300 rounded-lg focus:ring-[rgb(var(--color-primary))] focus:border-[rgb(var(--color-primary))] py-1.5 px-2">
                                        </template>
                                    </td>
                                </template>

                                {{-- NIM (Mahasiswa) --}}
                                <template x-if="importType === 'mahasiswa'">
                                    <td class="px-4 py-3">
                                        <template x-if="!row.editing">
                                            <div>
                                                <span class="text-sm font-medium text-zinc-600" x-text="row.nim"></span>
                                                <template x-if="row.errors.nim">
                                                    <p class="text-xs font-bold text-rose-500 mt-0.5" x-text="row.errors.nim"></p>
                                                </template>
                                            </div>
                                        </template>
                                        <template x-if="row.editing">
                                            <input type="text" x-model="row.nim" class="block w-full text-xs font-medium border-zinc-300 rounded-lg focus:ring-[rgb(var(--color-primary))] focus:border-[rgb(var(--color-primary))] py-1.5 px-2">
                                        </template>
                                    </td>
                                </template>

                                {{-- Kode Jurusan --}}
                                <td class="px-4 py-3">
                                    <template x-if="!row.editing">
                                        <div>
                                            <span class="text-xs font-bold px-2 py-1 rounded bg-zinc-100 text-zinc-700" x-text="row.department_code"></span>
                                            <template x-if="row.errors.department_code">
                                                <p class="text-xs font-bold text-rose-500 mt-1" x-text="row.errors.department_code"></p>
                                            </template>
                                        </div>
                                    </template>
                                    <template x-if="row.editing">
                                        <input type="text" x-model="row.department_code" class="block w-full text-xs font-medium border-zinc-300 rounded-lg focus:ring-[rgb(var(--color-primary))] focus:border-[rgb(var(--color-primary))] py-1.5 px-2">
                                    </template>
                                </td>

                                {{-- Peran --}}
                                <td class="px-4 py-3">
                                    <template x-if="!row.editing">
                                        <div>
                                            <span class="text-xs font-medium text-zinc-600" x-text="row.role"></span>
                                            <template x-if="row.errors.role">
                                                <p class="text-xs font-bold text-rose-500 mt-0.5" x-text="row.errors.role"></p>
                                            </template>
                                        </div>
                                    </template>
                                    <template x-if="row.editing">
                                        <input type="text" x-model="row.role" class="block w-full text-xs font-medium border-zinc-300 rounded-lg focus:ring-[rgb(var(--color-primary))] focus:border-[rgb(var(--color-primary))] py-1.5 px-2">
                                    </template>
                                </td>

                                {{-- Status --}}
                                <td class="px-4 py-3 text-center">
                                    <template x-if="row.isValidating">
                                        <span class="material-symbols-rounded animate-spin text-[18px] text-[rgb(var(--color-primary))]">progress_activity</span>
                                    </template>
                                    <template x-if="!row.isValidating && row.isValid">
                                        <span class="inline-flex items-center px-2 py-1 rounded-md ui-primary-soft text-[rgb(var(--color-primary))] border border-[rgb(var(--color-primary)_/_0.2)] text-xs font-bold">
                                            <x-atoms.icon name="check-circle" class="w-3.5 h-3.5 mr-1" /> Valid
                                        </span>
                                    </template>
                                    <template x-if="!row.isValidating && !row.isValid">
                                        <span class="inline-flex items-center px-2 py-1 rounded-md bg-rose-100 text-rose-700 text-xs font-bold border border-rose-200">
                                            <x-atoms.icon name="x-circle" class="w-3.5 h-3.5 mr-1" /> Error
                                        </span>
                                    </template>
                                </td>

                                {{-- Aksi --}}
                                <td class="px-4 py-3 text-center">
                                    <div class="flex items-center justify-center gap-1">
                                        <template x-if="!row.editing">
                                            <button @click="row.editing = true" class="p-1.5 rounded-lg text-zinc-400 hover:text-[rgb(var(--color-primary))] hover:ui-primary-soft transition-colors" title="Edit">
                                                <span class="material-symbols-rounded text-[18px]">edit</span>
                                            </button>
                                        </template>
                                        <template x-if="row.editing">
                                            <button @click="validateRow(index)" class="p-1.5 rounded-lg text-[rgb(var(--color-primary))] hover:text-[rgb(var(--color-primary))] hover:ui-primary-soft transition-colors" title="Simpan">
                                                <span class="material-symbols-rounded text-[18px]">check</span>
                                            </button>
                                        </template>
                                        <button @click="deleteRow(index)" class="p-1.5 rounded-lg text-zinc-400 hover:text-rose-600 hover:bg-rose-50 transition-colors" title="Hapus">
                                            <span class="material-symbols-rounded text-[18px]">delete</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
            
        </div>
    </div>
@endsection
