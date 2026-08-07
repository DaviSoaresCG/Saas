<x-store-layout :page-title="$product->name" :slug="$user->slug">
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
                    @if($hasMultiple)
                    @foreach ($images as $i => $img)
                        <img src="{{ str_starts_with($img->path ?? '', 'https://') ? $img->path : asset('storage/' . $img->path) }}"
                            alt="{{ $product->name }}"
                            class="absolute inset-0 w-full h-full object-contain transition-opacity duration-400"
                            :class="active === {{ $i }} ? 'opacity-100 z-10' : 'opacity-0 z-0'">
                    @endforeach
                    @else
                        <img src="{{ str_starts_with($product->path ?? '', 'https://') ? $product->path : asset('storage/' . $product->path) }}"
                            alt="{{ $product->name }}"
                            class="absolute inset-0 w-full h-full object-contain transition-opacity duration-400 opacity-100 z-10">
                    @endif

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
                    @endif
                </div>

                {{-- Thumbnails (somente quando há mais de 1 imagem) --}}
                @if ($hasMultiple)
                    <div class="flex gap-2 p-3 overflow-x-auto bg-[var(--bg-card)] border-t border-[var(--color-primary)]/20">
                        @foreach ($images as $i => $img)
                            <button @click="active = {{ $i }}" type="button"
                                class="flex-shrink-0 h-14 w-14 rounded-xl overflow-hidden border-2 transition-all"
                                :class="active === {{ $i }} ? 'border-[var(--color-primary)] opacity-100' : 'border-transparent opacity-50 hover:opacity-75'">
                                <img src="{{ str_starts_with($img->path ?? '', 'http') ? $img->path : asset('storage/' . $img->path) }}" class="w-full h-full object-cover" alt="">
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
                
                <div class="mt-4 flex items-baseline gap-2">
                    @if ($product->valor_com_desconto)
                        <span class="text-3xl font-extrabold text-emerald-600">R$ {{ $product->valor_com_desconto }}</span>
                        <span class="text-lg text-gray-500 line-through">R$ {{ $product->value }}</span>
                    @else
                        <span class="text-3xl font-extrabold text-emerald-600">R$ {{ $product->value }}</span>
                    @endif
                </div>

                {{-- Form de adicionar ao carrinho (POST) --}}
                <form action="{{ tenant_route('cart.add', ['id' => $product->id]) }}" method="POST" class="mt-6 space-y-5">
                    @csrf

                    <button type="submit"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-[var(--color-primary)] px-6 py-3 text-sm font-bold text-[var(--text-on-primary)] shadow-lg transition-all hover:opacity-90 active:scale-95 cursor-pointer">
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
