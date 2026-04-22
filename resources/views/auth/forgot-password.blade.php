<x-guest-layout>
    <div class="mb-4 text-sm text-[var(--text-base)]">
        {{ __('Esqueceu a sua senha? Sem problemas. Apenas digite seu endereço de email e vamos enviar um email com o link para resetar a sua senha. ') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Enviar Email') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
