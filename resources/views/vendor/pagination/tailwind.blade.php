@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Paginação') }}" class="flex items-center justify-between gap-4 py-4">
        {{-- Mobile View --}}
        <div class="flex flex-1 items-center justify-between gap-2 sm:hidden">
            @if ($paginator->onFirstPage())
                <span class="inline-flex items-center gap-1 rounded-xl bg-[var(--bg-card)] border border-[var(--color-primary)]/10 px-3.5 py-2 text-xs font-semibold text-[var(--text-muted)] opacity-50 cursor-not-allowed">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    Anterior
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="inline-flex items-center gap-1 rounded-xl bg-[var(--bg-card)] border border-[var(--color-primary)]/20 px-3.5 py-2 text-xs font-semibold text-[var(--text-base)] hover:bg-[var(--color-primary)] hover:text-[var(--text-on-primary)] transition-all shadow-sm active:scale-95">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    Anterior
                </a>
            @endif

            <span class="text-xs font-semibold text-[var(--text-muted)]">
                <span class="text-[var(--text-base)] font-bold">{{ $paginator->currentPage() }}</span> / <span class="text-[var(--text-base)] font-bold">{{ $paginator->lastPage() }}</span>
            </span>

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="inline-flex items-center gap-1 rounded-xl bg-[var(--bg-card)] border border-[var(--color-primary)]/20 px-3.5 py-2 text-xs font-semibold text-[var(--text-base)] hover:bg-[var(--color-primary)] hover:text-[var(--text-on-primary)] transition-all shadow-sm active:scale-95">
                    Próxima
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            @else
                <span class="inline-flex items-center gap-1 rounded-xl bg-[var(--bg-card)] border border-[var(--color-primary)]/10 px-3.5 py-2 text-xs font-semibold text-[var(--text-muted)] opacity-50 cursor-not-allowed">
                    Próxima
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </span>
            @endif
        </div>

        {{-- Desktop View --}}
        <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between gap-4">
            <div>
                <p class="text-xs text-[var(--text-muted)]">
                    Exibindo
                    <span class="font-bold text-[var(--text-base)]">{{ $paginator->firstItem() }}</span>
                    a
                    <span class="font-bold text-[var(--text-base)]">{{ $paginator->lastItem() }}</span>
                    de
                    <span class="font-bold text-[var(--text-base)]">{{ $paginator->total() }}</span>
                    resultados
                </p>
            </div>

            <div>
                <span class="relative z-0 inline-flex items-center rounded-2xl p-1 bg-[var(--bg-card)] border border-[var(--color-primary)]/20 shadow-sm gap-1">
                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                        <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                            <span class="relative inline-flex items-center justify-center h-9 w-9 rounded-xl text-[var(--text-muted)] opacity-30 cursor-not-allowed" aria-hidden="true">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                            </span>
                        </span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="relative inline-flex items-center justify-center h-9 w-9 rounded-xl text-[var(--text-base)] hover:bg-[var(--color-primary)] hover:text-[var(--text-on-primary)] transition-all cursor-pointer" aria-label="{{ __('pagination.previous') }}">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        </a>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <span aria-disabled="true">
                                <span class="relative inline-flex items-center justify-center h-9 w-9 rounded-xl text-xs font-bold text-[var(--text-muted)]">{{ $element }}</span>
                            </span>
                        @endif

                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span aria-current="page">
                                        <span class="relative inline-flex items-center justify-center h-9 w-9 rounded-xl bg-[var(--color-primary)] text-xs font-bold text-[var(--text-on-primary)] shadow-sm scale-105 transition-all">{{ $page }}</span>
                                    </span>
                                @else
                                    <a href="{{ $url }}" class="relative inline-flex items-center justify-center h-9 w-9 rounded-xl text-xs font-medium text-[var(--text-base)] hover:bg-[var(--color-primary)]/10 hover:text-[var(--color-primary)] transition-all" aria-label="{{ __('Ir para página :page', ['page' => $page]) }}">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="relative inline-flex items-center justify-center h-9 w-9 rounded-xl text-[var(--text-base)] hover:bg-[var(--color-primary)] hover:text-[var(--text-on-primary)] transition-all cursor-pointer" aria-label="{{ __('pagination.next') }}">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    @else
                        <span aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                            <span class="relative inline-flex items-center justify-center h-9 w-9 rounded-xl text-[var(--text-muted)] opacity-30 cursor-not-allowed" aria-hidden="true">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </span>
                        </span>
                    @endif
                </span>
            </div>
        </div>
    </nav>
@endif
