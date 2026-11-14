# 03 — Estilização da tela de processamento e aguardo de assinatura

**What to build:** O lojista que acessa o painel enquanto o webhook processa a ativação visualiza a tela `subscription_pending` repaginada no estilo Brillance (removendo referências legadas ao Mercado Pago e centralizando a experiência no Asaas), com indicador animado de verificação de pagamento, selos de conexão segura e polling contínuo para redirecionamento imediato ao dashboard assim que a loja for ativada, com validação na suíte de testes.

**Blocked by:** Nenhum — pronto para implementação (Ticket 02 concluído).

**Status:** completed

- [x] Migração de `resources/views/subscription_pending.blade.php` para o Design System Brillance (fundo `#f7f5f3`, tipografia `Inter` e `Instrument Serif`, remoção de `bg-slate-900` e menções ao Mercado Pago).
- [x] Indicador visual suave de carregamento / verificação da assinatura com micro-animação elegante.
- [x] Mensagens amigáveis e transparentes explicando o tempo de confirmação bancária do Asaas (instantâneo para Pix ou análise para cartão).
- [x] Script de polling seguro e resiliente que monitora a rota `pagamento.status` e redireciona automaticamente o lojista ao dashboard após confirmação.
- [x] Execução da suíte completa de testes (`php artisan test`) com 100% de aprovação verde e sem regressões.
