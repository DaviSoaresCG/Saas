<?php

namespace App\Http\Controllers;

use App\Mail\WelcomeEmail;
use App\Models\Pedido;
use App\Models\ProductClick;
use App\Models\Products;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
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
        if (! $user->subscribed()) {
            return view('subscription_pending');
        } elseif (empty($user->slug)) {

            $store_name = str_replace(' ', '', $user->store_name);
            $slug = Str::slug($store_name);
            $unique_slug = $this->generateUniqueSlug($slug);
            $user->slug = $unique_slug;

            $user->updateStripeCustomer([
                'preferred_locales' => ['pt-BR'],
            ]);
            $user->save();
            // email de boas vindas
            Mail::to($user->email)->queue(new WelcomeEmail($user));
        }

        // antes de cirar o subdominio, verifico se ja exite
        $checkResponse = Http::withToken(env('CLOUDFLARE_API_TOKEN'))
            ->get('https://api.cloudflare.com/client/v4/zones/'.env('CLOUDFLARE_ZONE_ID').'/dns_records', [
                'type' => 'A',
                'name' => "{$user->slug}.zapcatalago.com.br",
            ]);
            Log::info("Check Response: {$checkResponse->body()}");

        $registros_encontrados = $checkResponse->json('result');
        if (! empty($registros_encontrados)) {
            Log::info("Subdominio ja existe: {$user->slug}");
        } else {
            Log::info("Subdominio nao existe: {$user->slug}, criando...");

            // criação do subdominio
            $response = Http::withToken(env('CLOUDFLARE_API_TOKEN'))
                ->post('https://api.cloudflare.com/client/v4/zones/'.env('CLOUDFLARE_ZONE_ID').'/dns_records', [
                    'type' => 'A',
                    'name' => $user->slug,
                    'content' => env('SERVER_IP'),
                    'proxied' => true,
                ]);

            if ($response->successful()) {
                Log::info('Subdominio '.$user->slug.'.zapcatalago.com.br Criado com sucesso');
            } else {
                Log::error('Erro ao criar subdomínio na Cloudflare: '.$response->body());

                return 'Erro ao criar o subdominio, entre em contato com a equipe técnica: 63 991055232';
            }

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
            'slug' => ['required', 'min:3', 'string', Rule::unique('users', 'slug')->ignore(Auth::id())],
        ]);

        // verifica se ele digitou o mesmo subdominio
        $user = Auth::user();
        if ($request->slug == $user->slug) {
            return redirect()->back()->withErrors(['slug_request' => 'O subdominio é o mesmo'])->with('error', 'O subdominio é o mesmo');
        }

        $slug = $request->slug;
        $original_slug = $request->slug;
        $count = 1;
        while ($user->where('slug', $slug)->exists()) {
            $slug = $original_slug.'-'.$count++;
        }

        $user->slug = $slug;
        $user->save();

        return redirect()->away('http://'.$slug.'.'.env('APP_DOMAIN').'/profile');

    }

   public function dashboard()
    {
        $user = Auth::user();

        $subscriptionEnd = null;
        $subscriptionStatus = 'inactive';
        $stripeStatus = null;
        $invoiceUpcoming = null;
        $recentInvoices = collect(); // Inicializa vazio (proteção contra quebras)

        try {
            if ($user->subscribed()) {
                $subscription = $user->subscription();
                if ($subscription) {
                    $stripeStatus = $subscription->stripe_status;
                    $subscriptionStatus = match (true) {
                        in_array($stripeStatus, ['canceled', 'cancelled'], true) => 'cancelled',
                        $stripeStatus === 'active' => 'active',
                        $stripeStatus === 'trialing' => 'trialing',
                        $stripeStatus === 'past_due' => 'past_due',
                        default => 'other',
                    };
                    $stripeSub = $subscription->asStripeSubscription();
                    if ($stripeSub && $stripeSub->current_period_end) {
                        $subscriptionEnd = date('d/m/Y H:i', $stripeSub->current_period_end);
                    }
                }
                
                // Cache da Próxima Fatura
                $invoiceUpcoming = Cache::remember("stripe_invoices_upcoming_{$user->id}", 43200, function () use ($user){
                    return $user->upcomingInvoice();
                });
                
                // CORREÇÃO AQUI: Usar a variável $recentInvoices
                $recentInvoices = Cache::remember("stripe_invoices_{$user->id}", 43200, function () use ($user){
                    return $user->invoicesIncludingPending()->take(8);
                });
            }
        } catch (\Throwable $e) {
            Log::warning('Dashboard: falha ao carregar dados Stripe', [
                'user_id' => $user->id,
                'message' => $e->getMessage(),
            ]);
            // Opcional: Você pode forçar a limpeza do cache caso o erro do Stripe seja persistente
            // Cache::forget("stripe_invoices_upcoming_{$user->id}");
            // Cache::forget("stripe_invoices_{$user->id}");
        }

        $totalProducts = Products::count();
        $totalPedidos = Pedido::count();

        $recentPedidos = Pedido::query()
            ->with(['iten_pedido' => fn ($q) => $q->limit(3)])
            ->latest()
            ->limit(6)
            ->get();

        $topProductsByClicks = ProductClick::query()
            ->with('product:id,name,user_id')
            ->orderByDesc('clicks')
            ->limit(6)
            ->get();

        return view('admin.dashboard', [
            'subscriptionEnd' => $subscriptionEnd,
            'subscriptionStatus' => $subscriptionStatus,
            'stripeStatus' => $stripeStatus,
            'invoiceUpcoming' => $invoiceUpcoming,
            'recentInvoices' => $recentInvoices, // CORREÇÃO AQUI
            'totalProducts' => $totalProducts,
            'totalPedidos' => $totalPedidos,
            'recentPedidos' => $recentPedidos,
            'topProductsByClicks' => $topProductsByClicks,
            'tenant' => app(User::class),
        ]);
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
