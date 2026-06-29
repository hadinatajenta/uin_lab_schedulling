@php
    $roles = old('roles', []);
@endphp
<form method="POST" action="{{ route('add.users') }}" id="add-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full" x-data="{ roles: @js($roles), hasStudent() { return this.roles.includes('student') || this.roles.includes('assistant'); }, hasLecturer() { return this.roles.includes('lecturer') || this.roles.includes('admin_lab'); } }">
    @csrf
    <div class="relative p-4 w-full max-w-2xl max-h-full">
        <div class="relative ui-surface rounded-3xl shadow-xl border border-default">
            <div class="flex items-center justify-between px-5 py-4 border-b border-default/50">
                <h3 class="text-sm font-bold text-foreground">Tambah Pengguna Baru</h3>
                <button type="button" class="text-foreground-muted/60 hover:bg-surface-muted hover:text-foreground rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center transition-colors focus:outline-none" data-modal-hide="add-modal">
                    <x-atoms.icon name="x-mark" class="w-4 h-4" />
                </button>
            </div>
            <div class="p-5 space-y-5 max-h-[70vh] overflow-y-auto hide-scrollbar">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <label class="block mb-1.5 text-xs font-bold text-foreground-muted uppercase">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" required class="bg-surface-muted border border-default text-foreground text-xs font-medium rounded-xl focus:outline-none focus:ring-2 focus:ring-[rgb(var(--color-primary)_/_0.2)] focus:border-[rgb(var(--color-primary))] block w-full p-2.5 transition-colors" placeholder="Masukkan nama..." />
                        @error('name')<p class="text-danger text-[10px] mt-1 font-semibold">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block mb-1.5 text-xs font-bold text-foreground-muted uppercase">Alamat Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" value="{{ old('email') }}" required class="bg-surface-muted border border-default text-foreground text-xs font-medium rounded-xl focus:outline-none focus:ring-2 focus:ring-[rgb(var(--color-primary)_/_0.2)] focus:border-[rgb(var(--color-primary))] block w-full p-2.5 transition-colors" placeholder="email@contoh.com" />
                        @error('email')<p class="text-danger text-[10px] mt-1 font-semibold">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block mb-1.5 text-xs font-bold text-foreground-muted uppercase">Nomor HP</label>
                        <input type="text" name="phone_number" value="{{ old('phone_number') }}" class="bg-surface-muted border border-default text-foreground text-xs font-medium rounded-xl focus:outline-none focus:ring-2 focus:ring-[rgb(var(--color-primary)_/_0.2)] focus:border-[rgb(var(--color-primary))] block w-full p-2.5 transition-colors" placeholder="08..." />
                        @error('phone_number')<p class="text-danger text-[10px] mt-1 font-semibold">{{ $message }}</p>@enderror
                    </div>
                </div>
                <div class="h-px bg-surface-muted"></div>
                <div>
                    <label class="block mb-2.5 text-xs font-bold text-foreground-muted uppercase">Peran / Role <span class="text-danger">*</span></label>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        @foreach(\App\Domains\Role\Models\Role::all() ?? [] as $role)
                            <label class="relative flex items-center justify-center p-3 border border-default rounded-xl cursor-pointer hover:bg-surface-muted transition-colors" :class="{ 'ui-primary-soft border-[rgb(var(--color-primary)_/_0.2)] ring-1 ring-[rgb(var(--color-primary)_/_0.2)]': roles.includes('{{ $role->slug }}'), 'opacity-60 cursor-not-allowed bg-surface-muted': ('{{ $role->slug }}' === 'lecturer' && roles.includes('admin_lab')) || ('{{ $role->slug }}' === 'student' && roles.includes('assistant')) || (['student', 'assistant'].includes('{{ $role->slug }}') && hasLecturer()) || (['lecturer', 'admin_lab'].includes('{{ $role->slug }}') && hasStudent()) }">
                                <input type="checkbox" name="roles[]" value="{{ $role->slug }}" x-model="roles" class="sr-only" :disabled="('{{ $role->slug }}' === 'lecturer' && roles.includes('admin_lab')) || ('{{ $role->slug }}' === 'student' && roles.includes('assistant')) || (['student', 'assistant'].includes('{{ $role->slug }}') && hasLecturer()) || (['lecturer', 'admin_lab'].includes('{{ $role->slug }}') && hasStudent())" @change="if('{{ $role->slug }}' === 'admin_lab' && roles.includes('admin_lab') && !roles.includes('lecturer')) roles.push('lecturer'); if('{{ $role->slug }}' === 'assistant' && roles.includes('assistant') && !roles.includes('student')) roles.push('student');">
                                <span class="text-xs font-bold text-foreground-muted select-none" :class="{ 'text-[rgb(var(--color-primary))]': roles.includes('{{ $role->slug }}') }">{{ $role->name }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('roles')<p class="text-danger text-[10px] mt-1 font-semibold">{{ $message }}</p>@enderror
                    <p class="mt-2 text-[11px] font-medium text-foreground-muted">Pilih satu atau lebih peran. Admin Lab otomatis mendapatkan akses Dosen. Asisten otomatis Mahasiswa.</p>
                </div>
                <div x-show="hasStudent()" x-cloak class="space-y-4 p-4 rounded-xl bg-surface-muted/50 border border-default/50">
                    <h4 class="text-xs font-bold text-foreground uppercase tracking-wide">Data Mahasiswa</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block mb-1.5 text-xs font-bold text-foreground-muted uppercase">NIM <span class="text-danger">*</span></label>
                            <input type="text" name="nim" value="{{ old('nim') }}" :required="hasStudent()" class="bg-surface border border-default text-foreground text-xs font-medium rounded-xl focus:outline-none focus:ring-2 focus:ring-[rgb(var(--color-primary)_/_0.2)] focus:border-[rgb(var(--color-primary))] block w-full p-2.5 transition-colors" placeholder="Masukkan NIM..." />
                            @error('nim')<p class="text-danger text-[10px] mt-1 font-semibold">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block mb-1.5 text-xs font-bold text-foreground-muted uppercase">Tahun Angkatan <span class="text-danger">*</span></label>
                            <input type="number" name="entry_year" value="{{ old('entry_year') }}" :required="hasStudent()" class="bg-surface border border-default text-foreground text-xs font-medium rounded-xl focus:outline-none focus:ring-2 focus:ring-[rgb(var(--color-primary)_/_0.2)] focus:border-[rgb(var(--color-primary))] block w-full p-2.5 transition-colors" placeholder="2024" />
                            @error('entry_year')<p class="text-danger text-[10px] mt-1 font-semibold">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>
                <div x-show="hasLecturer()" x-cloak class="space-y-4 p-4 rounded-xl bg-surface-muted/50 border border-default/50">
                    <h4 class="text-xs font-bold text-foreground uppercase tracking-wide">Data Dosen</h4>
                    <div>
                        <label class="block mb-1.5 text-xs font-bold text-foreground-muted uppercase">NIP <span class="text-danger">*</span></label>
                        <input type="text" name="nip" value="{{ old('nip') }}" :required="hasLecturer()" class="bg-surface border border-default text-foreground text-xs font-medium rounded-xl focus:outline-none focus:ring-2 focus:ring-[rgb(var(--color-primary)_/_0.2)] focus:border-[rgb(var(--color-primary))] block w-full p-2.5 transition-colors" placeholder="Masukkan NIP..." />
                        @error('nip')<p class="text-danger text-[10px] mt-1 font-semibold">{{ $message }}</p>@enderror
                    </div>
                </div>
                <div x-show="roles.length > 0" x-cloak>
                    <label class="block mb-1.5 text-xs font-bold text-foreground-muted uppercase">Jurusan</label>
                    <select name="department_id" class="bg-surface-muted border border-default text-foreground text-xs font-semibold rounded-xl focus:outline-none focus:ring-2 focus:ring-[rgb(var(--color-primary)_/_0.2)] focus:border-[rgb(var(--color-primary))] block w-full p-2.5 transition-colors">
                        <option value="">Pilih Jurusan...</option>
                        @foreach(\App\Domains\Department\Models\Department::all()->groupBy('faculty') as $faculty => $depts)
                            <optgroup label="{{ $faculty ?: 'Lainnya' }}">
                                @foreach($depts as $dept)
                                    <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                    @error('department_id')<p class="text-danger text-[10px] mt-1 font-semibold">{{ $message }}</p>@enderror
                </div>
                <div class="p-3 ui-primary-soft border border-[rgb(var(--color-primary)_/_0.2)] rounded-xl flex items-start gap-3">
                    <x-atoms.icon name="information-circle" class="w-5 h-5 text-[rgb(var(--color-primary))] shrink-0 mt-0.5" />
                    <div>
                        <p class="text-xs font-bold text-[rgb(var(--color-primary))]">Password Default</p>
                        <p class="text-[11px] font-medium text-[rgb(var(--color-primary)_/_0.8)] mt-0.5">Password pengguna otomatis diatur ke <b>LabUIN@2026</b> dan pengguna wajib mengganti password saat login pertama.</p>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-end px-5 py-4 border-t border-default/50 bg-surface-muted/50 rounded-b-3xl gap-3">
                <button data-modal-hide="add-modal" type="button" class="py-2.5 px-4 text-xs font-semibold text-foreground-muted bg-surface border border-default hover:bg-surface-muted rounded-xl transition-colors">Batal</button>
                <button type="submit" class="ui-primary hover:opacity-90 font-semibold rounded-xl text-xs px-4 py-2.5 transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-[rgb(var(--color-primary))]">Tambah Pengguna</button>
            </div>
        </div>
    </div>
</form>
