<!DOCTYPE html>
<html lang="pt-BR" class="scroll-smooth">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Editar Cupom: {{ $counpon->code }} | ZapCatalog</title>
    
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
            <a href="{{ route('home') }}" class="flex-shrink-0 flex items-center gap-2 cursor-pointer">
              <div class="bg-blue-600 p-2 rounded-lg">
                <i data-lucide="shopping-bag" class="h-6 w-6 text-white"></i>
              </div>
              <span class="text-white font-bold text-xl tracking-tight">ZapCatalogo</span>
            </a>
            <div class="hidden md:block">
              <div class="ml-10 flex items-baseline space-x-4">
                <a href="{{ route('home') }}#features" class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium transition-colors">Funcionalidades</a>
                <a href="{{ route('home') }}#how-it-works" class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium transition-colors">Como Funciona</a>
                <a href="{{ route('home') }}#pricing" class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium transition-colors">Preços</a>
              </div>
            </div>
          </div>
          <div class="hidden md:block">
            <div class="ml-4 flex items-center md:ml-6 space-x-4">
                @auth()
                    <span class="text-gray-300 font-medium text-sm">Olá, {{ auth()->user()->name }}</span>
                    @if(auth()->user()->isLojaAtiva())
                        <a href="{{ route('products.index', ['slug' => auth()->user()->slug]) }}" class="text-blue-400 hover:text-blue-300 text-sm font-semibold">Sua Página</a>
                    @endif
                    <a href="{{ route('counpons.index') }}" class="text-blue-400 hover:text-blue-300 font-semibold text-sm flex items-center gap-1">
                      <i data-lucide="ticket" class="h-4 w-4"></i> Cupons
                    </a>
                    <form action="{{ route('logout') }}" method="post" class="inline">
                        @csrf
                        <input type="submit" value="Logout" class="cursor-pointer bg-slate-800 hover:bg-slate-700 text-slate-300 border border-slate-700 px-4 py-2 rounded-lg text-sm font-semibold transition-all">
                    </form>
                @endauth
            </div>
          </div>
        </div>
      </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow pt-28 pb-16 px-4 sm:px-6 lg:px-8 max-w-4xl mx-auto w-full">
        <!-- Back Navigation -->
        <div class="mb-6">
            <a href="{{ route('counpons.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-400 hover:text-white transition-colors">
                <i data-lucide="arrow-left" class="h-4 w-4"></i>
                Voltar para lista de cupons
            </a>
        </div>

        <!-- Form Card Container -->
        <div class="bg-slate-800/50 backdrop-blur-xl border border-slate-700/50 rounded-2xl p-6 sm:p-8 shadow-xl relative overflow-hidden">
            <div class="absolute -right-10 -top-10 w-40 h-40 bg-blue-600/10 rounded-full blur-3xl pointer-events-none"></div>

            <div class="flex items-center justify-between mb-8 pb-6 border-b border-slate-700/60">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/10 border border-blue-500/20 text-blue-400 text-xs font-semibold mb-2">
                        <i data-lucide="edit-3" class="h-3.5 w-3.5"></i> Edição de Cupom
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">Editar Cupom: {{ $counpon->code }}</h1>
                    <p class="text-slate-400 text-sm mt-1">Atualize as informações do cupom abaixo.</p>
                </div>
            </div>

            <form action="{{ route('counpons.update', $counpon) }}" method="post">
                @csrf
                @method('PUT')
                @include('counpons._form')
            </form>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-slate-950 border-t border-slate-800 py-8 text-center text-slate-500 text-sm">
        <p>© {{ date('Y') }} ZapCatalog. Todos os direitos reservados.</p>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
    </script>
</body>
</html>