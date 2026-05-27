<x-store-layout :page-title="$product->name">
    <div class="mb-6 flex flex-wrap items-center gap-2">
        <button type="button" onclick="history.back()"
            class="inline-flex cursor-pointer items-center gap-2 rounded-xl bg-[var(--color-primary)] px-3 py-2 text-sm font-semibold text-[var(--text-on-primary)]">
            <i data-lucide="arrow-left" class="h-4 w-4"></i>
            Voltar
        </button>
    </div>

    <div class="rounded-2xl border border-[var(--color-primary)]/80 bg-[var(--bg-card)] overflow-hidden shadow-xl shadow-black/20">
        <div class="lg:grid lg:grid-cols-2 lg:gap-0">

            {{-- Carrossel de imagens --}}
            @php
                $images = $product->productImages;
                $hasMultiple = $images->count() > 1;
            @endphp

            <div x-data="carousel({{ $images->count() }})" class="relative bg-[var(--bg-card)] select-none">
                {{-- Imagem principal --}}
                <div class="relative aspect-square sm:aspect-[4/3] lg:aspect-auto lg:min-h-[420px] overflow-hidden">
                    @foreach ($images as $i => $img)
                        <img src="{{ asset('storage/' . $img->path) }}"
                            alt="{{ $product->name }}"
                            class="absolute inset-0 w-full h-full object-contain transition-opacity duration-400"
                            :class="active === {{ $i }} ? 'opacity-100 z-10' : 'opacity-0 z-0'">
                    @endforeach

                    {{-- Setas (somente quando há mais de 1 imagem) --}}
                    @if ($hasMultiple)
                        <button @click="prev()" type="button"
                            class="absolute left-2 top-1/2 -translate-y-1/2 z-20 flex h-9 w-9 items-center justify-center rounded-full bg-black/40 text-white hover:bg-black/60 transition-colors">
                            <i data-lucide="chevron-left" class="h-5 w-5"></i>
                        </button>
                        <button @click="next()" type="button"
                            class="absolute right-2 top-1/2 -translate-y-1/2 z-20 flex h-9 w-9 items-center justify-center rounded-full bg-black/40 text-white hover:bg-black/60 transition-colors">
                            <i data-lucide="chevron-right" class="h-5 w-5"></i>
                        </button>

                        {{-- Indicadores --}}
                        <div class="absolute bottom-3 left-1/2 -translate-x-1/2 z-20 flex gap-1.5">
                            @foreach ($images as $i => $img)
                                <button @click="active = {{ $i }}" type="button"
                                    class="h-1.5 rounded-full transition-all duration-300"
                                    :class="active === {{ $i }} ? 'w-5 bg-white' : 'w-1.5 bg-white/50'">
                                </button>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Thumbnails (somente quando há mais de 1 imagem) --}}
                @if ($hasMultiple)
                    <div class="flex gap-2 p-3 overflow-x-auto bg-[var(--bg-card)] border-t border-[var(--color-primary)]/20">
                        @foreach ($images as $i => $img)
                            <button @click="active = {{ $i }}" type="button"
                                class="flex-shrink-0 h-14 w-14 rounded-xl overflow-hidden border-2 transition-all"
                                :class="active === {{ $i }} ? 'border-[var(--color-primary)] opacity-100' : 'border-transparent opacity-50 hover:opacity-75'">
                                <img src="{{ asset('storage/' . $img->path) }}" class="w-full h-full object-cover" alt="">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Info do produto --}}
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

                    @if ($product->atributos->isNotEmpty())
                        <div>
                            <p class="text-sm font-bold text-[var(--text-base)] mb-3">Escolha os atributos:</p>
                            <div class="flex flex-wrap gap-2">
                                @foreach ($product->atributos as $atributo)
                                    <label for="atr_c_{{ $atributo->id }}"
                                        class="flex items-center gap-2 rounded-xl border border-[var(--color-primary)]/30 bg-[var(--bg-page)]/60 px-3.5 py-2 text-sm text-[var(--text-base)] cursor-pointer transition-all has-[:checked]:border-[var(--color-primary)] has-[:checked]:bg-[var(--color-primary)]/15 has-[:checked]:font-bold">
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

    <script>
        function carousel(total) {
            return {
                active: 0,
                total: total,
                next() { this.active = (this.active + 1) % this.total; },
                prev() { this.active = (this.active - 1 + this.total) % this.total; },
            }
        }
    </script>
</x-store-layout>
