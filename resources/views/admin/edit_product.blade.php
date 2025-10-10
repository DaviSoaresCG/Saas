@extends('layouts.admin')
@section('content')
<form action="{{ route('products.update', ['slug' => Auth::user()->slug, 'product' => $product->id]) }}" method="POST" enctype="multipart/form-data">
    @method('PATCH')
    @csrf

    <div>
        <label for="nome">Nome do Produto:</label>
        <input type="text" id="name" name="name" value="{{old('name', $product->name)}}" required  class="border-2 p-2">
    </div>
    @error('name')
        <p class="text-red-600 text-sm">{{$message}}</p>
    @enderror

    <div>
        <label for="imagem">Imagem do Produto:</label>
        <input type="file" id="image" name="image" value="{{asset('storage/'.$product->path)}}" class="border-2 p-2">
        <input type="text" name="path" id="path" value="{{asset('storage/'.$product->path)}}" hidden>
        <a href="{{ asset('storage/'.$product->path) }}" download="imagem.jpg">
            Baixar Imagem
        </a>
    </div>
    @error('name')
        <p class="text-red-600 text-sm">{{$message}}</p>
    @enderror

    <div>
        <label for="description">Descrição:</label>
        <input type="text" id="description" name="description" value="{{old('description', $product->description)}}" required class="border-2 p-2">
    </div>
    @error('name')
        <p class="text-red-600 text-sm">{{$message}}</p>
    @enderror

    <div>
        <label for="value">Valor:</label>
        <input type="text" id="value" name="value" value="{{old('value', $product->value)}}" required class="border-2 p-2">
    </div>
    @error('name')
        <p class="text-red-600 text-sm">{{$message}}</p>
    @enderror

    <button type="submit">Salvar Produto</button>
</form>
</main>
@endsection