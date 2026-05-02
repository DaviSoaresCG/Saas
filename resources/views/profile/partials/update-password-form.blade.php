<section>
    <header>
        <h2 class="text-lg font-medium text-[var(--text-base)]">
            {{ __('Mude sua senha') }}
        </h2>

        <p class="mt-1 text-sm text-[var(--text-muted)]">
            {{ __('Use uma senha forte para que seja segura') }}
        </p>
    </header>

    <form method="post" id="password" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div>
            <x-input-base id="current_password" name="current_password" type="password" class="mt-1 block w-full"
                autocomplete="current-password" icon="lock" placeholder="Senha Atual" label="Senha atual" value="" />
            @error('current_password', 'updatePassword')
                <p class="text-sm font-medium text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <x-input-base id="password" name="password" type="password" class="mt-1 block w-full"
                autocomplete="new-password" icon="lock" placeholder="Nova Senha" label="Nova senha" value="" />
            @error('password', 'updatePassword')
                <p class="text-sm font-medium text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <x-input-base id="password_confirmation" name="password_confirmation" type="password"
                class="mt-1 block w-full" autocomplete="new-password" icon="lock" placeholder="Confirmar Senha"
                value="{{ old('password_confirmation') }}" label="Confirmar nova senha" />
            @error('password_confirmation', 'updatePassword')
                <p class="text-sm font-medium text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Salvar') }}</x-primary-button>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-green-600">{{ __('Salvo.') }}</p>
            @endif
        </div>
    </form>
</section>