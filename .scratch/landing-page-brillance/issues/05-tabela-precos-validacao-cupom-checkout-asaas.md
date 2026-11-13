# 05 — Implementar tabela de preços mensal e anual, validação de cupom e fluxo de contratação

**What to build:** Entregar a seção de precificação e conversão comercial do ZapCatálogo conectada à lógica de negócio da aplicação. A seção apresenta dois cartões de planos: Mensal (R$ 29,90/mês) e Anual (R$ 299,00/ano destacado como "Mais Popular", com tag de economia de 17% e 2 meses grátis), cada um com lista de benefícios e botões de contratação inteligentes (se autenticado, direciona para o pagamento pendente do Asaas via `route('pagamento.pending', ['plan' => ...])`; se visitante, direciona para o cadastro). Inclui também a caixa de verificação de cupons promocionais integrada via formulário POST para `route('counpons.validate')` com token CSRF e exibição de mensagens de status.

**Blocked by:** 02 — Implementar header com autenticação, hero editorial e mockup de vitrine com WhatsApp

**Status:** ready-for-agent

- [ ] A seção de preços exibe claramente os planos Mensal (R$ 29,90) e Anual (R$ 299,00), destacando a economia do plano anual.
- [ ] Cada cartão de plano exibe a listagem completa de benefícios (produtos ilimitados, WhatsApp checkout, subdomínio próprio, métricas e 0% comissão).
- [ ] Quando um usuário autenticado clica para assinar, ele é redirecionado para `route('pagamento.pending')` com o parâmetro correspondente ao plano escolhido (`monthly` ou `yearly`).
- [ ] Quando um visitante não autenticado clica para assinar, ele é conduzido para a rota de registro (`route('register')`).
- [ ] O campo de cupom de desconto permite ao usuário submeter um código para validação contra o endpoint oficial `route('counpons.validate')`, exibindo adequadamente os retornos de sucesso ou erro vindos da sessão (`session('success')` ou `session('error')`).
- [ ] Informações sobre formas de pagamento aceitas (Pix instantâneo e Cartão de Crédito) são visíveis e transmitem segurança.

