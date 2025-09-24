<?php

namespace App\Http\Controllers;

use App\Models\Products;
use App\Models\User;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;

class ProdutoController extends Controller
{
    public function index()
    {
        $user = app(User::class);
        $products = Products::all();
        return view('products.index', compact('user', 'products'));
    }

    public function show($slug, $id)
    {

        $product = Products::findOrFail($id);

        return view('products.show', compact('product'));
    }

    public function create($slug)
    {
        // $categorias = Categoria::all()
        return view('admin.create_product');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3|max:255',
            'value' => 'required',
            'description' => 'required|min:3|max:255',
            'path' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:16384'
        ]);

        $path = null;

        //  Verifica se o arquivo de imagem foi enviado
        if ($request->hasFile('image') && $request->file('image')->isValid()) {

            // Armazena a imagem no disco 'public' e obtém o caminho
            // O Laravel gera um nome de arquivo único automaticamente
            $path = $request->file('image')->store('path', 'public');
        } else {
            echo "Nao enviou a imagem";
        }

        //Cria o produto no banco de dados
        Products::create([
            'name' => $request->name,
            'value' => $request->value,
            'description' => $request->description,
            'path' => $path,
            'user_id' => app(User::class)->id
        ]);

        return redirect()->route('products.index', ['slug' => app(User::class)->slug]);
    }

    public function edit($slug, $id)
    {
        $product = Products::findOrFail($id);
        return view('admin.edit_product', compact('product'));
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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:16384'
        ]);
        //  verifica se o arquivo de imagem foi enviado
        if ($request->hasFile('image') && $request->file('image')->isValid()) {

            // armazena a imagem em public/path
            $path = $request->file('image')->store('path', 'public');
        }

        $product = Products::findOrFail($request->id);

        //se o vendedor nao enviou a imagem, nao faz o update do path
        if (!empty($path)) {
            $product->update([
                'name' => $request->name,
                'value' => $request->value,
                'description' => $request->description,
                'path' => $path
            ]);
        } else {
            $product->update([
                'name' => $request->name,
                'value' => $request->value,
                'description' => $request->description
            ]);
        }
        return redirect()->route('admin.products', ['slug' => $request->slug]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $product = Products::findOrFail($request->id);

        $product->delete();

        return redirect()->route('admin.products', ['slug' => $request->slug]);
        
    }
}
