<?php

namespace App\Http\Controllers;

use App\Events\InscricaoConfirmada;
use Laravel\Cashier\Http\Controllers\WebhookController as CashierController;
use App\Events\SubscriptionApproved;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class StripeWebhookController extends CashierController
{
    /**
     * Lida com o evento 'invoice.payment_succeeded' do Stripe.
     */
    public function handleInvoicePaymentSucceeded($payload)
    {
        Log::info('Webhook Stripe recebido: invoice.payment_succeeded');

        // REMOVIDO: $response = parent::handleInvoicePaymentSucceeded($payload);
        // O Cashier padrão não tem esse método, por isso estava dando erro.

        // Lógica de WebSocket
        $stripeId = $payload['data']['object']['customer'] ?? null;

        if ($stripeId) {
            $user = User::where('stripe_id', $stripeId)->first();

            if ($user) {
                Log::info("Pagamento confirmado para o usuário ID: {$user->id}. Disparando WebSocket...");
                InscricaoConfirmada::dispatch($user);
            } else {
                Log::warning("Usuário não encontrado para o Stripe ID: {$stripeId}");
            }
        }

        // Retorna sucesso para o Stripe parar de reenviar o webhook
        return new Response('Webhook Handled', 200);
    }
}