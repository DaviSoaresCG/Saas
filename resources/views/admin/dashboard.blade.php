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
@endphp

<x-admin-layout active="dashboard" :show-back-bar="false">
    <div class="space-y-8">
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
                <a href="{{ route('products.create') }}"
                    class="inline-flex items-center gap-2 rounded-xl bg-blue-600 hover:bg-blue-700 px-4 py-2.5 text-sm font-bold text-white shadow-lg shadow-blue-600/25 transition-all">
                    <i data-lucide="plus" class="h-4 w-4"></i>
                    Criar produto
                </a>
                @if (auth()->user()->tipo_cliente === 'direct')
                    <a href="{{ route('pagamento.pending') }}"
                        class="inline-flex items-center gap-2 rounded-xl border border-slate-600 bg-slate-800 px-4 py-2.5 text-sm font-semibold text-slate-200 transition-colors">
                        <i data-lucide="settings" class="h-4 w-4"></i>
                        Renovar plano / Pix
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
        <section id="assinatura" class="rounded-2xl border border-[var(--color-primary)]/80 bg-[var(--bg-card)] overflow-hidden shadow-xl shadow-black/15 hover:scale-[1.02] transition-all scroll-mt-24">
            <div class="flex items-center justify-between bg-[var(--color-primary)] px-5 py-4 border-b border-[var(--color-primary)]/80">
                <h2 class="text-lg font-bold text-[var(--text-on-primary)] flex items-center gap-2">
                    <i data-lucide="credit-card" class="h-5 w-5 text-[var(--text-on-primary)]"></i>
                    Sua Assinatura (Pix)
                </h2>
                <a href="{{ route('pagamento.pending') }}"
                    class="sm:text-sm text-xs font-semibold bg-[var(--bg-card)] text-[var(--text-base)] p-2 rounded-lg">
                    Renovar plano
                </a>
            </div>

            <div class="p-5 grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="rounded-xl border border-[var(--color-primary)]/30 bg-[var(--color-primary)]/5 p-4">
                    <p class="text-xs font-semibold uppercase tracking-wide text-[var(--text-muted)] mb-2">Status da Loja</p>
                    <span class="inline-flex items-center rounded-lg border px-3 py-1 text-sm font-bold {{ $badge['class'] }}">
                        {{ $badge['label'] }}
                    </span>
                </div>
                
                <div class="rounded-xl border border-[var(--color-primary)]/30 bg-[var(--color-primary)]/5 p-4">
                    <p class="text-xs font-semibold uppercase tracking-wide text-[var(--text-muted)] mb-2">Plano Expira Em</p>
                    @if ($subscriptionEnd)
                        <p class="text-lg font-bold text-[var(--text-base)]">{{ $subscriptionEnd }}</p>
                        <p class="text-xs text-[var(--text-muted)] mt-1">Sua loja ficará indisponível automaticamente após esta data se não for renovada.</p>
                    @else
                        <p class="text-sm text-[var(--text-muted)]">Nenhum pagamento registrado ainda.</p>
                    @endif
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
