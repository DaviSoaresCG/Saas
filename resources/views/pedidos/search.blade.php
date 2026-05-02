{{-- Mantida para compatibilidade; o fluxo principal usa pedidos.index com resultados da busca. --}}
<x-admin-layout active="orders" title="Busca de pedido" subtitle="Resultado da pesquisa.">
    <div class="rounded-2xl border border-slate-700/80 bg-slate-800/40 p-6 text-center text-slate-400">
        <p class="text-sm">Esta tela não é usada pelo controller atual. Redirecione para a lista de pedidos.</p>
        <a href="{{ route('order.index') }}"
            class="mt-4 inline-flex items-center gap-2 text-blue-400 font-semibold hover:text-blue-300">
            Ir para pedidos
        </a>
    </div>
</x-admin-layout>