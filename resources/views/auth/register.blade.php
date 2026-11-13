<x-auth-split-layout
    title="Criar conta"
    eyebrow="Sua loja começa aqui"
    heading="Crie sua conta."
    subheading="Prepare sua vitrine digital e comece a receber pedidos pelo WhatsApp.">

    @if ($errors->any())
        <div class="mb-5 rounded-xl border border-[#d8b8a9] bg-[#fbf0eb] px-4 py-3 text-xs leading-5 text-[#81513f]" role="alert">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}" class="flex flex-col gap-4">
        @csrf

        {{-- Campo Nome Completo --}}
        <div class="flex flex-col gap-1.5">
            <label for="name" class="text-xs font-medium text-[#514b46]">Nome completo</label>
            <div class="relative">
                <i data-lucide="user" class="pointer-events-none absolute left-4 top-1/2 size-4 -translate-y-1/2 text-[#969087]"></i>
                <input id="name" name="name" type="text" autocomplete="name" required autofocus maxlength="255"
                    value="{{ old('name') }}" placeholder="Como podemos chamar você?"
                    class="h-11 w-full rounded-xl border border-[#37322f]/15 bg-white/80 pl-11 pr-4 text-sm text-[#37322f] outline-none transition placeholder:text-[#a19a93] focus:border-[#8ea05e] focus:ring-2 focus:ring-[#8ea05e]/15 @error('name') border-red-400 @enderror">
            </div>
            @error('name')
                <p class="text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Campo E-mail --}}
        <div class="flex flex-col gap-1.5">
            <label for="email" class="text-xs font-medium text-[#514b46]">E-mail</label>
            <div class="relative">
                <i data-lucide="mail" class="pointer-events-none absolute left-4 top-1/2 size-4 -translate-y-1/2 text-[#969087]"></i>
                <input id="email" name="email" type="email" autocomplete="email" required maxlength="255"
                    value="{{ old('email') }}" placeholder="voce@exemplo.com"
                    class="h-11 w-full rounded-xl border border-[#37322f]/15 bg-white/80 pl-11 pr-4 text-sm text-[#37322f] outline-none transition placeholder:text-[#a19a93] focus:border-[#8ea05e] focus:ring-2 focus:ring-[#8ea05e]/15 @error('email') border-red-400 @enderror">
            </div>
            @error('email')
                <p class="text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Campo WhatsApp --}}
        <div class="flex flex-col gap-1.5">
            <label for="whatsapp" class="text-xs font-medium text-[#514b46]">WhatsApp da Loja</label>
            <div class="relative">
                <i data-lucide="message-circle" class="pointer-events-none absolute left-4 top-1/2 size-4 -translate-y-1/2 text-[#969087]"></i>
                <input id="whatsapp" name="whatsapp" type="tel" autocomplete="tel" required
                    value="{{ old('whatsapp') }}" placeholder="(xx) 9xxxx-xxxx"
                    x-data x-mask="(99) 99999-9999"
                    class="h-11 w-full rounded-xl border border-[#37322f]/15 bg-white/80 pl-11 pr-4 text-sm text-[#37322f] outline-none transition placeholder:text-[#a19a93] focus:border-[#8ea05e] focus:ring-2 focus:ring-[#8ea05e]/15 @error('whatsapp') border-red-400 @enderror">
            </div>
            <span class="text-[10px] text-[#857d76]">É por este número que você receberá os pedidos dos clientes.</span>
            @error('whatsapp')
                <p class="text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Campo Nome da Loja --}}
        <div class="flex flex-col gap-1.5">
            <label for="store_name" class="text-xs font-medium text-[#514b46]">Nome da Loja</label>
            <div class="relative">
                <i data-lucide="store" class="pointer-events-none absolute left-4 top-1/2 size-4 -translate-y-1/2 text-[#969087]"></i>
                <input id="store_name" name="store_name" type="text" autocomplete="organization" required maxlength="255"
                    value="{{ old('store_name') }}" placeholder="Ex: Minha Loja"
                    class="h-11 w-full rounded-xl border border-[#37322f]/15 bg-white/80 pl-11 pr-4 text-sm text-[#37322f] outline-none transition placeholder:text-[#a19a93] focus:border-[#8ea05e] focus:ring-2 focus:ring-[#8ea05e]/15 @error('store_name') border-red-400 @enderror">
            </div>
            <span class="text-[10px] text-[#857d76]">Seu link exclusivo será gerado a partir deste nome.</span>
            @error('store_name')
                <p class="text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Campos de Senha e Confirmação com Toggle Conjunto --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2" x-data="{ showPass: false }">
            <div class="flex flex-col gap-1.5">
                <label for="password" class="text-xs font-medium text-[#514b46]">Senha</label>
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

            <div class="flex flex-col gap-1.5">
                <label for="password_confirmation" class="text-xs font-medium text-[#514b46]">Confirme a senha</label>
                <div class="relative">
                    <i data-lucide="lock" class="pointer-events-none absolute left-4 top-1/2 size-4 -translate-y-1/2 text-[#969087]"></i>
                    <input id="password_confirmation" name="password_confirmation" :type="showPass ? 'text' : 'password'" autocomplete="new-password" required minlength="8"
                        placeholder="Repita a senha"
                        class="h-11 w-full rounded-xl border border-[#37322f]/15 bg-white/80 pl-11 pr-4 text-sm text-[#37322f] outline-none transition placeholder:text-[#a19a93] focus:border-[#8ea05e] focus:ring-2 focus:ring-[#8ea05e]/15 @error('password_confirmation') border-red-400 @enderror">
                </div>
                @error('password_confirmation')
                    <p class="text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Botão Criar Conta --}}
        <button type="submit" class="mt-3 inline-flex h-12 w-full items-center justify-center gap-2 rounded-full bg-[#37322f] px-6 text-sm font-semibold text-white shadow-sm transition hover:bg-[#514a45] active:scale-[0.99]">
            <span>Criar minha conta</span>
            <i data-lucide="arrow-right" class="size-4"></i>
        </button>
    </form>

    {{-- Link de Transição para Login --}}
    <p class="mt-5 text-center text-sm text-[#716a64]">
        Já tem uma conta?
        <a href="{{ route('login') }}" class="font-semibold text-[#37322f] underline decoration-[#a9b484] underline-offset-4 hover:text-[#71834f] transition">
            Entrar
        </a>
    </p>
</x-auth-split-layout>
