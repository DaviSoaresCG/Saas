<!DOCTYPE html>
<html lang="pt-BR" class="scroll-smooth">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>ZapCatalog | Seu Catálogo Digital no WhatsApp</title>
    
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
      /* Ajuste fino para scrollbar */
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
  <script type="importmap">
{
  "imports": {
    "react": "https://esm.sh/react@^19.2.4",
    "lucide-react": "https://esm.sh/lucide-react@^0.563.0",
    "react/": "https://esm.sh/react@^19.2.4/"
  }
}
</script>
</head>
  <body class="bg-slate-900 text-white font-sans antialiased selection:bg-blue-600 selection:text-white">
    
    <!-- Navbar -->
    <nav class="fixed w-full z-50 bg-slate-900/90 backdrop-blur-md border-b border-slate-800">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">
          <div class="flex items-center">
            <div class="flex-shrink-0 flex items-center gap-2 cursor-pointer">
              <div class="bg-blue-600 p-2 rounded-lg">
                <i data-lucide="shopping-bag" class="h-6 w-6 text-white"></i>
              </div>
              <span class="text-white font-bold text-xl tracking-tight">ZapCatalogo</span>
            </div>
            <div class="hidden md:block">
              <div class="ml-10 flex items-baseline space-x-4">
                <a href="#features" class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium transition-colors">Funcionalidades</a>
                <a href="#how-it-works" class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium transition-colors">Como Funciona</a>
                <a href="#pricing" class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium transition-colors">Preços</a>
              </div>
            </div>
          </div>
          <div class="hidden md:block">
            <div class="ml-4 flex items-center md:ml-6 space-x-4">
              <p class="text-white font-medium text-sm">

                @auth()
                    Ola  {{ auth()->user()->name }}
                    @if(auth()->user()->subscribed())
                        <a href="{{ route('products.index', ['slug' => auth()->user()->slug]) }}" class="text-blue-500">Sua Página</a>
                    @endif
                    <form action="{{ route('logout') }}" method="post">
                        @csrf
                        <input type="submit" value="Logout" class="cursor-pointer bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg text-sm font-semibold transition-all shadow-lg shadow-blue-600/20 hover:scale-105">
                    </form>
                @endauth

                @guest
                  <a href="{{ route('login') }}">Login</a>                                    
                @endguest
            </p>                
            </div>
          </div>
          <div class="-mr-2 flex md:hidden">
            <button id="mobile-menu-btn" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-slate-800 focus:outline-none">
              <span class="sr-only">Abrir menu</span>
              <i data-lucide="menu" class="h-6 w-6 block" id="menu-icon"></i>
              <i data-lucide="x" class="h-6 w-6 hidden" id="close-icon"></i>
            </button>
          </div>
        </div>
      </div>

      <!-- Mobile Menu -->
      <div id="mobile-menu" class="hidden md:hidden bg-slate-900 border-b border-slate-800 transition-all duration-300 ease-in-out">
        <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
          <a href="#features" class="text-gray-300 hover:text-white block px-3 py-2 rounded-md text-base font-medium">Funcionalidades</a>
          <a href="#how-it-works" class="text-gray-300 hover:text-white block px-3 py-2 rounded-md text-base font-medium">Como Funciona</a>
          <a href="#pricing" class="text-gray-300 hover:text-white block px-3 py-2 rounded-md text-base font-medium">Preços</a>
          <div class="pt-4 border-t border-slate-800 mt-4">
            @auth()
                    Ola  {{ auth()->user()->name }}
                    @if(auth()->user()->subscribed())
                        <a href="{{ route('products.index', ['slug' => auth()->user()->slug]) }}" class="text-blue-500">Sua Página</a>
                    @endif
                    <form action="{{ route('logout') }}" method="post">
                        @csrf
                        <input type="submit" value="Logout" class="cursor-pointer bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg text-sm font-semibold transition-all shadow-lg shadow-blue-600/20 hover:scale-105">
                    </form>
              @endauth

              @guest
                  <a href="{{ route('login') }}" class="text-gray-300 hover:text-white block px-3 py-2 rounded-md text-base font-medium">
                    Login
                  </a>                                    
              @endguest
          </div>
        </div>
      </div>
    </nav>

    <main>
      <!-- Hero Section -->
      <div class="relative overflow-hidden pt-32 pb-16 lg:pt-40 lg:pb-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
          <div class="text-center max-w-4xl mx-auto">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-blue-900/30 border border-blue-800 text-blue-300 text-sm font-semibold mb-8">
              <span class="relative flex h-2 w-2">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
              </span>
              Nova Versão 2.0 Disponível
            </div>
            
            <h1 class="text-5xl md:text-7xl font-extrabold tracking-tight text-white mb-8 leading-tight">
              Transforme seu WhatsApp em uma <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-indigo-500">Máquina de Vendas</span>
            </h1>
            
            <p class="mt-4 text-xl text-gray-400 max-w-2xl mx-auto mb-10">
              Crie seu catálogo digital em minutos com subdomínio próprio (suamarca.zapcatalog.com). Receba pedidos organizados direto no seu WhatsApp.
            </p>
            
            <div class="flex flex-col sm:flex-row justify-center gap-4 mb-16">
              <a href="{{ route('plans') }}" class="inline-flex items-center justify-center px-8 py-4 border border-transparent text-base font-bold rounded-xl text-white bg-blue-600 hover:bg-blue-700 md:text-lg transition-all shadow-lg shadow-blue-600/30 hover:scale-105">
                Começar Agora
                <i data-lucide="arrow-right" class="ml-2 h-5 w-5"></i>
              </a>
              <a href="#" class="inline-flex items-center justify-center px-8 py-4 border border-slate-700 text-base font-bold rounded-xl text-gray-300 bg-slate-800/50 hover:bg-slate-800 md:text-lg transition-all hover:text-white">
                Ver Demonstração
              </a>
            </div>

            <div class="flex flex-col md:flex-row items-center justify-center gap-8 text-sm text-gray-500 font-medium">
              <div class="flex items-center gap-2">
                <i data-lucide="check-circle-2" class="h-5 w-5 text-green-500"></i>
                <span>Sem cartão de crédito</span>
              </div>
              <div class="flex items-center gap-2">
                <i data-lucide="check-circle-2" class="h-5 w-5 text-green-500"></i>
                <span>Configuração em 2 min</span>
              </div>
              <div class="flex items-center gap-2">
                <i data-lucide="check-circle-2" class="h-5 w-5 text-green-500"></i>
                <span>Cancele quando quiser</span>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Abstract Background Shapes -->
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden -z-10 pointer-events-none">
          <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-blue-600/10 rounded-full blur-[100px]"></div>
          <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-indigo-600/10 rounded-full blur-[100px]"></div>
        </div>
      </div>

      <!-- Features Section -->
      <section id="features" class="py-24 bg-slate-900 relative">
        <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-20 brightness-100 pointer-events-none"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
          <div class="text-center mb-16">
            <h2 class="text-blue-500 font-semibold tracking-wide uppercase text-sm">Recursos Poderosos</h2>
            <h3 class="mt-2 text-3xl md:text-4xl leading-8 font-extrabold tracking-tight text-white sm:text-4xl">
              Tudo que você precisa para vender online
            </h3>
            <p class="mt-4 max-w-2xl text-xl text-gray-400 mx-auto">
              Uma plataforma completa projetada para simplificar suas vendas e profissionalizar seu atendimento no WhatsApp.
            </p>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Feature 1 -->
            <div class="bg-slate-800/50 border border-slate-700 rounded-2xl p-8 hover:bg-slate-800 transition-colors duration-300">
              <div class="inline-flex items-center justify-center p-3 bg-blue-600/20 border-2 border-blue-400 rounded-xl mb-6">
                <i data-lucide="globe" class="h-6 w-6 text-blue-500"></i>
              </div>
              <h4 class="text-xl font-bold text-white mb-3">Subdomínio Personalizado</h4>
              <p class="text-gray-400 leading-relaxed">
                Seu link exclusivo (sualoja.zapcatalog.com) para compartilhar nas redes sociais e bio do Instagram.
              </p>
            </div>
            <!-- Feature 2 -->
            <div class="bg-slate-800/50 border border-slate-700 rounded-2xl p-8 hover:bg-slate-800 transition-colors duration-300">
              <div class="inline-flex items-center justify-center p-3 bg-blue-600/20 border-2 border-blue-400 rounded-xl mb-6">
                <i data-lucide="smartphone" class="h-6 w-6 text-blue-500"></i>
              </div>
              <h4 class="text-xl font-bold text-white mb-3">Checkout via WhatsApp</h4>
              <p class="text-gray-400 leading-relaxed">
                O cliente monta o carrinho e o pedido chega pronto e formatado no seu WhatsApp.
              </p>
            </div>
            <!-- Feature 3 -->
            <div class="bg-slate-800/50 border border-slate-700 rounded-2xl p-8 hover:bg-slate-800 transition-colors duration-300">
              <div class="inline-flex items-center justify-center p-3 bg-blue-600/20 border-2 border-blue-400 rounded-xl mb-6">
                <i data-lucide="shopping-cart" class="h-6 w-6 text-blue-500"></i>
              </div>
              <h4 class="text-xl font-bold text-white mb-3">Gestão de Produtos</h4>
              <p class="text-gray-400 leading-relaxed">
                Cadastre fotos, descrições e preços de forma ilimitada e simples.
              </p>
            </div>
            <!-- Feature 4 -->
            <div class="bg-slate-800/50 border border-slate-700 rounded-2xl p-8 hover:bg-slate-800 transition-colors duration-300">
              <div class="inline-flex items-center justify-center p-3 bg-blue-600/20 border-2 border-blue-400 rounded-xl mb-6">
                <i data-lucide="zap" class="h-6 w-6 text-blue-500"></i>
              </div>
              <h4 class="text-xl font-bold text-white mb-3">Alta Performance</h4>
              <p class="text-gray-400 leading-relaxed">
                Catálogo ultra-rápido otimizado para celular, garantindo que seu cliente não desista da compra.
              </p>
            </div>
            <!-- Feature 5 -->
            <div class="bg-slate-800/50 border border-slate-700 rounded-2xl p-8 hover:bg-slate-800 transition-colors duration-300">
              <div class="inline-flex items-center justify-center p-3 bg-blue-600/20 border-2 border-blue-400 rounded-xl mb-6">
                <i data-lucide="shield-check" class="h-6 w-6 text-blue-500"></i>
              </div>
              <h4 class="text-xl font-bold text-white mb-3">Sem Taxas por Venda</h4>
              <p class="text-gray-400 leading-relaxed">
                Não cobramos comissão sobre suas vendas. O lucro é 100% seu, independente do plano.
              </p>
            </div>
            <!-- Feature 6 -->
            <div class="bg-slate-800/50 border border-slate-700 rounded-2xl p-8 hover:bg-slate-800 transition-colors duration-300">
                <div class="flex flex-row items-center justify-between flex-wrap">
                    <div class="inline-flex items-center justify-center p-3 bg-blue-600/20 border-2 border-blue-400 rounded-xl mb-6">
                        <i data-lucide="bar-chart-3" class="h-6 w-6 text-blue-500"></i>
                    </div>
                    <div class="inline-flex items-center justify-center p-3 bg-blue-600/20 border-2 border-blue-400 rounded-xl mb-6">
                        <p>Em produção!</p>
                    </div>      
                </div>
              
              <h4 class="text-xl font-bold text-white mb-3">Dashboard de Vendas</h4>
              <p class="text-gray-400 leading-relaxed">
                Acompanhe visualizações e cliques no botão de compra para entender melhor seu público.
              </p>
            </div>
          </div>
        </div>
      </section>

      <!-- How It Works -->
      <section id="how-it-works" class="py-24 bg-slate-950 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-extrabold text-white mb-4">
              Do Cadastro à Venda em 3 Passos
            </h2>
            <p class="text-gray-400 max-w-2xl mx-auto">
              Simplificamos o processo para você focar no que importa: vender mais.
            </p>
          </div>

          <div class="relative">
            <!-- Connecting Line (Desktop) -->
            <div class="hidden md:block absolute top-1/2 left-0 w-full h-0.5 bg-gradient-to-r from-blue-600/0 via-blue-600/50 to-blue-600/0 -translate-y-1/2 z-0"></div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-12 relative z-10">
              <!-- Step 1 -->
              <div class="flex flex-col items-center text-center group">
                <div class="w-20 h-20 bg-slate-800 rounded-2xl border border-slate-700 flex items-center justify-center mb-6 shadow-xl shadow-black/50 group-hover:scale-110 transition-transform duration-300 group-hover:border-blue-500">
                  <span class="text-3xl font-bold text-white">1</span>
                </div>
                <h3 class="text-xl font-bold text-white mb-3">Cadastre seus Produtos</h3>
                <p class="text-gray-400 px-4">
                  Adicione fotos, descrições e preços em um painel administrativo simples e intuitivo.
                </p>
              </div>

              <!-- Step 2 -->
              <div class="flex flex-col items-center text-center group">
                <div class="w-20 h-20 bg-slate-800 rounded-2xl border border-slate-700 flex items-center justify-center mb-6 shadow-xl shadow-black/50 group-hover:scale-110 transition-transform duration-300 group-hover:border-blue-500">
                  <span class="text-3xl font-bold text-white">2</span>
                </div>
                <h3 class="text-xl font-bold text-white mb-3">Divulgue seu Link</h3>
                <p class="text-gray-400 px-4">
                  Compartilhe seu subdomínio (ex: loja.zapcatalog.com) no Instagram, Facebook e grupos.
                </p>
              </div>

              <!-- Step 3 -->
              <div class="flex flex-col items-center text-center group">
                <div class="w-20 h-20 bg-slate-800 rounded-2xl border border-slate-700 flex items-center justify-center mb-6 shadow-xl shadow-black/50 group-hover:scale-110 transition-transform duration-300 group-hover:border-blue-500">
                  <span class="text-3xl font-bold text-white">3</span>
                </div>
                <h3 class="text-xl font-bold text-white mb-3">Receba no WhatsApp</h3>
                <p class="text-gray-400 px-4">
                  Os clientes montam o carrinho e o pedido chega pronto para você fechar a venda.
                </p>
              </div>
            </div>
          </div>

          <!-- Demo Preview -->
          <div class="mt-20 bg-slate-800 rounded-3xl p-4 md:p-8 border border-slate-700 shadow-2xl">
              <div class="flex flex-col md:flex-row items-center gap-8">
                  <div class="flex-1 space-y-6">
                      <h3 class="text-2xl font-bold text-white">Experiência do Cliente</h3>
                      <p class="text-gray-400">
                          Seu cliente navega por um catálogo limpo e rápido. Ele seleciona os itens e, ao finalizar, é redirecionado automaticamente para o seu WhatsApp com uma mensagem pré-formatada contendo o resumo do pedido.
                      </p>
                      <div class="p-4 bg-slate-900 rounded-xl border border-slate-700 border-l-4 border-l-green-500">
                          <p class="text-sm text-gray-400 mb-2">Mensagem recebida no WhatsApp:</p>
                          <p class="text-green-100 font-mono text-sm">
                              Olá! Gostaria de fazer o pedido #1024:<br/>
                              - 1x Camiseta Basic (M) - R$ 49,90<br/>
                              - 2x Meias Sport - R$ 29,90<br/>
                              <br/>
                              Total: R$ 79,80<br/>
                              Aguardo confirmação!
                          </p>
                      </div>
                  </div>
                  <div class="flex-1 w-full max-w-sm">
                      <!-- Mock Phone -->
                      <div class="relative mx-auto border-gray-800 bg-gray-800 border-[14px] rounded-[2.5rem] h-[500px] w-[260px] sm:w-[300px] shadow-xl">
                          <div class="h-[32px] w-[3px] bg-gray-800 absolute -left-[17px] top-[72px] rounded-l-lg"></div>
                          <div class="h-[46px] w-[3px] bg-gray-800 absolute -left-[17px] top-[124px] rounded-l-lg"></div>
                          <div class="h-[46px] w-[3px] bg-gray-800 absolute -left-[17px] top-[178px] rounded-l-lg"></div>
                          <div class="h-[64px] w-[3px] bg-gray-800 absolute -right-[17px] top-[142px] rounded-r-lg"></div>
                          <div class="rounded-[2rem] overflow-hidden w-[230px] sm:w-[270px] h-[472px] bg-slate-100 relative">
                              <img src="https://images.unsplash.com/photo-1556742049-0cfed4f7a07d?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" class="w-full h-full object-cover opacity-90" alt="App Screen" />
                              <div class="absolute bottom-10 left-1/2 transform -translate-x-1/2 w-40 bg-green-500 text-white text-center py-2 rounded-full shadow-lg font-bold text-sm cursor-pointer hover:bg-green-600 transition-colors">
                                  Enviar Pedido
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
        </div>
      </section>

      <!-- Pricing -->
      <section id="pricing" class="bg-slate-900 py-24 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="text-center mb-16">
            <h2 class="text-3xl md:text-5xl font-bold text-white mb-6">
              Projetado para times de negócios como o seu
            </h2>
            <p class="text-gray-400 max-w-2xl mx-auto text-lg mb-8">
              Focamos em mercados onde tecnologia, inovação e capital podem desbloquear valor a longo prazo.
            </p>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Starter Plan -->
            <div class="flex flex-col p-8 rounded-2xl border bg-slate-800/40 border-slate-700">
              <div class="mb-8">
                <h3 class="text-2xl font-bold text-white mb-2">Starter</h3>
                <p class="text-gray-400 text-sm h-10">Melhor opção para uso pessoal e para começar a vender.</p>
              </div>

              <div class="flex items-baseline mb-8">
                <span class="text-4xl font-extrabold text-white tracking-tight">R$ 49,99</span>
                <span class="text-gray-400 ml-2">/mês</span>
              </div>

              <ul class="space-y-4 mb-8 flex-1">
                <li class="flex items-start">
                  <div class="flex-shrink-0 h-5 w-5 rounded-full flex items-center justify-center mt-0.5 bg-green-500/20">
                    <i data-lucide="check" class="h-3 w-3 text-green-500"></i>
                  </div>
                  <span class="ml-3 text-sm text-gray-300">Até 50 produtos</span>
                </li>
                 <li class="flex items-start">
                  <div class="flex-shrink-0 h-5 w-5 rounded-full flex items-center justify-center mt-0.5 bg-green-500/20">
                    <i data-lucide="check" class="h-3 w-3 text-green-500"></i>
                  </div>
                  <span class="ml-3 text-sm text-gray-300">Suporte por Email</span>
                </li>
                <li class="flex items-start">
                  <div class="flex-shrink-0 h-5 w-5 rounded-full flex items-center justify-center mt-0.5 bg-slate-700/50">
                    <i data-lucide="check" class="h-3 w-3 text-slate-500"></i>
                  </div>
                  <span class="ml-3 text-sm text-gray-600 line-through">Gestão de estoque</span>
                </li>
              </ul>

              <a href="{{ route('plans.selected', ['id' => $prices['monthly']]) }}">
              <button class="w-full py-3 px-6 rounded-lg text-sm font-bold transition-all bg-blue-600 hover:bg-blue-700 text-white">
                Começar Agora
              </button>
              </a>
            </div>

            <!-- Company Plan (Highlighted) -->
            <div class="flex flex-col p-8 rounded-2xl border bg-slate-800/80 border-blue-500 shadow-xl shadow-blue-900/20 relative overflow-hidden transform md:-translate-y-4">
              <div class="absolute top-0 right-0 bg-blue-600 text-white text-xs font-bold px-3 py-1 rounded-bl-lg">
                POPULAR
              </div>
              
              <div class="mb-8">
                <h3 class="text-2xl font-bold text-white mb-2">Company</h3>
                <p class="text-gray-400 text-sm h-10">Relevante para lojas em crescimento com múltiplos atendentes.</p>
              </div>

              <div class="flex items-baseline mb-8">
                <span class="text-5xl font-extrabold text-white tracking-tight">R$ 499,99</span>
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
                  <span class="ml-3 text-sm text-gray-300">Suporte Prioritário (WhatsApp)</span>
                </li>
                <li class="flex items-start">
                  <div class="flex-shrink-0 h-5 w-5 rounded-full flex items-center justify-center mt-0.5 bg-green-500/20">
                    <i data-lucide="check" class="h-3 w-3 text-green-500"></i>
                  </div>
                  <span class="ml-3 text-sm text-gray-300">Gestão de estoque(Em desenvolvimento)</span>
                </li>
              </ul>

                <a href="{{ route('plans.selected', ['id' => $prices['yearly']]) }}">
                    <button class="w-full py-3 px-6 rounded-lg text-sm font-bold transition-all bg-blue-600 hover:bg-blue-700 text-white shadow-lg shadow-blue-600/25">
                        Começar Agora
                    </button>
                </a>
            </div>

            <!-- Enterprise Plan -->
            <div class="flex flex-col p-8 rounded-2xl border bg-slate-800/40 border-slate-700">
              <div class="mb-8">
                <h3 class="text-2xl font-bold text-white mb-2">Enterprise</h3>
                <p class="text-gray-400 text-sm h-10">Para operações de grande escala e direitos de redistribuição.</p>
              </div>

              <div class="flex items-baseline mb-8">
                <span class="text-4xl font-extrabold text-white tracking-tight">R$ 1499,99</span>
                <span class="text-gray-400 ml-2">/tri-anual</span>
              </div>

              <ul class="space-y-4 mb-8 flex-1">
                <li class="flex items-start">
                  <div class="flex-shrink-0 h-5 w-5 rounded-full flex items-center justify-center mt-0.5 bg-green-500/20">
                    <i data-lucide="check" class="h-3 w-3 text-green-500"></i>
                  </div>
                  <span class="ml-3 text-sm text-gray-300">Tudo do Company</span>
                </li>
                 <li class="flex items-start">
                  <div class="flex-shrink-0 h-5 w-5 rounded-full flex items-center justify-center mt-0.5 bg-green-500/20">
                    <i data-lucide="check" class="h-3 w-3 text-green-500"></i>
                  </div>
                  <span class="ml-3 text-sm text-gray-300">Gerente de conta dedicado</span>
                </li>
              </ul>

              <a href="{{ route('plans.selected', ['id' => $prices['longest']]) }}">
                <button class="w-full py-3 px-6 rounded-lg text-sm font-bold transition-all bg-blue-600 hover:bg-blue-700 text-white">
                    Começar Agora
                </button>
              </a>
            </div>
          </div>
        </div>
      </section>

      <!-- CTA Bottom -->
      <section class="py-20 relative overflow-hidden">
        <div class="absolute inset-0 bg-blue-600 opacity-10"></div>
        <div class="max-w-4xl mx-auto px-4 text-center relative z-10">
          <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">
            Pronto para vender mais?
          </h2>
          <p class="text-xl text-blue-100 mb-8">
            Junte-se a mais de 5.000 lojistas que transformaram suas vendas no WhatsApp.
          </p>
          <a href="{{ route('plans') }}">
            <button class="bg-white text-blue-900 hover:bg-blue-50 px-8 py-4 rounded-xl font-bold text-lg shadow-xl transition-all hover:scale-105">
                Criar meu Catálogo Grátis
            </button>
          </a>
        </div>
      </section>
    </main>

    <!-- Footer -->
    <footer class="bg-slate-900 border-t border-slate-800 pt-16 pb-8">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
          <div class="col-span-1 md:col-span-1">
            <div class="flex items-center gap-2 mb-4">
              <div class="bg-blue-600 p-1.5 rounded-lg">
                <i data-lucide="shopping-bag" class="h-5 w-5 text-white"></i>
              </div>
              <span class="text-white font-bold text-lg">ZapCatalog</span>
            </div>
            <p class="text-gray-400 text-sm leading-relaxed">
              A plataforma líder para criar catálogos digitais integrados ao WhatsApp. Potencialize suas vendas hoje mesmo.
            </p>
          </div>
          
          <div>
            <h4 class="text-white font-bold mb-4">Produto</h4>
            <ul class="space-y-2 text-sm text-gray-400">
              <li><a href="#" class="hover:text-white transition-colors">Funcionalidades</a></li>
              <li><a href="#" class="hover:text-white transition-colors">Preços</a></li>
              <li><a href="#" class="hover:text-white transition-colors">Showcase</a></li>
              <li><a href="#" class="hover:text-white transition-colors">Atualizações</a></li>
            </ul>
          </div>

          <div>
            <h4 class="text-white font-bold mb-4">Suporte</h4>
            <ul class="space-y-2 text-sm text-gray-400">
              <li><a href="#" class="hover:text-white transition-colors">Central de Ajuda</a></li>
              <li><a href="#" class="hover:text-white transition-colors">Tutoriais</a></li>
              <li><a href="#" class="hover:text-white transition-colors">Contato</a></li>
              <li><a href="#" class="hover:text-white transition-colors">Status</a></li>
            </ul>
          </div>

          <div>
            <h4 class="text-white font-bold mb-4">Legal</h4>
            <ul class="space-y-2 text-sm text-gray-400">
              <li><a href="#" class="hover:text-white transition-colors">Termos de Uso</a></li>
              <li><a href="#" class="hover:text-white transition-colors">Privacidade</a></li>
              <li><a href="#" class="hover:text-white transition-colors">Cookies</a></li>
            </ul>
          </div>
        </div>

        <div class="border-t border-slate-800 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
          <p class="text-gray-500 text-sm">
            © 2024 ZapCatalog Tecnologia Ltda. Todos os direitos reservados.
          </p>
          <div class="flex space-x-6">
            <a href="#" class="text-gray-500 hover:text-white transition-colors">
              <i data-lucide="instagram" class="h-5 w-5"></i>
            </a>
            <a href="#" class="text-gray-500 hover:text-white transition-colors">
              <i data-lucide="facebook" class="h-5 w-5"></i>
            </a>
            <a href="#" class="text-gray-500 hover:text-white transition-colors">
              <i data-lucide="twitter" class="h-5 w-5"></i>
            </a>
          </div>
        </div>
      </div>
    </footer>

    <!-- Scripts -->
    <script>
      // Initialize Lucide Icons
      lucide.createIcons();

      // Mobile Menu Logic
      const btn = document.getElementById('mobile-menu-btn');
      const menu = document.getElementById('mobile-menu');
      const menuIcon = document.getElementById('menu-icon');
      const closeIcon = document.getElementById('close-icon');

      btn.addEventListener('click', () => {
        menu.classList.toggle('hidden');
        
        if (menu.classList.contains('hidden')) {
          menuIcon.classList.remove('hidden');
          closeIcon.classList.add('hidden');
        } else {
          menuIcon.classList.add('hidden');
          closeIcon.classList.remove('hidden');
        }
      });
    </script>
  </body>
</html>