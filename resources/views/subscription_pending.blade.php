<!DOCTYPE html>
<html lang="pt-BR" class="scroll-smooth">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Processando assinatura | ZapCatalogo</title>

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
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-blue-900/30 border border-blue-800 text-blue-300 text-sm font-semibold mb-8">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
                </span>
                Confirmando com o Stripe
            </div>

            <div class="bg-slate-800/50 border border-slate-700 rounded-2xl p-8 md:p-10 shadow-2xl shadow-black/40">
                <div class="mx-auto mb-8 relative flex h-20 w-20 items-center justify-center">
                    <div class="absolute inset-0 rounded-2xl bg-blue-600/20 border border-blue-500/30 animate-pulse"></div>
                    <div class="relative flex h-14 w-14 items-center justify-center rounded-xl bg-slate-900/80 border border-slate-600">
                        <i data-lucide="loader-2" class="h-8 w-8 text-blue-400 animate-spin"></i>
                    </div>
                </div>

                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white mb-3">
                    Processando sua <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-indigo-400">inscrição</span>
                </h1>
                <p class="text-gray-400 text-base leading-relaxed mb-2">
                    Estamos finalizando a confirmação do pagamento. Isso costuma levar só alguns segundos.
                </p>
                <p class="text-sm text-slate-500">
                    Não feche esta página — você será avisado assim que estiver pronto.
                </p>
            </div>

            <div class="mt-8 flex flex-wrap items-center justify-center gap-6 text-sm text-gray-500">
                <span class="inline-flex items-center gap-2">
                    <i data-lucide="shield-check" class="h-4 w-4 text-blue-500"></i>
                    Conexão segura
                </span>
                <span class="inline-flex items-center gap-2">
                    <i data-lucide="zap" class="h-4 w-4 text-amber-500"></i>
                    Ativação automática
                </span>
            </div>
        </div>
    </main>

    @vite(['resources/js/app.js'])

    <script>
        lucide.createIcons();

        const currentUserId = {{ auth()->id() }};
    </script>

    <script type="module">
        console.log('Iniciando escuta do WebSocket...');

        Echo.private(`App.Models.User.${currentUserId}`)
            .listen('InscricaoConfirmada', (e) => {
                console.log('⚡ Evento recebido via WebSocket!', e);
                window.location.href = "{{ route('subscription.success') }}";
            });
    </script>
</body>
</html>
