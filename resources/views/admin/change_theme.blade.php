<x-admin-layout active="theme" title="Mudar o tema do site" subtitle="Mude o tema do seu catálago com um clique">
    <div class="max-w-4xl mx-auto rounded-3xl border border-[var(--color-primary)]/50 bg-[var(--bg-card)] p-6 sm:p-8 shadow-2xl shadow-black/5">
    
        <div class="flex items-center gap-4 mb-8 border-b border-[var(--color-primary)]/10 pb-6">
            <div class="p-3.5 rounded-2xl bg-[var(--color-primary)]/10 text-[var(--color-primary)] shadow-inner">
                <i data-lucide="layout-template" class="h-6 w-6"></i>
            </div>
            <div>
                <h2 class="text-xl font-extrabold text-[var(--text-base)]">Visual da Loja</h2>
                <p class="text-sm text-[var(--text-muted)] mt-1">Selecione uma paleta de cores profissional para sua vitrine.</p>
            </div>
        </div>
    
        <form action="{{ route('theme.update') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($themes as $theme)
                <label class="relative group cursor-pointer">
                    <input type="radio" name="theme_id" value="{{ $theme['id'] }}" class="peer sr-only" {{ $theme['id'] == $theme_atual ? 'checked' : '' }}>
                    
                    <div class="p-4 rounded-2xl border-2 border-transparent bg-[var(--bg-page)]/50 peer-checked:border-[var(--color-primary)] peer-checked:bg-[var(--color-primary)]/5 transition-all hover:scale-[1.02] active:scale-95 shadow-sm">
                        
                        <div class="w-full h-24 rounded-xl mb-3 overflow-hidden border border-black/5 flex flex-col" style="background-color: {{ $theme['bg'] }}">
                            <div class="h-4 w-full bg-black/5 mb-2"></div>
                            <div class="mt-auto p-3 flex justify-end">
                                <div class="h-6 w-16 rounded-lg shadow-sm" style="background-color: {{ $theme['primary'] }}"></div>
                            </div>
                        </div>
    
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-bold text-[var(--text-base)]">{{ $theme['name'] }}</span>
                            <div class="h-5 w-5 rounded-full border-2 border-[var(--text-muted)] flex items-center justify-center peer-checked:bg-[var(--color-primary)] peer-checked:border-[var(--color-primary)] transition-colors">
                                <i data-lucide="check" class="h-3 w-3 text-white opacity-0 peer-checked:opacity-100"></i>
                            </div>
                        </div>
                        
                        @if($theme['dark'])
                            <span class="inline-block mt-2 px-2 py-0.5 rounded-md bg-black/20 text-[10px] font-bold uppercase tracking-wider text-white">Modo Escuro</span>
                        @else
                            <span class="inline-block mt-2 px-2 py-0.5 rounded-md bg-white text-[10px] font-bold uppercase tracking-wider text-black">Modo Claro</span>
                        @endif
                    </div>
                </label>
                @endforeach
    
            </div>
    
            <div class="mt-10 pt-6 border-t border-[var(--color-primary)]/10 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-2 text-[var(--text-muted)]">
                    <i data-lucide="info" class="h-4 w-4"></i>
                    <p class="text-xs font-medium">O tema será aplicado instantaneamente em sua vitrine pública.</p>
                </div>
                
                <button type="submit"
                    class="inline-flex items-center justify-center gap-2.5 w-full sm:w-auto rounded-2xl cursor-pointer bg-[var(--color-primary)] hover:opacity-90 px-10 py-4 text-sm font-bold text-[var(--text-on-primary)] shadow-xl shadow-[var(--color-primary)]/25 transition-all active:scale-95">
                    <i data-lucide="save" class="h-5 w-5"></i>
                    Confirmar Tema
                </button>
            </div>
        </form>
    </div>
</x-admin-layout>