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
            'iconBg' => 'ui-danger-soft',
            'border' => 'hover:border-danger-soft/80',
        ],
        'zinc' => [
            'iconBg' => 'bg-surface-muted text-foreground-muted',
            'border' => 'hover:border-default',
        ],
        default => [
            'iconBg' => 'ui-primary-soft text-[rgb(var(--color-primary))]',
            'border' => 'hover:border-[rgb(var(--color-primary-soft))]',
        ]
    };
@endphp

<div class="ui-surface rounded-2xl border border-default/80 p-5 shadow-sm transition-all duration-200 hover:shadow-md/50 {{ $typeClasses['border'] }} flex items-center space-x-4">
    <div class="stat-card-icon w-12 h-12 rounded-xl flex items-center justify-center shrink-0 shadow-sm ring-1 ring-ring/5 {{ $typeClasses['iconBg'] }}">
        <x-atoms.icon :name="$icon" class="w-6 h-6" />
    </div>
    <div>
        <p class="text-xs font-semibold uppercase tracking-wider text-foreground-muted">{{ $label }}</p>
        <h3 class="text-2xl font-bold text-foreground mt-1.5 leading-none tracking-tight">{{ $value }}</h3>
    </div>
</div>
