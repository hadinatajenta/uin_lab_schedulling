@props(['schedules'])

<div class="ui-surface border border-default/80 rounded-3xl shadow-sm overflow-hidden h-full flex flex-col">
    <div class="p-5 border-b border-default/50 flex items-center justify-between">
        <h2 class="text-[13px] font-bold text-foreground uppercase tracking-wider">Upcoming Schedule</h2>
        <a href="{{ route('lab') }}" class="text-xs font-semibold text-primary hover:opacity-90">Lihat Semua</a>
    </div>
    <div class="flex-1 overflow-y-auto p-4">
        @if($schedules->isEmpty())
            <div class="h-full flex flex-col items-center justify-center py-10 text-center">
                <div class="w-12 h-12 rounded-full ui-surface-muted flex items-center justify-center mx-auto mb-3">
                    <x-atoms.icon name="calendar" class="w-6 h-6 text-foreground-muted/60" />
                </div>
                <h3 class="text-sm font-semibold text-foreground">Tidak ada jadwal</h3>
                <p class="text-xs text-foreground-muted mt-1">Belum ada jadwal penggunaan lab mendatang.</p>
            </div>
        @else
            <ul class="divide-y divide-default/50">
                @foreach($schedules as $jadwal)
                <li class="py-3 hover:bg-surface-muted transition-colors flex items-start space-x-4 rounded-xl px-2">
                    <div class="w-10 h-10 rounded-xl ui-primary-soft flex flex-col items-center justify-center shrink-0 border border-primary-soft/50">
                        <span class="text-[10px] font-bold text-primary leading-none mb-0.5 uppercase">{{ \Carbon\Carbon::parse($jadwal->tanggal_jadwal)->translatedFormat('M') }}</span>
                        <span class="text-sm font-bold text-primary leading-none">{{ \Carbon\Carbon::parse($jadwal->tanggal_jadwal)->format('d') }}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-foreground truncate">{{ $jadwal->mata_kuliah }}</p>
                        <div class="flex items-center text-xs text-foreground-muted mt-1 space-x-3">
                            <span class="flex items-center">
                                <x-atoms.icon name="calendar" class="w-3.5 h-3.5 mr-1 text-foreground-muted/60" />
                                {{ \Carbon\Carbon::parse($jadwal->waktu_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->waktu_selesai)->format('H:i') }}
                            </span>
                            <span class="flex items-center truncate">
                                <x-atoms.icon name="home" class="w-3.5 h-3.5 mr-1 text-foreground-muted/60" />
                                {{ $jadwal->nama_ruangan ?? 'Ruang Lab' }}
                            </span>
                        </div>
                    </div>
                </li>
                @endforeach
            </ul>
        @endif
    </div>
</div>
