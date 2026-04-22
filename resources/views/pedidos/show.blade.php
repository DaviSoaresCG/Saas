<x-admin-layout active="orders" title="Detalhe do pedido" subtitle="Itens incluídos neste pedido.">
    <div class="rounded-2xl border border-[var(--color-primary)]/80 bg-[var(--bg-card)] overflow-hidden shadow-xl shadow-black/15">
        
        <div class="px-5 py-4 border-b border-[var(--color-primary)]/80 flex flex-wrap items-center justify-between gap-3 bg-[var(--bg-card)]">
            <p class="text-sm text-[var(--text-muted)]">
                <span class="text-[var(--text-base)] font-semibold">Todos os itens do pedido
            </p>
            <a href="{{ route('order.index') }}"
                class="inline-flex items-center gap-2 rounded-lg border border-[var(--color-primary)]/40 bg-[var(--color-primary)]/10 px-3 py-2 text-sm font-semibold text-[var(--text-base)] hover:bg-[var(--color-primary)]/20 transition-colors">
                <i data-lucide="list" class="h-4 w-4"></i>
                Todos os pedidos
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs font-semibold uppercase tracking-wide text-[var(--text-on-primary)] bg-[var(--color-primary)]/80 border-b border-[var(--color-primary)]/80">
                    <tr>
                        <th scope="col" class="px-4 sm:px-6 py-4">Produto</th>
                        <th scope="col" class="px-4 sm:px-6 py-4">Preço</th>
                        <th scope="col" class="px-4 sm:px-6 py-4">Qtd.</th>
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
    </div>
</x-admin-layout>