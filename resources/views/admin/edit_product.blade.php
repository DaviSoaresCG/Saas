<x-admin-layout active="products" title="Editar produto" :subtitle="$product->name">
    <div class="max-w-2xl rounded-3xl border border-[var(--color-primary)]/15 bg-[var(--bg-card)] p-6 sm:p-8 shadow-2xl shadow-black/5">
        
        <form action="{{ route('products.update', ['product' => $product->id]) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @method('PATCH')
            @csrf
            <input type="hidden" name="slug" value="{{ auth()->user()->slug }}">
            
            <div>
                <label for="name" class="block text-sm font-bold text-[var(--text-base)] mb-2">Nome</label>
                <input type="text" name="name" value="{{ old('name', $product->name) }}" id="name" required
                    class="w-full rounded-2xl border border-[var(--color-primary)]/20 bg-[var(--bg-page)]/50 px-4 py-3.5 text-sm text-[var(--text-base)] outline-none transition-all focus:border-[var(--color-primary)]/50 focus:ring-4 focus:ring-[var(--color-primary)]/10 shadow-inner">
                @error('name')
                    <p class="mt-2 text-sm font-medium text-red-600 ml-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="description" class="block text-sm font-bold text-[var(--text-base)] mb-2">Descrição</label>
                <textarea name="description" id="description" rows="4" required
                    class="w-full rounded-2xl border border-[var(--color-primary)]/20 bg-[var(--bg-page)]/50 px-4 py-3.5 text-sm text-[var(--text-base)] outline-none transition-all focus:border-[var(--color-primary)]/50 focus:ring-4 focus:ring-[var(--color-primary)]/10 shadow-inner resize-y">{{ old('description', $product->description) }}</textarea>
                @error('description')
                    <p class="mt-2 text-sm font-medium text-red-600 ml-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="value" class="block text-sm font-bold text-[var(--text-base)] mb-2">Valor (R$)</label>
                <x-text-input id="value" type="tel" name="value" old="{{ old('value') }}" :value="old('value', $product->value)" required placeholder="0,00" autocomplete="value" x-data x-mask:dynamic="$money($input, ',', '.')" 
                    class="block w-full !rounded-2xl !border-[var(--color-primary)]/20 !bg-[var(--bg-page)]/50 !px-4 !py-3.5 !text-sm !text-[var(--text-base)] outline-none transition-all focus:!border-[var(--color-primary)]/50 focus:!ring-4 focus:!ring-[var(--color-primary)]/10 shadow-inner" />
                @error('value')
                    <p class="mt-2 text-sm font-medium text-red-600 ml-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="image" class="block text-sm font-bold text-[var(--text-base)] mb-2">Nova imagem (opcional)</label>
                <input type="file" id="image" name="image" accept="image/png, image/jpeg, image/webp, image/jpg"
                    class="mt-2 block w-full text-sm text-[var(--text-muted)] file:mr-4 file:rounded-xl file:border-0 file:bg-[var(--color-primary)] file:px-5 file:py-2.5 file:font-bold file:text-[var(--text-on-primary)] hover:file:opacity-90 file:cursor-pointer transition-all file:shadow-md cursor-pointer">
                
                <a href="{{ asset('storage/' . $product->path) }}" download
                    class="mt-4 inline-flex items-center gap-2 text-sm font-bold text-[var(--color-primary)] hover:opacity-70 transition-opacity decoration-[var(--color-primary)]/30 hover:underline underline-offset-4">
                    <i data-lucide="download" class="h-4 w-4"></i>
                    Baixar imagem atual
                </a>
            </div>
            
            {{-- Atributos (opcional) --}}
            @if ($atributos->isNotEmpty())
            <div>
                <label class="block text-sm font-bold text-[var(--text-base)] mb-3">
                    Atributos do produto <span class="text-[var(--text-muted)] font-normal text-xs">(opcional)</span>
                </label>
                <div class="flex flex-wrap gap-2">
                    @foreach ($atributos as $atributo)
                        <label for="atr_{{ $atributo->id }}"
                            class="flex items-center gap-2 rounded-xl border border-[var(--color-primary)]/20 bg-[var(--bg-page)]/50 px-3 py-2 text-sm text-[var(--text-base)] cursor-pointer transition-all has-[:checked]:border-[var(--color-primary)] has-[:checked]:bg-[var(--color-primary)]/10 has-[:checked]:font-semibold">
                            <input type="checkbox" id="atr_{{ $atributo->id }}" name="atributos[]"
                                value="{{ $atributo->id }}" class="accent-[var(--color-primary)]"
                                {{ in_array($atributo->id, old('atributos', $atributosVinculados)) ? 'checked' : '' }}>
                            {{ $atributo->nome }}
                        </label>
                    @endforeach
                </div>
                <p class="mt-2 text-xs text-[var(--text-muted)]">
                    O cliente poderá escolher um ou mais atributos ao adicionar ao carrinho.
                    <a href="{{ route('atributos.index') }}" class="text-[var(--color-primary)] hover:underline underline-offset-2">Gerenciar atributos</a>
                </p>
            </div>
            @else
            <div class="rounded-xl border border-dashed border-[var(--color-primary)]/20 px-4 py-3 text-sm text-[var(--text-muted)]">
                Nenhum atributo cadastrado. <a href="{{ route('atributos.index') }}" class="text-[var(--color-primary)] hover:underline underline-offset-2">Criar atributos</a>
            </div>
            @endif

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
    </div>
</x-admin-layout>