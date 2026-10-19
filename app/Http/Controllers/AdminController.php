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
        if (! $user->isLojaAtiva()) {
            return view('subscription_pending');
        } elseif (empty($user->slug)) {
            $user->slug = User::generateUniqueSlug($user->nome_loja ?: 'loja', $user->id);
            $user->save();

            // email de boas vindas
            Mail::to($user->email)->queue(new WelcomeEmail($user));
        }

        // Criação de subdomínio na Cloudflare (se configurado)
        $cloudflareToken = env('CLOUDFLARE_API_TOKEN');
        $cloudflareZoneId = env('CLOUDFLARE_ZONE_ID');
        $baseDomain = env('APP_DOMAIN', 'zapcatalogo.com.br');

        if ($cloudflareToken && $cloudflareZoneId && !empty($user->slug)) {
            $recordName = "{$user->slug}.{$baseDomain}";

            try {
                $checkResponse = Http::withToken($cloudflareToken)
                    ->get("https://api.cloudflare.com/client/v4/zones/{$cloudflareZoneId}/dns_records", [
                        'type' => 'A',
                        'name' => $recordName,
                    ]);
                Log::info("Cloudflare Check Response: {$checkResponse->body()}");

                $registros_encontrados = $checkResponse->json('result');
                if (!empty($registros_encontrados)) {
                    Log::info("Subdomínio já existe na Cloudflare: {$recordName}");
                } else {
                    Log::info("Subdomínio não existe na Cloudflare: {$recordName}, criando...");

                    $response = Http::withToken($cloudflareToken)
                        ->post("https://api.cloudflare.com/client/v4/zones/{$cloudflareZoneId}/dns_records", [
                            'type' => 'A',
                            'name' => $user->slug,
                            'content' => env('SERVER_IP'),
                            'proxied' => true,
                        ]);

                    if ($response->successful()) {
                        Log::info("Subdomínio {$recordName} criado com sucesso na Cloudflare.");
                    } else {
                        Log::error("Erro ao criar subdomínio na Cloudflare: " . $response->body());
                    }
                }
            } catch (\Exception $e) {
                Log::error("Exceção ao comunicar com Cloudflare: " . $e->getMessage());
            }
        }

        return view('subscription_success', ['slug' => $user->slug]);
    }

    public function subscriptionPending()
    {
        $user = Auth::user();
        if ($user && $user->isLojaAtiva()) {
            return redirect()->route('subscription.success');
        }

        return view('subscription_pending');
    }

    public function generateUniqueSlug($slug)
    {
        return User::generateUniqueSlug($slug, Auth::id());
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

        $user->slug = User::generateUniqueSlug($request->slug, $user->id);
        $user->save();

        return redirect()->away('https://'.$user->slug.'.'.env('APP_DOMAIN').'/profile');
    }

    public function dashboard()
    {
        $user = Auth::user();

        $subscriptionEnd = $user->plano_expira_em ? $user->plano_expira_em->format('d/m/Y H:i') : null;
        $subscriptionStatus = $user->isLojaAtiva() ? 'active' : 'inactive';

        $totalProducts = Products::count();
        $totalPedidos = Pedido::count();

        $recentPedidos = Pedido::query()
            ->with(['iten_pedido' => fn ($q) => $q->limit(3)])
            ->latest()
            ->limit(6)
            ->get();

        $topProductsByClicks = ProductClick::query()
            ->with('product:id,nome,user_id')
            ->orderByDesc('clicks')
            ->limit(6)
            ->get();

        return view('admin.dashboard', [
            'subscriptionEnd' => $subscriptionEnd,
            'subscriptionStatus' => $subscriptionStatus,
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

    public function gerarTokenAPI()
    {
        $user = auth()->user();
        $token = $user->createToken('api-token')->plainTextToken;
        $user->api_token = $token;
        $user->save();

        return redirect()->route('profile.edit')->with('status', 'Token API gerado com sucesso');
    }

    public function apiDocs()
    {
        $user = auth()->user();
        return view('admin.api_docs', compact('user'));
    }
}
