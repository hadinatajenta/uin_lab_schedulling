@props([
    'label',
    'value',
    'icon',
    'type' => 'primary'
])

@php
    $typeClasses = match($type) {
        'primary' => [
            'iconBg' => 'ui-primary-soft text-[rgb(var(--color-primary))]',
            'border' => 'hover:border-[rgb(var(--color-primary-soft))]',
        ],
        'rose' => [
            'iconBg' => 'bg-rose-50 text-rose-600',
            'border' => 'hover:border-rose-200/80',
        ],
        'zinc' => [
            'iconBg' => 'bg-zinc-50 text-zinc-600',
            'border' => 'hover:border-zinc-300',
        ],
        default => [
            'iconBg' => 'ui-primary-soft text-[rgb(var(--color-primary))]',
            'border' => 'hover:border-[rgb(var(--color-primary-soft))]',
        ]
    };
@endphp

<div class="ui-surface rounded-2xl border border-zinc-200/80 p-5 shadow-sm transition-all duration-200 hover:shadow-md/50 {{ $typeClasses['border'] }} flex items-center space-x-4">
    <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0 shadow-sm ring-1 ring-black/5 {{ $typeClasses['iconBg'] }}">
        <x-atoms.icon :name="$icon" class="w-6 h-6" />
    </div>
    <div>
        <p class="text-xs font-semibold uppercase tracking-wider text-zinc-400">{{ $label }}</p>
        <h3 class="text-2xl font-bold text-zinc-900 mt-1.5 leading-none tracking-tight">{{ $value }}</h3>
    </div>
</div>
