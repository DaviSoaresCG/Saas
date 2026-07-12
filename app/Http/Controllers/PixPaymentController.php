<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PixPaymentController extends Controller
{
    /**
     * Show pending payment page.
     */
    public function pending()
    {
        $user = Auth::user();

        if ($user->isLojaAtiva()) {
            return redirect()->route('dashboard', ['slug' => $user->slug]);
        }

        return view('checkout.pending');
    }

    /**
     * Generate simulated Pix and redirect to checkout page.
     */
    public function generate(Request $request)
    {
        $request->validate([
            'plan' => 'required|in:monthly,yearly',
        ]);

        $plan = $request->plan;
        $amount = $plan === 'yearly' ? 299.00 : 29.90;
        $planName = $plan === 'yearly' ? 'Plano Anual' : 'Plano Mensal';

        // Generate simulated QR code base64 (using a standard static pixel art of QR code for simulation)
        // We will display a nice visual simulation
        $txid = uniqid('pix_');

        session([
            'pending_pix' => [
                'plan' => $plan,
                'amount' => $amount,
                'plan_name' => $planName,
                'txid' => $txid,
                'copy_paste' => '00020101021226870014br.gov.bcb.pix2565pix-qr.conecta.saas/pix/' . $txid . '5204000053039865405' . number_format($amount, 2, '.', '') . '5802BR5915ZapCatalogSaaS6006Palmas62070503***6304',
            ]
        ]);

        return redirect()->route('pagamento.checkout');
    }

    /**
     * Display Pix checkout screen.
     */
    public function checkout()
    {
        $payment = session('pending_pix');

        if (!$payment) {
            return redirect()->route('pagamento.pending');
        }

        return view('checkout.pix', compact('payment'));
    }

    /**
     * Simulate Webhook payment success for local environment.
     */
    public function simulateSuccess(Request $request)
    {
        $payment = session('pending_pix');
        $user = Auth::user();

        if (!$payment || !$user) {
            return redirect()->route('pagamento.pending')->with('error', 'Nenhum pagamento pendente encontrado.');
        }

        // Call our internal webhook logic directly to simulate
        $plan = $payment['plan'];
        $days = $plan === 'yearly' ? 365 : 30;

        $currentExpiration = $user->plano_expira_em && $user->plano_expira_em->isFuture()
            ? $user->plano_expira_em
            : now();

        $user->plano_expira_em = $currentExpiration->addDays($days);
        $user->status = 'active';
        
        // Se ainda não tiver slug (cadastro novo), gera o padrão
        if (empty($user->slug)) {
            $user->slug = Str::slug(str_replace(' ', '', $user->nome_loja ?: 'loja'));
            // Garante unicidade
            $count = User::where('slug', 'LIKE', "{$user->slug}%")->count();
            if ($count) {
                $user->slug = "{$user->slug}-{$count}";
            }
        }
        
        $user->save();

        // Limpa sessão de pagamento pendente
        session()->forget('pending_pix');

        return redirect()->route('dashboard', ['slug' => $user->slug])->with('success', 'Pagamento Pix simulado e confirmado com sucesso! Sua loja já está ativa.');
    }

    /**
     * Real API Webhook endpoint for Pix payments (e.g. Asaas/MercadoPago webhook)
     */
    public function webhook(Request $request)
    {
        Log::info('Pix Webhook received: ' . json_encode($request->all()));

        // In a real implementation, we would validate the token/secret from Asaas or Mercado Pago,
        // retrieve the txid, find the matching user, and extend their plan.
        // We'll leave the stub ready:
        
        /*
        $txid = $request->input('txid'); // or external_reference
        $status = $request->input('status'); // approved/paid
        
        if ($status === 'approved') {
            // resolve user, extend plan
        }
        */

        return response()->json(['success' => true]);
    }
}
