# 03 — Redesenho do resumo do pedido e do modal de identificação do cliente

**What to build:** O cliente visualiza o resumo da sua compra em um painel lateral flutuante e moderno no estilo Brillance, com total destacado em `Instrument Serif`, e conclui o pedido através de um modal de identificação refinado com cantos `rounded-3xl`, campos com foco suave e máscara de WhatsApp, preservando toda a reatividade assíncrona com o servidor e o redirecionamento final.

**Blocked by:** 02 — Modernização dos cards de produto e controles táteis de quantidade

**Status:** completed

- [x] Redesenho do painel lateral de resumo do pedido com cantos `rounded-3xl`, fundo `bg-[var(--bg-card)]`, borda `border border-[var(--text-base)]/10` e sombra suave.
- [x] Destaque do valor total do pedido com tipografia editorial `font-serif text-3xl text-[var(--text-base)]` e suporte a "Preço Sob Consulta" no elemento `#summary-total`.
- [x] Botão primário "Finalizar pedido" estilizado no padrão Brillance (`rounded-full bg-[var(--color-primary)] text-[var(--text-on-primary)] hover:opacity-95 active:scale-[0.98] shadow-sm`).
- [x] Redesenho do modal de identificação (`#checkout-modal`) com cantos `rounded-3xl`, backdrop `bg-black/60 backdrop-blur-md`, borda sutil e sombra profunda `shadow-2xl`.
- [x] Inputs de nome e celular/WhatsApp com cantos `rounded-xl`, borda suave e anel de foco consistente (`focus:ring-2 focus:ring-[var(--color-primary)]/15`), preservando o `x-mask`.
- [x] Preservação de todos os event listeners JS, submissão para `tenant_route('order.finished')` e reatividade de atualização do carrinho.
