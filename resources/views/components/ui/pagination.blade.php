@props(['paginator', 'label' => 'Total Data'])

@php
    $currentPage = $paginator->currentPage();
    $lastPage = $paginator->lastPage();
    $windowStart = max(1, $currentPage - 2);
    $windowEnd = min($lastPage, $currentPage + 2);

    if ($windowEnd - $windowStart < 4) {
        $windowStart = max(1, $windowEnd - 4);
        $windowEnd = min($lastPage, $windowStart + 4);
    }
@endphp

<div {{ $attributes->merge(['class' => 'px-5 py-4 ui-surface shadow-sm rounded-3xl']) }}>
    <div class="flex flex-row justify-between items-center gap-4">

        {{-- Kiri: Info Total --}}
        <p class="text-sm text-zinc-500 font-medium shrink-0">
            {{ $label }}:
            <span class="font-bold text-zinc-800">{{ $paginator->total() }}</span>
        </p>

        <div class="flex items-center gap-2 overflow-x-auto pb-1 min-w-0 max-w-full">
            @if ($paginator->onFirstPage())
                <span
                    class="inline-flex items-center justify-center w-9 h-9 rounded-xl text-zinc-300 cursor-not-allowed shrink-0">
                    <span class="material-symbols-rounded text-[20px]">chevron_left</span>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}"
                    class="inline-flex items-center justify-center w-9 h-9 rounded-xl text-zinc-500 hover:bg-zinc-100 transition-colors shrink-0">
                    <span class="material-symbols-rounded text-[20px]">chevron_left</span>
                </a>
            @endif

            @if ($windowStart > 1)
                <a href="{{ $paginator->url(1) }}"
                    class="inline-flex items-center justify-center w-9 h-9 rounded-xl text-zinc-600 text-xs font-semibold border border-zinc-200 hover:bg-zinc-50 transition-colors shrink-0">1</a>
                @if ($windowStart > 2)
                    <span class="inline-flex items-center justify-center w-9 h-9 rounded-xl text-zinc-400 shrink-0">…</span>
                @endif
            @endif

            @for ($page = $windowStart; $page <= $windowEnd; $page++)
                @if ($page == $currentPage)
                    <span
                        class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-emerald-50 text-emerald-700 text-xs font-bold ring-1 ring-emerald-200 shrink-0">{{ $page }}</span>
                @else
                    <a href="{{ $paginator->url($page) }}"
                        class="inline-flex items-center justify-center w-9 h-9 rounded-xl text-zinc-600 text-xs font-semibold border border-zinc-200 hover:bg-zinc-50 transition-colors shrink-0">{{ $page }}</a>
                @endif
            @endfor

            @if ($windowEnd < $lastPage)
                @if ($windowEnd < $lastPage - 1)
                    <span class="inline-flex items-center justify-center w-9 h-9 rounded-xl text-zinc-400 shrink-0">…</span>
                @endif
                <a href="{{ $paginator->url($lastPage) }}"
                    class="inline-flex items-center justify-center w-9 h-9 rounded-xl text-zinc-600 text-xs font-semibold border border-zinc-200 hover:bg-zinc-50 transition-colors shrink-0">{{ $lastPage }}</a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}"
                    class="inline-flex items-center justify-center w-9 h-9 rounded-xl text-zinc-500 hover:bg-zinc-100 transition-colors shrink-0">
                    <span class="material-symbols-rounded text-[20px]">chevron_right</span>
                </a>
            @else
                <span
                    class="inline-flex items-center justify-center w-9 h-9 rounded-xl text-zinc-300 cursor-not-allowed shrink-0">
                    <span class="material-symbols-rounded text-[20px]">chevron_right</span>
                </span>
            @endif
        </div>

        <div class="flex items-center gap-2 shrink-0">
            <span class="text-xs font-semibold text-zinc-500">Show per Page:</span>
            <select
                onchange="const params = new URLSearchParams(window.location.search); params.set('per_page', this.value); params.delete('page'); window.location.search = params.toString();"
                class="bg-zinc-50 border border-zinc-200 text-zinc-800 text-xs font-semibold rounded-xl py-1.5 pl-2.5 pr-7 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 appearance-none cursor-pointer"
                style="background-image: url('data:image/svg+xml,%3Csvg xmlns=%27http://www.w3.org/2000/svg%27 width=%2716%27 height=%2716%27 viewBox=%270 0 24 24%27 fill=%27none%27 stroke=%27%2371717a%27 stroke-width=%272%27%3E%3Cpath d=%27M6 9l6 6 6-6%27/%3E%3C/svg%3E'); background-repeat: no-repeat; background-position: right 0.35rem center; background-size: 1rem;">
                @foreach ([5, 10, 25, 50] as $size)
                    <option value="{{ $size }}" {{ request('per_page', $paginator->perPage()) == $size ? 'selected' : '' }}>
                        {{ $size }}
                    </option>
                @endforeach
            </select>

        </div>
    </div>