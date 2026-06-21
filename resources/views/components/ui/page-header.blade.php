@props(['title', 'description' => null])

<div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-zinc-900">{{ $title }}</h1>
        @if($description)
            <p class="text-sm font-medium text-zinc-500 mt-1">{{ $description }}</p>
        @endif
    </div>
    @if(isset($slot) && $slot->isNotEmpty())
        <div class="flex items-center gap-3 shrink-0 w-full md:w-auto">
            {{ $slot }}
        </div>
    @endif
</div>
