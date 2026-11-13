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

    {{-- Shell Semântico da Landing Page --}}
    <main id="app" class="relative min-h-screen overflow-x-hidden">
        {{-- Shell Base: Placeholder pronto para os blocos da Landing Page --}}
        <div id="shell-container" class="relative mx-auto flex min-h-screen flex-col justify-between">
            {{-- Header/Navbar Container (Ticket 02) --}}
            <div id="header-slot"></div>

            {{-- Hero Section Container (Ticket 02) --}}
            <div id="hero-slot"></div>

            {{-- Métricas, Funcionalidades e Comparativo Container (Ticket 03) --}}
            <div id="features-slot"></div>

            {{-- Como Funciona e Demonstração do Pedido Container (Ticket 04) --}}
            <div id="how-it-works-slot"></div>

            {{-- Tabela de Preços e Cupom Container (Ticket 05) --}}
            <div id="pricing-slot"></div>

            {{-- FAQ, CTA Final e Rodapé Container (Ticket 06) --}}
            <div id="closing-slot"></div>
        </div>
    </main>

    <!-- Inicialização dos ícones Lucide -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
    </script>
</body>
</html>
