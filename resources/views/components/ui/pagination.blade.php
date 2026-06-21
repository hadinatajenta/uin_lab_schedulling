@props(['paginator', 'label' => 'Total Data'])

@if ($paginator->hasPages())
    <div {{ $attributes->merge(['class' => 'px-5 py-4 bg-white border border-zinc-200/80 shadow-sm rounded-2xl']) }}>
        <div class="flex flex-col lg:flex-row items-center justify-between gap-4">

            {{-- Kiri: Info Total --}}
            <p class="text-sm text-zinc-500 font-medium shrink-0 order-2 lg:order-1">
                {{ $label }}:
                <span class="font-bold text-zinc-800">{{ $paginator->total() }}</span>
            </p>

            {{-- Tengah: Kontrol Halaman & Sliding Window --}}
            <div class="flex items-center gap-1 order-1 lg:order-2">
                {{-- Prev --}}
                @if ($paginator->onFirstPage())
                    <span class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-zinc-300 cursor-not-allowed">
                        <span class="material-symbols-rounded text-[20px]">chevron_left</span>
                    </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}"
                        class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-zinc-500 hover:bg-zinc-100 transition-colors">
                        <span class="material-symbols-rounded text-[20px]">chevron_left</span>
                    </a>
                @endif

                {{-- Page Numbers --}}
                @foreach ($paginator->getUrlRange(1, $paginator->lastPage()) as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-indigo-50 text-indigo-700 text-xs font-bold ring-1 ring-indigo-200">
                            {{ $page }}
                        </span>
                    @else
                        <a href="{{ $url }}"
                            class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-zinc-600 text-xs font-semibold border border-zinc-200 hover:bg-zinc-50 transition-colors">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach

                {{-- Next --}}
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}"
                        class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-zinc-500 hover:bg-zinc-100 transition-colors">
                        <span class="material-symbols-rounded text-[20px]">chevron_right</span>
                    </a>
                @else
                    <span class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-zinc-300 cursor-not-allowed">
                        <span class="material-symbols-rounded text-[20px]">chevron_right</span>
                    </span>
                @endif
            </div>

            {{-- Kanan: Per Page Dropdown --}}
            <div class="flex items-center gap-2 shrink-0 order-3">
                <span class="text-xs font-semibold text-zinc-500">Show per Page:</span>
                <select
                    onchange="const url = new URL(window.location.href); url.searchParams.set('per_page', this.value); url.searchParams.delete('page'); window.location.href = url.toString();"
                    class="bg-zinc-50 border border-zinc-200 text-zinc-800 text-xs font-semibold rounded-lg py-1.5 pl-2.5 pr-7 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 appearance-none cursor-pointer"
                    style="background-image: url('data:image/svg+xml,%3Csvg xmlns=%27http://www.w3.org/2000/svg%27 width=%2716%27 height=%2716%27 viewBox=%270 0 24 24%27 fill=%27none%27 stroke=%27%2371717a%27 stroke-width=%272%27%3E%3Cpath d=%27M6 9l6 6 6-6%27/%3E%3C/svg%3E'); background-repeat: no-repeat; background-position: right 0.35rem center; background-size: 1rem;"
                >
                    @foreach ([5, 10, 25, 50] as $size)
                        <option value="{{ $size }}" {{ request('per_page', $paginator->perPage()) == $size ? 'selected' : '' }}>
                            {{ $size }}
                        </option>
                    @endforeach
                </select>
            </div>

        </div>
    </div>
@endif
