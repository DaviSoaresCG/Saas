<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Cliente Direto Ativo
        User::updateOrCreate(
            ['email' => 'direto.ativo@gmail.com'],
            [
                'name' => 'Lojista Direto Ativo',
                'nome_loja' => 'Loja Direto Ativa',
                'slug' => 'loja1',
                'whatsapp' => '63999999991',
                'password' => Hash::make('123123123'),
                'tipo_cliente' => 'direct',
                'status' => 'active',
                'plano_expira_em' => now()->addDays(30),
                'email_verified_at' => now(),
            ]
        );

        // 2. Cliente Direto Expirado (para testar o checkout Pix)
        User::updateOrCreate(
            ['email' => 'direto.expirado@gmail.com'],
            [
                'name' => 'Lojista Direto Expirado',
                'nome_loja' => 'Loja Direto Expirada',
                'slug' => 'loja2',
                'whatsapp' => '63999999992',
                'password' => Hash::make('123123123'),
                'tipo_cliente' => 'direct',
                'status' => 'active',
                'plano_expira_em' => now()->subDays(2),
                'email_verified_at' => now(),
            ]
        );

        // 3. Cliente vindo do ERP ConectaVenda
        User::updateOrCreate(
            ['email' => 'erp.cliente@gmail.com'],
            [
                'name' => 'Lojista Integrado ERP',
                'nome_loja' => 'Loja Integrada ERP',
                'slug' => 'joao',
                'whatsapp' => '63999999993',
                'documento' => '12345678901', // CPF/CNPJ usado como senha inicial
                'password' => Hash::make('12345678901'),
                'tipo_cliente' => 'erp',
                'status' => 'active',
                'api_token' => 'erp_test_token_123',
                'need_change_password' => true, // Obriga a redefinir a senha
                'email_verified_at' => now(),
            ]
        );
    }
}
