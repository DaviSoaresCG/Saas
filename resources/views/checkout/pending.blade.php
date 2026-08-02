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
      <div class="max-w-4xl w-full mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center mb-12">
          <h2 class="text-3xl md:text-5xl font-bold text-white mb-4">
            Ative o seu Catálogo Digital
          </h2>
          <p class="text-gray-400 max-w-xl mx-auto text-base md:text-lg">
            Escolha o plano ideal para o seu negócio e comece a vender pelo WhatsApp hoje mesmo.
          </p>
        </div>

        @if (session('error'))
            <div class="mb-8 p-4 rounded-xl bg-red-500/10 border border-red-500/30 text-red-400 text-sm max-w-2xl mx-auto flex items-center gap-3">
                <i data-lucide="alert-circle" class="h-5 w-5 text-red-500 flex-shrink-0"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto items-stretch">
          <!-- Plano Mensal -->
          <div class="flex flex-col p-8 rounded-2xl border bg-slate-800/40 border-slate-700 hover:border-blue-500/50 transition-all duration-300 shadow-xl">
            <div class="mb-6">
              <h3 class="text-2xl font-bold text-white mb-2">Plano Mensal</h3>
              <p class="text-gray-400 text-sm">Flexibilidade sem compromisso de longo prazo.</p>
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

            <form action="{{ route('pagamento.generate') }}" method="POST">
              @csrf
              <input type="hidden" name="plan" value="monthly">
              <button type="submit" class="w-full py-3.5 px-6 rounded-xl text-sm font-bold transition-all bg-blue-600 hover:bg-blue-700 text-white cursor-pointer shadow-lg shadow-blue-600/10">
                Selecionar Mensal
              </button>
            </form>
          </div>

          <!-- Plano Anual -->
          <div class="flex flex-col p-8 rounded-2xl border bg-slate-800/80 border-blue-500 shadow-2xl relative overflow-hidden transform md:-translate-y-2">
            <div class="absolute top-0 right-0 bg-blue-600 text-white text-xs font-bold px-4 py-1.5 rounded-bl-xl uppercase tracking-wider">
              Melhor Valor
            </div>
            
            <div class="mb-6">
              <h3 class="text-2xl font-bold text-white mb-2">Plano Anual</h3>
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

            <form action="{{ route('pagamento.generate') }}" method="POST">
              @csrf
              <input type="hidden" name="plan" value="yearly">
              <button type="submit" class="w-full py-3.5 px-6 rounded-xl text-sm font-bold transition-all bg-blue-600 hover:bg-blue-700 text-white shadow-lg shadow-blue-600/25 cursor-pointer">
                Selecionar Anual
              </button>
            </form>
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
