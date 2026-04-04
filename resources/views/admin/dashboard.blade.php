@php
    $catalogHost = $tenant->slug . '.' . env('APP_DOMAIN');
    $subLabels = [
        'active' => ['label' => 'Ativa', 'class' => 'bg-emerald-500/15 text-emerald-300 border-emerald-500/30'],
        'trialing' => ['label' => 'Em teste', 'class' => 'bg-blue-500/15 text-blue-300 border-blue-500/30'],
        'past_due' => ['label' => 'Em atraso', 'class' => 'bg-amber-500/15 text-amber-300 border-amber-500/30'],
        'cancelled' => ['label' => 'Cancelada', 'class' => 'bg-slate-500/20 text-slate-300 border-slate-500/40'],
        'inactive' => ['label' => 'Inativa', 'class' => 'bg-slate-500/20 text-slate-400 border-slate-600'],
        'other' => ['label' => 'Outro status', 'class' => 'bg-slate-500/20 text-slate-300 border-slate-500/40'],
    ];
    $badge = $subLabels[$subscriptionStatus] ?? $subLabels['other'];
@endphp

<x-admin-layout active="dashboard" :show-back-bar="false">
    <div class="space-y-8">
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white">
                    Olá, {{ auth()->user()->name }}
                </h1>
                <p class="mt-1 text-slate-400 text-sm sm:text-base">
                    Resumo da sua loja e da assinatura em um só lugar.
                </p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('products.create') }}"
                    class="inline-flex items-center gap-2 rounded-xl bg-blue-600 hover:bg-blue-700 px-4 py-2.5 text-sm font-bold text-white shadow-lg shadow-blue-600/25 transition-all">
                    <i data-lucide="plus" class="h-4 w-4"></i>
                    Criar produto
                </a>
                <a href="{{ route('billing') }}"
                    class="inline-flex items-center gap-2 rounded-xl border border-slate-600 bg-slate-800/80 px-4 py-2.5 text-sm font-semibold text-slate-200 hover:bg-slate-700 transition-colors">
                    <i data-lucide="settings" class="h-4 w-4"></i>
                    Portal de cobrança
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
            <div class="rounded-2xl border border-slate-700/80 bg-slate-800/50 p-5 shadow-xl shadow-black/20">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-sm font-medium text-slate-400">Produtos cadastrados</p>
                        <p class="mt-2 text-3xl font-extrabold text-white tabular-nums">{{ $totalProducts }}</p>
                    </div>
                    <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-500/15 border border-blue-500/30">
                        <i data-lucide="boxes" class="h-5 w-5 text-blue-400"></i>
                    </span>
                </div>
                <a href="{{ route('admin.products') }}" class="mt-4 inline-flex items-center gap-1 text-sm font-semibold text-blue-400 hover:text-blue-300">
                    Gerenciar <i data-lucide="arrow-right" class="h-4 w-4"></i>
                </a>
            </div>
            <div class="rounded-2xl border border-slate-700/80 bg-slate-800/50 p-5 shadow-xl shadow-black/20">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-sm font-medium text-slate-400">Pedidos recebidos</p>
                        <p class="mt-2 text-3xl font-extrabold text-white tabular-nums">{{ $totalPedidos }}</p>
                    </div>
                    <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-indigo-500/15 border border-indigo-500/30">
                        <i data-lucide="shopping-bag" class="h-5 w-5 text-indigo-400"></i>
                    </span>
                </div>
                <a href="{{ route('order.index') }}" class="mt-4 inline-flex items-center gap-1 text-sm font-semibold text-blue-400 hover:text-blue-300">
                    Ver pedidos <i data-lucide="arrow-right" class="h-4 w-4"></i>
                </a>
            </div>
            <div class="rounded-2xl border border-slate-700/80 bg-slate-800/50 p-5 shadow-xl shadow-black/20 sm:col-span-2 xl:col-span-2">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-sm font-medium text-slate-400">Catálogo público</p>
                        <p class="mt-2 text-lg font-bold text-white break-all">{{ $catalogHost }}</p>
                    </div>
                    <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-500/15 border border-emerald-500/30 shrink-0">
                        <i data-lucide="globe" class="h-5 w-5 text-emerald-400"></i>
                    </span>
                </div>
                <p class="mt-2 text-xs text-slate-500">Compartilhe este link com seus clientes.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
            <section class="rounded-2xl border border-slate-700/80 bg-slate-800/40 overflow-hidden shadow-xl shadow-black/15">
                <div class="flex items-center justify-between px-5 py-4 border-b border-slate-700/80">
                    <h2 class="text-lg font-bold text-white flex items-center gap-2">
                        <i data-lucide="clock" class="h-5 w-5 text-blue-400"></i>
                        Últimos pedidos
                    </h2>
                    <a href="{{ route('order.index') }}" class="text-sm font-semibold text-blue-400 hover:text-blue-300">Ver todos</a>
                </div>
                <div class="divide-y divide-slate-700/60">
                    @forelse ($recentPedidos as $pedido)
                        <a href="{{ route('order.show', ['id' => $pedido->id]) }}"
                            class="flex items-center justify-between gap-4 px-5 py-4 hover:bg-slate-700/30 transition-colors">
                            <div class="min-w-0">
                                <p class="font-semibold text-white">Pedido #{{ $pedido->id }}</p>
                                <p class="text-xs text-slate-500 mt-0.5">
                                    {{ $pedido->created_at->format('d/m/Y H:i') }}
                                    @if ($pedido->iten_pedido->isNotEmpty())
                                        · {{ $pedido->iten_pedido->count() }} item(ns)
                                    @endif
                                </p>
                            </div>
                            <div class="text-right shrink-0">
                                <p class="font-bold text-emerald-400">R$ {{ $pedido->total }}</p>
                                <span class="text-xs text-slate-500">Total</span>
                            </div>
                        </a>
                    @empty
                        <div class="px-5 py-12 text-center text-slate-500">
                            <i data-lucide="inbox" class="h-10 w-10 mx-auto mb-3 opacity-50"></i>
                            <p class="text-sm">Nenhum pedido ainda.</p>
                        </div>
                    @endforelse
                </div>
            </section>

            <section class="rounded-2xl border border-slate-700/80 bg-slate-800/40 overflow-hidden shadow-xl shadow-black/15">
                <div class="flex items-center justify-between px-5 py-4 border-b border-slate-700/80">
                    <h2 class="text-lg font-bold text-white flex items-center gap-2">
                        <i data-lucide="mouse-pointer-click" class="h-5 w-5 text-violet-400"></i>
                        Mais cliques no produto
                    </h2>
                    <span class="text-xs text-slate-500 font-medium">Detalhe do produto</span>
                </div>
                <ul class="divide-y divide-slate-700/60">
                    @forelse ($topProductsByClicks as $row)
                        @if ($row->product)
                            <li class="flex items-center justify-between gap-4 px-5 py-4">
                                <div class="min-w-0 flex-1">
                                    <p class="font-semibold text-white truncate">{{ $row->product->name }}</p>
                                    <p class="text-xs text-slate-500 mt-0.5">Visualizações na página do produto</p>
                                </div>
                                <span class="shrink-0 inline-flex items-center justify-center min-w-[3rem] rounded-lg bg-violet-500/15 border border-violet-500/30 px-2.5 py-1 text-sm font-bold text-violet-300 tabular-nums">
                                    {{ $row->clicks }}
                                </span>
                            </li>
                        @endif
                    @empty
                        <li class="px-5 py-12 text-center text-slate-500">
                            <i data-lucide="bar-chart-3" class="h-10 w-10 mx-auto mb-3 opacity-50"></i>
                            <p class="text-sm">Sem dados de cliques ainda.</p>
                            <p class="text-xs mt-2 max-w-xs mx-auto">Os cliques são contados quando alguém abre a página de um produto no seu catálogo.</p>
                        </li>
                    @endforelse
                </ul>
            </section>
        </div>

        <section id="assinatura"
            class="rounded-2xl border border-slate-700/80 bg-slate-800/40 overflow-hidden shadow-xl shadow-black/15 scroll-mt-24">
            <div class="px-5 py-4 border-b border-slate-700/80 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <h2 class="text-lg font-bold text-white flex items-center gap-2">
                    <i data-lucide="credit-card" class="h-5 w-5 text-blue-400"></i>
                    Assinatura e faturas
                </h2>
                <a href="{{ route('billing') }}"
                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-600 hover:bg-blue-700 px-4 py-2 text-sm font-bold text-white transition-colors w-fit">
                    Gerenciar assinatura
                </a>
            </div>
            <div class="p-5 grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-1 space-y-4">
                    <div class="rounded-xl border border-slate-600/80 bg-slate-900/50 p-4">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 mb-2">Status</p>
                        <span class="inline-flex items-center rounded-lg border px-3 py-1 text-sm font-bold {{ $badge['class'] }}">
                            {{ $badge['label'] }}
                        </span>
                        @if ($stripeStatus)
                            <p class="text-xs text-slate-500 mt-2 font-mono">Stripe: {{ $stripeStatus }}</p>
                        @endif
                    </div>
                    <div class="rounded-xl border border-slate-600/80 bg-slate-900/50 p-4">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 mb-2">Renovação / período atual</p>
                        @if ($subscriptionEnd)
                            <p class="text-lg font-bold text-white">{{ $subscriptionEnd }}</p>
                            <p class="text-xs text-slate-500 mt-1">Fim do período de cobrança atual (Stripe).</p>
                        @else
                            <p class="text-sm text-slate-400">Indisponível no momento.</p>
                        @endif
                    </div>
                </div>
                <div class="lg:col-span-2 space-y-4">
                    <div class="rounded-xl border border-blue-500/30 bg-blue-950/30 p-4">
                        <p class="text-xs font-semibold uppercase tracking-wide text-blue-300/80 mb-2">Próxima fatura</p>
                        @if ($invoiceUpcoming)
                            <div class="flex flex-wrap items-end justify-between gap-4">
                                <div>
                                    <p class="text-2xl font-extrabold text-white">{{ $invoiceUpcoming->total() }}</p>
                                    <p class="text-sm text-slate-400 mt-1">
                                        Data: {{ $invoiceUpcoming->date()->format('d/m/Y') }}
                                    </p>
                                </div>
                            </div>
                        @else
                            <p class="text-sm text-slate-400">Nenhuma fatura futura encontrada (ou dados ainda não disponíveis no Stripe).</p>
                        @endif
                    </div>

                    <div>
                        <p class="text-sm font-bold text-white mb-3">Faturas recentes</p>
                        <div class="space-y-2 max-h-64 overflow-y-auto pr-1">
                            @forelse ($recentInvoices as $invoice)
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 rounded-lg border border-slate-600/60 bg-slate-900/40 px-4 py-3">
                                    <div class="min-w-0">
                                        <p class="text-sm font-semibold text-white">
                                            {{ $invoice->date()->format('d/m/Y') }}
                                            <span class="text-slate-500 font-normal">· {{ $invoice->total() }}</span>
                                        </p>
                                        <p class="text-xs text-slate-500 capitalize">{{ $invoice->asStripeInvoice()->status }}</p>
                                    </div>
                                    <div class="flex flex-wrap gap-2 shrink-0">
                                        @if ($invoice->asStripeInvoice()->status === 'open' && $invoice->hosted_invoice_url)
                                            <a href="{{ $invoice->hosted_invoice_url }}" target="_blank" rel="noopener"
                                                class="inline-flex items-center gap-1 rounded-lg bg-amber-500/20 border border-amber-500/40 px-3 py-1.5 text-xs font-bold text-amber-200 hover:bg-amber-500/30">
                                                Pagar
                                            </a>
                                        @endif
                                        @if ($invoice->isPaid())
                                            <a href="{{ route('invoice.download', ['id' => $invoice->id]) }}"
                                                class="inline-flex items-center gap-1 rounded-lg bg-emerald-500/20 border border-emerald-500/40 px-3 py-1.5 text-xs font-bold text-emerald-200 hover:bg-emerald-500/30">
                                                PDF
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <p class="text-sm text-slate-500 py-4">Nenhuma fatura listada.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</x-admin-layout>
