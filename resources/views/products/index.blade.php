<x-store-layout :slug="$user->slug">
    @if (session('success'))
        <div class="mb-6 rounded-xl border border-emerald-500/40 bg-emerald-800 px-4 py-3 text-sm text-white">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-6 rounded-xl border border-red-500/40 bg-red-800 px-4 py-3 text-sm text-white">
            {{ session('error') }}
        </div>
    @endif

    @if (session('desconto_index'))
        <div class="mb-6 rounded-2xl border border-indigo-500/30 bg-indigo-500/10 px-4 py-3.5 text-sm font-semibold text-indigo-400 flex items-center gap-2">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            <span>Você está visualizando o catálogo especial com {{ number_format(session('desconto_index'), 0, ',', '.') }}% de desconto aplicado automaticamente!</span>
        </div>
    @endif

    <div class="mb-8 flex flex-wrap items-center justify-between gap-4">
        <div>
            <h2 class="text-lg font-bold text-[var(--text-base)]">Todos os produtos</h2>
            <p class="text-sm text-[var(--text-base)] mt-1">Toque em um item para ver detalhes.</p>
        </div>
    </div>

    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
        @foreach ($products as $product)
            <x-produto-card :produto="$product" :user="$user" />
        @endforeach
    </div>
</x-store-layout>
