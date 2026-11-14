# 02 — Modernização dos cards de produto e controles táteis de quantidade

**What to build:** O cliente gerencia os itens no carrinho através de cards de produto redesenhados no estilo Brillance. Cada produto exibe sua foto em moldura refinada com cantos arredondados, nome com tipografia nítida, badges sutis de atributos e variações selecionadas, seletores de quantidade ergonômicos e táteis (`-` e `+`) com micro-interações suaves, preço unitário e subtotal em destaque com `Instrument Serif` (ou badge harmoniosa "Sob Consulta"), além de ação de exclusão com ícone e hover discreto, preservando integralmente os IDs, data attributes e lógica de reatividade assíncrona com o servidor.

**Blocked by:** 01 — Layout do carrinho, cabeçalho e estado vazio Brillance

**Status:** ready-for-agent

- [ ] Redesenho da moldura dos itens (`.product-item`): fundo `bg-[var(--bg-card)]`, borda suave `border border-[var(--text-base)]/10`, cantos `rounded-2xl` e sombra sutil `shadow-sm`.
- [ ] Moldura da imagem do produto com cantos `rounded-xl`, borda sutil e proporção adequada.
- [ ] Badges de atributos/variações e observações estilizados em pill neutro (`rounded-full bg-[var(--text-base)]/5 border border-[var(--text-base)]/10 text-xs font-medium`).
- [ ] Substituição dos botões quadrados de quantidade por seletores táteis arredondados (`rounded-xl border border-[var(--text-base)]/15 bg-[var(--bg-card)] hover:bg-[var(--text-base)]/5 active:scale-95 text-[var(--text-base)]`), mantendo classes `btn-update-cart`, `data-action`, `data-product-id` e o elemento `id="quantity-{{ $id }}"`.
- [ ] Exibição de valores monetários e subtotais destacados em `font-serif` e suporte dinâmico ao status "Preço Sob Consulta".
- [ ] Botão de exclusão (`.btn-remove-item`) modernizado com ícone Lucide `trash-2` e hover suave.
