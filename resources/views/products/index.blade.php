<x-app-layout>
    <x-slot:header>
        <a href="{{ route('dashboard', ['slug' => $user->slug]) }}">
            <x-primary-button>Dashboard</x-primary-button>
        </a>
        <a href="{{ route('cart.index', ['slug' => $user->slug]) }}">
            <x-primary-button>Carrinho</x-primary-button>
        </a>
    </x-slot:header>
    <section class="bg-gray-50 py-8 antialiased dark:bg-gray-900 md:py-12">
        <div class="mx-auto max-w-screen-xl px-4 2xl:px-0">
            @if (session('success'))
                <div class="p-3 bg-green-700 text-white">
                    <p>
                        {{ session('success') }}
                    </p>
                </div>
            @endif
            <div class="mb-4 grid gap-4 sm:grid-cols-2 md:mb-8 lg:grid-cols-3 xl:grid-cols-4">
                @foreach ($products as $product)
                    <x-produto-card :produto="$product" :user="$user" />
                @endforeach
            </div>
    </section>
</x-app-layout>
