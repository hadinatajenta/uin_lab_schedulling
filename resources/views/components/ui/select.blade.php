@props(['name', 'options' => [], 'value' => null, 'placeholder' => 'Pilih salah satu...'])

<div 
    x-data="{
        open: false,
        search: '',
        value: @js($value),
        options: @js($options), // Expected format: [{value: '1', label: 'Item 1'}]
        get filteredOptions() {
            if (this.search === '') return this.options;
            return this.options.filter(opt => opt.label.toLowerCase().includes(this.search.toLowerCase()));
        },
        get selectedLabel() {
            const selected = this.options.find(opt => opt.value == this.value);
            return selected ? selected.label : '{{ $placeholder }}';
        },
        selectOption(val) {
            this.value = val;
            this.open = false;
            this.search = '';
            // trigger change event for livewire/alpine forms if needed
            $el.dispatchEvent(new CustomEvent('change', { detail: val, bubbles: true }));
        }
    }"
    class="relative w-full"
    @click.outside="open = false"
>
    {{-- Hidden Input for form submission --}}
    <input type="hidden" name="{{ $name }}" :value="value">

    {{-- Trigger Button --}}
    <button 
        type="button"
        @click="open = !open"
        class="w-full flex items-center justify-between px-3.5 py-2.5 ui-surface border border-default rounded-xl text-left focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all duration-200 shadow-sm"
        :class="{ 'border-primary ring-2 ring-primary/30': open }"
    >
        <span 
            class="block truncate text-[13px] font-medium transition-colors"
            :class="value ? 'text-foreground' : 'text-foreground-muted'"
            x-text="selectedLabel"
        ></span>
        
        <span class="material-symbols-rounded text-foreground-muted/60 text-[18px] transition-transform duration-200"
              :class="open ? 'rotate-180' : ''">
            expand_more
        </span>
    </button>

    {{-- Dropdown Panel --}}
    <div 
        x-show="open" 
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 scale-95 translate-y-1"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 translate-y-1"
        class="absolute z-50 w-full mt-2 ui-surface border border-default rounded-xl shadow-lg shadow-black/5 overflow-hidden"
        style="display: none;"
        x-cloak
    >
        {{-- Search Input --}}
        <div class="p-2 border-b border-default bg-surface-muted/30">
            <div class="relative">
                <span class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none">
                    <span class="material-symbols-rounded text-[16px] text-foreground-muted/70">search</span>
                </span>
                <input 
                    type="text" 
                    x-model="search"
                    class="w-full pl-8 pr-3 py-1.5 text-[13px] ui-surface border-default rounded-lg focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all placeholder:text-foreground-muted/50"
                    placeholder="Cari..."
                    @keydown.escape.prevent="open = false"
                >
            </div>
        </div>

        {{-- Options List --}}
        <ul class="max-h-56 overflow-y-auto p-1 custom-scrollbar">
            <template x-for="option in filteredOptions" :key="option.value">
                <li 
                    @click="selectOption(option.value)"
                    class="flex items-center justify-between px-3 py-2 cursor-pointer rounded-lg text-[13px] font-medium transition-colors"
                    :class="value == option.value ? 'ui-primary-soft text-primary' : 'text-foreground hover:bg-surface-muted'"
                >
                    <span x-text="option.label"></span>
                    <span x-show="value == option.value" class="material-symbols-rounded text-[16px]">check</span>
                </li>
            </template>
            
            {{-- Empty State --}}
            <li x-show="filteredOptions.length === 0" class="px-3 py-4 text-center text-[13px] text-foreground-muted">
                Tidak ada hasil ditemukan.
            </li>
        </ul>
    </div>
</div>
