<x-admin-layout active="orders" title="Detalhe do pedido" subtitle="Itens incluídos neste pedido.">
    <div class="rounded-2xl border border-slate-700/80 bg-slate-800/40 overflow-hidden shadow-xl shadow-black/15">
        <div class="px-5 py-4 border-b border-slate-700/80 flex flex-wrap items-center justify-between gap-3">
            <p class="text-sm text-slate-400">
                Use <span class="text-white font-semibold">Voltar</span> para a lista anterior ou o menu lateral.
            </p>
            <a href="{{ route('order.index') }}"
                class="inline-flex items-center gap-2 rounded-lg border border-slate-600 bg-slate-900/50 px-3 py-2 text-sm font-semibold text-slate-200 hover:bg-slate-700/50 transition-colors">
                <i data-lucide="list" class="h-4 w-4"></i>
                Todos os pedidos
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs font-semibold uppercase tracking-wide text-slate-400 bg-slate-900/50 border-b border-slate-700/80">
                    <tr>
                        <th scope="col" class="px-4 sm:px-6 py-4">Produto</th>
                        <th scope="col" class="px-4 sm:px-6 py-4">Preço</th>
                        <th scope="col" class="px-4 sm:px-6 py-4">Qtd.</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700/60">
                    @foreach ($itens_pedido as $item)
                        @continue(!$item->product)
                        <tr class="hover:bg-slate-700/25 transition-colors cursor-pointer"
                            onclick="window.location='{{ route('products.show', ['product' => $item->product->id]) }}'">
                            <td class="px-4 sm:px-6 py-4 font-medium text-white">{{ $item->product->name }}</td>
                            <td class="px-4 sm:px-6 py-4 text-slate-300">R$ {{ $item->value }}</td>
                            <td class="px-4 sm:px-6 py-4 text-slate-400 tabular-nums">{{ $item->quantidade }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if ($itens_pedido instanceof \Illuminate\Pagination\AbstractPaginator)
            <div class="px-4 py-4 border-t border-slate-700/80">
                <div class="text-slate-400 text-sm [&_a]:text-blue-400 [&_a:hover]:text-blue-300">
                    {{ $itens_pedido->links() }}
                </div>
            </div>
        @endif
    </div>
</x-admin-layout>
