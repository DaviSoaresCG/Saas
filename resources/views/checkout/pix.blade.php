<!DOCTYPE html>
<html lang="pt-BR" class="scroll-smooth">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pagamento Pix | ZapCatalogo</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

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
    </style>
  </head>
  <body class="bg-slate-900 text-white font-sans antialiased selection:bg-blue-600 selection:text-white min-h-screen flex flex-col">
    
    <!-- Navbar -->
    <nav class="fixed w-full z-50 bg-slate-900/90 backdrop-blur-md border-b border-slate-800">
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
    <main class="flex-grow pt-32 pb-20 flex items-center justify-center">
      <div class="max-w-2xl w-full mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center mb-8">
          <h2 class="text-3xl font-extrabold text-white mb-2">Pagamento via Pix</h2>
          <p class="text-gray-400 text-sm">
            Escaneie o QR Code ou copie o código abaixo para ativar sua assinatura do <strong class="text-blue-400">{{ $payment['plan_name'] }}</strong>.
          </p>
        </div>

        <!-- QR Code Card -->
        <div class="flex flex-col items-center justify-center p-8 rounded-3xl border border-dashed border-slate-750 bg-slate-800/40 mb-8 shadow-xl">
          <div class="text-center mb-6">
            <span class="text-xs text-gray-400 uppercase font-semibold tracking-wider">Valor a Pagar</span>
            <div class="text-4xl font-extrabold text-emerald-400 mt-1">R$ {{ number_format($payment['amount'], 2, ',', '.') }}</div>
          </div>

          <!-- Dynamic QR Code Container -->
          <div class="p-4 bg-white rounded-2xl shadow-xl border border-gray-200 hover:scale-105 transition-transform duration-300">
            @if (!empty($payment['qr_code_base64']))
                <img src="data:image/png;base64,{{ $payment['qr_code_base64'] }}" alt="Pix QR Code" class="w-[200px] h-[200px]" />
            @elseif (!empty($payment['qr_code_url']))
                <img src="{{ $payment['qr_code_url'] }}" alt="Pix QR Code" class="w-[200px] h-[200px]" />
            @else
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ urlencode($payment['qr_code'] ?? $payment['copy_paste']) }}" alt="Pix QR Code" class="w-[200px] h-[200px]" />
            @endif
          </div>

          <p class="mt-6 text-xs text-gray-500 flex items-center gap-1.5">
            <i data-lucide="clock" class="h-4 w-4 text-gray-400"></i>
            O QR Code expira em 30 minutos.
          </p>
        </div>

        <!-- Pix Copia e Cola -->
        <div class="mb-8" x-data="{ copied: false }">
          <label class="block text-sm font-semibold text-gray-300 mb-2">Pix Copia e Cola</label>
          <div class="flex gap-2">
            <input type="text" id="pixCode" readonly value="{{ $payment['qr_code'] ?? $payment['copy_paste'] }}" 
              class="flex-1 px-4 py-3 bg-slate-800 border border-slate-750 rounded-xl text-xs text-gray-300 focus:outline-none focus:border-blue-500 truncate">
            <button @click="
              navigator.clipboard.writeText($el.previousElementSibling.value); 
              copied = true; 
              setTimeout(() => copied = false, 2000)
            " class="px-5 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs transition-all cursor-pointer whitespace-nowrap shadow-lg shadow-blue-600/10">
              <span x-show="!copied">Copiar</span>
              <span x-show="copied" class="text-emerald-400 flex items-center gap-1"><i data-lucide="check" class="h-3 w-3"></i> Copiado!</span>
            </button>
          </div>
        </div>

        <!-- Instructions -->
        <div class="mb-8 p-6 rounded-2xl bg-blue-600/5 border border-blue-500/20">
          <h4 class="text-sm font-bold text-blue-400 mb-3 flex items-center gap-2">
            <i data-lucide="info" class="h-4 w-4"></i>
            Instruções para pagamento:
          </h4>
          <ol class="list-decimal list-inside text-xs text-gray-400 space-y-2 leading-relaxed">
            <li>Abra o aplicativo do seu banco no celular.</li>
            <li>Escolha a opção <strong class="text-gray-300">Pix</strong> e depois <strong class="text-gray-300">Pagar via QR Code</strong> (ou <strong class="text-gray-300">Pix Copia e Cola</strong>).</li>
            <li>Escaneie a imagem acima ou cole o código copiado.</li>
            <li>Confirme os dados e finalize o pagamento. Sua conta será ativada instantaneamente após a confirmação.</li>
          </ol>
        </div>

        <!-- Simulação Local (Ambiente de Teste) -->
        <div class="p-6 rounded-3xl border border-amber-500/30 bg-amber-500/5 text-center mb-8">
          <div class="flex items-center justify-center gap-2 text-amber-400 mb-2">
            <i data-lucide="alert-triangle" class="h-5 w-5"></i>
            <span class="text-sm font-bold uppercase tracking-wider">Área de Simulação Local</span>
          </div>
          <p class="text-xs text-gray-400 mb-5 leading-relaxed">
            Você está em ambiente de desenvolvimento. Clique no botão abaixo para simular a resposta positiva do Webhook e ativar imediatamente a sua loja.
          </p>
          
          <form action="{{ route('pagamento.simulate') }}" method="POST">
            @csrf
            <button type="submit" class="w-full py-3 px-4 rounded-xl bg-amber-500 hover:bg-amber-600 text-white font-bold text-sm shadow-lg shadow-amber-600/10 transition-all cursor-pointer">
              Confirmar Pagamento Simulado
            </button>
          </form>
        </div>

        <div class="text-center">
          <a href="{{ route('pagamento.pending') }}" class="text-xs text-gray-500 hover:text-gray-300 transition-colors flex items-center justify-center gap-1">
            <i data-lucide="arrow-left" class="h-3 w-3"></i> Voltar para seleção de planos
          </a>
        </div>

      </div>
    </main>

    <!-- Footer -->
    <footer class="bg-slate-950 border-t border-slate-800 py-8 text-center text-gray-500 text-sm">
      <div class="max-w-7xl mx-auto px-4">
        <p>© 2024 ZapCatalogo Tecnologia Ltda. Todos os direitos reservados.</p>
      </div>
    </footer>

    <!-- Scripts -->
    <script>
      lucide.createIcons();
    </script>
  </body>
</html>
