@props(['title' => ''])

<div class="px-2 pb-1.5 mb-1.5 border-b border-zinc-100">
    @if($title)
        <p class="text-xs font-semibold text-zinc-500 mb-1 mt-1">{{ $title }}</p>
    @endif
    {{ $slot }}
</div>
