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
                <input type="submit" value="Logout" class="text-white cursor-pointer">
            </form>
        </div>
    </div>

    <div class="text-center mt-5">
        <p class="display-6">Dashboard!</p>

    </div>

    <hr>

    <div class="text-center">
        <p>Subsctiption termina em <strong>{{$subscription_end}}</strong></p>
        <a href="{{ route('products.index', ['slug' => $user->slug]) }}">Acessar página da loja</a>
        <a href="{{ route('products.create', ['slug' => $user->slug]) }}">Criar um produto</a>
    </div>

    <hr>

    @foreach($invoices as $invoice)
        <div class="text-center">
            <a href="{{ Route('invoice.download', ['id' => $invoice->id]) }}" 
                class="btn btn-primary">
                Gerar Nota Fiscal
            </a>
        </div>
    @endforeach
@endsection