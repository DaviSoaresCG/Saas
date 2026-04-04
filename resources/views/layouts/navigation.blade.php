<header class="sticky top-0 z-20 border-b border-slate-800 bg-slate-900/90 backdrop-blur-md">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between gap-4">
        <div class="flex items-center gap-3 min-w-0">
            <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-600 shrink-0">
                <i data-lucide="shopping-bag" class="h-5 w-5 text-white"></i>
            </div>
            <div class="min-w-0">
                <p class="text-xs font-medium text-blue-400 uppercase tracking-wide truncate">Nome da loja</p>
                <p class="text-sm font-bold text-white truncate">Catálogo</p>
            </div>
        </div>
        <nav class="flex items-center gap-2 sm:gap-3 shrink-0">
            <a href="{{ route('dashboard') }}"
                class="inline-flex items-center gap-1.5 rounded-lg px-3 py-2 text-sm font-medium text-slate-300 hover:text-white hover:bg-slate-800 transition-colors">
                <i data-lucide="layout-grid" class="h-4 w-4"></i>
                <span class="hidden sm:inline">Dashboard</span>
            </a>
            <a href="{{ route('cart.index') }}"
                class="inline-flex items-center gap-1.5 rounded-lg bg-blue-600 hover:bg-blue-700 px-3 py-2 text-sm font-bold text-white shadow-lg shadow-blue-600/20 transition-colors">
                <i data-lucide="shopping-cart" class="h-4 w-4"></i>
                <span class="hidden sm:inline">Carrinho</span>
            </a>
        </nav>
    </div>
</header>