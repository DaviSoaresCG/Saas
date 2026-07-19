<x-admin-layout active="products" title="Editar produto" :subtitle="$product->name">
    <div class="max-w-2xl rounded-3xl border border-[var(--color-primary)]/15 bg-[var(--bg-card)] p-6 sm:p-8 shadow-2xl shadow-black/5">
        
        <form id="product-form" action="{{ route('products.update', ['slug' => auth()->user()->slug, 'product' => $product->id]) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
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

            {{-- ========= IMAGENS DO PRODUTO (SALVAS E NOVAS) ========= --}}
            <div>
                <div class="flex items-center justify-between mb-3">
                    <label class="text-sm font-bold text-[var(--text-base)]">
                        Imagens do produto
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
                    class="grid grid-cols-3 sm:grid-cols-4 gap-3 select-none mb-4">

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
                        <div class="absolute inset-0 flex items-end justify-center pb-2 opacity-0 group-hover:opacity-100 transition-opacity bg-gradient-to-t from-black/60 via-transparent to-transparent">
                            <button type="button"
                                onclick="if(confirm('Remover esta imagem?')) { document.getElementById('delete-image-form-{{ $img->id }}').submit(); }"
                                class="flex items-center gap-1 rounded-lg bg-red-600/90 px-2.5 py-1 text-xs font-bold text-white cursor-pointer hover:bg-red-600 transition-colors">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Remover
                            </button>
                        </div>
                    </div>
                    @endforeach

                </div>

                {{-- Drop zone --}}
                <label for="images-input"
                    id="drop-zone"
                    class="flex flex-col items-center justify-center gap-2 rounded-2xl border-2 border-dashed border-[var(--color-primary)]/30 bg-[var(--bg-page)]/40 px-4 py-8 cursor-pointer hover:border-[var(--color-primary)]/60 hover:bg-[var(--color-primary)]/5 transition-all mb-4"
                    ondragover="event.preventDefault(); this.classList.add('border-[var(--color-primary)]')"
                    ondragleave="this.classList.remove('border-[var(--color-primary)]')"
                    ondrop="handleDrop(event)">
                    <i data-lucide="image-plus" class="h-8 w-8 text-[var(--color-primary)] opacity-70"></i>
                    <span class="text-sm text-[var(--text-muted)]">Clique ou arraste as imagens aqui para adicionar</span>
                    <span class="text-xs text-[var(--text-muted)] opacity-60">JPG, PNG, WebP — máx. 10 MB por imagem</span>
                    <input type="file" id="images-input" multiple accept="image/png,image/jpeg,image/webp"
                        class="hidden" onchange="uploadSelectedFiles(this.files)">
                </label>

                {{-- Hint de arrastar --}}
                <p class="text-xs text-[var(--text-muted)] opacity-60 flex items-center gap-1.5">
                    <svg class="h-3.5 w-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                    </svg>
                    Arraste as imagens para reordenar. A ordem é salva automaticamente.
                </p>
            </div>



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

        @if ($product->productImages->isNotEmpty())
            @foreach ($product->productImages as $img)
                <form id="delete-image-form-{{ $img->id }}" action="{{ route('products.image.destroy', ['slug' => auth()->user()->slug, 'image' => $img->id]) }}" method="POST" class="hidden">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="image" value="{{ $img->id }}">
                </form>
            @endforeach
        @endif
    </div>

    {{-- SortableJS (CDN leve, ~45 KB) --}}
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.3/Sortable.min.js"></script>

    <script>
        // ── Drag-and-drop de imagens salvas ──────────────────────────────
        const grid = document.getElementById('sortable-images');

        if (grid) {
            const reorderUrl = @json(route('products.images.reorder', ['slug' => auth()->user()->slug, 'product' => $product->id]));
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
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                        },
                        body: JSON.stringify({ order }),
                    })
                    .then(async r => {
                        if (!r.ok) {
                            const errData = await r.json().catch(() => ({ message: 'Server error' }));
                            throw new Error(errData.message || 'Erro ao ordenar');
                        }
                        return r.json();
                    })
                    .then(data => {
                        if (data.ok) showToast();
                    })
                    .catch(err => {
                        console.error('Reorder error:', err);
                    });
                },
            });

            function showToast() {
                toast.classList.remove('hidden');
                clearTimeout(toast._t);
                toast._t = setTimeout(() => toast.classList.add('hidden'), 2500);
            }
        }

        // ── Upload instantâneo e gerenciamento de novas imagens ─────────────
        function handleDrop(e) {
            e.preventDefault();
            document.getElementById('drop-zone').classList.remove('border-[var(--color-primary)]');
            uploadSelectedFiles(e.dataTransfer.files);
        }

        function uploadSelectedFiles(files) {
            const grid = document.getElementById('sortable-images');
            if (!grid) return;

            const uploadUrl = @json(route('products.image.upload', ['slug' => auth()->user()->slug, 'product' => $product->id]));
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            Array.from(files).forEach(file => {
                // 1. Cria um item temporário com loading spinner
                const tempId = 'temp-' + Date.now() + '-' + Math.random().toString(36).substr(2, 9);
                const tempDiv = document.createElement('div');
                tempDiv.id = tempId;
                tempDiv.className = 'relative rounded-xl overflow-hidden aspect-square border-2 border-dashed border-[var(--color-primary)]/40 bg-[var(--bg-page)]/20 flex items-center justify-center';
                tempDiv.innerHTML = `
                    <div class="flex flex-col items-center gap-1.5 text-[var(--color-primary)]">
                        <svg class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                        <span class="text-[10px] opacity-70">Enviando...</span>
                    </div>
                `;
                grid.appendChild(tempDiv);

                // 2. Faz o upload via AJAX
                const formData = new FormData();
                formData.append('image', file);

                fetch(uploadUrl, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: formData
                })
                .then(async r => {
                    if (!r.ok) {
                        const errData = await r.json().catch(() => ({ message: 'Server error' }));
                        throw new Error(errData.message || `Erro no servidor (${r.status})`);
                    }
                    return r.json();
                })
                .then(data => {
                    if (data.ok) {
                        // 3. Substitui o item temporário pela imagem real carregada
                        const realDiv = document.createElement('div');
                        realDiv.className = 'sortable-item relative group rounded-xl overflow-hidden aspect-square border-2 border-[var(--color-primary)]/20 bg-[var(--bg-page)]/30 cursor-grab active:cursor-grabbing transition-all duration-200';
                        realDiv.dataset.id = data.id;
                        realDiv.innerHTML = `
                            <img src="${data.path}" class="w-full h-full object-cover pointer-events-none" draggable="false" alt="">
                            <div class="absolute top-1.5 right-1.5 rounded-lg bg-black/40 backdrop-blur-sm p-1 opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none">
                                <svg class="h-3.5 w-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9h.01M8 12h.01M8 15h.01M12 9h.01M12 12h.01M12 15h.01M16 9h.01M16 12h.01M16 15h.01"/>
                                </svg>
                            </div>
                            <div class="capa-badge absolute top-1.5 left-1.5 hidden rounded-md bg-[var(--color-primary)] px-1.5 py-0.5 text-[10px] font-bold text-[var(--text-on-primary)] pointer-events-none shadow">
                                Capa
                            </div>
                            <div class="absolute inset-0 flex items-end justify-center pb-2 opacity-0 group-hover:opacity-100 transition-opacity bg-gradient-to-t from-black/60 via-transparent to-transparent">
                                <button type="button"
                                    onclick="if(confirm('Remover esta imagem?')) { document.getElementById('delete-image-form-' + ${data.id}).submit(); }"
                                    class="flex items-center gap-1 rounded-lg bg-red-600/90 px-2.5 py-1 text-xs font-bold text-white cursor-pointer hover:bg-red-600 transition-colors">
                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Remover
                                </button>
                            </div>
                        `;
                        tempDiv.replaceWith(realDiv);

                        // 4. Cria o form de exclusão correspondente dinamicamente
                        const form = document.createElement('form');
                        form.id = `delete-image-form-${data.id}`;
                        form.action = @js(route('products.image.destroy', ['slug' => auth()->user()->slug, 'image' => 'PLACEHOLDER'])).replace('PLACEHOLDER', data.id);
                        form.method = 'POST';
                        form.className = 'hidden';
                        form.innerHTML = `
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="image" value="${data.id}">
                        `;
                        document.getElementById('product-form').appendChild(form);

                        // 5. Atualiza o badge de capa e a ordem do backend
                        updateCapaBadge();
                    } else {
                        tempDiv.remove();
                        alert('Erro ao enviar imagem.');
                    }
                })
                .catch(err => {
                    tempDiv.remove();
                    console.error(err);
                    alert('Erro na conexão ao enviar imagem: ' + err.message);
                });
            });
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