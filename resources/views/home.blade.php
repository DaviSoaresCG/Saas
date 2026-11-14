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
            <div class="hidden items-center gap-3 md:flex">
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
            <div class="flex items-center md:hidden">
                <button type="button" id="mobile-menu-btn" aria-label="Abrir menu" class="inline-flex size-10 items-center justify-center rounded-xl border border-[#37322f]/10 text-[#37322f] transition hover:bg-white">
                    <i data-lucide="menu" class="size-5" id="menu-icon"></i>
                    <i data-lucide="x" class="hidden size-5" id="close-icon"></i>
                </button>
            </div>
        </div>

        {{-- Mobile Dropdown Menu --}}
        <div id="mobile-menu" class="hidden border-b border-[#37322f]/10 bg-[#f7f5f3] px-5 py-4 md:hidden">
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

        {{-- Como Funciona (Ticket 04) --}}
        <section id="como-funciona" class="px-5 py-20 md:px-8 md:py-28">
            <div class="mx-auto max-w-[1160px]">
                <div class="mx-auto mb-12 max-w-[650px] text-center">
                    <span class="text-[11px] font-semibold uppercase tracking-[.18em] text-[#849451]">Comece sem complicação</span>
                    <h2 class="mt-3 font-serif text-4xl leading-tight tracking-[-.025em] text-[#37322f] md:text-5xl">Do catálogo ao pedido em 3 passos.</h2>
                    <p class="mt-4 text-sm leading-6 text-[#716a64] md:text-base">Sem instalar aplicativos. Sem precisar entender de tecnologia. É só colocar sua loja no ar.</p>
                </div>

                <div class="grid gap-8 md:grid-cols-3">
                    {{-- Passo 1 --}}
                    <article class="relative flex flex-col items-center text-center">
                        <div class="relative flex size-[72px] items-center justify-center rounded-full border border-[#37322f]/10 bg-white text-[#71834f] shadow-[0_6px_22px_rgba(55,50,47,.05)]">
                            <i data-lucide="package" class="size-6"></i>
                            <span class="absolute -right-1 -top-1 flex size-6 items-center justify-center rounded-full bg-[#37322f] text-[10px] font-medium text-white">01</span>
                        </div>
                        <div class="absolute left-[calc(50%+50px)] top-9 hidden h-px w-[calc(100%-100px)] border-t border-dashed border-[#bcb8ae] md:block"></div>
                        <h3 class="mt-5 text-base font-semibold text-[#37322f]">Monte sua vitrine</h3>
                        <p class="mt-2 max-w-[290px] text-sm leading-6 text-[#716a64]">Cadastre seus produtos, adicione sua logo e escolha as cores da sua marca.</p>
                    </article>

                    {{-- Passo 2 --}}
                    <article class="relative flex flex-col items-center text-center">
                        <div class="relative flex size-[72px] items-center justify-center rounded-full border border-[#37322f]/10 bg-white text-[#71834f] shadow-[0_6px_22px_rgba(55,50,47,.05)]">
                            <i data-lucide="globe" class="size-6"></i>
                            <span class="absolute -right-1 -top-1 flex size-6 items-center justify-center rounded-full bg-[#37322f] text-[10px] font-medium text-white">02</span>
                        </div>
                        <div class="absolute left-[calc(50%+50px)] top-9 hidden h-px w-[calc(100%-100px)] border-t border-dashed border-[#bcb8ae] md:block"></div>
                        <h3 class="mt-5 text-base font-semibold text-[#37322f]">Compartilhe seu link</h3>
                        <p class="mt-2 max-w-[290px] text-sm leading-6 text-[#716a64]">Coloque seu endereço exclusivo na bio do Instagram, TikTok ou WhatsApp Business.</p>
                    </article>

                    {{-- Passo 3 --}}
                    <article class="relative flex flex-col items-center text-center">
                        <div class="relative flex size-[72px] items-center justify-center rounded-full border border-[#37322f]/10 bg-white text-[#71834f] shadow-[0_6px_22px_rgba(55,50,47,.05)]">
                            <i data-lucide="message-circle" class="size-6"></i>
                            <span class="absolute -right-1 -top-1 flex size-6 items-center justify-center rounded-full bg-[#37322f] text-[10px] font-medium text-white">03</span>
                        </div>
                        <h3 class="mt-5 text-base font-semibold text-[#37322f]">Receba e confirme</h3>
                        <p class="mt-2 max-w-[290px] text-sm leading-6 text-[#716a64]">Seu cliente monta o pedido. Você recebe tudo organizado no WhatsApp e combina o pagamento.</p>
                    </article>
                </div>
            </div>
        </section>

        {{-- Banner Shopper Story (Ticket 04) --}}
        <section aria-label="Compre com praticidade pelo celular" class="px-5 pb-20 md:px-8 md:pb-28">
            <div class="relative mx-auto flex min-h-[360px] max-w-[1160px] items-center overflow-hidden rounded-[28px] bg-[#37322f] shadow-lg md:min-h-[430px]">
                <img src="https://hebbkx1anhila5yf.public.blob.vercel-storage.com/mulher_comprando-FAop2IuzUjyZMb0XWzE60Z2JJCNfuL.jpg" alt="Cliente sorrindo enquanto faz compras pelo celular" class="absolute inset-0 size-full object-cover object-[35%_center]">
                <div class="absolute inset-0 bg-gradient-to-r from-[#25231f]/10 via-[#25231f]/35 to-[#25231f]/85"></div>
                <div class="relative ml-auto max-w-[520px] px-7 py-12 text-white md:px-14 md:py-16">
                    <span class="text-[11px] font-semibold uppercase tracking-[.18em] text-[#d7f56b]">Uma compra fácil, do começo ao fim</span>
                    <h2 class="mt-4 font-serif text-4xl leading-tight tracking-[-.025em] md:text-5xl">A sua vitrine na palma da mão.</h2>
                    <p class="mt-4 max-w-[400px] text-sm leading-6 text-white/80 md:text-base">Seus clientes exploram os produtos, montam o pedido e falam com você pelo WhatsApp — tudo pelo celular, sem complicação.</p>
                    <a href="#demonstracao" class="mt-6 inline-flex items-center gap-2 text-sm font-semibold text-[#d7f56b] transition hover:gap-3">
                        <span>Veja como funciona</span>
                        <i data-lucide="arrow-right" class="size-4"></i>
                    </a>
                </div>
            </div>
        </section>

        {{-- Demonstração Interativa do Pedido no WhatsApp (Ticket 04) --}}
        <section id="demonstracao" class="px-5 pb-20 md:px-8 md:pb-28">
            <div class="mx-auto grid max-w-[1160px] items-center gap-8 overflow-hidden rounded-[28px] border border-[#37322f]/10 bg-[#eaece3] p-6 md:grid-cols-[.9fr_1.1fr] md:p-12">
                <div>
                    <span class="inline-flex items-center gap-2 rounded-full bg-white/70 px-3 py-1.5 text-[11px] font-medium text-[#69784c] shadow-sm">
                        <i data-lucide="message-circle" class="size-3.5"></i>
                        <span>Checkout direto no WhatsApp</span>
                    </span>
                    <h2 class="mt-5 max-w-[430px] font-serif text-4xl leading-tight tracking-[-.025em] text-[#37322f] md:text-[48px]">
                        O pedido chega. Você só precisa responder.
                    </h2>
                    <p class="mt-4 max-w-[410px] text-sm leading-6 text-[#625d56]">
                        Seu cliente escolhe produtos, variações e quantidades. O ZapCatálogo monta a mensagem com os itens, o total e os dados do cliente.
                    </p>
                    <div class="mt-6 flex flex-col gap-3 text-sm text-[#514b46]">
                        <span class="flex items-center gap-2">
                            <i data-lucide="check" class="size-4 text-[#839b50]"></i> Número do pedido automático
                        </span>
                        <span class="flex items-center gap-2">
                            <i data-lucide="check" class="size-4 text-[#839b50]"></i> Produtos e variações organizados
                        </span>
                        <span class="flex items-center gap-2">
                            <i data-lucide="check" class="size-4 text-[#839b50]"></i> Total calculado para você
                        </span>
                    </div>
                    <a href="#precos" class="mt-7 inline-flex items-center gap-2 text-sm font-semibold text-[#37322f] hover:text-[#71834f] transition">
                        <span>Quero vender assim</span>
                        <i data-lucide="arrow-right" class="size-4"></i>
                    </a>
                </div>

                <div class="rounded-2xl border border-white/70 bg-[#e7e4dc] p-4 md:p-6 shadow-sm">
                    <div class="mx-auto max-w-[420px] overflow-hidden rounded-[20px] bg-[#efeae0] shadow-[0_18px_45px_rgba(55,50,47,.12)]">
                        <div class="flex items-center gap-3 bg-[#f8f6f1] px-4 py-3 border-b border-[#37322f]/5">
                            <div class="flex size-9 items-center justify-center rounded-full bg-[#d9e2cb] text-[#60764b]">
                                <i data-lucide="shopping-bag" class="size-4"></i>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-[#37322f]">Minha Boutique</p>
                                <p class="text-[10px] text-[#817971]">Catálogo online</p>
                            </div>
                            <span class="ml-auto rounded-full bg-[#edf0e7] px-2 py-1 text-[10px] font-medium text-[#69784c]">Pedido #1042</span>
                        </div>

                        <div class="flex flex-col gap-3 p-4">
                            <div id="order-message-preview" class="max-w-[90%] self-end rounded-2xl rounded-tr-sm bg-white p-3 text-[11px] leading-[1.8] text-[#37322f] shadow-sm text-left">
                                <strong>Olá! Gostaria de fazer o pedido #1042:</strong><br>
                                • 1x Camiseta Basic (Preta / G) — R$ 59,90<br>
                                • 2x Bermuda Jeans (Tamanho 42) — R$ 179,80<br>
                                <span class="text-[#a39a90]">————————————</span><br>
                                <strong>Total: R$ 239,70</strong><br>
                                Nome: João da Silva<br>
                                Aguardo confirmação!
                            </div>
                            <span class="self-end text-[9px] text-[#8e887e]">10:42 ✓✓</span>

                            <button type="button" id="copy-order-btn" class="flex items-center justify-center gap-2 rounded-xl border border-[#d8d4c9] bg-white/70 px-3 py-2 text-[10px] font-medium text-[#79736b] transition hover:bg-white hover:text-[#37322f]">
                                <i data-lucide="copy" class="size-3.5" id="copy-icon"></i>
                                <span id="copy-text">Pedido copiado e pronto para enviar</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Tabela de Preços e Cupom (Ticket 05) --}}
        <section id="precos" class="border-y border-[#37322f]/10 bg-white/55 px-5 py-20 md:px-8 md:py-28">
            <div class="mx-auto max-w-[1000px]">
                <div class="mx-auto mb-12 max-w-[650px] text-center">
                    <span class="text-[11px] font-semibold uppercase tracking-[.18em] text-[#849451]">Preço simples e transparente</span>
                    <h2 class="mt-3 font-serif text-4xl leading-tight tracking-[-.025em] text-[#37322f] md:text-5xl">Você vende. O lucro é seu.</h2>
                    <p class="mt-4 text-sm leading-6 text-[#716a64] md:text-base">Todos os recursos, sem comissão por pedido. Escolha a assinatura que faz sentido para a sua loja.</p>
                </div>

                {{-- Grid dos Planos --}}
                <div class="mx-auto grid max-w-[780px] gap-4 md:grid-cols-2">
                    {{-- Plano Mensal --}}
                    <article class="flex flex-col rounded-2xl border border-[#37322f]/12 bg-[#f9f8f6] p-6 md:p-7 shadow-sm">
                        <div class="text-sm font-semibold text-[#37322f]">Mensal</div>
                        <p class="mt-1 text-xs text-[#716a64]">Flexibilidade mês a mês</p>
                        <div class="mt-6 flex items-end gap-1">
                            <span class="mb-1 text-sm text-[#716a64]">R$</span>
                            <span class="font-serif text-5xl tracking-tight text-[#37322f]">29,90</span>
                            <span class="mb-1 text-sm text-[#716a64]">/ mês</span>
                        </div>

                        @auth
                            <a href="{{ route('pagamento.pending', ['plan' => 'monthly']) }}" class="mt-6 inline-flex h-11 items-center justify-center rounded-full border border-[#37322f]/20 text-sm font-medium text-[#37322f] transition hover:bg-[#37322f] hover:text-white">
                                Começar mensal
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="mt-6 inline-flex h-11 items-center justify-center rounded-full border border-[#37322f]/20 text-sm font-medium text-[#37322f] transition hover:bg-[#37322f] hover:text-white">
                                Começar mensal
                            </a>
                        @endauth

                        <ul class="mt-6 flex flex-col gap-3 border-t border-[#37322f]/10 pt-5 text-left">
                            <li class="flex items-center gap-2 text-xs text-[#605a57]">
                                <i data-lucide="check" class="size-3.5 text-[#839b50]"></i>
                                <span>Produtos ilimitados</span>
                            </li>
                            <li class="flex items-center gap-2 text-xs text-[#605a57]">
                                <i data-lucide="check" class="size-3.5 text-[#839b50]"></i>
                                <span>Pedidos pelo WhatsApp</span>
                            </li>
                            <li class="flex items-center gap-2 text-xs text-[#605a57]">
                                <i data-lucide="check" class="size-3.5 text-[#839b50]"></i>
                                <span>Subdomínio exclusivo</span>
                            </li>
                            <li class="flex items-center gap-2 text-xs text-[#605a57]">
                                <i data-lucide="check" class="size-3.5 text-[#839b50]"></i>
                                <span>Métricas de cliques</span>
                            </li>
                            <li class="flex items-center gap-2 text-xs text-[#605a57]">
                                <i data-lucide="check" class="size-3.5 text-[#839b50]"></i>
                                <span>0% de comissão</span>
                            </li>
                        </ul>
                    </article>

                    {{-- Plano Anual (Destaque Mais Popular) --}}
                    <article class="relative flex flex-col overflow-hidden rounded-2xl border border-[#37322f] bg-[#37322f] p-6 text-white shadow-[0_18px_45px_rgba(55,50,47,.17)] md:p-7">
                        <span class="absolute right-4 top-4 inline-flex items-center gap-1 rounded-full bg-[#d7f56b] px-2.5 py-1 text-[10px] font-semibold text-[#37322f]">
                            <i data-lucide="sparkles" class="size-3"></i> MAIS POPULAR
                        </span>
                        <div class="text-sm font-semibold">Anual</div>
                        <p class="mt-1 text-xs text-white/65">Dois meses grátis no ano</p>
                        <div class="mt-6 flex items-end gap-1">
                            <span class="mb-1 text-sm text-white/65">R$</span>
                            <span class="font-serif text-5xl tracking-tight">299</span>
                            <span class="mb-1 text-sm text-white/65">/ ano</span>
                        </div>
                        <p class="mt-1 text-xs text-white/65">Equivale a R$ 24,91 por mês · economize 17%</p>

                        @auth
                            <a href="{{ route('pagamento.pending', ['plan' => 'yearly']) }}" class="mt-5 inline-flex h-11 items-center justify-center gap-2 rounded-full bg-[#d7f56b] text-sm font-semibold text-[#37322f] transition hover:bg-[#e2ff88]">
                                <span>Escolher plano anual</span>
                                <i data-lucide="arrow-right" class="size-4"></i>
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="mt-5 inline-flex h-11 items-center justify-center gap-2 rounded-full bg-[#d7f56b] text-sm font-semibold text-[#37322f] transition hover:bg-[#e2ff88]">
                                <span>Escolher plano anual</span>
                                <i data-lucide="arrow-right" class="size-4"></i>
                            </a>
                        @endauth

                        <ul class="mt-6 flex flex-col gap-3 border-t border-white/15 pt-5 text-left">
                            <li class="flex items-center gap-2 text-xs text-white/85">
                                <i data-lucide="check" class="size-3.5 text-[#d7f56b]"></i>
                                <span>Produtos ilimitados</span>
                            </li>
                            <li class="flex items-center gap-2 text-xs text-white/85">
                                <i data-lucide="check" class="size-3.5 text-[#d7f56b]"></i>
                                <span>Pedidos pelo WhatsApp</span>
                            </li>
                            <li class="flex items-center gap-2 text-xs text-white/85">
                                <i data-lucide="check" class="size-3.5 text-[#d7f56b]"></i>
                                <span>Subdomínio exclusivo</span>
                            </li>
                            <li class="flex items-center gap-2 text-xs text-white/85">
                                <i data-lucide="check" class="size-3.5 text-[#d7f56b]"></i>
                                <span>Métricas de cliques</span>
                            </li>
                            <li class="flex items-center gap-2 text-xs text-white/85">
                                <i data-lucide="check" class="size-3.5 text-[#d7f56b]"></i>
                                <span>0% de comissão</span>
                            </li>
                        </ul>
                    </article>
                </div>

                {{-- Caixa de Cupom de Desconto --}}
                <div class="mx-auto mt-7 max-w-[780px] rounded-2xl border border-[#37322f]/10 bg-[#f7f5f3] p-5 shadow-sm text-left">
                    <label for="coupon-input" class="text-xs font-semibold text-[#514b46]">Tem um cupom de desconto?</label>
                    @auth
                        <form action="{{ route('counpons.validate') }}" method="POST" class="mt-2 flex gap-2">
                            @csrf
                            <input id="coupon-input" name="code" value="{{ old('code') }}" placeholder="Digite seu cupom" required maxlength="20" class="h-10 min-w-0 flex-1 rounded-lg border border-[#37322f]/15 bg-white px-3 text-sm text-[#37322f] outline-none placeholder:text-[#9b938c] focus:border-[#849451] transition">
                            <button type="submit" class="h-10 rounded-lg bg-[#37322f] px-5 text-xs font-semibold text-white hover:bg-[#514a45] transition">Aplicar</button>
                        </form>
                    @else
                        <form action="{{ route('login') }}" method="GET" class="mt-2 flex gap-2">
                            <input id="coupon-input" name="code" placeholder="Digite seu cupom" required maxlength="20" class="h-10 min-w-0 flex-1 rounded-lg border border-[#37322f]/15 bg-white px-3 text-sm text-[#37322f] outline-none placeholder:text-[#9b938c] focus:border-[#849451] transition">
                            <button type="submit" class="h-10 rounded-lg bg-[#37322f] px-5 text-xs font-semibold text-white hover:bg-[#514a45] transition">Aplicar</button>
                        </form>
                        <p class="mt-2 text-[11px] text-[#716a64]">Faça login ou crie sua conta para ativar seu cupom na assinatura.</p>
                    @endauth

                    @if(session('error'))
                        <p role="alert" class="mt-2 text-xs font-medium text-red-600">{{ session('error') }}</p>
                    @endif
                    @if(session('success'))
                        <p role="status" class="mt-2 text-xs font-medium text-[#487045]">{{ session('success') }}</p>
                    @endif
                </div>

                {{-- Aviso de Meios de Pagamento --}}
                <div class="mt-6 flex flex-col items-center justify-center gap-2 text-center text-xs text-[#716a64]">
                    <p class="inline-flex items-center gap-1.5 font-medium">
                        <i data-lucide="shield-check" class="size-4 text-[#839b50]"></i>
                        <span>Pagamento seguro via Pix ou cartão de crédito. Assinatura transparente, sem taxas escondidas.</span>
                    </p>
                </div>
            </div>
        </section>

        {{-- FAQ (Perguntas Frequentes - Ticket 06) --}}
        <section id="duvidas" class="px-5 py-20 md:px-8 md:py-28">
            <div class="mx-auto max-w-[760px]">
                <div class="mx-auto mb-12 text-center">
                    <span class="text-[11px] font-semibold uppercase tracking-[.18em] text-[#849451]">Perguntas frequentes</span>
                    <h2 class="mt-3 font-serif text-4xl leading-tight tracking-[-.025em] text-[#37322f] md:text-5xl">Ficou com alguma dúvida?</h2>
                    <p class="mt-4 text-sm leading-6 text-[#716a64] md:text-base">Respostas diretas para você começar com tranquilidade.</p>
                </div>

                <div class="flex flex-col divide-y divide-[#37322f]/10 border-y border-[#37322f]/10 text-left">
                    {{-- FAQ 1 --}}
                    <details class="group py-5">
                        <summary class="flex cursor-pointer list-none items-center justify-between gap-4 text-left text-sm font-semibold text-[#37322f] marker:hidden [&::-webkit-details-marker]:hidden">
                            <span>O ZapCatálogo cobra comissão sobre as vendas?</span>
                            <i data-lucide="chevron-down" class="size-4 shrink-0 text-[#7f8e59] transition-transform duration-200 group-open:rotate-180"></i>
                        </summary>
                        <p class="max-w-[650px] pt-3 text-sm leading-6 text-[#716a64]">
                            Não. Você paga somente a assinatura fixa e fica com 100% do valor das suas vendas. Não cobramos comissão por pedido.
                        </p>
                    </details>

                    {{-- FAQ 2 --}}
                    <details class="group py-5">
                        <summary class="flex cursor-pointer list-none items-center justify-between gap-4 text-left text-sm font-semibold text-[#37322f] marker:hidden [&::-webkit-details-marker]:hidden">
                            <span>Como o cliente paga pelo pedido?</span>
                            <i data-lucide="chevron-down" class="size-4 shrink-0 text-[#7f8e59] transition-transform duration-200 group-open:rotate-180"></i>
                        </summary>
                        <p class="max-w-[650px] pt-3 text-sm leading-6 text-[#716a64]">
                            O pedido chega organizado no seu WhatsApp. Você combina diretamente com o cliente a forma de pagamento e a entrega: Pix, cartão, dinheiro ou link.
                        </p>
                    </details>

                    {{-- FAQ 3 --}}
                    <details class="group py-5">
                        <summary class="flex cursor-pointer list-none items-center justify-between gap-4 text-left text-sm font-semibold text-[#37322f] marker:hidden [&::-webkit-details-marker]:hidden">
                            <span>Posso escolher o endereço da minha loja?</span>
                            <i data-lucide="chevron-down" class="size-4 shrink-0 text-[#7f8e59] transition-transform duration-200 group-open:rotate-180"></i>
                        </summary>
                        <p class="max-w-[650px] pt-3 text-sm leading-6 text-[#716a64]">
                            Sim. Você escolhe o nome do seu subdomínio, como sualoja.zapcatalogo.com.br, e compartilha o link nas suas redes.
                        </p>
                    </details>

                    {{-- FAQ 4 --}}
                    <details class="group py-5">
                        <summary class="flex cursor-pointer list-none items-center justify-between gap-4 text-left text-sm font-semibold text-[#37322f] marker:hidden [&::-webkit-details-marker]:hidden">
                            <span>O catálogo funciona bem no celular?</span>
                            <i data-lucide="chevron-down" class="size-4 shrink-0 text-[#7f8e59] transition-transform duration-200 group-open:rotate-180"></i>
                        </summary>
                        <p class="max-w-[650px] pt-3 text-sm leading-6 text-[#716a64]">
                            Sim. A experiência é pensada para celular, e você pode escolher exibir seus produtos em uma ou duas colunas.
                        </p>
                    </details>

                    {{-- FAQ 5 --}}
                    <details class="group py-5">
                        <summary class="flex cursor-pointer list-none items-center justify-between gap-4 text-left text-sm font-semibold text-[#37322f] marker:hidden [&::-webkit-details-marker]:hidden">
                            <span>Preciso instalar algum aplicativo?</span>
                            <i data-lucide="chevron-down" class="size-4 shrink-0 text-[#7f8e59] transition-transform duration-200 group-open:rotate-180"></i>
                        </summary>
                        <p class="max-w-[650px] pt-3 text-sm leading-6 text-[#716a64]">
                            Não. Seu catálogo e o painel funcionam diretamente no navegador, tanto para você quanto para seus clientes.
                        </p>
                    </details>
                </div>
            </div>
        </section>

        {{-- CTA Final de Alta Conversão (Ticket 06) --}}
        <section id="criar-conta" class="px-5 pb-16 md:px-8 md:pb-20">
            <div class="relative mx-auto max-w-[1160px] overflow-hidden rounded-[28px] bg-[#37322f] px-6 py-14 text-center shadow-2xl md:px-12 md:py-20">
                {{-- Halos de luz de fundo glow --}}
                <div class="pointer-events-none absolute -left-16 -top-24 size-64 rounded-full bg-[#a8c65c]/25 blur-[70px]"></div>
                <div class="pointer-events-none absolute -bottom-28 -right-10 size-72 rounded-full bg-[#dba982]/20 blur-[80px]"></div>

                <div class="relative mx-auto max-w-[680px]">
                    <span class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/5 px-3.5 py-1.5 text-[11px] font-medium text-white/75 backdrop-blur-sm">
                        <i data-lucide="clock-3" class="size-3.5 text-[#d7f56b]"></i>
                        <span>Sua loja começa com um link</span>
                    </span>

                    <h2 class="mt-5 font-serif text-4xl leading-tight tracking-[-.025em] text-white md:text-6xl">
                        Pronto para vender mais pelo WhatsApp?
                    </h2>

                    <p class="mx-auto mt-4 max-w-[480px] text-sm leading-6 text-white/70 md:text-base">
                        Crie sua vitrine, compartilhe seu link e receba pedidos sem abrir mão do seu lucro.
                    </p>

                    <div class="mt-7 flex justify-center">
                        @auth
                            <a href="#precos" class="inline-flex h-12 items-center gap-2 rounded-full bg-[#d7f56b] px-6 text-sm font-semibold text-[#37322f] shadow-lg transition hover:-translate-y-0.5 hover:bg-[#e2ff88]">
                                <span>Criar meu catálogo agora</span>
                                <i data-lucide="arrow-right" class="size-4"></i>
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="inline-flex h-12 items-center gap-2 rounded-full bg-[#d7f56b] px-6 text-sm font-semibold text-[#37322f] shadow-lg transition hover:-translate-y-0.5 hover:bg-[#e2ff88]">
                                <span>Criar meu catálogo agora</span>
                                <i data-lucide="arrow-right" class="size-4"></i>
                            </a>
                        @endauth
                    </div>

                    <p class="mt-4 text-[11px] text-white/50">
                        Sem comissão · Sem complicação · Cancele quando quiser
                    </p>
                </div>
            </div>
        </section>
    </main>

    {{-- Rodapé Semântico (Ticket 06) --}}
    <footer class="border-t border-[#37322f]/10 bg-[#f7f5f3] px-5 py-7 md:px-8">
        <div class="mx-auto flex max-w-[1160px] flex-col items-center justify-between gap-4 sm:flex-row">
            {{-- BrandMark no Rodapé --}}
            <a href="#inicio" class="flex items-center gap-2.5 font-semibold tracking-tight text-[#37322f]" aria-label="ZapCatálogo, início">
                <span class="flex size-8 items-center justify-center rounded-xl bg-[#37322f] text-[#d7f56b] shadow-sm">
                    <i data-lucide="shopping-bag" class="size-4"></i>
                </span>
                <span class="text-[16px]">zap<span class="font-normal">catálogo</span></span>
            </a>

            {{-- Direitos Autorais --}}
            <p class="text-xs text-[#857d76]">
                © {{ date('Y') }} ZapCatálogo. Todos os direitos reservados.
            </p>

            {{-- Links de Navegação do Rodapé --}}
            <nav class="flex gap-5 text-xs text-[#716a64]" aria-label="Links do rodapé">
                <a href="#recursos" class="transition hover:text-[#37322f]">Funcionalidades</a>
                <a href="#como-funciona" class="transition hover:text-[#37322f]">Como funciona</a>
                <a href="#precos" class="transition hover:text-[#37322f]">Preços</a>
                <a href="#duvidas" class="transition hover:text-[#37322f]">Dúvidas</a>
            </nav>
        </div>
    </footer>

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

            // Botão Interativo de Copiar Pedido Demo
            const copyBtn = document.getElementById('copy-order-btn');
            const copyText = document.getElementById('copy-text');

            if (copyBtn) {
                copyBtn.addEventListener('click', function () {
                    const textToCopy = `Olá! Gostaria de fazer o pedido #1042:
• 1x Camiseta Basic (Preta / G) — R$ 59,90
• 2x Bermuda Jeans (Tamanho 42) — R$ 179,80
————————————
Total: R$ 239,70
Nome: João da Silva
Aguardo confirmação!`;

                    if (navigator.clipboard && navigator.clipboard.writeText) {
                        navigator.clipboard.writeText(textToCopy).then(function () {
                            if (copyText) copyText.textContent = 'Pedido copiado para a área de transferência!';
                            copyBtn.classList.add('bg-[#dff1d5]', 'border-[#b8deb3]', 'text-[#487045]');
                            setTimeout(function () {
                                if (copyText) copyText.textContent = 'Pedido copiado e pronto para enviar';
                                copyBtn.classList.remove('bg-[#dff1d5]', 'border-[#b8deb3]', 'text-[#487045]');
                            }, 2500);
                        });
                    } else {
                        if (copyText) copyText.textContent = 'Pedido pronto para enviar!';
                    }
                });
            }
        });
    </script>
</body>
</html>
