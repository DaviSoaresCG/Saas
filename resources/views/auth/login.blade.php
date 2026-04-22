<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />
    <x-auth-session-status class="mb-4" :status="session('success')" />

    <form method="POST" action="{{ route('login') }}">
        <h1 class="text-2xl font-bold text-center mb-4 text-[var(--text-base)]">Login</h1>
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Senha')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex items-center gap-3  justify-end mt-4">
            @if (Route::has('password.request'))
                <a class=" text-sm text-[var(--text-muted)] hover:text-[var(--text-base)] transition-all ease-in duration-75" href="{{ route('password.request') }}">
                    {{ __('Esqueceu a senha?') }}
                </a>
            @endif
            <a class=" text-sm text-[var(--text-muted)] hover:text-[var(--text-base)] transition-all ease-in duration-75" href="{{ route('register') }}">
                {{ __('Nao possui conta?') }}
            </a>

            <x-primary-button class="ms-3">
                {{ __('Entrar') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
