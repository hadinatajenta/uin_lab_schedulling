<div x-data="{
        show: false,
        message: '',
        type: 'success',
        init() {
            @if(session('success'))
                this.message = '{{ addslashes(session('success')) }}';
                this.type = 'success';
                this.show = true;
            @elseif(session('error'))
                this.message = '{{ addslashes(session('error')) }}';
                this.type = 'error';
                this.show = true;
            @elseif(session('alert'))
                this.message = '{{ addslashes(session('alert')) }}';
                this.type = 'alert';
                this.show = true;
            @elseif($errors->any())
                this.message = '{{ addslashes($errors->first()) }}';
                this.type = 'error';
                this.show = true;
            @endif

            if(this.show) {
                setTimeout(() => { this.show = false; }, 5000);
            }
        }
    }"
    @toast.window="
        message = $event.detail.message;
        type = $event.detail.type || 'success';
        show = true;
        setTimeout(() => { show = false; }, 5000);
    "
    x-show="show"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95 sm:translate-x-4"
    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100 sm:translate-x-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 sm:scale-100"
    x-transition:leave-end="opacity-0 sm:scale-95 sm:translate-x-4"
    class="fixed top-6 right-6 z-[100] flex w-full max-w-sm"
    style="display: none;"
>
    <!-- Toast Content -->
    <div class="w-full flex items-start p-4 bg-surface rounded-2xl shadow-2xl border border-default/50 ring-1 ring-ring/5">
        <div class="flex-shrink-0">
            <!-- Icons based on type -->
            <template x-if="type === 'success'">
                <div class="w-8 h-8 rounded-full ui-primary-soft flex items-center justify-center ring-4 ring-primary-soft/50">
                    <svg class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
            </template>
            <template x-if="type === 'error'">
                <div class="w-8 h-8 rounded-full ui-danger-soft flex items-center justify-center ring-4 ring-danger-soft/50">
                    <svg class="w-4 h-4 text-danger" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
            </template>
            <template x-if="type === 'alert'">
                <div class="w-8 h-8 rounded-full ui-warning-soft flex items-center justify-center ring-4 ring-warning-soft/50">
                    <svg class="w-4 h-4 text-warning" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
            </template>
        </div>
        <div class="ml-3 w-0 flex-1 pt-0.5">
            <p class="text-[13px] font-bold text-foreground" x-text="type === 'success' ? 'Berhasil!' : (type === 'error' ? 'Terjadi Kesalahan' : 'Perhatian')"></p>
            <p class="mt-1 text-xs font-medium text-foreground-muted leading-relaxed" x-text="message"></p>
        </div>
        <div class="ml-4 flex flex-shrink-0">
            <button @click="show = false" type="button" class="inline-flex rounded-md bg-surface text-foreground-muted hover:text-foreground focus:outline-none transition-colors">
                <span class="sr-only">Close</span>
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>
</div>
