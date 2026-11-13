<x-auth-split-layout
    title="Verifique seu e-mail"
    eyebrow="Quase lá"
    heading="Confirme seu endereço de e-mail."
    subheading="Enviamos um link de confirmação para sua caixa de entrada.">

    {{-- Feedback de Reenvio do Link --}}
    @if (session('status') == 'verification-link-sent')
        <div class="mb-5 flex items-start gap-3 rounded-2xl border border-[#c4d693] bg-[#f4f8e6] p-4 text-xs font-medium text-[#4e5c26]" role="status">
            <i data-lucide="check-circle" class="mt-0.5 size-4 shrink-0 text-[#71834f]"></i>
            <p>{{ __('Um novo link de verificação foi enviado para o seu endereço de e-mail cadastrado.') }}</p>
        </div>
    @endif

    {{-- Cartão do E-mail Cadastrado --}}
    @if (auth()->check())
        <div class="mb-5 flex items-center gap-3 rounded-2xl border border-[#37322f]/10 bg-white/70 p-4 text-xs text-[#514b46]">
            <span class="flex size-9 shrink-0 items-center justify-center rounded-xl bg-[#37322f]/5 text-[#37322f]">
                <i data-lucide="mail-check" class="size-4"></i>
            </span>
            <div class="min-w-0 flex-1">
                <p class="text-[11px] font-medium text-[#716a64] uppercase tracking-wider">{{ __('Link enviado para') }}</p>
                <p class="truncate text-sm font-semibold text-[#37322f]">{{ auth()->user()->email }}</p>
            </div>
        </div>
    @endif

    {{-- Orientações e Caixa de Spam --}}
    <div class="mb-6 rounded-2xl border border-[#37322f]/10 bg-white/50 p-4 text-xs leading-relaxed text-[#605a57]">
        <p class="mb-2 font-medium text-[#37322f]">
            {{ __('O que fazer agora?') }}
        </p>
        <ul class="space-y-1.5 list-disc list-inside text-[#716a64]">
            <li>{{ __('Abra seu serviço de e-mail e procure pela mensagem do ZapCatálogo.') }}</li>
            <li>{{ __('Clique no link para validar sua conta e começar a montar sua vitrine.') }}</li>
            <li>{{ __('Não encontrou? Verifique sua caixa de spam ou lixo eletrônico.') }}</li>
        </ul>
    </div>

    {{-- Formulário de Reenvio --}}
    <form method="POST" action="{{ route('verification.send') }}" class="w-full">
        @csrf
        <button type="submit"
            class="inline-flex h-12 w-full items-center justify-center gap-2 rounded-full bg-[#37322f] px-6 text-sm font-semibold text-white shadow-sm transition hover:bg-[#514a45] active:scale-[0.99] cursor-pointer">
            <i data-lucide="send" class="size-4"></i>
            <span>{{ __('Reenviar e-mail de verificação') }}</span>
        </button>
    </form>

    {{-- Ação Discreta de Logout / Troca de Conta --}}
    <form method="POST" action="{{ route('logout') }}" class="mt-4 text-center">
        @csrf
        <button type="submit"
            class="inline-flex items-center gap-1.5 text-xs font-medium text-[#716a64] transition hover:text-[#37322f] hover:underline cursor-pointer">
            <i data-lucide="log-out" class="size-3.5"></i>
            <span>{{ __('Sair da conta ou trocar de e-mail') }}</span>
        </button>
    </form>
</x-auth-split-layout>
