@props([
    'title',
    'description',
    'icon' => 'cube',
    'action' => null
])

<div {{ $attributes->merge(['class' => 'relative flex flex-col items-center justify-center p-10 text-center ui-surface rounded-3xl shadow-sm border border-default overflow-hidden']) }}>
    
    {{-- Decorative Background Glow --}}
    <div class="absolute inset-0 bg-gradient-to-b from-primary/5 to-transparent opacity-50 pointer-events-none"></div>

    {{-- Icon Container --}}
    <div class="relative w-16 h-16 rounded-2xl flex items-center justify-center mb-5
                bg-surface-muted shadow-inner shadow-black/5 ring-1 ring-default">
        {{-- Inner subtle gradient for icon pop --}}
        <div class="absolute inset-0 rounded-2xl bg-gradient-to-br from-primary/10 to-transparent"></div>
        <x-atoms.icon :name="$icon" class="w-8 h-8 text-foreground-muted relative z-10" />
    </div>

    {{-- Typography --}}
    <h3 class="text-base font-bold text-foreground leading-tight tracking-tight mb-1.5">{{ $title }}</h3>
    <p class="text-[13px] text-foreground-muted font-medium max-w-[280px] leading-relaxed">{{ $description }}</p>
    
    {{-- Action Slot --}}
    @if($action)
        <div class="mt-6 relative z-10">
            {{ $action }}
        </div>
    @endif
</div>

