<?php

namespace App\Http\Controllers;

use App\Models\Products;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{

    public function index($slug)
    {
        $cart = session()->get('cart', []);
        $user = app(User::class);

        return view('cart.exemplo', compact('cart', 'user'));
    }

    public function add($slug, $id)
    {   
        $product = Products::findOrFail($id);

        $cart = session()->get('cart', []);

        if(isset($cart[$id])){
            $cart[$id]['quantity']++;
        }else{
            $cart[$id] = [
                'id' => $product->id,
                'name' => $product->name,
                'value' => $product->value,
                'quantity' => 1
            ];
        }

        session()->put('cart', $cart);

        return redirect()->route('products.index', ['slug' => app(User::class)->slug])->with('success', 'Produto Adicionado');
    }

    public function remove($slug, $id)
    {
        $cart = session()->get('cart', []);

        if(isset($cart[$id])){
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return redirect()->route('cart.index', ['slug' => app(User::class)->slug]);
    }

    public function update(Request $request)
    {
        $cart = session()->get('cart', []);
        if(isset($cart[$request->id])){
            $cart[$request->id]['quantity'] = $request->quantity;

            session()->put('cart', $cart);
        }

        return redirect()->route('cart.index', ['slug' => app(User::class)->slug]);
    }

    public function clear($slug)
    {
        session()->forget('cart');

        return redirect()->route('cart.index', ['slug' => app(User::class)->slug]);
    }


    
}
