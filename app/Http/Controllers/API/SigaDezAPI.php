<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Jobs\ProcessImageBase64;
use App\Models\Grupo;
use App\Models\Pedido;
use App\Models\Products;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SigaDezAPI extends Controller
{

    public function onboarding(Request $request)
    {

        $request->validate([
            'data' => 'required|array',
            'data.id' => 'required',
            'data.name' => 'required|string',
            'data.document' => 'required|string',
            'data.email' => 'required|email',
            'data.store_name' => 'required|string',
        ]);

        $data = $request->data;
        $document = preg_replace('/\D/', '', $data['document']);
        $password = $document;
        $user = User::where('document', $document)->orWhere('email', $data['email'])->first();

        if ($user) {
            return response()->json([
                'message' => 'Usuário já cadastrado',
            ], 409);
        }
        $slug = $this->generateUniqueSlug($data['store_name']);


        $user = User::create([
            'name' => $data['name'],
            'erp_id' => $data['id'],
            'documento' => $document,
            'email' => $data['email'],
            'password' => Hash::make($password),
            'tipo_cliente' => 'erp',
            'slug' => $slug,
            'nome_loja' => $data['store_name'],
            'status' => 'active',
            'need_change_password' => true,
            'email_verified_at' => now(),

        ]);

        if (!$user) {
            return response()->json([
                'message' => 'Erro ao cadastrar',
            ], 404);
        }

        $token = $user->createToken('api-token')->plainTextToken;
        $user->api_token = $token;
        $user->save();

        return response()->json([
            'message' => 'Usuário cadastrado com sucesso',
            'token' => $token,
        ], 201);
    }

    public function syncOrders(Request $request)
    {
        $user = $request->user();
        app()->instance(User::class, $user);

        return DB::transaction(function () {
            $pedidos = Pedido::with('iten_pedido.product')
                ->where('sync', false)
                ->get();

            if ($pedidos->isNotEmpty()) {
                Pedido::whereIn('id', $pedidos->pluck('id'))->update(['sync' => true]);
            }

            return response()->json([
                'pedidos' => $pedidos
            ], 200);
        });
    }

    public function syncProducts(Request $request)
    {
        $validated = $request->validate([
            'products' => 'required|array|max:100',
            'products.*.id' => 'required|integer',
            'products.*.sku' => 'required|string',
            'products.*.status' => 'required|boolean',
            'products.*.peso' => 'required|numeric',
            'products.*.name' => 'required|string',
            'products.*.description' => 'required|string',
            'products.*.price' => 'required|numeric',
            'products.*.image_base64' => 'nullable',
            'products.*.group' => 'required|array',
            'products.*.group.id' => 'required|integer',
            'products.*.group.name' => 'required|string',
        ]);
        
        $user = $request->user();
        if(!$user)
        {
            return response()->json([
                'message' => "Usuário não identificado",
                'error' => true
            ], 401);
        }
        
        
        app()->instance(User::class, $user);

        $processados = 0;

        foreach($validated['products'] as $product){
            $produto = Products::updateOrCreate(
                [
                    'erp_id' => $product['id'],
                    'user_id' => $user->id,
                ],
                [
                    'nome' => $product['name'],
                    'sku' => $product['sku'],
                    'peso' => $product['peso'],
                    'status' => $product['status'],
                    'description' => $product['description'],
                    'preco_base' => $product['price'],
                ]
            );

            if(!empty($product['image_base64'])){
                ProcessImageBase64::dispatch($produto->id, $user->id, $product['image_base64']);
            }
            $processados++;

            $grupo = Grupo::updateOrCreate(
                ['erp_id' => $product['group']['id'], 'user_id' => $user->id],
                [
                    'nome' => $product['group']['name'],
                ]
            );

            $produto->grupos()->syncWithoutDetaching($grupo->id);
            
        }

        
        return response()->json([
            'message' => "{$processados} produtos processados com sucesso. Imagens sendo processadas em segundo plano.",
        ], 202);

    }

    public function generateUniqueSlug($slug)
    {
        $count = User::where('slug', 'LIKE', "{$slug}%")->count();

        // se count retornar um numero, $slug-n
        return $count ? "{$slug}-{$count}" : $slug;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
