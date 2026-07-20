<x-admin-layout active="catalogos" title="Catálogos Dinâmicos" subtitle="Crie links de catálogos alternativos com descontos aplicados automaticamente.">

    @if (session('success'))
        <div class="mb-6 rounded-xl border border-emerald-500/40 bg-emerald-500/10 px-4 py-3 text-sm font-medium text-emerald-400">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="mb-6 rounded-xl border border-red-500/40 bg-red-500/10 px-4 py-3 text-sm font-medium text-red-400">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid gap-6 lg:grid-cols-3" x-data="{ editMode: false, editId: null, editNome: '', editDesconto: '', updateUrl: '' }">

        {{-- Formulário de criação/edição --}}
        <div class="lg:col-span-1">
            <!-- Criar Catálogo -->
            <div x-show="!editMode" class="rounded-3xl border border-[var(--color-primary)]/15 bg-[var(--bg-card)] p-6 shadow-2xl shadow-black/5">
                <h2 class="text-base font-bold text-[var(--text-base)] mb-5 flex items-center gap-2">
                    <i data-lucide="folder-plus" class="h-4 w-4 text-[var(--color-primary)]"></i>
                    Novo Catálogo
                </h2>

                <form action="{{ route('catalogos.store', ['slug' => auth()->user()->slug]) }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <x-input-base name="nome" value="{{ old('nome') }}" type="text" icon="folder" placeholder="Ex: Clientes VIP, Atacado" label="Nome do Catálogo" required />
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[var(--text-base)] mb-1">Desconto (%)</label>
                        <div class="relative rounded-2xl border border-[var(--color-primary)]/20 bg-[var(--bg-card)]/50 focus-within:border-[var(--color-primary)] transition-all">
                            <span class="absolute inset-y-0 left-4 flex items-center text-[var(--text-muted)]">
                                <i data-lucide="percent" class="h-4 w-4"></i>
                            </span>
                            <input name="desconto_index" value="{{ old('desconto_index') }}" type="number" step="0.01" min="0" max="100" placeholder="Ex: 10.00" required
                                class="w-full pl-12 pr-4 py-3 bg-transparent text-sm text-[var(--text-base)] outline-none rounded-2xl">
                        </div>
                    </div>

                    <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-2xl cursor-pointer bg-[var(--color-primary)] hover:opacity-90 px-6 py-3 text-sm font-bold text-[var(--text-on-primary)] shadow-lg shadow-[var(--color-primary)]/25 transition-all active:scale-95">
                        <i data-lucide="plus" class="h-4 w-4"></i>
                        Criar Catálogo
                    </button>
                </form>
            </div>

            <!-- Editar Catálogo -->
            <div x-show="editMode" x-cloak class="rounded-3xl border border-amber-500/30 bg-[var(--bg-card)] p-6 shadow-2xl shadow-black/5">
                <h2 class="text-base font-bold text-[var(--text-base)] mb-5 flex items-center gap-2">
                    <i data-lucide="edit" class="h-4 w-4 text-amber-500"></i>
                    Editar Catálogo
                </h2>

                <form :action="updateUrl" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="block text-xs font-bold text-[var(--text-base)] mb-1">Nome do Catálogo</label>
                        <div class="relative rounded-2xl border border-[var(--color-primary)]/20 bg-[var(--bg-card)]/50 focus-within:border-[var(--color-primary)] transition-all">
                            <span class="absolute inset-y-0 left-4 flex items-center text-[var(--text-muted)]">
                                <i data-lucide="folder" class="h-4 w-4"></i>
                            </span>
                            <input name="nome" type="text" x-model="editNome" required
                                class="w-full pl-12 pr-4 py-3 bg-transparent text-sm text-[var(--text-base)] outline-none rounded-2xl">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[var(--text-base)] mb-1">Desconto (%)</label>
                        <div class="relative rounded-2xl border border-[var(--color-primary)]/20 bg-[var(--bg-card)]/50 focus-within:border-[var(--color-primary)] transition-all">
                            <span class="absolute inset-y-0 left-4 flex items-center text-[var(--text-muted)]">
                                <i data-lucide="percent" class="h-4 w-4"></i>
                            </span>
                            <input name="desconto_index" type="number" step="0.01" min="0" max="100" x-model="editDesconto" required
                                class="w-full pl-12 pr-4 py-3 bg-transparent text-sm text-[var(--text-base)] outline-none rounded-2xl">
                        </div>
                    </div>

                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 inline-flex items-center justify-center gap-2 rounded-2xl cursor-pointer bg-amber-500 hover:bg-amber-600 px-4 py-3 text-sm font-bold text-white shadow-lg transition-all">
                            Salvar
                        </button>
                        <button type="button" @click="editMode = false" class="flex-1 inline-flex items-center justify-center gap-2 rounded-2xl cursor-pointer bg-gray-500 hover:bg-gray-600 px-4 py-3 text-sm font-bold text-white shadow-lg transition-all">
                            Cancelar
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Lista de catálogos --}}
        <div class="lg:col-span-2">
            <div class="rounded-2xl border border-[var(--color-primary)]/80 bg-[var(--bg-card)] overflow-hidden shadow-xl shadow-black/15">
                <div class="flex items-center gap-2 bg-[var(--color-primary)] px-5 py-4">
                    <i data-lucide="list" class="h-5 w-5 text-[var(--text-on-primary)]"></i>
                    <h2 class="text-base font-bold text-[var(--text-on-primary)]">Seus Catálogos</h2>
                    <span class="ml-auto text-xs font-bold text-[var(--text-on-primary)]/70 bg-black/20 px-2 py-0.5 rounded-full">
                        {{ $catalogos->count() }}
                    </span>
                </div>

                @if ($catalogos->isEmpty())
                    <div class="px-5 py-16 text-center">
                        <i data-lucide="folder" class="h-10 w-10 mx-auto mb-3 text-[var(--text-muted)] opacity-40"></i>
                        <p class="text-sm text-[var(--text-muted)]">Nenhum catálogo cadastrado ainda.</p>
                        <p class="text-xs text-[var(--text-muted)] mt-1 opacity-70">Crie seu primeiro catálogo promocional no formulário ao lado.</p>
                    </div>
                @else
                    <ul class="divide-y divide-[var(--color-primary)]/20" x-data="{ copiedHash: null }">
                        @foreach ($catalogos as $cat)
                            @php
                                $publicLink = 'http://' . env('APP_DOMAIN') . '/' . $cat->hash;
                            @endphp
                            <li class="flex flex-col gap-3 p-5 hover:bg-[var(--color-primary)]/5 transition-colors">
                                <div class="flex items-center justify-between gap-4">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-[var(--color-primary)]/10 border border-[var(--color-primary)]/20">
                                            <i data-lucide="folder" class="h-3.5 w-3.5 text-[var(--color-primary)]"></i>
                                        </span>
                                        <div>
                                            <span class="font-bold text-sm text-[var(--text-base)] block leading-tight">{{ $cat->nome }}</span>
                                            <span class="text-xs text-emerald-600 font-semibold">{{ number_format($cat->desconto_index, 2, ',', '.') }}% de desconto</span>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2 shrink-0">
                                        <button type="button" @click="
                                            editMode = true;
                                            editId = '{{ $cat->id }}';
                                            editNome = '{{ $cat->nome }}';
                                            editDesconto = '{{ $cat->desconto_index }}';
                                            updateUrl = '{{ route('catalogos.update', ['slug' => auth()->user()->slug, 'catalogo' => $cat->id]) }}';
                                            window.scrollTo({top: 0, behavior: 'smooth'});
                                        " class="inline-flex items-center gap-1 rounded-lg border border-amber-500/30 bg-amber-500/10 px-2.5 py-1.5 text-xs font-semibold text-amber-500 hover:bg-amber-500/20 transition-colors cursor-pointer">
                                            <i data-lucide="edit" class="h-3.5 w-3.5"></i>
                                            Editar
                                        </button>
                                        <form action="{{ route('catalogos.destroy', ['slug' => auth()->user()->slug, 'catalogo' => $cat->id]) }}" method="POST"
                                            onsubmit="return confirm('Remover o catálogo \'{{ $cat->nome }}\'?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center gap-1 rounded-lg border border-red-500/30 bg-red-500/10 px-2.5 py-1.5 text-xs font-semibold text-red-400 hover:bg-red-500/20 transition-colors cursor-pointer">
                                                <i data-lucide="trash-2" class="h-3.5 w-3.5"></i>
                                                Remover
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                {{-- Link para Copiar --}}
                                <div class="flex items-center gap-2 bg-[var(--bg-page)] rounded-xl p-2 border border-[var(--color-primary)]/10">
                                    <span class="text-xs text-[var(--text-muted)] truncate flex-1 font-mono pl-1">{{ $publicLink }}</span>
                                    <button type="button" @click="
                                        navigator.clipboard.writeText('{{ $publicLink }}');
                                        copiedHash = '{{ $cat->hash }}';
                                        setTimeout(() => copiedHash = null, 2000)
                                    " class="inline-flex items-center justify-center gap-1 rounded-lg bg-[var(--color-primary)] hover:opacity-90 px-3 py-1.5 text-xs font-bold text-[var(--text-on-primary)] transition-all cursor-pointer">
                                        <i data-lucide="copy" class="h-3 w-3" x-show="copiedHash !== '{{ $cat->hash }}'"></i>
                                        <i data-lucide="check" class="h-3 w-3 text-emerald-300" x-show="copiedHash === '{{ $cat->hash }}'" x-cloak></i>
                                        <span x-show="copiedHash !== '{{ $cat->hash }}'">Copiar Link</span>
                                        <span x-show="copiedHash === '{{ $cat->hash }}'" class="text-emerald-300" x-cloak>Copiado!</span>
                                    </button>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>

    </div>
</x-admin-layout>
