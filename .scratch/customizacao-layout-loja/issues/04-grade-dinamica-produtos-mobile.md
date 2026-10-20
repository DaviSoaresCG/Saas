# 04 — Grade Dinâmica de Produtos na Vitrine Mobile

**What to build:** Na vitrine de produtos da loja pública acessada via celular, a disposição dos cards de produtos reflete automaticamente a preferência configurada pelo lojista (1 coluna ou 2 colunas). Em telas maiores (tablets e desktops), o layout responsivo multi-colunas padrão é mantido intacto.

**Blocked by:** 03 — Painel de Configuração do Layout Mobile com Preview Interativo (Stitch)

**Status:** ready-for-agent

- [ ] A listagem pública de produtos consulta a configuração de layout mobile da loja/tenant.
- [ ] Em dispositivos mobile, quando configurado para 1 coluna, os cards de produtos ocupam a largura total em fila vertical (`grid-cols-1`).
- [ ] Em dispositivos mobile, quando configurado para 2 colunas, os cards de produtos são dispostos em grade de dois itens por linha (`grid-cols-2`).
- [ ] Telas desktop (`md:`, `lg:`) continuam exibindo a grade responsiva desktop normal sem regressão visual.
- [ ] Testes automatizados cobrem a alternância de classes e a renderização nos dois modos.
