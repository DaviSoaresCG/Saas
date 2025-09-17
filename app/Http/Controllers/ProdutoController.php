<?php

namespace App\Http\Controllers;

use App\Models\Products;
use App\Models\User;
use Illuminate\Http\Request;

class ProdutoController extends Controller
{
    public function index()
    {
        $user = app(User::class);
        // $products = Products::all();
        return view('products.index', compact('user'));
    }
}
