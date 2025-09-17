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
        request()->session()->invalidate();
        request()->session()->regenerateToken();
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
        $user = Auth::user();
        return view('subscription_success', ['slug' => $user->slug]);
    }

    public function dashboard()
    {

        // check the experation
        $timestamp = Auth::user()
            ->subscription(env('STRIPE_PRODUCT_ID'))
            ->asStripeSubscription()
            ->current_period_end;

        $subscription_end = date('d/m/y H:i:s', $timestamp);

        // get invoices da assinatura(especifiquei o produto)
        $invoices = Auth::user()->subscription(env('STRIPE_PRODUCT_ID'))->invoices();

        $user = app(User::class);
        return view('dashboard', compact('subscription_end', 'invoices', 'user'));
    }

    public function invoiceDownload($id)
    {
        // return Auth::user()->downloadInvoice($id);

        return Auth::user()->downloadInvoice($id, [
            'vendor' => 'Silencio LTD',
            'product' => 'SILENCIO',
            'street' => 'Main Str. 1',
            'location' => '2000 Antwerp, Belgium',
            'phone' => '+32 499 00 00 00',
            'email' => 'info@example.com',
            'url' => 'https://example.com',
            'vendorVat' => 'BE123456789',
        ]);
    }
}
