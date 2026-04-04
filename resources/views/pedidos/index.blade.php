<x-admin-layout active="orders" title="Pedidos" subtitle="Busque por ID e acompanhe os pedidos da sua loja.">
    @if (session('error'))
        <div class="mb-6 rounded-xl border border-red-500/40 bg-red-950/40 px-4 py-3 text-sm text-red-200">
            {{ session('error') }}
        </div>
    @endif

    <div class="rounded-2xl border border-slate-700/80 bg-slate-800/40 overflow-hidden shadow-xl shadow-black/15">
        <div class="p-4 sm:p-5 border-b border-slate-700/80">
            <form action="{{ route('order.search') }}" method="GET" class="flex flex-col sm:flex-row gap-3 sm:items-end">
                <div class="flex-1 min-w-0">
                    <label for="order-search-id" class="block text-xs font-semibold uppercase tracking-wide text-slate-500 mb-2">Buscar por ID do pedido</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-500">
                            <i data-lucide="search" class="h-4 w-4"></i>
                        </div>
                        <input type="text" id="order-search-id" name="id" value="{{ request('id') }}"
                            class="w-full rounded-xl border border-slate-600 bg-slate-900/60 pl-10 pr-4 py-2.5 text-sm text-white placeholder-slate-500 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/30 outline-none transition-shadow"
                            placeholder="Ex: 42" required>
                    </div>
                </div>
                <button type="submit"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 hover:bg-blue-700 px-5 py-2.5 text-sm font-bold text-white shadow-lg shadow-blue-600/20 transition-colors shrink-0">
                    <i data-lucide="search" class="h-4 w-4"></i>
                    Buscar
                </button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs font-semibold uppercase tracking-wide text-slate-400 bg-slate-900/50 border-b border-slate-700/80">
                    <tr>
                        <th scope="col" class="px-4 sm:px-6 py-4">ID</th>
                        <th scope="col" class="px-4 sm:px-6 py-4">Total</th>
                        <th scope="col" class="px-4 sm:px-6 py-4">Data</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700/60">
                    @forelse ($pedidos as $pedido)
                        <tr class="hover:bg-slate-700/25 transition-colors cursor-pointer group"
                            onclick="window.location='{{ route('order.show', ['id' => $pedido->id]) }}'">
                            <td class="px-4 sm:px-6 py-4 font-semibold text-white">#{{ $pedido->id }}</td>
                            <td class="px-4 sm:px-6 py-4 text-emerald-400 font-medium">R$ {{ $pedido->total }}</td>
                            <td class="px-4 sm:px-6 py-4 text-slate-400">{{ $pedido->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-16 text-center text-slate-500">
                                <i data-lucide="inbox" class="h-10 w-10 mx-auto mb-3 opacity-50"></i>
                                Nenhum pedido encontrado.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if (! $search && $pedidos instanceof \Illuminate\Pagination\AbstractPaginator)
            <div class="px-4 py-4 border-t border-slate-700/80">
                <div class="text-slate-400 text-sm [&_a]:text-blue-400 [&_a:hover]:text-blue-300 [&_span]:text-slate-500">
                    {{ $pedidos->links() }}
                </div>
            </div>
        @endif
    </div>
</x-admin-layout>
