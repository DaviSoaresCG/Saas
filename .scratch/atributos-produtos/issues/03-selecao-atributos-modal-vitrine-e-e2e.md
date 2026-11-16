# 03 — Seleção dinâmica de atributos no modal da vitrine e testes ponta a ponta

**What to build:** Na vitrine da loja (`products/index`), ao clicar no botão "Adicionar ao carrinho" de um card de produto que possua atributos associados, o modal `#add-to-cart-modal` renderiza dinamicamente as opções de atributos do produto para escolha obrigatória antes de confirmar a adição. Produtos sem atributos mantêm o fluxo rápido atual de seleção de quantidade. A jornada ponta a ponta — do cadastro de atributos no admin até a finalização do pedido no WhatsApp — é coberta por testes automatizados garantindo a integridade dos dados e do layout.

**Blocked by:** 02 — Exibição e seleção obrigatória de atributos na página do produto (`products/show`)

**Status:** ready-for-agent

- [ ] Cards de produto na vitrine (`resources/views/products/index.blade.php`) passam os atributos disponíveis (`id` e `nome`) nos dados acionados pelo botão de adicionar.
- [ ] Modal de adicionar ao carrinho (`resources/views/components/store-layout.blade.php` ou script do modal) renderiza dinamicamente as pills de atributos quando o produto possuir opções, exigindo a seleção antes da submissão.
- [ ] Submissão do modal envia os atributos selecionados para `tenant_route('cart.add')` com sucesso.
- [ ] Pedido finalizado (`PedidoController::finalizar`) persiste corretamente a descrição dos atributos em `item_pedidos.atributos` e inclui os atributos selecionados na mensagem formatada enviada ao WhatsApp.
- [ ] Testes de feature automatizados cobrindo a jornada completa: produto com atributos vs. produto sem atributos, modal da vitrine, adição ao carrinho, persistência no pedido e composição do texto de WhatsApp.
