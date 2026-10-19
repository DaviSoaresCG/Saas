@php
    $catalogHost = $tenant->slug . '.' . env('APP_DOMAIN');
    $catalogUrlComplete = $tenant->slug . '.' . env('APP_DOMAIN');
    if(strlen($catalogHost) > 18) {
        $catalogHost = substr($catalogHost, 0, 25) . '...';
    }
    $subLabels = [
        'active' => ['label' => 'Ativa', 'class' => 'bg-emerald-500 text-white border-emerald-500/30'],
        'trialing' => ['label' => 'Em teste', 'class' => 'bg-blue-500 text-blue-300 border-blue-500/30'],
        'past_due' => ['label' => 'Em atraso', 'class' => 'bg-amber-500 text-amber-300 border-amber-500/30'],
        'cancelled' => ['label' => 'Cancelada', 'class' => 'bg-slate-500 text-slate-300 border-slate-500/40'],
        'inactive' => ['label' => 'Inativa', 'class' => 'bg-slate-500 text-slate-400 border-slate-600'],
        'other' => ['label' => 'Outro status', 'class' => 'bg-slate-500 text-slate-300 border-slate-500/40'],
    ];
    $badge = $subLabels[$subscriptionStatus] ?? $subLabels['other'];
    $authUser = auth()->user();
    $diasRestantes = ($authUser && $authUser->plano_expira_em && $authUser->plano_expira_em->isFuture()) 
        ? (int) ceil(now()->diffInDays($authUser->plano_expira_em, false)) 
        : null;
@endphp

