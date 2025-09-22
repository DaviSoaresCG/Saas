<?php

namespace App\Http\Controllers;

use App\Models\Products;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;

class AdminController extends Controller
{

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
            return redirect()->route('home');
        }

        $plan = explode('|', $plan);
        $product_id = $plan[0];
        $price_id = $plan[1];

        return auth()->user()
            ->newSubscription($product_id, $price_id)
            ->checkout([
                'success_url' => route('subscription.pending'),
                'cancel_url' => route('erro')
            ]);
    }

    public function subscriptionSuccess()
    {
        // echo "AAA";
        if (!Auth::user()->subscribed(env('STRIPE_PRODUCT_ID'))) {
            return view('subscription_pending');
        }

        $user = Auth::user();
        $user->slug = Str::slug(fake()->unique()->words(2, true));
        $user->save();
        return view('subscription_success', ['slug' => $user->slug]);
    }

    public function subscriptionPending()
    {
        return view('subscription_pending');
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
        return view('admin.dashboard', compact('subscription_end', 'invoices', 'user'));
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

    public function getAllProducts()
    {
        $products = Products::paginate(10);
        return view('admin.products', compact('products'));
    }
}
