<?php

namespace App\Http\Controllers;

use App\Models\Grupo;
use App\Models\ProductClick;
use App\Models\ProductImage;
use App\Models\Products;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProdutoController extends Controller
{
    public function index(Request $request)
    {
        $user = app(User::class);
        $selectedGrupo = $request->query('grupo');
        $search = trim((string) $request->query('search', ''));

        $query = Products::with(['atributos', 'productImages', 'grupos'])->where('status', true);

        if ($user->isErp()) {
            $query->orderBy('sku');
        }

        if ($selectedGrupo) {
            $query->whereHas('grupos', function ($q) use ($selectedGrupo) {
                $q->where('grupos.id', $selectedGrupo);
            });
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('nome', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%")
                  ->orWhere('sku', 'LIKE', "%{$search}%");
            });
        }

        $products = $query->paginate(25)->withQueryString();
        $grupos = Grupo::all();

        return view('products.index', compact('user', 'products', 'grupos', 'selectedGrupo', 'search'));
    }

    public function show($slug, $id)
    {
        $user = app(User::class);
        $query = Products::with(['atributos', 'productImages', 'grupos']);

        if (!auth()->check() || (int) auth()->id() !== (int) $user->id) {
            $query->where('status', true);
        }

        $product = $query->findOrFail($id);

        ProductClick::recordProductView($product);

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
        $grupos = Grupo::all();

        return view('admin.create_product', compact('grupos'));
    }

    public function store(Request $request)
    {
        $request->validate(
            $this->productValidationRules(requireImages: true),
            $this->productValidationMessages()
        );

        $parsedPrice = Products::parseMonetaryValue($request->value);
        $storedPaths = [];

        try {
            DB::transaction(function () use ($request, $parsedPrice, &$storedPaths) {
                // Salva o produto; path = primeira imagem (capa)
                $product = Products::create([
                    'nome'        => $request->name,
                    'slug'        => Str::slug($request->name),
                    'preco_base'  => $parsedPrice,
                    'description' => $request->description,
                    'status'      => true,
                    'path'        => null,
                    'user_id'     => app(User::class)->id,
                ]);

                $files = $request->file('images');
                foreach ($files as $ordem => $file) {
                    $path = $file->store('path', 'public');
                    $storedPaths[] = $path;
                    ProductImage::create([
                        'product_id' => $product->id,
                        'path'       => $path,
                        'ordem'      => $ordem,
                    ]);
                }

                // Primeira imagem = capa
                $product->syncCover();

                // Associa grupos
                $product->grupos()->sync($request->input('grupos', []));
            });
        } catch (\Throwable $e) {
            foreach ($storedPaths as $path) {
                Storage::disk('public')->delete($path);
            }
            throw $e;
        }

        return redirect()->route('dashboard');
    }

    public function edit($slug, $id)
    {
        $product = Products::with(['productImages', 'grupos'])->findOrFail($id);
        $grupos = Grupo::all();
        $gruposVinculados = $product->grupos->pluck('id')->toArray();

        return view('admin.edit_product', compact('product', 'grupos', 'gruposVinculados'));
    }

    public function update($slug, Request $request)
    {
        $request->validate(
            $this->productValidationRules(requireImages: false),
            $this->productValidationMessages()
        );

        $product = Products::findOrFail($request->product);
        $parsedPrice = Products::parseMonetaryValue($request->value);

        DB::transaction(function () use ($request, $product, $parsedPrice) {
            $product->update([
                'nome'        => $request->name,
                'slug'        => $product->slug ?: Str::slug($request->name),
                'preco_base'  => $parsedPrice,
                'description' => $request->description,
            ]);

            // Adiciona novas imagens (ordem continua de onde parou)
            if ($request->hasFile('images')) {
                $proximaOrdem = $product->nextImageOrder();

                foreach ($request->file('images') as $i => $file) {
                    $path = $file->store('path', 'public');
                    ProductImage::create([
                        'product_id' => $product->id,
                        'path'       => $path,
                        'ordem'      => $proximaOrdem + $i,
                    ]);
                }

                $product->syncCover();
            }

            $product->grupos()->sync($request->input('grupos', []));
        });

        return redirect()->route('admin.products', ['slug' => auth()->user()->slug])
            ->with('success', 'Produto atualizado com sucesso!');
    }

    /** Remove uma imagem individual do produto */
    public function destroyImage(Request $request)
    {
        $imageId = $request->route('image') ?? $request->image;
        $image = ProductImage::whereHas('product')->findOrFail($imageId);
        $product = $image->product;
        Storage::disk('public')->delete($image->path);
        $image->delete();

        // Sincroniza capa após remoção
        $product->syncCover();

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

        $proximaOrdem = $product->nextImageOrder();

        $image = ProductImage::create([
            'product_id' => $product->id,
            'path'       => $path,
            'ordem'      => $proximaOrdem,
        ]);

        $product->syncCover();

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

        $product->syncCover();

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

    /** Alterna o status do produto entre Ativo (true) e Inativo (false) */
    public function toggleStatus($slug, $id)
    {
        $product = Products::findOrFail($id);
        $product->update([
            'status' => !$product->status,
        ]);

        $label = $product->status ? 'ativado' : 'inativado';

        return redirect()->back()->with('success', "Produto \"{$product->nome}\" {$label} com sucesso!");
    }

    /** Regras de validação compartilhadas entre criação e atualização de produto */
    private function productValidationRules(bool $requireImages): array
    {
        $tenantId = app()->bound(User::class) ? app(User::class)->id : auth()->id();

        return [
            'name'        => 'required|string|min:3|max:255',
            'value'       => ['required', function (string $attribute, mixed $value, \Closure $fail) {
                $parsed = Products::parseMonetaryValue($value);
                if ($parsed === null || $parsed <= 0) {
                    $fail('Informe um valor válido maior que zero.');
                } elseif ($parsed > 99999999.99) {
                    $fail('O valor informado excede o limite máximo permitido.');
                }
            }],
            'description' => 'required|string|min:3|max:255',
            'images'      => $requireImages ? 'required|array|min:1' : 'nullable|array',
            'images.*'    => 'image|mimes:jpeg,png,jpg,webp|max:10240',
            'grupos'      => 'nullable|array',
            'grupos.*'    => ['integer', Rule::exists('grupos', 'id')->where('user_id', $tenantId)],
        ];
    }

    /** Mensagens customizadas de validação de produto */
    private function productValidationMessages(): array
    {
        return [
            'description.max'  => 'A descrição deve ter no máximo 255 caracteres.',
            'images.required'  => 'Envie ao menos uma imagem.',
            'images.*.image'   => 'Arquivo não suportado.',
            'images.*.mimes'   => 'Tipo de imagem não suportado.',
            'images.*.max'     => 'Tamanho da imagem excedido (10 MB).',
        ];
    }
}
