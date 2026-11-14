<!DOCTYPE html>
<html lang="pt-BR" class="scroll-smooth">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Processando Assinatura | ZapCatálogo</title>
        <link rel="icon" href="{{ asset('favicon.ico') }}">

        <!-- Google Fonts: Instrument Serif & Inter -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

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
            /* Scrollbar suave Brillance */
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
    <body class="min-h-screen bg-[#f7f5f3] font-sans text-[#37322f] selection:bg-[#d7f56b] selection:text-[#37322f] antialiased flex flex-col relative">

        <!-- Header / Navbar Superior Simplificado -->
        <header class="sticky top-0 z-40 border-b border-[#37322f]/10 bg-[#f7f5f3]/90 backdrop-blur-xl transition-all">
            <div class="mx-auto flex h-[72px] max-w-[1160px] items-center justify-between px-5 md:px-8">
                <!-- BrandMark ZapCatálogo -->
                <a href="{{ route('home') }}" class="flex items-center gap-2.5 font-semibold tracking-tight text-[#37322f]" aria-label="ZapCatálogo, início">
                    <span class="flex size-9 items-center justify-center rounded-xl bg-[#37322f] text-[#d7f56b] shadow-sm">
                        <i data-lucide="shopping-bag" class="size-[18px]"></i>
                    </span>
                    <span class="text-[17px]">zap<span class="font-normal">catálogo</span></span>
                </a>

                <!-- Ação de Logout Discreta -->
                <div>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="inline-flex items-center gap-2 rounded-full border border-[#37322f]/15 bg-white px-3.5 py-1.5 text-xs font-medium text-[#605a57] transition hover:bg-[#eeece8] hover:text-[#37322f] shadow-sm cursor-pointer">
                            <i data-lucide="log-out" class="size-3.5"></i>
                            <span>Sair da conta</span>
                        </button>
                    </form>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1 relative flex items-center justify-center px-4 py-12 sm:py-20 w-full max-w-xl mx-auto">
            <div class="w-full text-center">

                <!-- Badge de Status -->
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-[#f4f8e6] border border-[#71834f]/30 text-[#546830] text-xs font-semibold mb-6 shadow-sm">
                    <span class="relative flex size-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#71834f] opacity-75"></span>
                        <span class="relative inline-flex rounded-full size-2 bg-[#71834f]"></span>
                    </span>
                    <span>Confirmando pagamento no Asaas</span>
                </div>

                <!-- Card Central de Processamento -->
                <div class="bg-white rounded-3xl border border-[#37322f]/10 p-8 sm:p-10 shadow-sm text-center relative overflow-hidden w-full">
                    <!-- Loader Animado -->
                    <div class="mx-auto mb-6 relative flex size-20 items-center justify-center">
                        <div class="absolute inset-0 rounded-2xl bg-[#f4f8e6] border border-[#71834f]/20 animate-pulse"></div>
                        <div class="relative flex size-14 items-center justify-center rounded-xl bg-white border border-[#37322f]/10 shadow-sm">
                            <i data-lucide="loader-2" class="size-7 text-[#71834f] animate-spin"></i>
                        </div>
                    </div>

                    <h1 class="font-serif text-3xl sm:text-4xl font-normal text-[#37322f] mb-3 tracking-tight">
                        Processando sua assinatura
                    </h1>
                    <p class="text-sm sm:text-base text-[#605a57] leading-relaxed mb-4">
                        Estamos aguardando a confirmação do pagamento pelo gateway Asaas. Para pagamentos via Pix a confirmação costuma ocorrer em poucos segundos; para pagamentos via cartão de crédito, a compensação pode levar alguns instantes durante a validação da operadora.
                    </p>
                    <div class="rounded-xl bg-[#f7f5f3] border border-[#37322f]/10 p-3.5 text-xs text-[#716a64] flex items-center justify-center gap-2">
                        <i data-lucide="sparkles" class="size-4 text-[#849451] flex-shrink-0"></i>
                        <span>Você será redirecionado automaticamente assim que a confirmação for recebida.</span>
                    </div>
                </div>

                <!-- Selos de Confiança -->
                <div class="mt-8 flex flex-wrap items-center justify-center gap-6 text-xs font-medium text-[#605a57]">
                    <span class="inline-flex items-center gap-1.5">
                        <i data-lucide="shield-check" class="size-4 text-[#71834f]"></i>
                        <span>Conexão segura</span>
                    </span>
                    <span class="inline-flex items-center gap-1.5">
                        <i data-lucide="zap" class="size-4 text-amber-500"></i>
                        <span>Ativação automática</span>
                    </span>
                    <span class="inline-flex items-center gap-1.5">
                        <i data-lucide="lock" class="size-4 text-[#605a57]"></i>
                        <span>Criptografia 256-bit</span>
                    </span>
                </div>

                <!-- Retorno para Opções de Pagamento -->
                <div class="mt-6 text-center">
                    <a href="{{ route('pagamento.pending') }}" class="inline-flex items-center gap-1.5 text-xs text-[#605a57] hover:text-[#37322f] transition-colors font-medium">
                        <i data-lucide="arrow-left" class="size-3.5"></i>
                        <span>Ver opções de pagamento</span>
                    </a>
                </div>

            </div>
        </main>

        <!-- Footer Padrão Brillance -->
        <footer class="border-t border-[#37322f]/10 bg-[#eeece8]/40 py-8 text-center text-xs text-[#716a64] mt-auto">
            <div class="max-w-4xl mx-auto px-4 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-2 font-semibold text-[#37322f]">
                    <span class="flex size-6 items-center justify-center rounded-lg bg-[#37322f] text-[#d7f56b]">
                        <i data-lucide="shopping-bag" class="size-3"></i>
                    </span>
                    <span>zap<span class="font-normal">catálogo</span></span>
                </div>
                <p>© {{ date('Y') }} ZapCatálogo Tecnologia. Pagamentos processados com segurança via Asaas.</p>
                <div class="flex items-center gap-2 text-[#605a57]">
                    <i data-lucide="shield-check" class="size-3.5 text-[#71834f]"></i>
                    <span>Ambiente Seguro 256-bit</span>
                </div>
            </div>
        </footer>

        <!-- Scripts de Polling e Lucide -->
        <script>
            lucide.createIcons();

            let pollInterval = null;

            // Polling automático a cada 3 segundos verificando aprovação do pagamento
            function checkPaymentStatus() {
                fetch("{{ route('pagamento.status') }}")
                    .then(response => {
                        if (!response.ok) {
                            if (response.status === 401 && pollInterval) {
                                clearInterval(pollInterval);
                                pollInterval = null;
                            }
                            return null;
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data && data.status === 'approved') {
                            if (pollInterval) {
                                clearInterval(pollInterval);
                                pollInterval = null;
                            }
                            window.location.href = "{{ route('subscription.success') }}";
                        }
                    })
                    .catch(err => console.error('Erro ao checar status:', err));
            }

            pollInterval = setInterval(checkPaymentStatus, 3000);
        </script>
    </body>
</html>
