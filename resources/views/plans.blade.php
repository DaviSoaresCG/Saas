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
                <a href="{{ route('home') }}" class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium transition-colors">Página inicial</a>
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
          <a href="#pricing" class="text-gray-300 hover:text-white block px-3 py-2 rounded-md text-base font-medium">Preços</a>
          <div class="pt-4 border-t border-slate-800 mt-4">
            <button class="block w-full text-left text-gray-300 hover:text-white px-3 py-2 rounded-md text-base font-medium">
              Login
            </button>
          </div>
        </div>
      </div>
    </nav>

    <main>
      <!-- Hero Section -->
     

      <!-- Pricing -->
      <section id="pricing" class="bg-slate-900 py-24 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="text-center mb-16">
            <h2 class="text-3xl md:text-5xl font-bold text-white mb-6">
              Preços
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