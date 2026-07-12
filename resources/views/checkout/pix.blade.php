<x-guest-layout>
    <div class="text-center mb-6">
        <h2 class="text-3xl font-extrabold text-[var(--text-base)]">Pagamento via Pix</h2>
        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Escaneie o QR Code ou copie o código abaixo para ativar sua assinatura do {{ $payment['plan_name'] }}.</p>
    </div>

    <div class="flex flex-col items-center justify-center p-6 rounded-2xl border border-dashed border-[var(--color-primary)]/40 bg-[var(--bg-card)]/50 mb-6">
        <div class="text-center mb-4">
            <span class="text-xs text-gray-400 uppercase font-semibold">Valor a Pagar</span>
            <div class="text-3xl font-extrabold text-emerald-600">R$ {{ number_format($payment['amount'], 2, ',', '.') }}</div>
        </div>

        <!-- Dynamic QR Code using public helper API -->
        <div class="p-4 bg-white rounded-xl shadow-md border border-gray-100">
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ urlencode($payment['copy_paste']) }}" alt="Pix QR Code" class="w-[200px] h-[200px]" />
        </div>

        <p class="mt-4 text-xs text-gray-400 text-center">O QR Code expira em 24 horas.</p>
    </div>

    <!-- Pix Copia e Cola -->
    <div class="mb-8" x-data="{ copied: false }">
        <label class="block text-sm font-semibold text-[var(--text-base)] mb-2">Pix Copia e Cola</label>
        <div class="flex gap-2">
            <input type="text" id="pixCode" readonly value="{{ $payment['copy_paste'] }}" 
                class="flex-1 px-4 py-3 bg-[var(--bg-card)] border border-[var(--color-primary)]/30 rounded-xl text-xs text-[var(--text-base)]/80 focus:outline-none truncate">
            <button @click="
                navigator.clipboard.writeText($el.previousElementSibling.value); 
                copied = true; 
                setTimeout(() => copied = false, 2000)
            " class="px-4 py-3 rounded-xl bg-[var(--color-primary)] text-[var(--text-on-primary)] font-bold text-xs hover:opacity-90 transition-all cursor-pointer whitespace-nowrap">
                <span x-show="!copied">Copiar</span>
                <span x-show="copied" class="text-emerald-300">Copiado!</span>
            </button>
        </div>
    </div>

    <!-- Instructions -->
    <div class="mb-8 p-4 rounded-xl bg-blue-500/5 border border-blue-500/25">
        <h4 class="text-sm font-bold text-[var(--color-primary)] mb-2">Instruções para pagamento:</h4>
        <ol class="list-decimal list-inside text-xs text-[var(--text-base)]/80 space-y-1">
            <li>Abra o aplicativo do seu banco no celular.</li>
            <li>Escolha a opção **Pix** e depois **Pagar via QR Code** (ou **Pix Copia e Cola**).</li>
            <li>Escaneie a imagem acima ou cole o código copiado.</li>
            <li>Confirme os dados e finalize o pagamento. Sua conta será ativada instantaneamente após a confirmação.</li>
        </ol>
    </div>

    <!-- Simulação Local (Ambiente de Teste) -->
    <div class="p-5 rounded-2xl border-2 border-amber-500/50 bg-amber-500/5 text-center">
        <div class="flex items-center justify-center gap-2 text-amber-500 mb-2">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            <span class="text-sm font-bold uppercase tracking-wider">Área de Simulação Local</span>
        </div>
        <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">Você está em ambiente de desenvolvimento. Clique no botão abaixo para simular a resposta positiva do Webhook e ativar imediatamente a sua loja.</p>
        
        <form action="{{ route('pagamento.simulate') }}" method="POST">
            @csrf
            <button type="submit" class="w-full py-3 px-4 rounded-xl bg-amber-500 hover:bg-amber-600 text-white font-bold text-sm shadow-md transition-all cursor-pointer">
                Confirmar Pagamento Simulado
            </button>
        </form>
    </div>

    <div class="mt-6 text-center">
        <a href="{{ route('pagamento.pending') }}" class="text-xs text-gray-500 hover:text-gray-700 underline">
            Voltar para seleção de planos
        </a>
    </div>
</x-guest-layout>
