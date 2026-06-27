@extends('layouts.app')

@section('title', 'Profil Pengguna: ' . $user->name)

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    
    {{-- Top Navigation & Header --}}
    <div class="flex items-center justify-between mb-8">
        <div class="flex items-center gap-4">
            <a href="{{ route('users.index') }}" class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-white border border-zinc-200 text-zinc-500 hover:text-zinc-800 hover:bg-zinc-50 transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-[rgb(var(--color-primary)_/_0.2)]">
                <span class="material-symbols-rounded text-[20px]">arrow_back</span>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-zinc-900 tracking-tight">Profil Pengguna</h1>
                <p class="text-sm font-medium text-zinc-500 mt-0.5">Kelola detail informasi dan aktivitas pengguna.</p>
            </div>
        </div>
    </div>

    {{-- User Profile Banner --}}
    <div class="ui-surface rounded-3xl p-6 md:p-8 border border-zinc-200/80 shadow-sm flex flex-col md:flex-row items-center md:items-start gap-6 relative overflow-hidden">
        <!-- Decorative Background -->
        <div class="absolute top-0 right-0 w-64 h-64 bg-[rgb(var(--color-primary))] opacity-[0.03] rounded-full blur-3xl -translate-y-1/2 translate-x-1/3 pointer-events-none"></div>
        
        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=EEF2FF&color=4F46E5&bold=true&size=128"
             alt="{{ $user->name }}"
             class="w-24 h-24 md:w-28 md:h-28 rounded-full object-cover ring-4 ring-white shadow-md z-10 shrink-0">
             
        <div class="flex-1 text-center md:text-left z-10">
            <h2 class="text-2xl font-bold text-zinc-900 tracking-tight">{{ $user->name }}</h2>
            <p class="text-[15px] font-medium text-zinc-500 mt-1">{{ $user->email }}</p>
            
            <div class="flex flex-wrap items-center justify-center md:justify-start gap-2 mt-4">
                @foreach($user->roles as $role)
                    @php
                        $badgeType = match (strtolower($role->name)) {
                            'admin lab', 'dosen', 'asisten dosen' => 'primary',
                            default => 'neutral',
                        };
                    @endphp
                    <x-ui.badge :type="$badgeType">
                        {{ Str::ucfirst($role->name) }}
                    </x-ui.badge>
                @endforeach
                
                @if($user->department)
                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[11px] font-bold bg-zinc-100 text-zinc-600 border border-zinc-200 uppercase tracking-wider">
                        {{ $user->department->name }}
                    </span>
                @endif
            </div>
        </div>
        
        <div class="z-10 text-center md:text-right self-center md:self-start bg-zinc-50 px-4 py-3 rounded-2xl border border-zinc-100 mt-4 md:mt-0">
            <p class="text-[11px] font-bold text-zinc-400 uppercase tracking-wider mb-1">Bergabung Sejak</p>
            <p class="text-sm font-semibold text-zinc-800">{{ $user->created_at ? $user->created_at->format('d F Y') : '-' }}</p>
            <p class="text-xs font-medium text-zinc-500 mt-0.5">{{ $user->created_at ? $user->created_at->diffForHumans() : '' }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
        
        {{-- Left Column: Edit Form --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="ui-surface rounded-3xl p-6 border border-zinc-200/80 shadow-sm">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-zinc-100">
                    <div class="w-8 h-8 rounded-xl bg-[rgb(var(--color-primary)_/_0.1)] text-[rgb(var(--color-primary))] flex items-center justify-center">
                        <span class="material-symbols-rounded text-[18px]">manage_accounts</span>
                    </div>
                    <h3 class="text-lg font-bold text-zinc-900 tracking-tight">Informasi Dasar</h3>
                </div>
                
                @php
                    $roles = old('roles', $user->roles->pluck('slug')->toArray());
                @endphp
                <form method="POST" action="{{ route('update.users', $user->id) }}" x-data="{ 
                        roles: @js($roles), 
                        hasStudent() { return this.roles.includes('student') || this.roles.includes('assistant'); }, 
                        hasLecturer() { return this.roles.includes('lecturer') || this.roles.includes('admin_lab'); } 
                    }">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-5">
                        
                        {{-- Name & Phone --}}
                        <div class="sm:col-span-2">
                            <label class="block mb-1.5 text-[11px] font-bold text-zinc-500 uppercase tracking-wider">Nama Lengkap <span class="text-rose-500">*</span></label>
                            <x-ui.input type="text" name="name" value="{{ old('name', $user->name) }}" required placeholder="Masukkan nama lengkap..." />
                            @error('name')<p class="text-rose-500 text-[10px] mt-1.5 font-semibold">{{ $message }}</p>@enderror
                        </div>
                        
                        <div class="sm:col-span-2">
                            <label class="block mb-1.5 text-[11px] font-bold text-zinc-500 uppercase tracking-wider">Nomor Handphone</label>
                            <x-ui.input type="text" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}" placeholder="08..." />
                            @error('phone_number')<p class="text-rose-500 text-[10px] mt-1.5 font-semibold">{{ $message }}</p>@enderror
                        </div>
                        
                        {{-- Roles --}}
                        <div class="sm:col-span-2 pt-4 mt-2 border-t border-zinc-100">
                            <label class="block mb-3 text-[11px] font-bold text-zinc-500 uppercase tracking-wider">Peran & Akses Sistem <span class="text-rose-500">*</span></label>
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                                @foreach(\App\Domains\Role\Models\Role::all() ?? [] as $role)
                                    <label class="relative flex items-center justify-center p-3.5 border border-zinc-200 rounded-2xl cursor-pointer hover:bg-zinc-50 transition-colors" 
                                           :class="{ 
                                               'ui-primary-soft border-[rgb(var(--color-primary)_/_0.2)] ring-1 ring-[rgb(var(--color-primary)_/_0.2)] shadow-sm': roles.includes('{{ $role->slug }}'), 
                                               'opacity-60 cursor-not-allowed bg-zinc-50': ('{{ $role->slug }}' === 'lecturer' && roles.includes('admin_lab')) || ('{{ $role->slug }}' === 'student' && roles.includes('assistant')) || (['student', 'assistant'].includes('{{ $role->slug }}') && hasLecturer()) || (['lecturer', 'admin_lab'].includes('{{ $role->slug }}') && hasStudent()) 
                                           }">
                                        <input type="checkbox" name="roles[]" value="{{ $role->slug }}" x-model="roles" class="sr-only" 
                                               :disabled="('{{ $role->slug }}' === 'lecturer' && roles.includes('admin_lab')) || ('{{ $role->slug }}' === 'student' && roles.includes('assistant')) || (['student', 'assistant'].includes('{{ $role->slug }}') && hasLecturer()) || (['lecturer', 'admin_lab'].includes('{{ $role->slug }}') && hasStudent())" 
                                               @change="if('{{ $role->slug }}' === 'admin_lab' && roles.includes('admin_lab') && !roles.includes('lecturer')) roles.push('lecturer'); if('{{ $role->slug }}' === 'assistant' && roles.includes('assistant') && !roles.includes('student')) roles.push('student');">
                                        <span class="text-[13px] font-bold text-zinc-700 select-none" 
                                              :class="{ 'text-[rgb(var(--color-primary))]': roles.includes('{{ $role->slug }}') }">
                                            {{ $role->name }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                            @error('roles')<p class="text-rose-500 text-[10px] mt-1.5 font-semibold">{{ $message }}</p>@enderror
                            <p class="mt-3 text-[11px] font-medium text-zinc-500">Pilih satu atau lebih peran. Admin Lab otomatis mendapatkan akses Dosen. Asisten otomatis Mahasiswa.</p>
                        </div>
                        
                        {{-- Student specific fields --}}
                        <div x-show="hasStudent()" x-cloak class="sm:col-span-2 space-y-4 p-5 rounded-2xl bg-zinc-50/80 border border-zinc-100 mt-2">
                            <h4 class="text-[11px] font-bold text-zinc-800 uppercase tracking-wider mb-2">Atribut Mahasiswa</h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block mb-1.5 text-[11px] font-bold text-zinc-500 uppercase tracking-wider">NIM <span class="text-rose-500">*</span></label>
                                    <x-ui.input type="text" name="nim" value="{{ old('nim', $user->nim) }}" x-bind:required="hasStudent()" placeholder="Masukkan NIM..." />
                                    @error('nim')<p class="text-rose-500 text-[10px] mt-1.5 font-semibold">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label class="block mb-1.5 text-[11px] font-bold text-zinc-500 uppercase tracking-wider">Tahun Angkatan <span class="text-rose-500">*</span></label>
                                    <x-ui.input type="number" name="entry_year" value="{{ old('entry_year', $user->entry_year) }}" x-bind:required="hasStudent()" placeholder="Misal: 2024" />
                                    @error('entry_year')<p class="text-rose-500 text-[10px] mt-1.5 font-semibold">{{ $message }}</p>@enderror
                                </div>
                            </div>
                        </div>
                        
                        {{-- Lecturer specific fields --}}
                        <div x-show="hasLecturer()" x-cloak class="sm:col-span-2 space-y-4 p-5 rounded-2xl bg-zinc-50/80 border border-zinc-100 mt-2">
                            <h4 class="text-[11px] font-bold text-zinc-800 uppercase tracking-wider mb-2">Atribut Dosen / Staf</h4>
                            <div>
                                <label class="block mb-1.5 text-[11px] font-bold text-zinc-500 uppercase tracking-wider">NIP <span class="text-rose-500">*</span></label>
                                <x-ui.input type="text" name="nip" value="{{ old('nip', $user->nip) }}" x-bind:required="hasLecturer()" placeholder="Masukkan NIP..." />
                                @error('nip')<p class="text-rose-500 text-[10px] mt-1.5 font-semibold">{{ $message }}</p>@enderror
                            </div>
                        </div>
                        
                        {{-- Department --}}
                        <div x-show="roles.length > 0" x-cloak class="sm:col-span-2 pt-4 mt-2 border-t border-zinc-100">
                            <label class="block mb-1.5 text-[11px] font-bold text-zinc-500 uppercase tracking-wider">Jurusan / Program Studi</label>
                            <div class="relative w-full">
                                <select name="department_id" class="w-full h-11 text-sm font-medium border border-zinc-200 rounded-xl px-4 pr-10 bg-white focus:ring-2 focus:ring-[rgb(var(--color-primary)_/_0.2)] focus:border-[rgb(var(--color-primary))] transition-colors text-zinc-800 appearance-none shadow-sm">
                                    <option value="">Pilih Jurusan...</option>
                                    @foreach(\App\Domains\Department\Models\Department::all()->groupBy('faculty') as $faculty => $depts)
                                        <optgroup label="{{ $faculty ?: 'Lainnya' }}">
                                            @foreach($depts as $dept)
                                                <option value="{{ $dept->id }}" {{ old('department_id', $user->department_id) == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <span class="material-symbols-rounded text-[20px] text-zinc-400">expand_more</span>
                                </div>
                            </div>
                            @error('department_id')<p class="text-rose-500 text-[10px] mt-1.5 font-semibold">{{ $message }}</p>@enderror
                        </div>
                        
                    </div>
                    
                    <div class="mt-8 pt-6 border-t border-zinc-100 flex items-center justify-end">
                        <button type="submit" class="inline-flex items-center justify-center ui-primary hover:opacity-90 font-semibold rounded-xl text-sm px-6 py-2.5 transition-opacity shadow-sm focus:outline-none focus:ring-2 focus:ring-[rgb(var(--color-primary))] focus:ring-offset-2">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        {{-- Right Column: Activity Logs --}}
        <div class="lg:col-span-1 space-y-6">
            <div class="ui-surface rounded-3xl p-6 border border-zinc-200/80 shadow-sm flex flex-col h-full max-h-[800px]">
                
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-zinc-100 shrink-0">
                    <div class="w-8 h-8 rounded-xl bg-zinc-100 text-zinc-600 flex items-center justify-center">
                        <span class="material-symbols-rounded text-[18px]">history</span>
                    </div>
                    <h3 class="text-lg font-bold text-zinc-900 tracking-tight">Riwayat Aktivitas</h3>
                </div>
                
                <div class="flex-1 overflow-y-auto pr-2 custom-scrollbar">
                    @if($activities->isEmpty())
                        <div class="flex flex-col items-center justify-center h-48 text-center px-4">
                            <span class="material-symbols-rounded text-[40px] text-zinc-200 mb-3">monitor_heart</span>
                            <p class="text-[13px] font-bold text-zinc-600">Belum ada aktivitas</p>
                            <p class="text-[11px] font-medium text-zinc-400 mt-1">Pengguna ini belum memiliki riwayat aksi apapun di sistem.</p>
                        </div>
                    @else
                        <div class="relative pl-3 border-l-2 border-zinc-100 space-y-6">
                            @foreach($activities as $log)
                                <div class="relative">
                                    {{-- Timeline node --}}
                                    <div class="absolute -left-[17px] top-1.5 w-3 h-3 rounded-full bg-white border-2 border-[rgb(var(--color-primary))] shadow-sm"></div>
                                    
                                    <div class="pl-4">
                                        <div class="flex items-start justify-between gap-2 mb-0.5">
                                            <p class="text-[13px] font-bold text-zinc-800">{{ Str::headline($log->action) }}</p>
                                            <span class="text-[10px] font-bold text-zinc-400 shrink-0 tabular-nums">{{ $log->created_at->diffForHumans(null, true, true) }}</span>
                                        </div>
                                        <p class="text-[12px] font-medium text-zinc-500 leading-relaxed">{{ $log->description }}</p>
                                        
                                        <p class="text-[10px] font-medium text-zinc-400 mt-1.5 flex items-center gap-1">
                                            <span class="material-symbols-rounded text-[12px]">desktop_windows</span>
                                            {{ $log->ip_address }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
                
                @if($activities->hasPages())
                    <div class="mt-4 pt-4 border-t border-zinc-100 shrink-0">
                        {{ $activities->links() }}
                    </div>
                @endif
                
            </div>
        </div>
        
    </div>
</div>

<style>
    /* Custom scrollbar for activity log */
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #e4e4e7; border-radius: 4px; }
</style>
@endsection
