<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
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

    public function syncProducts(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'message' => 'User not found',
            ], 404);
        }

        if ($user->client_type != 'erp') {
            return response()->json([
                'message' => 'User is not ERP',
            ], 400);
        }
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
