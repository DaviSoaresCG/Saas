# 06 — Implementar FAQ interativo, banner de fechamento glow, rodapé e testes automatizados da Home

**What to build:** Entregar as seções de fechamento da landing page e assegurar a qualidade e estabilidade de toda a Home através de testes automatizados. O usuário encontra uma seção de FAQ em acordeão acessível (com 5 dúvidas essenciais sobre comissões, recebimento de pagamentos, subdomínio, celular e ausência de aplicativos), um banner final escuro de alta conversão com efeitos de gradiente/brilho e chamada expressiva para criar o catálogo, e um rodapé semântico refinado com marca, direitos reservados e links institucionais. Para blindar a entrega contra regressões, uma suíte de testes de feature (`tests/Feature/LandingPageHomeTest.php`) valida o carregamento da Home, a integridade dos links públicos e autenticados, a submissão de cupom e o fluxo de redirecionamento.

**Blocked by:** 03 — Implementar barra de métricas, grade de funcionalidades e comparativo de vendas, 04 — Implementar fluxo em 3 passos, banner de compra mobile e demonstração interativa do pedido, 05 — Implementar tabela de preços mensal e anual, validação de cupom e fluxo de contratação

**Status:** ready-for-agent

- [ ] A seção de FAQ implementa acordeão interativo e acessível (via Alpine.js ou `<details>` estendido) com rotação suave do ícone chevron e respostas claras às 5 dúvidas principais.
- [ ] O banner final (CTA escuro) é renderizado com visual sofisticado, efeitos luminosos nos cantos, badge explicativa e botão principal direcionando para o cadastro/preços.
- [ ] O rodapé semântico exibe a marca ZapCatálogo, ano atual dinâmico e links de navegação consistentes com as seções da página.
- [ ] O teste automatizado em Pest (`tests/Feature/LandingPageHomeTest.php`) valida que a rota `GET /` retorna status HTTP 200 para visitantes não autenticados e contém as seções principais da landing page.
- [ ] O teste automatizado valida que para usuários autenticados a Home renderiza o link para a sua loja e o atalho para o painel.
- [ ] O teste automatizado valida que os botões de plano apontam para os destinos corretos tanto para visitantes quanto para usuários logados.
- [ ] A suíte completa de testes da aplicação executa sem falhas nem regressões.

