<x-auth-split-layout
    title="E-mail verificado com sucesso"
    eyebrow="Tudo pronto"
    heading="Conta verificada com sucesso!"
    subheading="Seu e-mail foi validado. Agora você já pode escolher o seu plano e colocar sua vitrine digital no ar.">

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
