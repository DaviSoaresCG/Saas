<x-store-layout page-title="Pedido enviado">
    <div class="max-w-lg mx-auto rounded-2xl border border-amber-500/30 bg-amber-950/20 p-6 sm:p-8 text-center">
        <div class="inline-flex h-14 w-14 items-center justify-center rounded-2xl bg-amber-500/20 border border-amber-500/40 mb-6">
            <i data-lucide="alert-circle" class="h-7 w-7 text-amber-400"></i>
        </div>
        <h1 class="text-xl font-extrabold text-white mb-3">Quase lá</h1>
        <p class="text-slate-400 text-sm leading-relaxed mb-8">
            Você será redirecionado ao WhatsApp do vendedor com uma mensagem pronta contendo os dados do pedido.
            Envie a mensagem para concluir.
        </p>
        <a href="{{ $url }}" target="_blank" rel="noopener noreferrer"
            class="inline-flex items-center justify-center gap-2 w-full sm:w-auto min-w-[240px] rounded-xl bg-emerald-600 hover:bg-emerald-500 px-6 py-3.5 text-base font-bold text-white shadow-lg shadow-emerald-600/25 transition-colors">
            <i data-lucide="message-circle" class="h-5 w-5"></i>
            Finalizar no WhatsApp
        </a>
        <div class="mt-8 pt-6 border-t border-slate-700/80">
            <button type="button"
                onclick="(function(){try{var r=document.referrer;if(r&&new URL(r).hostname===location.hostname){history.back();return;}}catch(e){} window.location.href='{{ route('cart.index') }}';})();"
                class="text-sm font-semibold text-slate-500 hover:text-slate-300 transition-colors">
                ← Voltar
            </button>
        </div>
    </div>
</x-store-layout>
