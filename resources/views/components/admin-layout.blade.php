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
    $navActive = 'flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-semibold bg-[var(--color-primary)] text-[var(--text-on-primary)] border border-[var(--color-primary)]';
    $navIdle = 'flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-[var(--text-base)] hover:bg-[var(--color-primary)]/10 transition-all hover:scale-105';
    $navClick = '@click="adminMenuOpen = false"';
@endphp

<x-app-layout :admin-shell="true">
    <div
        class="relative min-h-[calc(100vh-3.5rem)] w-full bg-[var(--bg-page)]  font-sans text-[var(--text-base)] antialiased selection:bg-[var(--color-primary)] selection:text-[var(--text-base)]">
        <div class="pointer-events-none absolute inset-0 -z-10 overflow-hidden">
            <div class="absolute -top-24 -left-24 h-80 w-80 rounded-full bg-blue-600/15 blur-[100px]"></div>
            <div class="absolute -bottom-32 -right-20 h-96 w-96 rounded-full bg-indigo-600/10 blur-[110px]"></div>
        </div>

        {{-- Fundo escuro ao abrir o menu (mobile) --}}
        <div x-show="adminMenuOpen" x-transition.opacity.duration.200ms x-cloak @click="adminMenuOpen = false"
            class="fixed inset-x-0 bottom-0 top-14 z-40 bg-slate-950/75 backdrop-blur-sm lg:hidden"></div>

        <div
            class="mx-auto flex max-w-[1600px] flex-col gap-6 px-4 py-6 sm:px-6 lg:flex-row lg:gap-10 lg:px-8 lg:py-10">

            <aside id="admin-sidebar"
                class="fixed left-0 top-14 z-50 flex h-[calc(100vh-3.5rem)] w-[min(18rem,88vw)] flex-col gap-4 overflow-y-auto p-4 shadow-2xl transition-transform duration-200 ease-out lg:relative lg:top-0 lg:z-0 lg:h-auto lg:max-h-none lg:w-64 lg:shrink-0 lg:translate-x-0 lg:border-0 lg:bg-transparent lg:p-0 lg:shadow-none"
                :class="adminMenuOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'">

                <div class="rounded-2xl border border-[var(--color-primary)] bg-[var(--bg-card)] p-5 backdrop-blur-sm">
                    <div class="mb-6 flex items-center gap-3 lg:hidden">
                        <div
                            class="flex h-11 w-11 items-center justify-center rounded-xl bg-[var(--color-primary)] shadow-lg shadow-[var(--color-primary)]/25">
                            <i data-lucide="layout-dashboard" class="h-6 w-6 text-[var(--text-on-primary)]"></i>
                        </div>
                        <div>
                            <p class="text-xs font-medium uppercase tracking-wider text-[var(--text-base)]">Painel</p>
                            <p class="font-bold leading-tight text-[var(--text-base)]">ZapCatalogo</p>
                        </div>
                    </div>
                    <nav class="space-y-1">
                        <a href="{{ route('dashboard') }}" {!! $navClick !!}
                            class="{{ $active === 'dashboard' ? $navActive : $navIdle }}">
                            <i data-lucide="layout-dashboard"
                                class="h-4 w-4 shrink-0 {{ $active === 'dashboard' ? 'text-[var(--text-on-primary)]' : 'text-[var(--text-base)]' }}"></i>
                            Dashboard
                        </a>
                        <a href="{{ route('theme.index') }}" {!! $navClick !!}
                            class="{{ $active === 'theme' ? $navActive : $navIdle }}">
                            <i data-lucide="palette"
                                class="h-4 w-4 shrink-0 {{ $active === 'theme' ? 'text-[var(--text-on-primary)]' : 'text-[var(--text-base)]' }}"></i>
                            Mudar Tema
                        </a>
                        <a href="{{ route('admin.products') }}" {!! $navClick !!}
                            class="{{ $active === 'products' ? $navActive : $navIdle }}">
                            <i data-lucide="package"
                                class="h-4 w-4 shrink-0 {{ $active === 'products' ? 'text-[var(--text-on-primary)]' : 'text-[var(--text-base)]' }}"></i>
                            Produtos
                        </a>
                        <a href="{{ route('products.create') }}" {!! $navClick !!}
                            class="{{ $active === 'products-create' ? $navActive : $navIdle }}">
                            <i data-lucide="plus-circle"
                                class="h-4 w-4 shrink-0 {{ $active === 'products-create' ? 'text-[var(--text-on-primary)]' : 'text-[var(--text-base)]' }}"></i>
                            Novo produto
                        </a>
                        <a href="{{ route('atributos.index') }}" {!! $navClick !!}
                            class="{{ $active === 'atributos' ? $navActive : $navIdle }}">
                            <i data-lucide="list"
                                class="h-4 w-4 shrink-0 {{ $active === 'atributos' ? 'text-[var(--text-on-primary)]' : 'text-[var(--text-base)]' }}"></i>
                            Atributos
                        </a>
                        <a href="{{ route('order.index') }}" {!! $navClick !!}
                            class="{{ $active === 'orders' ? $navActive : $navIdle }}">
                            <i data-lucide="clipboard-list"
                                class="h-4 w-4 shrink-0 {{ $active === 'orders' ? 'text-[var(--text-on-primary)]' : 'text-[var(--text-base)]' }}"></i>
                            Pedidos
                        </a>
                        <a href="{{ route('dashboard') }}#assinatura" {!! $navClick !!} class="{{ $navIdle }}">
                            <i data-lucide="credit-card"
                                class="h-4 w-4 shrink-0 {{ $active === 'credit-card' ? 'text-[var(--text-on-primary)]' : 'text-[var(--text-base)]' }}"></i>
                            Assinatura
                        </a>
                        <a href="{{ route('profile.edit') }}" {!! $navClick !!}
                            class="{{ $active === 'profile' ? $navActive : $navIdle }}">
                            <i data-lucide="user-cog"
                                class="h-4 w-4 shrink-0 {{ $active === 'profile' ? 'text-[var(--text-on-primary)]' : 'text-[var(--text-base)]' }}"></i>
                            Perfil
                        </a>
                    </nav>
                </div>
                <a href="{{ $catalogUrl }}" {!! $navClick !!} target="_blank" rel="noopener noreferrer"
                    class="flex w-full max-w-52 mx-auto items-center justify-center gap-2 rounded-xl bg-[var(--color-primary)] px-4 py-3 text-sm font-semibold text-[var(--text-on-primary)] transition-all hover:scale-105">
                    <i data-lucide="external-link" class="h-4 w-4"></i>
                    Ver loja pública
                </a>
            </aside>

            <div class="min-w-0 flex-1">
                @if ($showBackBar)
                    <div class="mb-6 flex flex-wrap items-center gap-2">
                        <button type="button" onclick="history.back()"
                            class="inline-flex items-center gap-2 rounded-xl bg-[var(--color-primary)]/90 px-3 py-2 text-sm font-semibold text-[var(--text-on-primary)] transition-colors hover:bg-[var(--color-primary)] cursor-pointer">
                            <i data-lucide="arrow-left" class="h-4 w-4"></i>
                            Voltar
                        </button>
                    </div>
                @endif

                @if ($title || $subtitle)
                    <div class="mb-8">
                        @if ($title)
                            <h1 class="text-2xl font-extrabold tracking-tight text-[var(--text-base)] sm:text-3xl">{{ $title }}
                            </h1>
                        @endif
                        @if ($subtitle)
                            <p class="mt-1 text-sm text-[var(--text-base)] sm:text-base">{{ $subtitle }}</p>
                        @endif
                    </div>
                @endif
                @if($errors->updatePassword->any())
                    <div class="mb-6 rounded-xl border border-red-500/40 bg-red-600/30 px-4 py-3 text-sm text-white">
                        Não foi possivel alterar a senha.
                    </div>
                @endif
                @if($errors->any())
                    <div class="mb-6 rounded-xl border border-red-500/40 bg-red-600/30 px-4 py-3 text-sm text-white">
                        Ocorreu um erro
                    </div>
                @endif
                @if(session('status') == 'password-updated')
                    <div
                        class="mb-6 rounded-xl border border-emerald-500/40 bg-emerald-600/30 px-4 py-3 text-sm text-emerald-200">
                        Senha Alterada.
                    </div>
                @endif

                {{ $slot }}
            </div>
        </div>
    </div>
</x-app-layout>