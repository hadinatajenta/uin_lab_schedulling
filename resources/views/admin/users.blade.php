@extends('layouts.app')

@section('title', 'Data Pengguna')

@section('content')
    <div class="px-2 pb-8 min-h-[calc(100vh-12rem)] flex flex-col">
        {{-- Header Section --}}
        <x-ui.page-header title="Data Pengguna" description="Kelola pengguna sistem laboratorium.">
            @if (Auth::user()->jabatan !== 'Mahasiswa')
                <button type="button" data-modal-target="add-modal" data-modal-toggle="add-modal"
                    class="w-full md:w-auto inline-flex items-center justify-center text-white bg-indigo-600 hover:bg-indigo-700 font-semibold rounded-xl text-sm md:text-xs px-4 h-11 md:h-10 transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <span class="material-symbols-rounded text-[20px] md:text-[18px] mr-2">add</span>
                    Tambah Pengguna
                </button>
            @endif
        </x-ui.page-header>

        {{-- Filtering & Search Bar --}}
        <div class="flex flex-col-reverse md:flex-row items-stretch md:items-center justify-between gap-4 mb-6">
            {{-- Role Tabs --}}
            <div
                class="flex items-center space-x-1 p-1.5 bg-zinc-100/80 border border-zinc-200/50 rounded-xl overflow-x-auto hide-scrollbar w-full md:max-w-full">
                @php
                    $currentJabatan = request('jabatan', '');
                    $tabs = [
                        '' => 'Semua',
                        'admin_lab' => 'Admin Lab',
                        'lecturer' => 'Dosen',
                        'assistant' => 'Asisten Dosen',
                        'student' => 'Mahasiswa'
                    ];
                @endphp
                @foreach($tabs as $val => $label)
                    <a href="{{ route('users.index', ['jabatan' => $val, 'keyword' => request('keyword')]) }}"
                        class="flex-1 md:flex-none text-center px-4 py-2.5 md:py-2 text-sm md:text-xs font-semibold rounded-lg transition-all whitespace-nowrap {{ $currentJabatan === (string) $val ? 'bg-white text-zinc-900 shadow-sm ring-1 ring-zinc-200' : 'text-zinc-500 hover:text-zinc-700 hover:bg-zinc-200/50' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>

            {{-- Search Input --}}
            <form method="GET" action="{{ route('users.index') }}" class="w-full md:w-72 relative flex items-center">
                @if(request('jabatan'))
                    <input type="hidden" name="jabatan" value="{{ request('jabatan') }}">
                @endif
                <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none">
                    <x-atoms.icon name="search" class="w-5 h-5 md:w-4 md:h-4 text-zinc-400" />
                </div>
                <input type="search" name="keyword" value="{{ request('keyword') }}"
                    class="block w-full h-12 md:h-10 pl-10 pr-10 text-sm md:text-xs font-medium text-zinc-800 border border-zinc-200 rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 shadow-sm transition-colors"
                    placeholder="Cari nama atau email..." />
                @if(request('keyword'))
                    <a href="{{ route('users.index', ['jabatan' => request('jabatan')]) }}"
                        class="absolute inset-y-0 right-0 flex items-center pr-3 text-zinc-400 hover:text-zinc-600 transition-colors">
                        <x-atoms.icon name="x-mark" class="w-4 h-4" />
                    </a>
                @endif
            </form>
        </div>

        {{-- Users Data Table / Card --}}
        @if($users->isEmpty())
            <x-ui.empty-state title="Belum ada pengguna"
                description="Tambahkan pengguna pertama atau sesuaikan filter pencarian untuk memulai." icon="users">
                @if (Auth::user()->jabatan !== 'Mahasiswa')
                    <x-slot name="action">
                        <button type="button" data-modal-target="add-modal" data-modal-toggle="add-modal"
                            class="inline-flex items-center justify-center text-white bg-indigo-600 hover:bg-indigo-700 font-semibold rounded-xl text-xs px-4 py-2 transition-colors shadow-sm">
                            Tambah Pengguna
                        </button>
                    </x-slot>
                @endif
            </x-ui.empty-state>
        @else
            <x-ui.table class="hidden lg:block flex-grow">
                <x-slot name="header">
                    <th class="px-6 py-4 text-[10px] font-bold text-indigo-900 uppercase tracking-wider w-10 text-center">No.</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-indigo-900 uppercase tracking-wider">Pengguna</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-indigo-900 uppercase tracking-wider">Role</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-indigo-900 uppercase tracking-wider">Jurusan</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-indigo-900 uppercase tracking-wider hidden md:table-cell">Bergabung</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-indigo-900 uppercase tracking-wider text-right">Aksi</th>
                </x-slot>
                            @foreach ($users as $user)
                                <tr class="group hover:bg-zinc-50/80 transition-all">
                                    <td class="px-6 py-4 text-center text-[13px] font-semibold text-zinc-500">
                                        {{ $users->firstItem() + $loop->index }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-4 min-w-0">
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=EEF2FF&color=4F46E5&bold=true"
                                                alt="{{ $user->name }}"
                                                class="w-10 h-10 rounded-full object-cover ring-4 ring-white shadow-sm shrink-0">
                                            <div class="min-w-0 flex-1">
                                                <p class="text-[14px] font-bold text-zinc-900 truncate tracking-tight">
                                                    {{ $user->name }}
                                                </p>
                                                <p class="text-[13px] font-medium text-zinc-500 truncate mt-0.5">
                                                    {{ $user->email }}
                                                </p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $badgeType = match (strtolower($user->jabatan)) {
                                                'admin lab' => 'indigo',
                                                'dosen' => 'emerald',
                                                'asisten dosen' => 'emerald',
                                                default => 'neutral'
                                            };
                                        @endphp
                                        <x-ui.badge :type="$badgeType">
                                            {{ Str::ucfirst($user->jabatan ?? '-') }}
                                        </x-ui.badge>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="text-[13px] font-medium text-zinc-600">{{ $user->department->name ?? '-' }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap hidden md:table-cell">
                                        <span class="text-[12px] font-medium text-zinc-400" title="{{ $user->created_at }}">
                                            {{ $user->created_at ? $user->created_at->diffForHumans() : '-' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        @if (Auth::user()->jabatan !== 'Mahasiswa')
                                            <div class="flex items-center justify-end">
                                                {{-- Row Actions Dropdown --}}
                                                <div x-data="{ open: false }" class="relative inline-block text-left">
                                                    <button @click="open = !open" @click.outside="open = false"
                                                        class="p-2 w-[44px] h-[44px] rounded-lg text-zinc-400 opacity-0 group-hover:opacity-100 focus:opacity-100 hover:text-zinc-600 hover:bg-zinc-200/50 transition-all flex items-center justify-center">
                                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                            <path
                                                                d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z" />
                                                        </svg>
                                                    </button>

                                                    <div x-show="open" x-cloak
                                                        class="absolute right-0 mt-1.5 w-44 rounded-xl bg-white border border-zinc-200 shadow-lg py-1.5 z-50 text-left">
                                                        <button type="button" data-modal-target="edit-modal-{{ $user->id }}"
                                                            data-modal-toggle="edit-modal-{{ $user->id }}"
                                                            class="w-full text-left px-3 py-1.5 text-xs font-semibold text-zinc-700 hover:bg-zinc-50 transition-colors flex items-center">
                                                            <x-atoms.icon name="settings" class="w-3.5 h-3.5 mr-2 text-zinc-400" />
                                                            Edit Pengguna
                                                        </button>

                                                        <button type="button"
                                                            class="w-full text-left px-3 py-1.5 text-xs font-semibold text-zinc-400 cursor-not-allowed hover:bg-zinc-50 transition-colors flex items-center">
                                                            <x-atoms.icon name="settings" class="w-3.5 h-3.5 mr-2 text-zinc-300" />
                                                            Reset Password
                                                        </button>

                                                        <button type="button"
                                                            class="w-full text-left px-3 py-1.5 text-xs font-semibold text-zinc-400 cursor-not-allowed hover:bg-zinc-50 transition-colors flex items-center">
                                                            <x-atoms.icon name="x-mark" class="w-3.5 h-3.5 mr-2 text-zinc-300" />
                                                            Nonaktifkan
                                                        </button>

                                                        <div class="h-px bg-zinc-100 my-1"></div>

                                                        <button type="button" data-modal-target="delete-modal-{{ $user->id }}"
                                                            data-modal-toggle="delete-modal-{{ $user->id }}"
                                                            class="w-full text-left px-3 py-1.5 text-xs font-semibold text-rose-600 hover:bg-rose-50 transition-colors flex items-center">
                                                            <x-atoms.icon name="trash" class="w-3.5 h-3.5 mr-2 text-rose-400" />
                                                            Hapus
                                                        </button>
                                                    </div>
                                                </div>

                                                
                                            </div>
                                        @endif
                                    </td>
                            @endforeach
            </x-ui.table>

                {{-- Desktop Pagination --}}
                <x-ui.pagination :paginator="$users" label="Total Pengguna" class="mt-6" />


            {{-- Mobile & Tablet Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:hidden gap-4 flex-grow">
                @foreach ($users as $user)
                    <div class="bg-white border border-zinc-200/80 rounded-2xl p-5 shadow-sm hover:shadow-md transition-shadow relative">
                        {{-- Action Menu --}}
                        @if (Auth::user()->jabatan !== 'Mahasiswa')
                            <div class="absolute top-4 right-4">
                                <div x-data="{ open: false }" class="relative inline-block text-left">
                                    <button @click="open = !open" @click.outside="open = false"
                                        class="p-2 rounded-xl text-zinc-400 hover:text-zinc-600 hover:bg-zinc-100 transition-all focus:outline-none focus:ring-2 focus:ring-indigo-500 flex items-center justify-center w-[44px] h-[44px]">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z" />
                                        </svg>
                                    </button>

                                    <div x-show="open" x-cloak
                                        class="absolute right-0 mt-1 w-44 rounded-xl bg-white border border-zinc-200 shadow-lg py-1.5 z-50 text-left">
                                        <button type="button" data-modal-target="edit-modal-{{ $user->id }}"
                                            data-modal-toggle="edit-modal-{{ $user->id }}"
                                            class="w-full text-left px-3 py-2 text-sm md:text-xs font-semibold text-zinc-700 hover:bg-zinc-50 transition-colors flex items-center min-h-[44px]">
                                            <x-atoms.icon name="settings" class="w-4 h-4 md:w-3.5 md:h-3.5 mr-2 text-zinc-400" />
                                            Edit Pengguna
                                        </button>
                                        <div class="h-px bg-zinc-100 my-1"></div>
                                        <button type="button" data-modal-target="delete-modal-{{ $user->id }}"
                                            data-modal-toggle="delete-modal-{{ $user->id }}"
                                            class="w-full text-left px-3 py-2 text-sm md:text-xs font-semibold text-rose-600 hover:bg-rose-50 transition-colors flex items-center min-h-[44px]">
                                            <x-atoms.icon name="trash" class="w-4 h-4 md:w-3.5 md:h-3.5 mr-2 text-rose-400" />
                                            Hapus
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Card Content --}}
                        <div class="flex items-start gap-4 mb-4 pr-12">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=EEF2FF&color=4F46E5&bold=true"
                                alt="{{ $user->name }}"
                                class="w-12 h-12 rounded-full object-cover ring-4 ring-white shadow-sm shrink-0">
                            <div class="min-w-0 flex-1">
                                <p class="text-[15px] font-bold text-zinc-900 truncate tracking-tight mb-0.5">
                                    {{ $user->name }}
                                </p>
                                <p class="text-[13px] font-medium text-zinc-500 truncate">
                                    {{ $user->email }}
                                </p>
                            </div>
                        </div>

                        <div class="space-y-3 pt-3 border-t border-zinc-100/80">
                            <div class="flex items-center justify-between gap-4">
                                <span class="text-[12px] font-bold text-zinc-400 uppercase tracking-wider">Role</span>
                                @php
                                    $badgeType = match (strtolower($user->jabatan)) {
                                        'admin lab' => 'indigo',
                                        'dosen' => 'emerald',
                                        'asisten dosen' => 'emerald',
                                        default => 'neutral'
                                    };
                                @endphp
                                <x-ui.badge :type="$badgeType">
                                    {{ Str::ucfirst($user->jabatan ?? '-') }}
                                </x-ui.badge>
                            </div>
                            <div class="flex items-center justify-between gap-4">
                                <span class="text-[12px] font-bold text-zinc-400 uppercase tracking-wider">Jurusan</span>
                                <span class="text-[13px] font-semibold text-zinc-700 text-right">{{ $user->department->name ?? '-' }}</span>
                            </div>
                            <div class="flex items-center justify-between gap-4">
                                <span class="text-[12px] font-bold text-zinc-400 uppercase tracking-wider">Bergabung</span>
                                <span class="text-[13px] font-semibold text-zinc-700 text-right">{{ $user->created_at ? $user->created_at->format('d M Y') : '-' }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

                        {{-- Extracted Modals --}}
        @foreach($users as $user)
            @if (Auth::user()->jabatan !== 'Mahasiswa')
                                                <form method="POST" action="{{ route('update.users', $user->id) }}"
                                                    id="edit-modal-{{ $user->id }}" tabindex="-1" aria-hidden="true"
                                                    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full text-left"
                                                    x-data="{ 
                                                                roles: {{ json_encode(old('roles', $user->roles->pluck('slug')->toArray())) }},
                                                                hasStudent() { return this.roles.includes('student') || this.roles.includes('assistant'); },
                                                                hasLecturer() { return this.roles.includes('lecturer') || this.roles.includes('admin_lab'); }
                                                            }">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="relative p-4 w-full max-w-2xl max-h-full">
                                                        <div class="relative bg-white rounded-2xl shadow-xl border border-zinc-200">
                                                            <div
                                                                class="flex items-center justify-between px-5 py-4 border-b border-zinc-100">
                                                                <h3 class="text-sm font-bold text-zinc-900">Edit Pengguna:
                                                                    {{ $user->name }}
                                                                </h3>
                                                                <button type="button"
                                                                    class="text-zinc-400 hover:bg-zinc-100 hover:text-zinc-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center transition-colors focus:outline-none"
                                                                    data-modal-hide="edit-modal-{{ $user->id }}">
                                                                    <x-atoms.icon name="x-mark" class="w-4 h-4" />
                                                                </button>
                                                            </div>

                                                            <div class="p-5 space-y-5 max-h-[70vh] overflow-y-auto hide-scrollbar">
                                                                {{-- Basic Information --}}
                                                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                                                    <div class="sm:col-span-2">
                                                                        <label
                                                                            class="block mb-1.5 text-xs font-bold text-zinc-500 uppercase">Nama
                                                                            Lengkap <span class="text-rose-500">*</span></label>
                                                                        <input type="text" name="name"
                                                                            value="{{ old('name', $user->name) }}" required
                                                                            class="bg-zinc-50 border border-zinc-200 text-zinc-800 text-xs font-medium rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 block w-full p-2.5 transition-colors" />
                                                                        @error('name')<p
                                                                            class="text-rose-500 text-[10px] mt-1 font-semibold">
                                                                        {{ $message }}</p>
                                                                        @enderror
                                                                    </div>
                                                                    <div class="sm:col-span-2">
                                                                        <label
                                                                            class="block mb-1.5 text-xs font-bold text-zinc-500 uppercase">Nomor
                                                                            HP</label>
                                                                        <input type="text" name="phone_number"
                                                                            value="{{ old('phone_number', $user->phone_number) }}"
                                                                            class="bg-zinc-50 border border-zinc-200 text-zinc-800 text-xs font-medium rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 block w-full p-2.5 transition-colors" />
                                                                        @error('phone_number')<p
                                                                            class="text-rose-500 text-[10px] mt-1 font-semibold">
                                                                        {{ $message }}</p>@enderror
                                                                    </div>
                                                                </div>

                                                                <div class="h-px bg-zinc-100"></div>

                                                                {{-- Roles Selection --}}
                                                                <div>
                                                                    <label
                                                                        class="block mb-2.5 text-xs font-bold text-zinc-500 uppercase">Peran
                                                                        / Role <span class="text-rose-500">*</span></label>
                                                                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                                                                        @foreach(App\Models\Role::all() ?? [] as $role)
                                                                            <label
                                                                                class="relative flex items-center justify-center p-3 border border-zinc-200 rounded-xl cursor-pointer hover:bg-zinc-50 transition-colors"
                                                                                :class="{ 
                                                                                                'bg-indigo-50/50 border-indigo-200 ring-1 ring-indigo-500/20': roles.includes('{{ $role->slug }}'),
                                                                                                'opacity-60 cursor-not-allowed bg-zinc-50': 
                                                                                                    ('{{ $role->slug }}' === 'lecturer' && roles.includes('admin_lab')) || 
                                                                                                    ('{{ $role->slug }}' === 'student' && roles.includes('assistant')) ||
                                                                                                    (['student', 'assistant'].includes('{{ $role->slug }}') && hasLecturer()) ||
                                                                                                    (['lecturer', 'admin_lab'].includes('{{ $role->slug }}') && hasStudent())
                                                                                            }">
                                                                                <input type="checkbox" name="roles[]"
                                                                                    value="{{ $role->slug }}" x-model="roles"
                                                                                    class="sr-only" :disabled="
                                                                                                    ('{{ $role->slug }}' === 'lecturer' && roles.includes('admin_lab')) || 
                                                                                                    ('{{ $role->slug }}' === 'student' && roles.includes('assistant')) ||
                                                                                                    (['student', 'assistant'].includes('{{ $role->slug }}') && hasLecturer()) ||
                                                                                                    (['lecturer', 'admin_lab'].includes('{{ $role->slug }}') && hasStudent())
                                                                                                " @change="
                                                                                                    if('{{ $role->slug }}' === 'admin_lab' && roles.includes('admin_lab') && !roles.includes('lecturer')) roles.push('lecturer');
                                                                                                    if('{{ $role->slug }}' === 'assistant' && roles.includes('assistant') && !roles.includes('student')) roles.push('student');
                                                                                                ">
                                                                                <span class="text-xs font-bold text-zinc-700 select-none"
                                                                                    :class="{ 'text-indigo-700': roles.includes('{{ $role->slug }}') }">{{ $role->name }}</span>
                                                                            </label>
                                                                        @endforeach
                                                                    </div>
                                                                    @error('roles')<p
                                                                        class="text-rose-500 text-[10px] mt-1 font-semibold">
                                                                    {{ $message }}</p>@enderror
                                                                </div>

                                                                {{-- Dynamic Student Fields --}}
                                                                <div x-show="hasStudent()" x-cloak
                                                                    class="space-y-4 p-4 rounded-xl bg-zinc-50/50 border border-zinc-100">
                                                                    <h4 class="text-xs font-bold text-zinc-800 uppercase tracking-wide">
                                                                        Data Mahasiswa</h4>
                                                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                                                        <div>
                                                                            <label
                                                                                class="block mb-1.5 text-xs font-bold text-zinc-500 uppercase">NIM
                                                                                <span class="text-rose-500">*</span></label>
                                                                            <input type="text" name="nim"
                                                                                value="{{ old('nim', $user->nim) }}"
                                                                                :required="hasStudent()"
                                                                                class="bg-white border border-zinc-200 text-zinc-800 text-xs font-medium rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 block w-full p-2.5 transition-colors" />
                                                                            @error('nim')<p
                                                                                class="text-rose-500 text-[10px] mt-1 font-semibold">
                                                                            {{ $message }}</p>@enderror
                                                                        </div>
                                                                        <div>
                                                                            <label
                                                                                class="block mb-1.5 text-xs font-bold text-zinc-500 uppercase">Tahun
                                                                                Angkatan <span class="text-rose-500">*</span></label>
                                                                            <input type="number" name="entry_year"
                                                                                value="{{ old('entry_year', $user->entry_year) }}"
                                                                                :required="hasStudent()"
                                                                                class="bg-white border border-zinc-200 text-zinc-800 text-xs font-medium rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 block w-full p-2.5 transition-colors" />
                                                                            @error('entry_year')<p
                                                                                class="text-rose-500 text-[10px] mt-1 font-semibold">
                                                                            {{ $message }}</p>@enderror
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                {{-- Dynamic Lecturer Fields --}}
                                                                <div x-show="hasLecturer()" x-cloak
                                                                    class="space-y-4 p-4 rounded-xl bg-zinc-50/50 border border-zinc-100">
                                                                    <h4 class="text-xs font-bold text-zinc-800 uppercase tracking-wide">
                                                                        Data Dosen</h4>
                                                                    <div>
                                                                        <label
                                                                            class="block mb-1.5 text-xs font-bold text-zinc-500 uppercase">NIP
                                                                            <span class="text-rose-500">*</span></label>
                                                                        <input type="text" name="nip"
                                                                            value="{{ old('nip', $user->nip) }}"
                                                                            :required="hasLecturer()"
                                                                            class="bg-white border border-zinc-200 text-zinc-800 text-xs font-medium rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 block w-full p-2.5 transition-colors" />
                                                                        @error('nip')<p
                                                                            class="text-rose-500 text-[10px] mt-1 font-semibold">
                                                                        {{ $message }}</p>@enderror
                                                                    </div>
                                                                </div>

                                                                {{-- General Info (Department) --}}
                                                                <div x-show="roles.length > 0" x-cloak>
                                                                    <label
                                                                        class="block mb-1.5 text-xs font-bold text-zinc-500 uppercase">Jurusan</label>
                                                                    <select name="department_id"
                                                                        class="bg-zinc-50 border border-zinc-200 text-zinc-800 text-xs font-semibold rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 block w-full p-2.5 transition-colors">
                                                                        <option value="">Pilih Jurusan...</option>
                                                                        @foreach(App\Models\Department::all()->groupBy('faculty') as $faculty => $depts)
                                                                            <optgroup label="{{ $faculty ?: 'Lainnya' }}">
                                                                                @foreach($depts as $dept)
                                                                                    <option value="{{ $dept->id }}" {{ old('department_id', $user->department_id) == $dept->id ? 'selected' : '' }}>
                                                                                        {{ $dept->name }}</option>
                                                                                @endforeach
                                                                            </optgroup>
                                                                        @endforeach
                                                                    </select>
                                                                    @error('department_id')<p
                                                                        class="text-rose-500 text-[10px] mt-1 font-semibold">
                                                                    {{ $message }}</p>@enderror
                                                                </div>
                                                            </div>

                                                            <div
                                                                class="flex items-center justify-end px-5 py-4 border-t border-zinc-100 bg-zinc-50/50 rounded-b-2xl gap-3">
                                                                <button data-modal-hide="edit-modal-{{ $user->id }}" type="button"
                                                                    class="py-2.5 px-4 text-xs font-semibold text-zinc-700 bg-white border border-zinc-200 hover:bg-zinc-50 rounded-xl transition-colors">
                                                                    Batal
                                                                </button>
                                                                <button type="submit"
                                                                    class="text-white bg-indigo-600 hover:bg-indigo-700 font-semibold rounded-xl text-xs px-4 py-2.5 transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                                                    Simpan Perubahan
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>

                                                {{-- Delete Modal --}}
                                                <form method="POST" action="{{ route('delete.users', $user->id) }}"
                                                    id="delete-modal-{{ $user->id }}" tabindex="-1" aria-hidden="true"
                                                    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full text-left">
                                                    @csrf
                                                    @method('DELETE')
                                                    <div class="relative p-4 w-full max-w-md max-h-full">
                                                        <div class="relative bg-white rounded-2xl shadow-xl border border-zinc-200">
                                                            <button type="button"
                                                                class="absolute top-3 right-3 text-zinc-400 hover:bg-zinc-100 hover:text-zinc-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center focus:outline-none"
                                                                data-modal-hide="delete-modal-{{ $user->id }}">
                                                                <x-atoms.icon name="x-mark" class="w-4 h-4" />
                                                                <span class="sr-only">Tutup</span>
                                                            </button>
                                                            <div class="p-6 text-center">
                                                                <div
                                                                    class="w-12 h-12 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center mx-auto mb-4">
                                                                    <x-atoms.icon name="trash" class="w-6 h-6" />
                                                                </div>
                                                                <h3 class="text-sm font-bold text-zinc-900 mb-2">Hapus Pengguna</h3>
                                                                <p class="text-xs text-zinc-500 font-medium mb-6 leading-relaxed">
                                                                    Apakah Anda yakin ingin menghapus <b>{{ $user->name }}</b>? Tindakan
                                                                    ini
                                                                    tidak dapat dibatalkan.
                                                                </p>
                                                                <div class="flex items-center justify-center gap-3">
                                                                    <button data-modal-hide="delete-modal-{{ $user->id }}" type="button"
                                                                        class="py-2.5 px-4 text-xs font-semibold text-zinc-700 bg-white border border-zinc-200 hover:bg-zinc-50 rounded-xl transition-colors">
                                                                        Batalkan
                                                                    </button>
                                                                    <button type="submit"
                                                                        class="text-white bg-rose-600 hover:bg-rose-700 font-semibold rounded-xl text-xs px-4 py-2.5 transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-rose-500">
                                                                        Ya, Hapus
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
            @endif
        @endforeach

            {{-- Mobile Pagination --}}
            <x-ui.pagination :paginator="$users" label="Total Pengguna" class="lg:hidden mt-4" />
        @endif
    </div>

    {{-- Add User Modal --}}
    <form method="POST" action="{{ route('add.users') }}" id="add-modal" tabindex="-1" aria-hidden="true"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full"
        x-data="{ 
                roles: {{ json_encode(old('roles', [])) }},
                hasStudent() { return this.roles.includes('student') || this.roles.includes('assistant'); },
                hasLecturer() { return this.roles.includes('lecturer') || this.roles.includes('admin_lab'); }
            }">
        @csrf
        <div class="relative p-4 w-full max-w-2xl max-h-full">
            <div class="relative bg-white rounded-2xl shadow-xl border border-zinc-200">
                <div class="flex items-center justify-between px-5 py-4 border-b border-zinc-100">
                    <h3 class="text-sm font-bold text-zinc-900">Tambah Pengguna Baru</h3>
                    <button type="button"
                        class="text-zinc-400 hover:bg-zinc-100 hover:text-zinc-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center transition-colors focus:outline-none"
                        data-modal-hide="add-modal">
                        <x-atoms.icon name="x-mark" class="w-4 h-4" />
                    </button>
                </div>

                <div class="p-5 space-y-5 max-h-[70vh] overflow-y-auto hide-scrollbar">
                    {{-- Basic Information --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="sm:col-span-2">
                            <label class="block mb-1.5 text-xs font-bold text-zinc-500 uppercase">Nama Lengkap <span
                                    class="text-rose-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}" required
                                class="bg-zinc-50 border border-zinc-200 text-zinc-800 text-xs font-medium rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 block w-full p-2.5 transition-colors"
                                placeholder="Masukkan nama..." />
                            @error('name')<p class="text-rose-500 text-[10px] mt-1 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block mb-1.5 text-xs font-bold text-zinc-500 uppercase">Alamat Email <span
                                    class="text-rose-500">*</span></label>
                            <input type="email" name="email" value="{{ old('email') }}" required
                                class="bg-zinc-50 border border-zinc-200 text-zinc-800 text-xs font-medium rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 block w-full p-2.5 transition-colors"
                                placeholder="email@contoh.com" />
                            @error('email')<p class="text-rose-500 text-[10px] mt-1 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block mb-1.5 text-xs font-bold text-zinc-500 uppercase">Nomor HP</label>
                            <input type="text" name="phone_number" value="{{ old('phone_number') }}"
                                class="bg-zinc-50 border border-zinc-200 text-zinc-800 text-xs font-medium rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 block w-full p-2.5 transition-colors"
                                placeholder="08..." />
                            @error('phone_number')<p class="text-rose-500 text-[10px] mt-1 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="h-px bg-zinc-100"></div>

                    {{-- Roles Selection --}}
                    <div>
                        <label class="block mb-2.5 text-xs font-bold text-zinc-500 uppercase">Peran / Role <span
                                class="text-rose-500">*</span></label>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                            @foreach(App\Models\Role::all() ?? [] as $role)
                                <label
                                    class="relative flex items-center justify-center p-3 border border-zinc-200 rounded-xl cursor-pointer hover:bg-zinc-50 transition-colors"
                                    :class="{ 
                                                'bg-indigo-50/50 border-indigo-200 ring-1 ring-indigo-500/20': roles.includes('{{ $role->slug }}'),
                                                'opacity-60 cursor-not-allowed bg-zinc-50': 
                                                    ('{{ $role->slug }}' === 'lecturer' && roles.includes('admin_lab')) || 
                                                    ('{{ $role->slug }}' === 'student' && roles.includes('assistant')) ||
                                                    (['student', 'assistant'].includes('{{ $role->slug }}') && hasLecturer()) ||
                                                    (['lecturer', 'admin_lab'].includes('{{ $role->slug }}') && hasStudent())
                                            }">
                                    <input type="checkbox" name="roles[]" value="{{ $role->slug }}" x-model="roles"
                                        class="sr-only" :disabled="
                                                    ('{{ $role->slug }}' === 'lecturer' && roles.includes('admin_lab')) || 
                                                    ('{{ $role->slug }}' === 'student' && roles.includes('assistant')) ||
                                                    (['student', 'assistant'].includes('{{ $role->slug }}') && hasLecturer()) ||
                                                    (['lecturer', 'admin_lab'].includes('{{ $role->slug }}') && hasStudent())
                                                " @change="
                                                    if('{{ $role->slug }}' === 'admin_lab' && roles.includes('admin_lab') && !roles.includes('lecturer')) roles.push('lecturer');
                                                    if('{{ $role->slug }}' === 'assistant' && roles.includes('assistant') && !roles.includes('student')) roles.push('student');
                                                ">
                                    <span class="text-xs font-bold text-zinc-700 select-none"
                                        :class="{ 'text-indigo-700': roles.includes('{{ $role->slug }}') }">{{ $role->name }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('roles')<p class="text-rose-500 text-[10px] mt-1 font-semibold">{{ $message }}</p>@enderror
                        <p class="mt-2 text-[11px] font-medium text-zinc-500">Pilih satu atau lebih peran. Admin Lab
                            otomatis mendapatkan akses Dosen. Asisten otomatis Mahasiswa.</p>
                    </div>

                    {{-- Dynamic Student Fields --}}
                    <div x-show="hasStudent()" x-cloak
                        class="space-y-4 p-4 rounded-xl bg-zinc-50/50 border border-zinc-100">
                        <h4 class="text-xs font-bold text-zinc-800 uppercase tracking-wide">Data Mahasiswa</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block mb-1.5 text-xs font-bold text-zinc-500 uppercase">NIM <span
                                        class="text-rose-500">*</span></label>
                                <input type="text" name="nim" value="{{ old('nim') }}" :required="hasStudent()"
                                    class="bg-white border border-zinc-200 text-zinc-800 text-xs font-medium rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 block w-full p-2.5 transition-colors"
                                    placeholder="Masukkan NIM..." />
                                @error('nim')<p class="text-rose-500 text-[10px] mt-1 font-semibold">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block mb-1.5 text-xs font-bold text-zinc-500 uppercase">Tahun Angkatan <span
                                        class="text-rose-500">*</span></label>
                                <input type="number" name="entry_year" value="{{ old('entry_year') }}"
                                    :required="hasStudent()"
                                    class="bg-white border border-zinc-200 text-zinc-800 text-xs font-medium rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 block w-full p-2.5 transition-colors"
                                    placeholder="2024" />
                                @error('entry_year')<p class="text-rose-500 text-[10px] mt-1 font-semibold">{{ $message }}
                                </p>@enderror
                            </div>
                        </div>
                    </div>

                    {{-- Dynamic Lecturer Fields --}}
                    <div x-show="hasLecturer()" x-cloak
                        class="space-y-4 p-4 rounded-xl bg-zinc-50/50 border border-zinc-100">
                        <h4 class="text-xs font-bold text-zinc-800 uppercase tracking-wide">Data Dosen</h4>
                        <div>
                            <label class="block mb-1.5 text-xs font-bold text-zinc-500 uppercase">NIP <span
                                    class="text-rose-500">*</span></label>
                            <input type="text" name="nip" value="{{ old('nip') }}" :required="hasLecturer()"
                                class="bg-white border border-zinc-200 text-zinc-800 text-xs font-medium rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 block w-full p-2.5 transition-colors"
                                placeholder="Masukkan NIP..." />
                            @error('nip')<p class="text-rose-500 text-[10px] mt-1 font-semibold">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    {{-- General Info (Department) --}}
                    <div x-show="roles.length > 0" x-cloak>
                        <label class="block mb-1.5 text-xs font-bold text-zinc-500 uppercase">Jurusan</label>
                        <select name="department_id"
                            class="bg-zinc-50 border border-zinc-200 text-zinc-800 text-xs font-semibold rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 block w-full p-2.5 transition-colors">
                            <option value="">Pilih Jurusan...</option>
                            @foreach(App\Models\Department::all()->groupBy('faculty') as $faculty => $depts)
                                <optgroup label="{{ $faculty ?: 'Lainnya' }}">
                                    @foreach($depts as $dept)
                                        <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>
                                            {{ $dept->name }}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                        @error('department_id')<p class="text-rose-500 text-[10px] mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="p-3 bg-indigo-50/50 border border-indigo-100 rounded-xl flex items-start gap-3">
                        <x-atoms.icon name="information-circle" class="w-5 h-5 text-indigo-500 shrink-0 mt-0.5" />
                        <div>
                            <p class="text-xs font-bold text-indigo-900">Password Default</p>
                            <p class="text-[11px] font-medium text-indigo-700/80 mt-0.5">Password pengguna otomatis diatur
                                ke <b>LabUIN@2026</b> dan pengguna wajib mengganti password saat login pertama.</p>
                        </div>
                    </div>
                </div>

                <div
                    class="flex items-center justify-end px-5 py-4 border-t border-zinc-100 bg-zinc-50/50 rounded-b-2xl gap-3">
                    <button data-modal-hide="add-modal" type="button"
                        class="py-2.5 px-4 text-xs font-semibold text-zinc-700 bg-white border border-zinc-200 hover:bg-zinc-50 rounded-xl transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                        class="text-white bg-indigo-600 hover:bg-indigo-700 font-semibold rounded-xl text-xs px-4 py-2.5 transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        Tambah Pengguna
                    </button>
                </div>
            </div>
        </div>
    </form>
@endsection