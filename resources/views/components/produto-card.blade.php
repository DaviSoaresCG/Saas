@props(['produto', 'user'])
<div class="rounded-lg border border-gray-400 bg-white p-3 shadow-lg dark:shadow-sm dark:border-gray-700 dark:bg-gray-800">
    <div class="h-56 w-full">
        <a href="{{ route('products.show', ['product' => $produto->id, 'slug' => $user->slug]) }}">
            <img class="mx-auto h-full rounded-2xl object-fill dark:block" src="{{ asset('storage/' . $produto->path) }}"
                alt="" width="200" />
        </a>
    </div>
    <div class="pt-6 text-center">
        <a href="{{ route('products.show', ['product' => $produto->id, 'slug' => $user->slug]) }}"
            class="text-lg font-semibold leading-tight text-gray-900 hover:underline dark:text-white">{{ $produto->name }}</a>
        <div class="mt-4 flex flex-col items-center justify-between gap-4">
            <p class="sm:text-2xl text-lg font-extrabold leading-tight text-gray-900 dark:text-white">R$ {{ $produto->value }}</p>
            <a href="{{ route('cart.add', ['id' => $produto->id]) }}"
                class="inline-flex items-center rounded-lg text-center bg-blue-700 px-5 py-2.5 text-sm sm:text-base font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-4  focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                Adicionar ao carrinho
            </a>
        </div>
    </div>

</div>
