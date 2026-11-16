# 02 — Exibição e seleção obrigatória de atributos na página do produto (`products/show`)

**What to build:** Na página de detalhes do produto (`products/show`), o cliente visualiza os atributos vinculados àquele produto como opções táteis selecionáveis em formato de pill no padrão Brillance. Se o produto possuir atributos vinculados, o cliente deve obrigatoriamente selecionar o(s) atributo(s) desejado(s) antes de adicionar o produto ao carrinho, com validação clara no front-end e no backend (`CartController::add`). Ao adicionar, os atributos selecionados são salvos no item do carrinho, exibidos no resumo e repassados para a finalização do pedido.

**Blocked by:** 01 — Associação de atributos no cadastro e edição de produtos no painel administrativo

**Status:** ready-for-agent

- [ ] `resources/views/products/show.blade.php` renderiza os atributos vinculados ao produto (`$product->atributos`) em formato de pills selecionáveis interativas com feedback visual ativo/inativo elegante no padrão Brillance.
- [ ] Validação na página do produto para impedir envio sem seleção prévia quando o produto possuir atributos, com feedback amigável orientando o cliente a escolher um atributo.
- [ ] `CartController::add` valida e processa os atributos selecionados (`atributos[]`), garantindo que pertençam ao produto e ao lojista do subdomínio/rota.
- [ ] Carrinho armazena os atributos selecionados no array da sessão e o resumo da página de carrinho (`cart/index.blade.php`) exibe as badges dos atributos escolhidos no item.
- [ ] Testes de feature cobrindo a renderização dos atributos na página do produto, a validação de seleção obrigatória e a inclusão bem-sucedida no carrinho com atributos.
