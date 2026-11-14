# 03 — Redesenho do modal de adicionar produto ao carrinho e controles de quantidade

**What to build:** Ao clicar para adicionar um produto ao carrinho em qualquer ponto da vitrine, o cliente interage com um modal de compra moderno no estilo Brillance. O container apresenta cantos arredondados generosos (`rounded-3xl`), sombra suave e borda sutil, com o preço do produto destacado em `Instrument Serif`, controles de quantidade (`-` e `+`) táteis com transições suaves, campos de observação/atributos integrados com anel de foco delicado e botão de ação primária com micro-interações de clique e feedback visual, mantendo todo o comportamento do formulário e manipulação via JavaScript existentes.

**Blocked by:** 02 — Redesenho editorial do cabeçalho da loja, navegação e contador do carrinho

**Status:** ready-for-agent

- [ ] Redesenho da estrutura do modal (`#add-to-cart-modal` e `#add-to-cart-modal-content`) com cantos `rounded-3xl`, fundo `bg-[var(--bg-card)]`, borda `border-[var(--text-base)]/10` e sombra `shadow-2xl`.
- [ ] Destaque tipográfico para o preço do produto em `font-serif` com suporte a preço normal e badge "Sob Consulta".
- [ ] Seletor de quantidade modernizado com botões `-` e `+` em estilo arredondado suave (`rounded-xl border border-[var(--text-base)]/15`) e input centralizado e responsivo.
- [ ] Campos de SKU, peso e textarea de observação com bordas arredondadas e anel de foco consistente (`focus:ring-2 focus:ring-[var(--color-primary)]/15`).
- [ ] Botão primário "Confirmar e Adicionar" com cantos `rounded-xl`, tipografia semibold e micro-interação ao clique (`active:scale-[0.98]`).
- [ ] Preservação de todos os event listeners JS (fechamento por ESC, clique fora, interceptação de formulários `/cart/add/`, preenchimento dinâmico de dados).

