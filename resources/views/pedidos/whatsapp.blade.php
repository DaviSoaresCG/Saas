<x-store-layout page-title="Pedido enviado">
    <div class="max-w-lg mx-auto rounded-3xl border border-[var(--color-primary)]/15 bg-[var(--bg-card)] p-6 sm:p-10 text-center shadow-2xl shadow-black/65 mt-8 sm:mt-12">
        
        <div class="inline-flex h-16 w-16 items-center justify-center rounded-2xl bg-[var(--color-primary)]/10 border border-[var(--color-primary)]/20 mb-6">
            <i data-lucide="alert-circle" class="h-8 w-8 text-[var(--color-primary)]"></i>
        </div>
        
        <h1 class="text-2xl font-extrabold text-[var(--text-base)] mb-3">Quase lá!</h1>
        <p class="text-[var(--text-muted)] text-base leading-relaxed mb-10 max-w-sm mx-auto">
            Você será redirecionado ao WhatsApp do vendedor com uma mensagem pronta contendo os dados do pedido.
            Envie a mensagem para concluir.
        </p>
        
        <a href="{{ $url }}" target="_blank" rel="noopener noreferrer"
            class="inline-flex items-center justify-center gap-2.5 w-full sm:w-auto min-w-[260px] rounded-2xl bg-[#25D366] hover:bg-[#20bd5a] px-8 py-4 text-base font-bold text-white shadow-lg shadow-[#25D366]/30 transition-all active:scale-95">
            <i data-lucide="message-circle" class="h-6 w-6"></i>
            Finalizar no WhatsApp
        </a>
        
        <div class="mt-10 pt-6 border-t border-[var(--color-primary)]/10">
            <button type="button"
                onclick="(function(){try{var r=document.referrer;if(r&&new URL(r).hostname===location.hostname){history.back();return;}}catch(e){} window.location.href='{{ route('cart.index') }}';})();"
                class="inline-flex items-center justify-center gap-2 text-sm font-bold text-[var(--text-muted)] hover:text-[var(--color-primary)] transition-colors active:scale-95">
                <i data-lucide="arrow-left" class="h-4 w-4"></i>
                Voltar para a loja
            </button>
        </div>
    </div>
</x-store-layout>