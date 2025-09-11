<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class MainController extends Controller
{
    public function loginPage()
    {
        return view('login');
    }

    public function loginSubmit($id)
    {
        //login direto
        $user = User::findOrFail($id);
        if ($user) {
            Auth::login($user);
            return redirect()->route('plans');
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }

    public function plans()
    {
        $prices = [
            "monthly" => Crypt::encryptString(env('STRIPE_PRODUCT_ID') . "|" . env('STRIPE_MONTHLY_PRICE_ID')),
            "yearly" => Crypt::encryptString(env('STRIPE_PRODUCT_ID') . "|" . env('STRIPE_YEARLY_PRICE_ID')),
            "longest" => Crypt::encryptString(env('STRIPE_PRODUCT_ID') . "|" . env('STRIPE_LONGEST_PRICE_ID'))
        ];
        return view('plans', compact('prices'));
    }

    public function planSelected($id)
    {
        // check if ID is valid
        $plan = Crypt::decryptString($id);
        if (!$plan) {
            return redirect()->route('plans');
        }

        $plan = explode('|', $plan);
        $product_id = $plan[0];
        $price_id = $plan[1];

        return auth()->user()
        ->newSubscription($product_id, $price_id)
        ->checkout([
            'success_url' => route('subscription.success'),
            'cancel_url' => route('plans'),
        ]);
    }

    public function subscriptionSuccess()
    {
        echo "Inscrição realizada com sucesso";
    }
}
