<x-admin-layout active="products" title="Seus produtos" subtitle="Busque, edite ou publique novos itens.">
    <div
        class="rounded-2xl border border-[var(--color-primary)]/15 bg-[var(--bg-card)] overflow-hidden shadow-xl shadow-black/15">

        <div
            class="p-4 sm:p-5 border-b border-[var(--color-primary)]/80 bg-[var(--bg-card)] flex flex-col md:flex-row gap-4 md:items-end md:justify-between">
            <form action="{{ route('products.search') }}"
                class="flex-1 min-w-0 flex flex-col sm:flex-row gap-3 sm:items-end" method="POST">
                @csrf
                <div class="flex-1 min-w-0">
                    <x-input-base name="search" type="text" icon="search" placeholder="Nome do produto"
                        label="Buscar produto" value="{{ old('search', '') }}" />
                </div>
                <button type="submit"
                    class="inline-flex items-center justify-center gap-2 rounded-xl cursor-pointer bg-[var(--color-primary)] px-5 py-2.5 text-sm font-bold text-[var(--text-on-primary)] shadow-lg hover:scale-105 transition-all shrink-0">
                    <i data-lucide="search" class="h-4 w-4"></i>
                    Buscar
                </button>
            </form>

            <a href="{{ route('products.create') }}"
                class="inline-flex items-center justify-center gap-2 rounded-xl cursor-pointer bg-[var(--color-primary)] px-5 py-2.5 text-sm font-bold text-[var(--text-on-primary)] shadow-lg hover:scale-105 transition-all shrink-0">
                <i data-lucide="plus" class="h-4 w-4"></i>
                Novo produto
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead
                    class="text-xs font-semibold uppercase tracking-wide text-[var(--text-on-primary)] bg-[var(--color-primary)]/80 border-b border-[var(--color-primary)]/80">
                    <tr>
                        <th scope="col" class="px-4 sm:px-6 py-4">Nome</th>
                        <th scope="col" class="px-4 sm:px-6 py-4">Preço</th>
                        <th scope="col" class="px-4 sm:px-6 py-4 text-right"><span class="sr-only">Ações</span></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[var(--color-primary)]/60">
                    @foreach ($products as $product)
                        <tr class="hover:bg-[var(--color-primary)]/10 transition-colors group">
                            <td class="px-4 sm:px-6 py-4">
                                <a href="{{ route('products.show', ['product' => $product->id]) }}"
                                    class="font-semibold text-[var(--text-base)] hover:opacity-70 transition-opacity">
                                    {{ $product->name }}
                                </a>
                            </td>
                            <td class="px-4 sm:px-6 py-4 text-emerald-600 font-medium tabular-nums">R$ {{ $product->value }}
                            </td>
                            <td class="px-4 sm:px-6 py-4">
                                <div class="flex flex-wrap items-center justify-end gap-2">
                                    <a href="{{ route('products.edit', ['product' => $product->id]) }}"
                                        class="inline-flex items-center gap-1 rounded-lg border border-[var(--color-primary)]/40 bg-[var(--color-primary)]/10 px-3 py-1.5 text-xs font-semibold text-[var(--text-base)] hover:bg-[var(--color-primary)]/20 transition-colors">
                                        Editar
                                    </a>
                                    <form action="{{ route('products.destroy', ['product' => $product->id]) }}"
                                        method="post" class="inline" onsubmit="return confirm('Remover este produto?');">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="slug" value="{{ auth()->user()->slug }}">
                                        <button type="submit"
                                            class="inline-flex items-center gap-1 rounded-lg border border-red-500/40 bg-red-500 px-3 py-1.5 text-xs font-semibold text-white cursor-pointer hover:bg-red-600 transition-colors">
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
            <div class="px-4 py-4 border-t border-[var(--color-primary)]/80">
                <div
                    class="text-[var(--text-base)] text-sm [&_a]:text-blue-500 [&_a:hover]:text-blue-400 [&_span]:text-[var(--text-muted)]">
                    {{ $products->links() }}
                </div>
            </div>
        @endif
    </div>
</x-admin-layout>