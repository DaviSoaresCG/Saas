<?php

namespace App\Http\Controllers;

use App\Models\ItemPedido;
use App\Models\Pedido;
use App\Models\User;
use Illuminate\Http\Request;

class PedidoController extends Controller
{
    public function index($slug)
    {
        $user = app(User::class);
        $pedidos = Pedido::with('iten_pedido')->paginate(10);
        $search = false;

        return view('pedidos.index', compact('pedidos', 'user', 'search'));
    }

    public function show($slug, $id)
    {
        $itens_pedido = ItemPedido::with('product')->where('pedido_id', $id)->paginate(10);
        // dd($itens_pedido);

        if (empty($itens_pedido)) {
            return redirect()->back()->with('error', 'pedido nao encontrado');
        }
        $user = app(User::class);

        return view('pedidos.show', compact('itens_pedido', 'user'));
    }

    public function finalizar($slug)
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->back()->with('error', 'Carrinho nao encontrado');
        }

        $total = 0;
        foreach ($cart as $product) {
            $total += $product['value'] * $product['quantity'];
        }

        $pedido = Pedido::create([
            'user_id' => app(User::class)->id,
            'total' => $total,
        ]);

        $produtos = '';
        $itensParaInserir = [];
        foreach ($cart as $product) {
            $itensParaInserir[] = [
                'pedido_id' => $pedido->id,
                'product_id' => $product['id'],
                'value' => $product['value'],
                'quantidade' => $product['quantity'],
                'created_at' => now(),
                'updated_at' => now(),
            ];
            $produtos .= "\n*Produto: ".$product['name'].'#'.$product['id'].'*'."\nValor: ".$product['value']."\nQuantidade: ".$product['quantity'];
        }
        // uma so ida no banco
        ItemPedido::insert($itensParaInserir);

        $whatsapp = Pedido::with('user')->findorFail($pedido->id);
        $tenantPhone = $whatsapp->user->whatsapp; // campo no banco
        $mensagem = "Olá, acabei de finalizar o pedido *#{$pedido->id}*.\nTotal: R$ {$total}".$produtos;

        // Monta a URL do WhatsApp
        $url = "https://wa.me/{$tenantPhone}?text=".urlencode($mensagem);
        session()->forget('cart');

        return view('pedidos.whatsapp', compact('pedido', 'url'));

    }

    public function search(Request $request, $slug)
    {
        $pedidos = Pedido::where('id', '=', $request->id)->get();

        if ($pedidos->isEmpty()) {
            return redirect()->back()->with('error', 'Pedido nao encontrado');
        }

        $user = app(User::class);
        $search = true;
        // dd($pedidos);

        return view('pedidos.index', compact('pedidos', 'user', 'search'));
    }
}
