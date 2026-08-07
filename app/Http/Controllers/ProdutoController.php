<?php

namespace App\Http\Controllers;

use App\Models\Atributo;
use App\Models\Grupo;
use App\Models\ProductClick;
use App\Models\ProductImage;
use App\Models\Products;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProdutoController extends Controller
{
    public function index(Request $request)
    {
        $user = app(User::class);
        $selectedGrupo = $request->query('grupo');

        $query = Products::with(['atributos', 'productImages', 'grupos'])->where('status', true);

        if ($selectedGrupo) {
            $query->whereHas('grupos', function ($q) use ($selectedGrupo) {
                $q->where('grupos.id', $selectedGrupo);
            });
        }

        $products = $query->get();
        $grupos = Grupo::all();

        return view('products.index', compact('user', 'products', 'grupos', 'selectedGrupo'));
    }

    public function show($slug, $id)
    {
        $product = Products::with(['atributos', 'productImages', 'grupos'])->findOrFail($id);

        ProductClick::recordProductView($product);
        $user = app(User::class);


        return view('products.show', compact('product', 'user'));
    }

    public function search(Request $request)
    {
        $request->validate(
            ['search' => ['required', 'max:255']],
            ['required' => 'Esse campo é requirido', 'max' => 'Tamanho maximo de caracteres excedido']
        );

        $produto = Products::where('nome', 'LIKE', "%{$request->search}%")->get();

        return view('admin.products', ['products' => $produto, 'link' => false]);
    }

    public function create($slug)
    {
        $atributos = Atributo::all();
        $grupos = Grupo::all();

        return view('admin.create_product', compact('atributos', 'grupos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|min:3|max:255',
            'value'       => 'required',
            'description' => 'required|min:3',
            'images'      => 'required|array|min:1',
            'images.*'    => 'image|mimes:jpeg,png,jpg,webp|max:10240',
        ], [
            'images.required'  => 'Envie ao menos uma imagem.',
            'images.*.image'   => 'Arquivo não suportado.',
            'images.*.mimes'   => 'Tipo de imagem não suportado.',
            'images.*.max'     => 'Tamanho da imagem excedido (10 MB).',
        ]);

        // Salva o produto; path = primeira imagem (capa)
        $product = Products::create([
            'nome'        => $request->name,
            'preco_base'       => $request->value,
            'description' => $request->description,
            'path'        => null,
            'user_id'     => app(User::class)->id,
        ]);

        $files = $request->file('images');
        foreach ($files as $ordem => $file) {
            $path = $file->store('path', 'public');
            ProductImage::create([
                'product_id' => $product->id,
                'path'       => $path,
                'ordem'      => $ordem,
            ]);
        }

        // Primeira imagem = capa
        $this->syncCover($product);

        // Associa atributos e grupos
        $product->atributos()->sync($request->input('atributos', []));
        $product->grupos()->sync($request->input('grupos', []));

        return redirect()->route('dashboard');
    }

    public function edit($slug, $id)
    {
        $product = Products::with(['atributos', 'productImages', 'grupos'])->findOrFail($id);
        $atributos = Atributo::all();
        $atributosVinculados = $product->atributos->pluck('id')->toArray();
        $grupos = Grupo::all();
        $gruposVinculados = $product->grupos->pluck('id')->toArray();

        return view('admin.edit_product', compact('product', 'atributos', 'atributosVinculados', 'grupos', 'gruposVinculados'));
    }

    public function update($slug, Request $request)
    {
        $request->validate([
            'name'        => 'required|min:3|max:255',
            'value'       => 'required',
            'description' => 'required|min:3',
            'images'      => 'nullable|array',
            'images.*'    => 'image|mimes:jpeg,png,jpg,webp|max:10240',
        ]);

        $product = Products::findOrFail($request->product);

        $product->update([
            'nome'        => $request->name,
            'preco_base'       => $request->value,
            'description' => $request->description,
        ]);

        // Adiciona novas imagens (ordem continua de onde parou)
        if ($request->hasFile('images')) {
            $proximaOrdem = $product->productImages()->max('ordem') + 1;

            foreach ($request->file('images') as $i => $file) {
                $path = $file->store('path', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'path'       => $path,
                    'ordem'      => $proximaOrdem + $i,
                ]);
            }

            $this->syncCover($product);
        }

        $product->atributos()->sync($request->input('atributos', []));
        $product->grupos()->sync($request->input('grupos', []));

        return redirect()->route('admin.products', ['slug' => auth()->user()->slug])
            ->with('success', 'Produto atualizado com sucesso!');
    }

    /** Remove uma imagem individual do produto */
    public function destroyImage(Request $request)
    {
        $image = ProductImage::findOrFail($request->image);
        $product = $image->product;
        Storage::disk('public')->delete($image->path);
        $image->delete();

        // Sincroniza capa após remoção
        $this->syncCover($product);

        return redirect()->back()->with('success', 'Imagem removida.');
    }

    /** Upload instantâneo de imagem durante edição */
    public function uploadImage($slug, $id, Request $request)
    {
        $product = Products::findOrFail($id);

        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:10240',
        ]);

        $file = $request->file('image');
        $path = $file->store('path', 'public');

        $proximaOrdem = $product->productImages()->max('ordem') + 1;

        $image = ProductImage::create([
            'product_id' => $product->id,
            'path'       => $path,
            'ordem'      => $proximaOrdem,
        ]);

        $this->syncCover($product);

        return response()->json([
            'ok' => true,
            'id' => $image->id,
            'path' => asset('storage/' . $image->path),
        ]);
    }

    /** Reordena as imagens via AJAX drag-and-drop */
    public function reorderImages($slug, $id, Request $request)
    {
        $product = Products::findOrFail($id);

        $request->validate(['order' => 'required|array']);

        foreach ($request->order as $ordem => $imageId) {
            ProductImage::where('id', $imageId)
                ->where('product_id', $product->id) // segurança: só imagens do próprio produto
                ->update(['ordem' => $ordem]);
        }

        $this->syncCover($product);

        return response()->json(['ok' => true]);
    }

    public function destroy(Request $request)
    {
        $product = Products::findOrFail($request->product);

        // Deleta imagens do storage
        foreach ($product->productImages as $img) {
            Storage::disk('public')->delete($img->path);
        }

        $product->delete();

        return redirect()->route('admin.products', ['slug' => $request->slug]);
    }

    /** Mantém products.path sincronizado com a 1ª imagem */
    private function syncCover(Products $product): void
    {
        $first = $product->productImages()->orderBy('ordem')->first();
        $product->update(['path' => $first?->path]);
    }
}
