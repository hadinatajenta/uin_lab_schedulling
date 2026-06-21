@extends('layouts.app')

@section('title', 'Import Pengguna')

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/xlsx/dist/xlsx.full.min.js"></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('importUsers', () => ({
                step: 1, // 1: Upload, 2: Preview & Validating, 3: Ready to Submit
                importType: 'staf', // 'staf' or 'mahasiswa'
                file: null,
                fileName: '',
                isDragging: false,
                rows: [],
                isProcessing: false,
                isSubmitting: false,
                errorMessage: '',
                successMessage: '',

                handleDrop(e) {
                    this.isDragging = false;
                    const files = e.dataTransfer.files;
                    if (files.length) {
                        this.processFile(files[0]);
                    }
                },
                
                handleFileSelect(e) {
                    const files = e.target.files;
                    if (files.length) {
                        this.processFile(files[0]);
                    }
                },

                processFile(file) {
                    if (!file.name.match(/\.(xlsx|csv)$/i)) {
                        this.errorMessage = 'Format file tidak didukung. Harap gunakan .xlsx atau .csv';
                        return;
                    }
                    this.errorMessage = '';
                    this.file = file;
                    this.fileName = file.name;
                    this.step = 2;
                    this.extractData();
                },

                extractData() {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        try {
                            const data = e.target.result;
                            const workbook = XLSX.read(data, { type: 'binary' });
                            const firstSheetName = workbook.SheetNames[0];
                            const worksheet = workbook.Sheets[firstSheetName];
                            
                            // Ekstrak ke JSON, abaikan baris pertama (header default yang ada di file)
                            // Jika ada header khusus, kita bisa atur header option.
                            const jsonData = XLSX.utils.sheet_to_json(worksheet, { header: 1 });
                            
                            // Asumsikan baris pertama adalah nama kolom.
                            // Kita mapping sesuai importType.
                            let parsedRows = [];
                            // Mulai dari baris ke-1 (skip header index 0)
                            for (let i = 1; i < jsonData.length; i++) {
                                const row = jsonData[i];
                                if (!row || row.length === 0 || (!row[0] && !row[1])) continue; // Skip baris kosong

                                let newRow = {
                                    id: 'row_' + i,
                                    editing: false,
                                    isValidating: false,
                                    errors: {}, // { email: 'Email sudah ada' }
                                    isValid: false,
                                };

                                if (this.importType === 'staf') {
                                    // Template Staf: Nama | Email | No HP | NIP | Kode Jurusan | Peran (admin_lab / lecturer)
                                    newRow.name = row[0] || '';
                                    newRow.email = row[1] || '';
                                    newRow.phone_number = row[2] || '';
                                    newRow.nip = row[3] || '';
                                    newRow.department_code = row[4] || '';
                                    newRow.role = row[5] || '';
                                } else {
                                    // Template Mhs: Nama | Email | No HP | NIM | Kode Jurusan | Tahun Masuk | Peran (student / assistant) | NIP Dosen
                                    newRow.name = row[0] || '';
                                    newRow.email = row[1] || '';
                                    newRow.phone_number = row[2] || '';
                                    newRow.nim = row[3] || '';
                                    newRow.department_code = row[4] || '';
                                    newRow.entry_year = row[5] || '';
                                    newRow.role = row[6] || '';
                                    newRow.supervisor_nip = row[7] || '';
                                }
                                parsedRows.push(newRow);
                            }
                            
                            this.rows = parsedRows;
                            this.validateBulk();
                        } catch (err) {
                            console.error(err);
                            this.errorMessage = 'Terjadi kesalahan saat membaca file Excel. Pastikan format sesuai template.';
                            this.step = 1;
                        }
                    };
                    reader.readAsBinaryString(this.file);
                },

                async validateBulk() {
                    this.isProcessing = true;
                    try {
                        const response = await fetch('/admin/users/validate-bulk', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                type: this.importType,
                                rows: this.rows
                            })
                        });
                        
                        const result = await response.json();
                        if (result.success && result.data) {
                            this.rows = result.data; // data baru sudah tertanam .errors dan .isValid
                        }
                    } catch (error) {
                        console.error('Validation error:', error);
                        this.errorMessage = 'Gagal melakukan validasi ke server.';
                    } finally {
                        this.isProcessing = false;
                        this.step = 3;
                    }
                },

                async validateRow(index) {
                    const row = this.rows[index];
                    row.isValidating = true;
                    row.editing = false;
                    
                    try {
                        const response = await fetch('/admin/users/validate-row', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                type: this.importType,
                                row: row
                            })
                        });
                        
                        const result = await response.json();
                        if (result.success) {
                            this.rows[index] = result.data;
                        }
                    } catch (error) {
                        console.error('Row validation error:', error);
                    } finally {
                        this.rows[index].isValidating = false;
                    }
                },

                deleteRow(index) {
                    this.rows.splice(index, 1);
                },

                get allValid() {
                    return this.rows.length > 0 && this.rows.every(r => r.isValid);
                },
                
                get invalidCount() {
                    return this.rows.filter(r => !r.isValid).length;
                },

                async submitBulk() {
                    if (!this.allValid) return;
                    this.isSubmitting = true;
                    
                    try {
                        const response = await fetch('/admin/users/process-bulk', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                type: this.importType,
                                rows: this.rows
                            })
                        });
                        
                        const result = await response.json();
                        if (result.success) {
                            // Redirect with session flash via window location
                            window.location.href = '/admin/users?bulk_import_success=true';
                        } else {
                            this.errorMessage = result.message || 'Gagal memproses import.';
                            this.isSubmitting = false;
                        }
                    } catch (error) {
                        console.error('Submit error:', error);
                        this.errorMessage = 'Terjadi kesalahan jaringan saat mengirim data.';
                        this.isSubmitting = false;
                    }
                }
            }));
        });
    </script>
