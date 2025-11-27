<?php

namespace App\Http\Controllers;

use App\Models\Products;
use App\Models\User;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index($slug)
    {

        $cart = session()->get('cart', []);
        $slug = app(User::class)->slug;
        $total = 0;
        foreach($cart as $item){
            $total+= $item['value'] * $item['quantity'];
        }


        return view('cart.index', compact('cart', 'slug', 'total'));
    }

    public function add($slug, $id)
    {
        $product = Products::findOrFail($id);

        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            $cart[$id] = [
                'id' => $product->id,
                'name' => $product->name,
                'value' => $product->value,
                'path' => $product->path,
                'quantity' => 1,
            ];
        }

        session()->put('cart', $cart);

        return redirect()->route('products.index', ['slug' => app(User::class)->slug])->with('success', 'Produto Adicionado');
    }

    public function remove($slug, $id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return redirect()->route('cart.index', ['slug' => app(User::class)->slug]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:0',
        ]);
        
        $produto_id = $request->product_id;
        $quantidade = $request->quantity;

        $cart = session()->get('cart', []);

        if (isset($cart[$produto_id])) {
            if ($quantidade > 0) {
                $cart[$produto_id]['quantity'] = $quantidade;
                $total = 0;
                foreach($cart as $item){
                    $total+= $item['value'] * $item['quantity'];
                }
                //formata o total do carrinho
                $total_formatado = number_format($total, 2, ',', '.');

                //pega o total do item no carrinho
                $item_total = $cart[$produto_id]['value'] * $quantidade;
                $item_subtotal_formatado = number_format($item_total, 2, ',', '.');

                session()->put('cart', $cart);

                return response()->json([
                    'success' => true,
                    'message' => 'atualizado',
                    'new_total' => $total_formatado,
                    'item_subtotal' => $item_subtotal_formatado,
                    'quantity' => $quantidade,
                    'cartCounter' => count($cart)
                ]);
            } else {
                unset($cart[$produto_id]);

                $total = 0.00;
                if(count($cart) > 0){
                    foreach($cart as $item){
                        $total+= $item['value'] * $item['quantity'];
                    }
                }
                
                //formata o total do carrinho
                $total_formatado = number_format($total, 2, ',', '.');

                session()->put('cart', $cart);

                return response()->json([
                    'success' => true,
                    'message' => 'removido',
                    'new_total' => $total_formatado,
                    'cartCounter' => count($cart)
                ]);
            }
        }

    }

    public function clear($slug)
    {
        session()->forget('cart');

        return redirect()->route('cart.index', ['slug' => app(User::class)->slug]);
    }
}
