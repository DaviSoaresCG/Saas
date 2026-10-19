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
            if (!Schema::hasColumn('users', 'asaas_customer_id')) {
                $table->string('asaas_customer_id')->nullable()->after('documento');
            }
        });

        Schema::table('payment_transactions', function (Blueprint $table) {
            if (!Schema::hasColumn('payment_transactions', 'subscription_id')) {
                $table->string('subscription_id')->nullable()->after('payment_id')->index();
            }
            if (!Schema::hasColumn('payment_transactions', 'invoice_url')) {
                $table->text('invoice_url')->nullable()->after('qr_code_base64');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'asaas_customer_id')) {
                $table->dropColumn('asaas_customer_id');
            }
        });

        Schema::table('payment_transactions', function (Blueprint $table) {
            $table->dropColumn(['subscription_id', 'invoice_url']);
        });
    }
};
