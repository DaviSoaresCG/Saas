# 02 — Garantir isolamento multi-tenant em grupos/imagens e corrigir galeria na edição de produtos

**What to build:** Assegurar que o gerenciamento de grupos e imagens de um produto respeite estritamente o isolamento entre lojas (tenants) e que a adição, ordenação e remoção de imagens na edição de produtos funcionem sem conflitos de formulário ou saltos de índice.

**Blocked by:** 01 — Corrigir validações, integridade transacional e formulário de criação de produtos

**Status:** ready-for-agent

- [ ] A criação e a edição de produtos aceitam apenas grupos pertencentes ao próprio lojista autenticado, ignorando ou rejeitando IDs de outros tenants.
- [ ] A exclusão individual de imagem verifica se a imagem pertence a um produto do lojista autenticado antes de apagar o arquivo e o registro, impedindo exclusão cruzada entre tenants.
- [ ] Ao adicionar imagens a um produto que não possui nenhuma imagem cadastrada, a ordem da primeira imagem inicia em `0` e atualiza corretamente a imagem de capa.
- [ ] O upload instantâneo de novas imagens na tela de edição cria os formulários de remoção fora do formulário principal de edição do produto, mantendo o botão de salvar alterações funcional.
- [ ] O processamento de imagens em segundo plano da integração ERP mantém a galeria de imagens sincronizada com a capa do produto.
- [ ] Testes automatizados validam o bloqueio de acesso cruzado entre tenants em grupos/imagens e a correta ordenação de imagens.

