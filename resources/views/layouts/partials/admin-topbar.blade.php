<header
    class="sticky top-0 z-[60] flex h-14 shrink-0 items-center justify-between gap-3 border-b border-[var(--color-primary)]/30 bg-[var(--bg-page)] px-3 sm:px-4 backdrop-blur-md">
    <div class="flex min-w-0 flex-1 items-center gap-2 sm:gap-3">
        <button type="button"
            @click="adminMenuOpen = !adminMenuOpen"
            class="relative inline-flex h-10 w-10 items-center justify-center rounded-xl border border-[var(--color-primary)]/60 bg-[var(--color-primary)] text-[var(--text-on-primary)]  lg:hidden"
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
            @php
                $adminUser = auth()->user() ?? (app()->bound(\App\Models\User::class) ? app(\App\Models\User::class) : null);
                $displayLogo = $logoUrl ?? $adminUser?->logo_url ?? null;
                $adminStoreName = $adminUser?->store_name ?? 'ZapCatalogo';
            @endphp
            @if (!empty($displayLogo))
                <div class="flex h-9 w-9 sm:h-10 sm:w-10 items-center justify-center rounded-full overflow-hidden shrink-0 border border-[var(--color-primary)]/30 bg-[var(--bg-card)] shadow-md">
                    <img src="{{ $displayLogo }}" alt="{{ $adminStoreName }}" class="h-full w-full object-cover">
                </div>
            @else
                <div class="flex h-9 w-9 sm:h-10 sm:w-10 shrink-0 items-center justify-center rounded-full bg-[var(--color-primary)] text-[var(--text-on-primary)] shadow-md shadow-[var(--color-primary)]/20">
                    <i data-lucide="layout-dashboard" class="h-5 w-5"></i>
                </div>
            @endif
            <span class="hidden min-w-0 sm:block">
                <span class="block text-[10px] font-semibold uppercase tracking-wider text-[var(--text-muted)]">Painel</span>
                <span class="block truncate text-sm font-bold text-[var(--text-base)]">{{ $adminStoreName }}</span>
            </span>
        </a>
    </div>

    <div class="flex shrink-0 items-center gap-1.5 sm:gap-2">
        <a href="{{ route('products.index') }}"
            class="hidden rounded-lg px-2.5 py-2 text-sm font-semibold text-[var(--text-base)] hover:bg-[var(--color-primary)] hover:text-[var(--text-on-primary)] md:inline-flex transition-all">
            Catálogo
        </a>
        @if(auth()->check() && auth()->user()->isSuperAdmin())
            <a href="{{ route('superadmin.index') }}"
                class="inline-flex items-center gap-1.5 rounded-lg px-2.5 py-1.5 text-xs font-bold bg-amber-500/20 text-amber-300 border border-amber-500/40 hover:bg-amber-500/30 transition-all">
                <i data-lucide="crown" class="h-3.5 w-3.5 text-amber-400"></i>
                Super Admin
            </a>
        @endif
        @auth
            <div class="relative" x-data="{ userOpen: false }" @click.outside="userOpen = false">
                <button type="button" @click="userOpen = !userOpen"
                    class="inline-flex max-w-[10rem] items-center gap-2 rounded-xl border  bg-[var(--color-primary)] px-2.5 py-2 text-left text-sm font-medium text-[var(--text-on-primary)]  sm:max-w-xs">
                    <span class="truncate">{{ Auth::user()->name }}</span>
                    <svg class="h-4 w-4 shrink-0 text-[var(--text-on-primary)]" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                        <path fill-rule="evenodd"
                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                </button>
                <div x-show="userOpen" x-cloak x-transition
                    class="absolute right-0 z-[70] mt-2 w-52 overflow-hidden rounded-xl bg-[var(--color-primary)] py-1 shadow-xl shadow-black/40">
                    @if(auth()->user()->isSuperAdmin())
                        <a href="{{ route('superadmin.index') }}"
                            class="block px-4 py-2.5 text-sm font-bold text-amber-300 hover:scale-105 transition-all">
                            👑 Super Admin
                        </a>
                    @endif
                    <a href="{{ route('profile.edit') }}"
                        class="block px-4 py-2.5 text-sm font-medium text-[var(--text-on-primary)] hover:scale-105 transition-all">Perfil</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full px-4 py-2.5 text-left text-sm cursor-pointer font-medium text-[var(--text-on-primary)] hover:scale-105 transition-all">
                            Sair
                        </button>
                    </form>
                </div>
            </div>
        @endauth
    </div>
</header>
