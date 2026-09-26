<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Super Admin' }} - ZapCatálogo</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CDN + Alpine -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        [x-cloak] { display: none !important; }
        body {
            font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
        }
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #090d16;
        }
        ::-webkit-scrollbar-thumb {
            background: #1e293b;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #334155;
        }
    </style>
</head>
<body class="bg-[#0b0f19] text-slate-100 antialiased min-h-screen flex flex-col selection:bg-purple-600 selection:text-white">

    <!-- Background glow shapes -->
    <div class="fixed inset-0 pointer-events-none -z-10 overflow-hidden">
        <div class="absolute -top-32 left-1/4 h-96 w-96 rounded-full bg-purple-600/10 blur-[120px]"></div>
        <div class="absolute top-1/3 -right-20 h-96 w-96 rounded-full bg-blue-600/10 blur-[130px]"></div>
        <div class="absolute -bottom-20 left-10 h-80 w-80 rounded-full bg-rose-600/10 blur-[120px]"></div>
    </div>

    <!-- Topbar -->
    <header class="sticky top-0 z-50 border-b border-slate-800/80 bg-[#0f172a]/80 backdrop-blur-xl">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Left: Brand / Title -->
                <div class="flex items-center gap-3">
                    <a href="{{ route('superadmin.index') }}" class="flex items-center gap-3 group">
                        <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-amber-500 via-purple-600 to-indigo-600 p-[1px] shadow-lg shadow-purple-500/20 group-hover:scale-105 transition-transform">
                            <div class="h-full w-full bg-slate-950 rounded-[11px] flex items-center justify-center">
                                <i data-lucide="crown" class="h-5 w-5 text-amber-400"></i>
                            </div>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="text-base font-extrabold tracking-tight bg-gradient-to-r from-white via-slate-200 to-slate-400 bg-clip-text text-transparent">Super Admin</span>
                                <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-full bg-amber-500/10 text-amber-400 border border-amber-500/20">Master</span>
                            </div>
                            <span class="block text-xs text-slate-400">Painel de Gestão e Exclusão Global</span>
                        </div>
                    </a>
                </div>

                <!-- Right: Action links & User Menu -->
                <div class="flex items-center gap-3">
                    @php
                        $user = auth()->user();
                    @endphp

                    @if($user && !empty($user->slug))
                        <a href="{{ route('dashboard', ['slug' => $user->slug]) }}"
                           class="hidden sm:inline-flex items-center gap-2 px-3 py-1.5 rounded-lg text-xs font-semibold text-slate-300 bg-slate-800/80 hover:bg-slate-700/80 border border-slate-700 hover:text-white transition-all">
                            <i data-lucide="store" class="h-3.5 w-3.5 text-blue-400"></i>
                            Minha Loja
                        </a>
                    @endif

                    <a href="{{ route('counpons.index') }}"
                       class="hidden sm:inline-flex items-center gap-2 px-3 py-1.5 rounded-lg text-xs font-semibold text-slate-300 bg-slate-800/80 hover:bg-slate-700/80 border border-slate-700 hover:text-white transition-all">
                        <i data-lucide="ticket" class="h-3.5 w-3.5 text-purple-400"></i>
                        Cupons
                    </a>

                    <!-- Profile dropdown -->
                    <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                        <button type="button" @click="open = !open"
                                class="flex items-center gap-2.5 px-3 py-1.5 rounded-xl border border-slate-700/80 bg-slate-900/80 text-xs font-semibold text-slate-200 hover:border-slate-600 transition-all cursor-pointer">
                            <div class="h-6 w-6 rounded-full bg-gradient-to-tr from-purple-600 to-indigo-600 flex items-center justify-center text-white text-[11px] font-bold">
                                {{ substr(auth()->user()->name ?? 'SA', 0, 2) }}
                            </div>
                            <span class="max-w-[130px] truncate hidden md:inline">{{ auth()->user()->email }}</span>
                            <i data-lucide="chevron-down" class="h-3.5 w-3.5 text-slate-400 transition-transform" :class="open ? 'rotate-180' : ''"></i>
                        </button>

                        <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-100"
                             class="absolute right-0 mt-2 w-56 rounded-xl border border-slate-800 bg-slate-900 p-1.5 shadow-2xl shadow-black/80 z-50">
                            <div class="px-3 py-2 border-b border-slate-800/80 mb-1">
                                <p class="text-xs font-medium text-slate-400">Conectado como:</p>
                                <p class="text-xs font-bold text-white truncate">{{ auth()->user()->email }}</p>
                            </div>
                            @if($user && !empty($user->slug))
                                <a href="{{ route('dashboard', ['slug' => $user->slug]) }}"
                                   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-medium text-slate-300 hover:bg-slate-800 hover:text-white transition-colors">
                                    <i data-lucide="layout-dashboard" class="h-4 w-4 text-blue-400"></i>
                                    Painel Lojista
                                </a>
                            @endif
                            <a href="{{ route('superadmin.index') }}"
                               class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-medium text-slate-300 hover:bg-slate-800 hover:text-white transition-colors">
                                <i data-lucide="crown" class="h-4 w-4 text-amber-400"></i>
                                Visão Geral Super Admin
                            </a>
                            <a href="{{ route('counpons.index') }}"
                               class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-medium text-slate-300 hover:bg-slate-800 hover:text-white transition-colors">
                                <i data-lucide="ticket" class="h-4 w-4 text-purple-400"></i>
                                Gerenciar Cupons
                            </a>
                            <div class="my-1 border-t border-slate-800"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                        class="w-full flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-medium text-rose-400 hover:bg-rose-500/10 hover:text-rose-300 transition-colors text-left cursor-pointer">
                                    <i data-lucide="log-out" class="h-4 w-4"></i>
                                    Encerrar Sessão
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- Flash messages -->
        @if (session('success'))
            <div x-data="{ show: true }" x-show="show" x-transition
                 class="mb-6 flex items-start justify-between gap-3 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-200">
                <div class="flex items-center gap-3">
                    <div class="h-8 w-8 rounded-lg bg-emerald-500/20 text-emerald-400 flex items-center justify-center shrink-0">
                        <i data-lucide="check-circle-2" class="h-5 w-5"></i>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-emerald-100">Operação concluída com sucesso!</p>
                        <p class="text-xs text-emerald-300/90 mt-0.5">{{ session('success') }}</p>
                    </div>
                </div>
                <button type="button" @click="show = false" class="text-emerald-400 hover:text-emerald-200">
                    <i data-lucide="x" class="h-4 w-4"></i>
                </button>
            </div>
        @endif

        @if (session('error'))
            <div x-data="{ show: true }" x-show="show" x-transition
                 class="mb-6 flex items-start justify-between gap-3 p-4 rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-200">
                <div class="flex items-center gap-3">
                    <div class="h-8 w-8 rounded-lg bg-rose-500/20 text-rose-400 flex items-center justify-center shrink-0">
                        <i data-lucide="alert-octagon" class="h-5 w-5"></i>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-rose-100">Atenção / Erro</p>
                        <p class="text-xs text-rose-300/90 mt-0.5">{{ session('error') }}</p>
                    </div>
                </div>
                <button type="button" @click="show = false" class="text-rose-400 hover:text-rose-200">
                    <i data-lucide="x" class="h-4 w-4"></i>
                </button>
            </div>
        @endif

        {{ $slot }}

    </main>

    <!-- Footer -->
    <footer class="border-t border-slate-900 py-6 mt-auto text-center text-xs text-slate-500">
        <div class="max-w-7xl mx-auto px-4">
            ZapCatálogo Super Admin Panel &bull; Modo Master (Exclusivo <span class="text-slate-400 font-semibold">{{ auth()->user()->email }}</span>)
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
