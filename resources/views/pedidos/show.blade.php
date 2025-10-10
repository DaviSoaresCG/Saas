@extends('layouts.admin')
@section('content')
<section class="bg-gray-50 min-h-screen dark:bg-gray-900 p-3 sm:p-5">
    <div class="mx-auto max-w-screen-xl px-4 lg:px-12">
        <!-- Start coding here -->
        <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg">
            <div class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">
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
                        @foreach($itens_pedido as $item)
                            <tr class="border-b dark:border-gray-700">
                                <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    <a href="{{route('products.show', ['slug' => Auth::user()->slug, 'product' => $item->product->id])}}">ds{{$item->product->name}}</a>
                                </td>
                                <td class="px-4 py-3">R$ {{ $item->value }}</td>
                                <td class="px-4 py-3">{{$item->quantidade}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        {{ $itens_pedido->links() }}
        </div>
    </div>
</section>
@endsection