@endsection

@section('content')
    <div class="px-4 pb-8 min-h-[calc(100vh-12rem)] flex flex-col space-y-6" x-data="importUsers()">
        <x-ui.page-header title="Import Pengguna (Bulk)" description="Unggah file Excel untuk menambahkan banyak pengguna sekaligus.">
            <a href="{{ route('users.index') }}" class="inline-flex items-center text-sm font-semibold text-zinc-600 hover:text-emerald-600 transition-colors">
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
        <div x-show="step === 1" class="bg-white border border-zinc-200/80 rounded-3xl p-6 shadow-sm">
            <div class="max-w-xl mx-auto space-y-8 py-4">
                
                <div class="space-y-4">
                    <h3 class="text-base font-bold text-zinc-800">1. Pilih Tipe Data</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <label class="relative flex cursor-pointer rounded-2xl border bg-white p-4 shadow-sm focus:outline-none"
                            :class="importType === 'staf' ? 'border-emerald-600 ring-1 ring-emerald-600' : 'border-zinc-200 hover:bg-zinc-50'">
                            <input type="radio" x-model="importType" value="staf" class="sr-only">
                            <span class="flex flex-1">
                                <span class="flex flex-col">
                                    <span class="block text-sm font-bold text-zinc-900">Staf Akademik</span>
                                    <span class="mt-1 flex items-center text-xs text-zinc-500 font-medium">Admin Lab & Dosen</span>
                                </span>
                            </span>
                            <x-atoms.icon name="check-circle" class="h-5 w-5 text-emerald-600" x-show="importType === 'staf'" />
                        </label>

                        <label class="relative flex cursor-pointer rounded-2xl border bg-white p-4 shadow-sm focus:outline-none"
                            :class="importType === 'mahasiswa' ? 'border-emerald-600 ring-1 ring-emerald-600' : 'border-zinc-200 hover:bg-zinc-50'">
                            <input type="radio" x-model="importType" value="mahasiswa" class="sr-only">
                            <span class="flex flex-1">
                                <span class="flex flex-col">
                                    <span class="block text-sm font-bold text-zinc-900">Mahasiswa</span>
                                    <span class="mt-1 flex items-center text-xs text-zinc-500 font-medium">Asisten & Mahasiswa</span>
                                </span>
                            </span>
                            <x-atoms.icon name="check-circle" class="h-5 w-5 text-emerald-600" x-show="importType === 'mahasiswa'" />
                        </label>
                    </div>
                    <div class="flex items-center justify-between mt-2">
                        <p class="text-xs text-zinc-500 font-medium">Download template Excel/CSV untuk menyesuaikan data.</p>
                        <a :href="importType === 'staf' ? '/templates/template-staf.csv' : '/templates/template-mahasiswa.csv'"
                           class="inline-flex items-center text-xs font-bold text-emerald-600 hover:text-emerald-700 bg-emerald-50 px-3 py-1.5 rounded-lg transition-colors">
                            <x-atoms.icon name="arrow-down-tray" class="w-3.5 h-3.5 mr-1" />
                            Download Template
                        </a>
                    </div>
                </div>

                <div class="space-y-4">
                    <h3 class="text-base font-bold text-zinc-800">2. Unggah File</h3>
                    <div class="mt-2 flex justify-center rounded-2xl border border-dashed border-zinc-300 px-6 py-12 transition-colors"
                         :class="isDragging ? 'border-emerald-500 bg-emerald-50/50' : 'hover:border-zinc-400 bg-zinc-50/50'"
                         @dragover.prevent="isDragging = true"
                         @dragleave.prevent="isDragging = false"
                         @drop.prevent="handleDrop">
                        <div class="text-center">
                            <x-atoms.icon name="document-plus" class="mx-auto h-10 w-10 text-zinc-400" />
                            <div class="mt-4 flex text-sm leading-6 text-zinc-600 justify-center">
                                <label for="file-upload" class="relative cursor-pointer rounded-md bg-transparent font-semibold text-emerald-600 hover:text-emerald-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-emerald-600 focus-within:ring-offset-2">
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
        <div x-show="step > 1" class="bg-white border border-zinc-200/80 rounded-3xl p-6 shadow-sm" x-cloak>
            
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
                        class="px-5 py-2 inline-flex items-center text-sm font-semibold text-white rounded-xl transition-all shadow-sm shadow-emerald-600/10 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2"
                        :class="(!allValid || isSubmitting) ? 'bg-emerald-300 cursor-not-allowed' : 'bg-emerald-600 hover:bg-emerald-700'">
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
                    <thead class="bg-emerald-50/50 text-xs text-emerald-900 font-bold uppercase border-b border-zinc-200">
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
                                    <span class="material-symbols-rounded animate-spin text-[32px] text-emerald-600 mb-2">progress_activity</span>
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
                            <tr class="bg-white border-b border-zinc-100 transition-colors" :class="{'bg-rose-50/30': !row.isValid, 'bg-emerald-50/30': row.isValid}">
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
                                        <input type="text" x-model="row.name" class="block w-full text-xs font-medium border-zinc-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500 py-1.5 px-2">
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
                                        <input type="email" x-model="row.email" class="block w-full text-xs font-medium border-zinc-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500 py-1.5 px-2">
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
                                            <input type="text" x-model="row.nip" class="block w-full text-xs font-medium border-zinc-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500 py-1.5 px-2">
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
                                            <input type="text" x-model="row.nim" class="block w-full text-xs font-medium border-zinc-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500 py-1.5 px-2">
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
                                        <input type="text" x-model="row.department_code" class="block w-full text-xs font-medium border-zinc-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500 py-1.5 px-2">
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
                                        <input type="text" x-model="row.role" class="block w-full text-xs font-medium border-zinc-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500 py-1.5 px-2">
                                    </template>
                                </td>

                                {{-- Status --}}
                                <td class="px-4 py-3 text-center">
                                    <template x-if="row.isValidating">
                                        <span class="material-symbols-rounded animate-spin text-[18px] text-emerald-500">progress_activity</span>
                                    </template>
                                    <template x-if="!row.isValidating && row.isValid">
                                        <span class="inline-flex items-center px-2 py-1 rounded-md bg-emerald-100 text-emerald-700 text-xs font-bold border border-emerald-200">
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
                                            <button @click="row.editing = true" class="p-1.5 rounded-lg text-zinc-400 hover:text-emerald-600 hover:bg-emerald-50 transition-colors" title="Edit">
                                                <span class="material-symbols-rounded text-[18px]">edit</span>
                                            </button>
                                        </template>
                                        <template x-if="row.editing">
                                            <button @click="validateRow(index)" class="p-1.5 rounded-lg text-emerald-500 hover:text-emerald-700 hover:bg-emerald-50 transition-colors" title="Simpan">
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
