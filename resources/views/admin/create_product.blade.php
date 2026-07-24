<x-admin-layout active="products-create" title="Novo produto" subtitle="Preencha os dados para publicar no catálogo.">
    <div class="max-w-2xl rounded-3xl border border-[var(--color-primary)]/15 bg-[var(--bg-card)] p-6 sm:p-8 shadow-2xl shadow-black/5">
        
        <form id="product-form" action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            <div>
                <label for="name" class="block text-sm font-bold text-[var(--text-base)] mb-2">Nome do produto</label>
                <input type="text" name="name" value="{{ old('name') }}" id="name" required
                    class="w-full rounded-2xl border border-[var(--color-primary)]/20 bg-[var(--bg-page)]/50 px-4 py-3.5 text-sm text-[var(--text-base)] placeholder-[var(--text-muted)]/70 outline-none transition-all focus:border-[var(--color-primary)]/50 focus:ring-4 focus:ring-[var(--color-primary)]/10 shadow-inner"
                    placeholder="Ex: Camiseta básica">
                @error('name') <p class="mt-2 text-sm font-medium text-red-500">{{ $message }}</p> @enderror
            </div>
            
            <div>
                <label for="description" class="block text-sm font-bold text-[var(--text-base)] mb-2">Descrição</label>
                <input type="text" name="description" id="description" value="{{ old('description') }}" required
                    class="w-full rounded-2xl border border-[var(--color-primary)]/20 bg-[var(--bg-page)]/50 px-4 py-3.5 text-sm text-[var(--text-base)] placeholder-[var(--text-muted)]/70 outline-none transition-all focus:border-[var(--color-primary)]/50 focus:ring-4 focus:ring-[var(--color-primary)]/10 shadow-inner"
                    placeholder="Breve descrição">
                @error('description') <p class="mt-2 text-sm font-medium text-red-500">{{ $message }}</p> @enderror
            </div>
            
            <div>
                <x-input-label for="value" :value="__('Valor (R$)')" class="!text-[var(--text-base)] !font-bold !mb-2" />
                <x-text-input id="value" type="tel" name="value" :value="old('value')" required placeholder="0,00" x-data x-mask:dynamic="$money($input, ',', '.')"
                    class="block w-full !rounded-2xl !border-[var(--color-primary)]/20 !bg-[var(--bg-page)]/50 !px-4 !py-3.5 !text-sm !text-[var(--text-base)] placeholder-[var(--text-muted)]/70 outline-none transition-all" />
                <x-input-error :messages="$errors->get('value')" class="mt-2 text-red-600 font-medium text-sm" />
            </div>

            {{-- ========= UPLOAD + REORDENAÇÃO DE PREVIEWS ========= --}}
            <div>
                <label class="block text-sm font-bold text-[var(--text-base)] mb-2">
                    Imagens do produto
                    <span class="text-[var(--text-muted)] font-normal text-xs">(arraste para ordenar · 1ª = capa)</span>
                </label>

                {{-- Drop zone --}}
                <label for="images-input"
                    id="drop-zone"
                    class="flex flex-col items-center justify-center gap-2 rounded-2xl border-2 border-dashed border-[var(--color-primary)]/30 bg-[var(--bg-page)]/40 px-4 py-8 cursor-pointer hover:border-[var(--color-primary)]/60 hover:bg-[var(--color-primary)]/5 transition-all"
                    ondragover="event.preventDefault(); this.classList.add('border-[var(--color-primary)]')"
                    ondragleave="this.classList.remove('border-[var(--color-primary)]')"
                    ondrop="handleDrop(event)">
                    <i data-lucide="image-plus" class="h-8 w-8 text-[var(--color-primary)] opacity-70"></i>
                    <span class="text-sm text-[var(--text-muted)]">Clique ou arraste as imagens aqui</span>
                    <span class="text-xs text-[var(--text-muted)] opacity-60">JPG, PNG, WebP — máx. 10 MB por imagem</span>
                    <input type="file" id="images-input" multiple accept="image/png,image/jpeg,image/webp"
                        class="hidden" onchange="addFiles(this.files)">
                </label>

                {{-- Grid de previews (sortável) --}}
                <div id="preview-grid" class="mt-4 grid grid-cols-3 sm:grid-cols-4 gap-3" style="display:none"></div>

                {{-- Input hidden real para o form --}}
                <input type="file" id="images-real" name="images[]" multiple accept="image/png,image/jpeg,image/webp" class="hidden" required>

                @error('images') <p class="mt-2 text-sm font-medium text-red-500">{{ $message }}</p> @enderror
                @error('images.*') <p class="mt-2 text-sm font-medium text-red-500">{{ $message }}</p> @enderror
            </div>

            @if (isset($grupos) && $grupos->isNotEmpty())
            <div>
                <label class="block text-sm font-bold text-[var(--text-base)] mb-2">
                    Grupos / Categorias
                    <span class="text-[var(--text-muted)] font-normal text-xs">(selecione onde exibir o produto)</span>
                </label>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5">
                    @foreach ($grupos as $grupo)
                        <label class="flex items-center gap-2.5 rounded-2xl border border-[var(--color-primary)]/20 bg-[var(--bg-page)]/40 p-3 cursor-pointer hover:border-[var(--color-primary)]/50 hover:bg-[var(--color-primary)]/5 transition-all">
                            <input type="checkbox" name="grupos[]" value="{{ $grupo->id }}"
                                {{ in_array($grupo->id, old('grupos', [])) ? 'checked' : '' }}
                                class="rounded-lg border-[var(--color-primary)]/30 bg-[var(--bg-card)] text-[var(--color-primary)] focus:ring-0 h-4 w-4">
                            @if ($grupo->foto_path)
                                <img src="{{ $grupo->foto_url }}" class="h-6 w-6 rounded-lg object-cover" alt="">
                            @else
                                <i data-lucide="layers" class="h-4 w-4 text-[var(--color-primary)] opacity-70"></i>
                            @endif
                            <span class="text-xs font-semibold text-[var(--text-base)] truncate">{{ $grupo->nome }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
            @endif

            <div class="flex flex-wrap gap-4 pt-4 mt-8 border-t border-[var(--color-primary)]/10">
                <button type="submit" id="submit-btn"
                    class="inline-flex items-center justify-center gap-2.5 rounded-2xl cursor-pointer bg-[var(--color-primary)] hover:opacity-90 px-7 py-3.5 text-sm font-bold text-[var(--text-on-primary)] shadow-lg shadow-[var(--color-primary)]/25 transition-all shrink-0 active:scale-95">
                    <i data-lucide="check" class="h-5 w-5"></i>
                    Salvar produto
                </button>
                <a href="{{ route('admin.products') }}"
                    class="inline-flex items-center justify-center gap-2.5 rounded-2xl cursor-pointer bg-transparent border border-[var(--color-primary)]/20 px-7 py-3.5 text-sm font-bold text-[var(--text-base)] hover:bg-[var(--color-primary)]/10 transition-all shrink-0 active:scale-95">
                    Cancelar
                </a>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.3/Sortable.min.js"></script>

    <script>
    // ── Gerenciador de arquivos (mantém a ordem correta) ────────────────
    let fileList = []; // Array de File objects na ordem atual

    function addFiles(newFiles) {
        Array.from(newFiles).forEach(f => fileList.push(f));
        renderPreviews();
    }

    function handleDrop(e) {
        e.preventDefault();
        document.getElementById('drop-zone').classList.remove('border-[var(--color-primary)]');
        addFiles(e.dataTransfer.files);
    }

    function renderPreviews() {
        const grid = document.getElementById('preview-grid');
        grid.innerHTML = '';
        grid.style.display = fileList.length ? 'grid' : 'none';

        fileList.forEach((file, i) => {
            const url = URL.createObjectURL(file);
            const div = document.createElement('div');
            div.className = 'sortable-item relative group rounded-xl overflow-hidden aspect-square border-2 border-[var(--color-primary)]/20 bg-[var(--bg-page)]/30 cursor-grab active:cursor-grabbing';
            div.dataset.index = i;
            div.innerHTML = `
                <img src="${url}" class="w-full h-full object-cover pointer-events-none" draggable="false">
                ${i === 0 ? `<div class="capa-badge absolute top-1.5 left-1.5 rounded-md bg-[var(--color-primary)] px-1.5 py-0.5 text-[10px] font-bold text-[var(--text-on-primary)] shadow pointer-events-none">Capa</div>` : ''}
                <button type="button" onclick="removeFile(${i})"
                    class="absolute top-1.5 right-1.5 flex items-center justify-center h-6 w-6 rounded-lg bg-red-600/80 text-white opacity-0 opacity-100 transition-opacity bg-red-600 cursor-pointer">
                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/50 to-transparent py-1 px-2 opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none">
                    <p class="text-[10px] text-white/80 truncate">${file.name}</p>
                </div>
            `;
            grid.appendChild(div);
        });

        // Inicializa/reinicia SortableJS no grid de previews
        if (window._sortableCreate) window._sortableCreate.destroy();
        window._sortableCreate = Sortable.create(grid, {
            animation: 180,
            easing: 'cubic-bezier(0.25, 1, 0.5, 1)',
            ghostClass: 'sortable-ghost',
            chosenClass: 'sortable-chosen',
            delay: 80,
            delayOnTouchOnly: true,
            onEnd() {
                // Reordena fileList de acordo com a nova posição dos elementos
                const newOrder = [...grid.querySelectorAll('.sortable-item')]
                    .map(el => parseInt(el.dataset.index));
                fileList = newOrder.map(i => fileList[i]);
                renderPreviews(); // Re-renderiza com índices corrigidos
            },
        });

        // Sincroniza o input real com os arquivos na ordem correta
        syncRealInput();
    }

    function removeFile(index) {
        fileList.splice(index, 1);
        renderPreviews();
    }

    function syncRealInput() {
        // Recria a FileList no input real via DataTransfer
        const dt = new DataTransfer();
        fileList.forEach(f => dt.items.add(f));
        document.getElementById('images-real').files = dt.files;
    }

    // Antes de submeter, sincroniza os arquivos na ordem atual
    document.getElementById('product-form').addEventListener('submit', function(e) {
        if (fileList.length === 0) {
            e.preventDefault();
            alert('Adicione pelo menos uma imagem para o produto.');
            return;
        }
        syncRealInput();
    });
    </script>

    <style>
        .sortable-ghost {
            opacity: 0.3;
            border: 2px dashed var(--color-primary) !important;
            background: transparent !important;
        }
        .sortable-chosen {
            box-shadow: 0 12px 30px rgba(0,0,0,.35);
            transform: rotate(1.5deg) scale(1.06);
            z-index: 50;
            border-color: var(--color-primary) !important;
        }
        .sortable-item {
            transition: transform 0.18s ease, box-shadow 0.18s ease;
        }
        #preview-grid {
            grid-template-columns: repeat(3, 1fr);
        }
        @media (min-width: 640px) {
            #preview-grid { grid-template-columns: repeat(4, 1fr); }
        }
    </style>
</x-admin-layout>