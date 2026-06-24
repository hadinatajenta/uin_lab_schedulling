<div x-data="{ open: false }" class="static inline-block text-left">
    <button @click="open = !open" @click.outside="open = false"
        class="p-2 w-10 h-10 rounded-xl text-zinc-400 hover:text-zinc-700 hover:bg-zinc-100 focus:outline-none focus:ring-2 focus:ring-[rgb(var(--color-primary))] transition-all flex items-center justify-center lg:opacity-0 lg:group-hover:opacity-100 lg:focus:opacity-100">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
            <path
                d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z" />
        </svg>
    </button>

    <div x-show="open" x-cloak
        class="absolute right-0 mt-2 w-48 rounded-2xl bg-white border border-zinc-200 shadow-lg shadow-zinc-900/5 py-2 z-50 text-left">
        {{ $slot }}
    </div>
</div>
