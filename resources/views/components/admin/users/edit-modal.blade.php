@props(['user'])
@php
    $roles = old('roles', $user->roles->pluck('slug')->toArray());
@endphp
<form method="POST" action="{{ route('update.users', $user->id) }}" id="edit-modal-{{ $user->id }}" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full text-left" x-data="{ roles: @js($roles), hasStudent() { return this.roles.includes('student') || this.roles.includes('assistant'); }, hasLecturer() { return this.roles.includes('lecturer') || this.roles.includes('admin_lab'); } }">
    @csrf
    @method('PUT')
    <div class="relative p-4 w-full max-w-2xl max-h-full">
        <div class="relative ui-surface rounded-3xl shadow-xl border border-zinc-200">
            <div class="flex items-center justify-between px-5 py-4 border-b border-zinc-100">
                <h3 class="text-sm font-bold text-zinc-900">Edit Pengguna: {{ $user->name }}</h3>
                <button type="button" class="text-zinc-400 hover:bg-zinc-100 hover:text-zinc-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center transition-colors focus:outline-none" data-modal-hide="edit-modal-{{ $user->id }}">
                    <x-atoms.icon name="x-mark" class="w-4 h-4" />
                </button>
            </div>
            <div class="p-5 space-y-5 max-h-[70vh] overflow-y-auto hide-scrollbar">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <label class="block mb-1.5 text-xs font-bold text-zinc-500 uppercase">Nama Lengkap <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="bg-zinc-50 border border-zinc-200 text-zinc-800 text-xs font-medium rounded-xl focus:outline-none focus:ring-2 focus:ring-[rgb(var(--color-primary)_/_0.2)] focus:border-[rgb(var(--color-primary))] block w-full p-2.5 transition-colors" />
                        @error('name')<p class="text-rose-500 text-[10px] mt-1 font-semibold">{{ $message }}</p>@enderror
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block mb-1.5 text-xs font-bold text-zinc-500 uppercase">Nomor HP</label>
                        <input type="text" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}" class="bg-zinc-50 border border-zinc-200 text-zinc-800 text-xs font-medium rounded-xl focus:outline-none focus:ring-2 focus:ring-[rgb(var(--color-primary)_/_0.2)] focus:border-[rgb(var(--color-primary))] block w-full p-2.5 transition-colors" />
                        @error('phone_number')<p class="text-rose-500 text-[10px] mt-1 font-semibold">{{ $message }}</p>@enderror
                    </div>
                </div>
                <div class="h-px bg-zinc-100"></div>
                <div>
                    <label class="block mb-2.5 text-xs font-bold text-zinc-500 uppercase">Peran / Role <span class="text-rose-500">*</span></label>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        @foreach(\App\Models\Role::all() ?? [] as $role)
                            <label class="relative flex items-center justify-center p-3 border border-zinc-200 rounded-xl cursor-pointer hover:bg-zinc-50 transition-colors" :class="{ 'ui-primary-soft border-[rgb(var(--color-primary)_/_0.2)] ring-1 ring-[rgb(var(--color-primary)_/_0.2)]': roles.includes('{{ $role->slug }}'), 'opacity-60 cursor-not-allowed bg-zinc-50': ('{{ $role->slug }}' === 'lecturer' && roles.includes('admin_lab')) || ('{{ $role->slug }}' === 'student' && roles.includes('assistant')) || (['student', 'assistant'].includes('{{ $role->slug }}') && hasLecturer()) || (['lecturer', 'admin_lab'].includes('{{ $role->slug }}') && hasStudent()) }">
                                <input type="checkbox" name="roles[]" value="{{ $role->slug }}" x-model="roles" class="sr-only" :disabled="('{{ $role->slug }}' === 'lecturer' && roles.includes('admin_lab')) || ('{{ $role->slug }}' === 'student' && roles.includes('assistant')) || (['student', 'assistant'].includes('{{ $role->slug }}') && hasLecturer()) || (['lecturer', 'admin_lab'].includes('{{ $role->slug }}') && hasStudent())" @change="if('{{ $role->slug }}' === 'admin_lab' && roles.includes('admin_lab') && !roles.includes('lecturer')) roles.push('lecturer'); if('{{ $role->slug }}' === 'assistant' && roles.includes('assistant') && !roles.includes('student')) roles.push('student');">
                                <span class="text-xs font-bold text-zinc-700 select-none" :class="{ 'text-[rgb(var(--color-primary))]': roles.includes('{{ $role->slug }}') }">{{ $role->name }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('roles')<p class="text-rose-500 text-[10px] mt-1 font-semibold">{{ $message }}</p>@enderror
                    <p class="mt-2 text-[11px] font-medium text-zinc-500">Pilih satu atau lebih peran. Admin Lab otomatis mendapatkan akses Dosen. Asisten otomatis Mahasiswa.</p>
                </div>
                <div x-show="hasStudent()" x-cloak class="space-y-4 p-4 rounded-xl bg-zinc-50/50 border border-zinc-100">
                    <h4 class="text-xs font-bold text-zinc-800 uppercase tracking-wide">Data Mahasiswa</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block mb-1.5 text-xs font-bold text-zinc-500 uppercase">NIM <span class="text-rose-500">*</span></label>
                            <input type="text" name="nim" value="{{ old('nim', $user->nim) }}" :required="hasStudent()" class="bg-white border border-zinc-200 text-zinc-800 text-xs font-medium rounded-xl focus:outline-none focus:ring-2 focus:ring-[rgb(var(--color-primary)_/_0.2)] focus:border-[rgb(var(--color-primary))] block w-full p-2.5 transition-colors" />
                            @error('nim')<p class="text-rose-500 text-[10px] mt-1 font-semibold">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block mb-1.5 text-xs font-bold text-zinc-500 uppercase">Tahun Angkatan <span class="text-rose-500">*</span></label>
                            <input type="number" name="entry_year" value="{{ old('entry_year', $user->entry_year) }}" :required="hasStudent()" class="bg-white border border-zinc-200 text-zinc-800 text-xs font-medium rounded-xl focus:outline-none focus:ring-2 focus:ring-[rgb(var(--color-primary)_/_0.2)] focus:border-[rgb(var(--color-primary))] block w-full p-2.5 transition-colors" />
                            @error('entry_year')<p class="text-rose-500 text-[10px] mt-1 font-semibold">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>
                <div x-show="hasLecturer()" x-cloak class="space-y-4 p-4 rounded-xl bg-zinc-50/50 border border-zinc-100">
                    <h4 class="text-xs font-bold text-zinc-800 uppercase tracking-wide">Data Dosen</h4>
                    <div>
                        <label class="block mb-1.5 text-xs font-bold text-zinc-500 uppercase">NIP <span class="text-rose-500">*</span></label>
                        <input type="text" name="nip" value="{{ old('nip', $user->nip) }}" :required="hasLecturer()" class="bg-white border border-zinc-200 text-zinc-800 text-xs font-medium rounded-xl focus:outline-none focus:ring-2 focus:ring-[rgb(var(--color-primary)_/_0.2)] focus:border-[rgb(var(--color-primary))] block w-full p-2.5 transition-colors" />
                        @error('nip')<p class="text-rose-500 text-[10px] mt-1 font-semibold">{{ $message }}</p>@enderror
                    </div>
                </div>
                <div x-show="roles.length > 0" x-cloak>
                    <label class="block mb-1.5 text-xs font-bold text-zinc-500 uppercase">Jurusan</label>
                    <select name="department_id" class="bg-zinc-50 border border-zinc-200 text-zinc-800 text-xs font-semibold rounded-xl focus:outline-none focus:ring-2 focus:ring-[rgb(var(--color-primary)_/_0.2)] focus:border-[rgb(var(--color-primary))] block w-full p-2.5 transition-colors">
                        <option value="">Pilih Jurusan...</option>
                        @foreach(\App\Models\Department::all()->groupBy('faculty') as $faculty => $depts)
                            <optgroup label="{{ $faculty ?: 'Lainnya' }}">
                                @foreach($depts as $dept)
                                    <option value="{{ $dept->id }}" {{ old('department_id', $user->department_id) == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                    @error('department_id')<p class="text-rose-500 text-[10px] mt-1 font-semibold">{{ $message }}</p>@enderror
                </div>
            </div>
            <div class="flex items-center justify-end px-5 py-4 border-t border-zinc-100 bg-zinc-50/50 rounded-b-3xl gap-3">
                <button data-modal-hide="edit-modal-{{ $user->id }}" type="button" class="py-2.5 px-4 text-xs font-semibold text-zinc-700 bg-white border border-zinc-200 hover:bg-zinc-50 rounded-xl transition-colors">Batal</button>
                <button type="submit" class="ui-primary hover:opacity-90 font-semibold rounded-xl text-xs px-4 py-2.5 transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-[rgb(var(--color-primary))]">Simpan Perubahan</button>
            </div>
        </div>
    </div>
</form>
