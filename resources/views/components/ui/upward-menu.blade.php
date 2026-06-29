<div x-show="open" x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0 scale-95 translate-y-3"
    x-transition:enter-end="opacity-100 scale-100 translate-y-0" x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100 scale-100 translate-y-0"
    x-transition:leave-end="opacity-0 scale-95 translate-y-3"
    class="absolute bottom-full left-0 right-0 mb-3 ui-surface rounded-2xl border border-default shadow-2xl shadow-black/10 z-50 origin-bottom overflow-hidden"
    style="display: none;" x-cloak>
    {{ $slot }}
</div>