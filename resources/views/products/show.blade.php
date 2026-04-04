<x-store-layout :page-title="$product->name">
    <div class="mb-6 flex flex-wrap items-center gap-2">
        <button type="button"
            onclick="(function(){try{var r=document.referrer;if(r&&new URL(r).hostname===location.hostname){history.back();return;}}catch(e){} window.location.href='{{ route('products.index') }}';})();"
            class="inline-flex items-center gap-2 rounded-xl border border-slate-600 bg-slate-800/80 px-3 py-2 text-sm font-semibold text-slate-200 hover:bg-slate-700/80 transition-colors">
            <i data-lucide="arrow-left" class="h-4 w-4"></i>
            Voltar ao catálogo
        </button>
    </div>

    <div class="rounded-2xl border border-slate-700/80 bg-slate-800/40 overflow-hidden shadow-xl shadow-black/20">
        <div class="lg:grid lg:grid-cols-2 lg:gap-0">
            <div class="relative aspect-square sm:aspect-[4/3] lg:aspect-auto lg:min-h-[320px] bg-slate-900/50">
                <img class="absolute inset-0 w-full h-full object-cover" src="{{ asset('storage/' . $product->path) }}"
                    alt="{{ $product->name }}" />
            </div>
            <div class="p-6 sm:p-8 flex flex-col justify-center">
                <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">
                    {{ $product->name }}
                </h1>
                <p class="mt-4 text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-indigo-400">
                    R$ {{ $product->value }}
                </p>

                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="{{ route('cart.add', ['id' => $product->id]) }}"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 hover:bg-blue-700 px-6 py-3 text-sm font-bold text-white shadow-lg shadow-blue-600/25 transition-all">
                        <i data-lucide="shopping-cart" class="h-5 w-5"></i>
                        Adicionar ao carrinho
                    </a>
                </div>

                <hr class="my-8 border-slate-700/80" />

                <p class="text-slate-400 text-sm leading-relaxed">
                    {{ $product->description }}
                </p>
            </div>
        </div>
    </div>
</x-store-layout>
