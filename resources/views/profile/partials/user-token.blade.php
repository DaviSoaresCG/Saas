<section>
    <header>
        <h2 class="text-lg font-medium text-[var(--text-base)] flex items-center gap-2">
            <i data-lucide="key" class="h-5 w-5 text-[var(--color-primary)]"></i>
            {{ __('Token de API') }}
        </h2>

        <p class="mt-1 text-sm text-[var(--text-muted)]">
            {{ __("Este token será usado para integrar sistemas externos com o nosso!") }}
        </p>
    </header>

    <form method="post" action="{{ route('token.get', ['slug' => auth()->user()->slug]) }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div class="space-y-4">
            @if(empty(auth()->user()->api_token))
                <div>
                    <p class="text-sm text-[var(--text-muted)] mb-3">Nenhum token gerado ainda. Clique no botão abaixo para gerar seu token de integração.</p>
                    <x-primary-button class="gap-2">
                        <i data-lucide="key" class="h-4 w-4"></i>
                        {{ __('Gerar Token') }}
                    </x-primary-button>
                </div>
            @else
                <div>
                    <label class="text-[var(--text-base)] mb-2 block font-medium text-sm">Seu Token de API</label>
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2">
                        <div class="relative flex-1">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-[var(--text-muted)]">
                                <i data-lucide="shield-check" class="h-4 w-4"></i>
                            </div>
                            <input type="text" 
                                   readonly 
                                   id="api_token_input"
                                   value="{{ auth()->user()->api_token }}" 
                                   class="w-full rounded-xl border border-[var(--color-primary)]/30 bg-[var(--bg-page)]/60 pl-9 pr-4 py-2.5 text-xs font-mono tracking-wide text-[var(--text-base)] focus:outline-none select-all shadow-inner">
                        </div>
                        
                        <button type="button" 
                                x-data="{ copied: false }"
                                @click="navigator.clipboard.writeText('{{ auth()->user()->api_token }}'); copied = true; setTimeout(() => copied = false, 2000)"
                                class="inline-flex items-center justify-center gap-1.5 px-4 py-2.5 rounded-xl bg-[var(--color-primary)] hover:opacity-90 text-[var(--text-on-primary)] text-sm font-semibold transition-all cursor-pointer shrink-0 shadow-md">
                            <i data-lucide="copy" class="h-4 w-4" x-show="!copied"></i>
                            <i data-lucide="check" class="h-4 w-4 text-emerald-300" x-show="copied" x-cloak></i>
                            <span x-text="copied ? 'Copiado!' : 'Copiar'">Copiar</span>
                        </button>
                    </div>
                </div>
            @endif

            @if (session('status') === 'Token API gerado com sucesso' || session('status') === 'token-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)"
                    class="text-sm font-medium text-emerald-400 flex items-center gap-1.5 mt-2">
                    <i data-lucide="check-circle" class="h-4 w-4"></i>
                    {{ session('status') }}
                </p>
            @endif
        </div>
    </form>
</section>