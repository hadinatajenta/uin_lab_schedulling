<li class="mb-8 ms-6 last:mb-0">
    <span class="{{ $nodeClasses ?? 'absolute flex items-center justify-center w-7 h-7 bg-emerald-100 rounded-full -start-3.5 ring-4 ring-white' }}">
        <svg class="w-3 h-3 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
            <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
        </svg>
    </span>
    <h3 class="text-sm font-bold text-zinc-900 tracking-tight mb-1">
        {{ $agenda->mata_kuliah }} — Kelas {{ $agenda->kelas }} Smt {{ $agenda->semester }}
    </h3>
    <time class="block mb-2 text-xs font-semibold text-zinc-400">
        @if(isset($showDate) && $showDate)
            {{ \Carbon\Carbon::parse($agenda->tanggal_jadwal)->format('d M Y') }} • 
        @endif
        {{ \Carbon\Carbon::parse($agenda->waktu_mulai)->format('H:i') }} – {{ \Carbon\Carbon::parse($agenda->waktu_selesai)->format('H:i') }}
    </time>
    <p class="text-sm font-medium text-zinc-500 leading-relaxed">
        Submateri {{ $agenda->submateri ?? '-' }} · Dosen {{ $agenda->dosen?->name ?? 'Unknown' }}
    </p>
</li>
