@props(['title' => ''])

<div class="px-2 pb-1.5 mb-1.5 border-b border-default/50">
    @if($title)
        <p class="text-xs font-semibold text-foreground-muted mb-1 mt-1">{{ $title }}</p>
    @endif
    {{ $slot }}
</div>
