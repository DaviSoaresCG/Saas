<x-admin-layout active="grupos" title="Grupos de Produtos" subtitle="Crie grupos/categorias com fotos opcionais para organizar e filtrar seus produtos no catálogo.">
    <div class="space-y-8">
        @if (session('success'))
            <div class="flex items-center gap-3 rounded-2xl border border-emerald-500/40 bg-emerald-500/10 px-5 py-4 text-sm font-semibold text-emerald-400 backdrop-blur-sm">
                <i data-lucide="check-circle-2" class="h-5 w-5 shrink-0 text-emerald-400"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="flex items-center gap-3 rounded-2xl border border-red-500/40 bg-red-500/10 px-5 py-4 text-sm font-semibold text-red-400 backdrop-blur-sm">
                <i data-lucide="alert-circle" class="h-5 w-5 shrink-0 text-red-400"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        {{-- CARD DE CRIAÇÃO DE GRUPO --}}
        <div class="rounded-3xl border border-[var(--color-primary)]/20 bg-[var(--bg-card)] p-6 sm:p-8 shadow-2xl shadow-black/5 backdrop-blur-sm">
            <h2 class="text-lg font-extrabold text-[var(--text-base)] flex items-center gap-2 mb-1">
                <i data-lucide="plus-circle" class="h-5 w-5 text-[var(--color-primary)]"></i>
                Criar Novo Grupo
            </h2>
            <p class="text-xs text-[var(--text-muted)] mb-6">Cadastre uma nova categoria para agrupar seus produtos. A foto é opcional.</p>

            <form action="{{ route('grupos.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf
                
                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label for="nome" class="block text-sm font-bold text-[var(--text-base)] mb-2">Nome do grupo <span class="text-red-500">*</span></label>
                        <input type="text" name="nome" value="{{ old('nome') }}" id="nome" required
                            class="w-full rounded-2xl border border-[var(--color-primary)]/20 bg-[var(--bg-page)]/50 px-4 py-3.5 text-sm text-[var(--text-base)] placeholder-[var(--text-muted)]/70 outline-none transition-all focus:border-[var(--color-primary)]/50 focus:ring-4 focus:ring-[var(--color-primary)]/10 shadow-inner"
                            placeholder="Ex: Bebidas, Promoções, Lanches...">
                        @error('nome') <p class="mt-2 text-xs font-medium text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="foto" class="block text-sm font-bold text-[var(--text-base)] mb-2">
                            Foto do grupo <span class="text-[var(--text-muted)] font-normal text-xs">(Opcional)</span>
                        </label>
                        <div class="flex items-center gap-3">
                            <label for="foto-create" class="flex-1 flex items-center gap-2 rounded-2xl border border-dashed border-[var(--color-primary)]/30 bg-[var(--bg-page)]/40 px-4 py-3 cursor-pointer hover:border-[var(--color-primary)]/60 hover:bg-[var(--color-primary)]/5 transition-all text-xs text-[var(--text-muted)] truncate">
                                <i data-lucide="image-plus" class="h-4 w-4 shrink-0 text-[var(--color-primary)]"></i>
                                <span id="foto-create-label" class="truncate">Escolher imagem...</span>
                                <input type="file" name="foto" id="foto-create" accept="image/png,image/jpeg,image/webp" class="hidden" onchange="previewCreateFoto(this)">
                            </label>
                            <div id="foto-create-preview-wrapper" class="hidden h-11 w-11 shrink-0 rounded-xl overflow-hidden border border-[var(--color-primary)]/30 bg-[var(--bg-page)]">
                                <img id="foto-create-preview" src="" class="h-full w-full object-cover" alt="Preview">
                            </div>
                        </div>
                        @error('foto') <p class="mt-2 text-xs font-medium text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="pt-2 flex justify-end">
                    <button type="submit"
                        class="inline-flex items-center gap-2 rounded-2xl bg-[var(--color-primary)] hover:opacity-90 px-6 py-3.5 text-sm font-bold text-[var(--text-on-primary)] shadow-lg shadow-[var(--color-primary)]/20 transition-all active:scale-95 cursor-pointer">
                        <i data-lucide="save" class="h-4 w-4"></i>
                        <span>Salvar Grupo</span>
                    </button>
                </div>
            </form>
        </div>

        {{-- LISTA DE GRUPOS --}}
        <div class="rounded-3xl border border-[var(--color-primary)]/20 bg-[var(--bg-card)] p-6 sm:p-8 shadow-2xl shadow-black/5 backdrop-blur-sm">
            <div class="flex items-center justify-between gap-4 mb-6">
                <div>
                    <h3 class="text-lg font-extrabold text-[var(--text-base)]">Grupos Cadastrados</h3>
                    <p class="text-xs text-[var(--text-muted)]">Gerencie os grupos e vincule os produtos correspondentes</p>
                </div>
                <span class="rounded-full bg-[var(--color-primary)]/10 px-3.5 py-1 text-xs font-bold text-[var(--color-primary)]">
                    {{ $grupos->count() }} {{ $grupos->count() === 1 ? 'grupo' : 'grupos' }}
                </span>
            </div>

            @if ($grupos->isEmpty())
                <div class="flex flex-col items-center justify-center rounded-2xl border-2 border-dashed border-[var(--color-primary)]/20 py-12 px-4 text-center">
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-[var(--color-primary)]/10 text-[var(--color-primary)] mb-3">
                        <i data-lucide="layers" class="h-7 w-7"></i>
                    </div>
                    <h4 class="text-base font-bold text-[var(--text-base)] mb-1">Nenhum grupo cadastrado</h4>
                    <p class="text-xs text-[var(--text-muted)] max-w-sm">Crie seu primeiro grupo acima para organizar os produtos da sua loja em categorias.</p>
                </div>
            @else
                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($grupos as $grupo)
                        @php
                            $associatedProductIds = $grupo->products->pluck('id')->toArray();
                        @endphp
                        <div class="group relative flex flex-col justify-between gap-4 rounded-2xl border border-[var(--color-primary)]/15 bg-[var(--bg-page)]/40 p-4 transition-all hover:border-[var(--color-primary)]/40 hover:bg-[var(--bg-page)]/80 hover:shadow-lg">
                            <div class="flex items-center gap-3 min-w-0">
                                {{-- Foto ou Avatar --}}
                                <div class="relative flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl overflow-hidden border border-[var(--color-primary)]/20 bg-[var(--color-primary)]/10">
                                    @if ($grupo->foto_path)
                                        <img src="{{ $grupo->foto_url }}" alt="{{ $grupo->nome }}" class="h-full w-full object-cover">
                                    @else
                                        <i data-lucide="layers" class="h-6 w-6 text-[var(--color-primary)] opacity-70"></i>
                                    @endif
                                </div>

                                {{-- Informações --}}
                                <div class="min-w-0 flex-1">
                                    <h4 class="font-bold text-sm text-[var(--text-base)] truncate">{{ $grupo->nome }}</h4>
                                    <p class="text-xs text-[var(--text-muted)] mt-0.5 flex items-center gap-1">
                                        <i data-lucide="package" class="h-3.5 w-3.5 opacity-60"></i>
                                        <span>{{ $grupo->products_count }} {{ $grupo->products_count === 1 ? 'produto' : 'produtos' }}</span>
                                    </p>
                                </div>
                            </div>

                            {{-- Ações --}}
                            <div class="pt-3 border-t border-[var(--color-primary)]/10 flex items-center justify-between gap-2">
                                <button type="button"
                                    onclick="openSyncModal({{ $grupo->id }}, '{{ addslashes($grupo->nome) }}', {{ json_encode($associatedProductIds) }})"
                                    class="inline-flex items-center gap-1.5 rounded-xl bg-[var(--color-primary)]/10 hover:bg-[var(--color-primary)]/20 px-3 py-1.5 text-xs font-bold text-[var(--color-primary)] transition-colors cursor-pointer">
                                    <i data-lucide="package-plus" class="h-3.5 w-3.5"></i>
                                    <span>Vincular Produtos</span>
                                </button>

                                <div class="flex items-center gap-1">
                                    <button type="button"
                                        onclick="openEditModal({{ $grupo->id }}, '{{ addslashes($grupo->nome) }}', '{{ $grupo->foto_url ?? '' }}')"
                                        class="flex h-8 w-8 items-center justify-center rounded-xl bg-blue-500/10 text-blue-400 hover:bg-blue-500/20 transition-colors cursor-pointer"
                                        title="Editar grupo">
                                        <i data-lucide="pencil" class="h-3.5 w-3.5"></i>
                                    </button>
                                    
                                    <form action="{{ route('grupos.destroy', ['slug' => auth()->user()->slug, 'grupo' => $grupo->id]) }}" method="POST"
                                        onsubmit="return confirm('Tem certeza que deseja excluir o grupo &quot;{{ addslashes($grupo->nome) }}&quot;?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="flex h-8 w-8 items-center justify-center rounded-xl bg-red-500/10 text-red-400 hover:bg-red-500/20 transition-colors cursor-pointer"
                                            title="Excluir grupo">
                                            <i data-lucide="trash-2" class="h-3.5 w-3.5"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- MODAL DE VINCULAR PRODUTOS AO GRUPO --}}
    <div id="sync-products-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/70 backdrop-blur-sm p-4 transition-all duration-300">
        <div class="relative w-full max-w-lg bg-[var(--bg-card)] border border-[var(--color-primary)]/30 rounded-3xl p-6 sm:p-8 shadow-2xl text-[var(--text-base)] max-h-[90vh] flex flex-col">
            <button type="button" onclick="closeSyncModal()" class="absolute top-5 right-5 text-[var(--text-muted)] hover:text-[var(--text-base)] transition-colors cursor-pointer">
                <i data-lucide="x" class="h-6 w-6"></i>
            </button>

            <div class="mb-4 flex items-center gap-3 shrink-0">
                <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-[var(--color-primary)]/10 text-[var(--color-primary)]">
                    <i data-lucide="package-plus" class="h-5 w-5"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-[var(--text-base)]" id="sync-modal-title">Vincular Produtos</h3>
                    <p class="text-xs text-[var(--text-muted)]">Selecione quais produtos fazem parte deste grupo</p>
                </div>
            </div>

            {{-- Campo de Busca e Botões Selecionar Todos --}}
            <div class="mb-4 space-y-2 shrink-0">
                <div class="relative">
                    <i data-lucide="search" class="absolute left-3.5 top-1/2 -translate-y-1/2 h-4 w-4 text-[var(--text-muted)]"></i>
                    <input type="text" id="sync-search-input" oninput="filterModalProducts(this.value)" placeholder="Buscar produto por nome..."
                        class="w-full rounded-2xl border border-[var(--color-primary)]/20 bg-[var(--bg-page)]/50 pl-10 pr-4 py-2.5 text-xs text-[var(--text-base)] placeholder-[var(--text-muted)]/70 outline-none focus:border-[var(--color-primary)]/50">
                </div>
                <div class="flex items-center justify-between text-xs text-[var(--text-muted)]">
                    <button type="button" onclick="toggleSelectAllModalProducts(true)" class="hover:text-[var(--color-primary)] cursor-pointer">Selecionar Todos</button>
                    <button type="button" onclick="toggleSelectAllModalProducts(false)" class="hover:text-red-400 cursor-pointer">Desmarcar Todos</button>
                </div>
            </div>

            <form id="sync-products-form" action="" method="POST" class="flex-1 flex flex-col min-h-0">
                @csrf

                {{-- Lista Rolável de Produtos --}}
                <div class="flex-1 overflow-y-auto space-y-2 pr-1 my-2 min-h-36 max-h-72">
                    @if ($allProducts->isEmpty())
                        <div class="text-center py-8 text-xs text-[var(--text-muted)]">
                            Nenhum produto cadastrado no sistema.
                        </div>
                    @else
                        @foreach ($allProducts as $prod)
                            <label class="product-item-row flex items-center gap-3 rounded-2xl border border-[var(--color-primary)]/15 bg-[var(--bg-page)]/30 p-2.5 cursor-pointer hover:border-[var(--color-primary)]/40 hover:bg-[var(--color-primary)]/5 transition-all"
                                data-name="{{ mb_strtolower($prod->nome) }}">
                                <input type="checkbox" name="products[]" value="{{ $prod->id }}" class="product-checkbox rounded-lg border-[var(--color-primary)]/30 bg-[var(--bg-card)] text-[var(--color-primary)] focus:ring-0 h-4 w-4 shrink-0">
                                
                                <div class="h-9 w-9 shrink-0 rounded-xl overflow-hidden bg-[var(--color-primary)]/10 border border-[var(--color-primary)]/20 flex items-center justify-center">
                                    @if ($prod->foto_url)
                                        <img src="{{ asset('storage/' . $prod->path) }}" class="h-full w-full object-cover" alt="">
                                    @else
                                        <i data-lucide="package" class="h-4 w-4 text-[var(--color-primary)] opacity-60"></i>
                                    @endif
                                </div>

                                <span class="text-xs font-semibold text-[var(--text-base)] truncate flex-1">{{ $prod->nome }}</span>
                            </label>
                        @endforeach
                    @endif
                </div>

                <div class="pt-4 border-t border-[var(--color-primary)]/10 flex items-center justify-end gap-3 shrink-0 mt-2">
                    <button type="button" onclick="closeSyncModal()"
                        class="rounded-2xl border border-[var(--color-primary)]/20 px-5 py-2.5 text-xs font-bold text-[var(--text-muted)] hover:bg-[var(--color-primary)]/10 transition-all cursor-pointer">
                        Cancelar
                    </button>
                    <button type="submit"
                        class="inline-flex items-center gap-1.5 rounded-2xl bg-[var(--color-primary)] hover:opacity-90 px-6 py-2.5 text-xs font-bold text-[var(--text-on-primary)] shadow-lg shadow-[var(--color-primary)]/20 transition-all active:scale-95 cursor-pointer">
                        <i data-lucide="check" class="h-4 w-4"></i>
                        <span>Salvar Vinculação</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL DE EDIÇÃO DE GRUPO --}}
    <div id="edit-grupo-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/70 backdrop-blur-sm p-4 transition-all duration-300">
        <div class="relative w-full max-w-md bg-[var(--bg-card)] border border-[var(--color-primary)]/30 rounded-3xl p-6 sm:p-8 shadow-2xl text-[var(--text-base)]">
            <button type="button" onclick="closeEditModal()" class="absolute top-5 right-5 text-[var(--text-muted)] hover:text-[var(--text-base)] transition-colors cursor-pointer">
                <i data-lucide="x" class="h-6 w-6"></i>
            </button>

            <div class="mb-5 flex items-center gap-3">
                <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-[var(--color-primary)]/10 text-[var(--color-primary)]">
                    <i data-lucide="pencil" class="h-5 w-5"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-[var(--text-base)]">Editar Grupo</h3>
                    <p class="text-xs text-[var(--text-muted)]">Atualize o nome ou a foto do grupo</p>
                </div>
            </div>

            <form id="edit-grupo-form" action="" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label for="edit-nome" class="block text-sm font-bold text-[var(--text-base)] mb-1.5">Nome do grupo <span class="text-red-500">*</span></label>
                    <input type="text" name="nome" id="edit-nome" required
                        class="w-full rounded-2xl border border-[var(--color-primary)]/20 bg-[var(--bg-page)]/50 px-4 py-3 text-sm text-[var(--text-base)] outline-none focus:border-[var(--color-primary)]/50 focus:ring-4 focus:ring-[var(--color-primary)]/10">
                </div>

                <div>
                    <label class="block text-sm font-bold text-[var(--text-base)] mb-1.5">Foto do grupo</label>
                    <div id="edit-foto-current-wrapper" class="hidden mb-3 items-center gap-3 rounded-2xl border border-[var(--color-primary)]/15 bg-[var(--bg-page)]/40 p-2.5">
                        <img id="edit-foto-current-img" src="" class="h-12 w-12 rounded-xl object-cover border border-[var(--color-primary)]/20" alt="">
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-semibold text-[var(--text-base)]">Foto atual</p>
                            <label class="inline-flex items-center gap-1.5 text-[11px] text-red-400 hover:text-red-300 cursor-pointer mt-0.5">
                                <input type="checkbox" name="remover_foto" value="1" class="rounded border-gray-600 bg-gray-700 text-red-500 focus:ring-0">
                                <span>Remover foto atual</span>
                            </label>
                        </div>
                    </div>

                    <label for="edit-foto-input" class="flex items-center gap-2 rounded-2xl border border-dashed border-[var(--color-primary)]/30 bg-[var(--bg-page)]/40 px-4 py-3 cursor-pointer hover:border-[var(--color-primary)]/60 hover:bg-[var(--color-primary)]/5 transition-all text-xs text-[var(--text-muted)]">
                        <i data-lucide="upload" class="h-4 w-4 text-[var(--color-primary)]"></i>
                        <span id="edit-foto-label">Enviar nova foto...</span>
                        <input type="file" name="foto" id="edit-foto-input" accept="image/png,image/jpeg,image/webp" class="hidden" onchange="previewEditFoto(this)">
                    </label>
                </div>

                <div class="pt-3 flex items-center justify-end gap-3">
                    <button type="button" onclick="closeEditModal()"
                        class="rounded-2xl border border-[var(--color-primary)]/20 px-5 py-2.5 text-xs font-bold text-[var(--text-muted)] hover:bg-[var(--color-primary)]/10 transition-all cursor-pointer">
                        Cancelar
                    </button>
                    <button type="submit"
                        class="inline-flex items-center gap-1.5 rounded-2xl bg-[var(--color-primary)] hover:opacity-90 px-6 py-2.5 text-xs font-bold text-[var(--text-on-primary)] shadow-lg shadow-[var(--color-primary)]/20 transition-all active:scale-95 cursor-pointer">
                        <i data-lucide="check" class="h-4 w-4"></i>
                        <span>Salvar</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function previewCreateFoto(input) {
            const label = document.getElementById('foto-create-label');
            const wrapper = document.getElementById('foto-create-preview-wrapper');
            const img = document.getElementById('foto-create-preview');

            if (input.files && input.files[0]) {
                label.innerText = input.files[0].name;
                img.src = URL.createObjectURL(input.files[0]);
                wrapper.classList.remove('hidden');
            } else {
                label.innerText = 'Escolher imagem...';
                wrapper.classList.add('hidden');
            }
        }

        function previewEditFoto(input) {
            const label = document.getElementById('edit-foto-label');
            if (input.files && input.files[0]) {
                label.innerText = input.files[0].name;
            } else {
                label.innerText = 'Enviar nova foto...';
            }
        }

        function openSyncModal(grupoId, grupoNome, associatedProductIds) {
            const modal = document.getElementById('sync-products-modal');
            const form = document.getElementById('sync-products-form');
            const title = document.getElementById('sync-modal-title');
            const searchInput = document.getElementById('sync-search-input');

            let syncUrl = "{{ route('grupos.sync-products', ['slug' => auth()->user()->slug, 'grupo' => ':id']) }}";
            syncUrl = syncUrl.replace(':id', grupoId);
            form.setAttribute('action', syncUrl);

            title.innerText = 'Vincular Produtos: ' + grupoNome;
            searchInput.value = '';
            filterModalProducts('');

            const checkboxes = form.querySelectorAll('.product-checkbox');
            checkboxes.forEach(cb => {
                const pId = parseInt(cb.value);
                cb.checked = associatedProductIds.includes(pId);
            });

            modal.classList.remove('hidden');
        }

        function closeSyncModal() {
            document.getElementById('sync-products-modal').classList.add('hidden');
        }

        function filterModalProducts(term) {
            term = term.toLowerCase().trim();
            const rows = document.querySelectorAll('#sync-products-form .product-item-row');
            rows.forEach(row => {
                const name = row.dataset.name || '';
                if (!term || name.includes(term)) {
                    row.classList.remove('hidden');
                    row.classList.add('flex');
                } else {
                    row.classList.add('hidden');
                    row.classList.remove('flex');
                }
            });
        }

        function toggleSelectAllModalProducts(select) {
            const visibleRows = document.querySelectorAll('#sync-products-form .product-item-row:not(.hidden)');
            visibleRows.forEach(row => {
                const cb = row.querySelector('.product-checkbox');
                if (cb) cb.checked = select;
            });
        }

        function openEditModal(id, nome, fotoUrl) {
            const modal = document.getElementById('edit-grupo-modal');
            const form = document.getElementById('edit-grupo-form');
            const nomeInput = document.getElementById('edit-nome');
            const currentWrapper = document.getElementById('edit-foto-current-wrapper');
            const currentImg = document.getElementById('edit-foto-current-img');
            const fotoInput = document.getElementById('edit-foto-input');
            const fotoLabel = document.getElementById('edit-foto-label');

            let updateUrl = "{{ route('grupos.update', ['slug' => auth()->user()->slug, 'grupo' => ':id']) }}";
            updateUrl = updateUrl.replace(':id', id);
            form.setAttribute('action', updateUrl);

            nomeInput.value = nome;
            fotoInput.value = '';
            fotoLabel.innerText = 'Enviar nova foto...';

            if (fotoUrl) {
                currentImg.src = fotoUrl;
                currentWrapper.classList.remove('hidden');
                currentWrapper.classList.add('flex');
            } else {
                currentWrapper.classList.add('hidden');
                currentWrapper.classList.remove('flex');
            }

            modal.classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('edit-grupo-modal').classList.add('hidden');
        }
    </script>
</x-admin-layout>
