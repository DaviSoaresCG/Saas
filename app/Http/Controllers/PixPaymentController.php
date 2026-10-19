<?php

namespace App\Http\Controllers;

/**
 * Controller legado mantido para retrocompatibilidade.
 * Todas as regras de negócio de pagamento, ciclo de vida e renovação estão centralizadas em SubscriptionController.
 */
class PixPaymentController extends SubscriptionController
{
    // Herda todos os métodos de SubscriptionController
}
