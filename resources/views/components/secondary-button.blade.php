<button 
    x-data="{ loading: false }"
    x-init="$el.closest('form')?.addEventListener('submit', (e) => { if(!e.defaultPrevented) loading = true; })"
    :disabled="loading"
    {{ $attributes->merge(['type' => 'button', 'class' => 'relative inline-flex items-center justify-center rounded-xl bg-surface px-4 py-2 text-[13px] font-semibold text-foreground border border-default shadow-sm transition-all duration-200 hover:bg-surface-muted focus:outline-none focus:ring-2 focus:ring-primary/30 focus:ring-offset-2 active:scale-[0.98] disabled:opacity-70 disabled:cursor-not-allowed']) }}
>
    {{-- Spinner --}}
    <svg x-show="loading" x-cloak class="absolute left-1/2 top-1/2 -ml-2.5 -mt-2.5 animate-spin h-5 w-5 text-foreground-muted" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
    </svg>
    
    {{-- Content --}}
    <span :class="{ 'opacity-0': loading }" class="inline-flex items-center gap-1.5 transition-opacity duration-200">
        {{ $slot }}
    </span>
</button>
