<section>
    <header>
        <h2 class="text-lg font-medium text-[var(--text-base)]">
            {{ __('Atualizar o Slug(Subdominio)') }}
        </h2>

        <p class="mt-1 text-sm text-[var(--text-muted)]">
            {{ __("Coloque um novo subdominio para seu site") }}
        </p>
    </header>


    <form method="post" action="{{ route('slug.update', ['slug' => auth()->user()->slug]) }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="slug" :value="__('Slug')" />
            <x-text-input id="slug" name="slug" type="text" class="mt-1 block w-full" :value="$user->slug" required autofocus autocomplete="slug" />
            <x-input-error class="mt-2" :messages="$errors->get('slug_request')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Salvar') }}</x-primary-button>

            @if (session('status') === 'slug-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = true, 2000)"
                    class="text-sm text-green-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
