<!DOCTYPE html>
<html lang="pt-BR" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Planos e preços transparentes do ZapCatálogo. Crie sua vitrine digital integrada ao WhatsApp com 0% de comissão.">
    <title>Planos e Preços | ZapCatálogo — Sua loja online no WhatsApp</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}">

    <!-- Google Fonts: Instrument Serif & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS (CDN com extensão de tema do Design System Brillance) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        serif: ['"Instrument Serif"', 'serif'],
                    },
                    colors: {
                        brillance: {
                            bg: '#f7f5f3',
                            paper: '#eeece8',
                            charcoal: '#37322f',
                            charcoalHover: '#514a45',
                            muted: '#605a57',
                            subtle: '#716a64',
                            lime: '#d7f56b',
                            limeHover: '#e2ff88',
                            moss: '#849451',
                            olive: '#71834f',
                            border: 'rgba(55, 50, 47, 0.1)',
                        }
                    }
                }
            }
        }
    </script>

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        html {
            font-family: 'Inter', sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        .font-serif {
            font-family: 'Instrument Serif', Georgia, serif;
        }

        /* Scrollbar suave */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f7f5f3;
        }
        ::-webkit-scrollbar-thumb {
            background: #d8d4cf;
            border-radius: 9999px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #b5afa8;
        }
    </style>
