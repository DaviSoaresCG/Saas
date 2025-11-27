<x-guest-layout>
    {{-- <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
        {{ __('Enviamos um email de verificação para você, abra a caixa de span se não chegou ou aguarde mais um pouco. Você também pode reenviar a verificação clicando no botão abaixo') }}
    </div> --}}

    {{-- @if (session('status') == 'verification-link-sent') --}}
    <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
        Parabens! Você verificou o email, agora pode prosseguir para a escolha dos planos, clique no botão abaixo!
    </div>
    {{-- @endif --}}

    <div class="mt-4 flex items-center justify-between">
        <div>
            <a href="{{ route('plans') }}">
                <x-primary-button>
                    Acessar todos os planos
                </x-primary-button>
            </a>
        </div>
    </div>
</x-guest-layout>
