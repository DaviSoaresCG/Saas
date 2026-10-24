# 01 — Exibir badge com contador de itens no ícone do carrinho no cabeçalho da loja

**What to build:** Permitir que o cliente veja rapidamente a quantidade total de itens adicionados ao carrinho através de um badge numérico destacado junto ao ícone de carrinho no cabeçalho da loja (`store-layout`). A contagem deve somar as quantidades de todos os itens no carrinho e só deve ser exibida quando houver ao menos um item (permanecendo oculta quando o carrinho estiver vazio). O layout deve ser responsivo e manter o alinhamento visual em dispositivos móveis e desktop.

**Blocked by:** Nenhum — pode começar imediatamente.

**Status:** completed

- [x] Componente `StoreLayout` calcula e disponibiliza a contagem total de itens no carrinho da sessão (`array_sum` das quantidades).
- [x] O ícone do carrinho no cabeçalho (`store-layout.blade.php`) exibe um badge visual (estilo e-commerce) com o valor da contagem quando for maior que 0.
- [x] O badge permanece oculto caso o carrinho esteja vazio ou tenha quantidade zero.
- [x] O posicionamento do badge é responsivo e mantém harmonia visual tanto no mobile quanto no desktop.
- [x] Testes automatizados cobrem a renderização do cabeçalho da loja com o carrinho vazio e com itens na sessão.
