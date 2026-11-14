@props([
    'title' => null,
    'eyebrow' => 'Que bom ter você de volta',
    'heading' => 'Entre na sua conta.',
    'subheading' => 'Acesse seu painel e continue cuidando da sua loja.',
])

<!DOCTYPE html>
<html lang="pt-BR" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ !empty($title) ? "{$title} | ZapCatálogo" : 'ZapCatálogo — Sua loja online no WhatsApp' }}</title>
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

    <!-- Vite Assets (Alpine.js, mask, css) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

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
    <main class="grid min-h-screen bg-[#f7f5f3] lg:grid-cols-[1fr_1fr]">
        {{-- Coluna Esquerda: Formulário --}}
        <section class="flex min-h-screen flex-col px-6 py-7 sm:px-10 lg:px-14">
            {{-- BrandMark no topo --}}
            <a href="{{ route('home') }}" class="flex w-fit items-center gap-2.5 font-semibold tracking-tight text-[#37322f]" aria-label="ZapCatálogo, início">
                <span class="flex size-9 items-center justify-center rounded-xl bg-[#37322f] text-[#d7f56b] shadow-sm">
                    <i data-lucide="shopping-bag" class="size-[18px]"></i>
                </span>
                <span class="text-[17px]">zap<span class="font-normal">catálogo</span></span>
            </a>

            {{-- Container Central do Formulário --}}
            <div class="mx-auto flex w-full max-w-[520px] flex-1 flex-col justify-center py-10 sm:py-12">
                {{-- Botão Voltar --}}
                <a class="mb-8 inline-flex w-fit items-center gap-2 text-sm font-medium text-[#77706a] transition hover:text-[#37322f]">
                    <i data-lucide="arrow-left" class="size-3.5"></i>
                    <button type="submit" onclick="history.back()">Voltar</button>
                </a>

                {{-- Eyebrow, Título e Subtítulo --}}
                <span class="text-[11px] font-semibold uppercase tracking-[.18em] text-[#849451]">{{ $eyebrow }}</span>
                <h1 class="mt-3 font-serif text-4xl leading-tight tracking-[-.03em] text-[#37322f] sm:text-5xl">{{ $heading }}</h1>
                <p class="mt-3 text-sm leading-6 text-[#716a64]">{{ $subheading }}</p>

                {{-- Slot do Formulário --}}
                <div class="mt-6">
                    {{ $slot }}
                </div>

                {{-- Aviso de Segurança --}}
                <p class="mt-8 text-center text-[11px] leading-5 text-[#938b83]">
                    Seus dados de acesso são protegidos pelo sistema seguro de autenticação do ZapCatálogo.
                </p>
            </div>

            {{-- Rodapé da coluna esquerda --}}
            <p class="text-center text-[11px] text-[#a19a93]">
                © {{ date('Y') }} ZapCatálogo
            </p>
        </section>

        {{-- Coluna Direita: Imagem Editorial Desktop --}}
        <aside class="relative hidden overflow-hidden bg-[#37322f] lg:flex lg:min-h-screen lg:items-end">
            <img src="https://img.magnific.com/fotos-gratis/homem-bonito-elegante-e-barbudo-posando-contra-a-parede-branca_176420-33011.jpg?semt=ais_hybrid&w=740&q=80" alt="Cliente sorrindo enquanto faz compras pelo celular" class="absolute inset-0 size-full object-cover object-[36%_center]">
            <div class="absolute inset-0 bg-gradient-to-t from-[#25231f]/85 via-[#25231f]/20 to-[#25231f]/10"></div>
            <div class="relative max-w-[560px] px-10 pb-12 text-white xl:px-16 xl:pb-16">
                <span class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-3 py-1.5 text-[11px] font-medium text-white backdrop-blur-sm">
                    <span class="size-2 rounded-full bg-[#d7f56b]"></span>
                    <span>Simples para você. Fácil para seus clientes.</span>
                </span>
                <h2 class="mt-5 font-serif text-4xl leading-tight tracking-[-.025em] text-white xl:text-5xl">
                    Uma vitrine que transforma visitas em conversas.
                </h2>
                <p class="mt-4 max-w-[440px] text-sm leading-6 text-white/80">
                    Organize seu catálogo e receba cada pedido pronto para continuar no WhatsApp.
                </p>
            </div>
        </aside>
    </main>

    <!-- Inicialização Lucide Icons -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
    </script>
</body>
</html>

