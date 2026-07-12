<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loja Indisponível</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600,800&display=swap" rel="stylesheet" />
    <style>
        body {
            font-family: 'Figtree', sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%);
        }
    </style>
</head>
<body class="min-h-screen text-slate-100 flex flex-col items-center justify-center p-4">
    <div class="max-w-md w-full text-center p-8 rounded-3xl bg-slate-900/60 border border-slate-800 backdrop-blur-xl shadow-2xl">
        <div class="inline-flex items-center justify-center p-4 bg-indigo-500/10 border border-indigo-500/25 rounded-2xl mb-6 text-indigo-400">
            <svg class="h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>

        <h1 class="text-2xl font-extrabold tracking-tight mb-3">Loja Temporariamente Indisponível</h1>
        
        <p class="text-sm text-slate-400 mb-6 leading-relaxed">
            Esta loja online de catálogo digital está temporariamente inativa. Se você é o proprietário desta loja, por favor verifique o status do seu plano de pagamento no painel administrativo ou a integração do ERP ConectaVenda.
        </p>

        <div class="border-t border-slate-800/80 pt-6">
            <a href="http://{{ env('APP_DOMAIN') }}" class="inline-flex items-center justify-center gap-2 rounded-2xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-sm px-6 py-3 transition-all cursor-pointer">
                Visitar ZapCatalog
            </a>
        </div>
    </div>
    
    <footer class="mt-8 text-center text-xs text-slate-500">
        &copy; {{ date('Y') }} ZapCatalog. Todos os direitos reservados.
    </footer>
</body>
</html>
