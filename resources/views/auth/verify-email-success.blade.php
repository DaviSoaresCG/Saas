<x-auth-split-layout
    title="E-mail verificado com sucesso"
    eyebrow="Tudo pronto"
    heading="Conta verificada com sucesso!"
    subheading="Seu e-mail foi validado. Agora você já pode escolher o seu plano e colocar sua vitrine digital no ar.">

    @php
        $currentUser = $user ?? auth()->user();
    @endphp

    {{-- Card Comemorativo de Sucesso --}}
    <div class="mb-5 flex items-start gap-3.5 rounded-2xl border border-[#c4d693] bg-[#f4f8e6] p-5 text-sm text-[#4e5c26]">
        <span class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-[#d7f56b]/60 text-[#37322f]">
            <i data-lucide="check-circle" class="size-5 text-[#4e5c26]"></i>
        </span>
        <div class="min-w-0 flex-1">
            <h3 class="font-semibold text-base text-[#37322f]">{{ __('Tudo certo com seu cadastro!') }}</h3>
            <p class="mt-1 text-xs leading-relaxed text-[#514b46]">
                {{ __('Sua conta no ZapCatálogo está oficialmente ativada e pronta para receber produtos e pedidos no WhatsApp.') }}
            </p>
        </div>
    </div>

    {{-- Card com Identificação da Conta --}}
    @if ($currentUser)
        <div class="mb-5 flex items-center gap-3.5 rounded-2xl border border-[#37322f]/10 bg-white/70 p-4 text-xs text-[#514b46]">
            <span class="flex size-9 shrink-0 items-center justify-center rounded-xl bg-[#37322f]/5 text-[#37322f]">
                <i data-lucide="user-check" class="size-4"></i>
            </span>
            <div class="min-w-0 flex-1">
                <p class="text-[11px] font-medium uppercase tracking-wider text-[#716a64]">{{ __('Conta verificada') }}</p>
                <p class="truncate text-sm font-semibold text-[#37322f]">
                    {{ $currentUser->nome_loja ?? $currentUser->name ?? 'Lojista' }}
                    <span class="font-normal text-[#716a64]">• {{ $currentUser->email }}</span>
                </p>
            </div>
        </div>
    @endif

    {{-- Box Orientativo do Próximo Passo --}}
    <div class="mb-6 rounded-2xl border border-[#37322f]/10 bg-white/50 p-4 text-xs leading-relaxed text-[#605a57]">
        <p class="mb-1.5 font-medium text-[#37322f]">
            {{ __('Qual é o próximo passo?') }}
        </p>
        <p class="text-[#716a64]">
            {{ __('Escolha o plano que melhor atende ao momento da sua empresa para desbloquear recursos como catálogo com pedidos ilimitados, fotos em alta resolução e painel de controle.') }}
        </p>
    </div>

    {{-- Botão Primário CTA: Planos --}}
    <a href="{{ route('plans') }}"
        class="inline-flex h-12 w-full items-center justify-center gap-2 rounded-full bg-[#37322f] px-6 text-sm font-semibold text-white shadow-sm transition hover:bg-[#514a45] active:scale-[0.99] cursor-pointer">
        <span>{{ __('Escolher meu plano') }}</span>
        <i data-lucide="arrow-right" class="size-4"></i>
    </a>

    {{-- Link Secundário: Início --}}
    <p class="mt-4 text-center">
        <a href="{{ route('home') }}" class="inline-flex items-center gap-1.5 text-xs font-medium text-[#716a64] transition hover:text-[#37322f] hover:underline">
            <i data-lucide="arrow-left" class="size-3.5"></i>
            <span>{{ __('Voltar para a página inicial') }}</span>
        </a>
    </p>
</x-auth-split-layout>
