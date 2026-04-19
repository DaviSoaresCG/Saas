<x-store-layout :page-title="$product->name">
    <div class="mb-6 flex flex-wrap items-center gap-2">
        <button type="button"
            onclick="history.back()"
            class="inline-flex cursor-pointer items-center gap-2 rounded-xl bg-[var(--color-primary)] px-3 py-2 text-sm font-semibold text-[var(--text-on-primary)]">
            <i data-lucide="arrow-left" class="h-4 w-4"></i>
            Voltar
        </button>
    </div>

    <div class="rounded-2xl border border-[var(--color-primary)]/80 bg-[var(--bg-card)] overflow-hidden shadow-xl shadow-black/20">
        <div class="lg:grid lg:grid-cols-2 lg:gap-0">
            <div class="relative aspect-square sm:aspect-[4/3] lg:aspect-auto lg:min-h-[420px] bg-[var(--bg-card)]">
                <img class="absolute inset-0 w-full h-full object-contain" src="{{ asset('storage/' . $product->path) }}"
                    alt="{{ $product->name }}" />
            </div>
            <div class="p-6 sm:p-8 flex flex-col justify-center">
                <h1 class="text-2xl sm:text-3xl font-extrabold text-[var(--text-base)] tracking-tight">
                    {{ $product->name }}
                </h1>
                <p class="mt-4 text-3xl font-extrabold text-emerald-600">
                    R$ {{ $product->value }}
                </p>

                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="{{ route('cart.add', ['id' => $product->id]) }}"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-[var(--color-primary)] px-6 py-3 text-sm font-bold text-[var(--text-on-primary)] shadow-lg transition-all">
                        <i data-lucide="shopping-cart" class="h-5 w-5"></i>
                        Adicionar ao carrinho
                    </a>
                </div>

                <hr class="my-8 border-slate-700/80" />

                <p class="text-[var(--text-base)] text-base leading-relaxed">
                    {{ $product->description }} 
                </p>
            </div>
        </div>
    </div>
</x-store-layout>
