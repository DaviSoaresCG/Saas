<x-admin-layout active="orders" title="Detalhe do pedido" subtitle="Itens incluídos neste pedido.">
    <div x-data="{ showClienteModal: false }" class="rounded-2xl border border-[var(--color-primary)]/80 bg-[var(--bg-card)] overflow-hidden shadow-xl shadow-black/15">
        
        <div class="px-5 py-4 border-b border-[var(--color-primary)]/80 flex flex-wrap items-center justify-between gap-3 bg-[var(--bg-card)]">
            <p class="text-sm text-[var(--text-muted)]">
                <span class="text-[var(--text-base)] font-semibold">Pedido #{{ $pedido->id }} — Itens</span>
            </p>

            <div class="flex items-center gap-2">
                <button type="button" @click="showClienteModal = true"
                    class="inline-flex items-center gap-2 rounded-lg border border-[var(--color-primary)]/40 bg-[var(--color-primary)]/10 px-3 py-2 text-sm font-semibold text-[var(--text-base)] hover:bg-[var(--color-primary)]/20 transition-colors cursor-pointer">
                    <i data-lucide="user" class="h-4 w-4 text-[var(--color-primary)]"></i>
                    Informações Cliente
                </button>
                <a href="{{ route('order.index') }}"
                    class="inline-flex items-center gap-2 rounded-lg border border-[var(--color-primary)]/40 bg-[var(--color-primary)]/10 px-3 py-2 text-sm font-semibold text-[var(--text-base)] hover:bg-[var(--color-primary)]/20 transition-colors">
                    <i data-lucide="list" class="h-4 w-4"></i>
                    Todos os pedidos
                </a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs font-semibold uppercase tracking-wide text-[var(--text-on-primary)] bg-[var(--color-primary)]/80 border-b border-[var(--color-primary)]/80">
                    <tr>
                        <th scope="col" class="px-4 sm:px-6 py-4">Produto</th>
                        <th scope="col" class="px-4 sm:px-6 py-4">Preço</th>
                        <th scope="col" class="px-4 sm:px-6 py-4">Qtd.</th>
                        <th scope="col" class="px-4 sm:px-6 py-4">Observação</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[var(--color-primary)]/60">
                    @foreach ($itens_pedido as $item)
                        @continue(!$item->product)
                        <tr class="hover:bg-[var(--color-primary)]/10 transition-colors cursor-pointer group"
                            onclick="window.location='{{ route('products.show', ['product' => $item->product->id]) }}'">
                            <td class="px-4 sm:px-6 py-4 font-semibold text-[var(--text-base)]">{{ $item->product->name }}</td>
                            <td class="px-4 sm:px-6 py-4 text-emerald-600 font-medium">R$ {{ $item->value }}</td>
                            <td class="px-4 sm:px-6 py-4 text-[var(--text-base)] tabular-nums">{{ $item->quantidade }}</td>
                            <td class="px-4 sm:px-6 py-4 text-[var(--text-base)] italic">{{ $item->observacao ?: '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if ($itens_pedido instanceof \Illuminate\Pagination\AbstractPaginator)
            <div class="px-4 py-4 border-t border-[var(--color-primary)]/80">
                <div class="text-[var(--text-base)] text-sm [&_a]:text-blue-500 [&_a:hover]:text-blue-400 [&_span]:text-[var(--text-muted)]">
                    {{ $itens_pedido->links() }}
                </div>
            </div>
        @endif

        {{-- Modal Informações do Cliente --}}
        <div x-show="showClienteModal"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4 backdrop-blur-xs"
            style="display: none;">
            <div @click.away="showClienteModal = false"
                class="w-full max-w-md rounded-3xl border border-[var(--color-primary)]/30 bg-[var(--bg-card)] p-6 shadow-2xl space-y-5">
                <div class="flex items-center justify-between border-b border-[var(--color-primary)]/20 pb-4">
                    <div class="flex items-center gap-2.5">
                        <div class="rounded-xl bg-[var(--color-primary)]/10 p-2 text-[var(--color-primary)]">
                            <i data-lucide="user-check" class="h-5 w-5"></i>
                        </div>
                        <h3 class="text-lg font-bold text-[var(--text-base)]">Informações do Cliente</h3>
                    </div>
                    <button type="button" @click="showClienteModal = false" class="rounded-lg p-1 text-[var(--text-muted)] hover:bg-[var(--color-primary)]/10 hover:text-[var(--text-base)] cursor-pointer">
                        <i data-lucide="x" class="h-5 w-5"></i>
                    </button>
                </div>

                <div class="space-y-4">
                    <div class="rounded-2xl border border-[var(--color-primary)]/15 bg-[var(--bg-page)]/50 p-4">
                        <span class="block text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider">Nome Completo</span>
                        <span class="block text-base font-bold text-[var(--text-base)] mt-1">{{ $pedido->cliente_nome ?: 'Não informado' }}</span>
                    </div>

                    <div class="rounded-2xl border border-[var(--color-primary)]/15 bg-[var(--bg-page)]/50 p-4">
                        <span class="block text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider">Celular / WhatsApp</span>
                        <div class="mt-1 flex items-center justify-between gap-2">
                            <span class="text-base font-bold text-[var(--text-base)] tabular-nums">{{ $pedido->cliente_phone ?: 'Não informado' }}</span>
                            @if ($pedido->cliente_phone)
                                <a href="https://wa.me/55{{ preg_replace('/\D/', '', $pedido->cliente_phone) }}" target="_blank"
                                    class="inline-flex items-center gap-1.5 rounded-xl bg-emerald-600 px-3 py-1.5 text-xs font-bold text-white shadow hover:bg-emerald-500 transition-colors">
                                    <i data-lucide="message-circle" class="h-3.5 w-3.5"></i>
                                    Abrir WhatsApp
                                </a>
                            @endif
                        </div>
                    </div>

                    <div class="rounded-2xl border border-[var(--color-primary)]/15 bg-[var(--bg-page)]/50 p-4">
                        <span class="block text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider">Total do Pedido</span>
                        <span class="block text-base font-extrabold text-emerald-600 mt-1 tabular-nums">R$ {{ $pedido->total }}</span>
                    </div>
                </div>

                <div class="pt-2">
                    <button type="button" @click="showClienteModal = false"
                        class="w-full rounded-2xl bg-[var(--color-primary)] py-3 text-center text-sm font-bold text-[var(--text-on-primary)] shadow-md hover:opacity-90 transition-opacity cursor-pointer">
                        Fechar
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>