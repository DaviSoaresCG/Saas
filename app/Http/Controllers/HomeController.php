<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class HomeController extends Controller
{

    public function home()
    {
        $prices = [
            'monthly' => Crypt::encryptString('default|'.config('services.stripe.monthly')),
            'yearly' => Crypt::encryptString('default|'.config('services.stripe.yearly')),
            'longest' => Crypt::encryptString('default|'.config('services.stripe.longest')),
        ];

        return view('plans', compact('prices'));
    }

    public function plans()
    {
        return 'PAGINA DOS PLANOS';
    }
}
