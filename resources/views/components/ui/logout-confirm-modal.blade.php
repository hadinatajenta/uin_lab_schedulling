<div x-data="{ showLogoutModal: false }" @open-logout-modal.window="showLogoutModal = true" x-show="showLogoutModal"
    x-cloak class="fixed inset-0 z-[200] flex items-center justify-center p-6" style="display: none;"
    @keydown.escape.window="showLogoutModal = false">
    <div x-show="showLogoutModal" x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-150"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="showLogoutModal = false"></div>

    <div x-show="showLogoutModal" x-transition:enter="ease-out duration-250"
        x-transition:enter-start="opacity-0 scale-95 translate-y-2"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0" x-transition:leave="ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 translate-y-2"
        class="relative w-full max-w-[340px] ui-surface rounded-2xl border border-default shadow-2xl shadow-black/15 overflow-hidden"
        @click.stop>
        <div class="h-1 w-full bg-gradient-to-r from-danger via-rose-400 to-orange-400"></div>

        <div class="px-6 pt-6 pb-5 flex flex-col items-center text-center">

            <div class="w-14 h-14 rounded-2xl ui-danger-soft flex items-center justify-center mb-5
                        ring-4 ring-danger/10 shadow-sm">
                <span class="material-symbols-rounded text-danger text-[28px]">logout</span>
            </div>
            <h2 class="text-[17px] font-bold text-foreground tracking-tight leading-tight mb-2">
                Keluar dari Sistem?
            </h2>
            <p class="text-[13px] text-foreground-muted leading-relaxed max-w-[260px]">
                Anda akan mengakhiri sesi ini. Semua aktivitas yang belum tersimpan akan hilang.
            </p>
        </div>

        <div class="h-px mx-5 bg-default"></div>

        <div class="p-4 flex gap-2.5">
            <button @click="showLogoutModal = false" class="flex-1 px-4 py-2.5 rounded-xl border border-default text-[13px] font-semibold
                       text-foreground-muted hover:bg-surface-muted hover:text-foreground
                       transition-all duration-150 outline-none focus-visible:ring-2 focus-visible:ring-primary/30">
                Batal
            </button>

            <form method="POST" action="{{ route('logout') }}" class="flex-1">
                @csrf
                <button type="submit" class="w-full inline-flex items-center justify-center gap-1.5 px-4 py-2.5 rounded-xl
                           bg-danger text-white text-[13px] font-semibold
                           hover:bg-danger/90 active:scale-[0.98]
                           transition-all duration-150
                           shadow-sm shadow-danger/20
                           outline-none focus-visible:ring-2 focus-visible:ring-danger/50">
                    <span class="material-symbols-rounded text-[15px]">logout</span>
                    Ya, Keluar
                </button>
            </form>
        </div>
    </div>
</div>