@extends('layouts.app')
@section('content')
<h1>Meu Carrinho</h1>

<div class="overflow-x-auto">
    <table class="min-w-full text-sm text-left text-gray-500 dark:text-gray-400 border border-gray-200 dark:border-gray-700 rounded-lg shadow">
        <thead class="text-xs uppercase bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
            <tr>
                <th scope="col" class="px-6 py-3">Produto</th>
                <th scope="col" class="px-6 py-3">Preço</th>
                <th scope="col" class="px-6 py-3">Qtd</th>
                <th scope="col" class="px-6 py-3">Total</th>
                <th scope="col" class="px-6 py-3">Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cart as $id => $item)
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                        {{ $item['name'] }}
                    </td>
                    <td class="px-6 py-4">
                        R$ {{ number_format($item['value'], 2, ',', '.') }}
                    </td>
                    <td class="px-6 py-4">
                        <form action="{{ route('cart.update', ['slug' => $user->slug, 'id' => $id]) }}" method="POST" class="flex items-center space-x-2">
                            @csrf
                            <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1"
                                   class="w-16 px-2 py-1 border rounded-md text-center dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-indigo-500">
                            <button type="submit"
                                    class="px-3 py-1 text-sm font-medium text-white bg-indigo-600 rounded hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                Atualizar
                            </button>
                        </form>
                    </td>
                    <td class="px-6 py-4">
                        R$ {{ number_format($item['value'] * $item['quantity'], 2, ',', '.') }}
                    </td>
                    <td class="px-6 py-4">
                        <a href="{{ route('cart.remove', ['slug' => $user->slug, 'id' => $id]) }}"
                           class="px-3 py-1 text-sm font-medium text-red-600 border border-red-600 rounded hover:bg-red-600 hover:text-white transition">
                            Remover
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    a
</div>

<a href="{{route('whatsapp', ['slug' => $user->slug])}}" class="bg-yellow-500 p-4 rounded">Finalizar pedido</a>
<a class="bg-blue-600 p-4 rounded text-white" href="{{ route('cart.clear', ['slug' => $user->slug]) }}">Esvaziar carrinho</a>

@endsection