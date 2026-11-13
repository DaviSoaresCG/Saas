<x-auth-split-layout
    title="Redefinir senha"
    eyebrow="Nova credencial"
    heading="Crie sua nova senha."
    subheading="Defina uma nova senha de acesso segura para continuar cuidando da sua vitrine.">

    @if ($errors->any())
        <div class="mb-5 rounded-xl border border-[#d8b8a9] bg-[#fbf0eb] px-4 py-3 text-xs leading-5 text-[#81513f]" role="alert">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.store') }}" class="flex flex-col gap-4" x-data="{ showPass: false }">
        @csrf

        {{-- Token de redefinição --}}
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        {{-- Campo E-mail --}}
        <div class="flex flex-col gap-1.5">
            <label for="email" class="text-xs font-medium text-[#514b46]">E-mail</label>
            <div class="relative">
                <i data-lucide="mail" class="pointer-events-none absolute left-4 top-1/2 size-4 -translate-y-1/2 text-[#969087]"></i>
                <input id="email" name="email" type="email" autocomplete="username" required autofocus maxlength="255"
                    value="{{ old('email', $request->email) }}" placeholder="voce@exemplo.com"
                    class="h-11 w-full rounded-xl border border-[#37322f]/15 bg-white/80 pl-11 pr-4 text-sm text-[#37322f] outline-none transition placeholder:text-[#a19a93] focus:border-[#8ea05e] focus:ring-2 focus:ring-[#8ea05e]/15 @error('email') border-red-400 @enderror">
            </div>
            @error('email')
                <p class="text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Nova Senha --}}
        <div class="flex flex-col gap-1.5">
            <label for="password" class="text-xs font-medium text-[#514b46]">Nova senha</label>
            <div class="relative">
                <i data-lucide="lock" class="pointer-events-none absolute left-4 top-1/2 size-4 -translate-y-1/2 text-[#969087]"></i>
                <input id="password" name="password" :type="showPass ? 'text' : 'password'" autocomplete="new-password" required minlength="8"
                    placeholder="Mínimo 8 dígitos"
                    class="h-11 w-full rounded-xl border border-[#37322f]/15 bg-white/80 pl-11 pr-10 text-sm text-[#37322f] outline-none transition placeholder:text-[#a19a93] focus:border-[#8ea05e] focus:ring-2 focus:ring-[#8ea05e]/15 @error('password') border-red-400 @enderror">
                <button type="button" @click="showPass = !showPass" :aria-label="showPass ? 'Ocultar senha' : 'Mostrar senha'"
                    class="absolute right-2.5 top-1/2 flex size-7 -translate-y-1/2 items-center justify-center rounded-lg text-[#77706a] hover:bg-[#37322f]/5 transition">
                    <i data-lucide="eye" class="size-3.5" x-show="!showPass"></i>
                    <i data-lucide="eye-off" class="size-3.5" x-show="showPass" style="display: none;"></i>
                </button>
            </div>
            @error('password')
                <p class="text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Confirme a Nova Senha --}}
        <div class="flex flex-col gap-1.5">
            <label for="password_confirmation" class="text-xs font-medium text-[#514b46]">Confirme a nova senha</label>
            <div class="relative">
                <i data-lucide="lock" class="pointer-events-none absolute left-4 top-1/2 size-4 -translate-y-1/2 text-[#969087]"></i>
                <input id="password_confirmation" name="password_confirmation" :type="showPass ? 'text' : 'password'" autocomplete="new-password" required minlength="8"
                    placeholder="Repita a nova senha"
                    class="h-11 w-full rounded-xl border border-[#37322f]/15 bg-white/80 pl-11 pr-4 text-sm text-[#37322f] outline-none transition placeholder:text-[#a19a93] focus:border-[#8ea05e] focus:ring-2 focus:ring-[#8ea05e]/15 @error('password_confirmation') border-red-400 @enderror">
            </div>
            @error('password_confirmation')
                <p class="text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Botão Redefinir Senha --}}
        <button type="submit" class="mt-3 inline-flex h-12 w-full items-center justify-center gap-2 rounded-full bg-[#37322f] px-6 text-sm font-semibold text-white shadow-sm transition hover:bg-[#514a45] active:scale-[0.99]">
            <span>Redefinir senha</span>
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
