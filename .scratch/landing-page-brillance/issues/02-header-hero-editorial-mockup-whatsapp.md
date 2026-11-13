# 02 — Implementar header com autenticação, hero editorial e mockup de vitrine com WhatsApp

**What to build:** Entregar o topo completo da landing page na estética Brillance. O visitante ou lojista visualiza um cabeçalho fixo (sticky) que reconhece o estado da sessão: usuários logados têm atalho para gerenciar sua loja, acessar o painel e efetuar logout; visitantes encontram links para entrar no painel e criar seu catálogo. Logo abaixo, a Hero Section apresenta a headline de alto impacto em serifa, badge de novidade ("ZapCatálogo 2.0 • Sem taxas por venda"), chamada para ação direta com rolagem suave ou link de cadastro, selos de confiança e o mockup duplo interativo (smartphone exibindo vitrine de produtos e balão verossímil de pedido recebido no WhatsApp Business com ID, itens e valor total).

**Blocked by:** 01 — Configurar design system Brillance com fontes, paleta e shell base da Home

**Status:** ready-for-agent

- [ ] O cabeçalho fixo exibe a marca ZapCatálogo, links de navegação para âncoras da página e botões de ação adaptados ao estado do usuário.
- [ ] Quando o usuário está autenticado (`@auth`), o cabeçalho exibe link direto para o catálogo da sua loja (`products.index`), atalho para o painel administrativo e botão/formulário funcional de logout.
- [ ] Quando o usuário é visitante (`@guest`), o cabeçalho exibe links para login e criação de conta/catálogo.
- [ ] A Hero Section exibe a headline em Instrument Serif com o estilo editorial do Brillance, subtítulo e badges de confiança ("0% de comissão", "Configuração simples", "Cancele quando quiser").
- [ ] Os botões de CTA da Hero direcionam o usuário para a área de planos/cadastro ou iniciam a rolagem para a demonstração.
- [ ] O mockup de produto exibe o smartphone estilizado com vitrine da loja e o balão realista de mensagem de pedido recebido no WhatsApp com itens e total somado.
- [ ] O conjunto Hero + Header é totalmente responsivo e adaptado para telas mobile, tablets e desktops.

