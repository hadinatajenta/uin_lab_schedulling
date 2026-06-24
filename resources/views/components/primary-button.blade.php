<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center rounded-xl ui-primary px-4 py-2 text-sm font-semibold shadow-sm transition-colors hover:opacity-95 focus:outline-none focus:ring-2 focus:ring-[rgb(var(--color-primary))] focus:ring-offset-2 active:opacity-90']) }}>
    {{ $slot }}
</button>
