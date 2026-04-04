<header
    class="sticky top-0 z-[60] flex h-14 shrink-0 items-center justify-between gap-3 border-b border-slate-800 bg-slate-900/95 px-3 sm:px-4 backdrop-blur-md">
    <div class="flex min-w-0 flex-1 items-center gap-2 sm:gap-3">
        <button type="button"
            @click="adminMenuOpen = !adminMenuOpen"
            class="relative inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-600 bg-slate-800/80 text-slate-200 transition-colors hover:bg-slate-700 lg:hidden"
            :aria-expanded="adminMenuOpen"
            aria-controls="admin-sidebar"
            aria-label="Abrir ou fechar menu">
            <svg class="absolute h-5 w-5 transition-opacity" :class="adminMenuOpen ? 'opacity-0' : 'opacity-100'" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
            <svg class="absolute h-5 w-5 transition-opacity" :class="adminMenuOpen ? 'opacity-100' : 'opacity-0'" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
        <a href="{{ route('dashboard') }}" class="flex min-w-0 items-center gap-2 sm:gap-3">
            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-blue-600 shadow-md shadow-blue-600/30">
                <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                </svg>
            </span>
            <span class="hidden min-w-0 sm:block">
                <span class="block text-[10px] font-semibold uppercase tracking-wider text-blue-400">Painel</span>
                <span class="block truncate text-sm font-bold text-white">ZapCatalogo</span>
            </span>
        </a>
    </div>

    <div class="flex shrink-0 items-center gap-1.5 sm:gap-2">
        <a href="{{ route('products.index') }}"
            class="hidden rounded-lg px-2.5 py-2 text-xs font-semibold text-slate-400 hover:bg-slate-800 hover:text-white md:inline-flex">
            Catálogo
        </a>
        <a href="{{ route('cart.index') }}"
            class="hidden rounded-lg px-2.5 py-2 text-xs font-semibold text-slate-400 hover:bg-slate-800 hover:text-white sm:inline-flex">
            Carrinho
        </a>

        @auth
            <div class="relative" x-data="{ userOpen: false }" @click.outside="userOpen = false">
                <button type="button" @click="userOpen = !userOpen"
                    class="inline-flex max-w-[10rem] items-center gap-2 rounded-xl border border-slate-600 bg-slate-800/80 px-2.5 py-2 text-left text-sm font-medium text-slate-200 hover:bg-slate-700 sm:max-w-xs">
                    <span class="truncate">{{ Auth::user()->name }}</span>
                    <svg class="h-4 w-4 shrink-0 text-slate-500" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                        <path fill-rule="evenodd"
                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                </button>
                <div x-show="userOpen" x-cloak x-transition
                    class="absolute right-0 z-[70] mt-2 w-52 overflow-hidden rounded-xl border border-slate-700 bg-slate-800 py-1 shadow-xl shadow-black/40">
                    <a href="{{ route('profile.edit') }}"
                        class="block px-4 py-2.5 text-sm font-medium text-slate-200 hover:bg-slate-700/80">Perfil</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full px-4 py-2.5 text-left text-sm font-medium text-red-300 hover:bg-slate-700/80">
                            Sair
                        </button>
                    </form>
                </div>
            </div>
        @endauth
    </div>
</header>
