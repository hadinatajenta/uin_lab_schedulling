@props([
    'title',
    'description',
    'icon' => 'users'
])

<div class="flex flex-col items-center justify-center p-8 text-center bg-white border border-dashed border-zinc-200 rounded-2xl shadow-sm">
    <div class="w-12 h-12 rounded-xl bg-zinc-50 flex items-center justify-center text-zinc-400 mb-4 ring-1 ring-zinc-100">
        <x-atoms.icon :name="$icon" class="w-6 h-6" />
    </div>
    <h3 class="text-[15px] font-bold text-zinc-900 leading-none tracking-tight">{{ $title }}</h3>
    <p class="text-xs text-zinc-500 font-medium mt-1.5 max-w-sm leading-relaxed">{{ $description }}</p>
    @if(isset($action) && $action->isNotEmpty())
        <div class="mt-5">
            {{ $action }}
        </div>
    @endif
</div>
