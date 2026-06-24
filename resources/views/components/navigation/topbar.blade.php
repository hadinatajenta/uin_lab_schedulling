<div class="lg:hidden sticky top-0 z-30 flex items-center justify-between p-4 bg-[rgb(var(--color-surface)_/_0.8)] backdrop-blur-md border-b border-[rgb(var(--color-border))] shadow-sm">
    <div class="flex items-center space-x-3">
        <img src="{{ asset('images/logo-uin.svg') }}" alt="Logo UIN" class="w-8 h-8 shrink-0 object-contain" />
        <h2 class="text-[17px] font-bold text-zinc-900 tracking-tight">Lab UIN</h2>
    </div>
    
    <button 
        @click="$store.sidebar.isMobileOpen = true" 
        type="button" 
        class="inline-flex items-center justify-center p-2 rounded-xl ui-text-muted hover:text-zinc-900 hover:bg-zinc-100 focus:outline-none focus:ring-2 focus:ring-[rgb(var(--color-primary))] transition-colors"
        aria-expanded="false"
    >
        <span class="sr-only">Open main menu</span>
        <span class="material-symbols-rounded text-[24px]">menu</span>
    </button>
</div>
