<?php

namespace App\Http\Controllers;

use App\Models\Atributo;
use App\Models\User;
use Illuminate\Http\Request;

class AtributoController extends Controller
{
    public function index()
    {
        $atributos = Atributo::all();

        return view('admin.atributos', compact('atributos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|min:1|max:100',
        ], [
            'nome.required' => 'O nome do atributo é obrigatório.',
            'nome.max' => 'Máximo de 100 caracteres.',
        ]);

        Atributo::create([
            'user_id' => app(User::class)->id,
            'nome' => $request->nome,
        ]);

        return redirect()->route('atributos.index')->with('success', 'Atributo criado com sucesso!');
    }

    public function destroy($slug, $id)
    {

        $atributo = Atributo::where('id', $id)->first();

        if ($atributo) {
            $atributo->delete();

            return redirect()->route('atributos.index')->with('success', 'Atributo deletado com sucesso!');
        } else {
            return redirect()->route('atributos.index')->with('error', 'Atributo não encontrado!');
        }
    }
}
