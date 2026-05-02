<div class="relative group/field">
    <label class="text-[var(--text-base)] mb-2 block" for="{{ $name }}">{{ $label }}</label>
    <div
        class="absolute left-0 top-12 flex items-center pointer-events-none pl-3 text-[var(--text-base)] opacity-60 transition-colors group-focus-within/field:opacity-100 group-focus-within/field:text-[var(--color-primary)] transition-all ease-in">
        <i data-lucide="{{ $icon }}" class="h-4 w-4"></i>
    </div>

    <input type="{{ $type }}" name="{{ $name }}" value="{{ old($name, $value) }}"
        class="w-full rounded-2xl border border-[var(--color-primary)]/20 bg-[var(--bg-page)]/50 pl-9 pr-4 py-3 text-sm text-[var(--text-base)] placeholder-[var(--text-muted)]/70 outline-none transition-all focus:border-[var(--color-primary)]/50 focus:ring-4 focus:ring-[var(--color-primary)]/10 shadow-inner"
        placeholder="{{ $placeholder }}" required>

    @error($name)
        <p class="mt-2 text-sm font-semibold text-red-500">{{ $message }}</p>
    @enderror
</div>