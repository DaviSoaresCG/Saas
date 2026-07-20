<?php

namespace App\Http\Controllers;

use App\Models\Catalogo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CatalogoController extends Controller
{
    /**
     * Display a listing of the catalogs.
     */
    public function index($slug = null)
    {
        $catalogos = Auth::user()->catalogos()->latest()->get();
        return view('admin.catalogos.index', compact('catalogos'));
    }

    /**
     * Store a newly created catalog.
     */
    public function store(Request $request, $slug = null)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'desconto_index' => 'required|numeric|min:0|max:100',
        ], [
            'nome.required' => 'O nome do catálogo é obrigatório.',
            'desconto_index.required' => 'O valor do desconto é obrigatório.',
            'desconto_index.numeric' => 'O desconto deve ser um número.',
            'desconto_index.min' => 'O desconto não pode ser menor que 0%.',
            'desconto_index.max' => 'O desconto não pode ser maior que 100%.',
        ]);

        // Generate a unique 12-char hash
        do {
            $hash = Str::random(12);
        } while (Catalogo::where('hash', $hash)->exists());

        Auth::user()->catalogos()->create([
            'nome' => $request->nome,
            'hash' => $hash,
            'desconto_index' => $request->desconto_index,
        ]);

        return redirect()->route('catalogos.index', ['slug' => Auth::user()->slug])
            ->with('success', 'Catálogo criado com sucesso!');
    }

    /**
     * Update the specified catalog.
     */
    public function update(Request $request, $slug = null, $id = null)
    {
        if ($id === null) {
            $id = $slug;
        }

        $catalogo = Auth::user()->catalogos()->findOrFail($id);

        $request->validate([
            'nome' => 'required|string|max:255',
            'desconto_index' => 'required|numeric|min:0|max:100',
        ], [
            'nome.required' => 'O nome do catálogo é obrigatório.',
            'desconto_index.required' => 'O valor do desconto é obrigatório.',
            'desconto_index.numeric' => 'O desconto deve ser um número.',
            'desconto_index.min' => 'O desconto não pode ser menor que 0%.',
            'desconto_index.max' => 'O desconto não pode ser maior que 100%.',
        ]);

        $catalogo->update([
            'nome' => $request->nome,
            'desconto_index' => $request->desconto_index,
        ]);

        return redirect()->route('catalogos.index', ['slug' => Auth::user()->slug])
            ->with('success', 'Catálogo atualizado com sucesso!');
    }

    /**
     * Remove the specified catalog.
     */
    public function destroy(Request $request, $slug = null, $id = null)
    {
        if ($id === null) {
            $id = $slug;
        }

        $catalogo = Auth::user()->catalogos()->findOrFail($id);
        $catalogo->delete();

        return redirect()->route('catalogos.index', ['slug' => Auth::user()->slug])
            ->with('success', 'Catálogo removido com sucesso!');
    }
}