</head>
<body class="min-h-screen bg-[#f7f5f3] font-sans text-[#37322f] selection:bg-[#d7f56b] selection:text-[#37322f] antialiased">

    {{-- Feedback de Mensagens de Sessão --}}
    @if(session('success'))
        <div class="fixed top-4 right-4 z-50 flex items-center gap-2 rounded-xl border border-[#dce8d3] bg-[#f0f7eb] px-4 py-3 text-xs font-medium text-[#487045] shadow-lg animate-fade-in">
            <i data-lucide="check-circle" class="size-4 text-[#20b85a]"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="fixed top-4 right-4 z-50 flex items-center gap-2 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-xs font-medium text-red-700 shadow-lg animate-fade-in">
            <i data-lucide="alert-circle" class="size-4 text-red-500"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    {{-- Header / Navbar Flutuante --}}
    <header class="sticky top-0 z-40 border-b border-[#37322f]/10 bg-[#f7f5f3]/90 backdrop-blur-xl transition-all">
        <div class="mx-auto flex h-[72px] max-w-[1160px] items-center justify-between px-5 md:px-8">
            {{-- BrandMark --}}
            <a href="{{ route('home') }}" class="flex items-center gap-2.5 font-semibold tracking-tight text-[#37322f]" aria-label="ZapCatálogo, início">
                <span class="flex size-9 items-center justify-center rounded-xl bg-[#37322f] text-[#d7f56b] shadow-sm">
                    <i data-lucide="shopping-bag" class="size-[18px]"></i>
                </span>
                <span class="text-[17px]">zap<span class="font-normal">catálogo</span></span>
            </a>

            {{-- Desktop Navigation Links --}}
            <nav class="hidden items-center gap-8 md:flex" aria-label="Navegação principal">
                <a class="text-sm text-[#605a57] transition hover:text-[#37322f]" href="{{ route('home') }}">Página inicial</a>
                <a class="text-sm text-[#605a57] transition hover:text-[#37322f]" href="{{ route('home') }}#recursos">Funcionalidades</a>
                <a class="text-sm text-[#605a57] transition hover:text-[#37322f]" href="{{ route('home') }}#como-funciona">Como funciona</a>
                <a class="text-sm font-semibold text-[#37322f]" href="{{ route('plans') }}">Planos</a>
                <a class="text-sm text-[#605a57] transition hover:text-[#37322f]" href="{{ route('home') }}#duvidas">FAQ</a>
            </nav>

            {{-- Desktop Action Area (Auth vs Guest) --}}
            <div class="hidden items-center gap-3 md:flex">
                @auth
                    <div class="flex items-center gap-3">
                        <span class="text-xs font-medium text-[#605a57]">
                            Olá, {{ auth()->user()->name }}
                        </span>
                        @if(auth()->user()->isLojaAtiva() && auth()->user()->slug)
                            <a href="{{ route('products.index', ['slug' => auth()->user()->slug]) }}" class="inline-flex h-9 items-center gap-1.5 rounded-full border border-[#37322f]/15 bg-white/80 px-3.5 text-xs font-medium text-[#37322f] transition hover:bg-white shadow-sm">
                                <i data-lucide="external-link" class="size-3.5 text-[#849451]"></i>
                                <span>Minha Loja</span>
                            </a>
                        @endif
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="inline-flex h-9 items-center rounded-full border border-[#37322f]/15 px-3 text-xs font-medium text-[#605a57] transition hover:bg-[#37322f] hover:text-white cursor-pointer">
                                Sair
                            </button>
                        </form>
                    </div>
                @endauth

                @guest
                    <a href="{{ route('login') }}" class="text-sm font-medium text-[#605a57] transition hover:text-[#37322f]">
                        Entrar no painel
                    </a>
                    <a href="{{ route('register') }}" class="inline-flex h-10 items-center gap-2 rounded-full bg-[#37322f] px-4 text-sm font-medium text-white shadow-sm transition hover:bg-[#514a45]">
                        <span>Criar catálogo</span>
                        <i data-lucide="arrow-right" class="size-4"></i>
                    </a>
                @endguest
            </div>

            {{-- Mobile Hamburger Button --}}
            <div class="flex items-center md:hidden">
                <button type="button" id="mobile-menu-btn" aria-label="Abrir menu" class="inline-flex size-10 items-center justify-center rounded-xl border border-[#37322f]/10 text-[#37322f] transition hover:bg-white cursor-pointer">
                    <i data-lucide="menu" class="size-5" id="menu-icon"></i>
                    <i data-lucide="x" class="hidden size-5" id="close-icon"></i>
                </button>
            </div>
        </div>

        {{-- Mobile Dropdown Menu --}}
        <div id="mobile-menu" class="hidden border-b border-[#37322f]/10 bg-[#f7f5f3] px-5 py-4 md:hidden">
            <nav class="flex flex-col gap-3" aria-label="Navegação mobile">
                <a class="text-sm font-medium text-[#605a57] transition hover:text-[#37322f]" href="{{ route('home') }}">Página inicial</a>
                <a class="text-sm font-medium text-[#605a57] transition hover:text-[#37322f]" href="{{ route('home') }}#recursos">Funcionalidades</a>
                <a class="text-sm font-medium text-[#605a57] transition hover:text-[#37322f]" href="{{ route('home') }}#como-funciona">Como funciona</a>
                <a class="text-sm font-semibold text-[#37322f]" href="{{ route('plans') }}">Planos</a>
                <a class="text-sm font-medium text-[#605a57] transition hover:text-[#37322f]" href="{{ route('home') }}#duvidas">FAQ</a>
                <div class="mt-2 flex flex-col gap-2 border-t border-[#37322f]/10 pt-3">
                    @auth
                        <p class="text-xs font-medium text-[#605a57]">Olá, {{ auth()->user()->name }}</p>
                        @if(auth()->user()->isLojaAtiva() && auth()->user()->slug)
                            <a href="{{ route('products.index', ['slug' => auth()->user()->slug]) }}" class="inline-flex h-10 items-center justify-center gap-2 rounded-full border border-[#37322f]/15 bg-white text-xs font-medium text-[#37322f]">
                                <i data-lucide="external-link" class="size-3.5 text-[#849451]"></i>
                                <span>Minha Loja</span>
                            </a>
                        @endif
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full h-10 rounded-full border border-[#37322f]/15 text-xs font-medium text-[#605a57] hover:bg-[#37322f] hover:text-white cursor-pointer">
                                Sair
                            </button>
                        </form>
                    @endauth

                    @guest
                        <a href="{{ route('login') }}" class="inline-flex h-10 items-center justify-center rounded-full border border-[#37322f]/15 bg-white text-xs font-medium text-[#37322f]">
                            Entrar no painel
                        </a>
                        <a href="{{ route('register') }}" class="inline-flex h-10 items-center justify-center gap-2 rounded-full bg-[#37322f] text-xs font-medium text-white hover:bg-[#514a45]">
                            <span>Criar catálogo</span>
                            <i data-lucide="arrow-right" class="size-4"></i>
                        </a>
                    @endguest
                </div>
            </nav>
        </div>
    </header>

    <main class="relative min-h-[calc(100vh-140px)]">

        {{-- Hero da Página de Planos --}}
        <section class="relative overflow-hidden px-5 pt-14 pb-8 md:pt-20 md:pb-12 text-center">
            <div class="pointer-events-none absolute inset-0 opacity-40" style="background-image: radial-gradient(#a8a098 0.7px, transparent 0.7px); background-size: 22px 22px; -webkit-mask-image: linear-gradient(to bottom, black, transparent 48%); mask-image: linear-gradient(to bottom, black, transparent 48%);"></div>

            <div class="relative mx-auto max-w-[800px]">
                <div class="inline-flex items-center gap-2 rounded-full border border-[#37322f]/10 bg-white/80 px-3.5 py-1.5 text-xs font-medium text-[#514b46] shadow-sm backdrop-blur-sm">
                    <i data-lucide="sparkles" class="size-3.5 text-[#849451]"></i>
                    <span class="text-[11px] font-semibold uppercase tracking-[.18em] text-[#849451]">Preço simples e transparente</span>
                </div>

                <h1 class="mt-5 font-serif text-4xl leading-tight tracking-[-.03em] text-[#37322f] sm:text-5xl md:text-6xl">
                    Planos feitos para o seu negócio <span class="italic text-[#7d8f55]">vender mais</span> no WhatsApp.
                </h1>

                <p class="mt-4 text-sm leading-6 text-[#716a64] sm:text-base md:text-lg">
                    Sem comissão por pedido, sem contratos longos. Escolha a assinatura ideal para colocar sua loja no ar.
                </p>
            </div>
        </section>

        {{-- Grid dos Planos --}}
        @php
            $monthlyCheckoutUrl = auth()->check() ? route('pagamento.pending', ['plan' => 'monthly']) : route('register');
            $yearlyCheckoutUrl = auth()->check() ? route('pagamento.pending', ['plan' => 'yearly']) : route('register');
        @endphp

        <section class="px-5 pb-16 md:px-8 md:pb-24">
            <div class="mx-auto max-w-[840px]">
                <div class="grid gap-6 md:grid-cols-2 items-stretch">

                    {{-- Plano Mensal --}}
                    <article class="flex flex-col justify-between rounded-2xl border border-[#37322f]/12 bg-[#f9f8f6] p-7 md:p-8 shadow-sm transition hover:border-[#37322f]/25">
                        <div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-semibold text-[#37322f]">Plano Mensal</span>
                                <span class="rounded-full bg-[#37322f]/5 px-2.5 py-0.5 text-[11px] font-medium text-[#716a64]">Sem fidelidade</span>
                            </div>
                            <p class="mt-1 text-xs text-[#716a64]">Flexibilidade para pagar mês a mês</p>

                            <div class="mt-6 flex items-end gap-1">
                                <span class="mb-1.5 text-sm font-medium text-[#716a64]">R$</span>
                                <span class="font-serif text-5xl tracking-tight text-[#37322f]">29,90</span>
                                <span class="mb-1.5 text-sm text-[#716a64]">/ mês</span>
                            </div>

                            <a href="{{ $monthlyCheckoutUrl }}"
                               class="mt-6 inline-flex h-12 w-full items-center justify-center rounded-full border border-[#37322f]/20 bg-white text-sm font-semibold text-[#37322f] shadow-sm transition hover:bg-[#37322f] hover:text-white active:scale-[0.99] cursor-pointer">
                                Começar mensal
                            </a>

                            <ul class="mt-7 flex flex-col gap-3.5 border-t border-[#37322f]/10 pt-6 text-left">
                                <li class="flex items-center gap-2.5 text-xs text-[#605a57]">
                                    <span class="flex size-4 shrink-0 items-center justify-center rounded-full bg-[#f4f8e6] text-[#71834f]">
                                        <i data-lucide="check" class="size-3"></i>
                                    </span>
                                    <span>Produtos e categorias ilimitados</span>
                                </li>
                                <li class="flex items-center gap-2.5 text-xs text-[#605a57]">
                                    <span class="flex size-4 shrink-0 items-center justify-center rounded-full bg-[#f4f8e6] text-[#71834f]">
                                        <i data-lucide="check" class="size-3"></i>
                                    </span>
                                    <span>Pedidos prontos direto no seu WhatsApp</span>
                                </li>
                                <li class="flex items-center gap-2.5 text-xs text-[#605a57]">
                                    <span class="flex size-4 shrink-0 items-center justify-center rounded-full bg-[#f4f8e6] text-[#71834f]">
                                        <i data-lucide="check" class="size-3"></i>
                                    </span>
                                    <span>Subdomínio próprio exclusivo</span>
                                </li>
                                <li class="flex items-center gap-2.5 text-xs text-[#605a57]">
                                    <span class="flex size-4 shrink-0 items-center justify-center rounded-full bg-[#f4f8e6] text-[#71834f]">
                                        <i data-lucide="check" class="size-3"></i>
                                    </span>
                                    <span>Múltiplos catálogos e cupons promocionais</span>
                                </li>
                                <li class="flex items-center gap-2.5 text-xs text-[#605a57]">
                                    <span class="flex size-4 shrink-0 items-center justify-center rounded-full bg-[#f4f8e6] text-[#71834f]">
                                        <i data-lucide="check" class="size-3"></i>
                                    </span>
                                    <span>Variações de produto (cor, tamanho)</span>
                                </li>
                                <li class="flex items-center gap-2.5 text-xs text-[#605a57]">
                                    <span class="flex size-4 shrink-0 items-center justify-center rounded-full bg-[#f4f8e6] text-[#71834f]">
                                        <i data-lucide="check" class="size-3"></i>
                                    </span>
                                    <span class="font-medium text-[#37322f]">0% de taxa por pedido</span>
                                </li>
                            </ul>
                        </div>
                    </article>

                    {{-- Plano Anual (Destaque Mais Popular) --}}
                    <article class="relative flex flex-col justify-between overflow-hidden rounded-2xl border border-[#37322f] bg-[#37322f] p-7 md:p-8 text-white shadow-[0_18px_45px_rgba(55,50,47,.17)]">
                        <span class="absolute right-4 top-4 inline-flex items-center gap-1 rounded-full bg-[#d7f56b] px-3 py-1 text-[11px] font-semibold text-[#37322f] shadow-sm">
                            <i data-lucide="sparkles" class="size-3"></i>
                            <span>MAIS POPULAR</span>
                        </span>

                        <div>
                            <div class="text-sm font-semibold">Plano Anual</div>
                            <p class="mt-1 text-xs text-white/65">Dois meses grátis no ano</p>

                            <div class="mt-6 flex items-end gap-1">
                                <span class="mb-1.5 text-sm text-white/65">R$</span>
                                <span class="font-serif text-5xl tracking-tight text-white">299</span>
                                <span class="mb-1.5 text-sm text-white/65">/ ano</span>
                            </div>
                            <p class="mt-1 text-xs text-[#d7f56b] font-medium">Equivale a R$ 24,91 por mês · economize 17%</p>

                            <a href="{{ $yearlyCheckoutUrl }}"
                               class="mt-6 inline-flex h-12 w-full items-center justify-center gap-2 rounded-full bg-[#d7f56b] text-sm font-semibold text-[#37322f] shadow-sm transition hover:bg-[#e2ff88] active:scale-[0.99] cursor-pointer">
                                <span>Escolher plano anual</span>
                                <i data-lucide="arrow-right" class="size-4"></i>
                            </a>

                            <ul class="mt-7 flex flex-col gap-3.5 border-t border-white/15 pt-6 text-left">
                                <li class="flex items-center gap-2.5 text-xs text-white/90">
                                    <span class="flex size-4 shrink-0 items-center justify-center rounded-full bg-white/10 text-[#d7f56b]">
                                        <i data-lucide="check" class="size-3"></i>
                                    </span>
                                    <span>Todos os recursos do plano mensal</span>
                                </li>
                                <li class="flex items-center gap-2.5 text-xs text-white/90">
                                    <span class="flex size-4 shrink-0 items-center justify-center rounded-full bg-white/10 text-[#d7f56b]">
                                        <i data-lucide="check" class="size-3"></i>
                                    </span>
                                    <span>Produtos e pedidos ilimitados</span>
                                </li>
                                <li class="flex items-center gap-2.5 text-xs text-white/90">
                                    <span class="flex size-4 shrink-0 items-center justify-center rounded-full bg-white/10 text-[#d7f56b]">
                                        <i data-lucide="check" class="size-3"></i>
                                    </span>
                                    <span>Subdomínio exclusivo e personalizado</span>
                                </li>
                                <li class="flex items-center gap-2.5 text-xs text-white/90">
                                    <span class="flex size-4 shrink-0 items-center justify-center rounded-full bg-white/10 text-[#d7f56b]">
                                        <i data-lucide="check" class="size-3"></i>
                                    </span>
                                    <span>Suporte prioritário na configuração</span>
                                </li>
                                <li class="flex items-center gap-2.5 text-xs text-[#d7f56b] font-medium">
                                    <span class="flex size-4 shrink-0 items-center justify-center rounded-full bg-[#d7f56b]/20 text-[#d7f56b]">
                                        <i data-lucide="check" class="size-3"></i>
                                    </span>
                                    <span>Economia garantida de R$ 59,80</span>
                                </li>
                                <li class="flex items-center gap-2.5 text-xs text-white/90">
                                    <span class="flex size-4 shrink-0 items-center justify-center rounded-full bg-white/10 text-[#d7f56b]">
                                        <i data-lucide="check" class="size-3"></i>
                                    </span>
                                    <span class="font-medium text-white">0% de taxa por pedido</span>
                                </li>
                            </ul>
                        </div>
                    </article>
                </div>

                {{-- Formulário de Cupom de Desconto --}}
                <div class="mt-8 rounded-2xl border border-[#37322f]/10 bg-white/70 p-6 shadow-sm text-left">
                    <label for="coupon-input" class="text-xs font-semibold text-[#514b46]">Possui um cupom de desconto?</label>
                    @auth
                        <form action="{{ route('counpons.validate') }}" method="POST" class="mt-2.5 flex flex-col sm:flex-row gap-2">
                            @csrf
                            <div class="relative min-w-0 flex-1">
                                <i data-lucide="ticket" class="pointer-events-none absolute left-3.5 top-1/2 size-4 -translate-y-1/2 text-[#969087]"></i>
                                <input id="coupon-input" name="code" value="{{ old('code') }}" placeholder="Ex: BEMVINDO10" required maxlength="20"
                                    class="h-11 w-full rounded-xl border border-[#37322f]/15 bg-white pl-10 pr-3 text-sm text-[#37322f] outline-none placeholder:text-[#9b938c] focus:border-[#849451] transition">
                            </div>
                            <button type="submit" class="h-11 rounded-xl bg-[#37322f] px-6 text-xs font-semibold text-white hover:bg-[#514a45] transition cursor-pointer">
                                Aplicar cupom
                            </button>
                        </form>
                    @else
                        <div class="mt-2.5 flex flex-col sm:flex-row sm:items-center justify-between gap-3 pt-1">
                            <p class="text-xs text-[#716a64]">
                                Faça login ou crie sua conta para aplicar seu cupom na finalização da assinatura.
                            </p>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('login') }}" class="inline-flex h-9 items-center justify-center rounded-lg border border-[#37322f]/15 bg-white px-3.5 text-xs font-medium text-[#37322f] transition hover:bg-[#f7f5f3]">
                                    Entrar
                                </a>
                                <a href="{{ route('register') }}" class="inline-flex h-9 items-center justify-center rounded-lg bg-[#37322f] px-3.5 text-xs font-medium text-white transition hover:bg-[#514a45]">
                                    Criar conta
                                </a>
                            </div>
                        </div>
                    @endauth

                    @if(session('error'))
                        <p role="alert" class="mt-2 text-xs font-medium text-red-600">{{ session('error') }}</p>
                    @endif
                    @if(session('success'))
                        <p role="status" class="mt-2 text-xs font-medium text-[#487045]">{{ session('success') }}</p>
                    @endif
                </div>

                {{-- Aviso de Segurança e Pagamento --}}
                <div class="mt-8 flex flex-col items-center justify-center gap-2 text-center text-xs text-[#716a64]">
                    <p class="inline-flex items-center gap-2 font-medium">
                        <i data-lucide="shield-check" class="size-4 text-[#839b50]"></i>
                        <span>Pagamento seguro via Pix ou cartão de crédito. Assinatura transparente, sem fidelidade e sem taxas escondidas.</span>
                    </p>
                </div>
            </div>
        </section>
    </main>

    {{-- Footer Padrão Brillance --}}
    <footer class="border-t border-[#37322f]/10 bg-[#f7f5f3] py-12 text-[#605a57]">
        <div class="mx-auto max-w-[1160px] px-5 md:px-8">
            <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                <a href="{{ route('home') }}" class="flex items-center gap-2.5 font-semibold tracking-tight text-[#37322f]">
                    <span class="flex size-8 items-center justify-center rounded-xl bg-[#37322f] text-[#d7f56b] shadow-sm">
                        <i data-lucide="shopping-bag" class="size-4"></i>
                    </span>
                    <span class="text-base">zap<span class="font-normal">catálogo</span></span>
                </a>

                <p class="text-xs text-[#8c827a] text-center md:text-left">
                    &copy; {{ date('Y') }} ZapCatálogo. Todos os direitos reservados.
                </p>

                <div class="flex items-center gap-6 text-xs text-[#716a64]">
                    <a href="{{ route('home') }}" class="hover:text-[#37322f] transition">Início</a>
                    <a href="{{ route('home') }}#recursos" class="hover:text-[#37322f] transition">Recursos</a>
                    <a href="{{ route('home') }}#duvidas" class="hover:text-[#37322f] transition">Dúvidas</a>
                    <a href="{{ route('plans') }}" class="font-medium text-[#37322f]">Planos</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts de Interação -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }

            // Mobile menu toggle
            const btn = document.getElementById('mobile-menu-btn');
            const menu = document.getElementById('mobile-menu');
            const menuIcon = document.getElementById('menu-icon');
            const closeIcon = document.getElementById('close-icon');

            if (btn && menu) {
                btn.addEventListener('click', function () {
                    const isHidden = menu.classList.contains('hidden');
                    menu.classList.toggle('hidden');
                    if (menuIcon && closeIcon) {
                        menuIcon.classList.toggle('hidden', isHidden);
                        closeIcon.classList.toggle('hidden', !isHidden);
                    }
                });
            }
        });
    </script>
</body>
</html>
