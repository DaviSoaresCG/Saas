<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Rename store_name to nome_loja if it exists
            if (Schema::hasColumn('users', 'store_name')) {
                $table->renameColumn('store_name', 'nome_loja');
            } else {
                $table->string('nome_loja')->nullable();
            }

            $table->string('documento')->nullable();
            $table->string('tipo_cliente')->default('direct'); // 'direct' or 'erp'
            $table->timestamp('plano_expira_em')->nullable();
            $table->string('status')->default('active'); // 'active', 'suspended', etc.
            $table->string('api_token')->unique()->nullable()->default(null);
            $table->boolean('need_change_password')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'nome_loja')) {
                $table->renameColumn('nome_loja', 'store_name');
            }
            $table->dropColumn([
                'documento',
                'tipo_cliente',
                'plano_expira_em',
                'status',
                'api_token',
                'need_change_password'
            ]);
        });
    }
};
