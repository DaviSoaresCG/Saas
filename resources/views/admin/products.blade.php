<x-admin-layout active="products" title="Seus produtos" subtitle="Busque, edite ou publique novos itens.">
    <div class="rounded-2xl border border-slate-700/80 bg-slate-800/40 overflow-hidden shadow-xl shadow-black/15">
        <div class="p-4 sm:p-5 border-b border-slate-700/80 flex flex-col md:flex-row gap-4 md:items-end md:justify-between">
            <form action="{{ route('products.search') }}" class="flex-1 min-w-0 flex flex-col sm:flex-row gap-3 sm:items-end" method="POST">
                @csrf
                <div class="flex-1 min-w-0">
                    <label for="product-search" class="block text-xs font-semibold uppercase tracking-wide text-slate-500 mb-2">Buscar por nome</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-500">
                            <i data-lucide="search" class="h-4 w-4"></i>
                        </div>
                        <input type="text" id="product-search" name="search"
                            class="w-full rounded-xl border border-slate-600 bg-slate-900/60 pl-10 pr-4 py-2.5 text-sm text-white placeholder-slate-500 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/30 outline-none"
                            placeholder="Nome do produto" required value="{{ old('search') }}">
                    </div>
                    @error('search')
                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 hover:bg-blue-700 px-5 py-2.5 text-sm font-bold text-white shrink-0">
                    <i data-lucide="search" class="h-4 w-4"></i>
                    Buscar
                </button>
            </form>
            <a href="{{ route('products.create') }}"
                class="inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 px-5 py-2.5 text-sm font-bold text-white shadow-lg shadow-emerald-600/20 shrink-0">
                <i data-lucide="plus" class="h-4 w-4"></i>
                Novo produto
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs font-semibold uppercase tracking-wide text-slate-400 bg-slate-900/50 border-b border-slate-700/80">
                    <tr>
                        <th scope="col" class="px-4 sm:px-6 py-4">Nome</th>
                        <th scope="col" class="px-4 sm:px-6 py-4">Preço</th>
                        <th scope="col" class="px-4 sm:px-6 py-4 text-right"><span class="sr-only">Ações</span></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700/60">
                    @foreach ($products as $product)
                        <tr class="hover:bg-slate-700/20 transition-colors">
                            <td class="px-4 sm:px-6 py-4">
                                <a href="{{ route('products.show', ['product' => $product->id]) }}"
                                    class="font-semibold text-white hover:text-blue-400 transition-colors">
                                    {{ $product->name }}
                                </a>
                            </td>
                            <td class="px-4 sm:px-6 py-4 text-emerald-400 font-medium tabular-nums">R$ {{ $product->value }}</td>
                            <td class="px-4 sm:px-6 py-4">
                                <div class="flex flex-wrap items-center justify-end gap-2">
                                    <a href="{{ route('products.edit', ['product' => $product->id]) }}"
                                        class="inline-flex items-center gap-1 rounded-lg border border-slate-600 bg-slate-900/50 px-3 py-1.5 text-xs font-semibold text-slate-200 hover:bg-slate-700/50">
                                        Editar
                                    </a>
                                    <form action="{{ route('products.destroy', ['product' => $product->id]) }}" method="post" class="inline"
                                        onsubmit="return confirm('Remover este produto?');">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="slug" value="{{ auth()->user()->slug }}">
                                        <button type="submit"
                                            class="inline-flex items-center gap-1 rounded-lg border border-red-500/40 bg-red-950/30 px-3 py-1.5 text-xs font-semibold text-red-300 hover:bg-red-950/50">
                                            Excluir
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if ($link)
            <div class="px-4 py-4 border-t border-slate-700/80 text-slate-400 text-sm [&_a]:text-blue-400 [&_a:hover]:text-blue-300">
                {{ $products->links() }}
            </div>
        @endif
    </div>
</x-admin-layout>
