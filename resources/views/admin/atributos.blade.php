<x-admin-layout active="atributos" title="Atributos" subtitle="Gerencie os atributos disponíveis para seus produtos.">

    @if (session('success'))
        <div
            class="mb-6 rounded-xl border border-emerald-500/40 bg-emerald-500/10 px-4 py-3 text-sm font-medium text-emerald-400">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="mb-6 rounded-xl border border-red-500/40 bg-red-500/10 px-4 py-3 text-sm font-medium text-red-400">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid gap-6 lg:grid-cols-3">

        {{-- Formulário de novo atributo --}}
        <div class="lg:col-span-1">
            <div
                class="rounded-3xl border border-[var(--color-primary)]/15 bg-[var(--bg-card)] p-6 shadow-2xl shadow-black/5">
                <h2 class="text-base font-bold text-[var(--text-base)] mb-5 flex items-center gap-2">
                    <i data-lucide="tag" class="h-4 w-4 text-[var(--color-primary)]"></i>
                    Novo atributo
                </h2>

                <form action="{{ route('atributos.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <x-input-base name="nome" type="text" icon="tag" placeholder="Ex: Cor Azul, 220v, Tamanho M"
                            label="Nome" :value="old('nome')" />
                    </div>

                    <button type="submit"
                        class="w-full inline-flex items-center justify-center gap-2 rounded-2xl cursor-pointer bg-[var(--color-primary)] hover:opacity-90 px-6 py-3 text-sm font-bold text-[var(--text-on-primary)] shadow-lg shadow-[var(--color-primary)]/25 transition-all active:scale-95">
                        <i data-lucide="plus" class="h-4 w-4"></i>
                        Adicionar atributo
                    </button>
                </form>
            </div>
        </div>

        {{-- Lista de atributos --}}
        <div class="lg:col-span-2">
            <div
                class="rounded-2xl border border-[var(--color-primary)]/80 bg-[var(--bg-card)] overflow-hidden shadow-xl shadow-black/15">
                <div class="flex items-center gap-2 bg-[var(--color-primary)] px-5 py-4">
                    <i data-lucide="list" class="h-5 w-5 text-[var(--text-on-primary)]"></i>
                    <h2 class="text-base font-bold text-[var(--text-on-primary)]">Atributos cadastrados</h2>
                    <span
                        class="ml-auto text-xs font-bold text-[var(--text-on-primary)]/70 bg-black/20 px-2 py-0.5 rounded-full">
                        {{ $atributos->count() }}
                    </span>
                </div>

                @if ($atributos->isEmpty())
                    <div class="px-5 py-16 text-center">
                        <i data-lucide="tag" class="h-10 w-10 mx-auto mb-3 text-[var(--text-muted)] opacity-40"></i>
                        <p class="text-sm text-[var(--text-muted)]">Nenhum atributo cadastrado ainda.</p>
                        <p class="text-xs text-[var(--text-muted)] mt-1 opacity-70">Crie seu primeiro atributo no formulário
                            ao lado.</p>
                    </div>
                @else
                    <ul class="divide-y divide-[var(--color-primary)]/40">
                        @foreach ($atributos as $atributo)
                            <li
                                class="flex items-center justify-between gap-4 px-5 py-3.5 hover:bg-[var(--color-primary)]/5 transition-colors group">
                                <div class="flex items-center gap-3 min-w-0">
                                    <span
                                        class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-[var(--color-primary)]/10 border border-[var(--color-primary)]/20">
                                        <i data-lucide="tag" class="h-3.5 w-3.5 text-[var(--color-primary)]"></i>
                                    </span>
                                    <span
                                        class="font-semibold text-sm text-[var(--text-base)] truncate">{{ $atributo->nome }}</span>
                                </div>
                                <form action="{{ route('atributos.destroy', [$atributo->id]) }}" method="POST"
                                    onsubmit="return confirm('Remover o atributo \'{{ $atributo->nome }}\'?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="inline-flex items-center gap-1 rounded-lg border border-red-500/30 bg-red-500/10 px-3 py-1.5 text-xs font-semibold text-red-400 hover:bg-red-500/20 transition-colors cursor-pointer">
                                        <i data-lucide="trash-2" class="h-3.5 w-3.5"></i>
                                        Remover
                                    </button>
                                </form>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>

    </div>
</x-admin-layout>