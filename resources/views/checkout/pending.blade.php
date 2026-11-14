<!DOCTYPE html>
<html lang="pt-BR" class="scroll-smooth">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Ative seu Catálogo | ZapCatálogo</title>
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
      @keyframes shake {
        0%, 100% { transform: translateX(0); }
        20%, 60% { transform: translateX(-6px); }
        40%, 80% { transform: translateX(6px); }
      }
      .animate-shake {
        animation: shake 0.4s ease-in-out;
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
    <main class="flex-grow pt-8 md:pt-12 pb-20 px-4 sm:px-6 lg:px-8 max-w-4xl mx-auto w-full">
      
      <!-- Cabeçalho da Página / Hero -->
      <div class="text-center mb-8">
        <div class="flex justify-center mb-4">
          <span class="inline-flex items-center gap-2 rounded-full border border-[#71834f]/25 bg-[#f4f8e6] px-3.5 py-1 text-xs font-semibold text-[#546830]">
            <i data-lucide="sparkles" class="size-3.5 text-[#71834f]"></i>
            <span>Ativação de Assinatura</span>
          </span>
        </div>
        <h1 class="font-serif text-3xl sm:text-4xl md:text-5xl font-normal text-center text-[#37322f] mb-3 tracking-tight">
          Ative o seu Catálogo Digital
        </h1>
        <p class="text-center text-[#605a57] max-w-xl mx-auto text-sm sm:text-base leading-relaxed">
          Complete seus dados e escolha o plano ideal para começar a vender pelo WhatsApp hoje mesmo.
        </p>
      </div>

      <!-- Feedback de Erro de Sessão -->
      @if (session('error'))
        <div class="mb-8 p-4 rounded-2xl bg-red-50 border border-red-200 text-red-700 text-sm max-w-xl mx-auto flex items-center gap-3 shadow-sm animate-fade-in">
          <i data-lucide="alert-circle" class="size-5 text-red-500 flex-shrink-0"></i>
          <span>{{ session('error') }}</span>
        </div>
      @endif

      <!-- Stepper Visual Interativo -->
      <div class="flex items-center justify-center gap-3 mb-8">
        <div id="stepperStep1" class="flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-semibold bg-[#37322f] text-white shadow-sm transition-all duration-300">
          <span id="stepperStep1Badge" class="w-5 h-5 rounded-full bg-[#d7f56b] text-[#37322f] flex items-center justify-center text-[10px] font-bold">1</span>
          <span>Passo 1: Identificação</span>
        </div>
        <div class="w-8 h-px bg-[#37322f]/20"></div>
        <div id="stepperStep2" class="flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-semibold bg-white border border-[#37322f]/10 text-[#605a57] transition-all duration-300">
          <span id="stepperStep2Badge" class="w-5 h-5 rounded-full bg-[#eeece8] text-[#37322f] flex items-center justify-center text-[10px] font-bold">2</span>
          <span>Passo 2: Plano & Pagamento</span>
        </div>
      </div>

      <!-- Passo 1: Card de CPF/CNPJ com Validação em Tempo Real -->
      <div id="docInputCard" class="max-w-xl mx-auto mb-12 bg-white p-6 sm:p-7 rounded-2xl border border-[#37322f]/10 shadow-sm transition-all duration-300">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-2">
          <label for="docInput" class="block text-sm sm:text-base font-semibold text-[#37322f] flex items-center gap-2">
            <i data-lucide="shield-check" class="size-5 text-[#71834f]"></i>
            <span>Passo 1: CPF ou CNPJ do Titular</span>
            <span class="text-[11px] bg-red-50 text-red-700 border border-red-200 px-2 py-0.5 rounded-full font-medium">Obrigatório</span>
          </label>

          @if(config('app.env') === 'local' || config('services.asaas.env') === 'sandbox')
          <button type="button" id="btnGenTestCpf" class="text-xs text-[#71834f] hover:text-[#546830] font-semibold underline flex items-center gap-1 cursor-pointer transition-colors self-start sm:self-auto">
            <i data-lucide="zap" class="size-3.5 text-amber-500"></i>
            <span>Gerar CPF Válido (Teste)</span>
          </button>
          @endif
        </div>

        <p class="text-xs text-[#605a57] mb-4 leading-relaxed">
          Exigido pelas normas do Banco Central e pelo gateway Asaas para emissão de Pix e cobrança da assinatura.
        </p>

        <div class="relative">
          <input type="text" id="docInput"
            value="{{ auth()->user()->documento ?? old('documento') }}"
            placeholder="000.000.000-00 ou 00.000.000/0000-00"
            maxlength="18"
            autocomplete="off"
            class="w-full px-4 py-3 bg-[#f7f5f3] border border-[#37322f]/15 rounded-xl text-[#37322f] text-base font-medium tracking-wide focus:outline-none focus:bg-white focus:border-[#37322f] focus:ring-2 focus:ring-[#37322f]/10 placeholder:text-[#a09a95] transition-all">
          
          <div id="docValidIcon" class="absolute right-3.5 top-3.5 hidden">
            <i data-lucide="check-circle-2" class="size-5 text-emerald-600"></i>
          </div>
        </div>

        <div id="docFeedback" class="mt-2.5 text-xs flex items-center gap-1.5 transition-all hidden">
          <i data-lucide="alert-circle" id="docFeedbackIcon" class="size-4 flex-shrink-0"></i>
          <span id="docFeedbackText">Por favor, informe seu CPF ou CNPJ antes de prosseguir.</span>
        </div>
      </div>

      <!-- Passo 2: Seleção de Planos & Pagamento -->
      <div class="mb-6 text-center">
        <h2 class="font-serif text-2xl sm:text-3xl text-[#37322f] mb-1 flex items-center justify-center gap-2">
          <i data-lucide="sparkles" class="size-5 text-[#849451]"></i>
          <span>Passo 2: Escolha o seu Plano</span>
        </h2>
        <p class="text-xs sm:text-sm text-[#605a57]">Clique na forma de pagamento desejada para ativar seu acesso imediato.</p>
      </div>

      @php $selectedPlan = request('plan', 'monthly'); @endphp

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-4xl mx-auto items-stretch">
        
        <!-- Plano Mensal -->
        <div id="planCardMonthly" class="plan-card flex flex-col p-6 sm:p-8 rounded-2xl border {{ $selectedPlan === 'monthly' ? 'border-[#37322f] ring-2 ring-[#37322f]/15' : 'border-[#37322f]/15' }} bg-white hover:border-[#37322f]/40 transition-all duration-300 shadow-sm group hover:-translate-y-1">
          <div class="mb-5">
            <div class="flex items-center justify-between mb-2">
              <h3 class="text-xl sm:text-2xl font-bold text-[#37322f]">Plano Mensal</h3>
              <span class="text-[11px] font-semibold text-[#605a57] bg-[#eeece8] px-2.5 py-1 rounded-full">Sem Fidelidade</span>
            </div>
            <p class="text-xs sm:text-sm text-[#605a57]">Flexibilidade mês a mês para o seu negócio.</p>
          </div>

          <div class="flex items-baseline my-4">
            <span class="font-serif text-4xl sm:text-5xl font-normal text-[#37322f] tracking-tight">R$ 29,90</span>
            <span class="text-xs sm:text-sm text-[#605a57] ml-2 font-medium">/mês</span>
          </div>

          <ul class="space-y-3 mb-6 flex-1 text-sm text-[#37322f]">
            <li class="flex items-start gap-2.5">
              <span class="flex size-4 shrink-0 items-center justify-center rounded-full bg-[#f4f8e6] text-[#71834f] mt-0.5">
                <i data-lucide="check" class="size-3"></i>
              </span>
              <span class="text-xs sm:text-sm text-[#37322f]">Produtos Ilimitados</span>
            </li>
            <li class="flex items-start gap-2.5">
              <span class="flex size-4 shrink-0 items-center justify-center rounded-full bg-[#f4f8e6] text-[#71834f] mt-0.5">
                <i data-lucide="check" class="size-3"></i>
              </span>
              <span class="text-xs sm:text-sm text-[#37322f]">Pedidos direto no seu WhatsApp</span>
            </li>
            <li class="flex items-start gap-2.5">
              <span class="flex size-4 shrink-0 items-center justify-center rounded-full bg-[#f4f8e6] text-[#71834f] mt-0.5">
                <i data-lucide="check" class="size-3"></i>
              </span>
              <span class="text-xs sm:text-sm text-[#37322f]">Múltiplos Catálogos & Descontos</span>
            </li>
            <li class="flex items-start gap-2.5">
              <span class="flex size-4 shrink-0 items-center justify-center rounded-full bg-[#f4f8e6] text-[#71834f] mt-0.5">
                <i data-lucide="check" class="size-3"></i>
              </span>
              <span class="text-xs sm:text-sm text-[#37322f]">Suporte a Atributos (Cor, Tamanho)</span>
            </li>
          </ul>

          <form action="{{ route('pagamento.generate', [], false) }}" method="POST" class="payment-plan-form space-y-2.5 pt-4 border-t border-[#37322f]/10 mt-auto">
            @csrf
            <input type="hidden" name="plan" value="monthly">
            <input type="hidden" name="documento" class="documento-hidden" value="{{ auth()->user()->documento ?? old('documento') }}">
            <input type="hidden" name="payment_method" value="pix">

            <button type="submit" data-method="pix" class="btn-submit-payment w-full py-3 px-4 rounded-xl text-sm font-semibold transition-all bg-[#37322f] hover:bg-[#514a45] text-white cursor-pointer shadow-sm flex items-center justify-center gap-2">
              <i data-lucide="qr-code" class="size-4 text-[#d7f56b]"></i>
              <span class="btn-label">Pagar via Pix</span>
            </button>
            <button type="submit" data-method="credit_card" class="btn-submit-payment w-full py-3 px-4 rounded-xl text-sm font-semibold transition-all bg-[#eeece8] hover:bg-[#e4e1dc] text-[#37322f] cursor-pointer border border-[#37322f]/15 flex items-center justify-center gap-2">
              <i data-lucide="credit-card" class="size-4 text-[#605a57]"></i>
              <span class="btn-label">Pagar com Cartão de Crédito</span>
            </button>
          </form>
        </div>

        <!-- Plano Anual (Destaque Brillance Charcoal + Lime) -->
        <div id="planCardYearly" class="plan-card flex flex-col p-6 sm:p-8 rounded-2xl border {{ $selectedPlan === 'yearly' ? 'border-[#37322f] ring-2 ring-[#d7f56b]/50' : 'border-[#37322f]' }} bg-[#37322f] text-white shadow-xl relative overflow-hidden transform md:-translate-y-2 group hover:-translate-y-3 transition-all duration-300">
          <div class="absolute top-0 right-0 bg-[#d7f56b] text-[#37322f] text-[11px] font-bold px-3.5 py-1 rounded-bl-xl uppercase tracking-wider flex items-center gap-1 shadow-sm">
            <i data-lucide="award" class="size-3"></i>
            <span>Melhor Valor</span>
          </div>

          <div class="mb-5">
            <h3 class="text-xl sm:text-2xl font-bold text-white mb-1">Plano Anual</h3>
            <p class="text-xs sm:text-sm text-[#d8d4cf]">Economize quase 2 meses em relação ao mensal.</p>
          </div>

          <div class="flex items-baseline my-4">
            <span class="font-serif text-4xl sm:text-5xl font-normal text-white tracking-tight">R$ 299,00</span>
            <span class="text-xs sm:text-sm text-[#d8d4cf] ml-2 font-medium">/ano</span>
          </div>

          <ul class="space-y-3 mb-6 flex-1 text-sm text-[#eeece8]">
            <li class="flex items-start gap-2.5">
              <span class="flex size-4 shrink-0 items-center justify-center rounded-full bg-white/15 text-[#d7f56b] mt-0.5">
                <i data-lucide="check" class="size-3"></i>
              </span>
              <span class="text-xs sm:text-sm text-[#eeece8]">Produtos Ilimitados</span>
            </li>
            <li class="flex items-start gap-2.5">
              <span class="flex size-4 shrink-0 items-center justify-center rounded-full bg-white/15 text-[#d7f56b] mt-0.5">
                <i data-lucide="check" class="size-3"></i>
              </span>
              <span class="text-xs sm:text-sm text-[#eeece8]">Pedidos direto no seu WhatsApp</span>
            </li>
            <li class="flex items-start gap-2.5">
              <span class="flex size-4 shrink-0 items-center justify-center rounded-full bg-white/15 text-[#d7f56b] mt-0.5">
                <i data-lucide="check" class="size-3"></i>
              </span>
              <span class="text-xs sm:text-sm text-[#eeece8]">Múltiplos Catálogos & Descontos</span>
            </li>
            <li class="flex items-start gap-2.5">
              <span class="flex size-4 shrink-0 items-center justify-center rounded-full bg-white/15 text-[#d7f56b] mt-0.5">
                <i data-lucide="check" class="size-3"></i>
              </span>
              <span class="text-xs sm:text-sm text-[#eeece8]">Suporte a Atributos (Cor, Tamanho)</span>
            </li>
            <li class="flex items-start gap-2.5">
              <span class="flex size-4 shrink-0 items-center justify-center rounded-full bg-[#d7f56b]/20 text-[#d7f56b] mt-0.5">
                <i data-lucide="check" class="size-3"></i>
              </span>
              <span class="text-xs sm:text-sm text-[#d7f56b] font-semibold">Desconto de 17% garantido</span>
            </li>
          </ul>

          <form action="{{ route('pagamento.generate', [], false) }}" method="POST" class="payment-plan-form space-y-2.5 pt-4 border-t border-white/10 mt-auto">
            @csrf
            <input type="hidden" name="plan" value="yearly">
            <input type="hidden" name="documento" class="documento-hidden" value="{{ auth()->user()->documento ?? old('documento') }}">
            <input type="hidden" name="payment_method" value="pix">

            <button type="submit" data-method="pix" class="btn-submit-payment w-full py-3 px-4 rounded-xl text-sm font-bold transition-all bg-[#d7f56b] hover:bg-[#e2ff88] text-[#37322f] shadow-md cursor-pointer flex items-center justify-center gap-2">
              <i data-lucide="qr-code" class="size-4"></i>
              <span class="btn-label">Pagar via Pix</span>
            </button>
            <button type="submit" data-method="credit_card" class="btn-submit-payment w-full py-3 px-4 rounded-xl text-sm font-bold transition-all bg-white/15 hover:bg-white/25 text-white cursor-pointer border border-white/25 flex items-center justify-center gap-2">
              <i data-lucide="credit-card" class="size-4 text-[#d7f56b]"></i>
              <span class="btn-label">Pagar com Cartão de Crédito</span>
            </button>
          </form>
        </div>
        
      </div>

    </main>

    <!-- Floating Toast Notification -->
    <div id="toastNotice" class="fixed bottom-6 inset-x-0 mx-auto max-w-md w-[92%] px-4 py-3 rounded-2xl bg-[#37322f] text-white text-sm shadow-2xl flex items-center gap-3 transition-all duration-300 transform translate-y-28 opacity-0 pointer-events-none z-50 border border-white/10">
      <div id="toastIconContainer" class="size-8 rounded-xl bg-red-500/20 text-red-300 flex items-center justify-center flex-shrink-0">
        <i data-lucide="alert-triangle" class="size-4" id="toastIcon"></i>
      </div>
      <div class="flex-1 text-xs sm:text-sm font-medium" id="toastText">
        Por favor, informe seu CPF ou CNPJ no Passo 1 para continuar.
      </div>
    </div>

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

    <!-- Scripts de Interatividade e Validações -->
    <script>
      lucide.createIcons();

      const docInput = document.getElementById('docInput');
      const docInputCard = document.getElementById('docInputCard');
      const docFeedback = document.getElementById('docFeedback');
      const docFeedbackText = document.getElementById('docFeedbackText');
      const docFeedbackIcon = document.getElementById('docFeedbackIcon');
      const docValidIcon = document.getElementById('docValidIcon');
      const toastNotice = document.getElementById('toastNotice');
      const toastText = document.getElementById('toastText');
      const toastIconContainer = document.getElementById('toastIconContainer');
      const btnGenTestCpf = document.getElementById('btnGenTestCpf');
      const stepperStep1 = document.getElementById('stepperStep1');
      const stepperStep1Badge = document.getElementById('stepperStep1Badge');
      const stepperStep2 = document.getElementById('stepperStep2');
      const stepperStep2Badge = document.getElementById('stepperStep2Badge');

      // Validação de Dígitos Verificadores CPF / CNPJ
      function validateCpfCnpj(val) {
          const clean = val.replace(/\D/g, '');
          if (clean.length === 11) {
              if (/^(\d)\1{10}$/.test(clean)) return false;
              let sum = 0, rest;
              for (let i = 1; i <= 9; i++) sum += parseInt(clean.substring(i - 1, i)) * (11 - i);
              rest = (sum * 10) % 11;
              if (rest === 10 || rest === 11) rest = 0;
              if (rest !== parseInt(clean.substring(9, 10))) return false;
              sum = 0;
              for (let i = 1; i <= 10; i++) sum += parseInt(clean.substring(i - 1, i)) * (12 - i);
              rest = (sum * 10) % 11;
              if (rest === 10 || rest === 11) rest = 0;
              if (rest !== parseInt(clean.substring(10, 11))) return false;
              return true;
          } else if (clean.length === 14) {
              if (/^(\d)\1{13}$/.test(clean)) return false;
              let size = clean.length - 2;
              let numbers = clean.substring(0, size);
              let digits = clean.substring(size);
              let sum = 0, pos = size - 7;
              for (let i = size; i >= 1; i--) {
                  sum += numbers.charAt(size - i) * pos--;
                  if (pos < 2) pos = 9;
              }
              let result = sum % 11 < 2 ? 0 : 11 - (sum % 11);
              if (result != digits.charAt(0)) return false;
              size = size + 1;
              numbers = clean.substring(0, size);
              sum = 0; pos = size - 7;
              for (let i = size; i >= 1; i--) {
                  sum += numbers.charAt(size - i) * pos--;
                  if (pos < 2) pos = 9;
              }
              result = sum % 11 < 2 ? 0 : 11 - (sum % 11);
              if (result != digits.charAt(1)) return false;
              return true;
          }
          return false;
      }

      // Máscara e Formatação Dinâmica
      function formatDoc(value) {
          let clean = value.replace(/\D/g, '');
          if (clean.length <= 11) {
              return clean.replace(/(\d{3})(\d)/, '$1.$2')
                          .replace(/(\d{3})(\d)/, '$1.$2')
                          .replace(/(\d{3})(\d{1,2})$/, '$1-$2');
          } else {
              return clean.substring(0, 14)
                          .replace(/^(\d{2})(\d)/, '$1.$2')
                          .replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3')
                          .replace(/\.(\d{3})(\d)/, '.$1/$2')
                          .replace(/(\d{4})(\d)/, '$1-$2');
          }
      }

      function updateStepperState(isValid) {
          if (!stepperStep1 || !stepperStep2) return;
          if (isValid) {
              // Passo 1 concluído
              stepperStep1.className = 'flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-semibold bg-[#f4f8e6] text-[#546830] border border-[#71834f]/30 transition-all duration-300';
              stepperStep1Badge.className = 'w-5 h-5 rounded-full bg-[#71834f] text-white flex items-center justify-center text-[10px] font-bold';
              stepperStep1Badge.innerHTML = '✓';
              // Passo 2 ativo
              stepperStep2.className = 'flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-semibold bg-[#37322f] text-white shadow-sm transition-all duration-300';
              stepperStep2Badge.className = 'w-5 h-5 rounded-full bg-[#d7f56b] text-[#37322f] flex items-center justify-center text-[10px] font-bold';
              stepperStep2Badge.innerHTML = '2';
          } else {
              // Passo 1 ativo
              stepperStep1.className = 'flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-semibold bg-[#37322f] text-white shadow-sm transition-all duration-300';
              stepperStep1Badge.className = 'w-5 h-5 rounded-full bg-[#d7f56b] text-[#37322f] flex items-center justify-center text-[10px] font-bold';
              stepperStep1Badge.innerHTML = '1';
              // Passo 2 pendente
              stepperStep2.className = 'flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-semibold bg-white border border-[#37322f]/10 text-[#605a57] transition-all duration-300';
              stepperStep2Badge.className = 'w-5 h-5 rounded-full bg-[#eeece8] text-[#37322f] flex items-center justify-center text-[10px] font-bold';
              stepperStep2Badge.innerHTML = '2';
          }
      }

      function setDocValidationFeedback(state, message) {
          if (state === 'clear') {
              docFeedback.classList.add('hidden');
              docValidIcon.classList.add('hidden');
              docInput.classList.remove('border-red-500', 'border-emerald-600', 'ring-2', 'ring-red-500/20', 'ring-emerald-600/20');
              docInputCard.classList.remove('border-red-500', 'ring-4', 'ring-red-500/20');
              updateStepperState(false);
              return;
          }

          if (state === 'valid') {
              docFeedback.classList.remove('hidden', 'text-red-600');
              docFeedback.classList.add('text-emerald-700');
              docFeedbackText.textContent = message;
              docValidIcon.classList.remove('hidden');
              docInput.classList.remove('border-red-500', 'ring-red-500/20');
              docInput.classList.add('border-emerald-600');
              docInputCard.classList.remove('border-red-500', 'ring-4', 'ring-red-500/20');
              updateStepperState(true);
          } else {
              docFeedback.classList.remove('hidden', 'text-emerald-700');
              docFeedback.classList.add('text-red-600');
              docFeedbackText.textContent = message;
              docValidIcon.classList.add('hidden');
              docInput.classList.remove('border-emerald-600');
              docInput.classList.add('border-red-500');
              updateStepperState(false);
          }
      }

      function updateDocValidationUI() {
          if (!docInput) return;
          const clean = docInput.value.replace(/\D/g, '');
          if (!clean) {
              setDocValidationFeedback('clear');
              return;
          }

          const isValid = validateCpfCnpj(clean);
          if (isValid) {
              const msg = clean.length === 11 ? 'CPF válido e pronto para cobrança.' : 'CNPJ válido e pronto para cobrança.';
              setDocValidationFeedback('valid', msg);
          } else if (clean.length === 11 || clean.length === 14) {
              const msg = clean.length === 11 ? 'CPF inválido. Verifique os dígitos.' : 'CNPJ inválido. Verifique os dígitos.';
              setDocValidationFeedback('invalid', msg);
          } else {
              setDocValidationFeedback('invalid', 'Informe os 11 dígitos do CPF ou 14 do CNPJ.');
          }
      }

      if (docInput) {
          docInput.addEventListener('input', function(e) {
              e.target.value = formatDoc(e.target.value);
              updateDocValidationUI();
          });

          // Valida no carregamento se já houver valor salvo
          if (docInput.value) {
              docInput.value = formatDoc(docInput.value);
              updateDocValidationUI();
          }
      }

      // Gerador de CPF válido para testes (apenas sandbox/local)
      if (btnGenTestCpf) {
          btnGenTestCpf.addEventListener('click', function(e) {
              e.preventDefault();
              const n = [];
              for (let i = 0; i < 9; i++) n.push(Math.floor(Math.random() * 9));
              let d1 = 0;
              for (let i = 0; i < 9; i++) d1 += n[i] * (10 - i);
              d1 = 11 - (d1 % 11);
              if (d1 >= 10) d1 = 0;
              n.push(d1);
              let d2 = 0;
              for (let i = 0; i < 10; i++) d2 += n[i] * (11 - i);
              d2 = 11 - (d2 % 11);
              if (d2 >= 10) d2 = 0;
              n.push(d2);

              const generated = n.join('');
              docInput.value = formatDoc(generated);
              updateDocValidationUI();
              showToast('CPF de teste gerado com sucesso! Agora escolha o plano abaixo.', 'info');
          });
      }

      let toastTimeout = null;
      function showToast(message, type = 'error') {
          if (!toastNotice) return;
          if (toastText) toastText.textContent = message;

          if (toastIconContainer) {
              if (type === 'info') {
                  toastIconContainer.className = 'size-8 rounded-xl bg-amber-500/20 text-amber-300 flex items-center justify-center flex-shrink-0';
              } else {
                  toastIconContainer.className = 'size-8 rounded-xl bg-red-500/20 text-red-300 flex items-center justify-center flex-shrink-0';
              }
          }

          toastNotice.classList.remove('translate-y-28', 'opacity-0');
          toastNotice.classList.add('translate-y-0', 'opacity-100');

          if (toastTimeout) clearTimeout(toastTimeout);
          toastTimeout = setTimeout(() => {
              toastNotice.classList.remove('translate-y-0', 'opacity-100');
              toastNotice.classList.add('translate-y-28', 'opacity-0');
          }, 4500);
      }

      function highlightDocRequirement(customMsg) {
          showToast(customMsg || 'Por favor, informe seu CPF ou CNPJ no Passo 1 para continuar.');

          if (docInputCard) {
              docInputCard.scrollIntoView({ behavior: 'smooth', block: 'center' });
              docInputCard.classList.remove('border-[#37322f]/10');
              docInputCard.classList.add('border-red-500', 'ring-4', 'ring-red-500/20', 'animate-shake');
              setTimeout(() => {
                  docInputCard.classList.remove('animate-shake');
              }, 500);
          }

          if (docInput) {
              docInput.focus();
              docInput.classList.add('border-red-500', 'ring-2', 'ring-red-500/30');
          }

          if (docFeedback) {
              docFeedback.classList.remove('hidden', 'text-emerald-700');
              docFeedback.classList.add('text-red-600');
              docFeedbackText.textContent = customMsg || 'Por favor, informe seu CPF ou CNPJ antes de prosseguir.';
          }
      }

      // Gerenciamento de Submissão de Formulário
      let lastClickedBtn = null;
      document.querySelectorAll('.btn-submit-payment').forEach(btn => {
          btn.addEventListener('click', function() {
              lastClickedBtn = this;
          });
      });

      document.querySelectorAll('.payment-plan-form').forEach(form => {
          form.addEventListener('submit', function(e) {
              const cleanDoc = (docInput ? docInput.value : '').replace(/\D/g, '');

              // 1. Validação de preenchimento
              if (!cleanDoc) {
                  e.preventDefault();
                  highlightDocRequirement('Por favor, informe seu CPF ou CNPJ no Passo 1 antes de escolher o plano.');
                  return false;
              }

              // 2. Validação de dígitos
              if (!validateCpfCnpj(cleanDoc)) {
                  e.preventDefault();
                  highlightDocRequirement('O CPF ou CNPJ informado é inválido. Por favor, verifique os dígitos.');
                  return false;
              }

              // Sincroniza o documento no formulário
              const hiddenDoc = form.querySelector('.documento-hidden');
              if (hiddenDoc) {
                  hiddenDoc.value = cleanDoc;
              }

              // Sincroniza a forma de pagamento selecionada
              const method = lastClickedBtn ? lastClickedBtn.getAttribute('data-method') : 'pix';
              let hiddenMethod = form.querySelector('input[name="payment_method"]');
              if (!hiddenMethod) {
                  hiddenMethod = document.createElement('input');
                  hiddenMethod.type = 'hidden';
                  hiddenMethod.name = 'payment_method';
                  form.appendChild(hiddenMethod);
              }
              hiddenMethod.value = method;

              // Feedback visual de carregamento no botão
              if (lastClickedBtn) {
                  lastClickedBtn.style.pointerEvents = 'none';
                  lastClickedBtn.classList.add('opacity-80');
                  const label = lastClickedBtn.querySelector('.btn-label');
                  if (label) {
                      label.innerHTML = `
                          <span class="inline-flex items-center gap-2">
                              <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                              </svg>
                              <span>Gerando cobrança Asaas...</span>
                          </span>
                      `;
                  }
              }
          });
      });
    </script>
  </body>
</html>
