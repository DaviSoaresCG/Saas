<?php

namespace App\Http\Controllers;

use App\Jobs\EmailJob;
use App\Models\Products;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    public function plans()
    {
        $prices = [
            'monthly' => Crypt::encryptString('default|'.config('services.stripe.monthly')),
            'yearly' => Crypt::encryptString('default|'.config('services.stripe.yearly')),
            'longest' => Crypt::encryptString('default|'.config('services.stripe.longest')),
        ];

        return view('home', compact('prices'));
    }

    public function planSelected($id)
    {
        // check if ID is valid
        $plan = Crypt::decryptString($id);
        if (! $plan) {
            return redirect()->route('home');
        }

        $plan = explode('|', $plan);
        $product_id = $plan[0];
        $price_id = $plan[1];

        return Auth::user()
            ->newSubscription($product_id, $price_id)
            ->checkout([
                'locale' => 'pt-BR',
                'success_url' => route('subscription.pending'),
                'cancel_url' => route('erro'),
            ]);
    }

    public function subscriptionSuccess()
    {
        $user = Auth::user();
        // echo "AAA";
        if (! Auth::user()->subscribed()) {
            return view('subscription_pending');
        } elseif (empty(Auth::user()->slug)) {

            $slug = Str::slug(fake()->unique()->words(2, true));
            $unique_slug = $this->generateUniqueSlug($slug);
            $user->slug = $unique_slug;

            $user->updateStripeCustomer([
                'preferred_locales' => ['pt-BR'],
            ]);
            $user->save();

        }

        return view('subscription_success', ['slug' => $user->slug]);
    }

    public function subscriptionPending()
    {
        return view('subscription_pending');
    }

    public function generateUniqueSlug($slug)
    {
        $count = User::where('slug', 'LIKE', "{$slug}%")->count();

        // se count retornar um numero, $slug-n
        return $count ? "{$slug}-{$count}" : $slug;
    }

    public function gerarSlugUnicoPost(Request $request)
    {
        $request->validate([
            'slug' => ['required', 'min:2', 'string', Rule::unique('users', 'slug')->ignore(Auth::id())],
        ],
            [
                'required' => 'Preencha',
                'min' => 'Minimo de 2 caracteres',
                'string' => 'Digite letras',
                'unique' => 'Ja existe esse dominio',
            ]
        );

        //verifica se ele digitou o mesmo subdominio
        $user = Auth::user();
        if ($request->slug == $user->slug) {
            return redirect()->back()->withErrors(['slug_request' => 'O subdominio é o mesmo']);
        }

        $slug = $request->slug;
        $original_slug = $request->slug;
        $count = 1;
        while($user->where('slug', $slug)->exists()){
            $slug = $original_slug.'-'.$count++;
        }

        $user->slug = $slug;
        $user->save();

        return redirect()->away('http://'.$slug.'.'.env('APP_DOMAIN').'/profile');

    }

    public function dashboard()
    {
        $user = Auth::user();

        // check the experation
        $timestamp = Auth::user()
            ->subscription()
            ->asStripeSubscription()
            ->current_period_end;

        $subscription_end = date('d/m/y H:i:s', $timestamp);

        $invoice_upcoming = $user->upcomingInvoice();

        $invoices = Auth::user()->subscription()->invoices();

        $user = app(User::class);

        return view('admin.dashboard', compact('subscription_end', 'invoices', 'user', 'invoice_upcoming'));
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
        $link = true;

        return view('admin.products', compact('products', 'link'));
    }
}
