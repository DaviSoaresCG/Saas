<x-admin-layout active="products-create" title="Novo produto" subtitle="Preencha os dados para publicar no catálogo.">
    <div class="max-w-2xl rounded-2xl border border-slate-700/80 bg-slate-800/40 p-6 sm:p-8 shadow-xl shadow-black/15">
        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            <div>
                <label for="name" class="block text-sm font-medium text-slate-300 mb-2">Nome do produto</label>
                <input type="text" name="name" value="{{ old('name') }}" id="name" required
                    class="w-full rounded-xl border border-slate-600 bg-slate-900/60 px-4 py-2.5 text-sm text-white placeholder-slate-500 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/30 outline-none"
                    placeholder="Ex: Camiseta básica">
            </div>
            <div>
                <label for="description" class="block text-sm font-medium text-slate-300 mb-2">Descrição</label>
                <input type="text" name="description" id="description" value="{{ old('description') }}" required
                    class="w-full rounded-xl border border-slate-600 bg-slate-900/60 px-4 py-2.5 text-sm text-white placeholder-slate-500 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/30 outline-none"
                    placeholder="Breve descrição">
            </div>
            <div>
                <x-input-label for="value" :value="__('Valor (R$)')" class="!text-slate-300" />
                <x-text-input id="value" class="block mt-2 w-full !rounded-xl !border-slate-600 !bg-slate-900/60 !text-white" type="tel"
                    name="value" old="{{ old('value') }}" :value="old('value')" required placeholder="0,00" autocomplete="value" x-data
                    x-mask:dynamic="$money($input, ',', '.')" />
                <x-input-error :messages="$errors->get('value')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="image" :value="__('Imagem (JPG, PNG, Webp)')" class="!text-slate-300" />
                <input type="file" accept="image/png, image/jpeg, image/webp, image/jpg" id="image" name="image" required
                    class="mt-2 block w-full text-sm text-slate-300 file:mr-4 file:rounded-lg file:border-0 file:bg-blue-600 file:px-4 file:py-2 file:font-semibold file:text-white hover:file:bg-blue-700">
                <x-input-error :messages="$errors->get('image')" class="mt-2" />
            </div>
            <div class="flex flex-wrap gap-3 pt-2">
                <button type="submit"
                    class="inline-flex items-center gap-2 rounded-xl bg-blue-600 hover:bg-blue-700 px-6 py-3 text-sm font-bold text-white shadow-lg shadow-blue-600/20 transition-colors">
                    <i data-lucide="check" class="h-4 w-4"></i>
                    Salvar produto
                </button>
                <a href="{{ route('admin.products') }}"
                    class="inline-flex items-center gap-2 rounded-xl border border-slate-600 bg-slate-900/50 px-6 py-3 text-sm font-semibold text-slate-200 hover:bg-slate-700/50 transition-colors">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</x-admin-layout>