<x-admin-layout active="dashboard" :show-back-bar="false">
    <div class="space-y-8">
        {{-- Banner de Boas-Vindas pós-assinatura --}}
        @if (session('welcome') || session('success') || request('welcome'))
            <div id="welcomeBanner" class="relative overflow-hidden rounded-3xl border border-emerald-500/40 bg-gradient-to-r from-emerald-950/80 via-slate-900 to-blue-950/80 p-5 sm:p-7 md:p-8 shadow-2xl backdrop-blur-md transition-all duration-300">
                {{-- Decorative Glow Elements --}}
                <div class="pointer-events-none absolute -right-12 -top-12 h-48 w-48 rounded-full bg-emerald-500/20 blur-3xl"></div>
                <div class="pointer-events-none absolute -left-12 -bottom-12 h-48 w-48 rounded-full bg-blue-500/20 blur-3xl"></div>

                {{-- Botão Fechar no canto superior direito (padrão UX limpo) --}}
                <button type="button" onclick="document.getElementById('welcomeBanner').remove()" 
                    class="absolute top-4 right-4 sm:top-5 sm:right-5 z-20 inline-flex h-9 w-9 items-center justify-center rounded-xl border border-slate-700/60 bg-slate-800/80 hover:bg-slate-700 text-slate-400 hover:text-white transition-all cursor-pointer shadow-sm" 
                    title="Fechar aviso">
                    <i data-lucide="x" class="h-4 w-4"></i>
                </button>

                <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6 pr-8 sm:pr-10 lg:pr-0">
                    <div class="flex items-start gap-3.5 sm:gap-5">
                        <div class="flex h-12 w-12 sm:h-14 sm:w-14 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-tr from-emerald-500 to-teal-400 text-slate-950 shadow-lg shadow-emerald-500/30">
                            <i data-lucide="party-popper" class="h-6 w-6 sm:h-7 sm:w-7 text-white"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="flex flex-wrap items-center gap-2 mb-1.5">
                                <span class="inline-flex items-center gap-1 rounded-full bg-emerald-500/20 px-3 py-0.5 text-xs font-bold text-emerald-400 border border-emerald-500/30">
                                    <i data-lucide="check-circle" class="h-3.5 w-3.5"></i> Assinatura Confirmada
                                </span>
                                <span class="text-xs text-slate-400">• Loja Ativada</span>
                            </div>
                            <h2 class="text-lg sm:text-2xl font-extrabold text-white tracking-tight break-words">
                                Seja muito bem-vindo ao ZapCatálogo, {{ auth()->user()->name }}! 🎉
                            </h2>
                            <p class="mt-1 text-xs sm:text-sm text-slate-300 max-w-2xl leading-relaxed">
                                Sua assinatura foi confirmada com sucesso e sua loja já está ativa no ar. Agora você pode cadastrar seus produtos, criar catálogos temáticos e começar a vender pelo WhatsApp!
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 shrink-0 w-full sm:w-auto">
                        <a href="{{ route('products.create') }}" class="inline-flex w-full sm:w-auto items-center justify-center gap-2 rounded-xl bg-emerald-500 hover:bg-emerald-400 px-5 py-3 text-sm font-bold text-slate-950 shadow-lg shadow-emerald-500/25 transition-all hover:scale-[1.02] active:scale-95 cursor-pointer">
                            <i data-lucide="plus" class="h-4 w-4"></i>
                            <span>Cadastrar 1º Produto</span>
                        </a>
                    </div>
                </div>
            </div>
        @endif

        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-[var(--text-base)]">
                    Olá, {{ auth()->user()->name }}
                </h1>
                <p class="mt-1 text-[var(--text-muted)] text-sm sm:text-base">
                    Resumo da sua loja e da assinatura em um só lugar.
                </p>
            </div>
            <div class="flex flex-wrap gap-2">
                @if (auth()->user()->tipo_cliente === 'direct')

                <a href="{{ route('products.create') }}"
                    class="inline-flex items-center gap-2 rounded-xl bg-blue-600 hover:bg-blue-700 px-4 py-2.5 text-sm font-bold text-white shadow-lg shadow-blue-600/25 transition-all">
                    <i data-lucide="plus" class="h-4 w-4"></i>
                    Criar produto
                </a>
                    <a href="{{ route('subscription.renew') }}"
                        class="inline-flex items-center gap-2 rounded-xl border border-slate-600 bg-slate-800 px-4 py-2.5 text-sm font-semibold text-slate-200 transition-colors hover:bg-slate-700">
                        <i data-lucide="refresh-cw" class="h-4 w-4"></i>
                        Renovar plano
                    </a>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
            <div class="rounded-2xl border border-[var(--color-primary)]/80 bg-[var(--bg-card)] p-5 shadow-xl shadow-black/20 hover:scale-105 transition-all">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-sm font-medium text-[var(--text-base)]">Produtos cadastrados</p>
                        <p class="mt-2 text-3xl font-extrabold text-[var(--text-base)] tabular-nums">{{ $totalProducts }}</p>
                    </div>
                    <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-[var(--color-primary)]">
                        <i data-lucide="boxes" class="h-5 w-5 text-[var(--text-on-primary)]"></i>
                    </span>
                </div>
                <a href="{{ route('admin.products') }}" class="mt-4 inline-flex items-center gap-1 text-sm font-semibold text-[var(--color-primary)]">
                    Gerenciar <i data-lucide="arrow-right" class="h-4 w-4"></i>
                </a>
            </div>
            <div class="rounded-2xl border border-[var(--color-primary)]/80 bg-[var(--bg-card)] p-5 shadow-xl shadow-black/20 hover:scale-105 transition-all">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-sm font-medium text-[var(--text-base)]">Pedidos recebidos</p>
                        <p class="mt-2 text-3xl font-extrabold text-[var(--text-base)] tabular-nums">{{ $totalPedidos }}</p>
                    </div>
                    <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-[var(--color-primary)]">
                        <i data-lucide="shopping-bag" class="h-5 w-5 text-[var(--text-on-primary)]"></i>
                    </span>
                </div>
                <a href="{{ route('order.index') }}" class="mt-4 inline-flex items-center gap-1 text-sm font-semibold text-[var(--color-primary)]">
                    Ver pedidos <i data-lucide="arrow-right" class="h-4 w-4"></i>
                </a>
            </div>
            <div class="rounded-2xl border border-[var(--color-primary)] bg-[var(--bg-card)] p-5 shadow-xl shadow-black/20 md:col-span-2 hover:scale-[1.02] transition-all">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-sm font-medium text-[var(--text-base)]">Catálogo público</p>
                        <div class="mt-2 mb-2 gap-2 flex flex-row item-center justify-center text-center">
                            <p class="sm:text-lg text-xs truncate font-bold text-[var(--text-base)] break-all">{{ $catalogHost }}</p>
                            <p class="stick hidden break-all" id="meuTexto">{{ $catalogUrlComplete }}</p>
                            <span class="p-1 rounded bg-[var(--color-primary)] cursor-pointer" onclick="copyToClipboard()" id="btnCopiar">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 text-[var(--text-on-primary)]">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 7.5V6.108c0-1.135.845-2.098 1.976-2.192.373-.03.748-.057 1.123-.08M15.75 18H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08M15.75 18.75v-1.875a3.375 3.375 0 0 0-3.375-3.375h-1.5a1.125 1.125 0 0 1-1.125-1.125v-1.5A3.375 3.375 0 0 0 6.375 7.5H5.25m11.9-3.664A2.251 2.251 0 0 0 15 2.25h-1.5a2.251 2.251 0 0 0-2.15 1.586m5.8 0c.065.21.1.433.1.664v.75h-6V4.5c0-.231.035-.454.1-.664M6.75 7.5H4.875c-.621 0-1.125.504-1.125 1.125v12c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V16.5a9 9 0 0 0-9-9Z" />
                                  </svg>
                            </span>
                        </div>
                    </div>
                    <span class="flex sm:h-11 sm:w-11 h-7 w-7 items-center justify-center rounded-xl bg-emerald-500 border border-emerald-500/30 shrink-0">
                        <i data-lucide="globe" class="h-4 w-4 sm:h-5 sm:w-5 text-emerald-100"></i>
                    </span>
                </div>
                <p class="mt-2 text-xs text-[var(--text-muted)]">Compartilhe este link com seus clientes.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
            <section class="rounded-2xl border border-[var(--color-primary)]/80 bg-[var(--bg-card)] overflow-hidden shadow-xl shadow-black/15 hover:scale-[1.02] transition-all">
                <div class="flex items-center justify-between bg-[var(--color-primary)] px-5 py-4 border-b border-slate-700/80">
                    <h2 class="text-lg font-bold text-[var(--text-on-primary)] flex items-center gap-2">
                        <i data-lucide="clock" class="h-5 w-5 text-[var(--text-on-primary)]"></i>
                        Últimos pedidos
                    </h2>
                    <a href="{{ route('order.index') }}" class="text-xs sm:text-sm text-center font-semibold p-2 rounded-lg text-[var(--text-base)] bg-[var(--bg-card)]">Ver todos</a>
                </div>
                <div class="divide-y divide-[var(--color-primary)]/60">
                    @forelse ($recentPedidos as $pedido)
                        <a href="{{ route('order.show', ['id' => $pedido->id]) }}"
                            class="flex items-center justify-between gap-4 px-5 py-4 hover:bg-[var(--color-primary)]/10 transition-colors">
                            <div class="min-w-0">
                                <p class="font-semibold text-[var(--text-base)]">Pedido #{{ $pedido->id }}</p>
                                <p class="text-xs text-[var(--text-muted)] mt-0.5">
                                    {{ $pedido->created_at->format('d/m/Y H:i') }}
                                    @if ($pedido->iten_pedido->isNotEmpty())
                                        · {{ $pedido->iten_pedido->count() }} item(ns)
                                    @endif
                                </p>
                            </div>
                            <div class="text-right shrink-0">
                                <p class="font-bold text-emerald-600">R$ {{ $pedido->total }}</p>
                                <span class="text-xs text-[var(--text-muted)]">Total</span>
                            </div>
                        </a>
                    @empty
                        <div class="px-5 py-12 text-center text-[var(--text-base)]">
                            <i data-lucide="inbox" class="h-10 w-10 mx-auto mb-3 opacity-50"></i>
                            <p class="text-sm text-[var(--text-base)]">Nenhum pedido ainda.</p>
                        </div>
                    @endforelse
                </div>
            </section>

            <section class="rounded-2xl border hover:scale-[1.02] transition-all border-[var(--color-primary)]/80 bg-[var(--bg-card)] overflow-hidden shadow-xl shadow-black/15">
                <div class="flex items-center justify-between px-5 py-4 border-b border-[var(--color-primary)]/80 bg-[var(--color-primary)]">
                    <h2 class="text-lg font-bold text-[var(--text-on-primary)] flex items-center gap-2">
                        <i data-lucide="mouse-pointer-click" class="h-5 w-5 text-[var(--text-on-primary)]"></i>
                        Mais cliques no produto
                    </h2>
                </div>
                <ul class="divide-y divide-[var(--color-primary)]/60">
                    @forelse ($topProductsByClicks as $row)
                        @if ($row->product)
                            <li class="flex items-center justify-between gap-4 px-5 py-4">
                                <div class="min-w-0 flex-1">
                                    <p class="font-semibold text-[var(--text-base)] truncate">{{ $row->product->name }}</p>
                                    <p class="text-xs text-[var(--text-muted)] mt-0.5">Visualizações na página do produto</p>
                                </div>
                                <span class="shrink-0 inline-flex items-center justify-center min-w-[3rem] rounded-lg bg-[var(--color-primary)] border border-[var(--color-primary)]/30 px-2.5 py-1 text-sm font-bold text-[var(--text-on-primary)] tabular-nums">
                                    {{ $row->clicks }}
                                </span>
                            </li>
                        @endif
                    @empty
                        <li class="px-5 py-12 text-center text-[var(--text-base)]">
                            <i data-lucide="bar-chart-3" class="h-10 w-10 mx-auto mb-3 opacity-50"></i>
                            <p class="text-sm">Sem dados de cliques ainda.</p>
                            <p class="text-xs mt-2 max-w-xs mx-auto">Os cliques são contados quando alguém abre a página de um produto no seu catálogo.</p>
                        </li>
                    @endforelse
                </ul>
            </section>
        </div>

        @if (auth()->user()->tipo_cliente === 'direct')
        <section id="assinatura" class="relative overflow-hidden rounded-3xl border border-[var(--color-primary)]/40 bg-[var(--bg-card)] shadow-2xl transition-all duration-300 scroll-mt-24">
            {{-- Brilho de fundo decorativo suave --}}
            <div class="pointer-events-none absolute -right-16 -top-16 h-48 w-48 rounded-full bg-[var(--color-primary)]/10 blur-3xl"></div>

            {{-- Header do Card com Flexbox 100% Responsivo (Mobile & Desktop) --}}
            <div class="p-5 sm:p-6 border-b border-[var(--color-primary)]/20 bg-gradient-to-r from-[var(--color-primary)]/10 via-transparent to-transparent flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-3.5">
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-[var(--color-primary)] text-[var(--text-on-primary)] shadow-md shadow-[var(--color-primary)]/25">
                        <i data-lucide="credit-card" class="h-5 w-5"></i>
                    </div>
                    <div>
                        <div class="flex flex-wrap items-center gap-2">
                            <h2 class="text-base sm:text-lg font-bold text-[var(--text-base)]">
                                Sua Assinatura
                            </h2>
                            <span class="inline-flex items-center gap-1 rounded-full bg-[var(--color-primary)]/15 border border-[var(--color-primary)]/30 px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wider text-[var(--color-primary)]">
                                Asaas
                            </span>
                        </div>
                        <p class="text-xs text-[var(--text-muted)] mt-0.5">
                            Gerencie sua assinatura e métodos de pagamento (Pix & Cartão).
                        </p>
                    </div>
                </div>

                <a href="{{ route('subscription.renew') }}"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-[var(--color-primary)] px-4 py-2.5 text-xs sm:text-sm font-bold text-[var(--text-on-primary)] shadow-lg shadow-[var(--color-primary)]/25 hover:brightness-110 active:scale-95 transition-all w-full sm:w-auto shrink-0 cursor-pointer">
                    <i data-lucide="refresh-cw" class="h-4 w-4"></i>
                    <span>Renovar ou Alterar Plano</span>
                </a>
            </div>

            {{-- Grade Adaptativa: 1 coluna em mobile, 2 colunas a partir de tablets (md) --}}
            <div class="p-5 sm:p-6 grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                {{-- Bloco 1: Status da Loja --}}
                <div class="flex flex-col justify-between rounded-2xl border border-[var(--color-primary)]/25 bg-[var(--color-primary)]/5 p-4 sm:p-5 transition-colors hover:border-[var(--color-primary)]/40">
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-xs font-semibold uppercase tracking-wider text-[var(--text-muted)]">Status da Loja</span>
                            @if ($subscriptionStatus === 'active')
                                <span class="relative flex h-2.5 w-2.5" title="Loja Online">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                                </span>
                            @endif
                        </div>

                        <div class="flex flex-wrap items-center gap-2 mb-2">
                            <span class="inline-flex items-center gap-1.5 rounded-xl border px-3.5 py-1.5 text-sm font-extrabold {{ $badge['class'] }}">
                                @if ($subscriptionStatus === 'active')
                                    <i data-lucide="check-circle" class="h-4 w-4"></i>
                                @else
                                    <i data-lucide="alert-circle" class="h-4 w-4"></i>
                                @endif
                                {{ $badge['label'] }}
                            </span>
                        </div>
                    </div>
                    
                    <p class="text-xs text-[var(--text-muted)] mt-2 leading-relaxed">
                        @if ($subscriptionStatus === 'active')
                            Sua loja está online, visível para clientes e recebendo pedidos normalmente pelo WhatsApp.
                        @else
                            Sua loja está atualmente inativa. Renove sua assinatura para liberá-la imediatamente.
                        @endif
                    </p>
                </div>
                
                {{-- Bloco 2: Validade do Plano e Dias Restantes --}}
                <div class="flex flex-col justify-between rounded-2xl border border-[var(--color-primary)]/25 bg-[var(--color-primary)]/5 p-4 sm:p-5 transition-colors hover:border-[var(--color-primary)]/40">
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-xs font-semibold uppercase tracking-wider text-[var(--text-muted)]">Plano Expira Em</span>
                            @if ($diasRestantes !== null)
                                @if ($diasRestantes > 5)
                                    <span class="inline-flex items-center gap-1 rounded-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 px-2.5 py-0.5 text-xs font-bold">
                                        <i data-lucide="clock" class="h-3 w-3"></i>
                                        {{ $diasRestantes }} {{ $diasRestantes === 1 ? 'dia restante' : 'dias restantes' }}
                                    </span>
                                @elseif ($diasRestantes > 0)
                                    <span class="inline-flex items-center gap-1 rounded-full bg-amber-500/10 text-amber-400 border border-amber-500/20 px-2.5 py-0.5 text-xs font-bold animate-pulse">
                                        <i data-lucide="alert-triangle" class="h-3 w-3"></i>
                                        Expira em {{ $diasRestantes }} {{ $diasRestantes === 1 ? 'dia' : 'dias' }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 rounded-full bg-rose-500/10 text-rose-400 border border-rose-500/20 px-2.5 py-0.5 text-xs font-bold">
                                        <i data-lucide="alert-circle" class="h-3 w-3"></i>
                                        Vence hoje
                                    </span>
                                @endif
                            @endif
                        </div>

                        @if ($subscriptionEnd)
                            <div class="flex items-baseline gap-2 mb-1">
                                <p class="text-lg sm:text-xl font-extrabold text-[var(--text-base)] tracking-tight">
                                    {{ $subscriptionEnd }}
                                </p>
                            </div>
                        @else
                            <p class="text-sm font-semibold text-[var(--text-muted)]">Nenhum pagamento registrado ainda.</p>
                        @endif
                    </div>

                    <p class="text-xs text-[var(--text-muted)] mt-2 leading-relaxed">
                        @if ($subscriptionEnd)
                            Sua loja permanecerá ativa até esta data. Renovações antecipadas são somadas ao tempo restante sem qualquer perda de dias.
                        @else
                            Ative seu plano para liberar seu catálogo e subdomínio próprio.
                        @endif
                    </p>
                </div>
            </div>
        </section>
        @endif
    </div>
    <script>
        function copyToClipboard() {
        const texto = document.getElementById("meuTexto").innerText;
        console.log(texto);
        navigator.clipboard.writeText(texto).then(() => {
            alert('Texto copiado com sucesso!');
        });
  }
    </script>
</x-admin-layout>
