<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\PaymentTransaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;

class PixPaymentWebhookTest extends TestCase
{
    use RefreshDatabase;

    public function test_asaas_webhook_approves_payment_and_activates_user_and_slug(): void
    {
        config(['services.asaas.webhook_token' => 'SECRET_TOKEN_123']);

        $user = User::factory()->create([
            'nome_loja' => 'Moda Praia Legal',
            'slug' => null,
            'status' => 'pending',
            'plano_expira_em' => null,
        ]);

        $transaction = PaymentTransaction::create([
            'user_id' => $user->id,
            'payment_id' => 'pay_asaas_12345',
            'subscription_id' => 'sub_asaas_98765',
            'amount' => 5.00,
            'plan' => 'monthly',
            'status' => 'pending',
            'qr_code' => '00020126...payload',
        ]);

        $response = $this->withHeaders([
            'asaas-access-token' => 'SECRET_TOKEN_123',
        ])->postJson('/api/asaas/webhook', [
            'event' => 'PAYMENT_RECEIVED',
            'payment' => [
                'id' => 'pay_asaas_12345',
                'subscription' => 'sub_asaas_98765',
                'value' => 5.00,
                'status' => 'RECEIVED',
                'externalReference' => (string) $user->id,
            ],
        ]);

        $response->assertOk();
        $response->assertJson(['success' => true]);

        $transaction->refresh();
        $this->assertEquals('approved', $transaction->status);

        $user->refresh();
        $this->assertEquals('active', $user->status);
        $this->assertEquals('moda-praia-legal', $user->slug);
        $this->assertNotNull($user->plano_expira_em);
        $this->assertTrue($user->isLojaAtiva());
    }

    public function test_asaas_webhook_rejects_unauthorized_token(): void
    {
        config(['services.asaas.webhook_token' => 'SECRET_TOKEN_123']);

        $response = $this->withHeaders([
            'asaas-access-token' => 'WRONG_TOKEN',
        ])->postJson('/api/asaas/webhook', [
            'event' => 'PAYMENT_RECEIVED',
            'payment' => [
                'id' => 'pay_asaas_12345',
            ],
        ]);

        $response->assertStatus(401);
    }

    public function test_asaas_webhook_handles_overdue_payment(): void
    {
        config(['services.asaas.webhook_token' => 'SECRET_TOKEN_123']);

        $user = User::factory()->create();

        $transaction = PaymentTransaction::create([
            'user_id' => $user->id,
            'payment_id' => 'pay_overdue_1',
            'amount' => 5.00,
            'plan' => 'monthly',
            'status' => 'pending',
        ]);

        $response = $this->withHeaders([
            'asaas-access-token' => 'SECRET_TOKEN_123',
        ])->postJson('/api/asaas/webhook', [
            'event' => 'PAYMENT_OVERDUE',
            'payment' => [
                'id' => 'pay_overdue_1',
                'status' => 'OVERDUE',
            ],
        ]);

        $response->assertOk();
        $transaction->refresh();
        $this->assertEquals('payment_overdue', $transaction->status);
    }

    public function test_mercadopago_webhook_fallback(): void
    {
        config(['services.mercadopago.access_token' => 'TEST_ACCESS_TOKEN']);

        $user = User::factory()->create([
            'nome_loja' => 'Loja Super Top',
            'slug' => null,
            'status' => 'pending',
            'plano_expira_em' => null,
        ]);

        $transaction = PaymentTransaction::create([
            'user_id' => $user->id,
            'payment_id' => 'PREFERENCE_123',
            'amount' => 1.00,
            'plan' => 'monthly',
            'status' => 'pending',
            'qr_code' => 'https://mercadopago.com/init_point',
        ]);

        Http::fake([
            'https://api.mercadopago.com/v1/payments/999888' => Http::response([
                'id' => 999888,
                'status' => 'approved',
                'external_reference' => (string)$user->id,
            ], 200),
        ]);

        $response = $this->postJson('/api/payments/pix/webhook', [
            'data' => [
                'id' => '999888',
            ],
            'type' => 'payment',
        ]);

        $response->assertOk();

        $transaction->refresh();
        $this->assertEquals('approved', $transaction->status);

        $user->refresh();
        $this->assertEquals('active', $user->status);
        $this->assertEquals('loja-super-top', $user->slug);
    }

