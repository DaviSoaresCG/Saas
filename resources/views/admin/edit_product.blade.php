{{--
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
--}}

@extends('layouts.admin')
@section('content')
    <section class="bg-white dark:bg-gray-900">
        <div class="py-8 px-4 mx-auto max-w-2xl lg:py-16">
            <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">Add a new product</h2>
            <form action="{{ route('products.update', ['slug' => Auth::user()->slug, 'product' => $product->id]) }}"
                method="POST" enctype="multipart/form-data">
                @method('PATCH')
                @csrf
                <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                    <div class="sm:col-span-2">
                        <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Product
                            Name</label>
                        <input type="text" name="name" value="{{ old('name', $product->name) }}" id="name"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Type product name" required="">
                    </div>
                    @error('name')
                        <p class="text-red-600 text-sm">{{ $message }}</p>
                    @enderror

                    <div class="w-full">
                        <label for="description"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Descrição</label>
                        <textarea name="description" id="description"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 p-1 h-20 block w-full dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Descrição" required="">{{ old('description', $product->description) }}</textarea>
                    </div>
                    @error('decription')
                        <p class="text-red-600 text-sm">{{ $message }}</p>
                    @enderror
                    <div class="w-full">
                        <label for="price"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Value</label>
                        <input type="number" name="value" value="{{ old('value', $product->value) }}" id="value"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="$2999" required="">
                    </div>
                    @error('value')
                        <p class="text-red-600 text-sm">{{ $message }}</p>
                    @enderror
                    <div>
                        <label for="category"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Category</label>
                        <select id="category"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <option selected="">Select category</option>
                            <option value="TV">TV/Monitors</option>
                            <option value="PC">PC</option>
                            <option value="GA">Gaming/Console</option>
                            <option value="PH">Phones</option>
                        </select>
                    </div>
                    <div>

                        <label for="imagem">Imagem do Produto:</label>
                        <input type="file" id="image" name="image" value="{{ asset('storage/' . $product->path) }}"
                            class="border-2 rounded-lg p-2 bg-gray-400">
                        <input type="text" name="path" id="path" value="{{ asset('storage/' . $product->path) }}"
                            hidden>
                        <a href="{{ asset('storage/' . $product->path) }}" download="imagem.jpg"
                            class="p-2 bg-blue-600 text-white rounded-lg inline-block">
                            Baixar Imagem
                        </a>
                    </div>
                    <button type="submit"
                        class="inline-flex items-center px-5 py-2.5 mt-4 sm:mt-6 text-sm font-medium text-center text-white bg-blue-700 rounded-lg focus:ring-4 focus:ring-blue-200 dark:focus:ring-blue-900 hover:bg-blue-800">
                        Add product
                    </button>
            </form>
        </div>
    </section>
@endsection
