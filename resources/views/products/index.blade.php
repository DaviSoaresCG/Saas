<x-store-layout>
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
