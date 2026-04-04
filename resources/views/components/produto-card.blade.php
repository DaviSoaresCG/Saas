@props(['produto', 'user'])
<div class="group rounded-2xl border border-slate-700/80 bg-slate-800/40 overflow-hidden shadow-lg shadow-black/20 hover:border-blue-500/40 hover:bg-slate-800/60 transition-all duration-300">
    <div class="aspect-[4/3] overflow-hidden bg-slate-900/50">
        <a href="{{ route('products.show', ['product' => $produto->id, 'slug' => $user->slug]) }}" class="block h-full">
            <img class="h-full w-full object-cover group-hover:scale-[1.03] transition-transform duration-500"
                src="{{ asset('storage/' . $produto->path) }}" alt="" loading="lazy" />
        </a>
    </div>
    <div class="p-4 sm:p-5">
        <a href="{{ route('products.show', ['product' => $produto->id, 'slug' => $user->slug]) }}"
            class="block text-base font-bold text-white hover:text-blue-400 transition-colors line-clamp-2">
            {{ $produto->name }}
        </a>
        <p class="mt-3 text-xl font-extrabold text-emerald-400 tabular-nums">R$ {{ $produto->value }}</p>
        <a href="{{ route('cart.add', ['id' => $produto->id]) }}"
            class="mt-4 flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 hover:bg-blue-700 px-4 py-2.5 text-sm font-bold text-white shadow-md shadow-blue-600/20 transition-colors">
            <span>Adicionar</span>
        </a>
    </div>
</div>
