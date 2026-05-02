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

                {{-- Form de adicionar ao carrinho (POST) --}}
                <form action="{{ route('cart.add', ['id' => $product->id]) }}" method="POST" class="mt-6 space-y-5">
                    @csrf

                    {{-- Atributos disponíveis --}}
                    @if ($product->atributos->isNotEmpty())
                        <div>
                            <p class="text-sm font-bold text-[var(--text-base)] mb-3">Escolha os atributos:</p>
                            <div class="flex flex-wrap gap-2">
                                @foreach ($product->atributos as $atributo)
                                    <label for="atr_c_{{ $atributo->id }}"
                                        class="flex items-center gap-2 rounded-xl border border-[var(--color-primary)]/30 bg-[var(--bg-page)]/60 px-3.5 py-2 text-sm text-[var(--text-base)] cursor-pointer transition-all
                                               has-[:checked]:border-[var(--color-primary)] has-[:checked]:bg-[var(--color-primary)]/15 has-[:checked]:font-bold has-[:checked]:shadow-sm">
                                        <input type="checkbox" id="atr_c_{{ $atributo->id }}"
                                            name="atributos[]" value="{{ $atributo->id }}"
                                            class="accent-[var(--color-primary)]">
                                        {{ $atributo->nome }}
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <button type="submit"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-[var(--color-primary)] px-6 py-3 text-sm font-bold text-[var(--text-on-primary)] shadow-lg transition-all hover:opacity-90 active:scale-95">
                        <i data-lucide="shopping-cart" class="h-5 w-5"></i>
                        Adicionar ao carrinho
                    </button>
                </form>

                <hr class="my-8 border-[var(--color-primary)]/20" />

                <p class="text-[var(--text-base)] text-base leading-relaxed">
                    {{ $product->description }}
                </p>
            </div>
        </div>
    </div>
</x-store-layout>

