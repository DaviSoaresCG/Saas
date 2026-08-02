<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Products;
use App\Models\ProductImage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Cliente Direto Ativo
        $diretoAtivo = User::updateOrCreate(
            ['email' => 'maria@gmail.com'],
            [
                'name' => 'Lojista Direto Ativo',
                'nome_loja' => 'Loja Direto Ativa',
                'slug' => 'maria',
                'whatsapp' => '63991055232',
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
        $erpUser = User::updateOrCreate(
            ['email' => 'joao@gmail.com'],
            [
                'name' => 'Lojista Integrado ERP',
                'nome_loja' => 'Loja Integrada ERP',
                'slug' => 'joao',
                'whatsapp' => '63991055232',
                'documento' => '123456789', // CPF/CNPJ usado como senha inicial
                'password' => Hash::make('123456789'),
                'tipo_cliente' => 'erp',
                'status' => 'active',
                'api_token' => 'erp_test_token_123',
                'need_change_password' => true, // Obriga a redefinir a senha
                'email_verified_at' => now(),
            ]
        );

        // Vincula o tenant ERP no container temporariamente para as regras de criação do BelongsToTenant
        app()->instance(User::class, $erpUser);

        // Massa de Produtos para o Lojista ERP
        $erpProducts = [
            [
                'erp_id' => 'erp-prod-101',
                'sku' => 'TV-55-4K',
                'nome' => 'Smart TV 55" 4K Ultra HD',
                'preco_base' => 2499.90,
                'estoque' => 8,
                'description' => 'Smart TV 55 polegadas 4K Ultra HD com inteligência artificial, HDR10 e comando de voz.',
                'foto_url' => 'https://images.unsplash.com/photo-1593305841991-05c297ba4575?auto=format&fit=crop&w=400&q=80',
                'slug' => 'smart-tv-55-4k-ultra-hd',
            ],
            [
                'erp_id' => 'erp-prod-102',
                'sku' => 'PS5-SLIM',
                'nome' => 'Console PlayStation 5 Slim',
                'preco_base' => 3999.00,
                'estoque' => 3,
                'description' => 'Console PlayStation 5 Slim com leitor de discos físico, SSD de 1TB e controle DualSense.',
                'foto_url' => 'https://images.unsplash.com/photo-1606813907291-d86efa9b94db?auto=format&fit=crop&w=400&q=80',
                'slug' => 'console-playstation-5-slim',
            ],
            [
                'erp_id' => 'erp-prod-103',
                'sku' => 'S24-ULTRA-256',
                'nome' => 'Smartphone Galaxy S24 Ultra',
                'preco_base' => 6299.00,
                'estoque' => 12,
                'description' => 'Smartphone Samsung Galaxy S24 Ultra 256GB com câmera quádrupla de 200MP e inteligência artificial Galaxy AI.',
                'foto_url' => 'https://images.unsplash.com/photo-1610945265064-0e34e5519bbf?auto=format&fit=crop&w=400&q=80',
                'slug' => 'smartphone-galaxy-s24-ultra',
            ],
            [
                'erp_id' => 'erp-prod-104',
                'sku' => 'HEADPHONE-ANC',
                'nome' => 'Fone Bluetooth Cancelamento de Ruído',
                'preco_base' => 899.90,
                'estoque' => 25,
                'description' => 'Fone de ouvido Bluetooth Over-Ear com cancelamento de ruído ativo (ANC), microfone integrado e bateria de 40h.',
                'foto_url' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&w=400&q=80',
                'slug' => 'fone-bluetooth-cancelamento-de-ruido',
            ],
        ];

        foreach ($erpProducts as $pData) {
            $product = Products::updateOrCreate(
                [
                    'user_id' => $erpUser->id,
                    'erp_id' => $pData['erp_id']
                ],
                [
                    'sku' => $pData['sku'],
                    'nome' => $pData['nome'],
                    'preco_base' => $pData['preco_base'],
                    'estoque' => $pData['estoque'],
                    'description' => $pData['description'],
                    'foto_url' => $pData['foto_url'],
                    'slug' => $pData['slug'],
                ]
            );

            ProductImage::updateOrCreate(
                [
                    'product_id' => $product->id,
                    'ordem' => 0
                ],
                [
                    'path' => $pData['foto_url']
                ]
            );
        }

        // Limpa a instância do container após a execução
        app()->forgetInstance(User::class);
    }
}
