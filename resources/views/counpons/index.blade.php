<!DOCTYPE html>
<html lang="pt-BR" class="scroll-smooth">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Gerenciar Cupons | ZapCatalog</title>
    
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
                    <a href="{{ route('counpons.index') }}" class="text-blue-400 font-semibold text-sm flex items-center gap-1">
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
    <main class="flex-grow pt-28 pb-16 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto w-full">
        <!-- Flash Alert -->
        @if(session('success'))
            <div class="mb-6 rounded-2xl border border-emerald-500/30 bg-emerald-500/10 p-4 text-emerald-400 flex items-center justify-between shadow-lg shadow-emerald-500/5">
                <div class="flex items-center gap-3">
                    <div class="bg-emerald-500/20 p-2 rounded-xl">
                        <i data-lucide="check-circle-2" class="h-5 w-5 text-emerald-400"></i>
                    </div>
                    <span class="text-sm font-semibold">{{ session('success') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-emerald-400 hover:text-emerald-200">
                    <i data-lucide="x" class="h-4 w-4"></i>
                </button>
            </div>
        @endif

        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/10 border border-blue-500/20 text-blue-400 text-xs font-semibold mb-2">
                    <i data-lucide="shield-check" class="h-3.5 w-3.5"></i> Área Administrativa
                </div>
                <h1 class="text-3xl font-extrabold text-white tracking-tight">Gerenciamento de Cupons</h1>
                <p class="text-slate-400 text-sm mt-1">Crie e administre os cupons de desconto promocionais da plataforma.</p>
            </div>
            <div>
                <a href="{{ route('counpons.create') }}" 
                   class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold shadow-lg shadow-blue-600/25 hover:scale-105 active:scale-95 transition-all">
                    <i data-lucide="plus" class="h-5 w-5"></i>
                    Criar Novo Cupom
                </a>
            </div>
        </div>

        <!-- Summary Stats Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
            <div class="bg-slate-800/50 backdrop-blur-xl border border-slate-700/50 rounded-2xl p-5 flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total de Cupons</p>
                    <p class="text-3xl font-extrabold text-white mt-1">{{ $counpons->count() }}</p>
                </div>
                <div class="bg-blue-500/10 p-3 rounded-xl border border-blue-500/20 text-blue-400">
                    <i data-lucide="ticket" class="h-6 w-6"></i>
                </div>
            </div>

            <div class="bg-slate-800/50 backdrop-blur-xl border border-slate-700/50 rounded-2xl p-5 flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Cupons Ativos</p>
                    <p class="text-3xl font-extrabold text-emerald-400 mt-1">
                        {{ $counpons->filter(fn($c) => $c->active === \App\CounponStatus::ACTIVE)->count() }}
                    </p>
                </div>
                <div class="bg-emerald-500/10 p-3 rounded-xl border border-emerald-500/20 text-emerald-400">
                    <i data-lucide="check-circle" class="h-6 w-6"></i>
                </div>
            </div>

            <div class="bg-slate-800/50 backdrop-blur-xl border border-slate-700/50 rounded-2xl p-5 flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Cupons Inativos</p>
                    <p class="text-3xl font-extrabold text-red-400 mt-1">
                        {{ $counpons->filter(fn($c) => $c->active === \App\CounponStatus::INACTIVE)->count() }}
                    </p>
                </div>
                <div class="bg-red-500/10 p-3 rounded-xl border border-red-500/20 text-red-400">
                    <i data-lucide="pause-circle" class="h-6 w-6"></i>
                </div>
            </div>
        </div>

        <!-- Coupons Table Card -->
        <div class="bg-slate-800/50 backdrop-blur-xl border border-slate-700/50 rounded-2xl shadow-xl overflow-hidden">
            @if($counpons->isEmpty())
                <div class="p-12 text-center">
                    <div class="w-16 h-16 bg-slate-800 rounded-2xl border border-slate-700 flex items-center justify-center mx-auto mb-4 text-slate-400">
                        <i data-lucide="ticket-slash" class="h-8 w-8"></i>
                    </div>
                    <h3 class="text-lg font-bold text-white mb-1">Nenhum cupom cadastrado</h3>
                    <p class="text-slate-400 text-sm max-w-md mx-auto mb-6">
                        Você ainda não possui cupons cadastrados. Crie seu primeiro cupom de desconto para disponibilizar ofertas.
                    </p>
                    <a href="{{ route('counpons.create') }}" 
                       class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold shadow-lg shadow-blue-600/25 transition-all">
                        <i data-lucide="plus" class="h-4 w-4"></i>
                        Criar Primeiro Cupom
                    </a>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-900/60 border-b border-slate-700/60 text-slate-400 text-xs uppercase font-semibold tracking-wider">
                                <th class="py-4 px-6">Código</th>
                                <th class="py-4 px-6">Descrição</th>
                                <th class="py-4 px-6">Status</th>
                                <th class="py-4 px-6">Data de Criação</th>
                                <th class="py-4 px-6 text-right">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/80 text-sm text-slate-200">
                            @foreach($counpons as $counpon)
                                <tr class="hover:bg-slate-800/40 transition-colors">
                                    <td class="py-4 px-6 font-mono font-bold text-white">
                                        <div class="flex items-center gap-2">
                                            <span class="bg-slate-950 px-2.5 py-1 rounded-lg border border-slate-700 text-blue-400 font-mono tracking-wider text-xs">
                                                {{ $counpon->code }}
                                                
                                            </span>
                                            <button onclick="navigator.clipboard.writeText('{{ $counpon->code }}'); alert('Código {{ $counpon->code }} copiado!')" 
                                                    title="Copiar Código"
                                                    class="text-slate-500 hover:text-slate-300 p-1 rounded transition-colors">
                                                <i data-lucide="copy" class="h-3.5 w-3.5"></i>
                                            </button>
                                        </div>
                                    </td>
                                    <td class="py-4 px-6 text-slate-300 font-medium">
                                        {{ $counpon->description }}
                                    </td>
                                    <td class="py-4 px-6">
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold border {{ $counpon->active->color() }}">
                                            <span class="h-1.5 w-1.5 rounded-full {{ $counpon->active === \App\CounponStatus::ACTIVE ? 'bg-emerald-400' : 'bg-red-400' }}"></span>
                                            {{ $counpon->active->label() }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-6 text-slate-400 text-xs">
                                        {{ $counpon->created_at ? $counpon->created_at->format('d/m/Y H:i') : '-' }}
                                    </td>
                                    <td class="py-4 px-6 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('counpons.edit', $counpon) }}" 
                                               class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg border border-slate-700 bg-slate-800/80 hover:bg-slate-700 text-slate-200 text-xs font-semibold transition-all"
                                               title="Editar Cupom">
                                                <i data-lucide="pencil" class="h-3.5 w-3.5 text-blue-400"></i>
                                                Editar
                                            </a>
                                            <form action="{{ route('counpons.destroy', $counpon) }}" method="post" class="inline" onsubmit="return confirm('Tem certeza que deseja excluir o cupom {{ $counpon->code }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg border border-red-500/30 bg-red-500/10 hover:bg-red-500/20 text-red-400 text-xs font-semibold transition-all"
                                                        title="Excluir Cupom">
                                                    <i data-lucide="trash-2" class="h-3.5 w-3.5"></i>
                                                    Excluir
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
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