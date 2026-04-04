<x-admin-layout active="products" title="Editar produto" :subtitle="$product->name">
    <div class="max-w-2xl rounded-2xl border border-slate-700/80 bg-slate-800/40 p-6 sm:p-8 shadow-xl shadow-black/15">
        <form action="{{ route('products.update', ['product' => $product->id]) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @method('PATCH')
            @csrf
            <input type="hidden" name="slug" value="{{ auth()->user()->slug }}">
            <div>
                <label for="name" class="block text-sm font-medium text-slate-300 mb-2">Nome</label>
                <input type="text" name="name" value="{{ old('name', $product->name) }}" id="name" required
                    class="w-full rounded-xl border border-slate-600 bg-slate-900/60 px-4 py-2.5 text-sm text-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/30 outline-none">
                @error('name')
                    <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="description" class="block text-sm font-medium text-slate-300 mb-2">Descrição</label>
                <textarea name="description" id="description" rows="4" required
                    class="w-full rounded-xl border border-slate-600 bg-slate-900/60 px-4 py-2.5 text-sm text-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/30 outline-none resize-y">{{ old('description', $product->description) }}</textarea>
                @error('description')
                    <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="value" class="block text-sm font-medium text-slate-300 mb-2">Valor (R$)</label>
                <x-text-input id="value" class="block w-full !rounded-xl !border-slate-600 !bg-slate-900/60 !text-white" type="tel" name="value"
                    old="{{ old('value') }}" :value="old('value', $product->value)" required placeholder="0,00" autocomplete="value" x-data
                    x-mask:dynamic="$money($input, ',', '.')" />
                @error('value')
                    <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="image" class="block text-sm font-medium text-slate-300 mb-2">Nova imagem (opcional)</label>
                <input type="file" id="image" name="image" accept="image/png, image/jpeg, image/webp, image/jpg"
                    class="mt-2 block w-full text-sm text-slate-300 file:mr-4 file:rounded-lg file:border-0 file:bg-blue-600 file:px-4 file:py-2 file:font-semibold file:text-white hover:file:bg-blue-700">
                <a href="{{ asset('storage/' . $product->path) }}" download
                    class="mt-3 inline-flex items-center gap-2 text-sm font-semibold text-blue-400 hover:text-blue-300">
                    <i data-lucide="download" class="h-4 w-4"></i>
                    Baixar imagem atual
                </a>
            </div>
            <div class="flex flex-wrap gap-3 pt-2">
                <button type="submit"
                    class="inline-flex items-center gap-2 rounded-xl bg-blue-600 hover:bg-blue-700 px-6 py-3 text-sm font-bold text-white shadow-lg shadow-blue-600/20 transition-colors">
                    Salvar alterações
                </button>
                <a href="{{ route('admin.products') }}"
                    class="inline-flex items-center gap-2 rounded-xl border border-slate-600 bg-slate-900/50 px-6 py-3 text-sm font-semibold text-slate-200 hover:bg-slate-700/50 transition-colors">
                    Voltar à lista
                </a>
            </div>
        </form>
    </div>
</x-admin-layout>
