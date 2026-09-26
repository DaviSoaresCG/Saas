<?php

namespace App\Http\Controllers;

use App\Models\Atributo;
use App\Models\Catalogo;
use App\Models\Grupo;
use App\Models\ItemPedido;
use App\Models\PaymentTransaction;
use App\Models\Pedido;
use App\Models\ProductClick;
use App\Models\ProductImage;
use App\Models\Products;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SuperAdminController extends Controller
{
    /**
     * Super Admin Dashboard - Visão geral de todos os usuários e dados da plataforma.
     */
    public function index(Request $request): View
    {
        $search = trim($request->get('search', ''));
        $tipoCliente = $request->get('tipo_cliente', '');
        $status = $request->get('status', '');

        // Estatísticas Globais
        $stats = [
            'total_users' => User::count(),
            'total_direct' => User::where('tipo_cliente', 'direct')->count(),
            'total_erp' => User::where('tipo_cliente', 'erp')->count(),
            'total_products' => Products::withoutGlobalScopes()->count(),
            'total_pedidos' => Pedido::withoutGlobalScopes()->count(),
            'total_catalogos' => Catalogo::count(),
            'total_grupos' => Grupo::withoutGlobalScopes()->count(),
        ];

        // Consulta de Usuários com contadores
        $query = User::query()
            ->withCount([
                'products as products_count' => fn ($q) => $q->withoutGlobalScopes(),
                'pedidos as pedidos_count' => fn ($q) => $q->withoutGlobalScopes(),
                'grupos as grupos_count' => fn ($q) => $q->withoutGlobalScopes(),
                'catalogos as catalogos_count' => fn ($q) => $q->withoutGlobalScopes(),
                'atributos as atributos_count' => fn ($q) => $q->withoutGlobalScopes(),
            ]);

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%")
                    ->orWhere('nome_loja', 'LIKE', "%{$search}%")
                    ->orWhere('slug', 'LIKE', "%{$search}%")
                    ->orWhere('whatsapp', 'LIKE', "%{$search}%");
            });
        }

        if (!empty($tipoCliente)) {
            $query->where('tipo_cliente', $tipoCliente);
        }

        if (!empty($status)) {
            $query->where('status', $status);
        }

        $users = $query->latest('id')->paginate(12)->withQueryString();

        return view('superadmin.index', compact('stats', 'users', 'search', 'tipoCliente', 'status'));
    }

    /**
     * Tela de Detalhes e Gerenciamento de Dados de um Usuário específico.
     */
    public function showUser(User $user): View
    {
        // Contadores e métricas do lojista
        $productsCount = Products::withoutGlobalScopes()->where('user_id', $user->id)->count();
        $pedidosCount = Pedido::withoutGlobalScopes()->where('user_id', $user->id)->count();
        $gruposCount = Grupo::withoutGlobalScopes()->where('user_id', $user->id)->count();
        $catalogosCount = Catalogo::where('user_id', $user->id)->count();
        $atributosCount = Atributo::withoutGlobalScopes()->where('user_id', $user->id)->count();
        $clicksCount = (int) ProductClick::withoutGlobalScopes()->where('user_id', $user->id)->sum('clicks');

        // Total financeiro dos pedidos
        $pedidosRaw = Pedido::withoutGlobalScopes()->where('user_id', $user->id)->get(['total']);
        $totalRevenue = 0.0;
        foreach ($pedidosRaw as $p) {
            $rawVal = str_replace(['.', ','], ['', '.'], (string) $p->total);
            if (is_numeric($rawVal)) {
                $totalRevenue += (float) $rawVal;
            }
        }

        // Amostras recentes para conferência visual
        $recentProducts = Products::withoutGlobalScopes()
            ->where('user_id', $user->id)
            ->with('productImages')
            ->latest('id')
            ->limit(8)
            ->get();

        $recentPedidos = Pedido::withoutGlobalScopes()
            ->where('user_id', $user->id)
            ->with(['iten_pedido' => fn ($q) => $q->limit(3)])
            ->latest('id')
            ->limit(8)
            ->get();

        $grupos = Grupo::withoutGlobalScopes()
            ->where('user_id', $user->id)
            ->withCount('products')
            ->get();

        $catalogos = Catalogo::where('user_id', $user->id)->get();
        $atributos = Atributo::withoutGlobalScopes()->where('user_id', $user->id)->get();

        $counts = [
            'products' => $productsCount,
            'pedidos' => $pedidosCount,
            'grupos' => $gruposCount,
            'catalogos' => $catalogosCount,
            'atributos' => $atributosCount,
            'clicks' => $clicksCount,
            'total_revenue' => $totalRevenue,
        ];

        return view('superadmin.manage_user', compact(
            'user',
            'counts',
            'recentProducts',
            'recentPedidos',
            'grupos',
            'catalogos',
            'atributos'
        ));
    }

    /**
     * Excluir TODOS os Produtos de um certo usuário (incluindo imagens do storage).
     */
    public function deleteProducts(User $user): RedirectResponse
    {
        $deletedCount = 0;

        DB::transaction(function () use ($user, &$deletedCount) {
            $products = Products::withoutGlobalScopes()
                ->where('user_id', $user->id)
                ->with('productImages')
                ->get();

            $deletedCount = $products->count();

            foreach ($products as $product) {
                // Remove as imagens da galeria no disco
                foreach ($product->productImages as $image) {
                    if ($image->path && !str_starts_with($image->path, 'http')) {
                        Storage::disk('public')->delete($image->path);
                    }
                }

                // Remove imagem de capa no disco se local
                $rawCover = $product->getRawOriginal('path');
                if ($rawCover && !str_starts_with($rawCover, 'http')) {
                    Storage::disk('public')->delete($rawCover);
                }

                // Remove registros relacionados
                ProductImage::where('product_id', $product->id)->delete();
                ProductClick::withoutGlobalScopes()->where('product_id', $product->id)->delete();

                // Remove vínculos de tabelas pivô
                DB::table('atributo_product')->where('product_id', $product->id)->delete();
                DB::table('grupo_product')->where('product_id', $product->id)->delete();

                // Remove o produto
                $product->delete();
            }
        });

        return redirect()->route('superadmin.users.show', $user)
            ->with('success', "Todos os {$deletedCount} produto(s) de \"{$user->name}\" foram excluídos com sucesso juntamente com suas imagens!");
    }

    /**
     * Excluir TODOS os Pedidos de um certo usuário (e os itens de pedidos).
     */
    public function deletePedidos(User $user): RedirectResponse
    {
        $deletedCount = 0;

        DB::transaction(function () use ($user, &$deletedCount) {
            $pedidos = Pedido::withoutGlobalScopes()->where('user_id', $user->id)->get();
            $deletedCount = $pedidos->count();
            $pedidoIds = $pedidos->pluck('id');

            if ($pedidoIds->isNotEmpty()) {
                ItemPedido::whereIn('pedido_id', $pedidoIds)->delete();
            }

            Pedido::withoutGlobalScopes()->where('user_id', $user->id)->delete();
        });

        return redirect()->route('superadmin.users.show', $user)
            ->with('success', "Todos os {$deletedCount} pedido(s) de \"{$user->name}\" foram excluídos com sucesso!");
    }

    /**
     * Excluir TODOS os Grupos (Categorias) de um certo usuário.
     */
    public function deleteGrupos(User $user): RedirectResponse
    {
        $deletedCount = 0;

        DB::transaction(function () use ($user, &$deletedCount) {
            $grupos = Grupo::withoutGlobalScopes()->where('user_id', $user->id)->get();
            $deletedCount = $grupos->count();

            foreach ($grupos as $grupo) {
                if ($grupo->foto_path && !str_starts_with($grupo->foto_path, 'http')) {
                    Storage::disk('public')->delete($grupo->foto_path);
                }
                DB::table('grupo_product')->where('grupo_id', $grupo->id)->delete();
                $grupo->delete();
            }
        });

        return redirect()->route('superadmin.users.show', $user)
            ->with('success', "Todos os {$deletedCount} grupo(s)/categoria(s) de \"{$user->name}\" foram excluídos com sucesso!");
    }

    /**
     * Excluir TODOS os Catálogos Promocionais de um certo usuário.
     */
    public function deleteCatalogos(User $user): RedirectResponse
    {
        $deletedCount = Catalogo::where('user_id', $user->id)->delete();

        return redirect()->route('superadmin.users.show', $user)
            ->with('success', "Todos os {$deletedCount} catálogo(s) promocional(is) de \"{$user->name}\" foram excluídos com sucesso!");
    }

    /**
     * Excluir TODOS os Atributos customizados de um certo usuário.
     */
    public function deleteAtributos(User $user): RedirectResponse
    {
        $deletedCount = 0;

        DB::transaction(function () use ($user, &$deletedCount) {
            $atributos = Atributo::withoutGlobalScopes()->where('user_id', $user->id)->get();
            $deletedCount = $atributos->count();

            foreach ($atributos as $atributo) {
                DB::table('atributo_product')->where('atributo_id', $atributo->id)->delete();
                $atributo->delete();
            }
        });

        return redirect()->route('superadmin.users.show', $user)
            ->with('success', "Todos os {$deletedCount} atributo(s) de \"{$user->name}\" foram excluídos com sucesso!");
    }

    /**
     * ZERAR TUDO (Reset Completo da Loja):
     * Exclui Produtos (+ fotos), Pedidos (+ itens), Grupos (+ fotos), Catálogos, Atributos e Clicks.
     */
    public function wipeAll(User $user): RedirectResponse
    {
        DB::transaction(function () use ($user) {
            // 1. Excluir Produtos e Imagens
            $products = Products::withoutGlobalScopes()
                ->where('user_id', $user->id)
                ->with('productImages')
                ->get();

            foreach ($products as $product) {
                foreach ($product->productImages as $image) {
                    if ($image->path && !str_starts_with($image->path, 'http')) {
                        Storage::disk('public')->delete($image->path);
                    }
                }
                $rawCover = $product->getRawOriginal('path');
                if ($rawCover && !str_starts_with($rawCover, 'http')) {
                    Storage::disk('public')->delete($rawCover);
                }
                ProductImage::where('product_id', $product->id)->delete();
                DB::table('atributo_product')->where('product_id', $product->id)->delete();
                DB::table('grupo_product')->where('product_id', $product->id)->delete();
                $product->delete();
            }

            // 2. Excluir Pedidos e Itens
            $pedidos = Pedido::withoutGlobalScopes()->where('user_id', $user->id)->get();
            $pedidoIds = $pedidos->pluck('id');
            if ($pedidoIds->isNotEmpty()) {
                ItemPedido::whereIn('pedido_id', $pedidoIds)->delete();
            }
            Pedido::withoutGlobalScopes()->where('user_id', $user->id)->delete();

            // 3. Excluir Grupos
            $grupos = Grupo::withoutGlobalScopes()->where('user_id', $user->id)->get();
            foreach ($grupos as $grupo) {
                if ($grupo->foto_path && !str_starts_with($grupo->foto_path, 'http')) {
                    Storage::disk('public')->delete($grupo->foto_path);
                }
                DB::table('grupo_product')->where('grupo_id', $grupo->id)->delete();
                $grupo->delete();
            }

            // 4. Excluir Atributos
            $atributos = Atributo::withoutGlobalScopes()->where('user_id', $user->id)->get();
            foreach ($atributos as $atributo) {
                DB::table('atributo_product')->where('atributo_id', $atributo->id)->delete();
                $atributo->delete();
            }

            // 5. Excluir Catálogos
            Catalogo::where('user_id', $user->id)->delete();

            // 6. Excluir Clicks
            ProductClick::withoutGlobalScopes()->where('user_id', $user->id)->delete();
        });

        return redirect()->route('superadmin.users.show', $user)
            ->with('success', "A loja de \"{$user->name}\" foi completamente zerada! Todos os produtos, pedidos, categorias, catálogos e métricas foram removidos com sucesso.");
    }

    /**
     * Excluir Conta do Usuário Inteira (e todos os dados associados).
     */
    public function deleteUserAccount(User $user): RedirectResponse
    {
        if ($user->isSuperAdmin()) {
            return redirect()->back()->with('error', 'Ação proibida: você não pode excluir a conta do Super Administrador!');
        }

        $userName = $user->name;

        DB::transaction(function () use ($user) {
            // Executa wipe prévio
            $this->wipeAll($user);

            // Remove transações de pagamento
            PaymentTransaction::where('user_id', $user->id)->delete();

            // Remove logo
            if ($user->logo_path && !str_starts_with($user->logo_path, 'http')) {
                Storage::disk('public')->delete($user->logo_path);
            }

            // Remove tokens de API
            $user->tokens()->delete();

            // Deleta o usuário
            $user->delete();
        });

        return redirect()->route('superadmin.index')
            ->with('success', "O usuário \"{$userName}\" e toda a sua loja foram excluídos permanentemente da plataforma.");
    }
}
