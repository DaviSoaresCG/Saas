<!DOCTYPE html>
<html lang="pt-BR" class="scroll-smooth">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Assinatura confirmada | ZapCatalogo</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
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
      ::-webkit-scrollbar { width: 8px; }
      ::-webkit-scrollbar-track { background: #0f172a; }
      ::-webkit-scrollbar-thumb { background: #334155; border-radius: 4px; }
      ::-webkit-scrollbar-thumb:hover { background: #475569; }
    </style>
</head>
<body class="bg-slate-900 text-white font-sans antialiased selection:bg-blue-600 selection:text-white min-h-screen flex flex-col">

    <header class="border-b border-slate-800 bg-slate-900/90 backdrop-blur-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center gap-2">
                <div class="bg-blue-600 p-2 rounded-lg">
                    <i data-lucide="shopping-bag" class="h-6 w-6 text-white"></i>
                </div>
                <span class="text-white font-bold text-xl tracking-tight">ZapCatalogo</span>
            </a>
        </div>
    </header>

    <main class="flex-1 relative flex items-center justify-center px-4 py-16 sm:py-24 overflow-hidden">
        <div class="absolute inset-0 pointer-events-none -z-10">
            <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-blue-600/10 rounded-full blur-[100px]"></div>
            <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-indigo-600/10 rounded-full blur-[100px]"></div>
        </div>

        <div class="w-full max-w-lg text-center">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-green-900/30 border border-green-800/80 text-green-300 text-sm font-semibold mb-8">
                <i data-lucide="sparkles" class="h-4 w-4"></i>
                Tudo certo com o pagamento
            </div>

            <div class="bg-slate-800/50 border border-slate-700 rounded-2xl p-8 md:p-10 shadow-2xl shadow-black/40">
                <div class="mx-auto mb-8 flex h-20 w-20 items-center justify-center rounded-2xl bg-green-500/15 border-2 border-green-500/40">
                    <i data-lucide="check-circle-2" class="h-11 w-11 text-green-400"></i>
                </div>

                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white mb-3">
                    Obrigado pela sua <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-indigo-400">assinatura</span>
                </h1>
                <p class="text-gray-400 text-base leading-relaxed mb-8">
                    Sua conta está ativa. Você já pode gerenciar produtos, pedidos e seu catálogo no painel.
                </p>

                @php
                    $dashboardSlug = $slug ?? auth()->user()->slug;
                @endphp
                <a
                    href="{{ route('dashboard', ['slug' => $dashboardSlug]) }}"
                    class="inline-flex items-center justify-center w-full sm:w-auto min-w-[200px] px-8 py-4 rounded-xl text-base font-bold text-white bg-blue-600 hover:bg-blue-700 transition-all shadow-lg shadow-blue-600/30 hover:scale-[1.02]"
                >
                    Ir para o dashboard
                    <i data-lucide="arrow-right" class="ml-2 h-5 w-5"></i>
                </a>
            </div>

            <p class="mt-8 text-sm text-gray-500">
                Precisa de ajuda? Acesse o suporte pelo seu painel ou entre em contato com a equipe.
            </p>
        </div>
    </main>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
