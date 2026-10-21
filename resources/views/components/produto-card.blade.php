@props(['produto', 'user'])
<div
    class="group rounded-2xl bg-[var(--bg-card)] overflow-hidden shadow-lg shadow-black/20  transition-all duration-300"
    @if(isset($user) && $user->tipo_cliente === 'erp')
        data-sku="{{ $produto->sku }}"
        data-peso="{{ $produto->peso }}"
    @endif
>
    <div class="aspect-[4/3] rounded-t-2xl overflow-hidden">
        <a href="{{ tenant_route('products.show', ['product' => $produto->id]) }}" class="block h-full">
            <img class="h-full w-full object-contain group-hover:scale-[1.03] transition-transform duration-500"
                src="{{ str_starts_with($produto->path ?? '', 'http') ? $produto->path : asset('storage/' . $produto->path) }}" alt="" loading="lazy" />
                
        </a>
    </div>
    <div class="p-3.5 sm:p-5">
        <a href="{{ tenant_route('products.show', ['product' => $produto->id]) }}"
            class="block text-sm sm:text-base font-bold text-[var(--text-base)] transition-colors line-clamp-2">
            {{ $produto->name }}
        </a>
        @if(isset($user) && $user->tipo_cliente === 'erp')
            <div class="my-2 border-t border-gray-300 pt-2">
                <p class="text-[var(--text-base)] font-medium text-xs sm:text-sm">SKU: {{ $produto->sku }}</p>
                <p class="text-[var(--text-muted)] font-medium text-xs sm:text-sm">Peso: {{ $produto->peso }}</p>
            </div>
        @endif
        
        <div class="mt-2.5 sm:mt-3 flex items-baseline gap-1.5 sm:gap-2 flex-wrap">
            @if (session('sob_consulta'))
                <span class="text-sm sm:text-lg font-bold text-amber-500">Preço Sob Consulta</span>
            @elseif ($produto->valor_com_desconto)
                <span class="text-base sm:text-xl font-extrabold text-emerald-600 tabular-nums">R$ {{ $produto->valor_com_desconto }}</span>
                <span class="text-xs sm:text-sm text-gray-500 line-through tabular-nums">R$ {{ $produto->value }}</span>
            @else
                <span class="text-base sm:text-xl font-extrabold text-emerald-600 tabular-nums">R$ {{ $produto->value }}</span>
            @endif
        </div>

        <form action="{{ tenant_route('cart.add', ['id' => $produto->id]) }}" method="POST"
            @if(isset($user) && $user->tipo_cliente === 'erp')
                data-sku="{{ $produto->sku }}"
                data-peso="{{ $produto->peso }}"
            @endif
        >
            @csrf
            <button type="submit"
                class="mt-3 sm:mt-4 flex w-full items-center justify-center gap-1.5 sm:gap-2 rounded-xl bg-[var(--color-primary)] px-2.5 sm:px-4 py-2 sm:py-2.5 text-xs sm:text-sm font-bold text-[var(--text-on-primary)] shadow-md shadow-blue-600/20 transition-colors cursor-pointer hover:opacity-90">
                <i data-lucide="shopping-cart" class="h-3.5 w-3.5 sm:hidden"></i>
                <span class="truncate">Adicionar ao carrinho</span>
            </button>
        </form>
    </div>
</div>