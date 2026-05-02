@props(['produto', 'user'])
<div
    class="group rounded-2xl bg-[var(--bg-card)] overflow-hidden shadow-lg shadow-black/20  transition-all duration-300">
    <div class="aspect-[4/3] rounded-t-2xl overflow-hidden">
        <a href="{{ route('products.show', ['product' => $produto->id, 'slug' => $user->slug]) }}" class="block h-full">
            <img class="h-full w-full object-contain group-hover:scale-[1.03] transition-transform duration-500"
                src="{{ asset('storage/' . $produto->path) }}" alt="" loading="lazy" />
        </a>
    </div>
    <div class="p-4 sm:p-5">
        <a href="{{ route('products.show', ['product' => $produto->id, 'slug' => $user->slug]) }}"
            class="block text-base font-bold text-[var(--text-base)] transition-colors line-clamp-2">
            {{ $produto->name }}
        </a>
        <p class="mt-3 text-xl font-extrabold text-emerald-600 tabular-nums">R$ {{ $produto->value }}</p>

        @if ($produto->atributos->isNotEmpty())
            {{-- Produto com atributos: vai para a página do produto para o cliente escolher --}}
            <a href="{{ route('products.show', ['product' => $produto->id, 'slug' => $user->slug]) }}"
                class="mt-4 flex w-full items-center justify-center gap-2 rounded-xl bg-[var(--color-primary)] px-4 py-2.5 text-sm font-bold text-[var(--text-on-primary)] shadow-md shadow-blue-600/20 transition-colors hover:opacity-90">
                <i data-lucide="tag" class="h-4 w-4"></i>
                <span>Ver opções</span>
            </a>
        @else
            {{-- Produto sem atributos: adiciona direto ao carrinho --}}
            <form action="{{ route('cart.add', ['id' => $produto->id]) }}" method="POST">
                @csrf
                <button type="submit"
                    class="mt-4 flex w-full items-center justify-center gap-2 rounded-xl bg-[var(--color-primary)] px-4 py-2.5 text-sm font-bold text-[var(--text-on-primary)] shadow-md shadow-blue-600/20 transition-colors cursor-pointer hover:opacity-90">
                    <span>Adicionar ao carrinho</span>
                </button>
            </form>
        @endif
    </div>
</div>