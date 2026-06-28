<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center rounded-xl border ui-surface px-4 py-2 text-sm font-semibold text-foreground shadow-sm transition-colors hover:bg-surface-muted hover:text-foreground focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 disabled:opacity-50']) }}>
    {{ $slot }}
</button>