    public function test_pending_page_renders_stepper_and_plans(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'status' => 'pending',
            'plano_expira_em' => null,
            'slug' => null,
        ]);

        $response = $this->actingAs($user)->get('/pagamento/pendente');

        $response->assertOk();
        $response->assertSee('zap', false);
        $response->assertSee('catálogo', false);
        $response->assertSee('Ative o seu Catálogo Digital');
        $response->assertSee('Passo 1: Identificação', false);
        $response->assertSee('Passo 2: Plano & Pagamento', false);
        $response->assertSee('Passo 1: CPF ou CNPJ do Titular');
        $response->assertSee('Passo 2: Escolha o seu Plano');
        $response->assertSee('Plano Mensal');
        $response->assertSee('Plano Anual');
        $response->assertSee('Pagar via Pix');
        $response->assertSee('Pagar com Cartão de Crédito');
        $response->assertSee('Instrument Serif');
    }

    public function test_pending_page_preselects_plan_from_query_parameter(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'status' => 'pending',
            'plano_expira_em' => null,
            'slug' => null,
        ]);

        // Pré-seleciona plano anual
        $responseYearly = $this->actingAs($user)->get('/pagamento/pendente?plan=yearly');
        $responseYearly->assertOk();
        $responseYearly->assertSee('id="planCardYearly"', false);
        $responseYearly->assertSee('border-[#37322f] ring-2 ring-[#d7f56b]/50', false);

        // Pré-seleciona plano mensal
        $responseMonthly = $this->actingAs($user)->get('/pagamento/pendente?plan=monthly');
        $responseMonthly->assertOk();
        $responseMonthly->assertSee('id="planCardMonthly"', false);
        $responseMonthly->assertSee('border-[#37322f] ring-2 ring-[#37322f]/15', false);
    }

    public function test_renew_page_renders_brillance_stepper_and_plans(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'status' => 'active',
            'plano_expira_em' => now()->addDays(15),
            'slug' => 'loja-renovacao-teste',
            'tipo_cliente' => 'direct',
        ]);

        $response = $this->actingAs($user)->get(route('subscription.renew'));

        $response->assertOk();
        $response->assertSee('zap', false);
        $response->assertSee('catálogo', false);
        $response->assertSee('Passo 1: Identificação', false);
        $response->assertSee('Passo 2: Plano & Pagamento', false);
        $response->assertSee('Plano Mensal');
        $response->assertSee('Plano Anual');
    }

    public function test_generate_requires_documento(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'status' => 'pending',
            'plano_expira_em' => null,
            'documento' => null,
        ]);

        $response = $this->actingAs($user)->post('/pagamento/gerar', [
            'plan' => 'monthly',
            'documento' => '',
            'payment_method' => 'pix',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    public function test_generate_creates_subscription_and_redirects_to_checkout(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'status' => 'pending',
            'plano_expira_em' => null,
            'documento' => null,
        ]);

        Http::fake([
            'https://sandbox.asaas.com/api/v3/customers*' => Http::response([
                'id' => 'cus_mock_123',
                'name' => $user->name,
                'email' => $user->email,
            ], 200),
            'https://sandbox.asaas.com/api/v3/subscriptions' => Http::response([
                'id' => 'sub_mock_456',
                'customer' => 'cus_mock_123',
                'value' => 5.00,
            ], 200),
            'https://sandbox.asaas.com/api/v3/subscriptions/sub_mock_456/payments' => Http::response([
                'data' => [
                    [
                        'id' => 'pay_mock_789',
                        'value' => 5.00,
                        'invoiceUrl' => 'https://sandbox.asaas.com/i/pay_mock_789',
                    ]
                ]
            ], 200),
            'https://sandbox.asaas.com/api/v3/payments/pay_mock_789/pixQrCode' => Http::response([
                'encodedImage' => 'base64image...',
                'payload' => 'pixpayload...',
                'expirationDate' => '2026-10-19',
            ], 200),
        ]);

        $response = $this->actingAs($user)->post('/pagamento/gerar', [
            'plan' => 'monthly',
            'documento' => '12345678909',
            'payment_method' => 'pix',
        ]);

        $response->assertRedirect(route('pagamento.checkout'));
        $this->assertDatabaseHas('payment_transactions', [
            'user_id' => $user->id,
            'payment_id' => 'pay_mock_789',
            'subscription_id' => 'sub_mock_456',
            'plan' => 'monthly',
            'status' => 'pending',
        ]);
    }

    public function test_checkout_pix_page_renders_brillance_design_and_qr_code(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'status' => 'pending',
            'plano_expira_em' => null,
            'slug' => null,
        ]);

        $response = $this->actingAs($user)
            ->withSession([
                'pending_pix' => [
                    'plan' => 'monthly',
                    'amount' => 29.90,
                    'plan_name' => 'Plano Mensal',
                    'txid' => 'pay_checkout_123',
                    'qr_code' => '00020126580014br.gov.bcb.pix...',
                    'qr_code_base64' => 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII=',
                ]
            ])
            ->get(route('pagamento.checkout'));

        $response->assertOk();
        $response->assertSee('zap', false);
        $response->assertSee('catálogo', false);
        $response->assertSee('Pagamento via Pix');
        $response->assertSee('Valor a Pagar');
        $response->assertSee('29,90');
        $response->assertSee('Plano Mensal');
        $response->assertSee('Pix Copia e Cola');
        $response->assertSee('Instruções para pagamento');
        $response->assertSee('Simulação Sandbox / Local');
        $response->assertSee('Instrument Serif');

        // Em ambiente de produção o bloco de simulação não deve ser exibido
        config(['app.env' => 'production', 'services.asaas.env' => 'production']);
        $responseProd = $this->actingAs($user)
            ->withSession([
                'pending_pix' => [
                    'plan' => 'monthly',
                    'amount' => 29.90,
                    'plan_name' => 'Plano Mensal',
                    'txid' => 'pay_checkout_123',
                    'qr_code' => '00020126580014br.gov.bcb.pix...',
                    'qr_code_base64' => 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII=',
                ]
            ])
            ->get(route('pagamento.checkout'));
        $responseProd->assertOk();
        $responseProd->assertDontSee('Simulação Sandbox / Local');
    }

    public function test_payment_status_endpoint_returns_status_and_approved_flag(): void
    {
        Http::fake([
            'https://sandbox.asaas.com/api/v3/payments/*' => Http::response([
                'id' => 'pay_status_test',
                'status' => 'PENDING',
            ], 200),
        ]);

        $user = User::factory()->create([
            'email_verified_at' => now(),
            'status' => 'pending',
            'plano_expira_em' => null,
            'slug' => null,
        ]);

        $transaction = PaymentTransaction::create([
            'user_id' => $user->id,
            'payment_id' => 'pay_status_test',
            'subscription_id' => 'sub_status_test',
            'amount' => 29.90,
            'plan' => 'monthly',
            'status' => 'pending',
        ]);

        // 1. Status pendente
        $responsePending = $this->actingAs($user)
            ->withSession([
                'pending_pix' => [
                    'txid' => 'pay_status_test',
                ]
            ])
            ->getJson(route('pagamento.status'));

        $responsePending->assertOk();
        $responsePending->assertJson(['status' => 'pending']);

        // 2. Status aprovado
        $transaction->update(['status' => 'approved']);

        $responseApproved = $this->actingAs($user)
            ->withSession([
                'pending_pix' => [
                    'txid' => 'pay_status_test',
                ]
            ])
            ->getJson(route('pagamento.status'));

        $responseApproved->assertOk();
        $responseApproved->assertJson(['status' => 'approved']);
    }

    public function test_subscription_pending_page_renders_brillance_design_and_polling(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'status' => 'pending',
            'plano_expira_em' => null,
            'slug' => null,
        ]);

        $response = $this->actingAs($user)->get(route('subscription.pending'));

        $response->assertOk();
        $response->assertSee('zap', false);
        $response->assertSee('catálogo', false);
        $response->assertSee('Processando sua assinatura');
        $response->assertSee('Confirmando pagamento no Asaas');
        $response->assertDontSee('Mercado Pago');
        $response->assertDontSee('bg-slate-900');
        $response->assertSee('Conexão segura');
        $response->assertSee('Ativação automática');
        $response->assertSee('Instrument Serif');
        $response->assertSee(route('pagamento.status'), false);
        $response->assertSee('checkPaymentStatus', false);
        $response->assertSee('cartão de crédito', false);
    }

    public function test_generate_redirects_to_invoice_url_for_credit_card(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'status' => 'pending',
            'plano_expira_em' => null,
            'documento' => null,
        ]);

        Http::fake([
            'https://sandbox.asaas.com/api/v3/customers*' => Http::response([
                'id' => 'cus_mock_123',
                'name' => $user->name,
                'email' => $user->email,
            ], 200),
            'https://sandbox.asaas.com/api/v3/subscriptions' => Http::response([
                'id' => 'sub_mock_456',
                'customer' => 'cus_mock_123',
                'value' => 5.00,
            ], 200),
            'https://sandbox.asaas.com/api/v3/subscriptions/sub_mock_456/payments' => Http::response([
                'data' => [
                    [
                        'id' => 'pay_mock_789',
                        'value' => 5.00,
                        'invoiceUrl' => 'https://sandbox.asaas.com/i/pay_mock_789',
                    ]
                ]
            ], 200),
            'https://sandbox.asaas.com/api/v3/payments/pay_mock_789/pixQrCode' => Http::response([], 404),
        ]);

        $response = $this->actingAs($user)->post('/pagamento/gerar', [
            'plan' => 'yearly',
            'documento' => '12345678909',
            'payment_method' => 'credit_card',
        ]);

        $response->assertRedirect('https://sandbox.asaas.com/i/pay_mock_789');
    }

    public function test_simulate_payment_approves_and_activates_store(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'status' => 'pending',
            'plano_expira_em' => null,
            'slug' => null,
            'nome_loja' => 'Loja Teste Simulado',
        ]);

        $transaction = PaymentTransaction::create([
            'user_id' => $user->id,
            'payment_id' => 'pay_sim_123',
            'subscription_id' => 'sub_sim_123',
            'amount' => 5.00,
            'plan' => 'monthly',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($user)
            ->withSession([
                'pending_pix' => [
                    'plan' => 'monthly',
                    'amount' => 5.00,
                    'txid' => 'pay_sim_123',
                ]
            ])
            ->post('/pagamento/simular');

        $user->refresh();
        $response->assertRedirect(route('dashboard', ['slug' => $user->slug, 'welcome' => 1]));

        $transaction->refresh();
        $this->assertEquals('approved', $transaction->status);

        $user->refresh();
        $this->assertEquals('active', $user->status);
        $this->assertNotNull($user->plano_expira_em);
        $this->assertNotNull($user->slug);
    }

    public function test_active_user_can_access_renewal_page_with_renew_flag(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'status' => 'active',
            'plano_expira_em' => now()->addDays(30),
            'slug' => 'loja-ativa',
            'tipo_cliente' => 'direct',
        ]);

        // Sem flag renew, usuário ativo é redirecionado para dashboard
        $response = $this->actingAs($user)->get('/pagamento/pendente');
        $response->assertRedirect();

        // Com flag renew=1, usuário ativo acessa a tela de planos e checkout para renovação
        $responseRenew = $this->actingAs($user)->get('/pagamento/pendente?renew=1');
        $responseRenew->assertStatus(200);
        $responseRenew->assertSee('Plano Mensal');
        $responseRenew->assertSee('Plano Anual');
    }

    public function test_subscription_renew_route_renders_plans_and_adds_days_cumulatively(): void
    {
        $initialExpiration = now()->addDays(20);
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'status' => 'active',
            'plano_expira_em' => $initialExpiration,
            'slug' => 'loja-cumulativa',
            'tipo_cliente' => 'direct',
        ]);

        // Acessa a rota limpa /assinatura/renovar
        $response = $this->actingAs($user)->get(route('subscription.renew'));
        $response->assertStatus(200);
        $response->assertSee('Plano Mensal');

        // Cria uma transação de renovação mensal (+30 dias)
        $transaction = PaymentTransaction::create([
            'user_id' => $user->id,
            'payment_id' => 'pay_renew_test',
            'plan' => 'monthly',
            'status' => 'pending',
            'amount' => 29.90,
        ]);

        // Executa a aprovação de renovação
        $controller = new \App\Http\Controllers\SubscriptionController();
        $controller->approveTransactionAndUser($transaction, $user, 'pay_renew_test');

        $user->refresh();
        $this->assertEquals('active', $user->status);
        // A nova data deve ser exatamente initialExpiration + 30 dias (isto é, ~50 dias a partir de agora)
        $expectedDays = (int) ceil(now()->diffInDays($user->plano_expira_em, false));
        $this->assertGreaterThanOrEqual(49, $expectedDays);
    }

    public function test_webhook_is_idempotent_and_does_not_duplicate_expiration_days(): void
    {
        config(['services.asaas.webhook_token' => 'valid_token_123']);
        $initialExpiration = now()->addDays(10);
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'status' => 'active',
            'plano_expira_em' => $initialExpiration,
            'slug' => 'loja-idempotente',
            'tipo_cliente' => 'direct',
        ]);

        $transaction = PaymentTransaction::create([
            'user_id' => $user->id,
            'payment_id' => 'pay_idem_123',
            'plan' => 'monthly',
            'status' => 'pending',
            'amount' => 29.90,
        ]);

        $payload = [
            'event' => 'PAYMENT_RECEIVED',
            'payment' => [
                'id' => 'pay_idem_123',
                'value' => 29.90,
            ]
        ];

        // 1ª Notificação
        $resp1 = $this->withHeaders(['asaas-access-token' => 'valid_token_123'])
            ->postJson('/api/asaas/webhook', $payload);
        $resp1->assertStatus(200);

        $user->refresh();
        $firstExpiration = $user->plano_expira_em;

        // 2ª Notificação idêntica (retry ou PAYMENT_CONFIRMED)
        $payload['event'] = 'PAYMENT_CONFIRMED';
        $resp2 = $this->withHeaders(['asaas-access-token' => 'valid_token_123'])
            ->postJson('/api/asaas/webhook', $payload);
        $resp2->assertStatus(200);

        $user->refresh();
        // A data NÃO pode ter sido duplicada
        $this->assertEquals($firstExpiration->toDateTimeString(), $user->plano_expira_em->toDateTimeString());
    }

    public function test_simulation_is_forbidden_in_production_environment(): void
    {
        config(['services.asaas.env' => 'production']);
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->withSession([
                'pending_pix' => [
                    'plan' => 'monthly',
                    'amount' => 29.90,
                    'txid' => 'pay_hack_123',
                ]
            ])
            ->post('/pagamento/simular');

        $response->assertStatus(403);
    }

    public function test_cancel_subscription_invokes_asaas_delete_endpoint(): void
    {
        $user = User::factory()->create(['status' => 'active']);
        PaymentTransaction::create([
            'user_id' => $user->id,
            'payment_id' => 'pay_to_cancel',
            'subscription_id' => 'sub_cancel_999',
            'amount' => 29.90,
            'plan' => 'monthly',
            'status' => 'approved',
        ]);

        Http::fake([
            'https://sandbox.asaas.com/api/v3/subscriptions/sub_cancel_999' => Http::response(['deleted' => true], 200),
        ]);

        $response = $this->actingAs($user)->post(route('subscription.cancel'));
        $response->assertRedirect();
        $response->assertSessionHas('success');
    }

    public function test_home_page_syncs_pending_payment_with_asaas_and_redirects_to_dashboard(): void
    {
        $user = User::factory()->create([
            'nome_loja' => 'Loja da Home',
            'status' => 'pending',
            'plano_expira_em' => null,
            'slug' => null,
        ]);

        $transaction = PaymentTransaction::create([
            'user_id' => $user->id,
            'payment_id' => 'pay_home_sync_123',
            'amount' => 29.90,
            'plan' => 'monthly',
            'status' => 'pending',
        ]);

        Http::fake([
            'https://sandbox.asaas.com/api/v3/payments/pay_home_sync_123' => Http::response([
                'id' => 'pay_home_sync_123',
                'status' => 'CONFIRMED',
            ], 200),
        ]);

        $response = $this->actingAs($user)->get('/');

        $user->refresh();
        $transaction->refresh();

        $this->assertEquals('approved', $transaction->status);
        $this->assertEquals('active', $user->status);
        $this->assertNotNull($user->plano_expira_em);
        $this->assertNotNull($user->slug);

        $response->assertRedirect(route('dashboard', ['slug' => $user->slug, 'welcome' => 1]));
        $response->assertSessionHas('welcome', true);
    }

    public function test_home_page_renders_normally_when_payment_is_not_confirmed(): void
    {
        $user = User::factory()->create([
            'nome_loja' => 'Loja Ainda Pendente',
            'status' => 'pending',
            'plano_expira_em' => null,
        ]);

        PaymentTransaction::create([
            'user_id' => $user->id,
            'payment_id' => 'pay_still_pending_456',
            'amount' => 29.90,
            'plan' => 'monthly',
            'status' => 'pending',
        ]);

        Http::fake([
            'https://sandbox.asaas.com/api/v3/payments/pay_still_pending_456' => Http::response([
                'id' => 'pay_still_pending_456',
                'status' => 'PENDING',
            ], 200),
        ]);

        $response = $this->actingAs($user)->get('/');

        $response->assertOk();
        $response->assertViewIs('home');
    }
}



