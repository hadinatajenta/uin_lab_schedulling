@props(['type' => 'neutral'])

@php
    $classes = match($type) {
        'success' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/10',
        'danger' => 'bg-rose-50 text-rose-700 ring-rose-600/10',
        'indigo' => 'bg-indigo-50 text-indigo-700 ring-indigo-600/10',
        default => 'bg-zinc-50 text-zinc-700 ring-zinc-600/10',
    };
@endphp

<span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-semibold ring-1 ring-inset {{ $classes }}">
    {{ $slot }}
</span>
