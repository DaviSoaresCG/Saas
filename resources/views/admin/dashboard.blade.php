@extends('layouts.admin')
@section('content')
    <div class="d-flex justify-content-between p-3 bg-black">
        <div>
            <p class="text-warning">Laravel Cashier (Stripe)</p>
        </div>
        <div>
            <span>{{ Auth::user()->name }}</span>
            <span class="px-3">|</span>
            <form action="{{route('logout')}}" method="post">
                @csrf
                <input type="submit" value="Logout" class="text-white p-2 bg-blue-600 rounded cursor-pointer">
            </form>
        </div>
    </div>

    <div class="text-center mt-5">
        <p class="display-6">Dashboard!</p>

    </div>

    <hr>

    <div class="text-center">
        <p>Subsctiption termina em <strong>{{$subscription_end}}</strong></p>
        <a href="{{ route('products.index', ['slug' => $user->slug]) }}" class="inline-block bg-gray-600 p-2 text-white rounded">Acessar página da loja</a>
        <a href="{{ route('products.create', ['slug' => $user->slug]) }}" class="inline-block bg-blue-700 p-2 text-white rounded">Criar um produto</a>
        <a href="{{ route('admin.products', ['slug' => $user->slug]) }}" class="bg-green-700 inline-block p-2 text-white rounded">Lista de Produtos</a>
        <a href="{{ route('pedidos.index', ['slug' => $user->slug]) }}" class="bg-red-700 inline-block p-2 text-white rounded">Lista de pedidos</a>
    </div>

    <hr>

    @foreach($invoices as $invoice)
        <div class="text-center">
            <a href="{{ Route('invoice.download', ['id' => $invoice->id]) }}" 
                class="p-2 bg-yellow-500 rounded inline-block">
                Gerar Nota Fiscal
            </a>
        </div>
    @endforeach
@endsection