<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AsaasService
{
    protected string $baseUrl;
    protected string $apiKey;
    protected string $webhookToken;

    public function __construct()
    {
        $this->baseUrl = config('services.asaas.base_url', 'https://sandbox.asaas.com/api/v3');
        $this->apiKey = (string) config('services.asaas.api_key');
        $this->webhookToken = (string) config('services.asaas.webhook_token');
    }

    /**
     * Prepara a requisição HTTP com os cabeçalhos padrão do Asaas.
     */
    protected function client()
    {
        if (empty($this->apiKey)) {
            throw new \Exception('Chave de API do Asaas não está configurada no .env (ASAAS_SANDBOX_API_TOKEN ou ASAAS_API_TOKEN).');
        }

        return Http::withHeaders([
            'access_token' => $this->apiKey,
            'Content-Type' => 'application/json',
        ])->timeout(30);
    }

    /**
     * Localiza ou cria um cliente no Asaas.
     */
    public function findOrCreateCustomer(User $user, string $cpfCnpj): array
    {
        $cleanCpfCnpj = preg_replace('/\D/', '', $cpfCnpj);
        $cleanPhone = $user->whatsapp ? preg_replace('/\D/', '', $user->whatsapp) : null;

        // 1. Se o usuário já tem um asaas_customer_id salvo, tenta buscá-lo
        if (!empty($user->asaas_customer_id)) {
            $response = $this->client()->get("{$this->baseUrl}/customers/{$user->asaas_customer_id}");
            if ($response->successful()) {
                return $response->json();
            }
        }

        // 2. Tenta buscar por CPF/CNPJ existente para evitar duplicações no Asaas
        $searchResponse = $this->client()->get("{$this->baseUrl}/customers", [
            'cpfCnpj' => $cleanCpfCnpj,
        ]);

        if ($searchResponse->successful() && !empty($searchResponse->json('data.0'))) {
            $customer = $searchResponse->json('data.0');
            $user->asaas_customer_id = $customer['id'];
            $user->documento = $cleanCpfCnpj;
            $user->save();
            return $customer;
        }

        // 3. Cria um novo cliente no Asaas
        $payload = [
            'name' => $user->name,
            'cpfCnpj' => $cleanCpfCnpj,
            'email' => $user->email,
            'mobilePhone' => $cleanPhone,
            'externalReference' => (string) $user->id,
            'notificationDisabled' => false,
        ];

        Log::info("Criando cliente no Asaas para o usuário ID: {$user->id}");
        $response = $this->client()->post("{$this->baseUrl}/customers", $payload);

        if ($response->failed()) {
            $errorMsg = $response->json('errors.0.description') ?? $response->body();
            Log::error("Erro ao criar cliente no Asaas: {$errorMsg}");
            throw new \Exception("Erro ao cadastrar cliente no Asaas: {$errorMsg}");
        }

        $customer = $response->json();
        $user->asaas_customer_id = $customer['id'];
        $user->documento = $cleanCpfCnpj;
        $user->save();

        return $customer;
    }

    /**
     * Cria uma assinatura recorrente no Asaas (suporta PIX, CREDIT_CARD ou UNDEFINED).
     */
    public function createSubscription(string $customerId, string $plan, float $amount, ?string $externalReference = null, string $billingType = 'UNDEFINED'): array
    {
        $cycle = $plan === 'yearly' ? 'YEARLY' : 'MONTHLY';
        $planName = $plan === 'yearly' ? 'Plano Anual' : 'Plano Mensal';

        $payload = [
            'customer' => $customerId,
            'billingType' => $billingType,
            'value' => (float) $amount,
            'nextDueDate' => now()->format('Y-m-d'),
            'cycle' => $cycle,
            'description' => "Assinatura ZapCatalogo - {$planName}",
            'externalReference' => $externalReference,
        ];

        Log::info("Criando assinatura no Asaas para o cliente: {$customerId} - Plano: {$plan} - Tipo: {$billingType}");
        $response = $this->client()->post("{$this->baseUrl}/subscriptions", $payload);

        if ($response->failed()) {
            $errorMsg = $response->json('errors.0.description') ?? $response->body();
            Log::error("Erro ao criar assinatura no Asaas: {$errorMsg}");
            throw new \Exception("Erro ao gerar assinatura no Asaas: {$errorMsg}");
        }

        return $response->json();
    }

    /**
     * Obtém a primeira cobrança pendente de uma assinatura.
     */
    public function getFirstPaymentFromSubscription(string $subscriptionId): array
    {
        $response = $this->client()->get("{$this->baseUrl}/subscriptions/{$subscriptionId}/payments");

        if ($response->failed() || empty($response->json('data.0'))) {
            $errorMsg = $response->json('errors.0.description') ?? $response->body();
            Log::error("Erro ao buscar cobrança da assinatura {$subscriptionId}: {$errorMsg}");
            throw new \Exception("Cobrança inicial da assinatura não encontrada.");
        }

        return $response->json('data.0');
    }

    /**
     * Obtém o QR Code e código Copia e Cola Pix de uma cobrança.
     * Retorna null se não houver chave Pix configurada no Asaas.
     */
    public function getPixQrCode(string $paymentId): ?array
    {
        try {
            $response = $this->client()->get("{$this->baseUrl}/payments/{$paymentId}/pixQrCode");

            if ($response->successful()) {
                return [
                    'encodedImage' => $response->json('encodedImage'),
                    'payload' => $response->json('payload'),
                    'expirationDate' => $response->json('expirationDate'),
                ];
            }

            Log::warning("Asaas: Não foi possível obter QR Code Pix para {$paymentId}: " . $response->body());
            return null;
        } catch (\Exception $e) {
            Log::warning("Asaas: Exceção ao consultar QR Code Pix: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Consulta os detalhes de uma cobrança específica.
     */
    public function getPayment(string $paymentId): ?array
    {
        $response = $this->client()->get("{$this->baseUrl}/payments/{$paymentId}");

        if ($response->failed()) {
            return null;
        }

        return $response->json();
    }

    /**
     * Cancela uma assinatura recorrente no Asaas.
     */
    public function cancelSubscription(string $subscriptionId): array
    {
        $response = $this->client()->delete("{$this->baseUrl}/subscriptions/{$subscriptionId}");

        if ($response->failed()) {
            $errorMsg = $response->json('errors.0.description') ?? $response->body();
            Log::error("Erro ao cancelar assinatura {$subscriptionId} no Asaas: {$errorMsg}");
            throw new \Exception("Erro ao cancelar assinatura no Asaas: {$errorMsg}");
        }

        return $response->json();
    }

    /**
     * Valida se o token do webhook recebido é idêntico ao configurado.
     * Em produção, se o token não estiver configurado, rejeita por segurança.
     */
    public function isValidWebhookToken(?string $providedToken): bool
    {
        if (empty($this->webhookToken)) {
            // Em produção, nunca aceita webhook sem token configurado
            if (config('services.asaas.env') === 'production' || app()->environment('production')) {
                Log::critical('Asaas Webhook: ASAAS_WEBHOOK_TOKEN não está configurado em ambiente de produção! Bloqueando requisição por segurança.');
                return false;
            }
            return true; // Apenas em ambiente local/sandbox de desenvolvimento
        }

        return hash_equals($this->webhookToken, (string) $providedToken);
    }
}
