@props([
    'title',
    'description',
    'icon',
    'href'
])

<a href="{{ $href }}" class="group block bg-white rounded-2xl border border-zinc-200/80 p-5 shadow-sm transition-all duration-200 hover:shadow-md/50 hover:border-emerald-200/80">
    <div class="flex items-start justify-between">
        <div class="w-10 h-10 rounded-xl bg-zinc-50 group-hover:bg-emerald-50 text-zinc-500 group-hover:text-emerald-600 flex items-center justify-center shrink-0 shadow-sm ring-1 ring-black/5 transition-colors">
            <x-atoms.icon :name="$icon" class="w-5 h-5" />
        </div>
        <div class="text-zinc-400 group-hover:text-emerald-600 transition-colors transform translate-x-0 group-hover:translate-x-1 duration-200">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        </div>
    </div>
    <div class="mt-4">
        <h4 class="text-sm font-bold text-zinc-900 leading-tight">{{ $title }}</h4>
        <p class="text-[11px] font-medium text-zinc-500 mt-1 leading-normal">{{ $description }}</p>
    </div>
</a>
