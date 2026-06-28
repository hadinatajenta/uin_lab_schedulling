<div class="lg:hidden sticky top-0 z-30 flex items-center justify-between p-4 bg-surface/80 backdrop-blur-md border-b border-default shadow-sm">
    <div class="flex items-center space-x-3">
        <img src="{{ asset('images/logo-uin.svg') }}" alt="Logo UIN" class="w-8 h-8 shrink-0 object-contain" />
        <h2 class="text-[17px] font-bold text-foreground tracking-tight">Lab UIN</h2>
    </div>
    
    <button 
        @click="$store.sidebar.isMobileOpen = true" 
        type="button" 
        class="inline-flex items-center justify-center p-2 rounded-xl ui-text-muted hover:text-foreground hover:bg-surface-muted focus:outline-none focus:ring-2 focus:ring-primary transition-colors"
        aria-expanded="false"
    >
        <span class="sr-only">Open main menu</span>
        <span class="material-symbols-rounded text-[24px]">menu</span>
    </button>
</div>
