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
    <x-ui.table class="hidden lg:block flex-grow mt-4">
        <x-slot name="header">
            <th class="px-6 py-4 text-[10px] font-bold text-foreground-muted uppercase tracking-wider w-10 text-center">No.</th>
            <th class="px-6 py-4 text-[10px] font-bold text-foreground-muted uppercase tracking-wider">Pengguna</th>
            <th class="px-6 py-4 text-[10px] font-bold text-foreground-muted uppercase tracking-wider">Role</th>
            <th class="px-6 py-4 text-[10px] font-bold text-foreground-muted uppercase tracking-wider">Jurusan</th>
            <th class="px-6 py-4 text-[10px] font-bold text-foreground-muted uppercase tracking-wider hidden md:table-cell">
                Bergabung</th>
            <th class="px-6 py-4 text-[10px] font-bold text-foreground-muted uppercase tracking-wider text-right">Aksi</th>
        </x-slot>

        @foreach ($users as $user)
            <tr class="group hover:bg-surface-muted/80 transition-all">
                <td class="px-6 py-4 text-center text-[13px] font-semibold text-foreground-muted">
                    {{ $users->firstItem() + $loop->index }}
                </td>
                <td class="px-6 py-4">
                    <div class="flex items-center gap-4 min-w-0">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=EEF2FF&color=4F46E5&bold=true"
                            alt="{{ $user->name }}"
                            class="w-10 h-10 rounded-full object-cover ring-4 ring-surface shadow-sm shrink-0">
                        <div class="min-w-0 flex-1">
                            <p class="text-[14px] font-bold text-foreground truncate tracking-tight">{{ $user->name }}</p>
                            <p class="text-[13px] font-medium text-foreground-muted truncate mt-0.5">{{ $user->email }}</p>
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
                    <span class="text-[13px] font-medium text-foreground-muted">{{ $user->department->name ?? '-' }}</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap hidden md:table-cell">
                    <span class="text-[12px] font-medium text-foreground-muted/60" title="{{ $user->created_at }}">
                        {{ $user->created_at ? $user->created_at->diffForHumans() : '-' }}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right">
                    @if (Auth::user()->jabatan !== 'Mahasiswa')
                        <div class="flex items-center justify-end">
                            <x-table.action-menu>
                                <a href="{{ route('users.show', $user->id) }}"
                                    class="w-full text-left px-3 py-1.5 text-xs font-semibold text-foreground-muted hover:bg-surface-muted transition-colors flex items-center">
                                    <x-atoms.icon name="settings" class="w-3.5 h-3.5 mr-2 text-foreground-muted/60" />
                                    Edit Pengguna
                                </a>
                                <button type="button"
                                    class="w-full text-left px-3 py-1.5 text-xs font-semibold text-foreground-muted/60 cursor-not-allowed hover:bg-surface-muted transition-colors flex items-center">
                                    <x-atoms.icon name="settings" class="w-3.5 h-3.5 mr-2 text-default" />
                                    Reset Password
                                </button>
                                <button type="button"
                                    class="w-full text-left px-3 py-1.5 text-xs font-semibold text-foreground-muted/60 cursor-not-allowed hover:bg-surface-muted transition-colors flex items-center">
                                    <x-atoms.icon name="x-mark" class="w-3.5 h-3.5 mr-2 text-default" />
                                    Nonaktifkan
                                </button>
                                <div class="h-px bg-surface-muted my-1"></div>
                                <button type="button" data-modal-target="delete-modal-{{ $user->id }}"
                                    data-modal-toggle="delete-modal-{{ $user->id }}"
                                    class="w-full text-left px-3 py-1.5 text-xs font-semibold text-danger hover:ui-danger-soft transition-colors flex items-center">
                                    <x-atoms.icon name="trash" class="w-3.5 h-3.5 mr-2 text-danger/80" />
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
            <div
                class="ui-surface border border-default/80 rounded-3xl p-5 shadow-sm hover:shadow-md transition-shadow relative">
                @if (Auth::user()->jabatan !== 'Mahasiswa')
                    <div class="absolute top-4 right-4">
                        <x-table.action-menu>
                            <a href="{{ route('users.show', $user->id) }}"
                                class="w-full text-left px-3 py-2 text-sm md:text-xs font-semibold text-foreground-muted hover:bg-surface-muted transition-colors flex items-center min-h-[44px]">
                                <x-atoms.icon name="settings" class="w-4 h-4 md:w-3.5 md:h-3.5 mr-2 text-foreground-muted/60" />
                                Edit Pengguna
                            </a>
                            <div class="h-px bg-surface-muted my-1"></div>
                            <button type="button" data-modal-target="delete-modal-{{ $user->id }}"
                                data-modal-toggle="delete-modal-{{ $user->id }}"
                                class="w-full text-left px-3 py-2 text-sm md:text-xs font-semibold text-danger hover:ui-danger-soft transition-colors flex items-center min-h-[44px]">
                                <x-atoms.icon name="trash" class="w-4 h-4 md:w-3.5 md:h-3.5 mr-2 text-danger/80" />
                                Hapus
                            </button>
                        </x-table.action-menu>
                    </div>
                @endif

                <div class="flex items-start gap-4 mb-4 pr-12">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=EEF2FF&color=4F46E5&bold=true"
                        alt="{{ $user->name }}"
                        class="w-12 h-12 rounded-full object-cover ring-4 ring-surface shadow-sm shrink-0">
                    <div class="min-w-0 flex-1">
                        <p class="text-[15px] font-bold text-foreground truncate tracking-tight mb-0.5">{{ $user->name }}</p>
                        <p class="text-[13px] font-medium text-foreground-muted truncate">{{ $user->email }}</p>
                    </div>
                </div>

                <div class="space-y-3 pt-3 border-t border-default/50">
                    <div class="flex items-center justify-between gap-4">
                        <span class="text-[12px] font-bold text-foreground-muted/60 uppercase tracking-wider">Role</span>
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
                        <span class="text-[12px] font-bold text-foreground-muted/60 uppercase tracking-wider">Jurusan</span>
                        <span
                            class="text-[13px] font-semibold text-foreground-muted text-right">{{ $user->department->name ?? '-' }}</span>
                    </div>
                    <div class="flex items-center justify-between gap-4">
                        <span class="text-[12px] font-bold text-foreground-muted/60 uppercase tracking-wider">Bergabung</span>
                        <span
                            class="text-[13px] font-semibold text-foreground-muted text-right">{{ $user->created_at ? $user->created_at->format('d M Y') : '-' }}</span>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <x-ui.pagination :paginator="$users" label="Total Pengguna" class="lg:hidden mt-4" />
@endif

@foreach($users as $user)
    @if (Auth::user()->jabatan !== 'Mahasiswa')
        <x-admin.users.delete-modal :user="$user" />
    @endif
@endforeach