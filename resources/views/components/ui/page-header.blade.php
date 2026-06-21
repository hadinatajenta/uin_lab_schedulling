@props(['title', 'description' => null])

<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 rounded-3xl border ui-surface px-5 py-4 shadow-sm mb-6">
    <div>
        <p class="text-xs font-bold uppercase tracking-[0.18em] ui-primary-soft inline-flex rounded-full px-2 py-1">Section</p>
        <h1 class="mt-2 text-2xl font-bold tracking-tight text-zinc-900">{{ $title }}</h1>
        @if($description)
            <p class="text-sm leading-6 ui-text-muted mt-1">{{ $description }}</p>
        @endif
    </div>
    @if(isset($slot) && $slot->isNotEmpty())
        <div class="flex items-center gap-3 shrink-0 w-full md:w-auto">
            {{ $slot }}
        </div>
    @endif
</div>
