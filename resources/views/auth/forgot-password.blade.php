<x-auth-split-layout
    title="Recuperar senha"
    eyebrow="Recuperação de acesso"
    heading="Esqueceu a senha?"
    subheading="Sem problemas. Digite seu e-mail cadastrado e enviaremos um link seguro para você redefinir sua senha.">

    {{-- Status da Sessão (Ex: "Enviamos o link de redefinição por e-mail!") --}}
    @if (session('status'))
        <div class="mb-5 rounded-xl border border-[#c4d693] bg-[#f4f8e6] px-4 py-3 text-xs leading-5 text-[#4e5c26]" role="status">
            {{ session('status') }}
        </div>
    @endif

    {{-- Erros de Validação Globais --}}
    @if ($errors->any())
        <div class="mb-5 rounded-xl border border-[#d8b8a9] bg-[#fbf0eb] px-4 py-3 text-xs leading-5 text-[#81513f]" role="alert">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="flex flex-col gap-4">
        @csrf

        {{-- Campo E-mail --}}
        <div class="flex flex-col gap-1.5">
            <label for="email" class="text-xs font-medium text-[#514b46]">E-mail cadastrado</label>
            <div class="relative">
                <i data-lucide="mail" class="pointer-events-none absolute left-4 top-1/2 size-4 -translate-y-1/2 text-[#969087]"></i>
                <input id="email" name="email" type="email" autocomplete="email" required autofocus maxlength="255"
                    value="{{ old('email') }}" placeholder="voce@exemplo.com"
                    class="h-11 w-full rounded-xl border border-[#37322f]/15 bg-white/80 pl-11 pr-4 text-sm text-[#37322f] outline-none transition placeholder:text-[#a19a93] focus:border-[#8ea05e] focus:ring-2 focus:ring-[#8ea05e]/15 @error('email') border-red-400 @enderror">
            </div>
            @error('email')
                <p class="text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Botão Enviar Link --}}
        <button type="submit" class="mt-3 inline-flex h-12 w-full items-center justify-center gap-2 rounded-full bg-[#37322f] px-6 text-sm font-semibold text-white shadow-sm transition hover:bg-[#514a45] active:scale-[0.99]">
            <span>Enviar link de recuperação</span>
            <i data-lucide="arrow-right" class="size-4"></i>
        </button>
    </form>

    {{-- Link de Retorno ao Login --}}
    <p class="mt-6 text-center text-sm text-[#716a64]">
        Lembrou da sua senha?
        <a href="{{ route('login') }}" class="font-semibold text-[#37322f] underline decoration-[#a9b484] underline-offset-4 hover:text-[#71834f] transition">
            Voltar para o login
        </a>
    </p>
</x-auth-split-layout>
