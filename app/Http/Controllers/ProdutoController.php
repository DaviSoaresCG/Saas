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

    public function create()
    {
        // $categorias = Categoria::all()
        return view('products.create');
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

        // 2. Verifica se o arquivo de imagem foi enviado
        if ($request->hasFile('image') && $request->file('image')->isValid()) {

            // 3. Armazena a imagem no disco 'public' e obtém o caminho
            // O Laravel gera um nome de arquivo único automaticamente
            $path = $request->file('image')->store('path', 'public');
        }

        // 4. Cria o produto no banco de dados
        Products::create([
            'name' => $request->name,
            'value' => $request->value,
            'description' => $request->description, // Exemplo
            'path' => $path, // Salva o caminho no banco
            'user_id' => app(User::class)->id
        ]);

        return redirect()->route('products.index', ['slug' => app(User::class)->slug]);
    }
}
