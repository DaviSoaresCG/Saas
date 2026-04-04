@push('styles')
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            dark: '#0f172a',
                            card: '#1e293b',
                            accent: '#2563eb',
                            accentHover: '#1d4ed8',
                        }
                    }
                }
            }
        }
    </script>
@endpush

@push('scripts')
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof lucide !== 'undefined') lucide.createIcons();
        });
    </script>
@endpush

@php
    $navActive = 'flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-semibold bg-blue-600/20 text-white border border-blue-500/40';
    $navIdle = 'flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-300 hover:bg-slate-700/50 hover:text-white transition-colors';
    $navClick = '@click="adminMenuOpen = false"';
@endphp

<x-app-layout :admin-shell="true">
    <div
        class="relative min-h-[calc(100vh-3.5rem)] w-full bg-slate-900 font-sans text-slate-100 antialiased selection:bg-blue-600 selection:text-white">
        <div class="pointer-events-none absolute inset-0 -z-10 overflow-hidden">
            <div class="absolute -top-24 -left-24 h-80 w-80 rounded-full bg-blue-600/15 blur-[100px]"></div>
            <div class="absolute -bottom-32 -right-20 h-96 w-96 rounded-full bg-indigo-600/10 blur-[110px]"></div>
        </div>

        {{-- Fundo escuro ao abrir o menu (mobile) --}}
        <div x-show="adminMenuOpen" x-transition.opacity.duration.200ms x-cloak
            @click="adminMenuOpen = false"
            class="fixed inset-x-0 bottom-0 top-14 z-40 bg-slate-950/75 backdrop-blur-sm lg:hidden"></div>

        <div class="mx-auto flex max-w-[1600px] flex-col gap-6 px-4 py-6 sm:px-6 lg:flex-row lg:gap-10 lg:px-8 lg:py-10">

            <aside id="admin-sidebar"
                class="fixed left-0 top-14 z-50 flex h-[calc(100vh-3.5rem)] w-[min(18rem,88vw)] flex-col gap-4 overflow-y-auto border-r border-slate-800 bg-slate-900/98 p-4 shadow-2xl transition-transform duration-200 ease-out lg:relative lg:top-0 lg:z-0 lg:h-auto lg:max-h-none lg:w-64 lg:shrink-0 lg:translate-x-0 lg:border-0 lg:bg-transparent lg:p-0 lg:shadow-none"
                :class="adminMenuOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'">

                <div class="rounded-2xl border border-slate-700/80 bg-slate-800/40 p-5 backdrop-blur-sm">
                    <div class="mb-6 flex items-center gap-3 lg:hidden">
                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-600 shadow-lg shadow-blue-600/25">
                            <i data-lucide="layout-dashboard" class="h-6 w-6 text-white"></i>
                        </div>
                        <div>
                            <p class="text-xs font-medium uppercase tracking-wider text-blue-400">Painel</p>
                            <p class="font-bold leading-tight text-white">ZapCatalogo</p>
                        </div>
                    </div>
                    <nav class="space-y-1">
                        <a href="{{ route('dashboard') }}" {!! $navClick !!}
                            class="{{ $active === 'dashboard' ? $navActive : $navIdle }}">
                            <i data-lucide="home" class="h-4 w-4 shrink-0 text-blue-400"></i>
                            Dashboard
                        </a>
                        <a href="{{ route('admin.products') }}" {!! $navClick !!}
                            class="{{ $active === 'products' ? $navActive : $navIdle }}">
                            <i data-lucide="package" class="h-4 w-4 shrink-0 text-slate-400"></i>
                            Produtos
                        </a>
                        <a href="{{ route('products.create') }}" {!! $navClick !!}
                            class="{{ $active === 'products-create' ? $navActive : $navIdle }}">
                            <i data-lucide="plus-circle" class="h-4 w-4 shrink-0 text-slate-400"></i>
                            Novo produto
                        </a>
                        <a href="{{ route('order.index') }}" {!! $navClick !!}
                            class="{{ $active === 'orders' ? $navActive : $navIdle }}">
                            <i data-lucide="clipboard-list" class="h-4 w-4 shrink-0 text-slate-400"></i>
                            Pedidos
                        </a>
                        <a href="{{ route('dashboard') }}#assinatura" {!! $navClick !!} class="{{ $navIdle }}">
                            <i data-lucide="credit-card" class="h-4 w-4 shrink-0 text-slate-400"></i>
                            Assinatura
                        </a>
                        <a href="{{ route('profile.edit') }}" {!! $navClick !!}
                            class="{{ $active === 'profile' ? $navActive : $navIdle }}">
                            <i data-lucide="user-cog" class="h-4 w-4 shrink-0 text-slate-400"></i>
                            Perfil
                        </a>
                    </nav>
                </div>
                <a href="{{ $catalogUrl }}" {!! $navClick !!} target="_blank" rel="noopener noreferrer"
                    class="flex w-full items-center justify-center gap-2 rounded-xl border border-slate-600 bg-slate-800/60 px-4 py-3 text-sm font-semibold text-white hover:bg-slate-700/60 transition-colors">
                    <i data-lucide="external-link" class="h-4 w-4"></i>
                    Ver loja pública
                </a>
            </aside>

            <div class="min-w-0 flex-1">
                @if ($showBackBar)
                    <div class="mb-6 flex flex-wrap items-center gap-2">
                        <button type="button"
                            onclick="history.back()"
                            class="inline-flex items-center gap-2 rounded-xl border border-slate-600 bg-slate-800/80 px-3 py-2 text-sm font-semibold text-slate-200 transition-colors hover:bg-slate-700/80">
                            <i data-lucide="arrow-left" class="h-4 w-4"></i>
                            Voltar
                        </button>
                        <a href="{{ route('dashboard') }}"
                            class="inline-flex items-center gap-2 rounded-xl border border-blue-500/40 bg-blue-600/15 px-3 py-2 text-sm font-semibold text-blue-200 transition-colors hover:bg-blue-600/25">
                            <i data-lucide="layout-dashboard" class="h-4 w-4"></i>
                            Ir ao dashboard
                        </a>
                    </div>
                @endif

                @if ($title || $subtitle)
                    <div class="mb-8">
                        @if ($title)
                            <h1 class="text-2xl font-extrabold tracking-tight text-white sm:text-3xl">{{ $title }}</h1>
                        @endif
                        @if ($subtitle)
                            <p class="mt-1 text-sm text-slate-400 sm:text-base">{{ $subtitle }}</p>
                        @endif
                    </div>
                @endif

                {{ $slot }}
            </div>
        </div>
    </div>
</x-app-layout>
