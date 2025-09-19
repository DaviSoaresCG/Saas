@extends('layouts.app')
@section('content')
<form action="{{ route('products.store', ['slug' => Auth::user()->slug]) }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div>
        <label for="nome">Nome do Produto:</label>
        <input type="text" id="name" name="name" value="{{old('name')}}" required  class="border-2 p-2">
    </div>
    @error('name')
        <p class="text-red-600 text-sm">{{$message}}</p>
    @enderror

    <div>
        <label for="imagem">Imagem do Produto:</label>
        <input type="file" id="image" name="image" class="border-2 p-2">
    </div>
    @error('name')
        <p class="text-red-600 text-sm">{{$message}}</p>
    @enderror

    <div>
        <label for="description">Descrição:</label>
        <input type="text" id="description" name="description" value="{{old('description')}}" required class="border-2 p-2">
    </div>
    @error('name')
        <p class="text-red-600 text-sm">{{$message}}</p>
    @enderror

    <div>
        <label for="value">Valor:</label>
        <input type="text" id="value" name="value" value="{{old('value')}}" required class="border-2 p-2">
    </div>
    @error('name')
        <p class="text-red-600 text-sm">{{$message}}</p>
    @enderror

    <button type="submit">Salvar Produto</button>
</form>
</main>
@endsection