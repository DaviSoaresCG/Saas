<x-guest-layout>
    <div class="text-center mb-8">
        <h2 class="text-3xl font-extrabold text-[var(--text-base)] tracking-tight">Ative o seu Catálogo Digital</h2>
        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Escolha o plano ideal para o seu negócio e comece a vender pelo WhatsApp hoje mesmo.</p>
    </div>

    @if (session('error'))
        <div class="mb-4 p-4 rounded-xl bg-red-500/10 border border-red-500/30 text-red-500 text-sm">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
        <!-- Plano Mensal -->
        <div class="flex flex-col p-6 rounded-2xl border border-[var(--color-primary)]/35 bg-[var(--bg-card)] hover:border-[var(--color-primary)] transition-all duration-300 shadow-lg relative overflow-hidden group">
            <div class="mb-5">
                <h3 class="text-xl font-bold text-[var(--text-base)]">Plano Mensal</h3>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Flexibilidade sem compromisso de longo prazo</p>
                <div class="mt-4 flex items-baseline">
                    <span class="text-3xl font-extrabold text-emerald-600">R$ 29,90</span>
                    <span class="ml-1 text-sm font-semibold text-gray-500">/mês</span>
                </div>
            </div>

            <ul class="space-y-3 mb-6 text-sm text-[var(--text-base)]/80 flex-1">
                <li class="flex items-center gap-2">
                    <svg class="h-4 w-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    <span>Produtos Ilimitados</span>
                </li>
                <li class="flex items-center gap-2">
                    <svg class="h-4 w-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    <span>Pedidos direto no seu WhatsApp</span>
                </li>
                <li class="flex items-center gap-2">
                    <svg class="h-4 w-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    <span>Múltiplos Catálogos & Descontos</span>
                </li>
                <li class="flex items-center gap-2">
                    <svg class="h-4 w-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    <span>Suporte a Atributos (Cor, Tamanho)</span>
                </li>
            </ul>

            <form action="{{ route('pagamento.generate') }}" method="POST">
                @csrf
                <input type="hidden" name="plan" value="monthly">
                <button type="submit" class="w-full py-3 px-4 rounded-xl bg-[var(--color-primary)] text-[var(--text-on-primary)] font-bold text-sm shadow-md hover:opacity-90 transition-all cursor-pointer">
                    Selecionar Mensal
                </button>
            </form>
        </div>

        <!-- Plano Anual -->
        <div class="flex flex-col p-6 rounded-2xl border-2 border-[var(--color-primary)] bg-[var(--bg-card)] hover:shadow-xl transition-all duration-300 shadow-lg relative overflow-hidden group">
            <div class="absolute top-0 right-0 bg-[var(--color-primary)] text-[var(--text-on-primary)] text-[10px] font-extrabold uppercase px-3 py-1 rounded-bl-xl tracking-wider">
                Melhor Valor
            </div>
            
            <div class="mb-5">
                <h3 class="text-xl font-bold text-[var(--text-base)]">Plano Anual</h3>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Economize quase 2 meses em relação ao mensal</p>
                <div class="mt-4 flex items-baseline">
                    <span class="text-3xl font-extrabold text-emerald-600">R$ 299,00</span>
                    <span class="ml-1 text-sm font-semibold text-gray-500">/ano</span>
                </div>
            </div>

            <ul class="space-y-3 mb-6 text-sm text-[var(--text-base)]/80 flex-1">
                <li class="flex items-center gap-2">
                    <svg class="h-4 w-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    <span>Produtos Ilimitados</span>
                </li>
                <li class="flex items-center gap-2">
                    <svg class="h-4 w-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    <span>Pedidos direto no seu WhatsApp</span>
                </li>
                <li class="flex items-center gap-2">
                    <svg class="h-4 w-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    <span>Múltiplos Catálogos & Descontos</span>
                </li>
                <li class="flex items-center gap-2">
                    <svg class="h-4 w-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    <span>Suporte a Atributos (Cor, Tamanho)</span>
                </li>
                <li class="flex items-center gap-2 font-semibold text-emerald-600">
                    <svg class="h-4 w-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    <span>Desconto de 17% garantido</span>
                </li>
            </ul>

            <form action="{{ route('pagamento.generate') }}" method="POST">
                @csrf
                <input type="hidden" name="plan" value="yearly">
                <button type="submit" class="w-full py-3 px-4 rounded-xl bg-[var(--color-primary)] text-[var(--text-on-primary)] font-bold text-sm shadow-md hover:opacity-90 transition-all cursor-pointer">
                    Selecionar Anual
                </button>
            </form>
        </div>
    </div>

    <div class="mt-8 text-center">
        <form action="{{ route('logout') }}" method="POST" class="inline">
            @csrf
            <button type="submit" class="text-xs text-gray-500 hover:text-gray-700 underline cursor-pointer">
                Sair da conta
            </button>
        </form>
    </div>
</x-guest-layout>
