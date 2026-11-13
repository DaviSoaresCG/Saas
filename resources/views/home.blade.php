<!DOCTYPE html>
<html lang="pt-BR" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Crie seu catálogo digital com link próprio, carrinho inteligente e pedidos organizados direto no WhatsApp. Venda mais com 0% de comissão.">
    <title>ZapCatálogo — Sua loja online no WhatsApp, sem comissão</title>
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
        /* Tipografia e suavização */
        html {
            font-family: 'Inter', sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        .font-serif {
            font-family: 'Instrument Serif', Georgia, serif;
        }

        /* Scrollbar suave e sutil */
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
<body class="min-h-screen bg-[#f7f5f3] font-sans text-[#37322f] selection:bg-[#d7f56b] selection:text-[#37322f]">

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
            <a href="#inicio" class="flex items-center gap-2.5 font-semibold tracking-tight text-[#37322f]" aria-label="ZapCatálogo, início">
                <span class="flex size-9 items-center justify-center rounded-xl bg-[#37322f] text-[#d7f56b] shadow-sm">
                    <i data-lucide="shopping-bag" class="size-[18px]"></i>
                </span>
                <span class="text-[17px]">zap<span class="font-normal">catálogo</span></span>
            </a>

            {{-- Desktop Navigation Links --}}
            <nav class="hidden items-center gap-8 md:flex" aria-label="Navegação principal">
                <a class="text-sm text-[#605a57] transition hover:text-[#37322f]" href="#recursos">Funcionalidades</a>
                <a class="text-sm text-[#605a57] transition hover:text-[#37322f]" href="#como-funciona">Como funciona</a>
                <a class="text-sm text-[#605a57] transition hover:text-[#37322f]" href="#precos">Preços</a>
                <a class="text-sm text-[#605a57] transition hover:text-[#37322f]" href="#duvidas">FAQ</a>
            </nav>

            {{-- Desktop Action Area (Auth vs Guest) --}}
            <div class="hidden items-center gap-3 sm:flex">
                @auth
                    <div class="flex items-center gap-3">
                        <span class="text-xs font-medium text-[#605a57]">
                            {{ auth()->user()->name }}
                        </span>
                        @if(auth()->user()->isLojaAtiva() && auth()->user()->slug)
                            <a href="{{ route('products.index', ['slug' => auth()->user()->slug]) }}" class="inline-flex h-9 items-center gap-1.5 rounded-full border border-[#37322f]/15 bg-white/80 px-3.5 text-xs font-medium text-[#37322f] transition hover:bg-white shadow-sm">
                                <i data-lucide="external-link" class="size-3.5 text-[#849451]"></i>
                                <span>Minha Loja</span>
                            </a>
                        @endif
                        @if(auth()->user()->isSuperAdmin())
                            <a href="{{ route('superadmin.index') }}" class="inline-flex h-9 items-center gap-1 rounded-full border border-amber-300 bg-amber-50 px-3 text-xs font-semibold text-amber-800 transition hover:bg-amber-100">
                                <span>👑 Super Admin</span>
                            </a>
                        @endif
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="inline-flex h-9 items-center rounded-full border border-[#37322f]/15 px-3 text-xs font-medium text-[#605a57] transition hover:bg-[#37322f] hover:text-white">
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
            <div class="flex items-center sm:hidden">
                <button type="button" id="mobile-menu-btn" aria-label="Abrir menu" class="inline-flex size-10 items-center justify-center rounded-xl border border-[#37322f]/10 text-[#37322f] transition hover:bg-white">
                    <i data-lucide="menu" class="size-5" id="menu-icon"></i>
                    <i data-lucide="x" class="hidden size-5" id="close-icon"></i>
                </button>
            </div>
        </div>

        {{-- Mobile Dropdown Menu --}}
        <div id="mobile-menu" class="hidden border-b border-[#37322f]/10 bg-[#f7f5f3] px-5 py-4 sm:hidden">
            <nav class="flex flex-col gap-3" aria-label="Navegação mobile">
                <a class="text-sm font-medium text-[#605a57] transition hover:text-[#37322f]" href="#recursos">Funcionalidades</a>
                <a class="text-sm font-medium text-[#605a57] transition hover:text-[#37322f]" href="#como-funciona">Como funciona</a>
                <a class="text-sm font-medium text-[#605a57] transition hover:text-[#37322f]" href="#precos">Preços</a>
                <a class="text-sm font-medium text-[#605a57] transition hover:text-[#37322f]" href="#duvidas">FAQ</a>
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
                            <button type="submit" class="w-full h-10 rounded-full border border-[#37322f]/15 text-xs font-medium text-[#605a57] hover:bg-[#37322f] hover:text-white">
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

    {{-- Shell Semântico da Landing Page --}}
    <main id="app" class="relative min-h-screen overflow-x-hidden">

        {{-- Hero Section (Ticket 02) --}}
        <section id="inicio" class="relative overflow-hidden px-5 pb-14 pt-16 md:pb-20 md:pt-24">
            {{-- Dot pattern background com gradiente radial --}}
            <div class="pointer-events-none absolute inset-0 opacity-40" style="background-image: radial-gradient(#a8a098 0.7px, transparent 0.7px); background-size: 22px 22px; -webkit-mask-image: linear-gradient(to bottom, black, transparent 48%); mask-image: linear-gradient(to bottom, black, transparent 48%);"></div>

            <div class="relative mx-auto flex max-w-[1160px] flex-col items-center text-center">
                {{-- Pill badge --}}
                <div class="inline-flex items-center gap-2 rounded-full border border-[#37322f]/10 bg-white/80 px-3.5 py-2 text-xs font-medium text-[#514b46] shadow-sm backdrop-blur-sm">
                    <i data-lucide="zap" class="size-3.5 fill-[#9fc54f] text-[#9fc54f]"></i>
                    <span>ZapCatálogo 2.0</span>
                    <span class="h-3 w-px bg-[#37322f]/15"></span>
                    <span>Sem taxas por venda</span>
                </div>

                {{-- Headline editorial com Instrument Serif --}}
                <h1 class="mt-7 max-w-[900px] font-serif text-[clamp(44px,7vw,82px)] leading-[.99] tracking-[-.045em] text-[#37322f]">
                    Seu WhatsApp pode vender <span class="italic text-[#7d8f55]">muito mais.</span>
                </h1>

                {{-- Subheadline persuasiva --}}
                <p class="mt-6 max-w-[610px] text-base leading-7 text-[#605a57] md:text-lg">
                    Crie uma loja digital profissional com link próprio, carrinho inteligente e pedidos organizados no WhatsApp. Sem complicação e com <strong class="font-semibold text-[#37322f]">0% de comissão.</strong>
                </p>

                {{-- CTAs --}}
                <div class="mt-8 flex w-full flex-col justify-center gap-3 sm:w-auto sm:flex-row">
                    @auth
                        <a href="#precos" class="inline-flex h-12 items-center justify-center gap-2 rounded-full bg-[#37322f] px-6 text-sm font-medium text-white shadow-[0_8px_22px_rgba(55,50,47,.17)] transition hover:-translate-y-0.5 hover:bg-[#514a45]">
                            <span>Começar agora</span>
                            <i data-lucide="arrow-right" class="size-4"></i>
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="inline-flex h-12 items-center justify-center gap-2 rounded-full bg-[#37322f] px-6 text-sm font-medium text-white shadow-[0_8px_22px_rgba(55,50,47,.17)] transition hover:-translate-y-0.5 hover:bg-[#514a45]">
                            <span>Começar agora</span>
                            <i data-lucide="arrow-right" class="size-4"></i>
                        </a>
                    @endauth
                    <a href="#demonstracao" class="inline-flex h-12 items-center justify-center gap-2 rounded-full border border-[#37322f]/15 bg-white/70 px-6 text-sm font-medium text-[#37322f] transition hover:bg-white shadow-sm">
                        <span class="flex size-6 items-center justify-center rounded-full bg-[#e9eddf]">
                            <i data-lucide="arrow-down" class="size-3.5"></i>
                        </span>
                        <span>Ver demonstração</span>
                    </a>
                </div>

                {{-- Selos de confiança --}}
                <div class="mt-5 flex flex-wrap justify-center gap-x-5 gap-y-2 text-xs text-[#716a64]">
                    <span class="inline-flex items-center gap-1.5">
                        <i data-lucide="check" class="size-3.5 text-[#839b50]"></i> 0% de comissão
                    </span>
                    <span class="inline-flex items-center gap-1.5">
                        <i data-lucide="check" class="size-3.5 text-[#839b50]"></i> Configuração simples
                    </span>
                    <span class="inline-flex items-center gap-1.5">
                        <i data-lucide="check" class="size-3.5 text-[#839b50]"></i> Cancele quando quiser
                    </span>
                </div>

                {{-- Mockup Duplo: Smartphone com Vitrine + Balão WhatsApp --}}
                <div class="relative mx-auto w-full max-w-[930px] pt-4 md:pt-10">
                    {{-- Halos de luz de fundo --}}
                    <div class="absolute -left-10 top-24 hidden h-48 w-48 rounded-full bg-[#d7f56b]/40 blur-3xl md:block pointer-events-none"></div>
                    <div class="absolute -right-8 bottom-4 hidden h-52 w-52 rounded-full bg-[#e8b68e]/35 blur-3xl md:block pointer-events-none"></div>

                    <div class="relative grid items-center gap-5 md:grid-cols-[1fr_1.1fr] md:gap-8">
                        {{-- Mockup do Smartphone --}}
                        <div class="relative mx-auto w-[min(100%,350px)] rounded-[34px] border-[8px] border-[#292725] bg-white p-2 shadow-[0_28px_80px_rgba(55,50,47,.18)]">
                            <div class="overflow-hidden rounded-[25px] bg-[#fbfaf8]">
                                {{-- Topo do app mobile --}}
                                <div class="flex items-center justify-between border-b border-[#37322f]/10 px-4 py-3">
                                    <span class="text-xs font-semibold text-[#37322f]">boutique.zapcatalogo.com.br</span>
                                    <i data-lucide="shopping-bag" class="size-4 text-[#37322f]"></i>
                                </div>
                                {{-- Banner interno da loja --}}
                                <div class="bg-[#f1eadf] px-4 py-5 text-left">
                                    <span class="text-[10px] font-medium uppercase tracking-[.2em] text-[#827267]">nova coleção</span>
                                    <h3 class="mt-1 font-serif text-[28px] leading-none text-[#37322f]">Vista o seu melhor.</h3>
                                    <div class="mt-4 flex h-9 items-center gap-2 rounded-full bg-white/80 px-3 text-xs text-[#8a817a]">
                                        <i data-lucide="search" class="size-3.5"></i>
                                        <span>Buscar produtos</span>
                                    </div>
                                </div>
                                {{-- Vitrine em 2 colunas --}}
                                <div class="grid grid-cols-2 gap-2 p-3 text-left">
                                    <div class="rounded-xl bg-[#e8d7c7] p-2">
                                        <div class="flex h-[94px] items-center justify-center rounded-lg bg-gradient-to-br from-[#d9c2aa] to-[#b48c70]">
                                            <span class="text-2xl text-white/90">✦</span>
                                        </div>
                                        <p class="mt-2 text-[11px] font-medium text-[#37322f]">Blusa Aurora</p>
                                        <p class="text-[10px] text-[#766c64]">R$ 89,90</p>
                                    </div>
                                    <div class="rounded-xl bg-[#e8d7c7] p-2">
                                        <div class="flex h-[94px] items-center justify-center rounded-lg bg-gradient-to-br from-[#d1d9ca] to-[#87927e]">
                                            <span class="text-2xl text-white/90">✦</span>
                                        </div>
                                        <p class="mt-2 text-[11px] font-medium text-[#37322f]">Bolsa Serena</p>
                                        <p class="text-[10px] text-[#766c64]">R$ 149,90</p>
                                    </div>
                                </div>
                                {{-- Barra de sacola inferior --}}
                                <div class="mx-3 mb-3 flex items-center justify-between rounded-xl bg-[#37322f] px-3 py-2.5 text-white">
                                    <span class="text-[11px]">Ver sacola · 2 itens</span>
                                    <span class="text-xs font-semibold">R$ 239,80</span>
                                </div>
                            </div>
                            {{-- Notch do celular --}}
                            <div class="absolute left-1/2 top-1.5 h-4 w-20 -translate-x-1/2 rounded-b-xl bg-[#292725]"></div>
                        </div>

                        {{-- Mockup do WhatsApp Business --}}
                        <div class="relative rounded-2xl border border-[#dce8d3] bg-[#f0f7eb] p-4 text-left shadow-[0_18px_55px_rgba(55,50,47,.10)] md:ml-0 md:p-5">
                            <div class="mb-4 flex items-center gap-3 border-b border-[#dce8d3] pb-3">
                                <div class="flex size-9 items-center justify-center rounded-full bg-[#20b85a] text-white shadow-sm">
                                    <i data-lucide="message-circle" class="size-5"></i>
                                </div>
                                <div>
                                    <div class="text-sm font-semibold text-[#37322f]">Seu WhatsApp Business</div>
                                    <div class="text-[11px] text-[#75816e]">Pedido recebido agora</div>
                                </div>
                                <span class="ml-auto rounded-full bg-[#dff1d5] px-2 py-1 text-[10px] font-semibold text-[#487045]">NOVO</span>
                            </div>

                            {{-- Balão da mensagem formatada --}}
                            <div class="rounded-xl rounded-tl-sm bg-white p-4 text-[12px] leading-[1.8] text-[#37322f] shadow-sm">
                                <p class="font-semibold text-[#2f3e29]">Olá! Quero fazer o pedido #1024:</p>
                                <p class="mt-2">
                                    • 1x Blusa Aurora (Areia / M)<br>
                                    • 1x Bolsa Serena<br>
                                    <span class="text-[#a8a39a]">—————————————</span><br>
                                    <strong>Total: R$ 239,80</strong><br>
                                    Cliente: Marina Costa
                                </p>
                                <p class="mt-2 text-[#687f5e] font-medium">Aguardo confirmação!</p>
                                <span class="mt-2 block text-right text-[10px] text-[#9ba397]">10:42 ✓✓</span>
                            </div>

                            {{-- Destaque explicativo inferior --}}
                            <div class="mt-3 flex items-center gap-2 rounded-xl bg-white/70 px-3 py-2 text-[11px] text-[#697363]">
                                <i data-lucide="zap" class="size-3.5 text-[#93b64b]"></i>
                                <span>Pedido montado automaticamente pelo catálogo</span>
                            </div>
                        </div>
                    </div>

                    {{-- Badge explicativo sob o mockup --}}
                    <div class="mx-auto mt-6 flex w-fit items-center gap-2 rounded-full border border-[#37322f]/10 bg-white/80 px-4 py-2 text-xs text-[#605a57] shadow-sm">
                        <span class="size-2 rounded-full bg-[#9ac34b]"></span>
                        <span>Uma vitrine bonita. Um pedido que já chega pronto.</span>
                    </div>
                </div>
            </div>
        </section>

        {{-- Barra de Métricas (Ticket 03) --}}
        <section aria-label="Benefícios do ZapCatálogo" class="border-y border-[#37322f]/10 bg-white/45">
            <div class="mx-auto grid max-w-[1160px] grid-cols-2 divide-x divide-y divide-[#37322f]/10 px-5 md:grid-cols-4 md:divide-y-0 md:px-8">
                <div class="flex flex-col items-center justify-center py-6 text-center md:py-8">
                    <span class="font-serif text-3xl md:text-4xl text-[#37322f]">0%</span>
                    <span class="mt-1 text-xs text-[#716a64]">comissão por venda</span>
                </div>
                <div class="flex flex-col items-center justify-center py-6 text-center md:py-8">
                    <span class="font-serif text-3xl md:text-4xl text-[#37322f]">1 link</span>
                    <span class="mt-1 text-xs text-[#716a64]">para sua loja</span>
                </div>
                <div class="flex flex-col items-center justify-center py-6 text-center md:py-8">
                    <span class="font-serif text-3xl md:text-4xl text-[#37322f]">24/7</span>
                    <span class="mt-1 text-xs text-[#716a64]">catálogo no ar</span>
                </div>
                <div class="flex flex-col items-center justify-center py-6 text-center md:py-8">
                    <span class="font-serif text-3xl md:text-4xl text-[#37322f]">100%</span>
                    <span class="mt-1 text-xs text-[#716a64]">do lucro é seu</span>
                </div>
            </div>
        </section>

        {{-- Grade de Funcionalidades (Ticket 03) --}}
        <section id="recursos" class="px-5 py-20 md:px-8 md:py-28">
            <div class="mx-auto max-w-[1160px]">
                <div class="mx-auto mb-12 max-w-[650px] text-center">
                    <span class="text-[11px] font-semibold uppercase tracking-[.18em] text-[#849451]">Tudo em um só lugar</span>
                    <h2 class="mt-3 font-serif text-4xl leading-tight tracking-[-.025em] text-[#37322f] md:text-5xl">Sua loja online. Do seu jeito.</h2>
                    <p class="mt-4 text-sm leading-6 text-[#716a64] md:text-base">Uma experiência de compra simples para o cliente e ferramentas práticas para você cuidar de cada detalhe.</p>
                </div>

                <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                    {{-- Recurso 1: Subdomínio --}}
                    <article class="group min-h-[205px] rounded-2xl border border-[#37322f]/10 bg-white/60 p-5 transition duration-300 hover:-translate-y-1 hover:border-[#9bab70]/50 hover:bg-white hover:shadow-[0_14px_35px_rgba(55,50,47,.07)]">
                        <span class="flex size-10 items-center justify-center rounded-xl bg-[#eef0e7] text-[#647449] transition group-hover:bg-[#e3e9d3]">
                            <i data-lucide="globe" class="size-[18px]"></i>
                        </span>
                        <h3 class="mt-5 text-sm font-semibold text-[#37322f]">Seu endereço profissional</h3>
                        <p class="mt-2 text-[13px] leading-5 text-[#716a64]">Um subdomínio exclusivo para divulgar sua loja na bio, nos stories e onde seus clientes já estão.</p>
                    </article>

                    {{-- Recurso 2: WhatsApp --}}
                    <article class="group min-h-[205px] rounded-2xl border border-[#37322f]/10 bg-white/60 p-5 transition duration-300 hover:-translate-y-1 hover:border-[#9bab70]/50 hover:bg-white hover:shadow-[0_14px_35px_rgba(55,50,47,.07)]">
                        <span class="flex size-10 items-center justify-center rounded-xl bg-[#eef0e7] text-[#647449] transition group-hover:bg-[#e3e9d3]">
                            <i data-lucide="message-circle" class="size-[18px]"></i>
                        </span>
                        <h3 class="mt-5 text-sm font-semibold text-[#37322f]">Pedidos prontos no WhatsApp</h3>
                        <p class="mt-2 text-[13px] leading-5 text-[#716a64]">Carrinho, variações e total calculado. O cliente envia o pedido organizado direto para você.</p>
                    </article>

                    {{-- Recurso 3: Zero Comissão --}}
                    <article class="group min-h-[205px] rounded-2xl border border-[#37322f]/10 bg-white/60 p-5 transition duration-300 hover:-translate-y-1 hover:border-[#9bab70]/50 hover:bg-white hover:shadow-[0_14px_35px_rgba(55,50,47,.07)]">
                        <span class="flex size-10 items-center justify-center rounded-xl bg-[#eef0e7] text-[#647449] transition group-hover:bg-[#e3e9d3]">
                            <i data-lucide="tag" class="size-[18px]"></i>
                        </span>
                        <h3 class="mt-5 text-sm font-semibold text-[#37322f]">Zero comissão por venda</h3>
                        <p class="mt-2 text-[13px] leading-5 text-[#716a64]">Sem taxas sobre pedidos. Você paga uma assinatura fixa e fica com todo o lucro das vendas.</p>
                    </article>

                    {{-- Recurso 4: Branding --}}
                    <article class="group min-h-[205px] rounded-2xl border border-[#37322f]/10 bg-white/60 p-5 transition duration-300 hover:-translate-y-1 hover:border-[#9bab70]/50 hover:bg-white hover:shadow-[0_14px_35px_rgba(55,50,47,.07)]">
                        <span class="flex size-10 items-center justify-center rounded-xl bg-[#eef0e7] text-[#647449] transition group-hover:bg-[#e3e9d3]">
                            <i data-lucide="palette" class="size-[18px]"></i>
                        </span>
                        <h3 class="mt-5 text-sm font-semibold text-[#37322f]">A sua marca em cada detalhe</h3>
                        <p class="mt-2 text-[13px] leading-5 text-[#716a64]">Adicione sua logo e escolha as cores para criar uma vitrine com a identidade da sua loja.</p>
                    </article>

                    {{-- Recurso 5: Layout Mobile --}}
                    <article class="group min-h-[205px] rounded-2xl border border-[#37322f]/10 bg-white/60 p-5 transition duration-300 hover:-translate-y-1 hover:border-[#9bab70]/50 hover:bg-white hover:shadow-[0_14px_35px_rgba(55,50,47,.07)]">
                        <span class="flex size-10 items-center justify-center rounded-xl bg-[#eef0e7] text-[#647449] transition group-hover:bg-[#e3e9d3]">
                            <i data-lucide="layout-grid" class="size-[18px]"></i>
                        </span>
                        <h3 class="mt-5 text-sm font-semibold text-[#37322f]">Vitrine do seu jeito</h3>
                        <p class="mt-2 text-[13px] leading-5 text-[#716a64]">Escolha uma ou duas colunas no celular e deixe seus produtos brilharem como merecem.</p>
                    </article>

                    {{-- Recurso 6: Estatísticas --}}
                    <article class="group min-h-[205px] rounded-2xl border border-[#37322f]/10 bg-white/60 p-5 transition duration-300 hover:-translate-y-1 hover:border-[#9bab70]/50 hover:bg-white hover:shadow-[0_14px_35px_rgba(55,50,47,.07)]">
                        <span class="flex size-10 items-center justify-center rounded-xl bg-[#eef0e7] text-[#647449] transition group-hover:bg-[#e3e9d3]">
                            <i data-lucide="bar-chart-3" class="size-[18px]"></i>
                        </span>
                        <h3 class="mt-5 text-sm font-semibold text-[#37322f]">Dados para vender melhor</h3>
                        <p class="mt-2 text-[13px] leading-5 text-[#716a64]">Acompanhe produtos mais visitados, pedidos recebidos e o resumo da sua operação.</p>
                    </article>

                    {{-- Recurso 7: Promoções & Descontos --}}
                    <article class="group min-h-[205px] rounded-2xl border border-[#37322f]/10 bg-white/60 p-5 transition duration-300 hover:-translate-y-1 hover:border-[#9bab70]/50 hover:bg-white hover:shadow-[0_14px_35px_rgba(55,50,47,.07)]">
                        <span class="flex size-10 items-center justify-center rounded-xl bg-[#eef0e7] text-[#647449] transition group-hover:bg-[#e3e9d3]">
                            <i data-lucide="sparkles" class="size-[18px]"></i>
                        </span>
                        <h3 class="mt-5 text-sm font-semibold text-[#37322f]">Promoções com link próprio</h3>
                        <p class="mt-2 text-[13px] leading-5 text-[#716a64]">Crie campanhas com desconto automático ou mostre preços sob consulta para negociar.</p>
                    </article>

                    {{-- Recurso 8: Variações --}}
                    <article class="group min-h-[205px] rounded-2xl border border-[#37322f]/10 bg-white/60 p-5 transition duration-300 hover:-translate-y-1 hover:border-[#9bab70]/50 hover:bg-white hover:shadow-[0_14px_35px_rgba(55,50,47,.07)]">
                        <span class="flex size-10 items-center justify-center rounded-xl bg-[#eef0e7] text-[#647449] transition group-hover:bg-[#e3e9d3]">
                            <i data-lucide="package" class="size-[18px]"></i>
                        </span>
                        <h3 class="mt-5 text-sm font-semibold text-[#37322f]">Produtos e variações</h3>
                        <p class="mt-2 text-[13px] leading-5 text-[#716a64]">Organize categorias, fotos, tamanhos, cores e atributos em um catálogo fácil de explorar.</p>
                    </article>
                </div>
            </div>
        </section>

        {{-- Comparativo: Jeito Antigo vs ZapCatálogo (Ticket 03) --}}
        <section class="border-y border-[#37322f]/10 bg-[#eeece8] px-5 py-20 md:px-8 md:py-24">
            <div class="mx-auto max-w-[1000px]">
                <div class="mx-auto mb-12 max-w-[650px] text-center">
                    <span class="text-[11px] font-semibold uppercase tracking-[.18em] text-[#849451]">Uma forma mais leve de vender</span>
                    <h2 class="mt-3 font-serif text-4xl leading-tight tracking-[-.025em] text-[#37322f] md:text-5xl">Menos conversa perdida. Mais pedidos fechados.</h2>
                    <p class="mt-4 text-sm leading-6 text-[#716a64] md:text-base">Deixe o PDF e a digitação manual para trás. Sua vitrine trabalha junto com você.</p>
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    {{-- Jeito Antigo --}}
                    <article class="rounded-2xl border border-[#37322f]/10 bg-[#f8f6f4] p-6 md:p-8">
                        <span class="text-xs font-semibold uppercase tracking-[.13em] text-[#918780]">Do jeito antigo</span>
                        <h3 class="mt-3 font-serif text-2xl text-[#5d5752]">Tudo se perde no chat.</h3>
                        <ul class="mt-6 flex flex-col gap-4">
                            <li class="flex gap-3 text-sm text-[#77706a]">
                                <span class="mt-0.5 font-bold text-[#b29786]">—</span>
                                <span>PDF pesado que ninguém abre</span>
                            </li>
                            <li class="flex gap-3 text-sm text-[#77706a]">
                                <span class="mt-0.5 font-bold text-[#b29786]">—</span>
                                <span>Preço desatualizado em cada conversa</span>
                            </li>
                            <li class="flex gap-3 text-sm text-[#77706a]">
                                <span class="mt-0.5 font-bold text-[#b29786]">—</span>
                                <span>Fotos e informações digitadas uma a uma</span>
                            </li>
                            <li class="flex gap-3 text-sm text-[#77706a]">
                                <span class="mt-0.5 font-bold text-[#b29786]">—</span>
                                <span>Pedidos confusos e difíceis de somar</span>
                            </li>
                        </ul>
                    </article>

                    {{-- Com ZapCatálogo --}}
                    <article class="relative overflow-hidden rounded-2xl border border-[#d8dfc9] bg-[#f7f8f2] p-6 md:p-8 shadow-sm">
                        <div class="pointer-events-none absolute -right-8 -top-10 size-40 rounded-full bg-[#d7e4bc]/60 blur-3xl"></div>
                        <div class="relative">
                            <span class="text-xs font-semibold uppercase tracking-[.13em] text-[#7d914e]">Com ZapCatálogo</span>
                            <h3 class="mt-3 font-serif text-2xl text-[#37322f]">A venda flui naturalmente.</h3>
                            <ul class="mt-6 flex flex-col gap-4">
                                <li class="flex gap-3 text-sm text-[#514b46]">
                                    <i data-lucide="check" class="mt-0.5 size-4 shrink-0 text-[#839b50]"></i>
                                    <span>Um link leve para sua bio e redes sociais</span>
                                </li>
                                <li class="flex gap-3 text-sm text-[#514b46]">
                                    <i data-lucide="check" class="mt-0.5 size-4 shrink-0 text-[#839b50]"></i>
                                    <span>Vitrine interativa com busca instantânea</span>
                                </li>
                                <li class="flex gap-3 text-sm text-[#514b46]">
                                    <i data-lucide="check" class="mt-0.5 size-4 shrink-0 text-[#839b50]"></i>
                                    <span>Cliente escolhe produtos e variações</span>
                                </li>
                                <li class="flex gap-3 text-sm text-[#514b46]">
                                    <i data-lucide="check" class="mt-0.5 size-4 shrink-0 text-[#839b50]"></i>
                                    <span>Pedido completo, somado e pronto no WhatsApp</span>
                                </li>
                            </ul>
                        </div>
                    </article>
                </div>
            </div>
        </section>

        {{-- Como Funciona e Demonstração do Pedido Container (Ticket 04) --}}
        <div id="how-it-works-slot"></div>

        {{-- Tabela de Preços e Cupom Container (Ticket 05) --}}
        <div id="pricing-slot"></div>

        {{-- FAQ, CTA Final e Rodapé Container (Ticket 06) --}}
        <div id="closing-slot"></div>
    </main>

    <!-- Inicialização dos ícones Lucide e Interatividade Mobile -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }

            // Menu Mobile Toggle
            const menuBtn = document.getElementById('mobile-menu-btn');
            const mobileMenu = document.getElementById('mobile-menu');
            const menuIcon = document.getElementById('menu-icon');
            const closeIcon = document.getElementById('close-icon');

            if (menuBtn && mobileMenu) {
                menuBtn.addEventListener('click', function () {
                    const isHidden = mobileMenu.classList.contains('hidden');
                    if (isHidden) {
                        mobileMenu.classList.remove('hidden');
                        if (menuIcon) menuIcon.classList.add('hidden');
                        if (closeIcon) closeIcon.classList.remove('hidden');
                    } else {
                        mobileMenu.classList.add('hidden');
                        if (menuIcon) menuIcon.classList.remove('hidden');
                        if (closeIcon) closeIcon.classList.add('hidden');
                    }
                });
            }
        });
    </script>
</body>
</html>
