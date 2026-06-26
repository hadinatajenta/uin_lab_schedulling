@props([
    'name' => '',
    'value' => '',
    'placeholder' => 'Pilih tanggal',
    'id' => null,
])

@php
    $id = $id ?? 'datepicker-' . Str::random(8);
@endphp

<div x-data="{
        value: '{{ $value }}',
        instance: null,
        init() {
            this.instance = flatpickr(this.$refs.input, {
                dateFormat: 'Y-m-d',
                defaultDate: this.value,
                locale: 'id',
                disableMobile: true, // ensure custom UI is used on mobile too
                onChange: (selectedDates, dateStr) => {
                    this.value = dateStr;
                    this.$refs.hiddenInput.value = dateStr;
                    // Trigger native change and input events for forms or Alpine x-model
                    this.$refs.hiddenInput.dispatchEvent(new Event('input', { bubbles: true }));
                    this.$refs.hiddenInput.dispatchEvent(new Event('change', { bubbles: true }));
                }
            });

            // Watch for external value changes (e.g. from parent component)
            $watch('value', value => {
                if (this.instance) {
                    this.instance.setDate(value, false);
                    this.$refs.hiddenInput.value = value;
                }
            });
        }
    }" 
    class="relative w-full"
    wire:ignore>
    
    <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
        <span class="material-symbols-rounded text-[18px] text-zinc-400">calendar_month</span>
    </div>
    
    <input type="text"
           x-ref="input"
           class="block w-full h-[42px] pl-[38px] pr-4 text-[13px] font-medium text-zinc-800 border border-zinc-200/80 rounded-xl bg-zinc-50/50 hover:bg-zinc-50 focus:bg-white focus:outline-none focus:ring-4 focus:ring-[rgb(var(--color-primary)_/_0.1)] focus:border-[rgb(var(--color-primary))] transition-all cursor-pointer placeholder:text-zinc-400 placeholder:font-normal shadow-sm" 
           placeholder="{{ $placeholder }}">

    {{-- Hidden input for actual form submission --}}
    <input type="hidden" name="{{ $name }}" id="{{ $id }}" x-ref="hiddenInput" value="{{ $value }}" {{ $attributes }}>
</div>
