<?php

namespace App\Console\Commands;

use App\Mail\SubscriptionExpiringMail;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendExpiringSubscriptionNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-expiring-subscription-notifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envia notificações por e-mail para usuários com assinaturas próximas ao vencimento (3 dias e 1 dia).';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Iniciando verificação de assinaturas próximas ao vencimento...');

        // Busca clientes diretos com plano ativo que expira nos próximos 3 dias
        $users = User::where('tipo_cliente', 'direct')
            ->whereNotNull('plano_expira_em')
            ->where('plano_expira_em', '>', now())
            ->where('plano_expira_em', '<=', now()->addDays(3))
            ->get();

        $count = 0;

        foreach ($users as $user) {
            $diasRestantes = (int) ceil(now()->diffInDays($user->plano_expira_em, false));

            // Envia e-mail de notificação se faltarem exatamente 3 dias ou 1 dia
            if ($diasRestantes === 3 || $diasRestantes === 1 || $diasRestantes === 0) {
                try {
                    Mail::to($user->email)->send(new SubscriptionExpiringMail($user, $diasRestantes));
                    $this->info("E-mail de aviso enviado para {$user->email} (Faltam {$diasRestantes} dias).");
                    Log::info("E-mail de aviso de vencimento enviado para {$user->email} (Faltam {$diasRestantes} dias).");
                    $count++;
                } catch (\Exception $e) {
                    $this->error("Erro ao enviar e-mail para {$user->email}: " . $e->getMessage());
                    Log::error("Erro ao enviar e-mail de aviso para {$user->email}: " . $e->getMessage());
                }
            }
        }

        $this->info("Verificação concluída. {$count} notificações enviadas.");
    }
}
