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
        Schema::table('products', function (Blueprint $table) {
            // Rename existing columns for v2.0
            if (Schema::hasColumn('products', 'name')) {
                $table->renameColumn('name', 'nome');
            }
            if (Schema::hasColumn('products', 'value')) {
                $table->renameColumn('value', 'preco_base');
            }
            if (Schema::hasColumn('products', 'path')) {
                $table->renameColumn('path', 'foto_url');
            }

            // Add new columns
            $table->string('erp_id')->nullable()->index();
            $table->string('sku')->nullable()->index();
            $table->integer('estoque')->nullable();
            $table->string('slug')->nullable()->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'nome')) {
                $table->renameColumn('nome', 'name');
            }
            if (Schema::hasColumn('products', 'preco_base')) {
                $table->renameColumn('preco_base', 'value');
            }
            if (Schema::hasColumn('products', 'foto_url')) {
                $table->renameColumn('foto_url', 'path');
            }

            $table->dropColumn([
                'erp_id',
                'sku',
                'estoque',
                'slug'
            ]);
        });
    }
};
