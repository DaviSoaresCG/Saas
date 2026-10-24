# 02 — Sincronizar o badge do carrinho dinamicamente ao alterar quantidades na tela de carrinho

**What to build:** Garantir que as alterações de quantidade (incrementar, decrementar ou remover itens) realizadas pelo usuário na tela do carrinho (`/cart`) atualizem instantaneamente o contador do badge no cabeçalho sem recarregar a página. Caso o último item seja removido e o carrinho esvaziado, o badge deve ser ocultado automaticamente.

**Blocked by:** 01 — Exibir badge com contador de itens no ícone do carrinho no cabeçalho da loja

**Status:** completed

- [x] A rota `cart.update` (`CartController@update`) retorna a quantidade total acumulada de itens no carrinho na resposta JSON.
- [x] O script de atualização do carrinho (`cart/index.blade.php`) atualiza o texto do badge no cabeçalho com o novo total após a resposta da requisição AJAX.
- [x] Caso a contagem resultante chegue a 0, o badge é ocultado da interface.
- [x] Testes automatizados cobrem o endpoint `cart.update` validando o retorno correto da contagem total atualizada de itens.
