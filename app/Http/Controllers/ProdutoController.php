<?php

namespace App\Http\Controllers;

use App\Models\Atributo;
use App\Models\ProductClick;
use App\Models\Products;
use App\Models\User;
use Illuminate\Http\Request;

class ProdutoController extends Controller
{
    public function index()
    {
        $user = app(User::class);
        $products = Products::with('atributos')->get();

        return view('products.index', compact('user', 'products'));
    }

    public function show($slug, $id)
    {
        $product = Products::with('atributos')->findOrFail($id);

        ProductClick::recordProductView($product);

        return view('products.show', compact('product'));
    }

    public function search(request $request)
    {
        $request->validate([
            'search' => ['required', 'max:255', 'min:3'],
        ],
            [
                'required' => 'Esse campo é requirido',
                'max' => 'Tamanho maximo de caraceteres excedido',
            ]
        );

        $produto = Products::where('name', 'LIKE', "%{$request->search}%")->get();

        return view('admin.products', ['products' => $produto, 'link' => false]);
    }

    public function create($slug)
    {
        $atributos = Atributo::all();

        return view('admin.create_product', compact('atributos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3|max:255',
            'value' => 'required',
            'description' => 'required|min:3|max:255',
            'image' => 'image|mimes:jpeg,png,jpg,webp|max:10240',
        ], [
            'image.image' => 'Arquivo nao suportado',
            'image.mimes' => 'Tipo de imagem nao suportado',
            'image.max' => 'Tamanho da imagem excedido (10MB)',
        ]);

        $path = null;

        //  Verifica se o arquivo de imagem foi enviado
        if ($request->hasFile('image') && $request->file('image')->isValid()) {

            // Armazena a imagem no disco 'public' e obtém o caminho
            // O Laravel gera um nome de arquivo único automaticamente
            $path = $request->file('image')->store('path', 'public');
        } else {
            echo 'Nao enviou a imagem';
        }

        // Cria o produto no banco de dados
        $product = Products::create([
            'name' => $request->name,
            'value' => $request->value,
            'description' => $request->description,
            'path' => $path,
            'user_id' => app(User::class)->id,
        ]);

        // Associa os atributos selecionados (opcional)
        $product->atributos()->sync($request->input('atributos', []));

        return redirect()->route('products.index', ['slug' => app(User::class)->slug]);
    }

    public function edit($slug, $id)
    {
        $product = Products::with('atributos')->findOrFail($id);
        $atributos = Atributo::all();
        $atributosVinculados = $product->atributos->pluck('id')->toArray();

        return view('admin.edit_product', compact('product', 'atributos', 'atributosVinculados'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3|max:255',
            'value' => 'required',
            'description' => 'required|min:3|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:16384',
        ]);
        //  verifica se o arquivo de imagem foi enviado
        if ($request->hasFile('image') && $request->file('image')->isValid()) {

            // armazena a imagem em public/path
            $path = $request->file('image')->store('path', 'public');
        }

        $product = Products::findOrFail($request->product);

        // se o vendedor nao enviou a imagem, nao faz o update do path
        if (! empty($path)) {
            $product->update([
                'name' => $request->name,
                'value' => $request->value,
                'description' => $request->description,
                'path' => $path,
            ]);
        } else {
            $product->update([
                'name' => $request->name,
                'value' => $request->value,
                'description' => $request->description,
            ]);
        }

        // Atualiza os atributos associados
        $product->atributos()->sync($request->input('atributos', []));

        return redirect()->route('admin.products', ['slug' => $request->slug]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $product = Products::findOrFail($request->product);

        $product->delete();

        return redirect()->route('admin.products', ['slug' => $request->slug]);

    }
}
