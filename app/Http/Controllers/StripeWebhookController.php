<?php

namespace App\Http\Controllers;

use App\Events\InscricaoConfirmada;
use Laravel\Cashier\Http\Controllers\WebhookController as CashierController;
use App\Events\SubscriptionApproved;
use App\Mail\PaymentFailedMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

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

                // O Laravel avisa a Cloudflare para criar o subdomínio com a Nuvem Laranja
                $response = Http::withToken(env('CLOUDFLARE_API_TOKEN'))
                ->post('https://api.cloudflare.com/client/v4/zones/' . env('CLOUDFLARE_ZONE_ID') . '/dns_records', [
                    'type' => 'A',
                    'name' => $user->slug, // ex: 'joao'
                    'content' => env('SERVER_IP'),  // O IP da sua VPS
                    'proxied' => true,              // ISSO AQUI LIGA A NUVEM LARANJA! ☁️🟧
                ]);

                if($response->successful()){
                    Log::info("Subdominio " .$user->slug. ".zapcatalago.com.br Criado com sucesso");
                }else{
                    Log::error("Erro ao criar subdomínio na Cloudflare: " . $response->body());         
                    }


            } else {
                Log::warning("Usuário não encontrado para o Stripe ID: {$stripeId}");
            }
        }

        // Retorna sucesso para o Stripe parar de reenviar o webhook
        return new Response('Webhook Handled', 200);
    }

    public function handleInvoicePaymentFailed(array $payload)
    {
        $stripeId = $payload['data']['object']['customer'];
        $user = User::where('stripe_id', $stripeId)->first();

        if ($user) {
            Log::warning("Pagamento falhou para o usuário: {$user->email}");

            // Aqui você dispara seu Job de e-mail ou WhatsApp
            // Exemplo enviando o Job que você já possui:
            Mail::to($user->email)->queue(new PaymentFailedMail($user));            
            // Se for usar WhatsApp via API, você chamaria seu service aqui:
            // WhatsAppService::sendMessage($user->whatsapp, "Ops! Seu pagamento falhou...");
        }

        return $this->successMethod();
    }
}