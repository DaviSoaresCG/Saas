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

    {{-- Barra de Busca Responsiva --}}
    <div class="mb-6">
        <form action="{{ tenant_route('products.index') }}" method="GET" class="relative flex items-center gap-2">
            @if (!empty($selectedGrupo))
                <input type="hidden" name="grupo" value="{{ $selectedGrupo }}">
            @endif

            <div class="relative flex-1">
                <span class="absolute inset-y-0 left-3.5 flex items-center pointer-events-none text-[var(--text-muted)]">
                    <i data-lucide="search" class="h-4 w-4"></i>
                </span>
                <input type="text" name="search" value="{{ $search ?? '' }}"
                    placeholder="Buscar produtos por nome, SKU ou descrição..."
                    class="w-full pl-10 pr-10 py-3 rounded-2xl bg-[var(--bg-card)] border border-[var(--color-primary)]/20 text-sm text-[var(--text-base)] placeholder-[var(--text-muted)] outline-none focus:border-[var(--color-primary)] focus:ring-2 focus:ring-[var(--color-primary)]/20 shadow-sm transition-all">
                @if (!empty($search))
                    <a href="{{ request()->fullUrlWithQuery(['search' => null]) }}"
                        class="absolute inset-y-0 right-3.5 flex items-center text-[var(--text-muted)] hover:text-red-500 transition-colors"
                        title="Limpar pesquisa">
                        <i data-lucide="x" class="h-4 w-4"></i>
                    </a>
                @endif
            </div>

            <button type="submit"
                class="inline-flex items-center justify-center gap-1.5 rounded-2xl bg-[var(--color-primary)] hover:opacity-90 px-4 sm:px-5 py-3 text-sm font-bold text-[var(--text-on-primary)] shadow-md shadow-[var(--color-primary)]/20 transition-all cursor-pointer shrink-0 active:scale-95">
                <i data-lucide="search" class="h-4 w-4"></i>
                <span class="hidden sm:inline">Buscar</span>
            </button>
        </form>

        @if (!empty($search))
            <div class="mt-3 flex flex-wrap items-center gap-2 text-xs text-[var(--text-muted)]">
                <span>Resultados para:</span>
                <span class="inline-flex items-center gap-1 rounded-xl bg-[var(--color-primary)]/10 border border-[var(--color-primary)]/20 px-2.5 py-1 font-bold text-[var(--text-base)]">
                    "{{ $search }}"
                    <a href="{{ request()->fullUrlWithQuery(['search' => null]) }}" class="text-[var(--text-muted)] hover:text-red-500 transition-colors">
                        <i data-lucide="x" class="h-3 w-3"></i>
                    </a>
                </span>
                <span class="text-[var(--text-muted)]">({{ $products->count() }} {{ $products->count() === 1 ? 'produto encontrado' : 'produtos encontrados' }})</span>
            </div>
        @endif
    </div>

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
                <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full bg-[var(--color-primary)]/10 text-[var(--text-base)] border border-[var(--color-primary)]/20">
                    {{ $products->count() }}
                </span>
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
            @if (!empty($search))
                <p class="text-xs text-[var(--text-muted)] max-w-xs">Não encontramos nenhum item correspondente a "{{ $search }}".</p>
                <div class="mt-4 flex flex-wrap items-center justify-center gap-2">
                    <a href="{{ request()->fullUrlWithQuery(['search' => null]) }}"
                        class="inline-flex items-center gap-1.5 rounded-xl bg-[var(--color-primary)] px-4 py-2 text-xs font-bold text-[var(--text-on-primary)] shadow-sm hover:opacity-90 transition-all">
                        <i data-lucide="x" class="h-3.5 w-3.5"></i>
                        Limpar pesquisa
                    </a>
                </div>
            @else
                <p class="text-xs text-[var(--text-muted)] max-w-xs">Não há produtos cadastrados neste grupo até o momento.</p>
                @if ($grupoAtual)
                    <a href="{{ request()->fullUrlWithQuery(['grupo' => null]) }}"
                        class="mt-4 inline-flex items-center gap-1.5 text-xs font-bold text-[var(--color-primary)] hover:underline">
                        <i data-lucide="arrow-left" class="h-3.5 w-3.5"></i>
                        Ver todos os produtos
                    </a>
                @endif
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
