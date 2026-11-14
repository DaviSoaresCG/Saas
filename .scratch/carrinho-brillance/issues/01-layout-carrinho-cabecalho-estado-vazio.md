# 01 — Layout do carrinho, cabeçalho e estado vazio Brillance

**What to build:** O cliente que acessa o carrinho (`/cart/index` ou via subdomínio/variante) visualiza uma página harmonizada com o design system Brillance. A navegação de topo conta com botão de retorno no estilo pill ("Voltar às compras"), o cabeçalho exibe o título editorial "Meu Carrinho" com tipografia `Instrument Serif`, e, quando o carrinho não possui produtos adicionados, é exibido um Empty State elegante com ícone suave, mensagem acolhedora e botão primário direcionando o visitante para continuar explorando o catálogo de produtos.

**Blocked by:** Nenhum — pode começar imediatamente.

**Status:** completed

- [x] Redesenho do botão superior de retorno no estilo pill (`rounded-full border border-[var(--text-base)]/15 bg-[var(--bg-card)] text-[var(--text-base)] hover:bg-[var(--text-base)]/5 shadow-sm`), mantendo comportamento de histórico ou rota fallback.
- [x] Título da página "Meu Carrinho" estilizado em tipografia editorial (`font-serif text-3xl sm:text-4xl text-[var(--text-base)] tracking-tight`).
- [x] Criação de Empty State elegante e centralizado para quando `$cart` estiver vazio, com ícone de sacola/carrinho em container suave, texto descritivo e botão CTA pill ("Explorar Produtos" / "Ver Catálogo").
- [x] Preservação da exibição do grid de itens e resumo quando houver produtos no carrinho.

