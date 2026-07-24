<x-store-layout :slug="$user->slug">
    @if (session('success'))
        <div class="mb-6 rounded-xl border border-emerald-500/40 bg-emerald-800 px-4 py-3 text-sm text-white">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-6 rounded-xl border border-red-500/40 bg-red-800 px-4 py-3 text-sm text-white">
            {{ session('error') }}
        </div>
    @endif

    @if (isset($grupos) && $grupos->isNotEmpty())
        <div class="mb-6">
            <div class="flex items-center gap-2.5 overflow-x-auto pb-2 scrollbar-none">
                {{-- Chip "Todos" --}}
                <a href="{{ request()->fullUrlWithQuery(['grupo' => null]) }}"
                    class="inline-flex items-center gap-2 rounded-2xl px-4 py-2.5 text-xs font-bold transition-all shrink-0 border {{ empty($selectedGrupo) ? 'bg-[var(--color-primary)] text-[var(--text-on-primary)] border-[var(--color-primary)] shadow-md shadow-[var(--color-primary)]/25 scale-105' : 'bg-[var(--bg-card)] text-[var(--text-base)] border-[var(--color-primary)]/20 hover:border-[var(--color-primary)]/40 hover:bg-[var(--color-primary)]/5' }}">
                    <i data-lucide="grid" class="h-4 w-4"></i>
                    <span>Todos os produtos</span>
                </a>

                @foreach ($grupos as $grupo)
                    @php
                        $isActive = (string) $selectedGrupo === (string) $grupo->id;
                    @endphp
                    <a href="{{ request()->fullUrlWithQuery(['grupo' => $grupo->id]) }}"
                        class="inline-flex items-center gap-2.5 rounded-2xl px-4 py-2.5 text-xs font-bold transition-all shrink-0 border {{ $isActive ? 'bg-[var(--color-primary)] text-[var(--text-on-primary)] border-[var(--color-primary)] shadow-md shadow-[var(--color-primary)]/25 scale-105' : 'bg-[var(--bg-card)] text-[var(--text-base)] border-[var(--color-primary)]/20 hover:border-[var(--color-primary)]/40 hover:bg-[var(--color-primary)]/5' }}">
                        @if ($grupo->foto_path)
                            <img src="{{ $grupo->foto_url }}" class="h-5 w-5 rounded-lg object-cover" alt="{{ $grupo->nome }}">
                        @else
                            <i data-lucide="layers" class="h-4 w-4 opacity-70"></i>
                        @endif
                        <span>{{ $grupo->nome }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    @php
        $grupoAtual = !empty($selectedGrupo) && isset($grupos) ? $grupos->firstWhere('id', $selectedGrupo) : null;
    @endphp

    <div class="mb-8 flex flex-wrap items-center justify-between gap-4">
        <div>
            <h2 class="text-lg font-bold text-[var(--text-base)] flex items-center gap-2">
                @if ($grupoAtual)
                    <span>Grupo: {{ $grupoAtual->nome }}</span>
                @else
                    <span>Todos os produtos</span>
                @endif
            </h2>
            <p class="text-sm text-[var(--text-base)] mt-1">Toque em um item para ver detalhes.</p>
        </div>
    </div>

    @if ($products->isEmpty())
        <div class="flex flex-col items-center justify-center rounded-3xl border border-dashed border-[var(--color-primary)]/20 py-16 px-4 text-center bg-[var(--bg-card)]/50">
            <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-[var(--color-primary)]/10 text-[var(--color-primary)] mb-3">
                <i data-lucide="package-search" class="h-7 w-7"></i>
            </div>
            <h3 class="text-base font-bold text-[var(--text-base)] mb-1">Nenhum produto encontrado</h3>
            <p class="text-xs text-[var(--text-muted)] max-w-xs">Não há produtos cadastrados neste grupo até o momento.</p>
            @if ($grupoAtual)
                <a href="{{ request()->fullUrlWithQuery(['grupo' => null]) }}"
                    class="mt-4 inline-flex items-center gap-1.5 text-xs font-bold text-[var(--color-primary)] hover:underline">
                    <i data-lucide="arrow-left" class="h-3.5 w-3.5"></i>
                    Ver todos os produtos
                </a>
            @endif
        </div>
    @else
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            @foreach ($products as $product)
                <x-produto-card :produto="$product" :user="$user" />
            @endforeach
        </div>
    @endif
</x-store-layout>
