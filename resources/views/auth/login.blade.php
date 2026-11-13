<x-auth-split-layout
    title="Entrar"
    eyebrow="Que bom ter você de volta"
    heading="Entre na sua conta."
    subheading="Acesse seu painel e continue cuidando da sua loja.">

    {{-- Feedback de Status da Sessão --}}
    @if (session('status'))
        <div class="mb-5 rounded-2xl border border-[#d8dfc9] bg-[#f0f7eb] p-4 text-xs font-medium text-[#487045]" role="status">
            {{ session('status') }}
        </div>
    @endif

    @if (session('success'))
        <div class="mb-5 rounded-2xl border border-[#dce8d3] bg-[#f0f7eb] p-4 text-xs font-medium text-[#487045]" role="status">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-5 rounded-xl border border-[#d8b8a9] bg-[#fbf0eb] px-4 py-3 text-xs leading-5 text-[#81513f]" role="alert">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="flex flex-col gap-5">
        @csrf

        {{-- Campo E-mail --}}
        <div class="flex flex-col gap-2">
            <label for="email" class="text-xs font-medium text-[#514b46]">E-mail</label>
            <div class="relative">
                <i data-lucide="mail" class="pointer-events-none absolute left-4 top-1/2 size-4 -translate-y-1/2 text-[#969087]"></i>
                <input id="email" name="email" type="email" autocomplete="email" required autofocus
                    value="{{ old('email') }}" placeholder="voce@exemplo.com"
                    class="h-12 w-full rounded-xl border border-[#37322f]/15 bg-white/80 pl-11 pr-4 text-sm text-[#37322f] outline-none transition placeholder:text-[#a19a93] focus:border-[#8ea05e] focus:ring-2 focus:ring-[#8ea05e]/15 @error('email') border-red-400 @enderror">
            </div>
            @error('email')
                <p class="text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Campo Senha com Alternância de Visibilidade --}}
        <div class="flex flex-col gap-2" x-data="{ show: false }">
            <div class="flex items-center justify-between">
                <label for="password" class="text-xs font-medium text-[#514b46]">Senha</label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-xs font-medium text-[#716a64] transition hover:text-[#37322f]">
                        Esqueceu a senha?
                    </a>
                @endif
            </div>
            <div class="relative">
                <i data-lucide="lock" class="pointer-events-none absolute left-4 top-1/2 size-4 -translate-y-1/2 text-[#969087]"></i>
                <input id="password" name="password" :type="show ? 'text' : 'password'" autocomplete="current-password" required
                    placeholder="Sua senha"
                    class="h-12 w-full rounded-xl border border-[#37322f]/15 bg-white/80 pl-11 pr-12 text-sm text-[#37322f] outline-none transition placeholder:text-[#a19a93] focus:border-[#8ea05e] focus:ring-2 focus:ring-[#8ea05e]/15 @error('password') border-red-400 @enderror">
                <button type="button" @click="show = !show" :aria-label="show ? 'Ocultar senha' : 'Mostrar senha'"
                    class="absolute right-3 top-1/2 flex size-8 -translate-y-1/2 items-center justify-center rounded-lg text-[#77706a] hover:bg-[#37322f]/5 transition">
                    <i data-lucide="eye" class="size-4" x-show="!show"></i>
                    <i data-lucide="eye-off" class="size-4" x-show="show" style="display: none;"></i>
                </button>
            </div>
            @error('password')
                <p class="text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Lembrar de mim --}}
        <div class="flex items-center">
            <label for="remember_me" class="inline-flex items-center gap-2 cursor-pointer">
                <input id="remember_me" type="checkbox" name="remember" class="size-4 rounded border-[#37322f]/20 text-[#37322f] focus:ring-[#8ea05e]">
                <span class="text-xs text-[#716a64]">Lembrar de mim</span>
            </label>
        </div>

        {{-- Botão Entrar --}}
        <button type="submit" class="mt-2 inline-flex h-12 w-full items-center justify-center gap-2 rounded-full bg-[#37322f] px-6 text-sm font-semibold text-white shadow-sm transition hover:bg-[#514a45] active:scale-[0.99]">
            <span>Entrar</span>
            <i data-lucide="arrow-right" class="size-4"></i>
        </button>
    </form>

    {{-- Link de Transição para Cadastro --}}
    <p class="mt-6 text-center text-sm text-[#716a64]">
        Ainda não tem conta?
        <a href="{{ route('register') }}" class="font-semibold text-[#37322f] underline decoration-[#a9b484] underline-offset-4 hover:text-[#71834f] transition">
            Criar conta
        </a>
    </p>
</x-auth-split-layout>
