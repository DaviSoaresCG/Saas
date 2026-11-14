# 04 — Testes de integração e validação visual de ponta a ponta da vitrine da loja

**What to build:** Cobertura de testes automatizados e validação de ponta a ponta da vitrine da loja utilizando o novo layout Brillance. Os testes garantem que as páginas filhas (`products.index`, `products.show`, `cart.index`, `pedidos.whatsapp`) renderizem com precisão os novos elementos tipográficos, o header da loja, o avatar, os botões e badges de carrinho, e que o modal de adicionar ao carrinho continue operando sem regressões de front-end ou de regras de negócio multi-tenant.

**Blocked by:** Nenhum — Ticket 03 concluído.

**Status:** ready-for-agent

- [ ] Criação ou atualização de testes de feature cobrindo a renderização do `<x-store-layout>` com elementos Brillance (`Instrument Serif`, classes editoriais e tokens semânticos).
- [ ] Testes de integração validando a exibição do nome da loja, avatar com logo customizada e fallback com ícone padrão.
- [ ] Validação automatizada do badge do carrinho no header (estados vazio, contagem numérica e contagem `99+`).
- [ ] Teste de renderização do modal de adicionar ao carrinho na vitrine pública.
- [ ] Execução da suíte completa de testes (`php artisan test`) com 100% de aprovação e zero regressões.

