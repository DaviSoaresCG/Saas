@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-[var(--color-primary)]  text-[var(--text-base)] border-2 border-[var(--color-primary)] p-2 rounded-md shadow-sm ']) }}>
