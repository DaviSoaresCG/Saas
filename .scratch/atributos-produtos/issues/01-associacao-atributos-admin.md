# 01 — Associação de atributos no cadastro e edição de produtos no painel administrativo

**What to build:** O lojista (com perfil de produtos diretos) acessa as telas de cadastro e edição de produto (`admin/create_product` e `admin/edit_product`) e visualiza seus atributos cadastrados organizados em seletores visuais ou pills selecionáveis elegantes no padrão Brillance. O lojista pode selecionar múltiplos atributos (ex: "Cor Vermelha", "TAM 37", "TAM 38") para vincular ao produto ou desmarcar para desvincular. Ao salvar ou atualizar o produto, o `ProdutoController` valida que os atributos pertencem ao lojista autenticado e sincroniza os registros na tabela pivô `atributo_product`.

**Blocked by:** Nenhum — pode começar imediatamente.

**Status:** completed

- [x] `ProdutoController::create` e `ProdutoController::edit` carregam os atributos pertencentes ao lojista autenticado (`$user->atributos`) e, no caso da edição, identificam os atributos já vinculados ao produto (`$product->atributos->pluck('id')->toArray()`).
- [x] Formulário de criação de produtos (`resources/views/admin/create_product.blade.php`) exibe seção visual com os atributos disponíveis do lojista com opção de marcar/desmarcar de forma intuitiva, mantendo estado caso haja erro de validação (`old('atributos', [])`).
- [x] Formulário de edição de produtos (`resources/views/admin/edit_product.blade.php`) exibe os atributos disponíveis, marcando como selecionados aqueles atualmente vinculados ao produto, com suporte a `old('atributos')`.
- [x] `ProdutoController::store` e `ProdutoController::update` realizam validação e sincronização segura dos atributos via `$product->atributos()->sync(...)`, garantindo que apenas atributos do próprio lojista autenticado possam ser vinculados.
- [x] Se o lojista não tiver nenhum atributo cadastrado na loja, a interface exibe um aviso suave ou link convidando a cadastrar atributos em `/admin/atributos`.
- [x] Testes automatizados de controller/feature validando criação, edição e isolamento multi-tenant da vinculação de atributos.
