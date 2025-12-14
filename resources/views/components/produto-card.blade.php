@props(['produto', 'user'])
<div class="rounded-lg border border-gray-200 bg-white p-3 shadow-sm dark:border-gray-700 dark:bg-gray-800">
    <div class="h-56 w-full">
        <a href="{{ route('products.show', ['product' => $produto->id, 'slug' => $user->slug]) }}">
            <img class="mx-auto hidden h-full rounded-2xl object-cover dark:block" src="{{ asset('storage/' . $produto->path) }}"
                alt="" width="200" />
        </a>
    </div>
    <div class="pt-6">

        <a href="{{ route('products.show', ['product' => $produto->id, 'slug' => $user->slug]) }}"
            class="text-lg font-semibold leading-tight text-gray-900 hover:underline dark:text-white">{{ $produto->name }}</a>
        <div class="mt-4 flex items-center justify-between gap-4">
            <p class="text-2xl font-extrabold leading-tight text-gray-900 dark:text-white">R$ {{ $produto->value }}</p>
            <a href="{{ route('cart.add', ['id' => $produto->id]) }}"
                class="inline-flex items-center rounded-lg bg-primary-700 px-5 py-2.5 text-sm font-medium text-white hover:bg-primary-800 focus:outline-none focus:ring-4  focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                <button type="button"
                    class="inline-flex items-center rounded-lg bg-blue-700 px-5 py-2.5 text-sm font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-4  focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    adicionar ao carrinho
                </button>
            </a>
        </div>
    </div>

</div>
