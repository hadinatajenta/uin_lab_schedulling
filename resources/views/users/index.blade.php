@extends('layouts.app')

@section('title', 'Data Pengguna')

@section('content')
    <div class="px-2 pb-8 min-h-[calc(100vh-12rem)] flex flex-col space-y-6">
        <x-ui.page-header title="User Data" description="Manage laboratory system users.">
            @if (Auth::user()->jabatan !== 'Mahasiswa')
                <div x-data="{ open: false }" class="relative inline-block text-left w-full md:w-auto">
                    <button @click="open = !open" @click.outside="open = false" type="button"
                        class="w-full md:w-auto inline-flex items-center justify-center rounded-xl ui-primary px-4 h-11 md:h-10 font-semibold text-sm md:text-xs shadow-sm shadow-[rgb(var(--color-primary))_/_0.1] hover:opacity-90 transition-opacity focus:outline-none focus:ring-2 focus:ring-[rgb(var(--color-primary))] focus:ring-offset-2">
                        <span class="material-symbols-rounded text-[20px] md:text-[18px] mr-2">add</span>
                        Tambah Pengguna
                        <span class="material-symbols-rounded text-[20px] md:text-[18px] ml-1 opacity-70">expand_more</span>
                    </button>

                    <div x-show="open" x-cloak
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="transform opacity-100 scale-100"
                        x-transition:leave-end="transform opacity-0 scale-95"
                        class="absolute right-0 mt-2 w-56 rounded-xl ui-surface border border-zinc-200 shadow-lg py-1.5 z-50 text-left">
                        
                        <button type="button" data-modal-target="add-modal" data-modal-toggle="add-modal" @click="open = false"
                            class="w-full text-left px-4 py-2.5 text-sm md:text-xs font-semibold text-zinc-700 hover:bg-zinc-50 hover:text-[rgb(var(--color-primary))] transition-colors flex items-center">
                            <span class="material-symbols-rounded text-[18px] mr-2 text-zinc-400">person_add</span>
                            Input Manual
                        </button>
                        
                        <div class="h-px bg-zinc-100 my-1.5"></div>
                        
                        <a href="{{ route('users.import.view') }}"
                            class="w-full text-left px-4 py-2.5 text-sm md:text-xs font-semibold text-zinc-700 hover:bg-zinc-50 hover:text-[rgb(var(--color-primary))] transition-colors flex items-center">
                            <span class="material-symbols-rounded text-[18px] mr-2 text-[rgb(var(--color-primary))]">upload_file</span>
                            Import Excel (Bulk)
                        </a>
                    </div>
                </div>
            @endif
        </x-ui.page-header>

        <div class="ui-surface border border-zinc-200/80 rounded-3xl p-4 shadow-sm">
            <div class="flex flex-col-reverse md:flex-row items-stretch md:items-center justify-between gap-4">
                <div class="flex items-center space-x-1 p-1.5 bg-zinc-100/80 border border-zinc-200/50 rounded-2xl overflow-x-auto hide-scrollbar w-full md:max-w-full">
                    @php
                        $currentJabatan = request('jabatan', '');
                        $tabs = [
                            '' => 'Semua',
                            'admin_lab' => 'Admin Lab',
                            'lecturer' => 'Dosen',
                            'assistant' => 'Asisten Dosen',
                            'student' => 'Mahasiswa',
                        ];
                    @endphp
                    @foreach ($tabs as $val => $label)
                        <a href="{{ route('users.index', ['jabatan' => $val, 'keyword' => request('keyword')]) }}"
                            class="flex-1 md:flex-none text-center px-4 py-2.5 md:py-2 text-sm md:text-xs font-semibold rounded-xl transition-all whitespace-nowrap {{ $currentJabatan === (string) $val ? 'ui-surface text-zinc-900 shadow-sm ring-1 ring-zinc-200' : 'text-zinc-500 hover:text-zinc-700 hover:bg-zinc-200/50' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                </div>

                <form method="GET" action="{{ route('users.index') }}" class="w-full md:w-72 relative flex items-center">
                    @if(request('jabatan'))
                        <input type="hidden" name="jabatan" value="{{ request('jabatan') }}">
                    @endif
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none">
                        <x-atoms.icon name="search" class="w-5 h-5 md:w-4 md:h-4 text-zinc-400" />
                    </div>
                    <input type="search" name="keyword" value="{{ request('keyword') }}"
                        class="block w-full h-12 md:h-10 pl-10 pr-10 text-sm md:text-xs font-medium text-zinc-800 border border-zinc-200 rounded-xl ui-surface focus:outline-none focus:ring-2 focus:ring-[rgb(var(--color-primary)_/_0.2)] focus:border-[rgb(var(--color-primary))] shadow-sm transition-colors"
                        placeholder="Cari nama atau email..." />
                    @if(request('keyword'))
                        <a href="{{ route('users.index', ['jabatan' => request('jabatan')]) }}"
                            class="absolute inset-y-0 right-0 flex items-center pr-3 text-zinc-400 hover:text-zinc-600 transition-colors">
                            <x-atoms.icon name="x-mark" class="w-4 h-4" />
                        </a>
                    @endif
                </form>
            </div>
        </div>

        @if($users->isEmpty())
            <x-ui.empty-state title="Belum ada pengguna"
                description="Tambahkan pengguna pertama atau sesuaikan filter pencarian untuk memulai." icon="users">
                @if (Auth::user()->jabatan !== 'Mahasiswa')
                    <x-slot name="action">
                        <button type="button" data-modal-target="add-modal" data-modal-toggle="add-modal"
                            class="inline-flex items-center justify-center ui-primary hover:opacity-90 font-semibold rounded-xl text-xs px-4 py-2 transition-opacity shadow-sm">
                            Tambah Pengguna
                        </button>
                    </x-slot>
                @endif
            </x-ui.empty-state>
        @else
            <x-ui.table class="hidden lg:block flex-grow">
                <x-slot name="header">
                    <th class="px-6 py-4 text-[10px] font-bold text-zinc-500 uppercase tracking-wider w-10 text-center">No.</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-zinc-500 uppercase tracking-wider">Pengguna</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-zinc-500 uppercase tracking-wider">Role</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-zinc-500 uppercase tracking-wider">Jurusan</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-zinc-500 uppercase tracking-wider hidden md:table-cell">Bergabung</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-zinc-500 uppercase tracking-wider text-right">Aksi</th>
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
                                    <p class="text-[14px] font-bold text-zinc-900 truncate tracking-tight">{{ $user->name }}</p>
                                    <p class="text-[13px] font-medium text-zinc-500 truncate mt-0.5">{{ $user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $badgeType = match (strtolower($user->jabatan)) {
                                    'admin lab' => 'primary',
                                    'dosen' => 'primary',
                                    'asisten dosen' => 'primary',
                                    default => 'neutral',
                                };
                            @endphp
                            <x-ui.badge :type="$badgeType">
                                {{ Str::ucfirst($user->jabatan ?? '-') }}
                            </x-ui.badge>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-[13px] font-medium text-zinc-600">{{ $user->department->name ?? '-' }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap hidden md:table-cell">
                            <span class="text-[12px] font-medium text-zinc-400" title="{{ $user->created_at }}">
                                {{ $user->created_at ? $user->created_at->diffForHumans() : '-' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            @if (Auth::user()->jabatan !== 'Mahasiswa')
                                <div class="flex items-center justify-end">
                                    <x-table.action-menu>
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
                                    </x-table.action-menu>
                                </div>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </x-ui.table>

            <x-ui.pagination :paginator="$users" label="Total Pengguna" class="mt-6" />

            <div class="grid grid-cols-1 md:grid-cols-2 lg:hidden gap-4 flex-grow">
                @foreach ($users as $user)
                    <div class="ui-surface border border-zinc-200/80 rounded-3xl p-5 shadow-sm hover:shadow-md transition-shadow relative">
                        @if (Auth::user()->jabatan !== 'Mahasiswa')
                            <div class="absolute top-4 right-4">
                                <x-table.action-menu>
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
                                </x-table.action-menu>
                            </div>
                        @endif

                        <div class="flex items-start gap-4 mb-4 pr-12">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=EEF2FF&color=4F46E5&bold=true"
                                alt="{{ $user->name }}"
                                class="w-12 h-12 rounded-full object-cover ring-4 ring-white shadow-sm shrink-0">
                            <div class="min-w-0 flex-1">
                                <p class="text-[15px] font-bold text-zinc-900 truncate tracking-tight mb-0.5">{{ $user->name }}</p>
                                <p class="text-[13px] font-medium text-zinc-500 truncate">{{ $user->email }}</p>
                            </div>
                        </div>

                        <div class="space-y-3 pt-3 border-t border-zinc-100/80">
                            <div class="flex items-center justify-between gap-4">
                                <span class="text-[12px] font-bold text-zinc-400 uppercase tracking-wider">Role</span>
                                @php
                                    $badgeType = match (strtolower($user->jabatan)) {
                                        'admin lab' => 'primary',
                                        'dosen' => 'primary',
                                        'asisten dosen' => 'primary',
                                        default => 'neutral',
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

            <x-ui.pagination :paginator="$users" label="Total Pengguna" class="lg:hidden mt-4" />
        @endif
    </div>

    @foreach($users as $user)
        @if (Auth::user()->jabatan !== 'Mahasiswa')
            <x-admin.users.edit-modal :user="$user" />
            <x-admin.users.delete-modal :user="$user" />
        @endif
    @endforeach

    @if (Auth::user()->jabatan !== 'Mahasiswa')
        <x-admin.users.add-modal />
    @endif
@endsection
