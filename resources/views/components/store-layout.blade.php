@push('styles')
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
   
@endpush

@push('scripts')
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof lucide !== 'undefined') lucide.createIcons();
        });
    </script>

@endpush

<x-app-layout>
    <div class="min-h-[calc(100vh-4rem)] bg-[var(--bg-page)] font-sans antialiased ">
        <div class="pointer-events-none fixed inset-0 -z-10 overflow-hidden">
            <div class="absolute -top-24 -right-24 h-72 w-72 rounded-full bg-[var(--color-primary)]/10 blur-[90px]"></div>
            <div class="absolute bottom-0 -left-24 h-72 w-72 rounded-full bg-indigo-600/10 blur-[90px]"></div>
        </div>

        <header class="sticky top-0 z-20 bg-[var(--bg-page)] backdrop-blur-md">
            <div class="mx-auto h-16 flex items-center justify-between gap-4">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-[var(--color-primary)] shrink-0">
                        <i data-lucide="shopping-bag" class="h-5 w-5 text-[var(--text-on-primary)]"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs font-medium text-[var(--text-base)] uppercase tracking-wide truncate">{{ $storeName }}</p>
                        @if ($pageTitle)
                            <p class="text-sm font-bold text-[var(--text-base)] truncate">{{ $pageTitle }}</p>
                        @else
                            <p class="text-sm font-bold text-[var(--text-base)] truncate">Catálogo</p>
                        @endif
                    </div>
                </div>
                <nav class="flex items-center gap-2 sm:gap-3 shrink-0">
                    <a href="{{ route('dashboard') }}"
                        class="inline-flex items-center gap-1.5 rounded-lg px-3 py-2 text-sm font-medium text-[var(--text-on-primary)] bg-[var(--color-primary)] transition-colors shadow-lg shadow-[var(--color-primary)]/20">
                        <i data-lucide="layout-grid" class="h-4 w-4"></i>
                        <span class="hidden sm:inline">Dashboard</span>
                    </a>
                    <a href="{{ route('cart.index') }}"
                        class="inline-flex items-center gap-1.5 rounded-lg bg-[var(--color-primary)] px-3 py-2 text-sm text-[var(--text-on-primary)] shadow-lg shadow-[var(--color-primary)]/20 font-medium transition-colors">
                        <i data-lucide="shopping-cart" class="h-4 w-4"></i>
                        <span class="hidden sm:inline">Carrinho</span>
                    </a>
                </nav>
            </div>
        </header>
            
        {{ $slot }}
        
    </div>
</x-app-layout>
