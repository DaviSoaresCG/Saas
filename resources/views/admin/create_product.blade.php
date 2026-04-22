<x-admin-layout active="products-create" title="Novo produto" subtitle="Preencha os dados para publicar no catálogo.">
    <div class="max-w-2xl rounded-3xl border border-[var(--color-primary)]/15 bg-[var(--bg-card)] p-6 sm:p-8 shadow-2xl shadow-black/5">
        
        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            <div>
                <label for="name" class="block text-sm font-bold text-[var(--text-base)] mb-2">Nome do produto</label>
                <input type="text" name="name" value="{{ old('name') }}" id="name" required
                    class="w-full rounded-2xl border border-[var(--color-primary)]/20 bg-[var(--bg-page)]/50 px-4 py-3.5 text-sm text-[var(--text-base)] placeholder-[var(--text-muted)]/70 outline-none transition-all focus:border-[var(--color-primary)]/50 focus:ring-4 focus:ring-[var(--color-primary)]/10 shadow-inner"
                    placeholder="Ex: Camiseta básica">
            </div>
            
            <div>
                <label for="description" class="block text-sm font-bold text-[var(--text-base)] mb-2">Descrição</label>
                <input type="text" name="description" id="description" value="{{ old('description') }}" required
                    class="w-full rounded-2xl border border-[var(--color-primary)]/20 bg-[var(--bg-page)]/50 px-4 py-3.5 text-sm text-[var(--text-base)] placeholder-[var(--text-muted)]/70 outline-none transition-all focus:border-[var(--color-primary)]/50 focus:ring-4 focus:ring-[var(--color-primary)]/10 shadow-inner"
                    placeholder="Breve descrição">
            </div>
            
            <div>
                <x-input-label for="value" :value="__('Valor (R$)')" class="!text-[var(--text-base)] !font-bold !mb-2" />
                <x-text-input id="value" type="tel" name="value" old="{{ old('value') }}" :value="old('value')" required placeholder="0,00" autocomplete="value" x-data x-mask:dynamic="$money($input, ',', '.')"
                    class="block w-full !rounded-2xl !border-[var(--color-primary)]/20 !bg-[var(--bg-page)]/50 !px-4 !py-3.5 !text-sm !text-[var(--text-base)] placeholder-[var(--text-muted)]/70 outline-none transition-all focus:!border-[var(--color-primary)]/50 focus:!ring-4 focus:!ring-[var(--color-primary)]/10 shadow-inner" />
                <x-input-error :messages="$errors->get('value')" class="mt-2 text-red-600 font-medium text-sm" />
            </div>
            
            <div>
                <x-input-label for="image" :value="__('Imagem (JPG, PNG, Webp)')" class="!text-[var(--text-base)] !font-bold !mb-2" />
                <input type="file" accept="image/png, image/jpeg, image/webp, image/jpg" id="image" name="image" required
                    class="mt-2 block w-full text-sm text-[var(--text-muted)] file:mr-4 file:rounded-xl file:border-0 file:bg-[var(--color-primary)] file:px-5 file:py-2.5 file:font-bold file:text-[var(--text-on-primary)] hover:file:opacity-90 file:cursor-pointer transition-all file:shadow-md cursor-pointer">
                <x-input-error :messages="$errors->get('image')" class="mt-2 text-red-600 font-medium text-sm" />
            </div>
            
            <div class="flex flex-wrap gap-4 pt-4 mt-8 border-t border-[var(--color-primary)]/10">
                <button type="submit"
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
</x-admin-layout>