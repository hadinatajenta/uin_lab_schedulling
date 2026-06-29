@if($schedule->isEmpty())
    <x-ui.empty-state title="Belum ada jadwal"
        description="Tambahkan jadwal pertama atau sesuaikan filter pencarian untuk memulai." icon="calendar">
        @if (Auth::user()->jabatan !== 'Mahasiswa')
            <x-slot name="action">
                <a href="{{ route('addJadwalView') }}"
                    class="inline-flex items-center justify-center rounded-xl ui-primary hover:opacity-90 px-4 py-2 text-xs font-semibold shadow-sm transition-opacity">
                    Tambah Jadwal
                </a>
            </x-slot>
        @endif
    </x-ui.empty-state>
@else
    <x-ui.table class="hidden lg:block mb-6">
        <x-slot name="header">
            <th class="px-6 py-4 text-xs font-semibold text-foreground-muted uppercase tracking-wider">No</th>
            <th class="px-6 py-4 text-xs font-semibold text-foreground-muted uppercase tracking-wider">Mata Kuliah</th>
            <th class="px-6 py-4 text-xs font-semibold text-foreground-muted uppercase tracking-wider">Dosen</th>
            <th class="px-6 py-4 text-xs font-semibold text-foreground-muted uppercase tracking-wider">Kelas</th>
            <th class="px-6 py-4 text-xs font-semibold text-foreground-muted uppercase tracking-wider">Tanggal</th>
            <th class="px-6 py-4 text-xs font-semibold text-foreground-muted uppercase tracking-wider">Waktu</th>
            <th class="px-6 py-4 text-xs font-semibold text-foreground-muted uppercase tracking-wider">Status</th>
            @if (Auth::user()->jabatan !== 'Mahasiswa')
                <th class="px-6 py-4 text-xs font-semibold text-foreground-muted uppercase tracking-wider text-right">Aksi</th>
            @endif
        </x-slot>

        @foreach ($schedule as $index => $jadwal)
            @php
                $currentDateTime = \Carbon\Carbon::now();
                $jadwalDateTime = \Carbon\Carbon::parse($jadwal->tanggal_jadwal . ' ' . $jadwal->waktu_selesai);
                $isSelesai = $jadwalDateTime < $currentDateTime;
                $isConflict = in_array($jadwal->id, $conflicts ?? []);
            @endphp
            <tr
                class="schedule-item group {{ $isConflict ? 'ui-danger-soft/50 hover:bg-rose-100/50' : 'hover:bg-surface-muted/80' }} transition-all">
                <td class="px-6 py-4 text-center text-sm font-normal text-foreground-muted tabular-nums"> {{ $index + 1 }}
                </td>
                <td class="px-6 py-4">
                    <p class="text-sm {{ $isConflict ? 'text-danger' : 'text-foreground' }} tracking-tight">
                        {{ Str::ucfirst($jadwal->mata_kuliah ?? '-') }}
                        @if($isConflict)
                            <span
                                class="inline-flex items-center justify-center w-5 h-5 ml-2 rounded-full bg-rose-100 text-danger"
                                title="Bentrok Jadwal">
                                <span class="material-symbols-rounded text-[14px]">warning</span>
                            </span>
                        @endif
                    </p>
                </td>
                <td class="px-6 py-4"><span
                        class="text-sm font-normal {{ $isConflict ? 'text-danger' : 'text-foreground-muted' }}">{{ $jadwal->dosen?->name ?? '-' }}</span>
                </td>
                <td class="px-6 py-4"><span
                        class="text-sm font-normal {{ $isConflict ? 'text-danger' : 'text-foreground-muted' }}">{{ $jadwal->kelas ?? '-' }}
                        / Smt {{ $jadwal->semester ?? '-' }}</span></td>
                <td class="px-6 py-4"><span
                        class="text-sm font-normal tabular-nums {{ $isConflict ? 'text-danger' : 'text-foreground-muted' }}">{{ $jadwal->tanggal_jadwal ?? '-' }}</span>
                </td>
                <td class="px-6 py-4"><span
                        class="text-sm font-normal font-mono tabular-nums {{ $isConflict ? 'text-danger' : 'text-foreground-muted' }}">{{ $jadwal->waktu_mulai ?? '-' }}
                        – {{ $jadwal->waktu_selesai ?? '-' }}</span></td>
                <td class="px-6 py-4">
                    @if ($isConflict)
                        <x-ui.badge type="danger">BENTROK</x-ui.badge>
                    @elseif ($jadwal->status === 'selesai')
                        <x-ui.badge type="success">Selesai</x-ui.badge>
                    @elseif ($jadwal->status === 'berlangsung')
                        <x-ui.badge type="primary">Berlangsung</x-ui.badge>
                    @elseif ($jadwal->status === 'dibatalkan')
                        <x-ui.badge type="danger">Dibatalkan</x-ui.badge>
                    @else
                        <x-ui.badge type="warning">Dijadwalkan</x-ui.badge>
                    @endif
                </td>
                @if (Auth::user()->jabatan !== 'Mahasiswa')
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end">
                            <x-table.action-menu>
                                <a href="{{ route('updateJadwal', $jadwal->id) }}"
                                    class="w-full text-left px-3 py-2 text-xs font-semibold text-foreground-muted hover:bg-surface-muted transition-colors flex items-center min-h-[44px]">
                                    <x-atoms.icon name="settings" class="w-3.5 h-3.5 mr-2 text-foreground-muted/60" />
                                    Edit Jadwal
                                </a>
                                @if(in_array($jadwal->status, ['dijadwalkan', 'berlangsung']) && (Auth::id() == $jadwal->dosen_id || Auth::user()->jabatan == 'Admin Lab'))
                                    <form action="{{ route('completeEarly', $jadwal->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit"
                                            class="w-full text-left px-3 py-2 text-xs font-semibold text-[rgb(var(--color-primary))] hover:bg-surface-muted transition-colors flex items-center min-h-[44px]">
                                            <x-atoms.icon name="check" class="w-3.5 h-3.5 mr-2 text-[rgb(var(--color-primary))]" />
                                            Selesaikan
                                        </button>
                                    </form>
                                    <form action="{{ route('cancelJadwal', $jadwal->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit"
                                            class="w-full text-left px-3 py-2 text-xs font-semibold text-warning hover:ui-warning-soft transition-colors flex items-center min-h-[44px]">
                                            <x-atoms.icon name="x" class="w-3.5 h-3.5 mr-2 text-amber-400" />
                                            Batalkan
                                        </button>
                                    </form>
                                @endif
                                <div class="h-px bg-surface-muted my-1"></div>
                                <button type="button" data-modal-target="global-delete-modal"
                                    data-modal-toggle="global-delete-modal"
                                    @click="selectedName = '{{ addslashes($jadwal->mata_kuliah) }}'; deleteUrl = '{{ route('hapusJadwal', $jadwal->id) }}'"
                                    class="w-full text-left px-3 py-2 text-xs font-semibold text-danger hover:ui-danger-soft transition-colors flex items-center min-h-[44px]">
                                    <x-atoms.icon name="trash" class="w-3.5 h-3.5 mr-2 text-danger/80" />
                                    Hapus
                                </button>
                            </x-table.action-menu>
                        </div>
                    </td>
                @endif
            </tr>
        @endforeach
    </x-ui.table>
    <x-ui.pagination :paginator="$schedule" label="Total Jadwal" class="mt-6" />

    <div class="grid grid-cols-1 md:grid-cols-2 lg:hidden gap-4 flex-grow mt-6">
        @foreach ($schedule as $jadwal)
            @php
                $isConflict = in_array($jadwal->id, $conflicts ?? []);
            @endphp
            <div
                class="schedule-item ui-surface border {{ $isConflict ? 'border-danger-soft/80 ui-danger-soft/30 ring-1 ring-rose-500/20' : 'border-default/80' }} rounded-3xl p-5 shadow-sm hover:shadow-md transition-shadow relative">
                @if (Auth::user()->jabatan !== 'Mahasiswa')
                    <div class="absolute top-4 right-4">
                        <x-table.action-menu>
                            <a href="{{ route('updateJadwal', $jadwal->id) }}"
                                class="w-full text-left px-3 py-2 text-sm md:text-xs font-semibold text-foreground-muted hover:bg-surface-muted transition-colors flex items-center min-h-[44px]">
                                <x-atoms.icon name="settings" class="w-4 h-4 md:w-3.5 md:h-3.5 mr-2 text-foreground-muted/60" />
                                Edit Jadwal
                            </a>
                            @if(in_array($jadwal->status, ['dijadwalkan', 'berlangsung']) && (Auth::id() == $jadwal->dosen_id || Auth::user()->jabatan == 'Admin Lab'))
                                <form action="{{ route('completeEarly', $jadwal->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit"
                                        class="w-full text-left px-3 py-2 text-sm md:text-xs font-semibold text-[rgb(var(--color-primary))] hover:bg-surface-muted transition-colors flex items-center min-h-[44px]">
                                        <x-atoms.icon name="check"
                                            class="w-4 h-4 md:w-3.5 md:h-3.5 mr-2 text-[rgb(var(--color-primary))]" />
                                        Selesaikan
                                    </button>
                                </form>
                                <form action="{{ route('cancelJadwal', $jadwal->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit"
                                        class="w-full text-left px-3 py-2 text-sm md:text-xs font-semibold text-warning hover:ui-warning-soft transition-colors flex items-center min-h-[44px]">
                                        <x-atoms.icon name="x" class="w-4 h-4 md:w-3.5 md:h-3.5 mr-2 text-amber-400" />
                                        Batalkan
                                    </button>
                                </form>
                            @endif
                            <div class="h-px bg-surface-muted my-1"></div>
                            <button type="button" data-modal-target="global-delete-modal" data-modal-toggle="global-delete-modal"
                                @click="selectedName = '{{ addslashes($jadwal->mata_kuliah) }}'; deleteUrl = '{{ route('hapusJadwal', $jadwal->id) }}'"
                                class="w-full text-left px-3 py-2 text-sm md:text-xs font-semibold text-danger hover:ui-danger-soft transition-colors flex items-center min-h-[44px]">
                                <x-atoms.icon name="trash" class="w-4 h-4 md:w-3.5 md:h-3.5 mr-2 text-danger/80" />
                                Hapus
                            </button>
                        </x-table.action-menu>
                    </div>
                @endif

                <div class="pr-12 mb-4">
                    <h5 class="text-[15px] font-bold {{ $isConflict ? 'text-danger' : 'text-foreground' }} tracking-tight mb-1">
                        {{ Str::ucfirst($jadwal->mata_kuliah ?? '-') }}
                        @if($isConflict)
                            <span class="inline-block align-middle ml-1">
                                <x-atoms.icon name="exclamation-triangle" class="w-4 h-4 text-danger" />
                            </span>
                        @endif
                    </h5>
                    <p class="text-[13px] font-medium {{ $isConflict ? 'text-danger' : 'text-foreground-muted' }}">Kelas
                        {{ $jadwal->kelas ?? '-' }} · Semester {{ $jadwal->semester ?? '-' }}
                    </p>
                </div>

                <div class="space-y-3 pt-3 border-t {{ $isConflict ? 'border-danger-soft/50/80' : 'border-default/50' }}">
                    <div class="flex items-center justify-between gap-4">
                        <span
                            class="text-[12px] font-bold {{ $isConflict ? 'text-danger/80' : 'text-foreground-muted/60' }} uppercase tracking-wider">Submateri</span>
                        <span
                            class="text-[13px] font-semibold {{ $isConflict ? 'text-danger' : 'text-foreground-muted' }} text-right truncate">{{ $jadwal->submateri ?? '-' }}</span>
                    </div>
                    <div class="flex items-center justify-between gap-4">
                        <span
                            class="text-[12px] font-bold {{ $isConflict ? 'text-danger/80' : 'text-foreground-muted/60' }} uppercase tracking-wider">Dosen</span>
                        <span
                            class="text-[13px] font-semibold {{ $isConflict ? 'text-danger' : 'text-foreground-muted' }} text-right">{{ $jadwal->dosen?->name ?? '-' }}</span>
                    </div>
                    <div class="flex items-center justify-between gap-4">
                        <span
                            class="text-[12px] font-bold {{ $isConflict ? 'text-danger/80' : 'text-foreground-muted/60' }} uppercase tracking-wider">Tanggal</span>
                        <span
                            class="text-[13px] font-semibold {{ $isConflict ? 'text-danger' : 'text-foreground-muted' }} text-right">{{ $jadwal->tanggal_jadwal ?? '-' }}</span>
                    </div>
                    <div class="flex items-center justify-between gap-4">
                        <span
                            class="text-[12px] font-bold {{ $isConflict ? 'text-danger/80' : 'text-foreground-muted/60' }} uppercase tracking-wider">Waktu</span>
                        <span
                            class="text-[13px] font-semibold {{ $isConflict ? 'text-danger' : 'text-foreground-muted' }} text-right">{{ $jadwal->waktu_mulai ?? '-' }}
                            – {{ $jadwal->waktu_selesai ?? '-' }}</span>
                    </div>
                    <div class="flex items-center justify-between gap-4">
                        <span
                            class="text-[12px] font-bold {{ $isConflict ? 'text-danger/80' : 'text-foreground-muted/60' }} uppercase tracking-wider">Status</span>
                        @if ($isConflict)
                            <x-ui.badge type="danger">BENTROK</x-ui.badge>
                        @elseif ($jadwal->status === 'selesai')
                            <x-ui.badge type="success">Selesai</x-ui.badge>
                        @elseif ($jadwal->status === 'berlangsung')
                            <x-ui.badge type="primary">Berlangsung</x-ui.badge>
                        @elseif ($jadwal->status === 'dibatalkan')
                            <x-ui.badge type="danger">Dibatalkan</x-ui.badge>
                        @else
                            <x-ui.badge type="warning">Dijadwalkan</x-ui.badge>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <x-ui.pagination :paginator="$schedule" label="Total Jadwal" class="lg:hidden mt-6" />
