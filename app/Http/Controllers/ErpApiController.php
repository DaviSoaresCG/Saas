<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Products;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class ErpApiController extends Controller
{
    /**
     * Webhook for ERP tenant onboarding.
     */
    public function onboarding(Request $request)
    {
        // 1. Verify Master Key
        $masterKey = env('ERP_MASTER_KEY', 'ERP_MASTER_KEY_DEFAULT_123');
        $providedKey = $request->header('X-ERP-Key') ?: $request->bearerToken();

        if (!$providedKey || $providedKey !== $masterKey) {
            return response()->json(['error' => 'Unauthorized: Invalid Master Key'], 401);
        }

        // 2. Validate payload
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'documento' => 'required|string|max:255',
            'nome_loja' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:users,slug',
            'whatsapp' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Validation error', 'messages' => $validator->errors()], 422);
        }

        // 3. Create Tenant User (tipo_cliente = erp, status = active, initial password = documento)
        $apiToken = Str::random(60);
        $cleanWhatsapp = $request->whatsapp ? preg_replace('/\D/', '', $request->whatsapp) : null;

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'documento' => $request->documento,
            'nome_loja' => $request->nome_loja,
            'slug' => Str::slug($request->slug),
            'whatsapp' => $cleanWhatsapp,
            'tipo_cliente' => 'erp',
            'status' => 'active',
            'api_token' => $apiToken,
            'need_change_password' => true,
            'password' => Hash::make($request->documento),
        ]);

        return response()->json([
            'success' => true,
            'api_token' => $apiToken,
            'store_url' => 'http://' . $user->slug . '.' . env('APP_DOMAIN'),
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'nome_loja' => $user->nome_loja,
                'slug' => $user->slug,
            ]
        ], 201);
    }

    /**
     * Webhook for ERP product synchronization (upsert).
     */
    public function syncProducts(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'products' => 'required|array|min:1',
            'products.*.erp_id' => 'required|string|max:255',
            'products.*.sku' => 'nullable|string|max:255',
            'products.*.nome' => 'required|string|max:255',
            'products.*.preco_base' => 'required|numeric|min:0',
            'products.*.estoque' => 'nullable|integer|min:0',
            'products.*.foto_url' => 'nullable|string|max:2048',
            'products.*.description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Validation error', 'messages' => $validator->errors()], 422);
        }

        $syncedIds = [];
        $tenantId = Auth::id();

        foreach ($request->input('products') as $pData) {
            // Find or create product
            $product = Products::updateOrCreate(
                [
                    'user_id' => $tenantId,
                    'erp_id' => $pData['erp_id']
                ],
                [
                    'sku' => $pData['sku'] ?? null,
                    'nome' => $pData['nome'],
                    'preco_base' => $pData['preco_base'],
                    'estoque' => $pData['estoque'] ?? null,
                    'foto_url' => $pData['foto_url'] ?? null,
                    'description' => $pData['description'] ?? '',
                    'slug' => Str::slug($pData['nome']),
                ]
            );

            // Sync the main image to product_images for carousel compatibility
            if (!empty($pData['foto_url'])) {
                ProductImage::updateOrCreate(
                    [
                        'product_id' => $product->id,
                        'ordem' => 0
                    ],
                    [
                        'path' => $pData['foto_url']
                    ]
                );
            }

            $syncedIds[] = $product->id;
        }

        return response()->json([
            'success' => true,
            'message' => count($syncedIds) . ' products synchronized successfully.',
            'synced_ids' => $syncedIds
        ]);
    }

    /**
     * Webhook for ERP product deletion.
     */
    public function deleteProduct($erp_id)
    {
        $product = Products::where('user_id', Auth::id())
            ->where('erp_id', $erp_id)
            ->first();

        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        // Delete associated product images from storage if they are local paths (optional, here they are mostly URLs)
        // Delete model
        $product->delete();

        return response()->json([
            'success' => true,
            'message' => 'Product deleted successfully.'
        ]);
    }
}
