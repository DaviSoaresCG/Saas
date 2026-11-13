# 01 — Shell da página de planos e cards de precificação no Design System Brillance

**What to build:** O visitante ou lojista acessa a rota `/plans` e é recebido por uma página moderna com a identidade do Design System Brillance (`#f7f5f3`, `#37322f`, `Instrument Serif`, `Inter`), com cabeçalho responsivo (com menu mobile e identificação do usuário logado), hero persuasivo e os dois cards de planos (Mensal e Anual com destaque "Mais Popular"), com links de contratação funcionando para usuários convidados (`/register`) e autenticados (`/pagamento/pending`), acompanhado de testes de feature automatizados.

**Blocked by:** Nenhum — pode começar imediatamente.

**Status:** completed

- [x] Migração do layout base de `resources/views/plans.blade.php` para o Design System Brillance (fundo `#f7f5f3`, tipografia `Inter` e `Instrument Serif`, remoção de importmaps legados do React e classes `slate-900`/`blue-600`).
- [x] Cabeçalho (Navbar) padronizado com BrandMark ZapCatálogo, links de navegação e controles contextuais de usuário (exibição de nome e botão de logout para autenticados; botões de login e criar catálogo para visitantes).
- [x] Hero editorial com badge temática, título principal em fonte serifada e subtítulo explicativo sobre a ausência de taxas por venda.
- [x] Grade responsiva com os cards dos planos Mensal (R$ 29,90) e Anual (R$ 299,00 com badge "Mais Popular" e destaque visual em grafite `#37322f` / lima `#d7f56b`).
- [x] Botões de ação dinâmicos nos cards: direcionamento para `/pagamento/pending?plan={plan}` para usuários autenticados e `/register` para visitantes.
- [x] Testes de feature automatizados em `tests/Feature/PlansPageTest.php` validando o carregamento da página com status HTTP 200, renderização dos preços e links de contratação para visitantes e autenticados.