@endif

@if (Auth::user()->jabatan !== 'Mahasiswa')
    <form :action="deleteUrl" method="POST">
        @csrf
        @method('DELETE')
        <div id="global-delete-modal" tabindex="-1"
            class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative p-4 w-full max-w-md max-h-full">
                <div class="relative ui-surface rounded-3xl shadow-lg border border-default">
                    <button type="button"
                        class="absolute top-4 end-4 text-foreground-muted/60 bg-transparent hover:bg-surface-muted hover:text-foreground rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center transition-colors"
                        data-modal-hide="global-delete-modal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                    <div class="p-6 md:p-8 text-center">
                        <div
                            class="w-14 h-14 rounded-2xl ui-danger-soft flex items-center justify-center mx-auto mb-5 ring-1 ring-rose-100">
                            <svg class="w-7 h-7 text-danger" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-foreground mb-2">Hapus Jadwal?</h3>
                        <p class="text-sm text-foreground-muted mb-6">Jadwal <strong x-text="selectedName"></strong> akan dihapus
                            permanen. Tindakan ini tidak dapat dibatalkan.</p>
                        <div class="flex flex-col-reverse sm:flex-row gap-3 justify-center">
                            <button data-modal-hide="global-delete-modal" type="button"
                                class="px-5 py-2.5 text-sm font-semibold text-foreground-muted bg-surface rounded-xl border border-default hover:bg-surface-muted transition-colors min-h-[44px]">
                                Batal
                            </button>
                            <button type="submit"
                                class="px-5 py-2.5 text-sm font-semibold text-white ui-danger-soft0 rounded-xl hover:bg-rose-600 transition-colors min-h-[44px] shadow-sm shadow-rose-500/20">
                                Ya, Hapus
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endif