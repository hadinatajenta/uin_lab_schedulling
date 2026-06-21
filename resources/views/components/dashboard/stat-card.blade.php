@props([
    'label',
    'value',
    'icon',
    'type' => 'emerald'
])

@php
    $typeClasses = match($type) {
        'emerald' => [
            'iconBg' => 'bg-emerald-50 text-emerald-600',
            'border' => 'hover:border-emerald-200/80',
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
            'iconBg' => 'bg-emerald-50 text-emerald-600',
            'border' => 'hover:border-emerald-200/80',
        ]
    };
@endphp

<div class="bg-white rounded-2xl border border-zinc-200/80 p-5 shadow-sm transition-all duration-200 hover:shadow-md/50 {{ $typeClasses['border'] }} flex items-center space-x-4">
    <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0 shadow-sm ring-1 ring-black/5 {{ $typeClasses['iconBg'] }}">
        <x-atoms.icon :name="$icon" class="w-6 h-6" />
    </div>
    <div>
        <p class="text-xs font-semibold uppercase tracking-wider text-zinc-400">{{ $label }}</p>
        <h3 class="text-2xl font-bold text-zinc-900 mt-1.5 leading-none tracking-tight">{{ $value }}</h3>
    </div>
</div>
