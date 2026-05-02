<x-guest-layout>
    <div class="mb-4 text-sm text-[var(--text-base)]">
        {{ __('Enviamos um email de verificação para você, abra a caixa de span se não chegou ou aguarde mais um pouco. Você também pode reenviar a verificação clicando no botão abaixo') }}
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 font-medium text-sm text-green-600 ">
            {{ __('Um novo email de verificação foi enviado com sucesso!') }}
        </div>
    @endif

    <div class="mt-4 flex items-center justify-between">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <div>
                <button type="submit"
                    class="cursor-pointer inline-flex items-center justify-center gap-2.5 rounded-2xl bg-[var(--color-primary)] hover:bg-opacity-90 px-7 py-3.5 text-sm font-bold text-[var(--text-on-primary)] shadow-lg shadow-[var(--color-primary)]/25 transition-all shrink-0 active:scale-95 cursor-pointer">
                    {{ __('Reenviar o email de verificação') }}
                </button>
            </div>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit"
                class="cursor-pointer inline-flex items-center justify-center gap-2.5 rounded-2xl bg-[var(--color-primary)] hover:bg-opacity-90 px-7 py-3.5 text-sm font-bold text-[var(--text-on-primary)] shadow-lg shadow-[var(--color-primary)]/25 transition-all shrink-0 active:scale-95 cursor-pointer">
                {{ __('Logout') }}
            </button>
        </form>
    </div>
</x-guest-layout>