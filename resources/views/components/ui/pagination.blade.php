@props(['paginator'])

@if ($paginator->hasPages())
    <nav class="flex items-center justify-between border-t border-zinc-100 bg-white px-4 py-3 sm:px-6" aria-label="Pagination">
        <div class="hidden sm:block">
            <p class="text-xs text-zinc-500 font-medium">
                Menampilkan
                <span class="font-semibold text-zinc-900">{{ $paginator->firstItem() }}</span>
                sampai
                <span class="font-semibold text-zinc-900">{{ $paginator->lastItem() }}</span>
                dari
                <span class="font-semibold text-zinc-900">{{ $paginator->total() }}</span>
                hasil
            </p>
        </div>
        <div class="flex flex-1 justify-between sm:justify-end gap-2">
            @if ($paginator->onFirstPage())
                <span class="relative inline-flex justify-center items-center rounded-xl bg-zinc-50 px-4 py-2 text-sm md:text-xs font-semibold text-zinc-400 cursor-not-allowed border border-zinc-100 min-h-[44px]">
                    Sebelumnya
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="relative inline-flex justify-center items-center rounded-xl bg-white px-4 py-2 text-sm md:text-xs font-semibold text-zinc-700 hover:bg-zinc-50 border border-zinc-200 transition-colors min-h-[44px]">
                    Sebelumnya
                </a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="relative inline-flex justify-center items-center rounded-xl bg-white px-4 py-2 text-sm md:text-xs font-semibold text-zinc-700 hover:bg-zinc-50 border border-zinc-200 transition-colors min-h-[44px]">
                    Selanjutnya
                </a>
            @else
                <span class="relative inline-flex justify-center items-center rounded-xl bg-zinc-50 px-4 py-2 text-sm md:text-xs font-semibold text-zinc-400 cursor-not-allowed border border-zinc-100 min-h-[44px]">
                    Selanjutnya
                </span>
            @endif
        </div>
    </nav>
@endif
