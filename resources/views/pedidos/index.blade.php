<x-admin-layout active="orders" title="Pedidos" subtitle="Busque por ID e acompanhe os pedidos da sua loja.">
    @if (session('error'))
        <div class="mb-6 rounded-xl border border-red-500/40 bg-red-950/40 px-4 py-3 text-sm text-red-200">
            {{ session('error') }}
        </div>
    @endif

    <div
        class="rounded-2xl border border-[var(--color-primary)]/80 bg-[var(--bg-card)] overflow-hidden shadow-xl shadow-black/15">
        <div class="p-4 sm:p-5 border-b border-[var(--color-primary)]/80 bg-[var(--bg-card)]">
            <form action="{{ route('order.search') }}" method="GET"
                class="flex flex-col sm:flex-row gap-3 sm:items-end">
                <div class="flex-1 min-w-0">
                    <x-input-base name="id" type="text" label="Buscar por ID do pedido" icon="search"
                        placeholder="Ex: 3" value="{{ old('id') }}" />
                </div>
                <button type="submit"
                    class="inline-flex items-center justify-center gap-2 rounded-xl cursor-pointer bg-[var(--color-primary)] px-5 py-2.5 text-sm font-bold text-[var(--text-on-primary)] shadow-lg transition-colors shrink-0">
                    <i data-lucide="search" class="h-4 w-4"></i>
                    Buscar
                </button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead
                    class="text-xs font-semibold uppercase tracking-wide text-[var(--text-on-primary)] bg-[var(--color-primary)]/80 border-b border-[var(--color-primary)]/80">
                    <tr>
                        <th scope="col" class="px-4 sm:px-6 py-4">ID</th>
                        <th scope="col" class="px-4 sm:px-6 py-4">Total</th>
                        <th scope="col" class="px-4 sm:px-6 py-4">Data</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[var(--color-primary)]/60">
                    @forelse ($pedidos as $pedido)
                        <tr class="hover:bg-[var(--color-primary)]/10 transition-colors cursor-pointer group"
                            onclick="window.location='{{ route('order.show', ['id' => $pedido->id]) }}'">
                            <td class="px-4 sm:px-6 py-4 font-semibold text-[var(--text-base)]">#{{ $pedido->id }}</td>
                            <td class="px-4 sm:px-6 py-4 text-emerald-600 font-medium">R$ {{ $pedido->total }}</td>
                            <td class="px-4 sm:px-6 py-4 text-[var--text-muted]">
                                {{ $pedido->created_at->format('d/m/Y H:i') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-16 text-center text-[var(--text-base)]">
                                <i data-lucide="inbox" class="h-10 w-10 mx-auto mb-3 opacity-50"></i>
                                Nenhum pedido encontrado.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if (!$search && $pedidos instanceof \Illuminate\Pagination\AbstractPaginator)
            <div class="px-4 py-4 border-t border-[var(--color-primary)]/80">
                <div
                    class="text-[var(--text-base)] text-sm [&_a]:text-blue-400 [&_a:hover]:text-blue-300 [&_span]:text-slate-500">
                    {{ $pedidos->links() }}
                </div>
            </div>
        @endif
    </div>
</x-admin-layout>