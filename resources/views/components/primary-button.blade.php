<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-[var(--color-primary)] text-[var(--text-on-primary)] rounded cursor-pointer']) }}>
    {{ $slot }}
</button>
