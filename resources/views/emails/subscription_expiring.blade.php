<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aviso de Vencimento de Assinatura</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #0f172a; margin: 0; padding: 30px; color: #f8fafc;">
    <div style="max-width: 560px; margin: 0 auto; background-color: #1e293b; border-radius: 16px; padding: 32px; border: 1px solid #334155; box-shadow: 0 10px 25px rgba(0,0,0,0.3);">
        
        <div style="text-align: center; margin-bottom: 24px;">
            <div style="display: inline-block; background-color: #2563eb; padding: 12px; border-radius: 12px;">
                <h1 style="color: #ffffff; margin: 0; font-size: 20px; font-weight: bold;">ZapCatálogo</h1>
            </div>
        </div>

        <h2 style="color: #ffffff; font-size: 20px; margin-top: 0;">Olá, {{ $user->name }}!</h2>

        <p style="color: #94a3b8; font-size: 14px; line-height: 1.6;">
            Identificamos que a assinatura da sua loja <strong style="color: #ffffff;">{{ $user->nome_loja ?: 'ZapCatálogo' }}</strong> está próxima do vencimento.
        </p>

        <div style="background-color: #334155; border-left: 4px solid #f59e0b; padding: 16px; border-radius: 8px; margin: 20px 0;">
            <p style="margin: 0; color: #fef3c7; font-size: 14px; font-weight: bold;">
                Data de Vencimento: {{ $user->plano_expira_em ? $user->plano_expira_em->format('d/m/Y') : 'Em breve' }}
            </p>
            <p style="margin: 4px 0 0 0; color: #cbd5e1; font-size: 13px;">
                Faltam {{ $diasRestantes <= 1 ? 'menos de 24 horas' : "apenas {$diasRestantes} dias" }} para o vencimento.
            </p>
        </div>

        <p style="color: #94a3b8; font-size: 14px; line-height: 1.6;">
            Para evitar a interrupção do seu catálogo e garantir que seus clientes continuem fazendo pedidos pelo WhatsApp, renove sua assinatura agora via Pix ou Cartão:
        </p>

        <div style="text-align: center; margin: 28px 0;">
            <a href="{{ route('pagamento.pending') }}" style="background-color: #2563eb; color: #ffffff; text-decoration: none; font-size: 14px; font-weight: bold; padding: 14px 28px; border-radius: 12px; display: inline-block;">
                Renovar Minha Assinatura Agora
            </a>
        </div>

        <hr style="border: none; border-top: 1px solid #334155; margin: 24px 0;">

        <p style="color: #64748b; font-size: 12px; text-align: center; margin: 0;">
            Se você já efetuou o pagamento, pode desconsiderar esta mensagem.
            <br>© ZapCatálogo Tecnologia. Todos os direitos reservados.
        </p>
    </div>
</body>
</html>
