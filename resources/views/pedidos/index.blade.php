<x-app-layout>
<nav class="text-sm dark:text-gray-300 mb-4 ml-2">
    <a href="{{ route('dashboard') }}" class="hover:underline">Dashboard</a>
    <span class="mx-2">/</span>
    <span class="dark:text-white">Lista de pedidos</span>
</nav>
    <section class=" dark:bg-gray-900 p-3 sm:p-5">
        <div class="mx-auto max-w-screen-xl px-4 lg:px-12">
            @if (session('error'))
                <div class="bg-red-600 p-3 text-white rounded">
                    <p>
                        {{ session('error') }}
                    </p>
                </div>
            @endif
            <div class="bg-white dark:bg-gray-800 relative shadow-md  sm:rounded-lg overflow-hidden">
                <div class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">
                    <div class="w-full md:w-1/2">
                        <form action="{{ route('order.search') }}" class="flex items-center"
                            method="GET">

                            <label for="simple-search" class="sr-only">Procurar pelo ID</label>
                            <div class="relative w-full">

                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400"
                                        fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd"
                                            d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <input type="text" id="simple-search" name="id"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    placeholder="Procurar pelo ID" required>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-4 py-3">ID</th>
                                <th scope="col" class="px-4 py-3">Valor do pedido</th>
                                <th scope="col" class="px-4 py-3">Data/hora</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pedidos as $pedido)
                                <tr class="border-b dark:border-gray-700 hover:bg-gray-200 text-gray-700 dark:text-white dark:hover:bg-gray-700 transition-all duration-50 ease-in-out cursor-pointer"
                                    onclick="window.location='{{ route('order.show',['id' => $pedido->id]) }}'">
                                    <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ $pedido->id }}</td>
                                    <td class="px-4 py-3">{{ $pedido->total }}</td>
                                    <td class="px-4 py-3">{{ $pedido->created_at }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
            </div>
            @if(!$search)
                    {{ $pedidos->links() }}
                @endif
        </div>
    </section>
</x-app-layout>