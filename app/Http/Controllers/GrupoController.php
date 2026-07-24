<?php

namespace App\Http\Controllers;

use App\Models\Grupo;
use App\Models\Products;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GrupoController extends Controller
{
    public function index()
    {
        $grupos = Grupo::withCount('products')->with('products:id')->get();
        $allProducts = Products::select('id', 'nome', 'foto_url')->get();

        return view('admin.grupos', compact('grupos', 'allProducts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|min:1|max:100',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ], [
            'nome.required' => 'O nome do grupo é obrigatório.',
            'nome.max'      => 'Máximo de 100 caracteres.',
            'foto.image'    => 'O arquivo enviado deve ser uma imagem.',
            'foto.mimes'    => 'Formatos aceitos: JPG, PNG, WebP.',
            'foto.max'      => 'A imagem não pode exceder 5 MB.',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('grupos', 'public');
        }

        Grupo::create([
            'user_id'   => app(User::class)->id,
            'nome'      => $request->nome,
            'foto_path' => $fotoPath,
        ]);

        return redirect()->route('grupos.index')->with('success', 'Grupo criado com sucesso!');
    }

    public function update(Request $request, $slug, $id)
    {
        $grupo = Grupo::findOrFail($id);

        $request->validate([
            'nome' => 'required|string|min:1|max:100',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ], [
            'nome.required' => 'O nome do grupo é obrigatório.',
            'nome.max'      => 'Máximo de 100 caracteres.',
            'foto.image'    => 'O arquivo enviado deve ser uma imagem.',
            'foto.mimes'    => 'Formatos aceitos: JPG, PNG, WebP.',
            'foto.max'      => 'A imagem não pode exceder 5 MB.',
        ]);

        $fotoPath = $grupo->foto_path;

        if ($request->hasFile('foto')) {
            if ($grupo->foto_path) {
                Storage::disk('public')->delete($grupo->foto_path);
            }
            $fotoPath = $request->file('foto')->store('grupos', 'public');
        } elseif ($request->boolean('remover_foto')) {
            if ($grupo->foto_path) {
                Storage::disk('public')->delete($grupo->foto_path);
            }
            $fotoPath = null;
        }

        $grupo->update([
            'nome'      => $request->nome,
            'foto_path' => $fotoPath,
        ]);

        return redirect()->route('grupos.index')->with('success', 'Grupo atualizado com sucesso!');
    }

    public function syncProducts(Request $request, $slug, $id)
    {
        $grupo = Grupo::findOrFail($id);
        $productIds = $request->input('products', []);

        $grupo->products()->sync($productIds);

        return redirect()->route('grupos.index')->with('success', "Produtos do grupo \"{$grupo->nome}\" atualizados com sucesso!");
    }

    public function destroy($slug, $id)
    {
        $grupo = Grupo::findOrFail($id);

        if ($grupo->foto_path) {
            Storage::disk('public')->delete($grupo->foto_path);
        }

        $grupo->delete();

        return redirect()->route('grupos.index')->with('success', 'Grupo excluído com sucesso!');
    }
}
