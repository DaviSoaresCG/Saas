# 02 — Redesenho editorial do cabeçalho da loja, navegação e contador do carrinho

**What to build:** O lojista e seus clientes contam com um cabeçalho superior na vitrine pública (`store-layout`) redesenhado com estética editorial Brillance. A barra superior adota efeito translúcido em vidro com desfoque de fundo (`backdrop-blur-xl`), bordas inferiores sutis, moldura sofisticada para a logomarca da loja com cantos arredondados e anel suave (ou ícone padrão ZapCatálogo com acabamento premium), nome da loja e títulos em tipografia editorial, botão pill de retorno ao Dashboard (quando aplicável) e botão do Carrinho com badge numérico integrado e refinado, além de suporte a banner elegante para o nome do catálogo ativo.

**Blocked by:** Nenhum — Ticket 01 concluído.

**Status:** completed

- [x] Redesenho da barra superior fixa (`header`) com altura otimizada, efeito `backdrop-blur-xl`, fundo `bg-[var(--bg-page)]/90` e borda `border-b border-[var(--text-base)]/10`.
- [x] Moldura da logo/avatar da loja com cantos arredondados refinados (`rounded-2xl`), fundo `bg-[var(--bg-card)]` e borda sutil, mantendo ícone com bom contraste quando não houver imagem.
- [x] Nome da loja e título da página com hierarquia tipográfica clara, destacando o nome com classe editorial `font-serif`.
- [x] Botão do Dashboard estilizado no formato pill discreto (`rounded-full border border-[var(--text-base)]/15 bg-[var(--bg-card)] text-[var(--text-base)] hover:bg-[var(--text-base)]/5 shadow-sm`).
- [x] Botão do Carrinho redesenhado em formato pill com ícone e badge numérico harmonioso (suportando contadores normais e `99+` sem quebras de layout).
- [x] Faixa ou badge central para exibição do `$catalogoName` com cantos arredondados suaves quando configurado para exibição.

