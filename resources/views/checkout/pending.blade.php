<!DOCTYPE html>
<html lang="pt-BR" class="scroll-smooth">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Ative seu Catálogo | ZapCatalogo</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

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
    <style>
      ::-webkit-scrollbar {
        width: 8px;
      }
      ::-webkit-scrollbar-track {
        background: #0f172a;
      }
      ::-webkit-scrollbar-thumb {
        background: #334155;
        border-radius: 4px;
      }
      ::-webkit-scrollbar-thumb:hover {
        background: #475569;
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
  <body class="bg-slate-900 text-white font-sans antialiased selection:bg-blue-600 selection:text-white min-h-screen flex flex-col relative">
    
    <!-- Navbar -->
    <nav class="fixed w-full z-40 bg-slate-900/90 backdrop-blur-md border-b border-slate-800">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">
          <div class="flex items-center">
            <div class="flex-shrink-0 flex items-center gap-2">
              <div class="bg-blue-600 p-2 rounded-lg">
                <i data-lucide="shopping-bag" class="h-6 w-6 text-white"></i>
              </div>
              <span class="text-white font-bold text-xl tracking-tight">ZapCatalogo</span>
            </div>
          </div>
          <div>
            <form action="{{ route('logout') }}" method="POST" class="inline">
              @csrf
              <button type="submit" class="bg-slate-800 hover:bg-slate-700 text-gray-300 hover:text-white px-4 py-2 rounded-lg text-sm font-semibold transition-all border border-slate-750 cursor-pointer">
                Sair da conta
              </button>
            </form>
          </div>
        </div>
      </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow pt-32 pb-24 flex items-center justify-center">
      <div class="max-w-4xl w-full mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center mb-10">
          <h2 class="text-3xl md:text-5xl font-extrabold text-white mb-4 tracking-tight">
            Ative o seu Catálogo Digital
          </h2>
          <p class="text-gray-400 max-w-xl mx-auto text-base md:text-lg">
            Complete seus dados e escolha o plano ideal para começar a vender pelo WhatsApp hoje mesmo.
          </p>
        </div>

        @if (session('error'))
            <div class="mb-8 p-4 rounded-2xl bg-rose-500/10 border border-rose-500/30 text-rose-300 text-sm max-w-2xl mx-auto flex items-center gap-3 shadow-lg">
                <i data-lucide="alert-circle" class="h-5 w-5 text-rose-400 flex-shrink-0"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- Stepper Navigation -->
        <div class="flex items-center justify-center gap-3 mb-8">
          <div class="flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider bg-blue-500/15 text-blue-400 border border-blue-500/30">
            <span class="w-5 h-5 rounded-full bg-blue-500 text-white flex items-center justify-center text-[10px] font-extrabold">1</span>
            <span>Identificação</span>
          </div>
          <div class="w-8 h-px bg-slate-700"></div>
          <div class="flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider bg-slate-800 text-slate-400 border border-slate-700">
            <span class="w-5 h-5 rounded-full bg-slate-700 text-slate-300 flex items-center justify-center text-[10px] font-extrabold">2</span>
            <span>Plano & Pagamento</span>
          </div>
        </div>

        <!-- Passo 1: CPF/CNPJ Input Card -->
        <div id="docInputCard" class="max-w-xl mx-auto mb-12 bg-slate-800/90 p-6 md:p-8 rounded-2xl border border-slate-700 shadow-2xl transition-all duration-300">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-2">
                <label for="docInput" class="block text-base font-bold text-white flex items-center gap-2">
                    <i data-lucide="shield-check" class="h-5 w-5 text-blue-400"></i>
                    <span>Passo 1: CPF ou CNPJ do Titular</span>
                    <span class="text-xs bg-rose-500/20 text-rose-400 border border-rose-500/30 px-2 py-0.5 rounded-full font-semibold">Obrigatório</span>
                </label>

                @if(config('app.env') === 'local' || config('services.asaas.env') === 'sandbox')
                <button type="button" id="btnGenTestCpf" class="text-xs text-blue-400 hover:text-blue-300 font-semibold underline flex items-center gap-1 cursor-pointer transition-colors self-start sm:self-auto">
                    <i data-lucide="zap" class="h-3.5 w-3.5 text-amber-400"></i>
                    <span>Gerar CPF Válido (Teste)</span>
                </button>
                @endif
            </div>

            <p class="text-xs text-gray-400 mb-4 leading-relaxed">
                Exigido pelas normas do Banco Central e pelo gateway Asaas para emissão de Pix e cobrança da assinatura.
            </p>

            <div class="relative">
                <input type="text" id="docInput"
                    value="{{ auth()->user()->documento ?? old('documento') }}"
                    placeholder="000.000.000-00 ou 00.000.000/0000-00"
                    maxlength="18"
                    autocomplete="off"
                    class="w-full px-4 py-3.5 bg-slate-900 border border-slate-700 rounded-xl text-white text-base font-medium tracking-wide focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/30 placeholder:text-gray-500 transition-all">
                
                <div id="docValidIcon" class="absolute right-3.5 top-3.5 hidden">
                    <i data-lucide="check-circle-2" class="h-5 w-5 text-emerald-400"></i>
                </div>
            </div>

            <div id="docFeedback" class="mt-2.5 text-xs flex items-center gap-1.5 transition-all hidden">
                <i data-lucide="alert-circle" id="docFeedbackIcon" class="h-4 w-4 flex-shrink-0"></i>
                <span id="docFeedbackText">Por favor, informe seu CPF ou CNPJ antes de prosseguir.</span>
            </div>
        </div>

        <!-- Passo 2: Seleção de Planos -->
        <div class="mb-4 text-center">
            <h3 class="text-xl font-bold text-white mb-1 flex items-center justify-center gap-2">
                <i data-lucide="sparkles" class="h-5 w-5 text-amber-400"></i>
                <span>Passo 2: Escolha o seu Plano</span>
            </h3>
            <p class="text-xs text-gray-400">Clique na forma de pagamento desejada para ativar seu acesso imediato.</p>
        </div>

        @php $selectedPlan = request('plan', 'monthly'); @endphp

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto items-stretch">
          
          <!-- Plano Mensal -->
          <div class="plan-card flex flex-col p-8 rounded-2xl border {{ $selectedPlan === 'monthly' ? 'border-emerald-500 ring-2 ring-emerald-500/30' : 'border-slate-700' }} bg-slate-800/50 hover:border-emerald-500/50 transition-all duration-300 shadow-xl cursor-pointer group hover:-translate-y-1">
            <div class="mb-6">
              <div class="flex items-center justify-between mb-2">
                <h3 class="text-2xl font-bold text-white group-hover:text-emerald-400 transition-colors">Plano Mensal</h3>
                <span class="text-[11px] font-semibold text-slate-400 bg-slate-700/60 px-2.5 py-1 rounded-lg">Sem Fidelidade</span>
              </div>
              <p class="text-gray-400 text-sm">Flexibilidade mês a mês para o seu negócio.</p>
            </div>

            <div class="flex items-baseline mb-6">
              <span class="text-4xl font-extrabold text-white tracking-tight">R$ 29,90</span>
              <span class="text-gray-400 ml-2">/mês</span>
            </div>

            <ul class="space-y-4 mb-8 flex-1">
              <li class="flex items-start">
                <div class="flex-shrink-0 h-5 w-5 rounded-full flex items-center justify-center mt-0.5 bg-green-500/20">
                  <i data-lucide="check" class="h-3 w-3 text-green-500"></i>
                </div>
                <span class="ml-3 text-sm text-gray-300">Produtos Ilimitados</span>
              </li>
              <li class="flex items-start">
                <div class="flex-shrink-0 h-5 w-5 rounded-full flex items-center justify-center mt-0.5 bg-green-500/20">
                  <i data-lucide="check" class="h-3 w-3 text-green-500"></i>
                </div>
                <span class="ml-3 text-sm text-gray-300">Pedidos direto no seu WhatsApp</span>
              </li>
              <li class="flex items-start">
                <div class="flex-shrink-0 h-5 w-5 rounded-full flex items-center justify-center mt-0.5 bg-green-500/20">
                  <i data-lucide="check" class="h-3 w-3 text-green-500"></i>
                </div>
                <span class="ml-3 text-sm text-gray-300">Múltiplos Catálogos & Descontos</span>
              </li>
              <li class="flex items-start">
                <div class="flex-shrink-0 h-5 w-5 rounded-full flex items-center justify-center mt-0.5 bg-green-500/20">
                  <i data-lucide="check" class="h-3 w-3 text-green-500"></i>
                </div>
                <span class="ml-3 text-sm text-gray-300">Suporte a Atributos (Cor, Tamanho)</span>
              </li>
            </ul>

            <form action="{{ route('pagamento.generate', [], false) }}" method="POST" class="payment-plan-form space-y-3">
              @csrf
              <input type="hidden" name="plan" value="monthly">
              <input type="hidden" name="documento" class="documento-hidden" value="{{ auth()->user()->documento ?? old('documento') }}">
              <input type="hidden" name="payment_method" value="pix">

              <button type="submit" data-method="pix" class="btn-submit-payment w-full py-3.5 px-4 rounded-xl text-sm font-bold transition-all bg-emerald-600 hover:bg-emerald-500 text-white cursor-pointer shadow-lg shadow-emerald-600/20 flex items-center justify-center gap-2">
                <i data-lucide="qr-code" class="h-4 w-4"></i>
                <span class="btn-label">Pagar via Pix</span>
              </button>
              <button type="submit" data-method="credit_card" class="btn-submit-payment w-full py-2.5 px-4 rounded-xl text-xs font-semibold transition-all bg-slate-700/80 hover:bg-slate-700 text-gray-200 hover:text-white cursor-pointer border border-slate-600/80 hover:border-emerald-500 flex items-center justify-center gap-2">
                <i data-lucide="credit-card" class="h-3.5 w-3.5 text-blue-400"></i>
                <span class="btn-label">Pagar com Cartão de Crédito</span>
              </button>
            </form>
          </div>

          <!-- Plano Anual -->
          <div class="plan-card flex flex-col p-8 rounded-2xl border {{ $selectedPlan === 'yearly' ? 'border-blue-400 ring-2 ring-blue-500/40' : 'border-blue-500/60' }} bg-slate-800/90 shadow-2xl relative overflow-hidden transform md:-translate-y-2 group hover:-translate-y-3 transition-all duration-300 cursor-pointer">
            <div class="absolute top-0 right-0 bg-blue-600 text-white text-xs font-bold px-4 py-1.5 rounded-bl-xl uppercase tracking-wider flex items-center gap-1">
              <i data-lucide="award" class="h-3 w-3"></i>
              <span>Melhor Valor</span>
            </div>
            
            <div class="mb-6">
              <h3 class="text-2xl font-bold text-white mb-2 group-hover:text-blue-400 transition-colors">Plano Anual</h3>
              <p class="text-gray-400 text-sm">Economize quase 2 meses em relação ao mensal.</p>
            </div>

            <div class="flex items-baseline mb-6">
              <span class="text-4xl font-extrabold text-white tracking-tight">R$ 299,00</span>
              <span class="text-gray-400 ml-2">/ano</span>
            </div>

            <ul class="space-y-4 mb-8 flex-1">
              <li class="flex items-start">
                <div class="flex-shrink-0 h-5 w-5 rounded-full flex items-center justify-center mt-0.5 bg-green-500/20">
                  <i data-lucide="check" class="h-3 w-3 text-green-500"></i>
                </div>
                <span class="ml-3 text-sm text-gray-300">Produtos Ilimitados</span>
              </li>
              <li class="flex items-start">
                <div class="flex-shrink-0 h-5 w-5 rounded-full flex items-center justify-center mt-0.5 bg-green-500/20">
                  <i data-lucide="check" class="h-3 w-3 text-green-500"></i>
                </div>
                <span class="ml-3 text-sm text-gray-300">Pedidos direto no seu WhatsApp</span>
              </li>
              <li class="flex items-start">
                <div class="flex-shrink-0 h-5 w-5 rounded-full flex items-center justify-center mt-0.5 bg-green-500/20">
                  <i data-lucide="check" class="h-3 w-3 text-green-500"></i>
                </div>
                <span class="ml-3 text-sm text-gray-300">Múltiplos Catálogos & Descontos</span>
              </li>
              <li class="flex items-start">
                <div class="flex-shrink-0 h-5 w-5 rounded-full flex items-center justify-center mt-0.5 bg-green-500/20">
                  <i data-lucide="check" class="h-3 w-3 text-green-500"></i>
                </div>
                <span class="ml-3 text-sm text-gray-300">Suporte a Atributos (Cor, Tamanho)</span>
              </li>
              <li class="flex items-start">
                <div class="flex-shrink-0 h-5 w-5 rounded-full flex items-center justify-center mt-0.5 bg-green-500/20">
                  <i data-lucide="check" class="h-3 w-3 text-green-500"></i>
                </div>
                <span class="ml-3 text-sm text-emerald-400 font-semibold">Desconto de 17% garantido</span>
              </li>
            </ul>

            <form action="{{ route('pagamento.generate', [], false) }}" method="POST" class="payment-plan-form space-y-3">
              @csrf
              <input type="hidden" name="plan" value="yearly">
              <input type="hidden" name="documento" class="documento-hidden" value="{{ auth()->user()->documento ?? old('documento') }}">
              <input type="hidden" name="payment_method" value="pix">

              <button type="submit" data-method="pix" class="btn-submit-payment w-full py-3.5 px-4 rounded-xl text-sm font-bold transition-all bg-blue-600 hover:bg-blue-500 text-white shadow-lg shadow-blue-600/25 cursor-pointer flex items-center justify-center gap-2">
                <i data-lucide="qr-code" class="h-4 w-4"></i>
                <span class="btn-label">Pagar via Pix</span>
              </button>
              <button type="submit" data-method="credit_card" class="btn-submit-payment w-full py-2.5 px-4 rounded-xl text-xs font-semibold transition-all bg-slate-700/80 hover:bg-slate-700 text-gray-200 hover:text-white cursor-pointer border border-slate-600/80 hover:border-blue-400 flex items-center justify-center gap-2">
                <i data-lucide="credit-card" class="h-3.5 w-3.5 text-blue-400"></i>
                <span class="btn-label">Pagar com Cartão de Crédito</span>
              </button>
            </form>
          </div>
          
        </div>

      </div>
    </main>

    <!-- Floating Toast Notification -->
    <div id="toastNotice" class="fixed bottom-6 inset-x-0 mx-auto max-w-md w-[92%] px-5 py-3.5 rounded-2xl bg-slate-800/95 border border-rose-500/50 text-white text-sm shadow-2xl backdrop-blur-md flex items-center gap-3 transition-all duration-300 transform translate-y-28 opacity-0 pointer-events-none z-50">
        <div class="h-8 w-8 rounded-xl bg-rose-500/20 text-rose-400 flex items-center justify-center flex-shrink-0">
            <i data-lucide="alert-triangle" class="h-4 w-4"></i>
        </div>
        <div class="flex-1 text-xs md:text-sm font-medium" id="toastText">
            Por favor, informe seu CPF ou CNPJ no Passo 1 para continuar.
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-slate-950 border-t border-slate-800 py-8 text-center text-gray-500 text-sm">
      <div class="max-w-7xl mx-auto px-4">
        <p>© 2024 ZapCatalogo Tecnologia Ltda. Todos os direitos reservados.</p>
      </div>
    </footer>

    <!-- Scripts -->
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
      const btnGenTestCpf = document.getElementById('btnGenTestCpf');

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

      // Máscara e Formatação
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

      function updateDocValidationUI() {
          if (!docInput) return;
          const clean = docInput.value.replace(/\D/g, '');
          if (!clean) {
              docFeedback.classList.add('hidden');
              docValidIcon.classList.add('hidden');
              docInput.classList.remove('border-rose-500', 'border-emerald-500', 'ring-2', 'ring-rose-500/20', 'ring-emerald-500/20');
              docInputCard.classList.remove('border-rose-500', 'ring-4', 'ring-rose-500/20');
              return;
          }

          const isValid = validateCpfCnpj(clean);
          if (isValid) {
              docFeedback.classList.remove('hidden', 'text-rose-400');
              docFeedback.classList.add('text-emerald-400');
              docFeedbackText.textContent = clean.length === 11 ? 'CPF válido e pronto para cobrança.' : 'CNPJ válido e pronto para cobrança.';
              docValidIcon.classList.remove('hidden');
              docInput.classList.remove('border-rose-500', 'ring-rose-500/20');
              docInput.classList.add('border-emerald-500');
              docInputCard.classList.remove('border-rose-500', 'ring-4', 'ring-rose-500/20');
          } else if (clean.length === 11 || clean.length === 14) {
              docFeedback.classList.remove('hidden', 'text-emerald-400');
              docFeedback.classList.add('text-rose-400');
              docFeedbackText.textContent = clean.length === 11 ? 'CPF inválido. Verifique os dígitos.' : 'CNPJ inválido. Verifique os dígitos.';
              docValidIcon.classList.add('hidden');
              docInput.classList.add('border-rose-500');
          } else {
              docFeedback.classList.remove('hidden', 'text-emerald-400');
              docFeedback.classList.add('text-rose-400');
              docFeedbackText.textContent = 'Informe os 11 dígitos do CPF ou 14 do CNPJ.';
              docValidIcon.classList.add('hidden');
              docInput.classList.add('border-rose-500');
          }
      }

      if (docInput) {
          docInput.addEventListener('input', function(e) {
              e.target.value = formatDoc(e.target.value);
              updateDocValidationUI();
          });

          // Valida no carregamento se já houver valor
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
              docInputCard.classList.remove('border-slate-700');
              docInputCard.classList.add('border-rose-500', 'ring-4', 'ring-rose-500/20', 'animate-shake');
              setTimeout(() => {
                  docInputCard.classList.remove('animate-shake');
              }, 500);
          }

          if (docInput) {
              docInput.focus();
              docInput.classList.add('border-rose-500', 'ring-2', 'ring-rose-500/30');
          }

          if (docFeedback) {
              docFeedback.classList.remove('hidden', 'text-emerald-400');
              docFeedback.classList.add('text-rose-400');
              docFeedbackText.textContent = customMsg || 'Por favor, informe seu CPF ou CNPJ antes de prosseguir.';
          }
      }

      // Clique no card do plano aciona o botão principal (Pix)
      document.querySelectorAll('.plan-card').forEach(card => {
          card.addEventListener('click', function(e) {
              if (e.target.closest('button') || e.target.closest('a') || e.target.closest('form')) {
                  return;
              }
              const pixBtn = card.querySelector('button[data-method="pix"]');
              if (pixBtn) {
                  pixBtn.click();
              }
          });
      });

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
                              <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
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
