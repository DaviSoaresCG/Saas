<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\PaymentTransaction;
use App\Services\AsaasService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class SubscriptionController extends Controller
{
    /**
     * Rota amigável para renovação ou alteração de plano pelo lojista.
     */
    public function renew(Request $request, AsaasService $asaasService)
    {
        $request->merge(['renew' => 1]);
        return $this->pending($request, $asaasService);
    }

    /**
     * Exibe a tela de pagamento pendente/renovação e sincroniza com o Asaas se já pago.
     */
    public function pending(Request $request, AsaasService $asaasService)
    {
        $user = Auth::user();

        // 1. Verifica se há transação pendente recente e sincroniza com o Asaas
        $lastPending = PaymentTransaction::where('user_id', $user->id)
            ->where('status', 'pending')
            ->latest()
            ->first();

        if ($lastPending && !empty($lastPending->payment_id)) {
            try {
                $asaasPayment = $asaasService->getPayment($lastPending->payment_id);
                if ($asaasPayment && in_array($asaasPayment['status'] ?? '', ['RECEIVED', 'CONFIRMED'])) {
                    $this->approveTransactionAndUser($lastPending, $user, $lastPending->payment_id);
                    return redirect()->route('dashboard', ['slug' => $user->slug, 'welcome' => 1])
                        ->with('welcome', true)
                        ->with('success', 'Assinatura confirmada com sucesso! Bem-vindo ao ZapCatálogo.');
                }
            } catch (\Exception $e) {
                Log::warning("Erro ao sincronizar status Asaas em pending(): " . $e->getMessage());
            }
        }

        // Se a loja já está ativa e o usuário NÃO está pedindo renovação explícita, vai para o dashboard
        if ($user->isLojaAtiva() && !$request->has('renew') && !$request->has('plan')) {
            return redirect()->route('dashboard', ['slug' => $user->slug]);
        }

        return view('checkout.pending');
    }

    /**
     * Ativa o plano e o slug do usuário ao confirmar pagamento, SOMANDO dias cumulativamente.
     *
     * Regra de Negócio:
     * - Plano Mensal: +30 dias.
     * - Plano Anual: +365 dias.
     * - Se a assinatura atual ainda estiver ativa (no futuro), os dias são somados à data existente.
     * - Se a assinatura já estiver vencida ou vazia, os dias são somados a partir de agora (now()).
     */
    public function approveTransactionAndUser(PaymentTransaction $transaction, User $user, ?string $paymentId = null): void
    {
        // Trava de Idempotência: Se a transação já foi aprovada no banco, não soma dias novamente
        if ($transaction->exists && $transaction->status === 'approved') {
            Log::info("Asaas: Transação ID {$transaction->id} já havia sido aprovada. Evitando soma duplicada de dias.");
            return;
        }

        $transaction->status = 'approved';
        if ($paymentId) {
            $transaction->payment_id = $paymentId;
        }
        $transaction->save();

        $plan = $transaction->plan ?: 'monthly';
        $days = ($plan === 'yearly') ? 365 : 30;

        $currentExpiration = $user->plano_expira_em && $user->plano_expira_em->isFuture()
            ? $user->plano_expira_em
            : now();

        $user->plano_expira_em = $currentExpiration->addDays($days);
        $user->status = 'active';

        if (empty($user->slug)) {
            $user->slug = User::generateUniqueSlug($user->nome_loja ?: $user->name, $user->id);
        }

        $user->save();
        Log::info("Asaas: Pagamento aprovado. Usuário ID {$user->id} ({$user->email}) ativado/renovado até: {$user->plano_expira_em->format('d/m/Y H:i')}");
    }

    /**
     * Gera a cobrança da assinatura no Asaas (Pix ou Cartão de Crédito).
     */
    public function generate(Request $request, AsaasService $asaasService)
    {
        $request->validate([
            'plan' => 'required|in:monthly,yearly',
            'documento' => 'nullable|string',
            'payment_method' => 'nullable|in:pix,credit_card',
        ]);

        $user = Auth::user();
        $plan = $request->plan;
        $paymentMethod = $request->input('payment_method', 'pix');

        if ($request->filled('documento')) {
            $user->documento = preg_replace('/\D/', '', $request->documento);
            $user->save();
        }

        if (empty($user->documento)) {
            return redirect()->route('pagamento.pending', ['plan' => $plan, 'renew' => 1])
                ->with('error', 'Por favor, informe seu CPF ou CNPJ para emissão da cobrança.')
                ->withInput();
        }

        // Preços: Mensal R$ 29,90, Anual R$ 299,00 (Em sandbox, R$ 5,00 devido à regra de valor mínimo do Asaas)
        $amount = config('services.asaas.env') === 'sandbox'
            ? 5.00
            : ($plan === 'yearly' ? 299.00 : 29.90);
        $planName = $plan === 'yearly' ? 'Plano Anual' : 'Plano Mensal';

        // Evitar faturas duplicadas se houver uma pendente recente nos últimos 30 min
        $existingTransaction = PaymentTransaction::where('user_id', $user->id)
            ->where('plan', $plan)
            ->where('status', 'pending')
            ->where('created_at', '>=', now()->subMinutes(30))
            ->latest()
            ->first();

        if ($existingTransaction) {
            if ($paymentMethod === 'credit_card' && !empty($existingTransaction->invoice_url)) {
                return redirect()->away($existingTransaction->invoice_url);
            }

            if (!empty($existingTransaction->qr_code_base64) || !empty($existingTransaction->qr_code)) {
                session([
                    'pending_pix' => [
                        'plan' => $existingTransaction->plan,
                        'amount' => (float)$existingTransaction->amount,
                        'plan_name' => $planName,
                        'txid' => $existingTransaction->payment_id,
                        'qr_code' => $existingTransaction->qr_code,
                        'qr_code_base64' => $existingTransaction->qr_code_base64,
                        'invoice_url' => $existingTransaction->invoice_url,
                    ]
                ]);
                return redirect()->route('pagamento.checkout');
            }
        }

        try {
            Log::info("Iniciando assinatura/renovação no Asaas para: {$user->email} - Plano: {$plan} - Método: {$paymentMethod}");

            // 1. Localiza ou cria o cliente no Asaas
            $customer = $asaasService->findOrCreateCustomer($user, $user->documento);

            // 2. Cria a assinatura no Asaas
            $subscription = $asaasService->createSubscription($customer['id'], $plan, $amount, (string)$user->id, 'UNDEFINED');

            // 3. Obtém a fatura gerada
            $payment = $asaasService->getFirstPaymentFromSubscription($subscription['id']);

            // 4. Obtém o QR Code Pix da fatura
            $pixData = $asaasService->getPixQrCode($payment['id']);

            $qrCodePayload = $pixData['payload'] ?? null;
            $qrCodeBase64 = $pixData['encodedImage'] ?? null;
            $invoiceUrl = $payment['invoiceUrl'] ?? null;

            // 5. Salva a transação localmente
            PaymentTransaction::create([
                'user_id' => $user->id,
                'payment_id' => (string)$payment['id'],
                'subscription_id' => (string)$subscription['id'],
                'amount' => $amount,
                'plan' => $plan,
                'status' => 'pending',
                'qr_code' => $qrCodePayload ?: $invoiceUrl,
                'qr_code_base64' => $qrCodeBase64,
                'invoice_url' => $invoiceUrl,
            ]);

            // Se o usuário optou por Cartão de Crédito, redireciona diretamente para o Checkout Seguro do Asaas
            if ($paymentMethod === 'credit_card' && !empty($invoiceUrl)) {
                return redirect()->away($invoiceUrl);
            }

            // Se obteve QR code direto, exibe na tela interna de checkout Pix
            if ($qrCodeBase64 || $qrCodePayload) {
                session([
                    'pending_pix' => [
                        'plan' => $plan,
                        'amount' => $amount,
                        'plan_name' => $planName,
                        'txid' => $payment['id'],
                        'qr_code' => $qrCodePayload,
                        'qr_code_base64' => $qrCodeBase64,
                        'invoice_url' => $invoiceUrl,
                    ]
                ]);

                return redirect()->route('pagamento.checkout');
            }

            // Fallback: se não obteve QR code direto, redireciona para a fatura web do Asaas
            if (!empty($invoiceUrl)) {
                return redirect()->away($invoiceUrl);
            }

            return redirect()->route('pagamento.checkout');

        } catch (\Exception $e) {
            Log::error('Erro ao gerar assinatura/renovação no Asaas: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Ocorreu um erro ao preparar o pagamento: ' . $e->getMessage());
        }
    }

    /**
     * Exibe a tela com o QR Code Pix para pagamento.
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
                if (empty($lastPending->qr_code_base64) && !empty($lastPending->invoice_url)) {
                    return redirect()->away($lastPending->invoice_url);
                }

                $payment = [
                    'plan' => $lastPending->plan,
                    'amount' => (float)$lastPending->amount,
                    'plan_name' => $lastPending->plan === 'yearly' ? 'Plano Anual' : 'Plano Mensal',
                    'txid' => $lastPending->payment_id,
                    'qr_code' => $lastPending->qr_code,
                    'qr_code_base64' => $lastPending->qr_code_base64,
                    'invoice_url' => $lastPending->invoice_url,
                ];
                session(['pending_pix' => $payment]);
            }
        }

        if (!$payment) {
            return redirect()->route('pagamento.pending', ['renew' => 1]);
        }

        return view('checkout.pix', compact('payment'));
    }

    /**
     * Endpoint para polling frontend (verifica se a fatura já foi paga no Asaas).
     */
    public function checkStatus(AsaasService $asaasService)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['status' => 'unauthenticated'], 401);
        }

        $payment = session('pending_pix');
        $paymentId = $payment['txid'] ?? null;

        $transaction = null;
        if ($paymentId) {
            $transaction = PaymentTransaction::where('payment_id', $paymentId)
                ->where('user_id', $user->id)
                ->first();
        }

        if (!$transaction) {
            $transaction = PaymentTransaction::where('user_id', $user->id)
                ->where('status', 'pending')
                ->latest()
                ->first();
        }

        if ($transaction) {
            if ($transaction->status === 'approved') {
                return response()->json(['status' => 'approved']);
            }

            if ($transaction->status === 'pending' && !empty($transaction->payment_id)) {
                try {
                    $asaasPayment = $asaasService->getPayment($transaction->payment_id);
                    if ($asaasPayment && in_array($asaasPayment['status'] ?? '', ['RECEIVED', 'CONFIRMED'])) {
                        $this->approveTransactionAndUser($transaction, $user, $transaction->payment_id);
                        return response()->json(['status' => 'approved']);
                    }
                } catch (\Exception $e) {
                    // Ignora falha de rede temporária
                }
            }

            return response()->json([
                'status' => $transaction->status,
            ]);
        }

        // Se não há transação pendente, retorna se a loja já está ativa
        return response()->json([
            'status' => $user->isLojaAtiva() ? 'approved' : 'none',
        ]);
    }

    /**
     * Simula o pagamento de sucesso para testes em ambiente local / sandbox.
     */
    public function simulateSuccess(Request $request)
    {
        // Proteção de Segurança: Bloqueia simulação em ambiente de produção
        if (config('services.asaas.env') === 'production' || app()->environment('production')) {
            abort(403, 'Ação não permitida em ambiente de produção.');
        }

        $payment = session('pending_pix');
        $user = Auth::user();

        if (!$payment || !$user) {
            return redirect()->route('pagamento.pending', ['renew' => 1])->with('error', 'Nenhum pagamento pendente encontrado.');
        }

        $paymentId = $payment['txid'];
        $transaction = PaymentTransaction::where('payment_id', $paymentId)
            ->where('status', 'pending')
            ->first();

        if ($transaction) {
            $this->approveTransactionAndUser($transaction, $user, $paymentId);
        } else {
            $virtualTrans = PaymentTransaction::create([
                'user_id' => $user->id,
                'payment_id' => $paymentId,
                'amount' => 5.00,
                'plan' => $payment['plan'] ?? 'monthly',
                'status' => 'pending',
            ]);
            $this->approveTransactionAndUser($virtualTrans, $user, $paymentId);
        }

        session()->forget('pending_pix');

        return redirect()->route('dashboard', ['slug' => $user->slug, 'welcome' => 1])
            ->with('welcome', true)
            ->with('success', 'Assinatura confirmada com sucesso! Bem-vindo ao ZapCatálogo.');
    }

    /**
     * Cancela a renovação automática da assinatura no Asaas.
     */
    public function cancel(AsaasService $asaasService)
    {
        $user = Auth::user();
        $lastApproved = PaymentTransaction::where('user_id', $user->id)
            ->where('status', 'approved')
            ->whereNotNull('subscription_id')
            ->latest()
            ->first();

        if (!$lastApproved || empty($lastApproved->subscription_id)) {
            return redirect()->back()->with('error', 'Nenhuma assinatura ativa encontrada para cancelamento.');
        }

        try {
            $asaasService->cancelSubscription($lastApproved->subscription_id);
            Log::info("Assinatura {$lastApproved->subscription_id} cancelada no Asaas para o usuário {$user->id}");
            return redirect()->back()->with('success', 'Renovação automática cancelada com sucesso. Seu plano permanecerá ativo até a data de expiração.');
        } catch (\Exception $e) {
            Log::error("Erro ao cancelar assinatura no Asaas: " . $e->getMessage());
            return redirect()->back()->with('error', 'Não foi possível cancelar a assinatura no momento: ' . $e->getMessage());
        }
    }

    /**
     * Webhook unificado para Asaas e fallback legado do Mercado Pago.
     */
    public function webhook(Request $request, AsaasService $asaasService)
    {
        // 1. Processamento de Webhook do Asaas
        if ($request->has('event')) {
            Log::info('Asaas Webhook recebido: ' . json_encode($request->all()));

            $providedToken = $request->header('asaas-access-token');
            if (!$asaasService->isValidWebhookToken($providedToken)) {
                Log::warning('Asaas Webhook: Token de autenticação inválido.');
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            $event = $request->input('event');
            $paymentData = $request->input('payment', []);
            $paymentId = $paymentData['id'] ?? null;
            $subscriptionId = $paymentData['subscription'] ?? null;
            $externalReference = $paymentData['externalReference'] ?? null;

            if (in_array($event, ['PAYMENT_RECEIVED', 'PAYMENT_CONFIRMED'])) {
                $transaction = null;
                if ($paymentId) {
                    $transaction = PaymentTransaction::where('payment_id', $paymentId)->first();
                }
                if (!$transaction && $subscriptionId) {
                    $transaction = PaymentTransaction::where('subscription_id', $subscriptionId)
                        ->where('status', 'pending')
                        ->latest()
                        ->first();
                }
                if (!$transaction && $externalReference) {
                    $transaction = PaymentTransaction::where('user_id', $externalReference)
                        ->where('status', 'pending')
                        ->latest()
                        ->first();
                }

                $userId = $transaction ? $transaction->user_id : $externalReference;
                if (!$userId && $subscriptionId) {
                    $existingSub = PaymentTransaction::where('subscription_id', $subscriptionId)->latest()->first();
                    $userId = $existingSub ? $existingSub->user_id : null;
                }

                $user = $userId ? User::find($userId) : null;

                if ($user) {
                    if (!$transaction) {
                        // É uma renovação recorrente automática gerada pelo Asaas
                        $prevPlan = PaymentTransaction::where('user_id', $user->id)->latest()->value('plan') ?: 'monthly';
                        $transaction = PaymentTransaction::create([
                            'user_id' => $user->id,
                            'payment_id' => $paymentId,
                            'subscription_id' => $subscriptionId,
                            'amount' => (float) ($paymentData['value'] ?? ($prevPlan === 'yearly' ? 299.00 : 29.90)),
                            'plan' => $prevPlan,
                            'status' => 'pending',
                        ]);
                    }

                    $this->approveTransactionAndUser($transaction, $user, $paymentId);
                } else {
                    Log::warning("Asaas Webhook: Usuário não localizado para pagamento {$paymentId}.");
                }
            } elseif (in_array($event, ['PAYMENT_OVERDUE', 'PAYMENT_DELETED', 'PAYMENT_REFUNDED'])) {
                $transaction = PaymentTransaction::where('payment_id', $paymentId)->first();
                if (!$transaction && $subscriptionId) {
                    $transaction = PaymentTransaction::where('subscription_id', $subscriptionId)->first();
                }
                if ($transaction) {
                    $transaction->status = strtolower($event);
                    $transaction->save();
                    Log::info("Asaas Webhook: Transação {$paymentId} atualizada para {$event}.");

                    // Se foi estornado ou chargeback, suspende a loja para evitar uso indevido sem pagamento
                    if ($event === 'PAYMENT_REFUNDED') {
                        $user = User::find($transaction->user_id);
                        if ($user) {
                            $user->status = 'suspended';
                            $user->plano_expira_em = now();
                            $user->save();
                            Log::warning("Asaas Webhook: Loja do usuário {$user->id} ({$user->email}) suspensa por estorno (PAYMENT_REFUNDED).");
                        }
                    }
                }
            }

            return response()->json(['success' => true]);
        }

        // 2. Fallback: Webhook do Mercado Pago (legado)
        Log::info('Mercado Pago Webhook recebido: ' . json_encode($request->all()));

        $paymentId = null;
        if ($request->has('data.id')) {
            $paymentId = $request->input('data.id');
        } elseif ($request->input('topic') === 'payment') {
            $paymentId = $request->input('id');
        } elseif ($request->input('type') === 'payment' && $request->has('id')) {
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

            $response = Http::withToken($accessToken)
                ->get("https://api.mercadopago.com/v1/payments/{$paymentId}");

            if ($response->failed()) {
                Log::error("Mercado Pago Webhook: Falha ao consultar pagamento {$paymentId}");
                return response()->json(['error' => 'Failed to retrieve payment details'], 500);
            }

            $paymentData = $response->json();
            $status = $paymentData['status'] ?? null;
            $externalReference = $paymentData['external_reference'] ?? null;

            if ($status === 'approved') {
                $transaction = PaymentTransaction::where('payment_id', $paymentId)->first();
                if (!$transaction && $externalReference) {
                    $transaction = PaymentTransaction::where('user_id', $externalReference)
                        ->where('status', 'pending')
                        ->latest()
                        ->first();
                }

                if ($transaction) {
                    $user = User::find($transaction->user_id);
                    if ($user) {
                        $this->approveTransactionAndUser($transaction, $user, (string)$paymentId);
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error('Erro ao processar Webhook do Mercado Pago: ' . $e->getMessage());
            return response()->json(['error' => 'Internal Server Error'], 500);
        }

        return response()->json(['success' => true]);
    }
}
