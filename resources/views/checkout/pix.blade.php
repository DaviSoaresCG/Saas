<!DOCTYPE html>
<html lang="pt-BR" class="scroll-smooth">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Pagamento Pix | ZapCatálogo</title>
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

        <!-- Alpine.js -->
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

        <!-- Lucide Icons -->
        <script src="https://unpkg.com/lucide@latest"></script>

        <style>
            [x-cloak] { display: none !important; }
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
        <main class="flex-grow pt-8 md:pt-12 pb-20 px-4 sm:px-6 lg:px-8 max-w-2xl mx-auto w-full">

            <!-- Cabeçalho da Página / Status -->
            <div class="text-center mb-6">
                <div class="flex justify-center mb-4">
                    <span class="inline-flex items-center gap-2 rounded-full border border-[#71834f]/25 bg-[#f4f8e6] px-3.5 py-1 text-xs font-semibold text-[#546830]">
                        <span class="relative flex size-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#71834f] opacity-75"></span>
                            <span class="relative inline-flex rounded-full size-2 bg-[#71834f]"></span>
                        </span>
                        <span>Aguardando Pagamento Pix</span>
                    </span>
                </div>
                <h1 class="font-serif text-3xl sm:text-4xl md:text-5xl font-normal text-center text-[#37322f] mb-3 tracking-tight">
                    Pagamento via Pix
                </h1>
                <p class="text-center text-[#605a57] max-w-lg mx-auto text-sm sm:text-base leading-relaxed">
                    Escaneie o QR Code ou copie o código abaixo para ativar sua assinatura do <strong class="text-[#37322f] font-semibold">{{ $payment['plan_name'] }}</strong>.
                </p>
            </div>

            <!-- Card Central do QR Code -->
            <div class="bg-white rounded-3xl border border-[#37322f]/10 p-6 sm:p-8 shadow-sm mb-6 flex flex-col items-center justify-center text-center">
                <div class="mb-5">
                    <span class="text-xs text-[#605a57] uppercase font-semibold tracking-wider">Valor a Pagar</span>
                    <div class="font-serif text-4xl sm:text-5xl font-normal text-[#37322f] mt-1 tracking-tight">
                        R$ {{ number_format($payment['amount'], 2, ',', '.') }}
                    </div>
                    <div class="inline-flex items-center gap-1.5 mt-2 px-3 py-1 rounded-full text-xs font-medium bg-[#eeece8] text-[#605a57]">
                        <i data-lucide="check-circle" class="size-3.5 text-[#71834f]"></i>
                        <span>{{ $payment['plan_name'] }}</span>
                    </div>
                </div>

                <!-- Container do QR Code em Moldura de Alto Contraste -->
                <div class="p-4 bg-white rounded-2xl shadow-md border border-[#37322f]/15 hover:shadow-lg transition-all duration-300">
                    @if (!empty($payment['qr_code_base64']))
                        <img src="data:image/png;base64,{{ $payment['qr_code_base64'] }}" alt="Pix QR Code" class="w-[200px] h-[200px] rounded-lg" />
                    @elseif (!empty($payment['qr_code_url']))
                        <img src="{{ $payment['qr_code_url'] }}" alt="Pix QR Code" class="w-[200px] h-[200px] rounded-lg" />
                    @else
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ urlencode($payment['qr_code'] ?? '') }}" alt="Pix QR Code" class="w-[200px] h-[200px] rounded-lg" />
                    @endif
                </div>

                <div class="mt-5 flex items-center gap-2 text-xs text-[#605a57] bg-[#eeece8]/60 px-3.5 py-1.5 rounded-full">
                    <i data-lucide="clock" class="size-3.5 text-[#71834f]"></i>
                    <span>O QR Code expira em 30 minutos</span>
                </div>
            </div>

            <!-- Card Pix Copia e Cola com Ação em 1 Clique -->
            <div class="bg-white rounded-2xl border border-[#37322f]/10 p-5 sm:p-6 shadow-sm mb-6" x-data="{ copied: false }">
                <div class="flex items-center justify-between mb-2">
                    <label for="pixCode" class="block text-xs sm:text-sm font-semibold text-[#37322f] flex items-center gap-1.5">
                        <i data-lucide="copy" class="size-4 text-[#71834f]"></i>
                        <span>Pix Copia e Cola</span>
                    </label>
                    <span class="text-[11px] text-[#605a57]">Código de pagamento instantâneo</span>
                </div>

                <div class="flex flex-col sm:flex-row gap-2 mt-2">
                    <input type="text" id="pixCode" readonly value="{{ $payment['qr_code'] ?? '' }}"
                        class="flex-1 px-4 py-3 bg-[#f7f5f3] border border-[#37322f]/15 rounded-xl text-xs text-[#37322f] font-mono focus:outline-none focus:bg-white focus:border-[#37322f] select-all truncate">
                    <button @click="
                        navigator.clipboard.writeText(document.getElementById('pixCode').value);
                        copied = true;
                        setTimeout(() => copied = false, 2500)
                    " class="px-5 py-3 rounded-xl bg-[#37322f] hover:bg-[#514a45] text-white font-semibold text-xs transition-all cursor-pointer whitespace-nowrap shadow-sm flex items-center justify-center gap-1.5">
                        <span x-show="!copied" class="flex items-center gap-1.5">
                            <i data-lucide="copy" class="size-3.5 text-[#d7f56b]"></i>
                            <span>Copiar código</span>
                        </span>
                        <span x-show="copied" x-cloak class="text-[#d7f56b] font-bold flex items-center gap-1.5">
                            <i data-lucide="check" class="size-3.5"></i>
                            <span>Copiado!</span>
                        </span>
                    </button>
                </div>
            </div>

            <!-- Card de Instruções para Pagamento -->
            <div class="bg-white rounded-2xl border border-[#37322f]/10 p-5 sm:p-6 shadow-sm mb-6">
                <h4 class="text-xs sm:text-sm font-semibold text-[#37322f] mb-3 flex items-center gap-2">
                    <span class="flex size-6 items-center justify-center rounded-lg bg-[#f4f8e6] text-[#71834f]">
                        <i data-lucide="info" class="size-3.5"></i>
                    </span>
                    <span>Instruções para pagamento:</span>
                </h4>
                <ol class="list-decimal list-inside text-xs sm:text-sm text-[#605a57] space-y-2 leading-relaxed">
                    <li>Abra o aplicativo do seu banco ou carteira digital no celular.</li>
                    <li>Escolha a opção <strong class="text-[#37322f]">Pix</strong> e selecione <strong class="text-[#37322f]">Ler QR Code</strong> (ou <strong class="text-[#37322f]">Pix Copia e Cola</strong>).</li>
                    <li>Aponte a câmera para a imagem ou cole o código copiado acima.</li>
                    <li>Confirme os dados e finalize. Sua assinatura será <strong class="text-[#37322f]">ativada automaticamente</strong> assim que o pagamento for confirmado.</li>
                </ol>
            </div>

            @if (!empty($payment['invoice_url']))
            <!-- Opção Alternativa de Cartão de Crédito -->
            <div class="mb-6 p-5 sm:p-6 rounded-2xl bg-[#eeece8]/60 border border-[#37322f]/10 text-center shadow-sm">
                <div class="flex items-center justify-center gap-2 text-sm font-semibold text-[#37322f] mb-1">
                    <i data-lucide="credit-card" class="size-4 text-[#71834f]"></i>
                    <span>Prefere pagar com Cartão de Crédito?</span>
                </div>
                <p class="text-xs text-[#605a57] mb-4 leading-relaxed max-w-md mx-auto">
                    Pague com segurança no Checkout do Asaas. A cobrança da assinatura será processada no seu cartão.
                </p>
                <a href="{{ $payment['invoice_url'] }}" target="_blank" class="inline-flex items-center justify-center gap-2 w-full py-3 px-4 rounded-xl bg-white hover:bg-[#eeece8] text-[#37322f] font-semibold text-xs transition-all border border-[#37322f]/15 shadow-sm">
                    <span>Abrir Checkout de Cartão no Asaas</span>
                    <i data-lucide="external-link" class="size-3.5 text-[#605a57]"></i>
                </a>
            </div>
            @endif

            @if(config('app.env') === 'local' || config('services.asaas.env') === 'sandbox')
            <!-- Simulação Sandbox / Local (Apenas em Desenvolvimento) -->
            <div class="p-6 rounded-2xl border border-amber-300 bg-amber-50/70 text-center mb-6 shadow-sm">
                <div class="flex items-center justify-center gap-2 text-amber-900 mb-2">
                    <i data-lucide="zap" class="size-4 text-amber-600"></i>
                    <span class="text-xs font-bold uppercase tracking-wider">Simulação Sandbox / Local</span>
                </div>
                <p class="text-xs text-amber-800 mb-4 leading-relaxed max-w-md mx-auto">
                    Você está em ambiente de desenvolvimento. Você <strong>NÃO precisa transferir dinheiro real</strong>. Clique no botão abaixo para aprovar instantaneamente o pagamento e ativar sua loja.
                </p>

                <form action="{{ route('pagamento.simulate') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full py-3 px-4 rounded-xl bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold text-xs shadow-sm transition-all cursor-pointer flex items-center justify-center gap-2">
                        <i data-lucide="check-circle" class="size-4"></i>
                        <span>Simular Pagamento Aprovado</span>
                    </button>
                </form>
            </div>
            @endif

            <!-- Ação de Retorno -->
            <div class="text-center mb-6">
                <a href="{{ route('pagamento.pending') }}" class="inline-flex items-center gap-1.5 text-xs text-[#605a57] hover:text-[#37322f] transition-colors font-medium">
                    <i data-lucide="arrow-left" class="size-3.5"></i>
                    <span>Voltar para seleção de planos</span>
                </a>
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

            // Polling para checar o status do pagamento Pix em tempo real
            function checkPaymentStatus() {
                fetch("{{ route('pagamento.status') }}")
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'approved') {
                            if (pollInterval) {
                                clearInterval(pollInterval);
                                pollInterval = null;
                            }
                            window.location.href = "{{ route('subscription.success') }}";
                        }
                    })
                    .catch(err => console.error("Erro ao verificar status do pagamento:", err));
            }

            // Inicia a verificação a cada 3 segundos
            pollInterval = setInterval(checkPaymentStatus, 3000);
        </script>
    </body>
</html>
