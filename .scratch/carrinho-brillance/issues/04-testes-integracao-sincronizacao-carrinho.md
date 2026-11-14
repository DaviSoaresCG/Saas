# 04 — Testes de integração, sincronização reativa e validação ponta a ponta

**What to build:** Validação automatizada e testes de integração de ponta a ponta da página de carrinho modernizada. Os testes asseguram que a página renderize com elegância o layout Brillance em todos os estados (carrinho vazio com empty state e CTA, carrinho com múltiplos itens com atributos e observações, e produtos sob consulta), garantindo o correto envio de formulário, atualização assíncrona de quantidades e preservação de todas as regras multi-tenant.

**Blocked by:** 03 — Redesenho do resumo do pedido e do modal de identificação do cliente

**Status:** ready-for-agent

- [ ] Criação de testes de feature cobrindo a renderização visual da página de carrinho com tipografia `Instrument Serif`, empty state e botões pill.
- [ ] Testes de integração validando os cards de produto, atributos, observações, preço normal e sob consulta.
- [ ] Validação do modal de checkout com campos de cliente e rota de finalização.
- [ ] Testes de persistência e sincronização assíncrona no endpoint `/cart/update` e no contador `#cart-badge`.
- [ ] Execução da suíte completa de testes (`php artisan test`) com 100% de aprovação e zero regressões.
