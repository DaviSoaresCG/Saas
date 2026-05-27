<?php

namespace App\Http\Controllers;

use App\Models\Atributo;
use App\Models\ProductClick;
use App\Models\ProductImage;
use App\Models\Products;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProdutoController extends Controller
{
    public function index()
    {
        $user = app(User::class);
        $products = Products::with(['atributos', 'productImages'])->get();

        return view('products.index', compact('user', 'products'));
    }

    public function show($slug, $id)
    {
        $product = Products::with(['atributos', 'productImages'])->findOrFail($id);

        ProductClick::recordProductView($product);

        return view('products.show', compact('product'));
    }

    public function search(Request $request)
    {
        $request->validate(
            ['search' => ['required', 'max:255']],
            ['required' => 'Esse campo é requirido', 'max' => 'Tamanho maximo de caracteres excedido']
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
            'name'        => 'required|min:3|max:255',
            'value'       => 'required',
            'description' => 'required|min:3|max:255',
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
            'name'        => $request->name,
            'value'       => $request->value,
            'description' => $request->description,
            'path'        => null,
            'user_id'     => app(User::class)->id,
        ]);

        // Salva todas as imagens
        foreach ($request->file('images') as $ordem => $file) {
            $path = $file->store('path', 'public');
            ProductImage::create([
                'product_id' => $product->id,
                'path'       => $path,
                'ordem'      => $ordem,
            ]);

            // Primeira imagem = capa (para retrocompatibilidade)
            if ($ordem === 0) {
                $product->update(['path' => $path]);
            }
        }

        // Associa atributos
        $product->atributos()->sync($request->input('atributos', []));

        return redirect()->route('products.index', ['slug' => app(User::class)->slug]);
    }

    public function edit($slug, $id)
    {
        $product = Products::with(['atributos', 'productImages'])->findOrFail($id);
        $atributos = Atributo::all();
        $atributosVinculados = $product->atributos->pluck('id')->toArray();

        return view('admin.edit_product', compact('product', 'atributos', 'atributosVinculados'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name'        => 'required|min:3|max:255',
            'value'       => 'required',
            'description' => 'required|min:3|max:255',
            'images'      => 'nullable|array',
            'images.*'    => 'image|mimes:jpeg,png,jpg,webp|max:10240',
        ]);

        $product = Products::findOrFail($request->product);

        $product->update([
            'name'        => $request->name,
            'value'       => $request->value,
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

            // Atualiza capa com a primeira imagem existente
            $this->syncCover($product);
        }

        $product->atributos()->sync($request->input('atributos', []));

        return redirect()->route('admin.products', ['slug' => $request->slug]);
    }

    /** Remove uma imagem individual do produto */
    public function destroyImage(Request $request, ProductImage $image)
    {
        $product = $image->product;
        Storage::disk('public')->delete($image->path);
        $image->delete();

        // Sincroniza capa após remoção
        $this->syncCover($product);

        return redirect()->back()->with('success', 'Imagem removida.');
    }

    /** Reordena as imagens via AJAX drag-and-drop */
    public function reorderImages(Request $request, Products $product)
    {
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
