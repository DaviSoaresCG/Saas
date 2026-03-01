<h1>Olá, {{ $user->name }}!</h1>
<p>Infelizmente, não conseguimos processar o pagamento da sua assinatura do Catálogo Digital.</p>
<p>Para evitar que sua loja saia do ar, clique no botão abaixo para atualizar seus dados:</p>

<a href="{{ route('billing', ['slug' => $user->slug]) }}" 
   style="background: #2563eb; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">
   Atualizar Pagamento
</a>

<p>Se tiver dúvidas, responda a este e-mail. Você ainda tem mais 3 semanas de uso antes da conta ser cancelada.</p>