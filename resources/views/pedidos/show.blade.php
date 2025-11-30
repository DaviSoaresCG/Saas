<x-app-layout>
    <nav class="text-sm text-gray-300 mb-4 ml-2">
        <a href="{{ route('dashboard') }}" class="hover:underline">Dashboard</a>
        <span class="mx-2">/</span>
        <a href="{{ route('pedidos.index') }}" class="hover:underline">Lista de pedidos</a>
        <span class="mx-2">/</span>
        <span class="text-white">Pedido</span>
    </nav>
    <section class="bg-gray-50 min-h-screen dark:bg-gray-900 p-3 sm:p-5">
        <div class="mx-auto max-w-screen-xl px-4 lg:px-12">
            <div>
                <h1 class="text-2xl text-white font-bold">Lista de produtos do pedido</h1>
            </div>
            <!-- Start coding here -->
            <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg">
                <div
                    class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-4 py-3">Nome</th>
                                {{-- <th scope="col" class="px-4 py-3">Category</th> --}}
                                <th scope="col" class="px-4 py-3">Preço</th>
                                <th scope="col" class="px-4 py-3">Quantidade</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($itens_pedido as $item)
                                <tr class="border-b dark:border-gray-700">
                                    <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        <a
                                            href="{{ route('products.show', ['slug' => Auth::user()->slug, 'product' => $item->product->id]) }}">{{ $item->product->name }}</a>
                                    </td>
                                    <td class="px-4 py-3">R$ {{ $item->value }}</td>
                                    <td class="px-4 py-3">{{ $item->quantidade }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $itens_pedido->links() }}
            </div>
        </div>
    </section>
</x-app-layout>
