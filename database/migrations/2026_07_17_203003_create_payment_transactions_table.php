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
        Schema::create('payment_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('payment_id')->nullable()->unique(); // ID do pagamento no Mercado Pago
            $table->decimal('amount', 10, 2);
            $table->string('plan'); // 'monthly' ou 'yearly'
            $table->string('status')->default('pending'); // 'pending', 'approved', 'rejected', 'cancelled'
            $table->text('qr_code')->nullable(); // Código Pix Copia e Cola
            $table->text('qr_code_base64')->nullable(); // QR Code em base64 do MP
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_transactions');
    }
};
