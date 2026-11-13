<?php

namespace App\Http\Controllers;

use App\Services\AsaasService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Página inicial (Landing Page).
     * Se o usuário estiver autenticado e com pagamento recém-aprovado no Asaas,
     * sincroniza a assinatura e redireciona diretamente ao Dashboard.
     */
    public function home(Request $request, AsaasService $asaasService, SubscriptionController $subscriptionController)
    {
        if (Auth::check()) {
            $user = Auth::user();

            if ($subscriptionController->syncPendingPayment($user, $asaasService)) {
                return redirect()->route('dashboard', ['slug' => $user->slug, 'welcome' => 1])
                    ->with('welcome', true)
                    ->with('success', 'Assinatura confirmada com sucesso! Bem-vindo ao ZapCatálogo.');
            }
        }

        return view('home');
    }

    /**
     * Página de Planos.
     * Sincroniza também caso o usuário autenticado acesse a página após pagar.
     */
    public function plans(Request $request, AsaasService $asaasService, SubscriptionController $subscriptionController)
    {
        if (Auth::check()) {
            $user = Auth::user();

            if ($subscriptionController->syncPendingPayment($user, $asaasService)) {
                return redirect()->route('dashboard', ['slug' => $user->slug, 'welcome' => 1])
                    ->with('welcome', true)
                    ->with('success', 'Assinatura confirmada com sucesso! Bem-vindo ao ZapCatálogo.');
            }
        }

        return view('plans');
    }
}
