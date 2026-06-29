@props(['insights'])

@if($insights['totalInsights'] > 0)
<div class="mb-10">
    <h2 class="text-[13px] font-bold text-danger uppercase tracking-wider mb-4 flex items-center gap-2">
        <x-atoms.icon name="warning" class="w-4 h-4" />
        Needs Attention
    </h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        @if($insights['brokenEquipments'] > 0)
            <div class="ui-danger-soft border border-danger-soft/50 rounded-2xl p-5 flex items-center justify-between shadow-sm">
                <div>
                    <p class="text-xs font-semibold text-danger uppercase tracking-wide">Alat Rusak</p>
                    <p class="text-2xl font-bold text-danger mt-1">{{ $insights['brokenEquipments'] }}</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-white/50 flex items-center justify-center text-danger">
                    <x-atoms.icon name="cube" class="w-5 h-5" />
                </div>
            </div>
        @endif
        @if($insights['lowMaterials'] > 0)
            <div class="ui-warning-soft border border-warning/30 rounded-2xl p-5 flex items-center justify-between shadow-sm">
                <div>
                    <p class="text-xs font-semibold text-warning uppercase tracking-wide">Stok Bahan Kritis</p>
                    <p class="text-2xl font-bold text-warning mt-1">{{ $insights['lowMaterials'] }}</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-white/50 flex items-center justify-center text-warning">
                    <x-atoms.icon name="beaker" class="w-5 h-5" />
                </div>
            </div>
        @endif
        {{-- Tempat untuk Peminjaman Terlambat & Menunggu Persetujuan --}}
    </div>
</div>
@endif
