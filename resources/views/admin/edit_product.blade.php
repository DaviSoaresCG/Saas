<x-admin-layout active="products" title="Editar produto" :subtitle="$product->name">
    <div class="max-w-2xl rounded-3xl border border-[var(--color-primary)]/15 bg-[var(--bg-card)] p-6 sm:p-8 shadow-2xl shadow-black/5">
        
        <form action="{{ route('products.update', ['product' => $product->id]) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @method('PATCH')
            @csrf
            <input type="hidden" name="slug" value="{{ auth()->user()->slug }}">
            
            <div>
                <label for="name" class="block text-sm font-bold text-[var(--text-base)] mb-2">Nome</label>
                <input type="text" name="name" value="{{ old('name', $product->name) }}" id="name" required
                    class="w-full rounded-2xl border border-[var(--color-primary)]/20 bg-[var(--bg-page)]/50 px-4 py-3.5 text-sm text-[var(--text-base)] outline-none transition-all focus:border-[var(--color-primary)]/50 focus:ring-4 focus:ring-[var(--color-primary)]/10 shadow-inner">
                @error('name') <p class="mt-2 text-sm font-medium text-red-600 ml-1">{{ $message }}</p> @enderror
            </div>
            
            <div>
                <label for="description" class="block text-sm font-bold text-[var(--text-base)] mb-2">Descrição</label>
                <textarea name="description" id="description" rows="3" required
                    class="w-full rounded-2xl border border-[var(--color-primary)]/20 bg-[var(--bg-page)]/50 px-4 py-3.5 text-sm text-[var(--text-base)] outline-none transition-all focus:border-[var(--color-primary)]/50 focus:ring-4 focus:ring-[var(--color-primary)]/10 shadow-inner resize-y">{{ old('description', $product->description) }}</textarea>
                @error('description') <p class="mt-2 text-sm font-medium text-red-600 ml-1">{{ $message }}</p> @enderror
            </div>
            
            <div>
                <label for="value" class="block text-sm font-bold text-[var(--text-base)] mb-2">Valor (R$)</label>
                <x-text-input id="value" type="tel" name="value" :value="old('value', $product->value)" required placeholder="0,00" x-data x-mask:dynamic="$money($input, ',', '.')"
                    class="block w-full !rounded-2xl !border-[var(--color-primary)]/20 !bg-[var(--bg-page)]/50 !px-4 !py-3.5 !text-sm !text-[var(--text-base)] outline-none transition-all" />
                @error('value') <p class="mt-2 text-sm font-medium text-red-600 ml-1">{{ $message }}</p> @enderror
            </div>

            {{-- ========= IMAGENS SALVAS COM DRAG-AND-DROP ========= --}}
            @if ($product->productImages->isNotEmpty())
            <div>
                <div class="flex items-center justify-between mb-3">
                    <label class="text-sm font-bold text-[var(--text-base)]">
                        Imagens atuais
                        <span class="text-[var(--text-muted)] font-normal text-xs">(arraste para reordenar · 1ª = capa)</span>
                    </label>
                    {{-- Toast de feedback --}}
                    <span id="reorder-toast"
                        class="hidden text-xs font-semibold text-emerald-400 flex items-center gap-1 transition-opacity">
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        Ordem salva
                    </span>
                </div>

                {{-- Grid sortável --}}
                <div id="sortable-images"
                    class="grid grid-cols-3 sm:grid-cols-4 gap-3 select-none">

                    @foreach ($product->productImages as $img)
                    <div class="sortable-item relative group rounded-xl overflow-hidden aspect-square border-2 border-[var(--color-primary)]/20 bg-[var(--bg-page)]/30 cursor-grab active:cursor-grabbing transition-all duration-200"
                        data-id="{{ $img->id }}">

                        <img src="{{ asset('storage/' . $img->path) }}"
                            class="w-full h-full object-cover pointer-events-none"
                            draggable="false" alt="">

                        {{-- Ícone de arrastar (canto superior direito) --}}
                        <div class="absolute top-1.5 right-1.5 rounded-lg bg-black/40 backdrop-blur-sm p-1 opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none">
                            <svg class="h-3.5 w-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 9h.01M8 12h.01M8 15h.01M12 9h.01M12 12h.01M12 15h.01M16 9h.01M16 12h.01M16 15h.01"/>
                            </svg>
                        </div>

                        {{-- Badge "Capa" (sempre no 1º item, atualizado por JS) --}}
                        <div class="capa-badge absolute top-1.5 left-1.5 hidden rounded-md bg-[var(--color-primary)] px-1.5 py-0.5 text-[10px] font-bold text-[var(--text-on-primary)] pointer-events-none shadow">
                            Capa
                        </div>

                        {{-- Overlay de deletar --}}
                        <form action="{{ route('products.image.destroy', $img) }}" method="POST"
                            class="absolute inset-0 flex items-end justify-center pb-2 opacity-0 group-hover:opacity-100 transition-opacity bg-gradient-to-t from-black/60 via-transparent to-transparent"
                            onsubmit="return confirm('Remover esta imagem?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="flex items-center gap-1 rounded-lg bg-red-600/90 px-2.5 py-1 text-xs font-bold text-white cursor-pointer hover:bg-red-600 transition-colors">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Remover
                            </button>
                        </form>
                    </div>
                    @endforeach

                </div>

                {{-- Hint de arrastar --}}
                <p class="mt-2 text-xs text-[var(--text-muted)] opacity-60 flex items-center gap-1.5">
                    <svg class="h-3.5 w-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                    </svg>
                    Arraste as imagens para reordenar. A ordem é salva automaticamente.
                </p>
            </div>
            @endif

            {{-- Adicionar novas imagens --}}
            <div x-data="imagePreview()">
                <label class="block text-sm font-bold text-[var(--text-base)] mb-2">
                    Adicionar imagens
                    <span class="text-[var(--text-muted)] font-normal text-xs">(opcional)</span>
                </label>
                <label for="images"
                    class="flex flex-col items-center justify-center gap-2 rounded-2xl border-2 border-dashed border-[var(--color-primary)]/30 bg-[var(--bg-page)]/40 px-4 py-6 cursor-pointer hover:border-[var(--color-primary)]/60 hover:bg-[var(--color-primary)]/5 transition-all">
                    <i data-lucide="image-plus" class="h-7 w-7 text-[var(--color-primary)] opacity-70"></i>
                    <span class="text-sm text-[var(--text-muted)]">Clique para adicionar mais imagens</span>
                    <input type="file" id="images" name="images[]" multiple accept="image/png,image/jpeg,image/webp"
                        class="hidden" @change="previewImages($event)">
                </label>
                <div x-show="previews.length > 0" class="mt-3 grid grid-cols-3 sm:grid-cols-4 gap-3">
                    <template x-for="(src, i) in previews" :key="i">
                        <div class="relative rounded-xl overflow-hidden aspect-square border border-[var(--color-primary)]/20">
                            <img :src="src" class="w-full h-full object-cover">
                        </div>
                    </template>
                </div>
            </div>

            {{-- Atributos --}}
            @if ($atributos->isNotEmpty())
            <div>
                <label class="block text-sm font-bold text-[var(--text-base)] mb-3">
                    Atributos do produto <span class="text-[var(--text-muted)] font-normal text-xs">(opcional)</span>
                </label>
                <div class="flex flex-wrap gap-2">
                    @foreach ($atributos as $atributo)
                        <label for="atr_{{ $atributo->id }}"
                            class="flex items-center gap-2 rounded-xl border border-[var(--color-primary)]/20 bg-[var(--bg-page)]/50 px-3 py-2 text-sm text-[var(--text-base)] cursor-pointer transition-all has-[:checked]:border-[var(--color-primary)] has-[:checked]:bg-[var(--color-primary)]/10 has-[:checked]:font-semibold">
                            <input type="checkbox" id="atr_{{ $atributo->id }}" name="atributos[]"
                                value="{{ $atributo->id }}" class="accent-[var(--color-primary)]"
                                {{ in_array($atributo->id, old('atributos', $atributosVinculados)) ? 'checked' : '' }}>
                            {{ $atributo->nome }}
                        </label>
                    @endforeach
                </div>
            </div>
            @endif

            <div class="flex flex-wrap gap-4 pt-4 mt-8 border-t border-[var(--color-primary)]/10">
                <button type="submit"
                    class="inline-flex items-center justify-center gap-2.5 rounded-2xl cursor-pointer bg-[var(--color-primary)] hover:opacity-90 px-7 py-3.5 text-sm font-bold text-[var(--text-on-primary)] shadow-lg shadow-[var(--color-primary)]/25 transition-all shrink-0 active:scale-95">
                    <i data-lucide="save" class="h-5 w-5"></i>
                    Salvar alterações
                </button>
                <a href="{{ route('admin.products') }}"
                    class="inline-flex items-center justify-center gap-2.5 rounded-2xl cursor-pointer bg-transparent border border-[var(--color-primary)]/20 px-7 py-3.5 text-sm font-bold text-[var(--text-base)] hover:bg-[var(--color-primary)]/10 transition-all shrink-0 active:scale-95">
                    Voltar à lista
                </a>
            </div>
        </form>
    </div>

    {{-- SortableJS (CDN leve, ~45 KB) --}}
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.3/Sortable.min.js"></script>

    <script>
        // ── Drag-and-drop de imagens salvas ──────────────────────────────
        const grid = document.getElementById('sortable-images');

        if (grid) {
            const reorderUrl = @json(route('products.images.reorder', $product));
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const toast    = document.getElementById('reorder-toast');

            // Marca o badge "Capa" sempre no 1º item visível
            function updateCapaBadge() {
                const items = grid.querySelectorAll('.sortable-item');
                items.forEach((el, i) => {
                    const badge = el.querySelector('.capa-badge');
                    if (badge) badge.classList.toggle('hidden', i !== 0);
                });
            }
            updateCapaBadge(); // Inicializa

            Sortable.create(grid, {
                animation:     180,
                easing:        'cubic-bezier(0.25, 1, 0.5, 1)',
                ghostClass:    'sortable-ghost',
                chosenClass:   'sortable-chosen',
                dragClass:     'sortable-drag',
                delay:         80,           // Previne arraste acidental em mobile
                delayOnTouchOnly: true,

                onEnd() {
                    updateCapaBadge();

                    // Coleta nova ordem de IDs
                    const order = [...grid.querySelectorAll('.sortable-item')]
                        .map(el => el.dataset.id);

                    fetch(reorderUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                        },
                        body: JSON.stringify({ order }),
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (data.ok) showToast();
                    })
                    .catch(() => {});
                },
            });

            function showToast() {
                toast.classList.remove('hidden');
                clearTimeout(toast._t);
                toast._t = setTimeout(() => toast.classList.add('hidden'), 2500);
            }
        }

        // ── Preview de novas imagens (Alpine) ────────────────────────────
        function imagePreview() {
            return {
                previews: [],
                previewImages(e) {
                    this.previews = [];
                    Array.from(e.target.files).forEach(file => {
                        const reader = new FileReader();
                        reader.onload = ev => this.previews.push(ev.target.result);
                        reader.readAsDataURL(file);
                    });
                }
            }
        }
    </script>

    <style>
        /* Estado "fantasma" (placeholder durante arraste) */
        .sortable-ghost {
            opacity: 0.35;
            border: 2px dashed var(--color-primary) !important;
            background: transparent !important;
        }
        /* Item escolhido (sendo arrastado) */
        .sortable-chosen {
            box-shadow: 0 12px 30px rgba(0,0,0,.35);
            transform: rotate(1.5deg) scale(1.05);
            z-index: 50;
            border-color: var(--color-primary) !important;
        }
        /* Animação de escala ao soltar */
        .sortable-item {
            transition: transform 0.18s ease, box-shadow 0.18s ease;
        }
    </style>
</x-admin-layout>