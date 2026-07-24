<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\PaymentTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class PixPaymentController extends Controller
{
    /**
     * Show pending payment page.
     */
    public function pending()
    {
        $user = Auth::user();

        if ($user->isLojaAtiva()) {
            return redirect()->route('dashboard', ['slug' => $user->slug]);
        }

        return view('checkout.pending');
    }

    /**
     * Generate real Mercado Pago Pix Payment and redirect to checkout page.
     */
    public function generate(Request $request)
    {
        $request->validate([
            'plan' => 'required|in:monthly,yearly',
        ]);

        $user = Auth::user();
        $plan = $request->plan;
        
        // Preços: Mensal R$ 29,90, Anual R$ 299,00
        $amount = $plan === 'yearly' ? 299.00 : 29.90;
        $planName = $plan === 'yearly' ? 'Plano Anual' : 'Plano Mensal';

        // Evitar preferências duplicadas recentes do mesmo usuário
        $existingTransaction = PaymentTransaction::where('user_id', $user->id)
            ->where('plan', $plan)
            ->where('status', 'pending')
            ->where('created_at', '>=', now()->subMinutes(15))
            ->first();

        if ($existingTransaction && !empty($existingTransaction->qr_code)) {
            return redirect()->away($existingTransaction->qr_code);
        }

        // Criar Preferência de Checkout Pro no Mercado Pago
        try {
            $nameParts = explode(' ', trim($user->name));
            $firstName = $nameParts[0] ?? 'Cliente';
            $lastName = count($nameParts) > 1 ? implode(' ', array_slice($nameParts, 1)) : 'SaaS';

            $accessToken = config('services.mercadopago.access_token');
            if (empty($accessToken)) {
                throw new \Exception('Mercado Pago Access Token não está configurado.');
            }

            Log::info("Iniciando requisição de Checkout Pro Mercado Pago para o usuário: {$user->email}");

            $response = Http::withToken($accessToken)
                ->withHeaders([
                    'X-Idempotency-Key' => Str::uuid()->toString(),
                ])
                ->post('https://api.mercadopago.com/checkout/preferences', [
                    'items' => [
                        [
                            'title' => "Assinatura ZapCatalog - {$planName}",
                            'quantity' => 1,
                            'currency_id' => 'BRL',
                            'unit_price' => (float)$amount,
                        ]
                    ],
                    'payer' => [
                        'email' => $user->email,
                        'name' => $firstName,
                        'surname' => $lastName,
                    ],
                    'back_urls' => [
                        'success' => route('subscription.success'),
                        'pending' => route('subscription.pending'),
                        'failure' => route('pagamento.pending'),
                    ],
                    'auto_return' => 'approved',
                    'external_reference' => (string) $user->id,
                    'notification_url' => route('api.payments.pix.webhook'),
                ]);

            if ($response->failed()) {
                Log::error('Erro ao gerar Checkout Pro Mercado Pago: ' . $response->body());
                return redirect()->back()->with('error', 'Ocorreu um erro ao gerar a cobrança no Mercado Pago: ' . ($response->json('message') ?? 'Erro na API'));
            }

            $initPoint = $response->json('init_point');
            $preferenceId = $response->json('id');

            if (empty($initPoint)) {
                throw new \Exception('URL do Checkout Pro não foi retornada pelo Mercado Pago.');
            }

            // Salvar a transação no banco de dados
            PaymentTransaction::create([
                'user_id' => $user->id,
                'payment_id' => (string)$preferenceId,
                'amount' => $amount,
                'plan' => $plan,
                'status' => 'pending',
                'qr_code' => $initPoint,
                'qr_code_base64' => null,
            ]);

            // Redireciona diretamente para o Checkout Pro oficial do Mercado Pago
            return redirect()->away($initPoint);

        } catch (\Exception $e) {
            Log::error('Erro ao gerar Checkout Pro Mercado Pago: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Ocorreu um erro ao preparar o pagamento: ' . $e->getMessage());
        }
    }

    /**
     * Display Pix checkout screen.
     */
    public function checkout()
    {
        $payment = session('pending_pix');

        if (!$payment) {
            $user = Auth::user();
            $lastPending = PaymentTransaction::where('user_id', $user->id)
                ->where('status', 'pending')
                ->latest()
                ->first();
            
            if ($lastPending) {
                $payment = [
                    'plan' => $lastPending->plan,
                    'amount' => (float)$lastPending->amount,
                    'plan_name' => $lastPending->plan === 'yearly' ? 'Plano Anual' : 'Plano Mensal',
                    'txid' => $lastPending->payment_id,
                    'qr_code' => $lastPending->qr_code,
                    'qr_code_base64' => $lastPending->qr_code_base64,
                ];
                session(['pending_pix' => $payment]);
            }
        }

        if (!$payment) {
            return redirect()->route('pagamento.pending');
        }

        return view('checkout.pix', compact('payment'));
    }

    /**
     * Simulate Webhook payment success for local environment.
     */
    // public function simulateSuccess(Request $request)
    // {
    //     $payment = session('pending_pix');
    //     $user = Auth::user();

    //     if (!$payment || !$user) {
    //         return redirect()->route('pagamento.pending')->with('error', 'Nenhum pagamento pendente encontrado.');
    //     }

    //     // Simula o sucesso atualizando o banco de dados
    //     $paymentId = $payment['txid'];
    //     $transaction = PaymentTransaction::where('payment_id', $paymentId)
    //         ->where('status', 'pending')
    //         ->first();

    //     if ($transaction) {
    //         $transaction->status = 'approved';
    //         $transaction->save();
    //     }

    //     // Ativação do plano do usuário
    //     $plan = $payment['plan'];
    //     $days = $plan === 'yearly' ? 365 : 30;

    //     $currentExpiration = $user->plano_expira_em && $user->plano_expira_em->isFuture()
    //         ? $user->plano_expira_em
    //         : now();

    //     $user->plano_expira_em = $currentExpiration->addDays($days);
    //     $user->status = 'active';
        
    //     if (empty($user->slug)) {
    //         $user->slug = Str::slug(str_replace(' ', '', $user->nome_loja ?: 'loja'));
    //         $count = User::where('slug', 'LIKE', "{$user->slug}%")->count();
    //         if ($count) {
    //             $user->slug = "{$user->slug}-{$count}";
    //         }
    //     }
        
    //     $user->save();

    //     session()->forget('pending_pix');

    //     return redirect()->route('dashboard', ['slug' => $user->slug])->with('success', 'Pagamento Pix simulado e confirmado com sucesso! Sua loja já está ativa.');
    // }

    /**
     * Real API Webhook endpoint for Mercado Pago Pix payments
     */
    public function webhook(Request $request)
    {
        Log::info('Mercado Pago Webhook recebido: ' . json_encode($request->all()));

        $paymentId = null;

        // Se for notificação tipo webhooks v2
        if ($request->has('data.id')) {
            $paymentId = $request->input('data.id');
        } 
        // Se for notificação antiga IPN (topic=payment&id=123456)
        elseif ($request->input('topic') === 'payment') {
            $paymentId = $request->input('id');
        } 
        // Fallback para qualquer 'id' direto no request quando tipo for payment
        elseif ($request->input('type') === 'payment' && $request->has('id')) {
            $paymentId = $request->input('id');
        }

        if (!$paymentId) {
            Log::warning('Mercado Pago Webhook: ID do pagamento não encontrado no request.');
            return response()->json(['error' => 'Payment ID not found'], 400);
        }

        try {
            $accessToken = config('services.mercadopago.access_token');
            if (empty($accessToken)) {
                throw new \Exception('Mercado Pago Access Token não está configurado.');
            }

            // Consulta de forma segura o pagamento na API do Mercado Pago usando nosso Access Token
            $response = Http::withToken($accessToken)
                ->get("https://api.mercadopago.com/v1/payments/{$paymentId}");

            if ($response->failed()) {
                Log::error("Mercado Pago Webhook: Falha ao consultar o pagamento {$paymentId} - Status: " . $response->status() . " - Body: " . $response->body());
                return response()->json(['error' => 'Failed to retrieve payment details'], 500);
            }

            $paymentData = $response->json();
            $status = $paymentData['status'] ?? null;
            $externalReference = $paymentData['external_reference'] ?? null;

            Log::info("Mercado Pago Webhook: Pagamento {$paymentId} verificado. Status: {$status}. Ref: {$externalReference}");

            if ($status === 'approved') {
                // Tenta localizar a transação local no banco
                $transaction = PaymentTransaction::where('payment_id', $paymentId)->first();

                // Se não achou pela transação, tenta achar uma pendente do mesmo usuário
                if (!$transaction && $externalReference) {
                    $transaction = PaymentTransaction::where('user_id', $externalReference)
                        ->where('status', 'pending')
                        ->latest()
                        ->first();
                }

                if ($transaction) {
                    if ($transaction->status !== 'approved') {
                        $transaction->status = 'approved';
                        // Se não tinha o payment_id gravado, grava agora
                        if (!$transaction->payment_id) {
                            $transaction->payment_id = $paymentId;
                        }
                        $transaction->save();

                        // Atualiza o plano do usuário correspondente
                        $user = User::find($transaction->user_id);
                        if ($user) {
                            $days = $transaction->plan === 'yearly' ? 365 : 30;
                            $currentExpiration = $user->plano_expira_em && $user->plano_expira_em->isFuture()
                                ? $user->plano_expira_em
                                : now();

                            $user->plano_expira_em = $currentExpiration->addDays($days);
                            $user->status = 'active';

                            if (empty($user->slug)) {
                                $user->slug = Str::slug(str_replace(' ', '', $user->nome_loja ?: 'loja'));
                                $count = User::where('slug', 'LIKE', "{$user->slug}%")->count();
                                if ($count) {
                                    $user->slug = "{$user->slug}-{$count}";
                                }
                            }

                            $user->save();
                            Log::info("Plano do usuário ID {$user->id} ativado/estendido com sucesso via Webhook Mercado Pago.");
                        } else {
                            Log::warning("Mercado Pago Webhook: Usuário ID {$transaction->user_id} não encontrado.");
                        }
                    } else {
                        Log::info("Mercado Pago Webhook: Transação {$paymentId} já havia sido aprovada anteriormente.");
                    }
                } else {
                    // Fallback se não há transação local mas temos external_reference
                    if ($externalReference) {
                        $user = User::find($externalReference);
                        if ($user) {
                            $days = 30; // mensal como fallback padrão
                            $currentExpiration = $user->plano_expira_em && $user->plano_expira_em->isFuture()
                                ? $user->plano_expira_em
                                : now();

                            $user->plano_expira_em = $currentExpiration->addDays($days);
                            $user->status = 'active';

                            if (empty($user->slug)) {
                                $user->slug = Str::slug(str_replace(' ', '', $user->nome_loja ?: 'loja'));
                                $count = User::where('slug', 'LIKE', "{$user->slug}%")->count();
                                if ($count) {
                                    $user->slug = "{$user->slug}-{$count}";
                                }
                            }
                            $user->save();
                            Log::info("Webhook Mercado Pago: Usuário ID {$user->id} ativado via fallback external_reference.");
                        }
                    } else {
                        Log::warning("Mercado Pago Webhook: Nenhuma transação ou external_reference encontrado para o pagamento {$paymentId}.");
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error('Erro ao processar Webhook do Mercado Pago: ' . $e->getMessage());
            return response()->json(['error' => 'Internal Server Error'], 500);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Check current Pix payment status for frontend redirection.
     */
    public function checkStatus()
    {
        $payment = session('pending_pix');
        $user = Auth::user();

        if (!$user) {
            return response()->json(['status' => 'unauthenticated'], 401);
        }

        // Se não houver pagamento pendente na sessão, tentamos buscar a última transação do usuário no banco
        if (!$payment) {
            $lastTransaction = PaymentTransaction::where('user_id', $user->id)
                ->latest()
                ->first();
            
            if ($lastTransaction) {
                return response()->json([
                    'status' => $lastTransaction->status,
                ]);
            }

            return response()->json([
                'status' => $user->isLojaAtiva() ? 'approved' : 'none',
            ]);
        }

        $paymentId = $payment['txid'];
        $transaction = PaymentTransaction::where('payment_id', $paymentId)->first();

        // Se não encontramos por ID de pagamento, tentamos por ID de usuário e pendência
        if (!$transaction) {
            $transaction = PaymentTransaction::where('user_id', $user->id)
                ->where('status', 'pending')
                ->latest()
                ->first();
        }

        return response()->json([
            'status' => $transaction ? $transaction->status : 'pending',
        ]);
    }
}

